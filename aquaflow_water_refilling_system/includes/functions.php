<?php
require_once __DIR__ . '/../config/database.php';
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function logged_in(): bool { return isset($_SESSION['user']); }
function require_login(): void { if (!logged_in()) { header('Location: login.php'); exit; } }
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419); exit('Invalid request token. Please go back and retry.');
    }
}
function log_activity(PDO $pdo, string $action, string $details=''): void {
    if (!logged_in()) return;
    $s=$pdo->prepare('INSERT INTO activity_logs(user_id, action, details, ip_address) VALUES(?,?,?,?)');
    $s->execute([$_SESSION['user']['id'], $action, $details, $_SERVER['REMOTE_ADDR'] ?? '']);
}
function money($n): string { return '₱' . number_format((float)$n, 2); }
