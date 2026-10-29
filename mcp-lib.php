<?php
// Shared helpers for mcp.php and mcp-upload.php. Place in public_html ROOT (next to .env).
// PHP 7.2+ compatible. Laravel boot nahi hota, existing code touch nahi hota.

define('MCP_BASE', __DIR__);
define('MCP_PUBLIC', is_dir(__DIR__ . '/public') ? __DIR__ . '/public' : __DIR__);

function mcp_cfg($k = null)
{
    static $c;
    if ($c === null) {
        $f = MCP_BASE . '/mcp-config.php';
        $c = is_file($f) ? (array) require $f : [];
    }
    return $k === null ? $c : (isset($c[$k]) ? $c[$k] : null);
}

function mcp_env()
{
    static $e;
    if ($e !== null) return $e;
    $e = [];
    $f = MCP_BASE . '/.env';
    if (!is_file($f)) return $e;
    foreach (file($f, FILE_IGNORE_NEW_LINES) as $l) {
        $l = trim($l);
        if ($l === '' || $l[0] === '#') continue;
        $p = strpos($l, '=');
        if ($p === false) continue;
        $k = trim(substr($l, 0, $p));
        $v = trim(substr($l, $p + 1));
        if ($v !== '' && ($v[0] === '"' || $v[0] === "'")) {
            $q = $v[0];
            $end = strrpos($v, $q);
            $v = $end > 0 ? substr($v, 1, $end - 1) : substr($v, 1);
        } else {
            $h = strpos($v, ' #');
            if ($h !== false) $v = trim(substr($v, 0, $h));
        }
        $e[$k] = $v;
    }
    return $e;
}

function mcp_db()
{
    static $pdo;
    if ($pdo) return $pdo;
    $e = mcp_env();
    $host = isset($e['DB_HOST']) ? $e['DB_HOST'] : '127.0.0.1';
    $port = isset($e['DB_PORT']) ? $e['DB_PORT'] : '3306';
    $name = isset($e['DB_DATABASE']) ? $e['DB_DATABASE'] : '';
    $user = isset($e['DB_USERNAME']) ? $e['DB_USERNAME'] : '';
    $pass = isset($e['DB_PASSWORD']) ? $e['DB_PASSWORD'] : '';
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => true,
    ]);
    return $pdo;
}

function mcp_cols($table)
{
    static $cache = [];
    if (!isset($cache[$table])) {
        $cache[$table] = [];
        foreach (mcp_db()->query('SHOW COLUMNS FROM `' . str_replace('`', '', $table) . '`') as $r) {
            $cache[$table][] = $r['Field'];
        }
    }
    return $cache[$table];
}

function mcp_audit($what)
{
    if (!mcp_cfg('audit')) return;
    @file_put_contents(MCP_BASE . '/mcp-audit.log', date('Y-m-d H:i:s') . ' ' . substr(preg_replace('/\s+/', ' ', $what), 0, 400) . "\n", FILE_APPEND);
}

// ---- upload token (stateless HMAC) ----
function mcp_b64u($s) { return rtrim(strtr(base64_encode($s), '+/', '-_'), '='); }
function mcp_b64u_dec($s) { return base64_decode(strtr($s, '-_', '+/')); }

function mcp_make_token($productId)
{
    $ttl = (int) (mcp_cfg('upload_ttl') ?: 21600);
    $payload = $productId . '|' . (time() + $ttl);
    return mcp_b64u($payload) . '.' . hash_hmac('sha256', $payload, (string) mcp_cfg('secret'));
}

function mcp_check_token($t)
{
    if (!is_string($t) || strpos($t, '.') === false) return null;
    list($p, $sig) = explode('.', $t, 2);
    $payload = mcp_b64u_dec($p);
    if (!hash_equals(hash_hmac('sha256', (string) $payload, (string) mcp_cfg('secret')), $sig)) return null;
    $parts = explode('|', $payload);
    if (count($parts) !== 2 || (int) $parts[1] < time()) return null;
    return (int) $parts[0];
}

function mcp_upload_url($productId)
{
    return rtrim((string) mcp_cfg('site_url'), '/') . '/mcp-upload.php?t=' . mcp_make_token($productId);
}

// ---- storage dirs (app double-writes: storage/app/public + public/storage) ----
function mcp_dirs($kind)
{
    return [
        MCP_BASE . '/storage/app/public/vendor/products/' . $kind,
        MCP_PUBLIC . '/storage/vendor/products/' . $kind,
    ];
}

function mcp_save_file($tmp, $kind, $ext)
{
    $name = uniqid() . '.' . $ext;
    $dirs = mcp_dirs($kind);
    $saved = [];
    foreach ($dirs as $d) {
        if (!is_dir($d)) @mkdir($d, 0775, true);
        $real = realpath($d);
        if ($real && in_array($real, $saved, true)) continue; // symlink: same dir, skip duplicate
        if (!is_dir($d) || !@copy($tmp, $d . '/' . $name)) {
            throw new Exception("File save failed: $d");
        }
        if ($real) $saved[] = $real;
    }
    return $name;
}

function mcp_delete_file($kind, $name)
{
    if (!$name || strpos($name, '/') !== false || strpos($name, '..') !== false) return;
    foreach (mcp_dirs($kind) as $d) {
        $f = $d . '/' . $name;
        if (is_file($f)) @unlink($f);
    }
}
