<div id="js-filter-manufacture" class="border-1">
    <ul class="w-100 treeview p-0 m-0">
        <li>
            <b class="fw-bolder">Производител</b>

            <ul class="child border-top2 banner-bg ml-3" style="overflow: auto;max-height: 800px;">
                <?php
                $_f_brandIdRaw = $_GET['f_brandId'] ?? '';
                $_f_brandIds = array_values(
                    array_filter(preg_split('/[_,]+/', (string) $_f_brandIdRaw), 'strlen')
                );

                foreach ($brands as $к => $brand):
                    ?>

                    <li data-brand-id="<?= $brand['brand_id'] ?>">
                        <a class="text-black no-hover">
                            <input class="js-manufactureChk js-productFilter" 
                                   data-tip="manufacture"
                                   type="checkbox" 
                                   name="f_brandId[]" 
                                   value="<?= $brand['brand_id'] ?>"  
                                   <?= in_array((string) $brand['brand_id'], $_f_brandIds, true) ? 'checked' : '' ?>>
                                   <?= $brand['brandTxt'] ?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </li>
    </ul>
</div>
