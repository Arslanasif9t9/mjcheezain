<?php
// MCP server (Streamable HTTP, JSON). Standalone: Laravel boot nahi hota.
// Location: public_html/public/mcp.php   URL: https://mjcheezain.com/mcp.php?key=SECRET
error_reporting(0);
ini_set('display_errors', '0');

$lib = is_file(__DIR__ . '/../mcp-lib.php') ? __DIR__ . '/../mcp-lib.php' : __DIR__ . '/mcp-lib.php';
require $lib;

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function out($code, $data = null)
{
    http_response_code($code);
    if ($data !== null) echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;
}

// ---------- auth ----------
$secret = (string) mcp_cfg('secret');
$given = isset($_GET['key']) ? (string) $_GET['key'] : '';
if ($given === '' && !empty($_SERVER['HTTP_AUTHORIZATION']) && stripos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ') === 0) {
    $given = trim(substr($_SERVER['HTTP_AUTHORIZATION'], 7));
}
if ($secret === '' || strlen($secret) < 24 || !hash_equals($secret, $given)) {
    out(401, ['error' => 'unauthorized']);
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'OPTIONS') out(204);
if ($method === 'DELETE') out(200, new stdClass());
if ($method !== 'POST') out(405, ['error' => 'POST only']);

$raw = file_get_contents('php://input');
$req = json_decode($raw, true);
if (!is_array($req)) out(400, ['jsonrpc' => '2.0', 'id' => null, 'error' => ['code' => -32700, 'message' => 'Parse error']]);

// ---------- helpers ----------
function ok_text($data, $isError = false)
{
    $txt = is_string($data) ? $data : json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
    if (strlen($txt) > 150000) $txt = substr($txt, 0, 150000) . '... [TRUNCATED: LIMIT/SUBSTRING use karo]';
    return ['content' => [['type' => 'text', 'text' => $txt]], 'isError' => $isError];
}
function fail($msg, $extra = [])
{
    return ok_text(array_merge(['error' => $msg], $extra), true);
}
function arg($a, $k, $d = null)
{
    return (isset($a[$k]) && $a[$k] !== '') ? $a[$k] : $d;
}
function words($s) { return count(preg_split('/\s+/u', trim(strip_tags((string) $s)), -1, PREG_SPLIT_NO_EMPTY)); }

function single_statement($sql)
{
    $len = strlen($sql);
    $q = null;
    for ($i = 0; $i < $len; $i++) {
        $c = $sql[$i];
        if ($q) {
            if ($c === '\\' && $q !== '`') { $i++; continue; }
            if ($c === $q) $q = null;
        } elseif ($c === "'" || $c === '"' || $c === '`') {
            $q = $c;
        } elseif ($c === ';') {
            if (trim(substr($sql, $i + 1)) !== '') return false;
        }
    }
    return true;
}

function resolve_vendor($v)
{
    $db = mcp_db();
    $v = trim((string) $v);
    if ($v === '') return [null, 'vendor (id / email / username / name) zaroori hai'];
    if (ctype_digit($v)) {
        $st = $db->prepare("SELECT user_id, username, full_name, email, status FROM users WHERE type='vendor' AND user_id=?");
        $st->execute([$v]);
    } else {
        $st = $db->prepare("SELECT user_id, username, full_name, email, status FROM users WHERE type='vendor' AND (email=? OR username=? OR full_name=?)");
        $st->execute([$v, $v, $v]);
    }
    $rows = $st->fetchAll();
    if (count($rows) === 1) return [$rows[0], null];
    if (count($rows) === 0) return [null, "Vendor '$v' nahi mila (type='vendor' wale users me). list_vendors se dhoondo."];
    return [null, 'Ek se zyada vendors mile, user_id se batao: ' . json_encode($rows, JSON_UNESCAPED_UNICODE)];
}

function state_fields($state)
{
    switch ($state) {
        case 'draft':    return ['status' => 'draft', 'position' => 'draft'];
        case 'pending':  return ['status' => 'pending', 'position' => 'pending'];
        case 'approved': return ['status' => 'pending', 'position' => 'approved']; // dump ke approved products ka pattern
        case 'rejected': return ['status' => 'pending', 'position' => 'rejected'];
        case 'inactive': return ['status' => 'inactive', 'position' => 'inactive'];
    }
    return null;
}

$ATTR_COLS = ['fashion_attributes', 'jewelry_attributes', 'fragrance_attributes', 'bags_attributes', 'gym_attributes', 'kitchen_attributes', 'smarthome_attributes', 'personalcare_attributes'];

function check_product(array $f, $strict)
{
    $errs = [];
    foreach (['name', 'category', 'subcategory', 'brand', 'shipping_method', 'shipping_time', 'location'] as $k) {
        if (!isset($f[$k]) || trim((string) $f[$k]) === '') $errs[] = "$k khali hai";
    }
    if (!isset($f['quantity']) || (int) $f['quantity'] < 1) $errs[] = 'quantity kam az kam 1';
    if (!isset($f['pcondition']) || !in_array($f['pcondition'], ['New', 'Used', 'Refurbished'], true)) $errs[] = 'condition New/Used/Refurbished me se ho';
    if (!isset($f['shipping_method']) || !in_array($f['shipping_method'], ['standard', 'express', 'local'], true)) $errs[] = 'shipping_method standard/express/local me se ho';
    if (!isset($f['selling_price']) || (float) $f['selling_price'] <= 0) $errs[] = 'selling_price > 0 ho';
    if (isset($f['description']) && words($f['description']) < 100) $errs[] = 'description 100+ words ki ho (abhi ' . words($f['description']) . ')';
    if (isset($f['mrp']) && $f['mrp'] !== null && isset($f['selling_price']) && (float) $f['mrp'] < (float) $f['selling_price'] * 1.17) {
        $errs[] = 'MRP >= selling + 17% GST ho (kam az kam ' . round((float) $f['selling_price'] * 1.17, 2) . ')';
    }
    return $errs;
}

function product_summary($id)
{
    $db = mcp_db();
    $st = $db->prepare('SELECT * FROM vendor_products WHERE id=?');
    $st->execute([$id]);
    $p = $st->fetch();
    if (!$p) return null;
    $im = $db->prepare('SELECT id, image_path, is_primary FROM vendor_product_images WHERE product_id=? ORDER BY is_primary DESC, id');
    $im->execute([$id]);
    $p['images'] = $im->fetchAll();
    return $p;
}

// ---------- tools ----------
$TOOLS = [
    ['name' => 'run_sql', 'description' => 'Live DB par koi bhi SINGLE SQL query chalao (SELECT/INSERT/UPDATE/DELETE/SHOW/DESCRIBE). Read me rows wapas aati hain (default limit 100). DROP/TRUNCATE/ALTER ya WHERE ke bina UPDATE/DELETE ke liye confirm=true chahiye, jo sirf user ki explicit ijazat ke baad lagao. Write se pehle user ko batao kitni rows affect hongi.',
        'inputSchema' => ['type' => 'object', 'properties' => [
            'sql' => ['type' => 'string'], 'sql_b64' => ['type' => 'string', 'description' => 'sql ki jagah base64 (agar hosting firewall block kare)'],
            'params' => ['type' => 'array', 'items' => new stdClass(), 'description' => '? placeholders ki values'],
            'limit' => ['type' => 'integer'], 'confirm' => ['type' => 'boolean']]]],
    ['name' => 'list_vendors', 'description' => "users table se type='vendor' wale vendors. search: naam/email/username ka hissa.",
        'inputSchema' => ['type' => 'object', 'properties' => ['search' => ['type' => 'string']]]],
    ['name' => 'list_categories', 'description' => 'site_categories + site_subcategories (product me category/subcategory ka NAAM store hota hai).',
        'inputSchema' => ['type' => 'object', 'properties' => ['search' => ['type' => 'string']]]],
    ['name' => 'get_product', 'description' => 'Ek product ki poori detail + images.',
        'inputSchema' => ['type' => 'object', 'properties' => ['id' => ['type' => 'integer']], 'required' => ['id']]],
    ['name' => 'search_products', 'description' => 'Products dhoondo: q (naam/brand), vendor, state (draft/pending/approved/rejected/inactive), limit.',
        'inputSchema' => ['type' => 'object', 'properties' => ['q' => ['type' => 'string'], 'vendor' => ['type' => 'string'], 'state' => ['type' => 'string'], 'limit' => ['type' => 'integer']]]],
    ['name' => 'create_product', 'description' => "Naya product banao. Default state=draft (images baad me upload link se). Response me upload_url hota hai, wo user ko do. Site ke rules check hote hain: description 100+ words, MRP >= selling+17% GST, condition New/Used/Refurbished, shipping_method standard/express/local. draft me ye sirf warnings hain, pending/approved me errors (force=true se skip). Category-specific JSON ke liye attributes={\"fashion_attributes\":{...}} (pehle usi category ka koi mojooda product run_sql se dekh kar shape copy karo).",
        'inputSchema' => ['type' => 'object', 'properties' => [
            'vendor' => ['type' => 'string', 'description' => 'user_id / email / username / full_name'],
            'name' => ['type' => 'string'], 'category' => ['type' => 'string'], 'subcategory' => ['type' => 'string'],
            'quantity' => ['type' => 'integer'], 'brand' => ['type' => 'string'], 'model' => ['type' => 'string'],
            'condition' => ['type' => 'string'], 'selling_price' => ['type' => 'number'], 'mrp' => ['type' => 'number'],
            'delivery_charges' => ['type' => 'number'], 'free_delivery' => ['type' => 'boolean'],
            'shipping_method' => ['type' => 'string'], 'shipping_time' => ['type' => 'string'],
            'description' => ['type' => 'string'], 'location' => ['type' => 'string'], 'made_in' => ['type' => 'string'],
            'return_policy' => ['type' => 'string'], 'part_type' => ['type' => 'string'],
            'attributes' => ['type' => 'object'], 'state' => ['type' => 'string'], 'force' => ['type' => 'boolean']],
            'required' => ['vendor', 'name', 'category', 'subcategory']]],
    ['name' => 'update_product', 'description' => 'Product update. id + jo fields badalni hon (create_product wale naam, plus vendor, state). Sirf diye hue fields badalte hain.',
        'inputSchema' => ['type' => 'object', 'properties' => ['id' => ['type' => 'integer'], 'state' => ['type' => 'string'], 'force' => ['type' => 'boolean']], 'required' => ['id'], 'additionalProperties' => true]],
    ['name' => 'create_upload_link', 'description' => 'Product ki images (5-10) aur video upload karne ka link (user browser me kholega, multi-select hai).',
        'inputSchema' => ['type' => 'object', 'properties' => ['id' => ['type' => 'integer']], 'required' => ['id']]],
    ['name' => 'finalize_product', 'description' => 'Upload ke baad check (5-10 images, video) aur phir state set (pending/approved/draft). User se pooch kar hi state do.',
        'inputSchema' => ['type' => 'object', 'properties' => ['id' => ['type' => 'integer'], 'state' => ['type' => 'string'], 'allow_no_video' => ['type' => 'boolean']], 'required' => ['id', 'state']]],
    ['name' => 'delete_product', 'description' => "mode='safe' (default): agar product ke orders/ratings/replacements hain to delete refuse karta hai aur sirf inactive kar deta hai; warna delete. mode='hard': sab kuch delete (orders bhi, CASCADE) - sirf user ke explicit kehne par aur confirm=true ke saath.",
        'inputSchema' => ['type' => 'object', 'properties' => ['id' => ['type' => 'integer'], 'mode' => ['type' => 'string'], 'confirm' => ['type' => 'boolean']], 'required' => ['id']]],
];

function t_run_sql($a)
{
    $sql = isset($a['sql_b64']) ? base64_decode($a['sql_b64']) : (isset($a['sql']) ? $a['sql'] : '');
    $sql = trim($sql);
    $sql = rtrim($sql, "; \t\n\r");
    if ($sql === '') return fail('sql khali hai');
    if (!single_statement($sql)) return fail('Ek waqt me sirf ek statement.');
    if (preg_match('/\b(into\s+(out|dump)file|load_file\s*\(|load\s+data)\b/i', $sql)) return fail('File read/write blocked.');
    $kw = strtolower(strtok(ltrim($sql, "( \t\n\r"), " \t\n\r("));
    $read = in_array($kw, ['select', 'show', 'describe', 'desc', 'explain', 'with'], true);
    $confirm = !empty($a['confirm']);
    if (!$read && !$confirm) {
        if (in_array($kw, ['drop', 'truncate', 'alter', 'rename', 'grant', 'revoke'], true)) return fail("$kw ke liye confirm=true chahiye (user se explicit ijazat lo).");
        if (in_array($kw, ['update', 'delete'], true) && !preg_match('/\bwhere\b/i', $sql)) return fail("$kw bina WHERE ke poori table badal dega. confirm=true sirf user ki explicit ijazat par.");
    }
    $params = isset($a['params']) && is_array($a['params']) ? array_values($a['params']) : [];
    $db = mcp_db();
    $st = $db->prepare($sql);
    foreach ($params as $i => $v) {
        if (is_int($v)) $st->bindValue($i + 1, $v, PDO::PARAM_INT);
        elseif (is_null($v)) $st->bindValue($i + 1, null, PDO::PARAM_NULL);
        elseif (is_bool($v)) $st->bindValue($i + 1, $v ? 1 : 0, PDO::PARAM_INT);
        else $st->bindValue($i + 1, is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : (string) $v);
    }
    if (!$read) mcp_audit("run_sql[$kw] " . $sql);
    $st->execute();
    if ($st->columnCount() > 0) {
        $limit = max(1, min(500, (int) arg($a, 'limit', 100)));
        $rows = [];
        $trunc = false;
        while (($r = $st->fetch()) !== false) {
            if (count($rows) >= $limit) { $trunc = true; break; }
            foreach ($r as $k => $v) {
                if (is_string($v) && strlen($v) > 1500) $r[$k] = substr($v, 0, 1500) . '...[cut]';
            }
            $rows[] = $r;
        }
        return ok_text(['rows' => $rows, 'count' => count($rows), 'truncated' => $trunc]);
    }
    return ok_text(['affected_rows' => $st->rowCount(), 'last_insert_id' => $db->lastInsertId()]);
}

function t_list_vendors($a)
{
    $db = mcp_db();
    $s = arg($a, 'search');
    if ($s) {
        $st = $db->prepare("SELECT user_id, username, full_name, email, phone, status, verified FROM users WHERE type='vendor' AND (full_name LIKE ? OR email LIKE ? OR username LIKE ?) ORDER BY user_id");
        $l = '%' . $s . '%';
        $st->execute([$l, $l, $l]);
    } else {
        $st = $db->query("SELECT user_id, username, full_name, email, phone, status, verified FROM users WHERE type='vendor' ORDER BY user_id");
    }
    return ok_text($st->fetchAll());
}

function t_list_categories($a)
{
    $db = mcp_db();
    $cats = $db->query('SELECT id, name, status FROM site_categories ORDER BY sort_order, name')->fetchAll();
    $subs = $db->query('SELECT category_id, name FROM site_subcategories ORDER BY sort_order, name')->fetchAll();
    $by = [];
    foreach ($subs as $s) $by[$s['category_id']][] = $s['name'];
    $s = strtolower((string) arg($a, 'search', ''));
    $out = [];
    foreach ($cats as $c) {
        $c['subcategories'] = isset($by[$c['id']]) ? $by[$c['id']] : [];
        if ($s === '' || strpos(strtolower($c['name']), $s) !== false || stripos(implode('|', $c['subcategories']), $s) !== false) $out[] = $c;
    }
    return ok_text(['categories' => $out, 'note' => 'Kuch categories sirf code (CategoryCatalog) me ho sakti hain; DB list adhoori ho to batao.']);
}

function t_get_product($a)
{
    $p = product_summary((int) arg($a, 'id', 0));
    return $p ? ok_text($p) : fail('Product nahi mila');
}

function t_search_products($a)
{
    $db = mcp_db();
    $w = [];
    $p = [];
    if ($q = arg($a, 'q')) { $w[] = '(p.name LIKE ? OR p.brand LIKE ?)'; $p[] = "%$q%"; $p[] = "%$q%"; }
    if ($v = arg($a, 'vendor')) {
        list($vr, $err) = resolve_vendor($v);
        if ($err) return fail($err);
        $w[] = 'p.user_id=?'; $p[] = $vr['user_id'];
    }
    if ($s = arg($a, 'state')) { $w[] = 'p.position=?'; $p[] = $s; }
    $limit = max(1, min(200, (int) arg($a, 'limit', 30)));
    $sql = 'SELECT p.id, p.user_id, u.full_name AS vendor, p.name, p.category, p.subcategory, p.quantity, p.selling_price, p.mrp, p.status, p.position, p.created_at, (SELECT COUNT(*) FROM vendor_product_images i WHERE i.product_id=p.id) AS images, (p.video IS NOT NULL AND p.video<>"") AS has_video FROM vendor_products p LEFT JOIN users u ON u.user_id=p.user_id'
        . ($w ? ' WHERE ' . implode(' AND ', $w) : '') . ' ORDER BY p.id DESC LIMIT ' . $limit;
    $st = $db->prepare($sql);
    $st->execute($p);
    return ok_text($st->fetchAll());
}

function collect_fields($a)
{
    $map = ['name' => 'name', 'category' => 'category', 'subcategory' => 'subcategory', 'quantity' => 'quantity', 'brand' => 'brand', 'model' => 'model',
        'condition' => 'pcondition', 'selling_price' => 'selling_price', 'mrp' => 'mrp', 'delivery_charges' => 'delivery_charges',
        'shipping_method' => 'shipping_method', 'shipping_time' => 'shipping_time', 'description' => 'description', 'location' => 'location',
        'made_in' => 'made_in', 'return_policy' => 'return_policy', 'part_type' => 'part_type'];
    $f = [];
    foreach ($map as $in => $col) {
        if (array_key_exists($in, $a)) $f[$col] = $a[$in];
    }
    foreach (['name', 'brand', 'model', 'made_in'] as $k) {
        if (isset($f[$k]) && $f[$k] !== null) $f[$k] = ucwords(trim((string) $f[$k]));
    }
    if (array_key_exists('free_delivery', $a)) $f['free_delivery'] = $a['free_delivery'] ? 1 : 0;
    return $f;
}

function t_create_product($a)
{
    global $ATTR_COLS;
    $db = mcp_db();
    list($vendor, $err) = resolve_vendor(arg($a, 'vendor'));
    if ($err) return fail($err);
    $state = arg($a, 'state', 'draft');
    $sf = state_fields($state);
    if (!$sf) return fail('state draft/pending/approved/rejected/inactive me se ho');
    $f = collect_fields($a);
    if (!isset($f['delivery_charges'])) $f['delivery_charges'] = 250;
    if (!isset($f['free_delivery'])) $f['free_delivery'] = 0;
    if (!isset($f['quantity'])) $f['quantity'] = 0;
    $errs = check_product($f, $state !== 'draft');
    if ($errs && $state !== 'draft' && empty($a['force'])) return fail('Validation fail (force=true se skip)', ['problems' => $errs]);

    $row = array_merge($f, $sf, ['user_id' => (int) $vendor['user_id'], 'original_price' => 0]);
    foreach (['name' => 'Untitled Draft', 'category' => '', 'subcategory' => '', 'brand' => '', 'pcondition' => '', 'shipping_method' => '', 'shipping_time' => '', 'description' => '', 'location' => ''] as $k => $d) {
        if (!isset($row[$k]) || $row[$k] === null) $row[$k] = $d;
    }
    if ($sf['position'] === 'approved') $row['approved_at'] = date('Y-m-d H:i:s');

    $ignored = [];
    if (isset($a['attributes']) && is_array($a['attributes'])) {
        foreach ($a['attributes'] as $col => $val) {
            if (in_array($col, $ATTR_COLS, true) && in_array($col, mcp_cols('vendor_products'), true)) $row[$col] = json_encode($val, JSON_UNESCAPED_UNICODE);
            else $ignored[] = $col;
        }
    }
    $cols = mcp_cols('vendor_products');
    foreach (array_keys($row) as $k) {
        if (!in_array($k, $cols, true)) { $ignored[] = $k; unset($row[$k]); }
    }
    $names = array_keys($row);
    $sql = 'INSERT INTO vendor_products (`' . implode('`,`', $names) . '`) VALUES (' . implode(',', array_fill(0, count($names), '?')) . ')';
    $db->prepare($sql)->execute(array_values($row));
    $id = (int) $db->lastInsertId();
    mcp_audit("create_product #$id vendor={$vendor['user_id']} state=$state");
    return ok_text([
        'product_id' => $id, 'vendor' => $vendor, 'state' => $state,
        'warnings' => $errs, 'ignored_fields' => array_values(array_unique($ignored)),
        'upload_url' => mcp_upload_url($id),
        'next' => 'User ko upload_url do (5-10 images + video), phir finalize_product.',
    ]);
}

function t_update_product($a)
{
    global $ATTR_COLS;
    $db = mcp_db();
    $id = (int) arg($a, 'id', 0);
    $cur = product_summary($id);
    if (!$cur) return fail('Product nahi mila');
    $f = collect_fields($a);
    if ($v = arg($a, 'vendor')) {
        list($vr, $err) = resolve_vendor($v);
        if ($err) return fail($err);
        $f['user_id'] = (int) $vr['user_id'];
    }
    if ($s = arg($a, 'state')) {
        $sf = state_fields($s);
        if (!$sf) return fail('state galat');
        $f = array_merge($f, $sf);
        if ($sf['position'] === 'approved') $f['approved_at'] = date('Y-m-d H:i:s');
        $merged = array_merge($cur, $f);
        $errs = check_product($merged, $s !== 'draft');
        if ($errs && $s !== 'draft' && empty($a['force'])) return fail('Validation fail (force=true se skip)', ['problems' => $errs]);
    }
    if (isset($a['attributes']) && is_array($a['attributes'])) {
        foreach ($a['attributes'] as $col => $val) {
            if (in_array($col, $ATTR_COLS, true)) $f[$col] = json_encode($val, JSON_UNESCAPED_UNICODE);
        }
    }
    $cols = mcp_cols('vendor_products');
    $ignored = [];
    foreach (array_keys($f) as $k) {
        if (!in_array($k, $cols, true)) { $ignored[] = $k; unset($f[$k]); }
    }
    if (!$f) return fail('Kuch badalne ko nahi diya', ['ignored_fields' => $ignored]);
    $set = [];
    foreach (array_keys($f) as $k) $set[] = "`$k`=?";
    $vals = array_values($f);
    $vals[] = $id;
    $db->prepare('UPDATE vendor_products SET ' . implode(',', $set) . ' WHERE id=?')->execute($vals);
    mcp_audit("update_product #$id fields=" . implode(',', array_keys($f)));
    return ok_text(['updated' => array_keys($f), 'ignored_fields' => $ignored, 'product' => product_summary($id)]);
}

function t_create_upload_link($a)
{
    $id = (int) arg($a, 'id', 0);
    $st = mcp_db()->prepare('SELECT id, name FROM vendor_products WHERE id=?');
    $st->execute([$id]);
    $p = $st->fetch();
    if (!$p) return fail('Product nahi mila');
    return ok_text(['product' => $p, 'upload_url' => mcp_upload_url($id), 'valid_hours' => round(((int) (mcp_cfg('upload_ttl') ?: 21600)) / 3600, 1)]);
}

function t_finalize_product($a)
{
    $db = mcp_db();
    $id = (int) arg($a, 'id', 0);
    $p = product_summary($id);
    if (!$p) return fail('Product nahi mila');
    $state = arg($a, 'state');
    $sf = state_fields($state);
    if (!$sf) return fail('state galat');
    $n = count($p['images']);
    $problems = [];
    if ($state !== 'draft') {
        if ($n < 5 || $n > 10) $problems[] = "images $n hain, 5-10 chahiye";
        if (empty($p['video']) && empty($a['allow_no_video'])) $problems[] = 'video upload nahi hui (allow_no_video=true se skip)';
        $problems = array_merge($problems, check_product($p, true));
    }
    if ($problems) return fail('Finalize nahi ho saka', ['problems' => $problems, 'upload_url' => mcp_upload_url($id)]);
    $set = 'status=?, position=?';
    $vals = [$sf['status'], $sf['position']];
    if ($sf['position'] === 'approved') $set .= ', approved_at=NOW()';
    $vals[] = $id;
    $db->prepare("UPDATE vendor_products SET $set WHERE id=?")->execute($vals);
    mcp_audit("finalize_product #$id state=$state");
    return ok_text(['product_id' => $id, 'state' => $state, 'images' => $n, 'has_video' => !empty($p['video'])]);
}

function t_delete_product($a)
{
    $db = mcp_db();
    $id = (int) arg($a, 'id', 0);
    $p = product_summary($id);
    if (!$p) return fail('Product nahi mila');
    $mode = arg($a, 'mode', 'safe');
    $refs = [];
    foreach (['orders', 'product_ratings', 'replacement_requests'] as $t) {
        try {
            $st = $db->prepare("SELECT COUNT(*) FROM `$t` WHERE product_id=?");
            $st->execute([$id]);
            $c = (int) $st->fetchColumn();
            if ($c) $refs[$t] = $c;
        } catch (Exception $e) {
        }
    }
    if ($mode === 'safe' && $refs) {
        $sf = state_fields('inactive');
        $db->prepare('UPDATE vendor_products SET status=?, position=? WHERE id=?')->execute([$sf['status'], $sf['position'], $id]);
        mcp_audit("delete_product(safe->inactive) #$id");
        return ok_text(['deleted' => false, 'made_inactive' => true, 'reason' => 'Is product se orders/ratings/replacements juray hain, delete se wo bhi mit jate.', 'references' => $refs]);
    }
    if ($mode === 'hard' && empty($a['confirm'])) return fail('hard delete ke liye confirm=true chahiye', ['references' => $refs]);
    if ($mode !== 'safe' && $mode !== 'hard') return fail("mode 'safe' ya 'hard'");

    foreach ($p['images'] as $im) mcp_delete_file('images', $im['image_path']);
    mcp_delete_file('videos', $p['video']);
    $fr = $db->prepare('SELECT fault_image FROM vendor_product_faults WHERE product_id=?');
    $fr->execute([$id]);
    foreach ($fr->fetchAll() as $r) mcp_delete_file('faults', $r['fault_image']);
    $db->prepare('DELETE FROM vendor_product_images WHERE product_id=?')->execute([$id]);
    $db->prepare('DELETE FROM vendor_product_faults WHERE product_id=?')->execute([$id]);
    $db->prepare('DELETE FROM vendor_products WHERE id=?')->execute([$id]);
    mcp_audit("delete_product($mode) #$id name={$p['name']} refs=" . json_encode($refs));
    return ok_text(['deleted' => true, 'mode' => $mode, 'removed_references' => $refs]);
}

// ---------- JSON-RPC ----------
function handle($m)
{
    global $TOOLS;
    $id = isset($m['id']) ? $m['id'] : null;
    $method = isset($m['method']) ? $m['method'] : '';
    $isNote = !array_key_exists('id', $m);
    if ($isNote) return null;
    $res = null;
    try {
        switch ($method) {
            case 'initialize':
                $pv = isset($m['params']['protocolVersion']) ? $m['params']['protocolVersion'] : '2025-03-26';
                $res = ['protocolVersion' => $pv, 'capabilities' => ['tools' => new stdClass()], 'serverInfo' => ['name' => 'mjcheezain-products', 'version' => '1.0.0'],
                    'instructions' => 'MJCheezain vendor_products manager. Pehle user se saari product details lo, vendor poochho, state (draft/pending/approved) user se poochho. Write se pehle confirm lo.'];
                break;
            case 'ping':
                $res = new stdClass();
                break;
            case 'tools/list':
                $res = ['tools' => $TOOLS];
                break;
            case 'tools/call':
                $name = isset($m['params']['name']) ? $m['params']['name'] : '';
                $args = isset($m['params']['arguments']) && is_array($m['params']['arguments']) ? $m['params']['arguments'] : [];
                $fn = 't_' . $name;
                if (!preg_match('/^[a-z_]+$/', $name) || !function_exists($fn)) {
                    $res = fail("Unknown tool: $name");
                } else {
                    try {
                        $res = $fn($args);
                    } catch (Throwable $e) {
                        $res = fail('DB/server error: ' . $e->getMessage());
                    }
                }
                break;
            default:
                return ['jsonrpc' => '2.0', 'id' => $id, 'error' => ['code' => -32601, 'message' => "Method not found: $method"]];
        }
    } catch (Throwable $e) {
        return ['jsonrpc' => '2.0', 'id' => $id, 'error' => ['code' => -32603, 'message' => $e->getMessage()]];
    }
    return ['jsonrpc' => '2.0', 'id' => $id, 'result' => $res];
}

$isBatch = isset($req[0]) && is_array($req[0]);
if ($isBatch) {
    $outs = [];
    foreach ($req as $m) {
        $r = handle($m);
        if ($r !== null) $outs[] = $r;
    }
    if (!$outs) out(202);
    out(200, $outs);
}
$r = handle($req);
if ($r === null) out(202);
out(200, $r);
