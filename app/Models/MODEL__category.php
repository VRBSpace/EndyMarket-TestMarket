<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__category extends BaseModel {

    protected $table      = '_category'; // Име на таблицата в базата данни
    protected $primaryKey = 'category_id'; // Основен ключ на таблицата

    // Вземане на всички категории
    public function getCategories() {
        return $this -> findAll();
    }

    // Вземане на категория по ID
    public function getCategoryByID($id) {
        return $this -> find($id);
    }

    public function getCategoryNameByID($id) {
        $parent_id = $this -> getParentCategoryId($id);
        return $this -> select('category_name')
                        -> where('category_id', $parent_id)
                        -> get() -> getRow('category_name');
    }

    public function getParentCategoryId($categoryId) {
        $category = $this -> getCategoryByID($categoryId);

        if (empty($category)) {
            return;
        }

        if ($category['parent_id'] == null) {
            return $categoryId;
        }
        return $category['parent_id'];
    }

    public function get__parentCategory_byId($categoryId) {

        if (empty($categoryId)) {
            return;
        }

        $child = $this -> select('parent_id,category_name, image_cat')
                        -> where('category_id', $categoryId)
                        //-> where('parent_id IS NOT NULL')
                        -> get() -> getRow();

        $parent = $this -> select('category_name')
                        -> where('category_id', $child -> parent_id ?? null)
                        -> where('parent_id IS NULL')
                        -> get() -> getRow('category_name');

        return (object) ['parent' => $parent, 'child' => isset($child -> category_name) ? $child -> category_name : ''];
    }

    // Метод за вземане на йерархични категории за dropdown-ите
    public function getHierarchicalCategories($data = []) {
        $query = $this -> db -> table(self::TBL_CATEGORY . ' c')
                -> select('c.category_id, c.category_name, c.parent_id, c.image_cat, COUNT(p.product_id) as product_count')
                -> join(self::TBL_PRODUCT . ' p', 'p.category_id = c.category_id', 'left')
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id = p.product_id', 'left')
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id = p.product_id', 'left')
                -> groupStart()
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> groupEnd()
                -> groupBy('c.category_id')
                -> orderBy('c.category_name', 'ASC');

        // Филтриране по ниво на йерархията
        if (!empty($data['level'])) {
            switch ($data['level']) {
                case '4': // Първо ниво категории
                    $query -> where('c.parent_id IS NULL OR c.parent_id = 0');
                    break;
                case '5': // Второ ниво категории
                    if (!empty($data['parentId'])) {
                        $query -> where('c.parent_id', $data['parentId']);
                    }
                    break;
                case '6': // Трето ниво категории
                    if (!empty($data['parentId'])) {
                        $query -> where('c.parent_id', $data['parentId']);
                    }
                    break;
            }
        }

        // Филтриране по марка
        if (!empty($data['brandId'])) {
            $query -> where('p.brand_id', $data['brandId']);
        }

        // Филтриране по модел
        if (!empty($data['model'])) {
            $query -> join(self::TBL_MODEL . ' pm', 'model_id', 'left')
                   -> join(self::TBL_PRODUCT_MODEL_ADDIT_LINK . ' pam', 'pam.model_main_id=pm.model_id', 'left')// Допълнителни модели
                    -> where('pm.model', $data['model']);
            // -> where('pm.model', $data['model']);
        }

        // Филтриране по година
        if (!empty($data['year'])) {
            $query -> where('YEAR(p.created_at)', $data['year']);
        }

        // Филтриране по ценово ниво
        if (!empty($data['productPriceLevel'])) {
            $query -> where("pl.{$data['productPriceLevel']} IS NOT NULL");
        }

        $results = $query -> get() -> getResultArray();

        $categories = [];
        foreach ($results as $row) {
            if ($row['product_count'] > 0) { // Показваме само категории с продукти
                $categories[] = [
                    'value'     => $row['category_id'],
                    'text'      => $row['category_name'] . ' (' . $row['product_count'] . ')',
                    'parent_id' => $row['parent_id'],
                    'image_cat' => $row['image_cat'],
                ];
            }
        }

        return $categories;
    }
}
