
<div id="js-filter-models" class="border-1 <?= empty($_GET['f_brandId']) ? 'hide' : '' ?>">

    <ul class="w-100 treeview p-0">
        <li>
            <?php if (!empty($_GET['f_brandId'])): ?>  
                <span class="node-minus p-0 w-10px"></span>
                <b class="fw-bolder">Модели</b>
                <!-- филтър по модел -->
                <input id="filter-modelsByName" class="my-1 form-control h-1_5rem rounded-0 text-center" type="text" placeholder="търсене по име на модел" style="background: #EAE8FF">

                <?php
                $_f_brandIdRaw  = $_GET['f_brandId'] ?? '';
                $_f_brandIds    = array_values(
                    array_filter(preg_split('/[_,]+/', (string) $_f_brandIdRaw), 'strlen')
                );
                $_f_models      = $_GET['f_models'] ?? [];
                $_f_modelsOther = $_GET['f_modelsOther'] ?? [];
                $_modelNameArr  = array_column($brandsModels, 'model');

                $liItems = [];  // Array to hold all the <li> tags

                foreach ($brandsModels as $m) {

                    $productIds            = explode(',', $m['productIds']);
                    $brandTxt              = ucfirst(strtolower($m['brandTxt']));
                    $isHidden              = !in_array((string) $m['brand_id'], $_f_brandIds, true) ? 'hide' : '';
                    $urlParams_models      = !empty($_f_models) && !empty($_f_brandIds) ? explode('_', $_f_models) : [];
                    $urlParams_modelsOther = !empty($_f_modelsOther) && !empty($_f_brandIds) ? explode('_', $_f_modelsOther) : [];

                    // Generate <li> for the main model
                    $liItems[] = [
                        'content' => "<li class='$isHidden' data-brand-id='{$m['brand_id']}'>
                                        <a class='no-hover'>
                                            <input class='js-models js-productFilter' type='checkbox' data-product-id='{$m['product_id']}' value='{$m['model']}'" . (in_array($m['model'], $urlParams_models) && !in_array($m['model'], $urlParams_modelsOther) ? ' checked' : '') . ">
                                            $brandTxt {$m['model']}
                                            <small>(" . count($productIds) . ")</small>
                                        </a>
                                    </li>",
                        'name'    => "$brandTxt {$m['model']}"
                    ];

                    // Sort $model_json alphabetically by name (case-insensitive)
                    $_model_additional = explode(',', $m['additional_models']);
                    if (!empty($_model_additional)) {
                        foreach ($_model_additional as $value) {

                            if (!empty($value) && !in_array($value, $_modelNameArr)) {
                                $modelName = $m['model'] . '_' . $value;

                                $liItems[] = [
                                    'content' => "<li class='$isHidden' data-brand-id='{$m['brand_id']}'>
                                                    <a class='no-hover'>
                                                        <input class='js-models js-productFilter' type='checkbox' data-product-id='{$m['product_id']}' value='$value'" . (in_array($value, $urlParams_models) ? ' checked' : '') . ">--
                                                        $brandTxt $value
                                                        <small>(" . count($productIds) . ")</small>
                                                    </a>
                                                </li>",
                                    'name'    => "$brandTxt $value"
                                ];
                            }
                        }
                    }
                }

                // Sort the <li> items by the 'name'
                usort($liItems, fn($a, $b) => strcasecmp($a['name'], $b['name']));

                echo '<ul class="feature-block filters child border-top2 banner-bg ml-3" style="overflow: auto; max-height: 520px;">';
                foreach ($liItems as $item) {
                    echo $item['content'];
                }
                echo '</ul>';
                ?>

            </li>
        <?php endif ?>
    </ul>
</div>
