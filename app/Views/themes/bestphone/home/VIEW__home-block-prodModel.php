<?php $_imageBanersDir = $_ENV['app.imageDir'] ?>
<h3 class="font-weight-bold text-center <?= str_contains(uri_string(), 'shop') ? 'h5' : '' ?>">
<!--    <span class="css-triangle-down"></span>  
    <span class="css-triangle-down"></span>  
    <span class="css-triangle-down"></span> 
    ИЗБЕРИ БЪРЗО СВОЯ ТЕЛЕФОН ПО МАРКА И МОДЕЛ ОТ ТУК 
    <span class="css-triangle-down"></span>
    <span class="css-triangle-down"></span>  
    <span class="css-triangle-down"></span> -->
</h3>

<div class="d-flex flex-nowrap flex-xl-wrap1 overflow-auto overflow-xl-visble border-bottom33 border-bottom-gray-1">
    <?php
    foreach ($productModelsTree ?? [] as $k => $row):
        ?>

        <div class="banner-bg  <?= ISMOBILE ? '' : 'col-md-6 col-xl-4 col-wd-3' ?> mb-2 mb-xl-0  py-2 d-flex flex-shrink-0 flex-xl-shrink-1">
       
            <!-- .min-height-146  -->
            <a class="js-open-brandModel-modal d-flex p-1 p-xl-2 p-wd-1 m-auto align-items-center text-gray-90 fade_open" href="javascript:;">

                <div class="media1 align-items-center">
                    <div class="max-width-148 <?= ISMOBILE ? 'w-50' : '' ?>">
                        <img class="css-img-hover img-fluid rounded-bottom-pill rounded-top-left-pill border" src="<?= $_imageBanersDir . $row['image_brand'] ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="..." title="изберете модел">
                    </div>

                    <div class="ml-4 media-body">
                        <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                            <? // $row['brandTxt'] ?>
                        </div>
                    </div>
                </div>
            </a>
        
            <!-- модал ф. за моделите по марка -->
            <div class="modal well fade show p-0 m-0 mw-100" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" role="dialog" aria-hidden="true" style="top:10px;overflow-x: hidden;">
                <div class="modal-dialog modal-dialog-centered modal-fullscreen-md-down m-auto mw-100 px-2" role="document" >
                    <div class="modal-content">
                        <div class="modal-header p-1 text-center text-white" style="background: #0B027B">  
                            <h2 class="modal-title w-100 fw-bold"><?= $row['brandTxt'] ?>
                                <br>
                                <small class="fw-bold h4">модели</small>
                            </h2>

                            <button type="button" class="btn-close btn rounded-0 p-1 btn-white">x</button>
                        </div>

                        <div class="modal-body p-2">
                            <!-- филтър по модел -->
                            <input id="filter-modelsByName" class="d-block mx-auto my-1 form-control h-2rem text-center" type="text" placeholder="филтър по модел" style="background: #EAE8FF">

                            <?php
                            $_totalModels = count($row['children'] ?? []);
                            $_perColumn   = 10; // Брой елементи на колона
                            ?>

                            <div class="d-flex overflow-auto js-mega-menu u-header__mega-menu-wrapper p-0" style="font-size: 16px;max-width: 100vw">

                                <?php
                                if (!empty($row['children'])) {

                                    $output = '<div class="col js-models justify-content-center d-flex text-nowrap border-primary"><ul class="u-header__sub-menu-nav-group">';

                                    foreach ($row['children'] as $c => $child) {
                                        $_baseHref = sprintf(
                                                '/shop?catToModel=1&f_brandId=%s&brandTxt=%s&f_models=%s&f_modelAdditional=%s',
                                                $child['brand_id'],
                                                urlencode($row['brandTxt']),
                                                urlencode($child['model']),
                                                str_replace(',', '_', $child['additional_models'] ?? '')
                                        );

                                        // Add model link
                                        $output .= sprintf(
                                                '<li><a class="no-hover1 nav-link text-black p-1 fw-bold" href="%s"><small class="mr-1">%d.</small> %s</a></li>',
                                                $_baseHref,
                                                $c + 1,
                                                htmlspecialchars($child['model'])
                                        );

                                        // Wrap columns
                                        if (($c + 1) % $_perColumn === 0 && $c + 1 < $_totalModels) {
                                            $output .= '</ul></div><div class="col js-models justify-content-center d-flex text-nowrap border-left border-primary"><ul class="u-header__sub-menu-nav-group">';
                                        }
                                    }
                                    $output .= '</ul></div>';
                                    echo $output;
                                }
                                ?>

                            </div>
                        </div>

                        <div class="modal-footer" style="background: #0B027B"></div>
                    </div>
                </div>
            </div>  
        </div>

    <?php endforeach ?> 
</div>

