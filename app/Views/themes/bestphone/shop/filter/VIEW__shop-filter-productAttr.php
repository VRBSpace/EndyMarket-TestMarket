<?php
$_sort                     = $settings_portal['func']['sort'] ?? '';
?>
<style>
    .treeview *{
        list-style-type:none;
        margin:0;
        padding:0;
    }
    .treeview li{
        line-height:25px;
    }
    .treeview label{
        font-size:100%;
    }
    .treeview ul{
        margin-left: 35px;
        margin-bottom:14px;
        list-style-type:none;

    }

    .treeview .btn-wrpr{
        float: right;
    }


    .treeview ul.child .label{
        background: #69c1de !Important;
    }

    .treeview .node-plus,
    .treeview .node-minus,
    .treeview .node-none,
    .treeview .node-plus2,
    .treeview .node-minus2
    {
        margin:0;
        padding-left:8px;
        font-size:170%;
        display:inline-block;
        cursor:pointer;
        width: 30px;
    }
    .treeview .node-none{
        width:22px;
    }

    .treeview .node-plus::before,
    .treeview .node-plus2::before {
        content: "+";
    }

    .treeview .node-minus::before,
    .treeview .node-minus2::before
    {
        content: "-";
    }

    .treeview .node-plus:hover{
        color:#26c6da
    }

    .treeview .node-minus:hover{
        color:#e94949
    }

</style>

<?php
$_urlParams_productAttrIds = isset($_GET['productAttrIds']) ? explode('_', $_GET['productAttrIds'][0]) : [];

?>
<?php if (!empty($productAttr)) : ?>         
    <div class="mb-6">
        <div id="js-filter-productAttr" class="pb-4 mb-4">

            <ul class="w-100 treeview p-0">
                <?php
                $_categoryAttrIds = array_column($productAttr, 'category_characteristic_id');

                $_productIds = array_reduce($productAttr, function ($result, $product) {
                    $result[$product['product_id']] = $product['product_id'];
                    return $result;
                }, []);

                foreach ($productAttr as $product) {
                    if (in_array($product['product_id'], $_productIds)) {
                        $groupedProductAttr[$product['category_characteristic_id']][$product['product_characteristic_text']][] = $product;
                    }
                }

                foreach ($subCategoryAttr as $value) {
                    if (in_array($value['category_characteristic_id'], $_categoryAttrIds)) {
                        ?>
                        <li>
                            <span class="node-minus"></span>
                            <b><?= $value['value'] ?></b>

                            <ul class="feature-block filters child border-top2 banner-bg px-2">
                                <?php
                                if (isset($groupedProductAttr[$value['category_characteristic_id']])) {


                                    foreach ($groupedProductAttr[$value['category_characteristic_id']] as $attrValue => $products) {
                                        ?>
                                        <li>
                                            <!-- Checkboxes -->
                                            <?php
                                            $urlParams = urldecode(http_build_query([
                                                'categoryId'     => $_GET['categoryId'] ?? '',
                                                'sort'           => str_replace('&sort=', '', $_sort),
                                                'f_poductIds'    => implode(',', array_column($products, 'product_id')),
                                                'f_catCharId'    => $value['category_characteristic_id'],
                                                'f_podCharValId' => array_column($products, 'product_characteristic_value_id')[0]
                                                    ]))
                                            ?>
                                            <a href="<?= current_url() . '?' . $urlParams ?>">
                                                <input class="attribute js-productFilter" 
                                                       type="checkbox" 
                                                       data-route="<?= route_to('Filters-index', $_GET['categoryId'] ?? $filter_categoryId) ?>" 
                                                       data-category-characteristic-id="<?= $value['category_characteristic_id'] ?>" 
                                                       data-product-charvalue-id="<?= array_column($products, 'product_characteristic_value_id')[0] ?>" 
                                                       data-product-attr-id="<?= implode(',', array_column($products, 'product_id')) ?>" 
                                                       value="<?= $attrValue ?>">&nbsp;<?= $attrValue . '<small> (' . (count($products)) . ')</small>' ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
 endif ?>