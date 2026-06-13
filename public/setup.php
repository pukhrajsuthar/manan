<?php
/**
 * ONE-TIME SETUP FILE — DELETE IMMEDIATELY AFTER USE
 */

// Basic auth to prevent public access
$user = $_SERVER['PHP_AUTH_USER'] ?? '';
$pass = $_SERVER['PHP_AUTH_PW'] ?? '';
if ($user !== 'manan' || $pass !== 'setup2024') {
    header('WWW-Authenticate: Basic realm="Setup"');
    header('HTTP/1.0 401 Unauthorized');
    exit('Unauthorized');
}

ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(300);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo '<pre style="font-family:monospace;font-size:13px;padding:20px;">';
echo "=== Manan Furniture — One-Time Setup ===\n\n";

function run($kernel, $cmd, $label) {
    echo "▶ {$label}...\n";
    $exit = $kernel->call($cmd, [], new Symfony\Component\Console\Output\StreamOutput(STDOUT));
    echo $exit === 0 ? "  ✓ Done\n\n" : "  ✗ Failed (exit: {$exit})\n\n";
}

run($kernel, 'key:generate --force',   'Generating app key');
run($kernel, 'migrate --force',        'Running migrations');
run($kernel, 'db:seed --force',        'Seeding database (users, items, clients, invoices)');
run($kernel, 'config:clear',           'Clearing config cache');
run($kernel, 'route:clear',            'Clearing route cache');
run($kernel, 'view:clear',             'Clearing view cache');

echo "===========================================\n";
echo "✅ Setup complete!\n\n";
echo "Login URL  : " . url('/login') . "\n";
echo "Email      : admin@mananfurniture.com\n";
echo "Password   : Manan@2024\n\n";
echo "⚠️  DELETE THIS FILE NOW from public/setup.php\n";
echo '</pre>';
