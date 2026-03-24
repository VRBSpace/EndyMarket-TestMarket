<?php
helper('price');

function url_exists($url) {
    $ch   = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($code == 200);
}

function reziceImage($image) {

    $url     = url_exists($_ENV['app.imageDir'] . $image);
    $dataUri = '/public/no_image.jpg';

    if ($url) {

        $width  = 300;
        $height = 300;

        $file = $_ENV['app.imageDir'] . $image;

        //$size  = getimagesize($file);
        $image = @imagecreatefromjpeg($file);

        if ($image) {
            $origWidth  = imagesx($image);
            $origHeight = imagesy($image);

            if ($origWidth > $width || $origHeight > $height) {
                if ($origWidth > $origHeight) {
                    $maxWidth  = $width;
                    $maxHeight = intval($origHeight * $maxWidth / $origWidth);
                } else {
                    $maxHeight = $height;
                    $maxWidth  = intval($origWidth * $maxHeight / $origHeight);
                }
            } else {
                $maxWidth  = $width;
                $maxHeight = $height;
            }


            $x = intval(($width - $maxWidth) / 2);
            $y = intval(( $height - $maxHeight) / 2);

            // Calculate ratio of desired maximum sizes and original sizes.
            $widthRatio  = $maxWidth / $origWidth;
            $heightRatio = $maxHeight / $origHeight;

            // Ratio used for calculating new image dimensions.
            $ratio = min($widthRatio, $heightRatio);

            // Calculate new image dimensions.
            $newWidth  = (int) $origWidth * $ratio;
            $newHeight = (int) $origHeight * $ratio;

            //$image = imagescale($image, 300);
            //ob_start();
            $imageC   = imagecreatetruecolor($width, $height);
            $bg_color = imagecolorallocate($imageC, 255, 255, 255);

            imagefill($imageC, 0, 0, $bg_color);
            imagecopyresampled($imageC, $image, $x, $y, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

            //imagewebp($imageC);
            header('Content-Type: image/jpeg');
            $dataUri = imagejpeg($imageC, null, 30);
            imagedestroy($imageC);

            //$contents = ob_get_clean();
            //$dataUri = $contents;
        }
    }
    return $dataUri;
}
?>
<div>
    <!-- Tab  -->
    <div class="position-relative bg-white text-center z-index-2">
        <ul class="nav nav-classic nav-tab justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item active">
                <a class="nav-link active" id="pills-two-example1-tab" data-toggle="pill" href="#tab-promo" role="tab">
                    <h2 class="d-md-flex justify-content-md-center align-items-md-center">
                        <b>Промоции</b>
                    </h2>
                </a>
            </li>

            <!-- 
                        <li class="nav-item">
                            <a class="nav-link " id="pills-one-example1-tab" data-toggle="pill" href="#tab-feature" role="tab">
                                <div class="d-md-flex justify-content-md-center align-items-md-center">
                                    Featured
                                </div>
                            </a>
                        </li>
            
                                   <li class="nav-item">
                                        <a class="nav-link " id="pills-three-example1-tab" data-toggle="pill" href="#pills-three-example1" role="tab" aria-controls="pills-three-example1" aria-selected="false">
                                            <div class="d-md-flex justify-content-md-center align-items-md-center">
                                                Top Rated
                                            </div>
                                        </a>
                                    </li>-->
        </ul>
    </div>
    <!-- End Tab -->

    <!-- Tab Content -->
    <div class="tab-content" id="pills-tabContent">

        <!-- Tab Promo -->
        <div class="tab-pane fade pt-2 show active" id="tab-promo" role="tabpanel">

            <ul class="row list-unstyled products-group no-gutters">
                <?php foreach ($sales_products as $sales_product): ?>
                    <li class="js-product-item col-6 col-wd-2 col-md-4 product-item mb-5">
                        <div class="product-item__outer h-100">
                            <div class="product-item__inner px-xl-4 p-3">
                                <div class="product-item__body pb-xl-2">
                                    <!-- <div class="mb-2"><a href="../shop/product-categories-7-column-full-width.html" class="font-size-12 text-gray-5">Speakers</a></div> -->

                                    <?php
                                    $_basePrice = $sales_product['cenaKKC'] ?? 0;
                                    if (session() -> has('user_id')) {
                                        $_user_id                = session() -> get('user_id');
                                        $_klientIds_to_cenaIndiv = explode(',', $sales_product['klientIds_to_cenaIndiv'] ?? '');
                                        if (!empty($sales_product['cenaIndiv']) && in_array($_user_id, $_klientIds_to_cenaIndiv)) {
                                            $_basePrice = $sales_product['cenaIndiv'];
                                        } else {
                                            $_basePrice = $sales_product[$productPriceLevel] ?? 0;
                                        }
                                    } else {
                                        $_basePrice = $sales_product[$productPriceLevel] ?? 0;
                                   }

                                    $_promoPercent = getPromoPercentFromGensoftJson($sales_product['gensoft_json'] ?? null);
                                    $_promoPrice   = calculatePromoPrice($_basePrice, $_promoPercent);
                                    $_percent      = $_promoPrice !== null ? abs((float) $_promoPercent) : null;
                                    $_currentPrice = $_promoPrice ?? $_basePrice;
                                    $_displayBase  = get_valuta() === '€' ? ($_basePrice / 1.95583) : $_basePrice;
                                    $_displayPrice = get_valuta() === '€' ? ($_currentPrice / 1.95583) : $_currentPrice;
                                    ?>
                                    <?php if ($_percent !== null): ?>
                                        <span class="label-latest label-sale"><b>- <?= $_percent ?? '' ?> %</b></span>
                                    <?php endif; ?>

                                    <div class="mb-2" style="height: 200px;vertical-align: middle;display: table-cell;">
                                        <!-- site_url('product/' . $sales_product['product_id'])-->
                                        <a  class="d-block text-center img-fancybox" href="<?= $_ENV['app.imageDir'] . $sales_product['image'] ?>">
                                            <img class="lazy img-fluid" src="<?= $_ENV['app.imageDir'] . $sales_product['image'] ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" width="400" height="400" alt="...">
                                        </a>
                                    </div>

                                    <h5 class="mb-1 product-item__title">
                                        <a href="<?= site_url('product/' . $sales_product['product_id']) ?>" class="text-blue font-weight-bold" title="<?= $sales_product['product_name'] ?>"><?= $sales_product['product_name'] ?></a>
                                    </h5>

                                    <div class="ml-md-3 text-gray-9 font-size-14">Наличност:
                                        <?php
                                        $nalichClass = 'red';
                                        $nalichTxt   = 'не';

                                        if ($sales_product['nalichnost'] > 0) {
                                            $nalichClass = 'green';
                                            $nalichTxt   = 'да';
                                        }
                                        ?>
                                        <span class="text-<?= $nalichClass ?> font-weight-bold"><?= $nalichTxt ?></span>
                                    </div>

                                    <div class="border-top pt-2 h-15px flex-center-between flex-wrap"></div>
                                    <!-- стара цена-->
                                    <div class="css-old-price text-gray-100">
                                        <?= number_format($_displayBase, 2) ?>

                                        <small><?= get_valuta() ?></small>
                                        <?php if ($_percent !== null): ?>
                                            <small class="ml-1">(- <?= $_percent ?? '' ?> %)</small>
                                        <?php endif; ?>
                                    </div>


                                    <!-- нова цена-->
                                    <div class="text-center mb-1">
                                        <div class="prodcut-price text-danger">
                                            <div class="fw-bold"><?= preg_replace('/\.([0-9]*)/', '<sup class="font-size-14"> .$1</sup>', number_format($_displayPrice, 2)) ?>
                                                <small class="fw-bold"><?= get_valuta() ?></small>
                                                <span class="font-size-13 text-gray-100 fw-500"> <?= '/ ' . priceToEur2($_displayPrice) ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-1">
                                        <?php if (session() -> has('user_id')): ?>
                                            <?php if ($sales_product['nalichnost'] > 0): ?>  
                                                <div class=" input-group mb-1 prodcut-add-cart">
                                                    <div class="col border height-40 rounded-left-pill py-2 px-3">
                                                        <div class="js-quantity d-flex align-items-center">
                                                            <div class="">
                                                                <input class="js-result form-control h-auto border-0 rounded p-0 shadow-none text-center" type="text" value="1" autocomplete="off">
                                                            </div>

                                                            <div class="col-auto px-1">
                                                                <a class="js-minus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                                                    <small class="fas fa-minus btn-icon__inner"></small>
                                                                </a>

                                                                <a class="js-plus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                                                    <small class="fas fa-plus btn-icon__inner"></small>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <a href="javascript;" class="btn-add-cart btn-primary height-40 rounded-0 py-2 px-3" data-product-id="<?= $sales_product['product_id'] ?>" data-route="<?= route_to('Cart-addToCart') ?>">
                                                        <i class="ec ec-add-to-cart font-size-20"></i>
                                                    </a>
                                                </div>
                                            <?php else: ?>

                                                <div class="d-none d-xl-block">
                                                    <a class="m-auto btn-add-cart transition-3d-hover" style="background: #55b355;color: #fff;" title="За запитване  <?= $_ENV['app.phoneRequest'] ?>">
                                                        <i class="ec ec-phone font-size-20"></i>
                                                    </a>
                                                </div>
                                            <?php endif ?>

                                        <?php endif ?>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- END Tab Promo -->

        <!-- Tab feature -->
        <!--        <div class="tab-pane fade pt-2" id="tab-feature" role="tabpanel">
        
                    <ul class="row list-unstyled products-group no-gutters">
        <? //foreach ($featured_products as $featured_product):    ?>
                            <li class="col-6 col-wd-3 col-md-4 product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-xl-4 p-3">
                                        <div class="product-item__body pb-xl-2">
                                             <div class="mb-2"><a href="../shop/product-categories-7-column-full-width.html" class="font-size-12 text-gray-5">Speakers</a></div> 
                                            <h5 class="mb-1 product-item__title"><a href="<? // site_url('product/' . $featured_product['product_id'])                                               ?>" class="text-blue font-weight-bold"><? // $featured_product['product_name']                                               ?></a></h5>
        
                                            <div class="mb-2">
                                                <a href="<? // site_url('product/' . $featured_product['product_id'])                                               ?>" class="d-block text-center">
                                                    <img class="img-fluid" src="<? // $_ENV['app.imageDir'] . $featured_product['image']                                               ?>" alt="Image Description">
                                                </a>
                                            </div>
        
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100"><? // $featured_product['cenaKKC']                                               ?>лв.</div>
                                                </div>
        <? // if (session() -> has('user_id')):    ?>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="<? // site_url('product/' . $featured_product['product_id'])                                               ?>" class="btn-add-cart btn-primary transition-3d-hover"><i class="ec ec-add-to-cart" data-product-id="<? // $featured_product['product_id']                                               ?>"></i></a>
                                                    </div>
        <? // endif;   ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
        <? // endforeach    ?>                                                        
                    </ul>
                </div>-->
        <!-- END Tab feature -->

        <!--        <div class="tab-pane fade pt-2" id="pills-three-example1" role="tabpanel" aria-labelledby="pills-three-example1-tab">
                    <ul class="row list-unstyled products-group no-gutters">
        <? // foreach ($toprated_products as $toprated_product):     ?>
                            <li class="col-6 col-wd-3 col-md-4 product-item">
                                <div class="product-item__outer h-100">
                                    <div class="product-item__inner px-xl-4 p-3">
                                        <div class="product-item__body pb-xl-2">
                                             <div class="mb-2"><a href="../shop/product-categories-7-column-full-width.html" class="font-size-12 text-gray-5">Speakers</a></div> 
                                            <h5 class="mb-1 product-item__title"><a href="<? // site_url('product/' . $toprated_product['product_id'])                                                       ?>" class="text-blue font-weight-bold"><? // $toprated_product['product_name']                                                       ?></a></h5>
        
                                            <div class="mb-2">
                                                <a href="<? // site_url('product/' . $toprated_product['product_id'])                                                       ?>" class="d-block text-center"><img class="img-fluid" src="<? // $_ENV['app.imageDir'] . $toprated_product['image']                                                       ?>" alt="Image Description"></a>
                                            </div>
        
                                            <div class="flex-center-between mb-1">
                                                <div class="prodcut-price">
                                                    <div class="text-gray-100"><? // $toprated_product['price']                                                       ?>лв.</div>
                                                </div>
        <? // if (session() -> has('user_id')):     ?>
                                                    <div class="d-none d-xl-block prodcut-add-cart">
                                                        <a href="<? // site_url('product/' . $toprated_product['product_id'])                                                       ?>" class="btn-add-cart btn-primary transition-3d-hover"><i class="ec ec-add-to-cart" data-product-id="<? // $toprated_product['product_id']                                                       ?>"></i></a>
                                                    </div>
        <? // endif;    ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
        <? // endforeach;     ?>
                    </ul>
                </div>-->

    </div>
    <!-- End Tab Content -->
</div>
