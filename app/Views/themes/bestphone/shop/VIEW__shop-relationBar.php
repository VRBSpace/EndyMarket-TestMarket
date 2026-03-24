<?php
declare(strict_types=1);

$GET = filter_input_array(INPUT_GET, [
            'categoryRootId' => FILTER_VALIDATE_INT,
            'f_brandId'      => FILTER_VALIDATE_INT,
            'f_mainModelId'  => FILTER_VALIDATE_INT,
            'f_models'       => FILTER_UNSAFE_RAW,
            //'f_year'         => FILTER_UNSAFE_RAW,
            'f_subCatIds'    => FILTER_UNSAFE_RAW,
        ]) ?? [];

$activeImg   = '';
$activeTitle = '';

$imageBase   = $_ENV['app.imageDir'];
$imagePortal = $_ENV['app.imagePortalDir'];
$noImage     = $_ENV['app.noImage'];

$categoryRootId = $GET['categoryRootId'] ?? null;
$brandId        = $GET['f_brandId'] ?? null;
$model          = $GET['f_models'] ?? null;
$mainModelId    = $GET['f_mainModelId'] ?? null;
$subStr         = $GET['f_subCatIds'] ?? '';

$baseUrl = '/shop';

$brandsById = array_column($productModelsTree ?? [], null, 'brand_id');
$brandSel   = $brandId && isset($brandsById[$brandId]) ? $brandsById[$brandId] : null;
$models     = $brandSel['children'] ?? [];

$categories = array_column($categories ?? [], null, 'category_id');

$lv1     = $categories[$categoryRootId]['children'] ?? [];
$subPath = array_filter(explode('_', (string) $subStr));
$lv1ById = $lv1 ? array_column($lv1, null, 'category_id') : [];

$activeImg   = $activeTitle = '';

if ($model && !$activeImg) {
    foreach ($models as $m) {
        if ($m['model'] === $model) {
            $activeImg   = $m['image_model'] ?? null;
            $activeTitle = $m['model'];
            break;
        }
    }
}

function makeUrl($params, $baseUrl): string {
    $q = http_build_query(array_filter($params, fn($v) => $v !== null && $v !== ''));
    return $baseUrl . ($q ? "?$q" : '');
}

function getAllModels(array $models): array {
    $allModels = [];

    foreach ($models as $m) {
        $mainModel   = $m['model'] ?? '';
        $mainModelId = $m['model_id'] ?? '';
        $mainImg     = $m['image_model'] ?? '';

        if ($mainModel) {
            $allModels[] = [
                'mainModelId' => $mainModelId,
                'model'       => $mainModel,
                'image_model' => $mainImg,
                'is_main'     => true,
            ];
        }
        // избличане на допъл модели към главният модел
        $additional = json_decode($m['additional_models_json'] ?? '[]', true);

        foreach ($additional as $am) {
            if (!empty($am['name'])) {
                $allModels[] = [
                    'mainModelId' => $mainModelId,
                    'model'       => $am['name'],
                    'image_model' => $am['img'] ?? '',
                    'is_main'     => false,
                ];
            }
        }
    }

    // Сортиране по име
    usort($allModels, fn($a, $b) => strcasecmp($a['model'], $b['model']));

    // Премахване на дубликати по име
    $unique     = [];
    $renderList = [];

    foreach ($allModels as $m) {
        if (!in_array($m['model'], $unique)) {
            $unique[]     = $m['model'];
            $renderList[] = $m;
        }
    }

    return $renderList;
}
?>

<!-- филтърен ред с select box-->
<div class="row pt-4 <?= ISMOBILE ? 'hide' : '' ?>">
    <div class="col-12 p-0">
        <div class="bg-light rounded <?= ISMOBILE ? 'p-0' : 'p-3' ?>">

            <?php if (!ISMOBILE): ?>
                <h5 class="mb-3 font-weight-bold">Филтри за продукти</h5>
            <?php endif ?>

            <!-- ДВЕ КОЛОНИ: 75% dropdown-и / 25% снимка -->
            <div class="row">
                <!-- LEFT: 75% dropdowns -->
                <div class="col-md-9">
                    <div class="row">
                        <!-- Марка -->
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold mb-2">Марка</label>

                            <select id="js-brand-filter" class="js-shopRelationFilter form-control" name="f_brandId">
                                <?php
                                $emptyUrl = makeUrl([
                                    'categoryRootId' => $categoryRootId
                                        ], $baseUrl);
                                ?>
                                <option data-url="<?= $emptyUrl ?>" data-next="model" value="">Избери марка</option>
                                <?php
                                foreach ($brandsById as $b):
                                    $image    = !empty($b['image_brand']) ? $imageBase . $b['image_brand'] : $noImage;
                                    $url      = makeUrl(['categoryRootId' => $categoryRootId, 'f_brandId' => $b['brand_id']], $baseUrl);
                                    $selected = '';
                                    if ($brandId == $b['brand_id']) {
                                        $activeImg   = $image;
                                        $activeTitle = $b['brandTxt'];
                                        $selected    = 'selected';
                                    }
                                    ?>
                                    <option data-url="<?= $url ?>" data-next="model" data-img="<?= $image ?>" value="<?= $b['brand_id'] ?>" <?= $selected ?>>
                                        <?= $b['brandTxt'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php //dd($models);  ?>
                        <!-- Модел -->
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold mb-2">Модел</label>

                            <select id="js-model-filter" class="js-shopRelationFilter form-control" name="f_models">
                                <?php
                                $emptyUrl   = makeUrl([
                                    'categoryRootId' => $categoryRootId,
                                    'f_brandId'      => $brandId
                                        ], $baseUrl);
                                ?>
                                <option data-url="<?= $emptyUrl ?>" data-next="cat" value="">Избери модел</option>
                                <?php
                                $renderList = getAllModels($models);

                                // Обединяване на основни и допълнителни модели
                                foreach ($renderList as $m):
                                    $modelName = $m['model'];
                                    $imgUrl    = !empty($m['image_model']) ? $imageBase . $m['image_model'] : $noImage;
                                    $selected  = ($model === $modelName) ? 'selected' : '';
                                    $label     = $modelName;
                                    $url       = makeUrl([
                                        'categoryRootId' => $categoryRootId,
                                        'f_brandId'      => $brandId,
                                        'f_mainModelId'  => $m['mainModelId'],
                                        'f_models'       => $m['model'],
                                            ], $baseUrl);

                                    // За текущо избрания да подготвим изображение и заглавие
                                    if ($selected) {
                                        $activeImg   = $imgUrl;
                                        $activeTitle = $modelName;
                                    }
                                    ?>
                                    <option data-mainModel-id="<?= $m['mainModelId'] ?>" data-url="<?= $url ?>" data-next="cat" data-img="<?= $imgUrl ?>" <?= $selected ?> data-type="<?= $m['is_main'] ? 'main' : 'addit' ?>" value="<?= $m['mainModelId'] ?>">
                                        <?= $label ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <!-- Подкатегории 1–3 -->
                        <?php
                        $subs         = [];
                        $currentLevel = $lv1ById;
                        $subs[]       = $currentLevel; // добавяме първо ниво винаги

                        if ($subPath) {
                            foreach ($subPath as $cid) {
                                if (!isset($currentLevel[$cid]['children'])) {
                                    break; // няма повече поднива
                                }

                                // Вземаме следващото ниво
                                $nextLevel = array_column($currentLevel[$cid]['children'], null, 'category_id');
                                $subs[]    = $nextLevel;

                                // Подготвяме се за следваща итерация (навлизаме надолу)
                                $currentLevel = $nextLevel;
                            }
                        }

                        for ($i = 0; $i < 3; $i++):
                            ?>
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold mb-2">Подкатегория <?= $i + 1 ?></label>
                                <select class="js-shopRelationFilter form-control" name="form[category_id][]" data-level="<?= $i + 1 ?>">
                                    <?php
                                    $emptyUrl = makeUrl([
                                        'categoryRootId' => $categoryRootId,
                                        'f_brandId'      => $brandId,
                                        'f_mainModelId'  => $mainModelId,
                                        'f_models'       => $model,
                                        'f_subCatIds'    => $i == 0 ? '' : ($i == 1 ? preg_replace('/_[^_]*$/', '', $subStr) : $subStr)
                                            ], $baseUrl);
                                    ?>

                                    <option data-url="<?= $emptyUrl ?>" data-next="cat" value="">Избери Подкатегория</option>

                                    <?php
                                    if ($model && $subs):
                                        foreach ($subs[$i] ?? [] as $s):
                                            $image    = !empty($s['image_cat']) ? $imagePortal . $s['image_cat'] : $noImage;
                                            $selected = '';

                                            if ((isset($subPath[$i]) && $subPath[$i] == $s['category_id'])) {
                                                $activeImg                         = $image;
                                                $activeTitle                       = $s['category_name'];
                                                $crumbActiveCat[$s['category_id']] = $s['category_name'];
                                                $selected                          = 'selected';
                                            }


                                            // Пълна верига от избрани подкатегории + текущата
                                            $cut         = match (true) {
                                                $i === 0 => -2,
                                                $i === 1 && count($subPath) > 1 => -1,
                                                default => count($subPath) // ще даде празен масив
                                            };
                                            $fullChain   = array_merge(array_slice($subPath, 0, $cut), [$s['category_id']]);
                                            $subCatParam = implode('_', $fullChain);

                                            $url = makeUrl([
                                                'categoryRootId' => $categoryRootId,
                                                'f_brandId'      => $brandId,
                                                'f_models'       => $model,
                                                'f_mainModelId'  => $mainModelId,
                                                'f_subCatIds'    => $subCatParam
                                                    ], $baseUrl);
                                            ?>
                                            <option data-url="<?= $url ?>" data-next="cat" data-img="<?= $image ?>" value="<?= $s['category_id'] ?>" <?= $selected ?>>
                                                <?= esc($s['category_name']) ?>
                                            </option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </div>
                        <?php endfor ?>

                    </div>
                </div>

                <!-- колона с снимка за избрания филтър-->
                <div class="col-md-3 d-none d-md-flex align-items-end">
                    <?php if ($activeImg): ?>
                        <div class="w-100 text-center">
                            <img src="<?= esc($activeImg) ?>" alt="<?= esc($activeTitle) ?>"
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height:140px;object-fit:contain;background:#fff;padding:6px;border:1px solid rgba(0,0,0,.08)">
                            <?php if ($activeTitle): ?><div class="small mt-1 text-muted"><?= esc($activeTitle) ?></div><?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// за мобилна версия popup за филтрите
if (ISMOBILE) {
    $data = compact('subPath', 'subs', 'brandSel', 'brandId', 'mainModelId', 'model', 'categoryRootId', 'subStr', 'brandsById', 'models');
    echo view("{$_ENV['app.theme']}/shop/VIEW__shop-relationBar-mobile", $data);
}
?>

<!-- втори филтърен ред  -->
<?php
if ($categoryRootId):

    function renderSecondFilter(string $url, string $title, ?string $img = null, $isMain = null): void {
        $noImage   = $_ENV['app.noImage'];
        $img       = $img ?? $noImage;
        $additInfo = '';

        if (isset($isMain) && !$isMain) {
            $additInfo = '<small class="text-center text-secondary">съвместим модел</small>';
        }

        echo <<<HTML
                <div class="col-6 col-lg-2 d-flex">
                    <div class="css-filter-banner px-0 d-flex">
                        <a class="js-brand-link p-1 p-xl-2 p-wd-2 m-auto align-items-center text-gray-90 fade_open d-flex flex-column align-items-center rounded-0" href="$url">
                            <img class="css-fitler-image img-fluid w-75 mb-1"
                                 src="$img"
                                 onerror="this.onerror=null;this.src='$noImage';"
                                 alt="$title">
                            <div class="text-center">$title</div>
                            $additInfo
                        </a>
                    </div>
                </div>
             HTML;
    }
    ?>

    <div class="row pt-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-start">
                <?php
                if (!$brandId) {
                    foreach ($brandsById as $b) {
                        $imageBrand = !empty($b['image_brand']) ? $_ENV['app.imageDir'] . $b['image_brand'] : $_ENV['app.noImage'];

                        $url = makeUrl(['categoryRootId' => $categoryRootId, 'f_brandId' => $b['brand_id']], $baseUrl);
                        renderSecondFilter($url, $b['brandTxt'] ?? $b['brand'], $imageBrand);
                    }
                } elseif ($brandId && !$model) {
                    $renderList = getAllModels($models);

                    foreach ($renderList as $m) {
                        $isMain     = $m['is_main'];
                        $imageModel = !empty($m['image_model']) ? $_ENV['app.imageDir'] . $m['image_model'] : $_ENV['app.noImage'];

                        $url = makeUrl([
                            'categoryRootId' => $categoryRootId,
                            'f_brandId'      => $brandId,
                            'f_mainModelId'  => $m['mainModelId'],
                            'f_models'       => $m['model'],
                                ], $baseUrl);

                        renderSecondFilter($url, $m['model'], $imageModel, $isMain);
                    }
                } elseif ($model) {
                    foreach (empty($subPath) ? $lv1ById : $subs[count($subPath)] as $cat) {
                        $imageCat    = !empty($cat['image_cat']) ? $_ENV['app.imagePortalDir'] . $cat['image_cat'] : $_ENV['app.noImage'];
                        // Пълна верига от избрани подкатегории + текущата
                        $fullChain   = array_merge($subPath, [$cat['category_id']]);
                        $subCatParam = implode('_', $fullChain);

                        $url = makeUrl([
                            'categoryRootId' => $categoryRootId,
                            'f_brandId'      => $brandId,
                            'f_models'       => $model,
                            'f_mainModelId'  => $mainModelId,
                            'f_subCatIds'    => $subCatParam
                                ], $baseUrl);

                        renderSecondFilter($url, $cat['category_name'], $imageCat);
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php endif ?>

<?php
if (!empty($crumbActiveCat)) {
    // Контролен бар под филтрите
    echo view($views['shop-controlBar']);

    // Breadcrumbs 
    $crumbs = [];

    if ($brandSel) {
        $crumbs[] = [
            'label' => $brandSel['brandTxt'],
            'url'   => makeUrl(['categoryRootId' => $categoryRootId, 'f_brandId' => $brandId], $baseUrl)];
    }

    if ($model) {
        $crumbs[] = [
            'label' => $model,
            'url'   => makeUrl(['categoryRootId' => $categoryRootId, 'f_brandId' => $brandId, 'f_models' => $model], $baseUrl)];
    }

    $subCatIds = [];
    foreach ($crumbActiveCat as $key => $val) {
        $subCatIds[] = $key;

        $crumbs[] = [
            'label' => $val,
            'url'   => makeUrl(['categoryRootId' => $categoryRootId, 'f_brandId' => $brandId, 'f_models' => $model, 'f_subCatIds' => implode('_', $subCatIds)], $baseUrl)];
    }
    $lastIndex = array_key_last($crumbs);
    $newLi     = '';

    foreach ($crumbs as $i => $c) {
        $label = $c['label'];
        if ($i === $lastIndex) {
            $newLi .= '<li class="breadcrumb-item active">' . $label . '</li>';
        } else {
            $url   = $c['url'] ?? '';
            $newLi .= '<li class="breadcrumb-item"><a href="' . $url . '">' . $label . '</a></li>';
        }
    }

    // Добавяме новите li в края на вече съществуващите
    echo $breadcrumbs = str_replace('</ol>', $newLi . '</ol>', $breadcrumbs);
}
?>

