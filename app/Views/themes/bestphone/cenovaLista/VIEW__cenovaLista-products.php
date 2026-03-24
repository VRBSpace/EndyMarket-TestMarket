<ul id="js-cenova-ul" class="css-counter-ul d-block list-unstyled products-group prodcut-list-view-small">
    <?php
    foreach ($res as $key => $c):
        $_user_id                = session() -> get('user_id');
        $_model_json             = json_decode($c['model_json'] ?? '', true);
        $_klientIds_to_cenaIndiv = explode(',', $c['klientIds_to_cenaIndiv']);
        $_compatibleModels       = '';

        if (!empty($_model_json)) {
            $_compatibleModels = implode(', ', array_values($_model_json));
        }
        ?>

        <li class="js-product-item pl-3 product-item" data-product-name="<?= $c['product_name'] ?>" data-product-brand="<?= $c['brandTxt'] ?>">

            <div class="product-item__outer w-100">
                <div class="product-item__inner remove-prodcut-hover py-4 row border border-bottom-gray  border-top-0 border-right-0  border-left-0">

                    <!-- снимка -->
                    <div class="product-item__header col-6 col-md-2">
                        <div class="mb-2">
                            <a class="d-block text-center img-fancybox1" href="<?= site_url('product/' . $c['product_id']) ?>">
                                <img class="img-fluid" src="<?= $_ENV['app.imageDir'] . $c['image'] ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="...">
                            </a>
                        </div>
                    </div>

                    <div class="product-item__body col-5 col-md-6">
                        <div class="pr-lg-10">

                            <span class="js-productName hide"><?= $c['product_name'] ?></span>  
                            <!-- продукт име -->
                            <h4 class="mb-2 <?= (ISMOBILE ?? $ISMOBILE ) ? 'font-size-14' : 'product-item__title font-size-18' ?>">
                                <a href="<?= site_url('product/' . $c['product_id']) ?>" class="text-blue font-weight-bold"><?= $c['product_name'] ?></a>
                            </h4>

                            <!-- подкатегория -->
                            <div class="mb-2"><a href="<?= '/shop?categoryId=' . $c['category_id'] ?>" class="font-size-12 text-gray-5"><?= $c['category_name'] ?></a></div>

                            <div>
                                Производител: <b><?= $c['brandTxt'] ?></b>
                            </div>

                            <div>
                                <?php
                                if (!empty($_compatibleModels)) {
                                    echo '<span class="text-danger">Съвместимост с модели: </span>' . $_compatibleModels;
                                }
                                ?>
                            </div>
                            <div class="text-gray-9 font-size-14">Наличност:
                                <?php
                                $nalichClass = 'red';
                                $nalichTxt   = 'не';

                                if ($c['nalichnost'] > 0) {
                                    $nalichClass = 'green';
                                    $nalichTxt   = 'да';
                                }
                                ?>
                                <!--  за сортировката-->
                                <span class="js-qty hide"><?= $c['nalichnost'] > 0 ? 1 : 0 ?></span>
                                <span class="text-<?= $nalichClass ?> font-weight-bold"><?= $nalichTxt ?></span>
                            </div>

                            <br>

                            <?php
                            $_price = !empty($c['cenaIndiv']) && in_array($_user_id, $_klientIds_to_cenaIndiv) ? $c['cenaIndiv'] : $c[$productPriceLevel];

                            if ($c['is_promo'] && !empty($c['zavishena_zena'])) {
                                $_price = $c['cenaPromo'];

                                $_zavishena_zena = $c['zavishena_zena'] - $_price;
                                $_percent        = ($_zavishena_zena / $c['zavishena_zena']) * 100;

                                $_percent = abs((float) number_format($_percent, 2, ',', ' '));
                                echo '<span class="label-latest label-sale"><b>- ' . ( $_percent ?? '') . '%</b></span>';
                            }
                            ?>

                            <div class="prodcut-price d-none">
                                <div class="text-gray-100"><?= $_price ?></div>
                            </div>

                            <u>Кратко описание:</u><br>
                            <?= $c['short_description'] ?>
                        </div>
                    </div>

                    <div class="product-item__footer mt-3 col-md-4 d-block">
                        <div class="mb-2 flex-center-between">
                            <!-- Price -->
                         <!--  за сортировката-->
                            <span class="js-price hide"><?= $_price ?></span>
                            <div class="col prodcut-price">

                                <div class="text-gray-100 font-size-24 fw-bold  <?= $c['is_promo'] && $c['zavishena_zena'] ? 'text-danger' : '' ?>"><?= preg_replace('/\.([0-9]*)/', '<sup class="font-size-14"> .$1</sup>', number_format($_price, 2)) ?>
                                    <small class="fw-bold"><?= get_valuta() ?></small>
                                    <br>
                                    <?= priceToEur($_price) ?>
                                </div>

                                <?php if ($c['is_promo'] && !empty($c['zavishena_zena'])): ?>
                                    <div class="css-old-price text-gray-100 font-size-18 ">
                                        <?= $c['zavishena_zena'] ?>
                                        <small><?= get_valuta() ?></small>
                                    </div>
                                <?php endif ?> 
                            </div>

                            <?php if ($c['nalichnost'] > 0): ?> 
                                <!-- Quantity -->
                                <div class="col border rounded-pill py-2 px-3">
                                    <div class="js-quantity d-flex align-items-center">
                                        <div class="">
                                            <input class="js-result form-control h-auto border-0 rounded p-0 shadow-none text-center" type="text" value="1">
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
                                <!-- End Quantity -->

                                <div class="col prodcut-add-cart">
                                    <span class="btn-add-cart btn-primary transition-3d-hover" data-product-id="<?= $c['product_id'] ?>" data-route="<?= route_to('Cart-addToCart') ?>">
                                        <i class="ec ec-add-to-cart" ></i>
                                    </span>
                                </div>
                            <?php else: ?>
                                <div class="col-1"></div>

                                <div class="col">
                                    <a class="m-auto btn-add-cart transition-3d-hover" style="background: #55b355;color: #fff;" title="За запитване <?= $_ENV['app.phoneRequest'] ?>">
                                        <i class="ec ec-phone"></i>
                                    </a>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach ?>
</ul>
