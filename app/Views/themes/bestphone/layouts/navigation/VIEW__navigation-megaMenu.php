<?php
if (ISMOBILE) {
    return;
}
$_sort = $settings_portal['func']['sort'] ?? '';
$slugify = function ($text) {
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    $text = strtolower($text);
    $text = preg_replace('~[^a-z0-9]+~', '-', $text);
    return trim($text, '-');
};
?>
<div class="border-bottom mx-3 mt-3 mb-2">
    <h5 class="section-title section-title__sm mb-0 pb-2 font-weight-bold font-size-18 mx-1">Категории</h5>
</div>

<div class="vertical-menu position-relative">
    <div class="card-body p-0">
        <nav class="js-mega-menu navbar navbar-expand-xl u-header__navbar u-header__navbar--no-space hs-menu-initialized">
            <div id="navBar" class="collapse navbar-collapse u-header__navbar-collapse">
                <ul class="navbar-nav u-header__navbar-nav">

                    <?php foreach ($categories as $row): ?>
                        <?php $_parentSlug = $slugify($row['category_name'] ?? ''); ?>
                        <!-- Nav Item MegaMenu -->
                        <li class="nav-item hs-has-mega-menu u-header__nav-item"
                            data-event="click" 
                            data-animation-in="slideInUp"
                            data-animation-out="fadeOut"
                            data-position="left">

                            <a id="homeMegaMenu" class="nav-link u-header__nav-link u-header__nav-link-toggle u-header__nav-link-toggle cursor-pointer" ><?= $row['category_name'] ?></a>

                            <?php
                            $_childCategories   = $row['children'];
                            $_totalCategories   = count($_childCategories);
                            $_elementsPerColumn = 11; // Брой елементи на колона
                            $_totalCol          = ceil($_totalCategories / $_elementsPerColumn);
                            ?>

                            <div class="hs-mega-menu vmm-tfw u-header__sub-menu" style="border: 1px solid red;bottom: auto; <?= $_totalCol == 1 ? 'width:25rem !important' : '' ?>">

                                <div class="vmm-bg">
                                    <!-- <img class="img-fluid" src="../../assets/img/500X400/img1.png" alt="Image Description"> -->
                                </div>

                                <div class="py-2 px-4 " style="background: #e0e0e0;">
                                    <div class="col text-center">
                                        <?= $row['category_name'] ?>
                                    </div>
                                </div>

                                <div class="row u-header__mega-menu-wrapper p-0">
                                    <?php
                                    if ($_totalCategories > 0) {
                                        $_columns = ceil($_totalCategories / $_elementsPerColumn);

                                        for ($i = 0; $i < $_columns; $i++) {
                                            echo '<div class="col mb-3 mb-sm-0"><ul class="u-header__sub-menu-nav-group mb-3 ">';
                                            for ($j = $i * $_elementsPerColumn; $j < min(($i + 1) * $_elementsPerColumn, $_totalCategories); $j++) {

                                                $_child = $_childCategories[$j];

                                                $_childSlug = $_parentSlug . '/' . $slugify($_child['category_name'] ?? '');
                                                echo '<li><a class="nav-link d-block u-header__sub-menu-nav-link" href="/shop/' . $_childSlug . '" style="border-bottom: 1px solid #dddddd">' . $_child['category_name'] . '<small class="ml-1">' . ($countProductsBySubCategory[$_child['category_id']] ?? '') . '</small></a></li>';
                                            }

                                            echo '</ul></div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </li>
                        <!-- End Nav Item MegaMenu-->
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav>
    </div>
</div>
<!-- End Card -->

