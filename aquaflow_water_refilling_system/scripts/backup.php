<?php
// Run from CLI only: php scripts/backup.php
if (PHP_SAPI !== 'cli') { http_response_code(403); exit("CLI only\n"); }
require_once __DIR__.'/../includes/functions.php';
$dir=__DIR__.'/../storage/backups'; if(!is_dir($dir)) mkdir($dir,0700,true);
$tables=$pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
$sql="-- Scheduled AquaFlow backup ".date('c')."\nSET FOREIGN_KEY_CHECKS=0;\n";
foreach($tables as $t){$c=$pdo->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_ASSOC);$sql.="DROP TABLE IF EXISTS `$t`;\n".$c['Create Table'].";\n";foreach($pdo->query("SELECT * FROM `$t`")->fetchAll(PDO::FETCH_ASSOC) as $row){$v=[];foreach($row as $x)$v[]=$x===null?'NULL':$pdo->quote((string)$x);$sql.="INSERT INTO `$t` VALUES(".implode(',',$v).");\n";}}
$sql.="SET FOREIGN_KEY_CHECKS=1;\n";$f=$dir.'/scheduled_'.date('Ymd_His').'.sql';file_put_contents($f,$sql,LOCK_EX);chmod($f,0600);echo "Backup created: $f\n";
