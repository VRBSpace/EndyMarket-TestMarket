<div class="py-1 mt-1">
    <?php
    $_name = match (true) {
        !empty($_GET['searchName']) =>
        "Резултати от търсене за '{$_GET['searchName']}'",
        !empty($_GET['promo']) => 'ПРОМО ПРОДУКТИ',
        !empty($_GET['new']) => 'НОВИ ПРОДУКТИ',
        !empty($_GET['feature']) => 'ОЧАКВАНИ ПРОДУКТИ',
        !empty($_GET['fixPrice']) => 'ФИКСИРАНА ЦЕНА',
        !empty($categoryArr->child) => $categoryArr->child,
        !empty($_GET['catToModel']) =>
        'Пазаруване по модел: ' . trim(($_GET['text'] ?? '') . ' ' . ($_GET['f_models'] ?? '')),
        (!empty($_GET['brandTxt']) || !empty($_GET['brandId'])) =>
        'Пазаруване по марка: ' . ($_GET['brandTxt'] ?? ''),
        !empty($_GET['dilarUrl']) => 'Пазаруване по специална селекция: ',
        default => ''
    };

    // if (ISMOBILE) {
    //     echo "<div class='col'><h5><b>{$_name}</b></h5></div>";
    // }
    ?>

    <div class="d-flex <?= ISMOBILE ? 'justify-content-center flex-row-reverse' : 'justify-content-end' ?>">
        <!-- GRID AND LIST-->
        <div class="flex-center-between">
            <ul class="nav nav-tab-shop flex-nowrap" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a id="js-grid-view" class="nav-link active" data-toggle="pill" href="#js-products-grid" role="tab" aria-controls="pills-one-example1" aria-selected="true">
                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                            <i class="fa fa-th"></i>
                        </div>
                    </a>
                </li>

                <li class="nav-item">
                    <a id="js-list-view" class=" nav-link" data-toggle="pill" href="#js-products-list" role="tab">
                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                            <i class="fa fa-th-list"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <!-- END GRID AND LIST-->

        <?php if (!ISMOBILE): ?>
            <div class="col d-flex align-items-center">
                <h5 class="mb-0">
                        <?= $_name ?>
                </h5>
            </div>
        <?php endif ?>

        <div class="row <?= ISMOBILE ? 'w-40' : '' ?>">
            <?php
            if (!empty($products)):
                $productPriceLevel = 'cenaKKC';
              $_selectedSort     = $_GET['sort'] ?? '';
            ?>
                <!-- Select -->
                <select id="js-sorting-select"
                    class="js-productFilter js-select align-self-center selectpicker dropdown-select right-dropdown-0 px-2 <?= ISMOBILE ? 'w-100' : '' ?>"
                    data-style="btn-sm bg-white font-weight-normal  <?= ISMOBILE ? 'py-0' : 'py-2' ?> border text-gray-20 bg-lg-down-transparent border1-lg-down-0 rounded-3">
                    <option value="">Сортирай</option>

                    <option value="product_name ASC" <?= $_selectedSort === 'product_name ASC' ? 'selected' : '' ?>>Име Възходящ ред</option>

                    <option value="product_name DESC" <?= $_selectedSort === 'product_name DESC' ? 'selected' : '' ?>>Име Низходящ ред</option>

                    <option value="<?= htmlspecialchars($productPriceLevel) ?> ASC" <?= $_selectedSort === "$productPriceLevel ASC" ? 'selected' : '' ?>>Цена (от най-ниска)</option>

                    <option value="<?= htmlspecialchars($productPriceLevel) ?> DESC" <?= $_selectedSort === "$productPriceLevel DESC" ? 'selected' : '' ?>>Цена (от най-висока)</option>
                </select>
                <!-- End Select -->

                <?php if (false): ?>
                    <!-- <a id="js-xlsExportProducts" class="btn p-2 btn-success rounded-0 align-self-center" data-route="<?= route_to('Shop-xls_export_allProducts') ?>" data-name="<?= $_name ?>" href="javascript:;" title="Еспортиране на всички продукти от съответната подкатегория">
                        <i class="fa fa-file-excel"></i>&nbsp;Ексел експорт
                    </a> -->
                <?php endif ?>
            <?php endif ?>
        </div>

        <div class="d-xl-none">
            <!-- Account Sidebar Toggle Button -->
            <a id="sidebarNavToggler1" class="btn btn-sm font-weight-normal" href="javascript:;" role="button"
                aria-controls="sidebarContent1"
                aria-haspopup="true"
                aria-expanded="false"
                data-unfold-event="click"
                data-unfold-hide-on-scroll="false"
                data-unfold-target="#sidebarContent1"
                data-unfold-type="css-animation"
                data-unfold-animation-in="fadeInLeft"
                data-unfold-animation-out="fadeOutLeft"
                data-unfold-duration="500">
                <i class="fas fa-sliders-h"></i> <span class="ml-1">Филтри</span>
            </a>
            <!-- End Account Sidebar Toggle Button -->
        </div>

        <div class="px-3 d-none d-xl-block"></div>
    </div>
</div>
