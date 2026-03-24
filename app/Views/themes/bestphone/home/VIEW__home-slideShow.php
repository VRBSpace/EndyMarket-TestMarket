<?php
$_imageSlideshowDir = $_ENV['app.imagePortalDir'];
?>

<!-- <div class="mb-5"> -->
    <div class="bg-img-hero" >
        <!-- Image container of the image slider -->
        <div id="js-slideshowSlider" class="g_slide image-container" style="border-left: 2px solid red;">

            <div class="switch_main">
                <?php
                if (!empty($slideshow_images)) {
                    // Индексиране по ключ
                    $slideshow_images = array_column($slideshow_images, null, 'key');

                    foreach ($slideshow_images as $k => $b) {
                        // Винаги използваме публичния slideshow (като за нерегистрирани)
                        if ($k === 'slideshowDilar') {
                            continue;
                        }

                        if (!empty($b['text'])) {
                            $slideshow_images = json_decode($b['text']);

                            if (is_array($slideshow_images)) {
                                foreach ($slideshow_images as $image) {
                                    $_imgSrc = $_imageSlideshowDir . ($image -> img ?? '');
                                    $_link   = !empty($image -> url) ? $image -> url : '#';
                                    ?>
                                    <a class="item switch_item" href="<?= !empty($image -> url) ? $image -> url : '#' ?>">
                                        <img class="rounded-8" data-src="<?= $_imageSlideshowDir . $image -> img ?>" >
                                    </a>
                                    <?php
                                }
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
<!-- </div> -->
