<?php
if (!empty($_GET['f_price'])) {
    $f_price   = explode('_', $_GET['f_price']);
    $_f_minPrice = $f_price[0];
    $_f_maxPrice = $f_price[1];
}
?>
<div class="range-slider">
    <h4 class="font-size-14 mb-3 font-weight-bold">Цена</h4>

    <input class="js-range-slider" type="text"
           data-extra-classes="u-range-slider u-range-slider-indicator u-range-slider-grid"
           data-type="double"
           data-grid="false"
           data-hide-from-to="true"
           data-prefix="$"
           data-min="0"
           data-max="<?= $maxPrice ?>"
           data-from="<?= $_f_minPrice ?? 0 ?>"
           data-to="<?= $_f_maxPrice ?? $maxPrice ?>"
           data-result-min="#rangeSliderExample3MinResult"
           data-result-max="#rangeSliderExample3MaxResult">

    <div class="mt-1 text-gray-111 d-flex mb-4">
        <span class="mr-0dot5">Цена:</span>                          
        <span id="rangeSliderExample3MinResult"></span>
        <input type="hidden" name="min_price" id="min_price">
        <span><?= get_valuta() ?></span>

        <span class="mx-0dot5"> — </span>
        <span id="rangeSliderExample3MaxResult"></span>
        <input type="hidden" name="max_price" id="max_price">
        <span><?= get_valuta() ?></span>
    </div>
</div>
