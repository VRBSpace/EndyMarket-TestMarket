<!-- главни категории -->
<div id="js-filter-rootCategory" class="border-1">

    <ul class="w-100 treeview p-0 m-0">
        <li>
            <span class="node-minus p-0 w-10px"></span>
            <b class="font-size-14 font-weight-bold">Категории за <?= $_GET['f_models'] ?></b> 

            <ul class="child border-top2 banner-bg ml-3" style="overflow: auto;max-height: 200px;">
                <?php
                $_f_rootCatId = isset($_GET['f_rootCatId']) ? $_GET['f_rootCatId'] : '';

                foreach ($categories as $к => $c):
                    if (in_array($c['category_id'], array_column($product_to_category, 'categoryRoot_id'))):
                        ?>
                        <li>
                            <a class="text-black no-hover">
                                <input class="js-rootCaytegoryChk js-productFilter" type="radio" name="radio" value="<?= $c['category_id'] ?>"  <?= $c['category_id'] == $_f_rootCatId ? 'checked' : '' ?>> 
                                <?= $c['category_name'] ?>
                            </a>
                        </li>
                    <?php endif ?>
                <?php endforeach ?>
            </ul>
        </li>
    </ul>

    <!-- подкатегории -->
    <div id="js-filter-subCategory" class="border-1 <?= empty($_GET['f_rootCatId']) ? 'hide' : '' ?>">

        <ul class="w-100 treeview p-0 m-0">
            <li>
                <span class="node-minus p-0 w-10px"></span>
                <b class="font-size-14 font-weight-bold">Подкатегории за <?= $_GET['f_models'] ?></b> 

                <ul class="child border-top2 banner-bg ml-3" style="overflow: auto;max-height: 200px;">
                    <?php
                    $_f_subCatIds        = $_GET['f_subCatIds'] ?? [];
                    $urlParams_subCatIds = [];

                   // dd($categories[0]);
                    if (!empty($_f_subCatIds)) {
                        $urlParams_subCatIds = explode('_', $_f_subCatIds);
                    }

                    foreach ($categories as $к => $c):
                        $_childCategories = $c['children'];

                        foreach ($_childCategories as $child) :
                            if (in_array($child['category_id'], array_column($product_to_category, 'category_id'))) :

                                $count = count(array_filter(array_column($product_to_category, 'category_id'), function ($id) use ($child) {
                                            return $id == $child['category_id'];
                                        }));
                                ?>
                                <li class="<?= empty($_childCategories) || $c['category_id'] !== $_f_rootCatId ? 'hide' : '' ?>" data-rootCat-id="<?= $c['category_id'] ?>" data-subCat="<?= $child['category_id'] ?>">
                                    <a class="text-black no-hover">
                                        <input class="js-subCat js-productFilter" 
                                               type="checkbox" 
                                               value="<?= $child['category_id'] ?>"
                                               <?= in_array($child['category_id'], $urlParams_subCatIds) ? 'checked' : '' ?>
                                               >
                                               <?= $child['category_name'] ?>
                                        <small>(<?= $count ?>)</small>
                                    </a>
                                </li>
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php endforeach ?>
                </ul>
            </li>
        </ul>  

    </div>