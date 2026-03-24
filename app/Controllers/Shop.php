<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MODEL__seo;
use App\Models\MODEL__global;
use App\Models\MODEL__product;
use App\Models\MODEL__category;
use App\Models\MODEL__brand;
use App\Models\MODEL__filter;
use App\Models\MODEL__cenovaLista;
use App\Libraries\Pagination;
use App\Libraries\LIB__productBreadcrumbs;

class Shop extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->LIB__productBreadcrumbs = new LIB__productBreadcrumbs();
        $this->Pagination_lib = new Pagination();
        $this->MODEL__product = new MODEL__product();
        $this->MODEL__category = new MODEL__category();
        $this->MODEL__brand = new MODEL__brand();
        $this->MODEL__filter = new MODEL__filter();
        $this->MODEL__global = new MODEL__global();
        $this->MODEL__cenovaLista = new MODEL__cenovaLista();
        $this->MODEL__seo = new MODEL__seo();
        $this->className = basename(str_replace("\\", "/", get_class($this)));
    }

    // ============================================================
    // Cyrillic → Latin slug generator
    // ============================================================

    private function slugify_bg(string $text): string
    {
        $map = [
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "е" => "e",
            "ж" => "zh",
            "з" => "z",
            "и" => "i",
            "й" => "y",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "h",
            "ц" => "ts",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "sht",
            "ъ" => "a",
            "ь" => "y",
            "ю" => "yu",
            "я" => "ya",

            "А" => "a",
            "Б" => "b",
            "В" => "v",
            "Г" => "g",
            "Д" => "d",
            "Е" => "e",
            "Ж" => "zh",
            "З" => "z",
            "И" => "i",
            "Й" => "y",
            "К" => "k",
            "Л" => "l",
            "М" => "m",
            "Н" => "n",
            "О" => "o",
            "П" => "p",
            "Р" => "r",
            "С" => "s",
            "Т" => "t",
            "У" => "u",
            "Ф" => "f",
            "Х" => "h",
            "Ц" => "ts",
            "Ч" => "ch",
            "Ш" => "sh",
            "Щ" => "sht",
            "Ъ" => "a",
            "Ь" => "y",
            "Ю" => "yu",
            "Я" => "ya",
        ];

        $text = strtr($text, $map);
        $text = strtolower($text);
        $text = preg_replace("~[^a-z0-9]+~", "-", $text);
        $text = trim($text, "-");

        return $text ?: "";
    }

    private function buildShopCategoryRedirectUrl(
        string $finalPath,
        array $excludeParams = []
    ): string {
        $redirectUrl = "/shop/" . ltrim($finalPath, "/");
        $queryParams = $this->request->getGet();

        foreach ($excludeParams as $param) {
            unset($queryParams[$param]);
        }

        if (!empty($queryParams)) {
            $redirectUrl .= "?" . http_build_query($queryParams);
        }

        return $redirectUrl;
    }

    public function index($slugPath = null)
    {
        $perPage = $this->request->getVar("perPage") ?? 20;
        $page = max(1, $this->request->getVar("page"));
        $sort = $this->request->getVar("sort");

        $offset = max(0, ((int) $page - 1) * $perPage);
        //$col      = $this -> request -> getVar('col');
        $categoryRootId = $this->request->getVar("categoryRootId");
        $categoryId = $this->request->getVar("categoryId");

        $segments = $this->request->uri->getSegments();

        $currentSlug = end($segments);
        $currentCategoryId = null;

        $allCategories = $this->MODEL__category->getCategories();

        foreach ($allCategories as $cat) {
            $latin = $this->slugify_bg($cat["category_name"]);

            if ($latin === $currentSlug) {
                $categoryId = $cat["category_id"];
                $parentCategoryId = $cat["parent_id"];

                break;
            }
        }

        // ============================================================
        // FORCE categoryId FROM f_subCatIds
        // ============================================================

        $categoryRootId2 = $this->request->getGet("categoryRootId");

        if (!empty($categoryRootId2)) {
            $ids = explode("_", $categoryRootId2);

            // последната child категория
            $categoryId = (int) end($ids);
        }
        $f_subCatIds = $this->request->getGet("f_subCatIds");

        if (!empty($f_subCatIds)) {
            $ids = explode("_", $f_subCatIds);

            // последната child категория
            $categoryId = (int) end($ids);
        }
        $seoRow2 = null;

        if (!empty($categoryId)) {
            // SEO
            $seoRow = $this->MODEL__seo->getSeoForCategory((int) $categoryId);

            $categoryName = $this->MODEL__category->getCategoryNameByID(
                $categoryId
            );
            $seoRow2 = null;

            // ============================================================
            // FIX: If SEO title = PARENT category → use CHILD default SEO
            // ============================================================

            // URI segments
            $segments = $this->request->uri->getSegments();

            if (!empty($segments) && $segments[0] === "shop") {
                array_shift($segments);
            }

            $lastSlug = end($segments);

            // Намираме CHILD категория по slug
            $childCategoryId = null;
            $childCategoryName = null;

            $allCategories = $this->MODEL__category->getCategories();

            foreach ($allCategories as $cat) {
                $latin = $this->slugify_bg($cat["category_name"]);

                if ($latin === $lastSlug) {
                    $childCategoryId = $cat["category_id"];
                    $childCategoryName = $cat["category_name"];
                    break;
                }
            }

            // Ако имаме child
            if (!empty($childCategoryId)) {
                // Взимаме PARENT категорията
                $parentCat = $this->MODEL__category->getCategoryByID(
                    $parentCategoryId
                );

                $parentCategoryName = $parentCat["category_name"] ?? null;

                // DEBUG ако трябва
                // print_r([$parentCategoryName, $childCategoryName]); exit;

                // ------------------------------------------------------------
                // Ако SEO title = parent → override
                // ------------------------------------------------------------
                if (
                    !empty($seoRow["seo_title"]) &&
                    $seoRow["seo_title"] === $parentCategoryName
                ) {
                    $seoRow2 = [
                        "seo_title" => $childCategoryName,

                        "seo_description" => mb_substr(
                            "Открий богато разнообразие от {$childCategoryName}. Предлагаме различни модели на конкурентни цени.",
                            0,
                            160
                        ),

                        "focus_keyword" => $childCategoryName,

                        "seo_slug" => $this->slugify_bg($childCategoryName),

                        "canonical_url" => "/shop/" . implode("/", $segments),

                        "noindex" => 0,
                    ];
                }
            }
            $latinSlug = $this->slugify_bg($categoryName);

            if (empty($seoRow['seo_title'])) {

                $seoRow2 = [
                    "seo_title" => $categoryName,
                    "seo_description" => mb_substr(
                        "Открий богато разнообразие от {$categoryName}. Предлагаме различни модели на конкурентни цени.",
                        0,
                        160
                    ),
                    "focus_keyword" => $categoryName,
                    "seo_slug" => $latinSlug,
                    "canonical_url" => "/shop/" . $latinSlug,
                    "noindex" => 0,
                ];
            
            }


            // BUILD FULL PATH
            $path = [];
            $currentId = $categoryId;

            while (!empty($currentId)) {
                $cat = $this->MODEL__category->getCategoryByID($currentId);

                if (empty($cat)) {
                    break;
                }

                $slug = $this->slugify_bg($cat["category_name"] ?? "");

                if (!empty($slug)) {
                    array_unshift($path, $slug);
                }

                $currentId = $cat["parent_id"] ?? null;
            }

            $finalPath = implode("/", $path);

            // REDIRECT

            // ============================================================
            // REDIRECT FROM PARAMS → SLUG URL
            // ============================================================

            $hasCategoryParam = $this->request->getGet("categoryId");
            $hasSubCatParam = $this->request->getGet("f_subCatIds");
            $hasRootParam = $this->request->getGet("categoryRootId");

            // ------------------------------------------------------------
            // FORCE categoryId FROM PARAMS
            // ------------------------------------------------------------

            if (!empty($hasSubCatParam)) {
                // последната child категория
                $ids = explode("_", $hasSubCatParam);
                $categoryId = (int) end($ids);
            } elseif (!empty($hasRootParam)) {
                // root категория → взимаме slug
                $categoryId = (int) $hasRootParam;
            }

            // ------------------------------------------------------------
            // BUILD FULL SLUG PATH (ако не е билднат вече)
            // ------------------------------------------------------------

            if (!empty($categoryId)) {
                $path = [];
                $currentId = $categoryId;

                while (!empty($currentId)) {
                    $cat = $this->MODEL__category->getCategoryByID($currentId);

                    if (empty($cat)) {
                        break;
                    }

                    $slug = $this->slugify_bg($cat["category_name"]);

                    if (!empty($slug)) {
                        array_unshift($path, $slug);
                    }

                    $currentId = $cat["parent_id"] ?? null;
                }

                $finalPath = implode("/", $path);
            }

            // ------------------------------------------------------------
            // REDIRECT TRIGGER
            // ------------------------------------------------------------

            if ($hasCategoryParam || $hasSubCatParam || $hasRootParam) {
                return redirect()->to(
                    $this->buildShopCategoryRedirectUrl($finalPath, [
                        "categoryId",
                        "categoryRootId",
                        "f_subCatIds",
                    ]),
                    301
                );
            }
        }

        $priceFrom = $this->request->getVar("priceFrom") ?? 0;
        $searchName = $this->request->getVar("searchName");
        $promo = $this->request->getVar("promo");
        $brandTxt = $this->request->getVar("brandTxt");

        $subsubcategoryId = $this->request->getVar("subsubcategoryId");
        //$productCharValueIds = $this -> request -> getVar('productCharValueIds');

        $urlParams = $this->request->getGet();

        //$model          = $this -> request -> getVar('model');

        $subCategoryAttr = [];
        //$productAttr     = [];

        $products = [];
        $commonArg = [];
        $categoryPath = [];
        $branchIds = [];
        $isMainCategoryPage = false;
        helper("seo");
        helper("slug");

        // Ако имаме избрана категория, изчисляваме целия клон (самата + всички деца)
        if (empty($branchIds) && !empty($categoryId)) {
            $currentCategory = $this->MODEL__category->getCategoryByID(
                (int) $categoryId
            );
            $isMainCategoryPage =
                !empty($currentCategory) &&
                (empty($currentCategory["parent_id"]) ||
                    (int) $currentCategory["parent_id"] === 0);

            $allCategories = $this->MODEL__category->getCategories();
            $byParent = [];
            foreach ($allCategories as $_cat) {
                $byParent[$_cat["parent_id"]][] = $_cat;
            }
            $branchIds = $this->collectCategoryBranchIds(
                $categoryId,
                $byParent
            );
        }

        // ============================================================
        // CATEGORY SEO BLOCK
        // Fetch SEO → fallback default → repair Cyrillic slug → redirect
        // ============================================================

        $seoRow = [];

        if (!empty($categoryId)) {
            // ------------------------------------------------------------
            // 1. Fetch SEO from database
            // ------------------------------------------------------------
            $seoRow = $this->MODEL__seo->getSeoForCategory((int) $categoryId);

            // Get category name
            $category = $this->MODEL__category->getCategoryNameByID(
                $categoryId
            );

            // Always generate Latin slug
            $latinSlug = $this->slugify_bg($category);

            // ------------------------------------------------------------
            // 2. If SEO missing → create default
            // ------------------------------------------------------------
            if (empty($seoRow)) {
                $seoRow = [
                    "seo_title" => trim($category),

                    "seo_description" => mb_substr(
                        "Открий богато разнообразие от {$category}. Предлагаме различни модели на конкурентни цени.",
                        0,
                        160
                    ),

                    "focus_keyword" => trim($category),

                    "seo_slug" => $latinSlug,

                    "canonical_url" => "/shop/" . $latinSlug,

                    "noindex" => 0,
                ];

                // Insert into DB
                $this->MODEL__seo->db->table("_category_seo")->insert([
                    "category_id" => $categoryId,
                    "seo_title" => $seoRow["seo_title"],
                    "seo_description" => $seoRow["seo_description"],
                    "focus_keyword" => $seoRow["focus_keyword"],
                    "seo_slug" => $seoRow["seo_slug"],
                    "canonical_url" => $seoRow["canonical_url"],
                    "noindex" => 0,
                ]);
            }

            // ------------------------------------------------------------
            // 3. If SEO exists but slug is Cyrillic → repair
            // ------------------------------------------------------------
            else {
                if ($seoRow["seo_slug"] !== $latinSlug) {
                    $seoRow["seo_slug"] = $latinSlug;
                    $seoRow["canonical_url"] = "/shop/" . $latinSlug;

                    $this->MODEL__seo->db
                        ->table("_category_seo")
                        ->where("category_id", $categoryId)
                        ->update([
                            "seo_slug" => $seoRow["seo_slug"],
                            "canonical_url" => $seoRow["canonical_url"],
                        ]);
                }
            }

            // ------------------------------------------------------------
            // 4. FORCE LATIN URL REDIRECT
            // ------------------------------------------------------------
            if (!empty($slugSegments)) {
                $currentSlug = end($slugSegments);

                if ($currentSlug !== $seoRow["seo_slug"]) {
                    return redirect()->to(
                        $this->buildShopCategoryRedirectUrl($seoRow["seo_slug"]),
                        301
                    );
                }
            }
        }
        if ($seoRow2) {
            $seoRow = $seoRow2;
        }
        // Редирект от ID към slug (ако някой отвори /shop?categoryId=123)
        if (
            $this->request->getGet("categoryId") &&
            !empty($seoRow["seo_slug"])
        ) {
            return redirect()->to(
                $this->buildShopCategoryRedirectUrl($seoRow["seo_slug"], [
                    "categoryId",
                ]),
                301
            );
        }

        // shop е еднакъв за всички потребители (публична версия)
        $productPriceLevel = "cenaKKC";

        $dilarUrlId = empty($urlParams["dilarUrl"])
            ? []
            : explode("_", $urlParams["dilarUrl"]);
        $brandId = empty($urlParams["f_brandId"])
            ? ""
            : $urlParams["f_brandId"];
        $f_models = empty($urlParams["f_models"])
            ? null
            : explode("_", $urlParams["f_models"]);
        $podCharValIds = empty($urlParams["f_podCharValId"])
            ? []
            : explode("_", $urlParams["f_podCharValId"]);
        $f_subCatIds = empty($urlParams["f_subCatIds"])
            ? null
            : explode("_", $urlParams["f_subCatIds"]);
        $f_priceRange = empty($urlParams["f_price"])
            ? null
            : explode("_", $urlParams["f_price"]);
        $f_isInstock = empty($urlParams["f_instock"]) ? null : 1;

        if (!empty($categoryId) || !empty($searchName)) {
            $brandsModels = $this->MODEL__filter->get__brandsModels([
                "categoryId" => $categoryId,
                "productPriceLevel" => $productPriceLevel,
                "searchName" => $searchName,
                "brandId" => $brandId,
            ]);
        }

        if (!empty($f_models)) {
            // филтриране на х-ки на продукти
            $filters = $this->MODEL__filter->get_characteristic_filtersBySubcategories(
                $f_subCatIds
            );

            if (!empty($filters)) {
                $filter_groups = $filters["filters"];
                // $filter_productAttrIds = $filters['productIds'];
            }
        }

        // ако има подкатегория
        if (!empty($categoryId)) {
            $brands = $this->MODEL__filter->get__brands([
                "categoryId" => $categoryId,
                "subCategoryIds" => $branchIds ?? null,
                "productPriceLevel" => $productPriceLevel,
            ]);

            $categoryName = $this->MODEL__category->getCategoryNameByID(
                $categoryId
            );
            $categoryArr = $this->MODEL__category->get__parentCategory_byId(
                $categoryId
            );
            $subCategoryAttr = $this->MODEL__filter->get__subCategoryAttr(
                $categoryId
            );
        } elseif (!empty($urlParams["catToModel"])) {
            $product_to_category = $this->MODEL__filter->get__product_to_category(
                ["productPriceLevel" => $productPriceLevel]
            );
        } else {
            $categoryArr = $this->MODEL__category->get__parentCategory_byId(
                $categoryRootId
            );

            $brands = $this->MODEL__filter->get__brands([
                "categoryRootId" => $categoryRootId,
                "promo" => $promo,
                "searchName" => $searchName,
                "productPriceLevel" => $productPriceLevel,
            ]);
            $product_to_category = $this->MODEL__filter->get__product_to_category(
                ["productPriceLevel" => $productPriceLevel]
            );
        }

        //        if (!empty($filter_productAttrIds)) {
        //            $filter_productAttrIds = array_unique(array_merge(...array_map(fn($item) => explode(',', $item), $filter_productAttrIds)));
        //        }

        $commonArg = [
            // ако имаме клон, не филтрираме по single category_id, а по целия списък
            "categoryId" => !empty($branchIds) ? null : $categoryId,
            "categoryRootId" => $categoryRootId,
            "searchName" => $searchName,
            "promo" => $promo,
            "sort" => $sort,
            "to" => $f_priceRange[1] ?? null,
            "from" => $f_priceRange[0] ?? null,
            "page" => $page,
            "nalichnost" => $f_isInstock,
            "productCharValueIds" => $podCharValIds,
            //'productAttrIds'      => $filter_productAttrIds ?? null,
            "dilarUrlId" => $dilarUrlId,
            "brandTxt" => $brandTxt ?? null,
            "models" => $f_models ?? null,
            "subCategoryIds" => !empty($branchIds) ? $branchIds : $f_subCatIds,
            "brandId" => $brandId ?? null,
            "productPriceLevel" => $productPriceLevel,
            "offset" => $offset,
            "perPage" => $perPage,
            "subsubcategoryId" => $subsubcategoryId,
        ];

        // Ако сме на промо страница (/shop?promo=1), взимаме продуктите от _ofer_special.id = 10
        if (!empty($promo)) {
            $promoOfferId = 10;
            $promoRow = db_connect()
                ->table("_ofer_special")
                ->where("id", $promoOfferId)
                ->get()
                ->getRowArray();
            $promoOfferIds = array_values(
                array_filter(
                    array_map(
                        "intval",
                        preg_split("/[^0-9]+/", $promoRow["productsID"] ?? "")
                    )
                )
            );

            if (!empty($promoOfferIds)) {
                $commonArg["productIds"] = $promoOfferIds;
                // не филтрираме по промо-цена, а по избраните ID-та
                $commonArg["promo"] = null;
                $commonArg["sort"] =
                    "FIELD(p.product_id," . implode(",", $promoOfferIds) . ")";
                $commonArg["perPage"] = count($promoOfferIds);
                $commonArg["page"] = 1;
                $commonArg["offset"] = 0;
                // предотвратяваме пренаписване от URL параметри по-долу
                $urlParams = ["perPage" => $commonArg["perPage"]];
            }
        }

        if (isset($urlParams["perPage"])) {
        } else {
            // ако имаме клон от категории, не позволяваме URL параметърът да върне single categoryId
            if (!empty($branchIds)) {
                unset($urlParams["categoryId"]);
            }
            $commonArg = array_merge($commonArg, $urlParams);

            if (!empty($branchIds)) {
                $commonArg["categoryId"] = null;
                $commonArg["subCategoryIds"] = $branchIds;
            }
        }

        $product_models = $this->MODEL__filter->get__brandsModels();
        $brands_forModels = $this->MODEL__brand->get__brands();
        $productModelsTree = $this->buildModelByBrand(
            $brands_forModels,
            $product_models
        );
        $products = $this->MODEL__product->get__all_product($commonArg);

        // Динамични филтри по характеристики на база реално върнатите продукти
        $productIdsForFilters = array_column($products ?? [], "product_id");
        if (!empty($productIdsForFilters)) {
            $filters = $this->MODEL__filter->get_characteristic_filtersByProductIds(
                $productIdsForFilters
            );
            $filter_groups = $filters["filters"] ?? [];
        } else {
            $filter_groups = [];
        }

        // Запазваме първоначалния списък с марки за текущата страница,
        // за да могат да се виждат и немаркираните стойности след филтриране.

        //$productsNoLimit = $this -> MODEL__product -> get__all_product(['noLimit' => true] + $commonArg); // всички продукти без лимит

        $countTotalProducts = $this->MODEL__global->count__all_product(
            $commonArg
        );

        $paginationBaseUrl =
            "/" . ltrim((string) $this->request->getUri()->getPath(), "/");
        if ($paginationBaseUrl === "/") {
            $paginationBaseUrl = "/shop";
        }

        $pagination = $this->Pagination_lib->generate(
            [
                "total_pages" => ceil($countTotalProducts / max(1, $perPage)),
                "base_url" => $paginationBaseUrl,
            ] + $commonArg
        );

        $maxPrice = $this->MODEL__product->get__maxPrice($commonArg);

        $this->breadcrumb->add('<i class="fa fa-home"></i>', "/");

        // изграждане на пътя по категории (без марка/модел)
        $categoryPathNames = [];
        $categoryBreadcrumbLevels = [];
        $currentCategoryChildren = [];
        if (!empty($categoryId)) {
            $allCategories = $this->MODEL__category->getCategories();
            $categoryTree = $this->buildCategoryTree(null, $allCategories);
            $pathNodes =
                $this->findPathToCategory($categoryId, $categoryTree) ?? [];
            $slugPathParts = [];
            foreach ($pathNodes as $_node) {
                if (!empty($_node["category_name"])) {
                    $categoryPathNames[] = $_node["category_name"];
                    $slugPathParts[] = $this->slugify_bg($_node["category_name"]);
                    $categoryBreadcrumbLevels[] = [
                        "name" => $_node["category_name"],
                        "href" => "/shop/" . implode("/", $slugPathParts),
                    ];
                }
            }

            // Директни подкатегории на текущата категория
            $currentPath =
                !empty($categoryBreadcrumbLevels)
                    ? $categoryBreadcrumbLevels[
                        count($categoryBreadcrumbLevels) - 1
                    ]["href"]
                    : "";

            $currentCategoryChildren = array_values(
                array_filter(
                    $allCategories,
                    static function ($cat) use ($categoryId) {
                        return (int) ($cat["parent_id"] ?? 0) ===
                            (int) $categoryId;
                    }
                )
            );

            usort($currentCategoryChildren, static function ($a, $b) {
                return (int) ($a["category_position"] ?? 0) <=>
                    (int) ($b["category_position"] ?? 0);
            });

            $currentCategoryChildren = array_map(
                function ($cat) use ($currentPath) {
                    $slug = $this->slugify_bg($cat["category_name"] ?? "");
                    $href =
                        !empty($currentPath) && !empty($slug)
                            ? rtrim($currentPath, "/") . "/" . $slug
                            : "/shop";

                    return [
                        "name" => $cat["category_name"] ?? "",
                        "image" => $cat["image_cat"] ?? "",
                        "href" => $href,
                    ];
                },
                $currentCategoryChildren
            );
        }

        if ($searchName) {
            $this->breadcrumb->add("Резултати от търсене за: " . $searchName);
        } elseif ($promo) {
            $this->breadcrumb->add("Промоции");
        } elseif (!empty($urlParams["new"])) {
            $this->breadcrumb->add("Нови продукти");
        } elseif (!empty($urlParams["feature"])) {
            $this->breadcrumb->add("Очаквани продукти");
        } elseif (!empty($urlParams["fixPrice"])) {
            $this->breadcrumb->add("Фиксирана цена");
        } elseif (!empty($seoRow["seo_slug"])) {
            $lastIdx = count($categoryBreadcrumbLevels) - 1;
            foreach ($categoryBreadcrumbLevels as $idx => $_crumb) {
                $this->breadcrumb->add(
                    $_crumb["name"],
                    $idx < $lastIdx ? $_crumb["href"] : ""
                );
            }
        } elseif (!empty($categoryPathNames)) {
            $lastIdx = count($categoryBreadcrumbLevels) - 1;
            foreach ($categoryBreadcrumbLevels as $idx => $_crumb) {
                $this->breadcrumb->add(
                    $_crumb["name"],
                    $idx < $lastIdx ? $_crumb["href"] : ""
                );
            }
        }

        $data = [
            "title" => "",
            "breadcrumbs" => $this->breadcrumb->render(),
            "addGlobalJS" => $this->addGlobalJS(),
            "addJS" => $this->addJS(),
            "addCSS" => $this->addCSS(),
            "products" => $products,
            //'productAttr'         => $productAttr,
            "filter_groups" => $filter_groups ?? [],
            "productModelsTree" => array_column(
                $productModelsTree,
                null,
                "brand_id"
            ),
            "brandsModels" => $brandsModels ?? [],
            "product_to_category" => $product_to_category ?? [],
            "maxPrice" => $maxPrice->$productPriceLevel,
            "categoryArr" => $categoryArr ?? "",
            "brands" => $brands ?? null,
            "subCategoryAttr" => $subCategoryAttr,
            "isMainCategoryPage" => $isMainCategoryPage,
            "currentCategoryChildren" => $currentCategoryChildren,
            "countShowProducts" => $offset + count($products),
            "countTotalProducts" => $countTotalProducts,
            "pagination" => $pagination,
            // views
            "seo" => $seoRow,
            "views" => $this->get_views(),
        ];

        foreach ($this->global() as $key => $val) {
            $data[$key] = $val;
        }

        // Продуктовата страница е еднаква за всички потребители (публична версия)
        $data["productPriceLevel"] = "cenaKKC";

        return view("{$this->theme}/shop/VIEW__shop-index", $data);
    }

    public function get_single_product($param)
    {
        helper("seo");
        $seoModel = $this->MODEL__seo;

        if (ctype_digit($param)) {
            $product_id = (int) $param;
        } else {
            $product_id = $seoModel->findProductIdBySlug($param);
        }

        $product = $this->MODEL__product->getProduct($product_id);

        if (!$product) {
            return redirect()->to("/404");
        }
        $seoRow = $seoModel->getSeoForProduct($product_id);

        // ============================================================
        // FORCE LATIN PRODUCT SLUG
        // ============================================================

        // Generate Latin slug from product name
        $latinSlug = $this->slugify_bg($product->product_name);

        // If no SEO → create
        if (empty($seoRow)) {
            $seoRow = [
                "seo_title" => $product->product_name,
                "seo_description" => mb_substr(
                    strip_tags($product->description),
                    0,
                    160
                ),
                "focus_keyword" => $product->product_name,
                "seo_slug" => $latinSlug,
                "canonical_url" => "/product/" . $latinSlug,
                "noindex" => 0,
            ];

            $seoModel->db->table("_product_seo")->insert([
                "product_id" => $product_id,
                "seo_title" => $seoRow["seo_title"],
                "seo_description" => $seoRow["seo_description"],
                "focus_keyword" => $seoRow["focus_keyword"],
                "seo_slug" => $seoRow["seo_slug"],
                "canonical_url" => $seoRow["canonical_url"],
                "noindex" => 0,
            ]);
        }
        // If exists but Cyrillic → repair
        else {
            if ($seoRow["seo_slug"] !== $latinSlug) {
                $seoRow["seo_slug"] = $latinSlug;
                $seoRow["canonical_url"] = "/product/" . $latinSlug;

                $seoModel->db
                    ->table("_product_seo")
                    ->where("product_id", $product_id)
                    ->update([
                        "seo_slug" => $seoRow["seo_slug"],
                        "canonical_url" => $seoRow["canonical_url"],
                    ]);
            }
        }
        //---------------
        if (empty($seoRow["seo_title"])) {
            $seoRow["seo_title"] = $product->product_name;
        }

        if (empty($seoRow["seo_description"])) {
            $seoRow["seo_description"] = mb_substr(
                strip_tags($product->description),
                0,
                160
            );
        }
        $seo = buildProductSeo((array) $product, $seoRow);

        if (ctype_digit($param) && !empty($seoRow["seo_slug"])) {
            $slug = $seoRow["seo_slug"];

            if ($slug !== $param) {
                return redirect()->to("/product/" . $slug, 301);
            }
        }

        $additional_images = $product->image_aditional
            ? explode(",", $product->image_aditional)
            : [];

        $productAttr = $this->MODEL__product->getProductCharacteristics(
            $product?->product_id
        );

        $data = [
            "title" => "",
            "addGlobalJS" => $this->addGlobalJS(),
            "addJS" => $this->addJS("single"),
            "addCSS" => $this->addCSS(),
            "product" => $product,
            "seo" => $seo,
            "productCharacteristics" => $productAttr,
            "related_products" => $this->MODEL__product->get__relatedProducts(
                $product->related_products ?? []
            ),
            "variation_products" => $this->MODEL__product->get__variationProductsForPdp(
                $product->variation_products ?? [],
                $product->product_id ?? 0
            ),
            "additional_mages" => $additional_images,
            // views
            "views" => $this->get_views(),
        ];

        foreach ($this->global() as $key => $val) {
            $data[$key] = $val;
        }

        // изграждане на breadcrumbs
        $data["breadcrumbs"] = $this->LIB__productBreadcrumbs->build(
            $product,
            $data["categories"]
        );

        // определяне на id до подкатегория ниво 1 от последната вложена подкатегория
        $pathSubCat1 = !empty($product->category_id)
            ? $this->findPathToCategory(
                $product->category_id,
                $data["categories"]
            )
            : "";

        $subCatLv1Id = $pathSubCat1[1]["category_id"] ?? ""; // подкат ниво 1
        $categoryAttr = $this->MODEL__product->getCategoryAttribute(
            $product->category_id,
            null
        );

        $data["categoryAttributes"] = $categoryAttr;

        return view("{$this->theme}/shop/VIEW__single-product", $data);
    }

    public function quickView(int $product_id)
    {
        // Ако искаш да допускаш само AJAX, разкоментирай:
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setStatusCode(404);
        // }

        helper("price");

        // 1) Данни за продукта
        $product = $this->MODEL__product->getProduct($product_id);
        if (!$product) {
            return $this->response->setStatusCode(404, "Not found");
        }

        // 2) Още данни, както на продуктовата страница
        $additional_images = $product->image_aditional
            ? explode(",", $product->image_aditional)
            : [];
        $productAttr = $this->MODEL__product->getProductCharacteristics(
            $product->product_id
        );
        $categoryAttr = $this->MODEL__product->getCategoryAttribute(
            null,
            $product
        );

        // 3) Ценово ниво (публична версия)
        $productPriceLevel = "cenaKKC";

        // 4) View данни
        $data = [
            "product" => $product,
            "additional_mages" => $additional_images,
            "categoryAttributes" => $categoryAttr,
            "productCharacteristics" => $productAttr,
            "productPriceLevel" => $productPriceLevel,
            "ISMOBILE" => isMobile(),
            "customConfig" => new \Config\ValentiConfig\CustomConfig(),
            "settings_portal" => self::get_portalSettings(),
        ];
        foreach ($this->global() as $k => $v) {
            $data[$k] = $v;
        }

        // 5) ВРЪЩАМЕ PARTIAL (без header/footer)
        return view("{$_ENV["app.theme"]}/shop/VIEW__product-quickview", $data);
    }

    public function xls_export_allProducts()
    {
        //ajax
        $categoryRootId = $this->request->getVar("categoryRootId") ?: null;
        $categoryId = $this->request->getVar("categoryId") ?: null;
        $productIds = $this->request->getVar("productIds");
        $name = $this->request->getVar("name");
        $productIds = $productIds ? explode(",", $productIds) : null; // от ценовата листа само
        $productPriceLevel = $this->getResolvedProductPriceLevel();

        $response = $this->MODEL__product->get__all_product([
            "xlsExport" => true,
            "categoryId" => $categoryId,
            "categoryRootId" => $categoryRootId,
            "productIds" => $productIds,
            "productPriceLevel" => $productPriceLevel,
        ]);

        if (empty($response)) {
            return json_encode([
                "err" => "Продуктите немогат да бъдат експортирани.",
            ]);
        }

        $date = date("d-m-Y");
        $file_name = "$date.xlsx";
        $reader = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $reader->getActiveSheet();
        //  $reader -> setReadDataOnly(true);

        $sheet->setCellValue("A1", "Име на продукт");
        $sheet->setCellValue("B1", "Модел");
        $sheet->setCellValue("C1", "Производител");
        $sheet->setCellValue("D1", "Наличност");
        $sheet->setCellValue("E1", "Дилърска цена без ДДС");
        $sheet->fromArray($response, null, "A2");
        // $sheet       = $spreadsheet -> getActiveSheet();

        $highestRow = $sheet->getHighestRow();

        $sheet
            ->getStyle("A2:E$highestRow")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
        $sheet->getColumnDimension("A")->setWidth(70);
        $sheet->getColumnDimension("B")->setWidth(30);
        $sheet->getColumnDimension("C")->setWidth(20);
        $sheet->getColumnDimension("D")->setWidth(10);
        $sheet->getColumnDimension("E")->setWidth(20);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter(
            $reader,
            "Xlsx"
        );

        ob_start();
        $writer->setPreCalculateFormulas(false);
        $writer->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response = [
            "fileName" => $name . "_" . $file_name,
            "file" =>
                "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," .
                base64_encode($xlsData),
        ];

        return json_encode($response);
    }

    public function searchProducts()
    {
        //ajax
        $searchName = $this->request->getVar("searchName");

        // Горната търсачка е еднаква за всички потребители (публична версия)
        $productPriceLevel = "cenaKKC";

        $products = $this->MODEL__product->get__all_product([
            "searchName" => $searchName,
            "productPriceLevel" => $productPriceLevel,
        ]);

        $data = [
            "products" => $products,
            "productPriceLevel" => $productPriceLevel,
        ];

        $data["view"] = view(
            "{$this->theme}/layouts/header/VIEW__search-results",
            $data
        );

        return json_encode(["view" => $data["view"]]);
    }

    /**
     * Resolve category by slug segments (/shop/cat/subcat/...).
     */
    protected function resolveCategoryFromSlugs(array $segments): ?array
    {
        $seoModel = $this->MODEL__seo;
        $categoryModel = $this->MODEL__category;

        //$parentId   = null;
        $path = [];
        $categoryId = null;

        foreach ($segments as $slug) {
            // Търси категория със дадения slug и евентуален parent
            $row = $seoModel->findCategoryBySlug($slug);

            if (!$row) {
                return null; // прекъсваме при невалиден slug
            }

            $categoryId = (int) $row["category_id"];
            $path[] =
                $row["category_name"] ?? ucfirst(str_replace("-", " ", $slug));

            //$parentId   = $categoryId;
        }

        if (!$categoryId) {
            return null;
        }

        // Взимаме root ID чрез родителите нагоре
        $rootId = $seoModel->getRootCategoryId($categoryId);

        // Всички подкатегории за тази категория (включително самата)
        $allCategories = $categoryModel->getCategories();
        $byParent = [];
        foreach ($allCategories as $_cat) {
            $byParent[$_cat["parent_id"]][] = $_cat;
        }
        $branchIds = $this->collectCategoryBranchIds($categoryId, $byParent);

        return [
            "category_id" => $categoryId,
            "root_id" => $rootId,
            "path" => $path,
            "branch_ids" => $branchIds,
        ];
    }

    private function slugify(string $text): string
    {
        $text = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $text);
        $text = strtolower($text);
        $text = preg_replace("~[^a-z0-9]+~", "-", $text);
        $text = trim($text, "-");
        return $text ?: "";
    }

    // зареждане на css файлове в footer на html страноцата
    // ====================================================

    public function addCSS()
    {
        $page = "css/pages/";

        return ["$page/global"];
    }

    // зареждане на js файлове в footer на html страноцата
    // ====================================================

    public function addJS($arg = null)
    {
        $theme = "js/theme/electro";

        $plugins = ["$theme/loadPlugins"];

        $global = ["$theme/global"];

        $default =
            $arg == "single"
                ? ["$theme/pages/shop/singleProduct", "$theme/pages/cart/cart"]
                : [
                    "$theme/pages/shop/filters",
                    "$theme/pages/shop/xls_exportProducts",
                    "$theme/pages/cart/cart",
                    "$theme/pages/shop/singleProduct", // нужната init функция за модала
                    "$theme/quickViewProduct", // файлът с AJAX логиката (по-долу)
                ];

        $modals = [
            "$theme/pages/home/home_popupForms",
            "$theme/pages/shop/popup_mobile_relationFilter",
        ];

        return array_merge($plugins, $global, $default, $modals);
    }
}
