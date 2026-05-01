<?php

$_isDDS    = !empty($settingsJson -> prices_with_dds);
// Етикет за ДДС
$_ddsLabel = $_isDDS ? 'с ДДС' : 'без ДДС';
?>

<table class="css-cart-table table table-hover">
    <thead class="banner-bg">
        <tr>
            <th class="text-center align-middle"></th>
            <th class="col-1 text-center align-middle">Снимка</th>
            <th class="text-center align-middle">Име на продукта</th>
            <th class="text-center align-middle">Количество</th>
            <th class="text-center align-middle">Ед. цена <?= $_ddsLabel ?></th>
            <th class="text-center align-middle">Обща цена <?= $_ddsLabel ?></th>
        </tr>
    </thead>

    <tbody>
        <?php

        foreach ($products as $product):
            $_productJson = json_decode($product['product_json']) -> {$product['product_id']};
            $_qty         = $_productJson -> qty;
            $_price       = sprintf("%.2f", $_productJson -> price);
            $_total_price = sprintf("%.2f", ($_qty * $_price));
            ?>
            <tr>
                <td class="text-center align-middle"></td>  

                <td class="p-1 align-middle text-center">
                    <a href="<?= route_to('Shop-singleProduct', $product['product_id']) ?>">
                        <img class="img-fluid max-width-100 p-1 border border-color-1" src="<?= $product['image'] ? $_ENV['app.imageDir'] . $product['image'] : '' ?>" onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';" alt="...">
                    </a>
                </td>

                <td class="align-middle text-center">          
                    <a href="<?= route_to('Shop-singleProduct', $product['product_id']) ?>" class="text-blue"><?= $product['product_name'] ?></a>
                </td>

                <td class="align-middle text-center"><?= $_qty ?></td>
                <td class="align-middle text-center"><?= $_price . ' ' . get_valuta() ?> </td>
                <td class="align-middle text-center"><?= $_total_price . ' ' . get_valuta() ?></td>                                                  
            </tr>
        <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr>
            <td colspan="5">
                <span class="float-right fw-bold text-dark">Сума без ДДС:</span>
            </td>

            <td class="fw-bold text-dark text-center"><?= sprintf("%.2f %s", $orderData -> total_price / $dds, get_valuta()) ?> </td>
        </tr>

        <tr>
            <td colspan="5">
                <span class="float-right fw-bold text-dark">ДДС:</span>
            </td>

            <td class="fw-bold text-dark text-center"><?= sprintf("%.2f %s", ($orderData -> total_price * 0.2), get_valuta()) ?></td>
        </tr>

        <?php

        $_transportenRazhod = $orderData -> transportenRazhod ?? 0;
        $_freeDostavkaPrice = $settings_portal['order']['order']['freeDostavkaPrice'] ?? 0;

        if ($_transportenRazhod && $orderData -> total_price < $_freeDostavkaPrice):
            ?>
            <tr>
                <td colspan="5">
                    <span class="float-right fw-bold text-dark">Доставка:</span>
                </td>

                <td class="fw-bold text-dark text-center"><?= sprintf("%.2f %s", $_transportenRazhod, get_valuta()) ?></td>
            </tr>

            <?php

        elseif (!empty($_freeDostavkaPrice) && $orderData -> total_price >= $_freeDostavkaPrice):
            ?>
            <tr>
                <td colspan="5">
                    <span class="float-right fw-bold text-dark">Доставка:</span>
                </td>

                <td class="fw-bold text-dark text-center">безплатна</td>
            </tr>
        <?php endif ?>

        <tr>
            <td colspan="5">
                <span class="float-right fw-bold text-dark">Тотал с ДДС:</span>
            </td>

            <td class="fw-bold text-dark text-center"><?= sprintf("%.2f %s", ($orderData -> total_price * 1.2 + $orderData -> transportenRazhod), get_valuta()) ?></td>
        </tr>
    </tfoot>
</table>
