<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

include_once 'client/image_max.php';
$name = null;

if (isset($_GET["name"]))
    $name = $_GET["name"];
else {
    include_once 'client/config_thelia.php';
    require_once 'classes/PDOThelia.class.php';
    $pdo = PDOThelia::getInstance();
    if (isset($_GET["produit"])) {
        $q = "SELECT fichier FROM image WHERE produit=? AND classement=?";
        if (isset($_GET["classement"]))
            $classement = $_GET["classement"];
        else
            $classement = 1;
        $stmt = $pdo->prepare($q);
        $pdo->bindInt($stmt, 1, $_GET["produit"]);
        $pdo->bindInt($stmt, 2, $classement, true);
        $name = $pdo->fetchColumn($stmt);
    } else {
        if (isset($_GET["id"])) {
            $q = "SELECT fichier FROM image WHERE id=?";
            $stmt = $pdo->prepare($q);
            $pdo->bindInt($stmt, 1, $_GET["id"], true);
            $name = $pdo->fetchColumn($stmt);
        }
    }
}
if ($name) {
    
    $nb = isset($_GET["nb"]) ? "nb" : "";
    $type = isset($_GET["type"]) ? $_GET["type"] : "produit";
    
    $w = isset($_GET["width"]) ? $_GET["width"] : NAN;
    $h = isset($_GET["height"]) ? $_GET["height"] : NAN;
    
    $src = __DIR__ . "/client/gfx/photos/$type/" . $name;
    if (! file_exists($src)) {
        $name = null;
    }
}
if ($name) {
    $sizes = getimagesize($src);
    $img_type = $sizes[2];
    $ext = null;
    switch ($img_type) {
        case IMAGETYPE_GIF:
            {
                $ext = "gif";
                break;
            }
        
        case IMAGETYPE_JPEG:
            {
                $ext = "jpg";
                break;
            }
        
        case IMAGETYPE_PNG:
            {
                $ext = "png";
                break;
            }
        
        default:
            {
                $name = null;
            }
    }
}
if ($name) {
    if (is_nan($w) && is_nan($h)) {
        $w = $sizes[0];
        $h = $sizes[1];
    } else {
        if (! is_nan($w) && ! is_nan($h)) {
            $sx = $w / $sizes[0];
            $sy = $h / $sizes[1];
            if ($sy < $sx)
                $sx = $sy;
            $w = floor($sx * $sizes[0]);
            $h = floor($sx * $sizes[1]);
        } else if (is_nan($w) || is_nan($h)) {
            if (is_nan($w)) {
                $w = floor($h * $sizes[0] / $sizes[1]);
            } else
                $h = floor($w * $sizes[1] / $sizes[0]);
        }
    }
    $nomcache = "client/cache/" . $type . "/" . $w . "_" . $h . "__" . $nb . "____" . $name;
    $pathcache = __DIR__ . "/$nomcache";
    $create_img = true;
    /*
    if ($w > IMG_MAX_THUMB_WIDTH || $h > IMG_MAX_THUMB_HEIGHT) {
        $create_img = false;
        $name = null;
    }
    */
}
if ($name) {
    $create_img = ! file_exists($pathcache);
    if ($create_img) {
        $image_new = imagecreatetruecolor($w, $h);
        switch ($img_type) {
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
        if ($nb != "") {
            
            imagetruecolortopalette($image_new, false, 256);
            
            $total = ImageColorsTotal($image_new);
            
            for ($i = 0; $i < $total; $i ++) {
                $old = ImageColorsForIndex($image_new, $i);
                $commongrey = (int) (($old['red'] + $old['green'] + $old['blue']) / 3);
                ImageColorSet($image_new, $i, $commongrey, $commongrey, $commongrey);
            }
        }
        switch ($img_type) {
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
}
if (! $name) {
    $pathcache = __DIR__ . "/template/_gfx/no-image.png";
    $sizes = getimagesize($pathcache);
}

header('Content-Length: ' . filesize($pathcache));
header('Content-Type: ' . $sizes["mime"]);

echo file_get_contents($pathcache);
?>