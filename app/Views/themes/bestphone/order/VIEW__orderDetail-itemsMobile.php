<table id="cart-table" class="css-cart-table cart-table table table-hover">
    <tbody class="notAutoNum">
        <?php
        foreach ($products as $product):
            $_productJson = json_decode($product['product_json']) -> {$product['product_id']};
            $_qty         = $_productJson -> qty;
            $_price       = sprintf("%.2f", $_productJson -> price);
            $_total_price = sprintf("%.2f", ($_qty * $_price));
            ?>
            <tr>
                <td>
                    <!-- Продукт 1 -->
                    <div class="cart-item">
                        <div class="p-1 d-flex align-items-center fw-bold">
                            <a class="text-gray-90" href="<?= route_to('Shop-singleProduct', $product['product_id']) ?>"><?= $product['product_name'] ?></a>
                        </div>

                        <div class="p-2">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <a class="img-fancybox" href="<?= !is_null($product['image']) ? $_ENV['app.imageDir'] . $product['image'] : '' ?>">
                                        <img class="img-fluid border border-color-1" src="<?= $product['image'] ? $_ENV['app.imageDir'] . $product['image'] : '' ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="...">
                                    </a>
                                </div>

                                <div class="col">
                                    <div class="row text-left">
                                        <div class="col">Количество: </div>
                                        <div class="col-5"> 
                                            <span class="js-zenaProdava"><?= $_qty ?></span>
                                        </div>
                                    </div>

                                    <div class="row text-left">
                                        <div class="col">Ед. цена: </div>
                                        <div class="col-5"> 
                                            <span class="js-zenaProdava"><?= $_price ?> <?= get_valuta() ?></span>
                                        </div>
                                    </div>

                                    <div class="row text-left">
                                        <div class="col">Общо без ДДС: </div>
                                        <div class="js-total col-5"> 
                                            <?= $_total_price ?> <?= get_valuta() ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>

    <tfoot style="background: #cfcfcf;">

        <tr id="js-total_bez_dds">
            <td>
                <div class="row col">
                    <div class="col">Сума без ДДС:</div> 
                    <div class="suma col text-right">
                        <?= sprintf("%.2f", $orderData -> total_price) ?> <?= get_valuta() ?>
                    </div>
                </div>
            </td>
        </tr>

        <tr id="js-dds">
            <td>
                <div class="row col">
                    <div class="col">ДДС:</div> 
                    <div class="suma col text-right">
                        <?= sprintf("%.2f", ( $orderData -> total_price * 0.2)) ?> <?= get_valuta() ?>
                    </div>
                </div> 
            </td>
        </tr> 

        <!-- транспортни разходи -->
        <?php
        $_transportenRazhod = $orderData -> transportenRazhod ?? 0;
        $_freeDostavkaPrice = $settings_portal['order']['order']['freeDostavkaPrice'] ?? 0;

        if (!empty($_transportenRazhod) && $orderData -> total_price < $_freeDostavkaPrice):
            ?>
            <tr id="deliveryRow">
                <td>
                    <div class="row col">
                        <div class="col">Доставка:</div> 
                        <div class="col text-right">
                            <?= sprintf("%.2f", $_transportenRazhod) ?> <?= get_valuta() ?>
                        </div>
                    </div>  
                </td>
            </tr>

            <?php
        elseif (!empty($_freeDostavkaPrice) && $orderData -> total_price >= $_freeDostavkaPrice):
            ?>
            <tr>
                <td>
                    <div class="row col">
                        <div class="col">Доставка:</div> 
                        <div class="suma col text-right">безплатна</div>
                    </div> 
                </td>
            </tr>
        <?php endif ?>

        <tr id="js-total_s_dds">
            <td>
                <div class="row col">
                    <div class="col">Тотал с ДДС:</div> 
                    <div class="suma col text-right">
                        <?= sprintf("%.2f", ( $orderData -> total_price * 1.2 + $orderData -> transportenRazhod)) ?> <?= get_valuta() ?>
                    </div>
                </div>  
            </td>
        </tr>
    </tfoot>
</table>