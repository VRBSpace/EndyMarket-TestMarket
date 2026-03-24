<?php
$_sort = $settings_portal['func']['sort'] ?? '';
$slugify = function ($text) {
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    $text = strtolower($text);
    $text = preg_replace('~[^a-z0-9]+~', '-', $text);
    return trim($text, '-');
};
?>
<!-- Vertical Menu -->
<div class="col-1 p-0 d-none d-xl-flex align-items-end">

    <div class="max-width-270 min-width-270">
        <!-- Basics Accordion -->
        <div id="basicsAccordion">
            <!-- Card -->
            <div class="card border-0 rounded-0">
                <div class="card-header rounded-0 card-collapse border-0" id="basicsHeadingOne">
                    <button type="button" class="btn-link btn-remove-focus btn-block d-flex card-btn py-3 text-lh-1 px-4 shadow-none btn-primary rounded-top-lg border-0 font-weight-bold text-gray-90"
                            data-toggle="collapse"
                            data-target="#basicsCollapseOne"
                            >
                        <span class="pl-1 text-white">Категории</span>
                        <span class="text-text-white ml-1">
                            <span class="ec ec-arrow-down-search text-white"></span>
                        </span>
                    </button>

                </div>

                <div id="basicsCollapseOne" class="collapse <?= !url_is('*Order*') && !url_is('*orders') && !url_is('*Account') ? 'show1' : '' ?>  vertical-menu v1"
                     aria-labelledby="basicsHeadingOne"
                     data-parent="#basicsAccordion">

                    <div class="card-body p-0">
                        <nav class="js-mega-menu navbar navbar-expand-xl u-header__navbar u-header__navbar--no-space hs-menu-initialized">
                            <div id="navBar" class="collapse navbar-collapse u-header__navbar-collapse">
                                <ul class="navbar-nav u-header__navbar-nav  <!--border-primary--> border-top-0" <?= !url_is('/') ? 'style="border-right: 2px solid red !important;"' : '' ?> >
                                    <?php foreach ($categories as $category): ?>
                                        <?php $_parentSlug = $slugify($category['category_name'] ?? ''); ?>
                                        <!-- Nav Item MegaMenu -->
                                        <li class="nav-item hs-has-mega-menu u-header__nav-item"
                                            data-event="click" 
                                            data-animation-in="slideInUp"
                                            data-animation-out="fadeOut"
                                            data-position="left">

                                            <a id="homeMegaMenu" class="nav-link u-header__nav-link u-header__nav-link-toggle u-header__nav-link-toggle" href="javascript:;" aria-haspopup="true" aria-expanded="false" aria-labelledby="homeSubMenu"><?= $category['category_name'] ?></a>

                                            <!-- Nav Item - Mega Menu -->
                                            <?php
                                            $childCategories = $category['children'];
                                            $totalCategories = count($childCategories);

                                            $elementsPerColumn = 11; // Брой елементи на колона
                                            $totalCol          = ceil($totalCategories / $elementsPerColumn);
                                            ?>
                                            <div class="hs-mega-menu vmm-tfw u-header__sub-menu" aria-labelledby="homeMegaMenu" style="border: 1px solid #ccc;bottom: auto; <?= $totalCol == 1 ? 'width:20rem !important' : '' ?>">

                                                <div class="vmm-bg">
                                                    <!-- <img class="img-fluid" src="../../assets/img/500X400/img1.png" alt="Image Description"> -->
                                                </div>

                                                <div class=" py-2 px-4 " style="background: #e0e0e0;">
                                                    <div class="col text-center">
                                                        <?= $category['category_name'] ?>
                                                    </div>

                                                </div>

                                                <div class="row u-header__mega-menu-wrapper p-4">
                                                    <?php
                                                    if ($totalCategories > 0) {
                                                        $columns = ceil($totalCategories / $elementsPerColumn);

                                                        for ($i = 0; $i < $columns; $i++) {
                                                            echo '<div class="col mb-3 mb-sm-0"><ul class="u-header__sub-menu-nav-group mb-3">';
                                                            for ($j = $i * $elementsPerColumn; $j < min(($i + 1) * $elementsPerColumn, $totalCategories); $j++) {
                                                                $_child = $childCategories[$j];
                                                                $_childSlug = $_parentSlug . '/' . $slugify($_child['category_name'] ?? '');

                                                                echo '<li><a class="nav-link d-block u-header__sub-menu-nav-link" href="/shop/' . $_childSlug . '" style="border-bottom: 1px solid #dddddd">' . $_child['category_name'] . '<small class="ml-1">' . ($countProductsBySubCategory[$_child['category_id']] ?? '') . '</small></a></li>';
                                                            }
                                                            echo '</ul></div>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <!-- End Nav Item - Mega Menu -->
                                        </li>
                                        <!-- End Nav Item MegaMenu-->
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        <!-- End Basics Accordion -->
    </div>
</div>
<!-- End Vertical Menu -->
