<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ResizeImage extends BaseController {

    public function thumb() {
        $img = $this -> request -> getGet('img');
        $w   = (int) $this -> request -> getGet('w') ?: 300;
        $h   = (int) $this -> request -> getGet('h') ?: 300;

        return $this -> resize($img, $w, $h);
    }

    public function resize(string $image, int $width = 120, int $height = 120): ResponseInterface {
        $source = $_ENV['app.imageDir'] . $image;

        $ch           = curl_init($source);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $code         = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 200) {
            return false;
        }


        // MIME TYPE
        //$mime = mime_content_type($source);

        switch ($content_type) {
            case 'image/jpeg':
                $src = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $src = imagecreatefrompng($source);
                break;
            case 'image/webp':
                $src = imagecreatefromwebp($source);
                break;
            default:
                return false; // unsupported
        }

        if (!$src) {
            return false;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);

        // resize ratio
        $ratio = min($width / $origW, $height / $origH);
        $newW  = (int) ($origW * $ratio);
        $newH  = (int) ($origH * $ratio);

        $dst = imagecreatetruecolor($width, $height);

        // White background (remove if transparent PNG needed)
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

        // Centering
        $x = (int) (($width - $newW) / 2);
        $y = (int) (($height - $newH) / 2);

        imagecopyresampled($dst, $src, $x, $y, 0, 0, $newW, $newH, $origW, $origH);

        //imagedestroy($src);

        ob_start();
        imagewebp($dst, null, 100);
        $rawImage = ob_get_clean();
        imagedestroy($dst);

        return Services::response()
                        -> setHeader('Content-Type', 'image/webp')
                        -> setHeader('Content-Length', strlen($rawImage))
                        -> setBody($rawImage);

//        // === Проверка и създаване на директорията ако не съществува ===
//        $outputDir = dirname(FCPATH, 2) . $_ENV['app.mediaRoot'] . 'cache/thumbsportal/';
//        if (!is_dir($outputDir)) {
//            if (!mkdir($outputDir, 0775, true) && !is_dir($outputDir)) {
//                throw new \RuntimeException("❌ Не може да се създаде папка: $outputDir");
//            }
//        }
//
//        // OUTPUT PATH (same name but .webp)
//        $filename = pathinfo($source, PATHINFO_FILENAME) . '.webp';
//        $output   = dirname(FCPATH, 2) . $_ENV['app.mediaRoot'] . 'cache/thumbsportal/' . $filename;
//        $output2  = $_ENV['app.mediaUrl'] . 'cache/thumbsportal/' . $filename;
//
//        // Save WebP
//        imagewebp($dst, $output, 75);
//        imagedestroy($dst);
//
//        return $output2; // RETURN FILE PATH, not base64
    }
}
