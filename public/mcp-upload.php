<?php
// Product images/video upload page (token link, MCP se banta hai).
// Location: public_html/public/mcp-upload.php
error_reporting(0);
ini_set('display_errors', '0');
$lib = is_file(__DIR__ . '/../mcp-lib.php') ? __DIR__ . '/../mcp-lib.php' : __DIR__ . '/mcp-lib.php';
require $lib;

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex');

function h($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }

$token = isset($_GET['t']) ? $_GET['t'] : '';
$pid = mcp_check_token($token);
if (!$pid) {
    http_response_code(403);
    echo '<!doctype html><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><body style="font-family:sans-serif;padding:24px"><h2>Link invalid ya expire ho gaya</h2><p>Claude se naya upload link maango.</p>';
    exit;
}

$db = mcp_db();
$st = $db->prepare('SELECT id, name, video FROM vendor_products WHERE id=?');
$st->execute([$pid]);
$prod = $st->fetch();
if (!$prod) { http_response_code(404); echo 'Product nahi mila.'; exit; }

$msg = [];
$allowedImg = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
$allowedVid = ['mp4' => 1, 'mov' => 1, 'avi' => 1, 'webm' => 1];

function mcp_normalize_files($f)
{
    $out = [];
    if (!isset($f['name'])) return $out;
    if (!is_array($f['name'])) {
        if ($f['error'] !== UPLOAD_ERR_NO_FILE) $out[] = $f;
        return $out;
    }
    foreach ($f['name'] as $i => $n) {
        if ($f['error'][$i] === UPLOAD_ERR_NO_FILE) continue;
        $out[] = ['name' => $n, 'tmp_name' => $f['tmp_name'][$i], 'error' => $f['error'][$i], 'size' => $f['size'][$i]];
    }
    return $out;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (empty($_FILES) && !empty($_SERVER['CONTENT_LENGTH'])) {
            throw new Exception('Files hosting ki upload limit (post_max_size) se bari hain. Chhoti files ya kam images ek saath bhejein.');
        }
        $count = $db->prepare('SELECT COUNT(*) FROM vendor_product_images WHERE product_id=?');
        $count->execute([$pid]);
        $have = (int) $count->fetchColumn();

        if (!empty($_POST['replace_images'])) {
            $old = $db->prepare('SELECT image_path FROM vendor_product_images WHERE product_id=?');
            $old->execute([$pid]);
            foreach ($old->fetchAll() as $r) mcp_delete_file('images', $r['image_path']);
            $db->prepare('DELETE FROM vendor_product_images WHERE product_id=?')->execute([$pid]);
            $have = 0;
        }

        $imgs = isset($_FILES['images']) ? mcp_normalize_files($_FILES['images']) : [];
        if ($imgs) {
            if ($have + count($imgs) > 10) throw new Exception('Maximum 10 images. Abhi ' . $have . ' hain, aap ' . count($imgs) . ' aur de rahe hain.');
            $fi = new finfo(FILEINFO_MIME_TYPE);
            $added = 0;
            foreach ($imgs as $f) {
                if ($f['error'] !== UPLOAD_ERR_OK) throw new Exception($f['name'] . ': upload error ' . $f['error']);
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                if (!isset($allowedImg[$ext]) || $fi->file($f['tmp_name']) !== $allowedImg[$ext]) throw new Exception($f['name'] . ': sirf jpg/png/webp image.');
                if ($f['size'] > 10 * 1024 * 1024) throw new Exception($f['name'] . ': 10MB se bari.');
                $name = mcp_save_file($f['tmp_name'], 'images', $ext === 'jpeg' ? 'jpg' : $ext);
                $primary = ($have + $added) === 0 ? 1 : 0;
                $db->prepare('INSERT INTO vendor_product_images (product_id, image_path, is_primary) VALUES (?,?,?)')->execute([$pid, $name, $primary]);
                $added++;
            }
            $msg[] = ['ok', "$added images upload ho gayi."];
        }

        $vid = isset($_FILES['video']) ? mcp_normalize_files($_FILES['video']) : [];
        if ($vid) {
            $f = $vid[0];
            if ($f['error'] !== UPLOAD_ERR_OK) throw new Exception('Video upload error ' . $f['error'] . ' (file bari ho sakti hai).');
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!isset($allowedVid[$ext])) throw new Exception('Video mp4/mov/avi/webm ho.');
            if ($f['size'] > 50 * 1024 * 1024) throw new Exception('Video 50MB se choti ho.');
            $name = mcp_save_file($f['tmp_name'], 'videos', $ext);
            if (!empty($prod['video'])) mcp_delete_file('videos', $prod['video']);
            $db->prepare('UPDATE vendor_products SET video=? WHERE id=?')->execute([$name, $pid]);
            $prod['video'] = $name;
            $msg[] = ['ok', 'Video upload ho gayi.'];
        }
        if (!$imgs && !$vid && empty($_POST['replace_images'])) $msg[] = ['err', 'Koi file select nahi ki.'];
        mcp_audit("upload page product #$pid images=" . count($imgs) . ' video=' . ($vid ? 1 : 0));
    } catch (Throwable $e) {
        $msg[] = ['err', $e->getMessage()];
    }
}

$im = $db->prepare('SELECT image_path, is_primary FROM vendor_product_images WHERE product_id=? ORDER BY is_primary DESC, id');
$im->execute([$pid]);
$images = $im->fetchAll();
$n = count($images);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex">
<title>Upload Product Media</title>
<style>
 body{font-family:system-ui,sans-serif;background:#f6f6f8;margin:0;padding:16px;color:#222}
 .card{max-width:720px;margin:0 auto;background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,.08)}
 h1{font-size:1.2rem;margin:0 0 4px} .muted{color:#666;font-size:.9rem}
 .btn{background:#2563eb;color:#fff;border:0;padding:12px 18px;border-radius:8px;font-size:1rem;cursor:pointer;width:100%}
 .btn:disabled{opacity:.6} .pick{display:block;border:2px dashed #aaa;border-radius:10px;padding:18px;text-align:center;margin:10px 0;cursor:pointer}
 .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:8px;margin:12px 0}
 .grid img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:8px;border:1px solid #ddd}
 .ok{background:#e7f7ec;color:#116b2f;padding:10px;border-radius:8px;margin:8px 0}
 .err{background:#fdeaea;color:#a11;padding:10px;border-radius:8px;margin:8px 0}
 .tag{display:inline-block;font-size:.75rem;background:#2563eb;color:#fff;border-radius:4px;padding:1px 6px}
</style>
</head>
<body>
<div class="card">
 <h1>Product #<?= (int) $pid ?>: <?= h($prod['name']) ?></h1>
 <div class="muted">Images abhi: <b><?= $n ?></b> (5 se 10 chahiye) &middot; Video: <b><?= !empty($prod['video']) ? 'hai' : 'nahi' ?></b></div>
 <?php foreach ($msg as $m): ?><div class="<?= $m[0] ?>"><?= h($m[1]) ?></div><?php endforeach; ?>

 <?php if ($images): ?>
 <div class="grid">
  <?php foreach ($images as $i): ?>
   <div><img src="/storage/vendor/products/images/<?= h($i['image_path']) ?>" alt=""><?php if ($i['is_primary']): ?><span class="tag">main</span><?php endif; ?></div>
  <?php endforeach; ?>
 </div>
 <?php endif; ?>

 <form method="post" enctype="multipart/form-data" id="f">
  <label class="pick">Images select karein (ek hi baar me 5-10, jpg/png/webp)
   <input type="file" name="images[]" id="imgs" accept="image/jpeg,image/png,image/webp" multiple style="display:block;margin:10px auto 0">
  </label>
  <div class="grid" id="prev"></div>
  <?php if ($n): ?><label><input type="checkbox" name="replace_images" value="1"> Purani saari images hata kar naye sire se upload karein</label><br><br><?php endif; ?>
  <label class="pick">Video (mp4/mov, 50MB tak)
   <input type="file" name="video" accept="video/mp4,video/quicktime,video/webm,video/x-msvideo" style="display:block;margin:10px auto 0">
  </label>
  <button class="btn" id="b" type="submit">Upload karein</button>
  <p class="muted">Upload ke baad Claude ko batayein ke ho gaya.</p>
 </form>
</div>
<script>
 var imgs=document.getElementById('imgs'),prev=document.getElementById('prev');
 imgs.addEventListener('change',function(){prev.innerHTML='';Array.prototype.forEach.call(imgs.files,function(f){var i=document.createElement('img');i.src=URL.createObjectURL(f);prev.appendChild(i);});});
 document.getElementById('f').addEventListener('submit',function(){var b=document.getElementById('b');b.disabled=true;b.textContent='Upload ho raha hai... intezar karein';});
</script>
</body>
</html>
