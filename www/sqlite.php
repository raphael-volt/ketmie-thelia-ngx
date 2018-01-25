<?php
echo "<pre>";
$filename = __DIR__ . "/amf/motif.sqlite";

$pdo = new PDO("sqlite:$filename","", "", array(PDO::MYSQL_ATTR_INIT_COMMAND=>"set names utf8"));
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
$query = "SELECT name FROM sqlite_master WHERE type='table'";
$names = $pdo->query($query);
$names = $names->fetchAll(PDO::FETCH_COLUMN, 0);
if(array_search("motif", $names) === false )
{
	echo 'CREATE  TABLE "main"."motif"' . "\n";
	$query = 'CREATE  TABLE "main"."motif" (
"produit_id" INTEGER PRIMARY KEY  AUTOINCREMENT  UNIQUE , 
"filename" TEXT, 
"width" INTEGER, 
"height" INTEGER);';
	$pdo->query($query);
	$query = "INSERT INTO motif(produit_id,filename,width,height) VALUES(?,?,?,?)";
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(1, 127, PDO::PARAM_INT);
	$stmt->bindValue(2, "foo.jpg", PDO::PARAM_STR);
	$stmt->bindValue(3, 300, PDO::PARAM_INT);
	$stmt->bindValue(4, 400, PDO::PARAM_INT);
	$stmt->execute();
}
else 
{
	"TABLES:" . print_r($names) . "\n";
}
$query = "SELECT * FROM motif";
$stmt = $pdo->query($query);
print_r($stmt->fetchAll());
/*
SELECT name FROM sqlite_master WHERE type='table'
*/
/*
CREATE  TABLE "main"."motif" (
"produit_id" INTEGER PRIMARY KEY  AUTOINCREMENT  UNIQUE , 
"filename" TEXT, 
"width" INTEGER, 
"height" INTEGER)
 */