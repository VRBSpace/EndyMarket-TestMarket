<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__seo extends BaseModel {

    protected $table         = '_product_seo';
    protected $primaryKey    = 'seo_id';
    protected $allowedFields = [
        'product_id', 'seo_title', 'seo_description', 'focus_keyword',
        'canonical_url', 'noindex', 'seo_slug', 'seo_images'
    ];
    protected $returnType    = 'array';

    /**
     * Return SEO row for product with decoded seo_images JSON (if present).
     */
    public function getSeoForProduct(int $productId): ?array {
        $row = $this -> where('product_id', $productId) -> first();
        if (!$row) {
            return null;
        }
        $row['seo_images'] = $row['seo_images'] ? json_decode($row['seo_images'], true) : null;

        return $row;
    }

    /**
     * Find product_id by seo_slug (for routing by slug).
     */
    public function findProductIdBySlug(string $slug): ?int {
        $row = $this -> select('product_id') -> where('seo_slug', $slug) -> first();
        return $row['product_id'] ?? null;
    }

    public function getSeoForCategory(int $category_id): ?array {
        $row = $this -> db -> table('_category_seo')
                        -> where('category_id', $category_id)
                        -> get() -> getRowArray();

        return $row ?? null;
    }

    public function findCategoryBySlug(string $slug) {
        return $this -> db
                        -> table('_category_seo cs')
                        -> select('cs.category_id, c.category_name, c.parent_id')
                        -> join('_category c', 'c.category_id = cs.category_id')
                        -> where('cs.seo_slug', $slug)
                        -> get() -> getRowArray() ?? null;
    }

    public function getRootCategoryId(int $categoryId): int {
        $currentId = $categoryId;

        while (true) {
            $row = $this -> db -> table('_category')
                    -> select('parent_id')
                    -> where('category_id', $currentId)
                    -> get()
                    -> getRowArray();

            if (empty($row['parent_id'])) {
                break;
            }

            $currentId = (int) $row['parent_id'];
        }

        return $currentId;
    }
}
