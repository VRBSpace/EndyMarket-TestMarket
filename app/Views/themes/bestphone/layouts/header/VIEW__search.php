<!-- Search bar -->
<?php
$totalProducts = isset($totalShopProducts)
    ? (int) $totalShopProducts
    : (!empty($countProductsBySubCategory) ? array_sum($countProductsBySubCategory) : 0);
?>
<div class="w-100 align-self-center position-relative">

    <!-- Search-Form -->
    <form id="product-search-form" class="js-focus-state" method='GET' action="<?= site_url('/shop') ?>">
        <label class="sr-only" for="searchproduct">Търсене</label>

        <div class="input-group">
            <input id="js-searchproduct-item" class="form-control py-2 pl-5 font-size-15 border-right-0 height-40 border-width-2 css-header-input border-primary" name="searchName"  data-route="<?= route_to('Shop-searchProducts') ?>" type="text" placeholder="Търси в <?= number_format($totalProducts, 0, '', ' ') ?> продукта"  required>

            <div class="input-group-append">    
                <button type='button' class="btn height-40 py-2 px-3 css-header-btn css-header-btn-search" id="searchProduct1">
                    <span class="ec ec-search font-size-24"></span>
                </button>
            </div>
        </div>
    </form> 

    <div id="search1-result" class="w-100 p-3 bg-white border position-absolute" style="display:none;overflow: auto;z-index:1000000"></div>
    <!-- End Search-Form -->
</div>

<div id="css-searchBoxEffect" class="opacity-md"></div>


