<?php
$oneMonthInSeconds = 30 * 24 * 60 * 60; // 1 month in seconds (30 days)
// Set the Expires header to one month from now
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $oneMonthInSeconds) . " GMT");
// Set the Cache-Control header to cache the response for one month
header("Cache-Control: max-age=$oneMonthInSeconds, must-revalidate");

use App\Controllers\BaseController as BaseController;

include_once('Minifier.php');

$useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';
// Проверка за различни устройства
$isMobile  = match (true) {
    stripos($useragent, "iPod") !== false,
    stripos($useragent, "iPad") !== false,
    stripos($useragent, "iPhone") !== false,
    stripos($useragent, "Android") !== false,
    stripos($useragent, "iOS") !== false => true,
    default => false,
};

// Дефиниране на константата, ако не е дефинирана
if (!defined('EURORATE')) {
    $euroRate = $setingGeneral['euroRate'] ?? 1;
    define('EURORATE', $euroRate);
}

// Дефиниране на константата, ако не е дефинирана
if (!defined('ISMOBILE')) {
    define('ISMOBILE', $isMobile);
}

// ако има променен файл се изчиства кеша
if (!function_exists('auto')) {

    function auto($file = '') {

        if (!file_exists($file))
            return $file;


        $mtime = filemtime($file);
        return base_url() . $file . '?' . $mtime;
    }
}

// извл на логото за сайта
if (!function_exists('get_logo')) {
    function get_logo() {
        $text = json_decode(BaseController::get_logo() -> text ?? '[]', true);
        return $text['logoSite'] ?? null;
    }
}

// Функция за изчисление на отстъпката за промоции
if (!function_exists('calculateDiscount')) {
    function calculateDiscount($originalPrice, $promoPrice) {
        if ($originalPrice <= 0) {
            return null; // Предпазване от деление на нула
        }

        $discount = $originalPrice - $promoPrice;
        $percent  = ($discount / $originalPrice) * 100;
        return number_format($percent, 2, '.', ' ');
    }
}

//$cssPlugins = [
//    // 'font-awesome/css/fontawesome-all.min',
//    'animate.css/animate.min',
//    'hs-megamenu/src/hs.megamenu',
//    'ion-rangeslider/css/ion.rangeSlider',
//    'malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar',
//    'fancybox/jquery.fancybox',
//    'slick-carousel/slick/slick',
//    'bootstrap-select/dist/css/bootstrap-select.min'
//];
//
//$cssPluginsDir = 'assets/vendor';
//foreach ($cssPlugins as $css) {
//    $compresCssPlugins[] = \JShrink\Minifier::minify(file_get_contents($cssPluginsDir . '/' . $css . '.css'));
//}
//
//file_put_contents('public/assets/css/cssPlugins.min.css', $compresCssPlugins);
?>


<!DOCTYPE html>
<html lang="bg">
    <head>
        <?php
        // =======================================
        // DEFAULT SEO FALLBACK VALUES
        // =======================================
        $shopName = $_ENV['app.shopName'] ?? 'Online Store';

        // Check if SEO data exists
        $isSeo = !empty($seo);

        // Always ensure fallback values exist
        $defaultTitle = $isSeo ? ($seo['seo_title'] ?? $shopName) : $shopName;
        $defaultDesc  = $isSeo ? ($seo['seo_description'] ?? $shopName) : $shopName;

        // Canonical URL must always exist
        $canonical = $isSeo ? (!empty($seo['canonical_url']) ? $seo['canonical_url'] : current_url()) : current_url();

        // Robots tag: if noindex is set → noindex, otherwise always index
        $robots = (!empty($seo['noindex'])) ? 'noindex, nofollow' : 'index, follow';

        // OpenGraph image fallback
        $ogImage = $isSeo ? ($seo['seo_mainImage'] ?? base_url('uploads/no-image.jpg')) : base_url('uploads/no-image.jpg');
        ?>

        <!-- =======================================
             TITLE + META DESCRIPTION (Always present)
        =========================================== -->
        <title><?= esc($defaultTitle) ?></title>
        <meta name="description" content="<?= esc($defaultDesc) ?>">

        <!-- =======================================
             ROBOTS (Always present)
        =========================================== -->
        <meta name="robots" content="<?= $robots ?>">

        <!-- =======================================
             CANONICAL (Always required for SEO)
        =========================================== -->
        <link rel="canonical" href="<?= esc($canonical) ?>">

        <!-- =======================================
             OPEN GRAPH (Always generated)
        =========================================== -->
        <meta property="og:title" content="<?= esc($defaultTitle) ?>">
        <meta property="og:description" content="<?= esc($defaultDesc) ?>">
        <meta property="og:type" content="product">
        <meta property="og:url" content="<?= esc($canonical) ?>">
        <meta property="og:site_name" content="<?= esc($shopName) ?>">

        <meta property="og:image" content="<?= esc($ogImage) ?>">
        <meta property="og:image:secure_url" content="<?= esc($ogImage) ?>">
        <meta property="twitter:image" content="<?= esc($ogImage) ?>">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:locale" content="bg_BG">

        <!-- Additional OG gallery images if exist -->
        <?php if ($isSeo && !empty($seo['seo_images']['gallery'])): ?>
            <?php foreach ($seo['seo_images']['gallery'] as $img): ?>
                        <meta property="og:image" content="<?= esc($img) ?>">
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Required Meta Tags Always Come First -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, shrink-to-fit=no,user-scalable=no">

        <link href="/assets/css/font-electro.css" rel="preload" as="style" onload="this.rel = 'stylesheet'">
        <!--  <link href="/assets/css/theme.css" rel="stylesheet">-->
        <style><?php echo file_get_contents('assets/css/theme.css') ?> </style>
<!--          <link href="<? // 'public/assets/css/cssPlugins.min.css'                                 ?>" rel="preload stylesheet" as ="style" onload="this.rel = 'stylesheet'" >-->

        <link href="/assets/css/<?= $_ENV['app.theme'] . '/custom.css' ?>" rel="stylesheet">

        <!-- Favicon -->
        <link rel="shortcut icon" href="/favicon.png">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

        <!-- CSS Electro Template -->
        <link href="/assets/vendor/hs-megamenu/src/hs.megamenu.css" rel="preload" as="style" onload="this.rel = 'stylesheet'">
        <link href="/assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css" rel="prefetch" as="style" onload="this.rel = 'stylesheet'">
        <link href="/assets/vendor/fancybox/jquery.fancybox.css" rel="preload" as="style" onload="this.rel = 'stylesheet'">
        <link href="/assets/vendor/slick-carousel/slick/slick.css" rel="preload" as="style" onload="this.rel = 'stylesheet'">
        <link href="/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="preload" as="style" onload="this.rel = 'stylesheet'">

        <link href="/assets/vendor/font-awesome/css/fontawesome-all.min.css" rel="stylesheet">

        <link href="/assets/css/autocomplete.css" rel="stylesheet">

        <link href="/assets/vendor/animate.css/animate.min.css" rel="preload" as="style" onload="this.rel = 'stylesheet'">
        <link href="/assets/vendor/ion-rangeslider/css/ion.rangeSlider.css" rel="preload" as="style" onload="this.rel = 'stylesheet'">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="preload" as="font" onload="this.rel = 'stylesheet'" >

        <!-- JS Global Compulsory -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
  

    </head>


    <body> 
