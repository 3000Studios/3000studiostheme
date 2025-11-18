<?php
/**
 * 3000 STUDIOS – SHADOW WP RECOVERY PANEL
 * Fixes white screen, fatal PHP errors, broken themes/plugins, and .htaccess corruption
 */

error_reporting(E_ALL);
ini_set("display_errors", 1);

define('WP_USE_THEMES', false);

// --- Locate WP root ---
$root = __DIR__;
if (!file_exists($root . "/wp-config.php")) {
    die("❌ Place this file in your WordPress ROOT folder.");
}

require_once($root . "/wp-config.php");

// --- Header ---
echo "<h1 style='font-family:Arial;margin-bottom:5px;'>3000 STUDIOS — WordPress Shadow Recovery</h1>";
echo "<p>Site: <b>{$_SERVER['HTTP_HOST']}</b></p>";
echo "<hr>";

// --------- 1. SHOW LAST 20 LINES OF ERROR LOG ---------
$log1 = $root . "/wp-content/debug.log";
$log2 = ini_get("error_log");

echo "<h2>🔥 PHP Fatal Error Log</h2>";

function tail($file, $lines = 20) {
    if (!file_exists($file)) return false;
    $data = file($file);
    return array_slice($data, -$lines);
}

if (file_exists($log1)) {
    echo "<pre style='background:#111;color:#0f0;padding:10px;'>";
    echo htmlspecialchars(implode("", tail($log1)));
    echo "</pre>";
} elseif ($log2 && file_exists($log2)) {
    echo "<pre style='background:#111;color:#0f0;padding:10px;'>";
    echo htmlspecialchars(implode("", tail($log2)));
    echo "</pre>";
} else {
    echo "<p>No log found. (This happens when WP crashes too early.)</p>";
}


// --------- 2. DISABLE PLUGINS ---------
if (isset($_GET['disable_plugins'])) {
    rename($root . "/wp-content/plugins", $root . "/wp-content/plugins-off");
    echo "<p>✔ Plugins disabled</p>";
}


// --------- 3. DISABLE THEME ---------
if (isset($_GET['disable_theme'])) {
    $theme = $root . "/wp-content/themes";
    $dirs = scandir($theme);
    foreach ($dirs as $d) {
        if ($d !== "." && $d !== ".." && $d !== "twentytwentyone" && $d !== "twentytwentythree") {
            @rename("$theme/$d", "$theme/{$d}-off");
        }
    }
    echo "<p>✔ Theme disabled</p>";
}


// --------- 4. REBUILD .HTACCESS ---------
if (isset($_GET['fix_htaccess'])) {
    $default = <<<EOT
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
EOT;

    file_put_contents($root . "/.htaccess", $default);
    echo "<p>✔ .htaccess rebuilt</p>";
}


// --------- CONTROL PANEL ---------
echo "<hr><h2>Recovery Tools</h2>";
echo "<p><a href='?disable_plugins=1'>🛑 Disable ALL Plugins</a></p>";
echo "<p><a href='?disable_theme=1'>🎨 Disable Active Theme</a></p>";
echo "<p><a href='?fix_htaccess=1'>🧱 Rebuild .htaccess</a></p>";
echo "<p><a href='/wp-admin'>➡ Go to WP Admin</a></p>";

echo "<hr><p>✔ Delete this script once fixed: <b>wp-shadow-recover.php</b></p>";
?>
