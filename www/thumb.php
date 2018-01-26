<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function header404()
{
	// header('HTTP/1.0 404 Not Found');
    // exit;
    die("header('HTTP/1.0 404 Not Found')");
}

if( ! isset($_GET["id"]))
{
	header404();
}
$id = $_GET["id"];
$h = isset($_GET["h"]) ? $_GET["h"]:0;
$w = isset($_GET["w"]) ? $_GET["w"]:0; 

if($h == 0 && $w == 0)
{
	header404();
}

include_once 'client/image_max.php';
if( $w > IMG_MAX_THUMB_WIDTH || $h > IMG_MAX_THUMB_HEIGHT)
{
	die("w:$w, h:$h");
	header404();
}

$photos_dir = __DIR__ . "/client/gfx/photos";
include_once 'client/config_thelia.php';
require_once 'classes/PDOThelia.class.php';
$q = "SELECT fichier FROM image WHERE id=?";
try
{
$pdo = PDOThelia::getInstance();
$stmt = $pdo->prepare($q);
$pdo->bindInt($stmt, 1, $id, true);
if(!$stmt->rowCount())
{
	header404();
}
$name = $pdo->fetchColumn($stmt);
}
catch(Exception $e)
{
var_dump($e);
}

$dirs = scandir($photos_dir);

$src = null;
$srcDir = null;
for($i=0; $i<count($dirs); $i++)
{
	if(strpos($dirs[$i], ".") === 0)
	{
		continue;
	}
	$src = $photos_dir . "/" . $dirs[$i] . "/" . $name;
	if(file_exists($src))
	{
		$srcDir = $dirs[$i];
		break;
	}
	$src = null;
}

if($src == null)
{
	header404();
}
$sizes = getimagesize($src);
$img_type = $sizes[2];
$ext = null;	
switch ($img_type) 
{
	case  IMAGETYPE_GIF:
	{
		$ext = "gif";
		break;
	}
	
	case  IMAGETYPE_JPEG:
	{
		$ext = "jpg";
		break;
	}
	
	case  IMAGETYPE_PNG:
	{
		$ext = "png";
		break;
	}
	
	default:
	{
		header404();
	}
}
if($h == 0)
	$h = floor($w * $sizes[1] / $sizes[0]);
if($w == 0)
	$w = floor($h * $sizes[0] / $sizes[1]);
$nomcache  = "client/cache/" . $srcDir;
if( ! is_dir(__DIR__ . "/$nomcache"))
{
	mkdir(__DIR__ . "/$nomcache", 0777, true);
}
$nomcache  = $nomcache . "/" . $w . "_" . $h . "_______" . $name;
$pathcache = __DIR__ . "/$nomcache";
$create_img = ! file_exists($pathcache);
if($create_img)
{
	$image_new = imagecreatetruecolor($w, $h);
		
	switch ($img_type)
	{
		case IMAGETYPE_JPEG:
		{
			$image_orig = imagecreatefromjpeg($src);
			break;
		}
		
		case IMAGETYPE_PNG:
		{	
			$image_orig = imagecreatefrompng($src);
			break;
		}
		
		case IMAGETYPE_GIF:
		{
			$image_orig = imagecreatefromgif($src);
			break;
		}
	}	
		
	imagecopyresampled($image_new, $image_orig, 0, 0, 0, 0, $w, $h, $sizes[0], $sizes[1]);
	imagedestroy($image_orig);
	
	switch ($img_type)
	{
		case IMAGETYPE_JPEG:
		{
			imagejpeg($image_new, $pathcache);
			break;
		}
			
		case IMAGETYPE_PNG:
		{	
			imagepng($image_new, $pathcache);
			break;
		}
		
		case IMAGETYPE_GIF:
		{
			imagegif($image_new, $pathcache);
			break;
		}
	}
	imagedestroy($image_new);
}


header('Content-Length: ' . filesize($pathcache));
header('Content-Type: ' . $sizes["mime"]);

echo file_get_contents($pathcache);
?>