<?php
session_set_cookie_params([
    'lifetime' => 0, 'path' => '/', 'domain' => '',
    'secure' => isset($_SERVER['HTTPS']), 'httponly' => true, 'samesite' => 'Lax',
]);
session_start();
define('APP_DEBUG', false); // Set to true in development, false in production
$timeout_duration = 900;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset(); session_destroy(); session_start();
    $_SESSION['flash']['error'] = 'Phiên làm việc đã hết hạn.';
    header("Location: /login"); exit;
}
if(isset($_SESSION['user_id'])) $_SESSION['last_activity'] = time();