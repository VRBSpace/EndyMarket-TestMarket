<?php
$_sort           = $settings_portal['func']['sort'] ?? '';
$_imageBanersDir = $_ENV['app.imagePortalDir']
?>

<?php if (!empty($banerBlock1_images)): ?>
    <?php
    // Индексиране по ключ, но без да променяме оригиналния масив
    $banerBlock1_hero = array_slice(array_column($banerBlock1_images, null, 'key'), 0, 2, true);
    ?>

    <?php foreach ($banerBlock1_hero as $k => $b): ?>
        <?php
        // Винаги използваме публичните hero банери (като за нерегистрирани)
        if ($k === 'home_banerBlock1Dilar') {
            continue;
        }
        ?>

        <?php if (!empty($b['text'])): ?>
            <?php $_banerBlock1_json = json_decode($b['text']); ?>

            <?php if (is_array($_banerBlock1_json)): ?>
                <?php for ($i = 0; $i < min(2, count($_banerBlock1_json)); $i++): ?>
                    <?php
                    $image   = $_banerBlock1_json[$i];
                    $_imgSrc = $_imageBanersDir . ($image->img ?? '');
                    $_link   = !empty($image->url) ? $image->url . $_sort : '#';
                    ?>
                    <div class="home-banner-item">
                        <div class="home-ratio home-ratio-4x3">
                            <div class="home-ratio__inner">
                                <div class="css-banner-container d-inline-block w-100 h-100 position-relative">
                                    <img class="img-fluid w-100 h-100 rounded-8" style="object-fit: cover;" src="<?= $_imgSrc ?>" alt="">
                                    <a href="<?= $_link ?>" class="position-absolute w-100 h-100" style="top:0;left:0;"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
