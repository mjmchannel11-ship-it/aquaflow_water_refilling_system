<?php
require_once __DIR__.'/includes/functions.php'; require_login();
$dir=__DIR__.'/storage/backups'; if(!is_dir($dir)) mkdir($dir,0700,true);
$filename='aquaflow_backup_'.date('Ymd_His').'.sql'; $path=$dir.'/'.$filename;
$tables=$pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
$out="-- AquaFlow database backup\nSET FOREIGN_KEY_CHECKS=0;\n";
foreach($tables as $table){
 $create=$pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
 $out.="\nDROP TABLE IF EXISTS `$table`;\n".$create['Create Table'].";\n";
 $rows=$pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
 foreach($rows as $row){$vals=[];foreach($row as $v)$vals[]=$v===null?'NULL':$pdo->quote((string)$v);$out.="INSERT INTO `$table` VALUES(".implode(',',$vals).");\n";}
}
$out.="SET FOREIGN_KEY_CHECKS=1;\n";
file_put_contents($path,$out,LOCK_EX); chmod($path,0600); log_activity($pdo,'Database Backup',$filename);
header('Content-Type: application/sql');header('Content-Disposition: attachment; filename="'.$filename.'"');readfile($path);exit;
