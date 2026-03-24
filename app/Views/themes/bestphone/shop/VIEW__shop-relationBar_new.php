<?php
                // Приемаме, че текущата категория е достъпна като $currentCategoryId
                $currentCategoryId = $category['id'] ?? ($_GET['categoryId'] ?? null);
                $currentSubcategoryId = $_GET['f_subCatIds'] ?? null;
                
                // Намираме текущата главна категория и нейните подкатегории
                $currentMainCategory = null;
                $currentSubcategories = [];
                
                if ($currentCategoryId) {
                    foreach ($categories as $category) {
                        // Проверяваме дали текущата категория е главна категория
                        if ($category['category_id'] == $currentCategoryId) {
                            $currentMainCategory = $category;
                            $currentSubcategories = $category['children'] ?? [];
                            break;
                        }
                        // Проверяваме дали текущата категория е подкатегория
                        if (!empty($category['children'])) {
                            foreach ($category['children'] as $subcategory) {
                                if ($subcategory['category_id'] == $currentCategoryId) {
                                    $currentMainCategory = $category;
                                    $currentSubcategories = $category['children'];
                                    break 2;
                                }
                            }
                        }
                    }
                }
                ?>
               <!-- PRODUCT FILTERS HIERARCHICAL -->
               <div class="row pt-4">
                   <div class="col-12">
                       <div class="bg-light p-3 rounded">
                           <h5 class="mb-3 font-weight-bold">Филтри за продукти</h5>
                           <div class="row">
                               <!-- Brand Filter -->
                               <div class="col-md-3 mb-3">
                                   <label class="font-weight-bold mb-2">Марка:</label>
                                   <select name="brand_filter" id="brand_filter" class="form-control" onchange="filterByBrand(this.value)">
                                       <option value="">Избери марка</option>
                                       <?php if (!empty($productModelsTree)): ?>
                                           <?php foreach ($productModelsTree as $brand_id => $brand): ?>
                                               <option value="<?= $brand['brand_id'] ?>" <?= (isset($_GET['f_brandId']) && $_GET['f_brandId'] == $brand['brand_id']) ? 'selected' : '' ?>>
                                                   <?= htmlspecialchars($brand['brandTxt']) ?>
                                               </option>
                                           <?php endforeach; ?>
                                       <?php endif; ?>
                                   </select>
                               </div>
                                <div class="col-md-3 mb-3">
                                   <label class="font-weight-bold mb-2">Модел:</label>
                                   <select name="model_filter" id="model_filter" class="form-control" onchange="filterByModel(this.value)">
                                       <option value="">Избери модел</option>
                                       <?php if (!empty($productModelsTree)): ?>
                                           <?php foreach ($productModelsTree as $brand_id => $brand): ?>
                                               <?php if (!empty($brand['children'])): ?>
                                                   <?php foreach ($brand['children'] as $model): ?>
                                                       <option value="<?= $model['model'] ?>" 
                                                               data-brand-id="<?= $brand['brand_id'] ?>"
                                                               data-brand-name="<?= htmlspecialchars($brand['brandTxt']) ?>"
                                                               <?= (isset($_GET['f_models']) && $_GET['f_models'] == $model['model']) ? 'selected' : '' ?>>
                                                           <?= htmlspecialchars($brand['brandTxt']) ?> - <?= htmlspecialchars($model['model']) ?>
                                                       </option>
                                                   <?php endforeach; ?>
                                               <?php endif; ?>
                                           <?php endforeach; ?>
                                       <?php endif; ?>
                                   </select>
                               </div>
                               <!-- Subcategory Filter - показва се само ако има избрана марка и модел -->
                               <?php if (!empty($currentSubcategories) && (isset($_GET['f_brandId']) && isset($_GET['f_models']))): ?>
                               <div class="col-md-3 mb-3">
                                   <label class="font-weight-bold mb-2">Подкатегория:</label>
                                   <select name="subcategory_filter" id="subcategory_filter" class="form-control" onchange="filterBySubcategory(this.value)">
                                       <option value="">Избери подкатегория</option>
                                       <?php foreach ($currentSubcategories as $subcategory): ?>
                                           <option value="<?= $subcategory['category_id'] ?>" <?= ($currentSubcategoryId == $subcategory['category_id']) ? 'selected' : '' ?>>
                                               <?= htmlspecialchars($subcategory['category_name']) ?>
                                           </option>
                                       <?php endforeach; ?>
                                   </select>
                               </div>
                               <?php endif; ?>
                           </div>
                       </div>
                   </div>
               </div>
               <!-- END PRODUCT FILTERS HIERARCHICAL -->

               <!-- SUBCATEGORY IMAGES ROW - показва се само ако има избрана марка и модел -->
               <?php if (!empty($currentSubcategories) && (isset($_GET['f_brandId']) && isset($_GET['f_models']))): ?>
                   <div class="row pt-4" id="subcategory_images_row">
                       <div class="col-12">
                           <h3 class="font-weight-bold text-center h5 mb-4">
                               ИЗБЕРИ БЪРЗО ПО ПОДКАТЕГОРИЯ
                           </h3>
                           <div class="d-flex flex-wrap justify-content-center">
                               <?php foreach ($currentSubcategories as $subcategory): ?>
                                   <div class="col-6 col-lg-3 mb-3 d-flex">
                                       <div class="banner-bg w-100 py-2 d-flex">
                                           <?php
                                            // Запазваме всички текущи параметри и добавяме подкатегорията
                                            $params = $_GET; // Запазваме всички текущи параметри
                                            $params['f_subCatIds'] = $subcategory['category_id'];

                                            $url = '/shop?' . http_build_query($params);
                                            ?>
                                           <a class="js-subcategory-link d-flex p-1 p-xl-2 p-wd-1 m-auto align-items-center text-gray-90 fade_open w-100"
                                               href="<?= $url ?>">
                                               <div class="media1 align-items-center w-100">
                                                   <div class="max-width-148 w-50">
                                                       <?php if (!empty($subcategory['category_image'])): ?>
                                                           <img class="css-img-hover img-fluid rounded-bottom-pill rounded-top-left-pill border"
                                                               src="<?= $_ENV['app.imageDir'] . $subcategory['category_image'] ?>"
                                                               onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';"
                                                               alt="<?= htmlspecialchars($subcategory['category_name']) ?>"
                                                               title="избери подкатегория <?= htmlspecialchars($subcategory['category_name']) ?>">
                                                       <?php else: ?>
                                                           <div class="d-flex align-items-center justify-content-center border rounded-bottom-pill rounded-top-left-pill"
                                                               style="height: 100px; background: #f8f9fa;">
                                                               <div class="text-muted text-center" style="font-size: 12px;">
                                                                   <i class="fa fa-folder fa-2x mb-2"></i><br>
                                                                   <?= htmlspecialchars($subcategory['category_name']) ?>
                                                               </div>
                                                           </div>
                                                       <?php endif; ?>
                                                   </div>
                                                   <div class="ml-4 media-body">
                                                       <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                                           <?= htmlspecialchars($subcategory['category_name']) ?>
                                                       </div>
                                                       <?php if (isset($countProductsBySubCategory[$subcategory['category_id']])): ?>
                                                           <small class="text-muted">
                                                               (<?= $countProductsBySubCategory[$subcategory['category_id']] ?> продукта)
                                                           </small>
                                                       <?php endif; ?>
                                                   </div>
                                               </div>
                                           </a>
                                       </div>
                                   </div>
                               <?php endforeach; ?>
                           </div>
                       </div>
                   </div>
               <?php endif; ?>
               <!-- END SUBCATEGORY IMAGES ROW -->

               <!-- BRAND IMAGES ROW -->
               <?php if (!empty($productModelsTree) && $currentCategoryId): ?>
                   <div class="row pt-4" id="brand_images_row">
                       <div class="col-12">
                           <h3 class="font-weight-bold text-center h5 mb-4">
                               ИЗБЕРИ БЪРЗО ПО МАРКА
                           </h3>
                           <div class="d-flex flex-wrap justify-content-center">
                               <?php foreach ($productModelsTree as $brand_id => $brand): ?>
                                   <div class="col-6 col-lg-3 mb-3 d-flex">
                                       <div class="banner-bg w-100 py-2 d-flex">
                                           <?php
                                            // Основни параметри
                                            $params = [
                                                'categoryId' => $currentCategoryId,
                                                'f_brandId' => $brand['brand_id'],
                                                'brandTxt' => $brand['brandTxt']
                                            ];

                                            // Добавяме сортиране, ако има
                                            if (!empty($_sort)) {
                                                parse_str(ltrim($_sort, '&'), $sortParams);
                                                $params = array_merge($params, $sortParams);
                                            }

                                            $url = '/shop?' . http_build_query($params);
                                            ?>
                                           <a class="js-brand-link d-flex p-1 p-xl-2 p-wd-1 m-auto align-items-center text-gray-90 fade_open w-100"
                                               href="<?= $url ?>">
                                               <div class="media1 align-items-center w-100">
                                                   <div class="max-width-148 w-50">
                                                       <?php if (!empty($brand['image_brand'])): ?>
                                                           <img class="css-img-hover img-fluid rounded-bottom-pill rounded-top-left-pill border"
                                                               src="<?= $_ENV['app.imageDir'] . $brand['image_brand'] ?>"
                                                               onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';"
                                                               alt="<?= htmlspecialchars($brand['brandTxt']) ?>"
                                                               title="избери марка <?= htmlspecialchars($brand['brandTxt']) ?>">
                                                       <?php else: ?>
                                                           <div class="d-flex align-items-center justify-content-center border rounded-bottom-pill rounded-top-left-pill"
                                                               style="height: 100px; background: #f8f9fa;">
                                                               <div class="text-muted text-center" style="font-size: 12px;">
                                                                   <i class="fa fa-image fa-2x mb-2"></i><br>
                                                                   <?= htmlspecialchars($brand['brandTxt']) ?>
                                                               </div>
                                                           </div>
                                                       <?php endif; ?>
                                                   </div>
                                                   <div class="ml-4 media-body">
                                                       <div class="mb-2 pb-1 font-size-18 font-weight-light text-ls-n1 text-lh-23">
                                                           <?= htmlspecialchars($brand['brandTxt']) ?>
                                                       </div>
                                                   </div>
                                               </div>
                                           </a>
                                       </div>
                                   </div>
                               <?php endforeach; ?>
                           </div>
                       </div>
                   </div>
               <?php endif; ?>
               <!-- END BRAND IMAGES ROW -->

<!-- MODEL IMAGES ROW -->
<?php if (!empty($productModelsTree) && $currentCategoryId): ?>
    <div class="row pt-4" id="model_images_row">
        <div class="col-12">
            <h3 class="font-weight-bold text-center h5 mb-4">
                ИЗБЕРИ БЪРЗО ПО МОДЕЛ
            </h3>
            <div class="d-flex flex-wrap justify-content-center">
                <?php foreach ($productModelsTree as $brand_id => $brand): ?>
                    <?php if (!empty($brand['children'])): ?>
                        <?php foreach ($brand['children'] as $model): ?>
                            <div class="col-6 col-lg-3 mb-3 d-flex">
                                <div class="banner-bg w-100 py-2 d-flex">
                                    <?php
                                    // Основни параметри за модел
                                    $params = [
                                        'categoryId' => $currentCategoryId,
                                        'f_brandId' => $brand['brand_id'],
                                        'brandTxt' => $brand['brandTxt'],
                                        'f_models' => $model['model']
                                    ];

                                    // Добавяме сортиране, ако има
                                    if (!empty($_sort)) {
                                        parse_str(ltrim($_sort, '&'), $sortParams);
                                        $params = array_merge($params, $sortParams);
                                    }

                                    $url = '/shop?' . http_build_query($params);
                                    ?>
                                    <a class="js-model-link d-flex p-1 p-xl-2 p-wd-1 m-auto align-items-center text-gray-90 fade_open w-100"
                                       href="<?= $url ?>">
                                        <div class="media1 align-items-center w-100">
                                            <div class="max-width-148 w-50">
                                                <?php if (!empty($brand['image_brand'])): ?>
                                                    <img class="css-img-hover img-fluid rounded-bottom-pill rounded-top-left-pill border"
                                                         src="<?= $_ENV['app.imageDir'] . $brand['image_brand'] ?>"
                                                         onerror="this.onerror=null;this.src='<?= $_ENV['app.noImage'] ?>';"
                                                         alt="<?= htmlspecialchars($brand['brandTxt']) ?> - <?= htmlspecialchars($model['model']) ?>"
                                                         title="избери модел <?= htmlspecialchars($model['model']) ?>">
                                                <?php else: ?>
                                                    <div class="d-flex align-items-center justify-content-center border rounded-bottom-pill rounded-top-left-pill"
                                                         style="height: 100px; background: #f8f9fa;">
                                                        <div class="text-muted text-center" style="font-size: 12px;">
                                                            <i class="fa fa-mobile fa-2x mb-2"></i><br>
                                                            <?= htmlspecialchars($model['model']) ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-4 media-body">
                                                <div class="mb-2 pb-1 font-size-16 font-weight-light text-ls-n1 text-lh-23">
                                                    <?= htmlspecialchars($model['model']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<!-- END MODEL IMAGES ROW -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const brandSelect = document.getElementById('brand_filter');
    const modelSelect = document.getElementById('model_filter');
    
    // Запазваме всички модели при зареждане на страницата
    const allModels = Array.from(modelSelect.options).slice(1); // Без първата "Избери модел" опция
    
    // Функция за филтриране на моделите по марка
    function filterModelsByBrand(selectedBrandId) {
        // Изчистваме всички опции освен първата
        modelSelect.innerHTML = '<option value="">Избери модел</option>';
        
        if (!selectedBrandId) {
            // Ако няма избрана марка, показваме всички модели
            allModels.forEach(option => {
                modelSelect.appendChild(option.cloneNode(true));
            });
        } else {
            // Показваме само моделите от избраната марка
            allModels.forEach(option => {
                if (option.getAttribute('data-brand-id') === selectedBrandId) {
                    modelSelect.appendChild(option.cloneNode(true));
                }
            });
        }
    }
    
    // Слушател за промяна на марката
    brandSelect.addEventListener('change', function() {
        const selectedBrandId = this.value;
        filterModelsByBrand(selectedBrandId);
    });
    
    // При зареждане на страницата, ако има избрана марка, филтрираме моделите
    const currentBrandId = brandSelect.value;
    if (currentBrandId) {
        filterModelsByBrand(currentBrandId);
    }
});

// Функция за филтриране по подкатегория
function filterBySubcategory(subcategoryId) {
    const currentUrl = new URL(window.location);
    
    if (subcategoryId) {
        // Запазваме всички текущи параметри и добавяме подкатегорията
        currentUrl.searchParams.set('f_subCatIds', subcategoryId);
    } else {
        // Премахваме само подкатегорията, запазваме останалите филтри
        currentUrl.searchParams.delete('f_subCatIds');
    }
    
    window.location.href = currentUrl.toString();
}

// Функция за филтриране по марка
function filterByBrand(brandId) {
    if (brandId) {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('f_brandId', brandId);
        // Изчистваме модела при смяна на марката
        currentUrl.searchParams.delete('f_models');
        window.location.href = currentUrl.toString();
    } else {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.delete('f_brandId');
        currentUrl.searchParams.delete('brandTxt');
        currentUrl.searchParams.delete('f_models');
        window.location.href = currentUrl.toString();
    }
}

// Функция за филтриране по модел
function filterByModel(modelName) {
    if (modelName) {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('f_models', modelName);
        
        // Намираме марката на избрания модел
        const modelSelect = document.getElementById('model_filter');
        const selectedOption = modelSelect.querySelector(`option[value="${modelName}"]`);
        if (selectedOption) {
            const brandId = selectedOption.getAttribute('data-brand-id');
            const brandName = selectedOption.getAttribute('data-brand-name');
            if (brandId) {
                currentUrl.searchParams.set('f_brandId', brandId);
                currentUrl.searchParams.set('brandTxt', brandName);
            }
        }
        
        window.location.href = currentUrl.toString();
    } else {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.delete('f_models');
        window.location.href = currentUrl.toString();
    }
}
</script>
