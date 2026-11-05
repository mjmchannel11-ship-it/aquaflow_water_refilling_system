<?php
require_once __DIR__.'/includes/functions.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $username=trim($_POST['username'] ?? '');
    $stmt=$pdo->prepare('SELECT * FROM users WHERE username=? LIMIT 1'); $stmt->execute([$username]); $u=$stmt->fetch();
    if ($u && password_verify($_POST['password'] ?? '', $u['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user']=['id'=>$u['id'],'name'=>$u['full_name'],'username'=>$u['username'],'role'=>$u['role']];
        log_activity($pdo,'Login','Successful login');
        header('Location: index.php'); exit;
    }
    $error='Incorrect username or password.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sign in • AquaFlow</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="login-body"><div class="water-bg"></div><main class="login-card glass">
<div class="drop-logo">💧</div><h1>AquaFlow</h1><p class="muted">Water Refilling Station Management System</p>
<?php if($error): ?><div class="alert danger"><?=e($error)?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>">
<label>Username</label><input name="username" required autocomplete="username" placeholder="Enter username">
<label>Password</label><div class="password-wrap"><input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter password"><button type="button" class="eye" onclick="togglePassword()">◉</button></div>
<div class="login-options"><label class="check"><input type="checkbox" name="remember"> Remember me</label><span>Secure access</span></div>
<button class="btn primary wide">Sign In <span>→</span></button></form><small>Clean Water • Better Health • Brighter Tomorrow</small></main><script src="assets/js/app.js"></script></body></html>
