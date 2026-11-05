<?php
require_once __DIR__.'/config/database.php';
$hash=password_hash('admin123', PASSWORD_DEFAULT);
$stmt=$pdo->prepare("SELECT id FROM users WHERE username='admin'");
$stmt->execute();
if($stmt->fetch()) {$q=$pdo->prepare("UPDATE users SET password_hash=? WHERE username='admin'");$q->execute([$hash]);}
else {$q=$pdo->prepare("INSERT INTO users(username,password_hash,full_name,role) VALUES('admin',?,'System Administrator','Admin')");$q->execute([$hash]);}
echo '<h2>AquaFlow setup complete</h2><p>Demo login: <b>admin</b> / <b>admin123</b></p><p>Delete install.php after setup, then sign in at <a href="login.php">login.php</a>.</p>';
