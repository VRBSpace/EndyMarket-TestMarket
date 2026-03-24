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
$_f_podCharValId = !empty($_GET['f_podCharValId']) ? explode('_', $_GET['f_podCharValId']) : null;
//$_f_catCharId    = !empty($_GET['f_catCharId']) ? explode('_', $_GET['f_catCharId']) : null;
?>

<?php 

if (!empty($filter_groups)) : ?>        
    <div class="mb-6">
        <div id="js-filter-productAttr" class="pb-4 mb-4">
            <?php foreach ($filter_groups as $group): ?>
                <div class="filter-group">
                    <b><?= $group['name'] ?></b>
                    <ul class="feature-block filters list-unstyled child border-top2 banner-bg ml-3">
                        <?php  foreach ($group['values'] as $value): ?>
                            <li>
                                <label>
                                    <input class="js-attribute js-productFilter" 
                                           data-productids="<?= !empty($value['product_ids']) ? implode(',', $value['product_ids']) : '' ?>"  
                                           <?= in_array($value['value_id'], $_f_podCharValId ?? []) ? 'checked' : '' ?> 
                                           type="checkbox" name="f_podCharValId[]" 
                                           value="<?= $value['value_id'] ?>">

                                    <?= $value['value_text'] . (!empty($value['product_count']) ? ' (' . $value['product_count'] . ')' : '') ?> 
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php







 endif ?>