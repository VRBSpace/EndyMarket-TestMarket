<?php
$_sort     = $settings_portal['func']['sort'] ?? '';
?>
<style>
    .css-img-hover img:hover {
        opacity: 0.8;
        transform: scale(1.2);
        transition: transform 0.2s ease;
    }

</style>

<div class=" js-slick-carousel css-img-hover u-slick u-slick--gutters-0 position-static overflow-hidden u-slick-overflow-visble px-1 py-3 text-lh-38"
     data-arrows-classes="d-none d-xl-block u-slick__arrow-normal u-slick__arrow-centered--y rounded-circle text-black font-size-30 z-index-2"
     data-arrow-left-classes="fa fa-angle-left u-slick__arrow-inner--left left-n16"
     data-arrow-right-classes="fa fa-angle-right u-slick__arrow-inner--right right-n20"
     data-pagi-classes="d-xl-none text-center right-0 bottom-1 left-0 u-slick__pagination u-slick__pagination--dark u-slick__pagination--long mb-2 z-index-n1 mt-4 pt-1"
     data-slides-show="11"
     data-slides-scroll="1"
     data-responsive='[{
     "breakpoint": 1600,
     "settings": {
     "slidesToShow": 11
     }
     }, {
     "breakpoint": 1200,
     "settings": {
     "slidesToShow": 6
     }
     }, {
     "breakpoint": 992,
     "settings": {
     "slidesToShow": 5
     }
     }, {
     "breakpoint": 768,
     "settings": {
     "slidesToShow": 3
     }
     }, {
     "breakpoint": 554,
     "settings": {
     "slidesToShow": 2
     }
     }]'>

    <?php $_imageDir = $_ENV['app.imageDataDir'] . 'smallBtns/' ?>

    <?php
    $info      = [
        ['catRootId' => 166, 'img' => 'Butoni1', 'name' => 'Реновирана техника'],
        ['catRootId' => 184, 'img' => 'Komputri_monitori', 'name' => 'Компютри и Монитори'],
        ['catRootId' => 111, 'img' => 'Videonabludenie', 'name' => 'Видео наблюдение'],
        ['catRootId' => 85, 'img' => 'komputarni_komponenti', 'name' => 'Компютърни Компоненти'],
        ['catRootId' => 135, 'img' => 'Mrezhovi_ustroystva', 'name' => 'Мрежови устройства аксесоари и кабели'],
        ['catRootId' => 94, 'img' => 'Komputarna_periferiya', 'name' => 'Компютърна Периферия'],
        //['catRootId' => 116, 'img' => 'Acsesoari', 'name' => 'Аксесоари за мобилни телефони'],
        ['catRootId' => 116, 'img' => 'Roxpower', 'name' => 'Roxpower'],
        ['catRootId' => 103, 'img' => 'Audio', 'name' => 'Аудио Техника'],
        ['catRootId' => 190, 'img' => 'Gamerski_stolove', 'name' => 'Геймърски столове'],
        ['catRootId' => 110, 'img' => 'Konsumativi_printeri', 'name' => 'Консумативи за принтери'],
        ['catRootId' => 206, 'img' => 'Antivirusni', 'name' => 'Антивирусни програми']
    ];

    $_roxPowerHref = '/shop?brandId=220&brandTxt=ROXPOWER';
    ?>

    <?php
    for ($i = 0; $i < 11; $i++):
        $_href = ($info[$i]['name'] === 'Roxpower') ? $_roxPowerHref : '/shop?categoryRootId=' . $info[$i]['catRootId'];
        ?>
        <div class="js-slide">
            <a class="d-block text-center" href="<?= $_href ?>">
                <img class="mx-auto" src="<?= $_imageDir . $info[$i]['img'] . '.jpg' ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" width="70" height="70" alt="...">
            </a>

            <div class="p-2 text-center">
                <h6 class="font-weight-semi-bold font-size-13 text-gray-90 mb-0 text-lh-1dot2"><?= $info[$i]['name'] ?></h6>
            </div>
        </div>

    <?php endfor ?>
</div>
