
<?php if (!empty($res)): ?>
    <div class="row col">
        <div class="col flex-center-between">

            <section class="range-slider w-25">
                <span class="mr-0dot5">Цена:</span>   
                <input class="js-filter-range-cenova" type="text"
                       data-route="<?= route_to('CenovaLista-filter', $_GET['categoryId'] ?? 'null') ?>"
                       data-extra-classes="u-range-slider u-range-slider-indicator u-range-slider-grid"
                       data-type="double"
                       data-grid="false"
                       data-hide-from-to="true"
                       data-prefix="$"
                       data-min="0"
                       data-max="<?= $maxPrice ?>"
                       data-from="0"
                       data-to="<?= $maxPrice ?>"
                       data-result-min="#rangeSliderExample3MinResult"
                       data-result-max="#rangeSliderExample3MaxResult">

                <div class="text-gray-111 d-flex mb-1">                       
                    <span id="rangeSliderExample3MinResult"></span>
                    <input type="hidden" name="min_price" id="min_price">
                    <span>&nbsp;<?= get_valuta() ?></span>

                    <span class="mx-0dot5"> — </span>
                    <span id="rangeSliderExample3MaxResult"></span>
                    <input type="hidden" name="max_price" id="max_price">
                    <span>&nbsp;<?= get_valuta() ?></span>
                </div>
            </section> 

            <div class="custom-control custom-checkbox">
                <input id="js-nalichChk" class="js-productFilter custom-control-input" data-route="<?= route_to('CenovaLista-filter', $cenovaListaId) ?>" type="checkbox" value="1">
                <label class="custom-control-label" for="js-nalichChk">В наличност</label>
            </div>

            <input id="js-filter-productAndBrandName" class="form-control max-width-300 max-width-160-sm" data-route="<?= route_to('CenovaLista-filter', $cenovaListaId) ?>" type="text" placeholder="филтър по име и производител">

            <!-- Select -->
            <select id="js-filter-sorting-select" class="js-productFilter form-control selectpicker max-width-150 max-width-160-sm" data-route="<?= route_to('CenovaLista-filter', $cenovaListaId) ?>" data-style="btn bg-white font-weight-normal  border  bg-lg-down-transparent border-lg-down-0">
                <option value="">Сортирай</option>
                <option value="product_name ASC">Име Възходящ ред</option>
                <option value="product_name DESC">Име Низходящ ред</option>
                <option value="<?= session() -> has('user_id') ? $productPriceLevel : 'cenaKKC' ?> DESC">Цена низходящо</option>
                <option value="<?= session() -> has('user_id') ? $productPriceLevel : 'cenaKKC' ?> ASC">Цена възходящо</option>
            </select> 
            <!-- End Select -->

            <?php if (!ISMOBILE): ?>
                <a id="js-xlsExportProducts" class="btn ml-4 p-2 btn-success"  data-route="<?= route_to('Shop-xls_export_allProducts') ?>" data-product-ids="<?= empty($res) ? '' : implode(',', array_column($res, 'product_id')) ?>" data-name="ценова-листа-<?= $cenovaListaName ?>" href="javascript:;" title="Еспортиране на всички продукти от съответната подкатегория">
                    <i class="fa fa-file-excel"></i>&nbsp;Xls експорт
                </a>  
            <?php endif ?>
        </div>
    </div>
    <?php

 endif ?>
