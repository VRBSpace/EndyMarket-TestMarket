<?php

namespace App\Libraries;

class LIB__productBreadcrumbs {

    public function build(object $product, array $categories): string {
        $esc = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');

        $makeUrl = static function (array $params) {
            $q = http_build_query(array_filter($params, fn($v) => $v !== null && $v !== ''));
            return '/shop' . ($q ? ('?' . $q) : '');
        };

        $findPathTo = function (?int $targetId, array $nodes, array $path = []) use (&$findPathTo): ?array {
            if (!$targetId)
                return null;
            
            foreach ($nodes as $n) {
                $cur = [...$path, $n];
                if ((int) ($n['category_id'] ?? 0) === $targetId)
                    return $cur;
                if (!empty($n['children']) && is_array($n['children'])) {
                    $res = $findPathTo($targetId, $n['children'], $cur);
                    if ($res)
                        return $res;
                }
            }
            return null;
        };

        $rootId   = (int) ($product -> categoryRoot_id ?? 0);
        $catId    = (int) ($product -> category_id ?? 0);
        $brandId  = (int) ($product -> brand_id ?? 0);
        $brandTxt = (string) ($product -> brandTxt ?? '');
        $model    = (string) ($product -> model ?? '');
        $year     = isset($product -> model_year) && $product -> model_year !== '' ? (string) $product -> model_year : null;

        $path = $findPathTo($catId, $categories) ?? [];

        $subIds = [];
        if ($path) {
            if (!empty($path[0]['category_id']) && (int) $path[0]['category_id'] === $rootId) {
                array_shift($path);
            }
            foreach ($path as $node) {
                if (!empty($node['category_id'])) {
                    $subIds[] = (int) $node['category_id'];
                }
            }
        }

        $breadcrumbs   = [];
        $breadcrumbs[] = ['label' => '<i class="fa fa-home"></i>', 'url' => '/', 'active' => false];

        // Root
        if ($rootId) {
            $rootPath      = $findPathTo($rootId, $categories);
            $rootName      = $rootPath[0]['category_name'] ?? 'Категория';
            $breadcrumbs[] = [
                'label'  => $esc($rootName),
                'url'    => $makeUrl(['categoryRootId' => $rootId]),
                'active' => false
            ];
        }

        if ($brandId) {
            $breadcrumbs[] = [
                'label'  => $esc($brandTxt ?: 'Марка ' . $brandId),
                'url'    => $makeUrl(['categoryRootId' => $rootId, 'f_brandId' => $brandId]),
                'active' => false
            ];
        }

        if ($model !== '') {
            $breadcrumbs[] = [
                'label'  => $esc($model),
                'url'    => $makeUrl([
                    'categoryRootId' => $rootId,
                    'f_brandId'      => $brandId,
                    'f_models'       => $model
                ]),
                'active' => false
            ];
        }

        if ($year !== null) {
            $breadcrumbs[] = [
                'label'  => $esc($year),
                'url'    => $makeUrl([
                    'categoryRootId' => $rootId,
                    'f_brandId'      => $brandId ?: null,
                    'f_models'       => $model ?: null,
                    'f_year'         => $year
                ]),
                'active' => false
            ];
        }

        if ($subIds) {
            $acc = [];

            foreach ($subIds as $idx => $id) {
                $acc[]         = $id;
                $nodeName      = $path[$idx]['category_name'] ?? ('Категория ' . $id);
                $breadcrumbs[] = [
                    'label'  => $esc($nodeName),
                    'url'    => $makeUrl([
                        'categoryRootId' => $rootId,
                        'f_brandId'      => $brandId ?: null,
                        'f_models'       => $model ?: null,
                        'f_year'         => $year ?: null,
                        'f_subCatIds'    => implode('_', $acc),
                    ]),
                    'active' => false
                ];
            }
        }

        $breadcrumbs[] = [
            'label'  => $esc($product -> name ?? ''),
            'url'    => '',
            'active' => true
        ];

        return $this -> render($breadcrumbs);
    }

    protected function render(array $items, string $class = 'breadcrumb'): string {
        if (empty($items))
            return '';

        $html  = "<nav class='$class'> <ol class='breadcrumb'>";
        $total = count($items);

        foreach ($items as $i => $item) {
            $label  = $item['label'] ?? '';
            $url    = $item['url'] ?? '';
            $active = $item['active'] ?? false;

            $isLast = ($i === $total - 1);

            if ($isLast) {
                
            } elseif (!$active) {
                $html .= "<li class='breadcrumb-item'><a href='$url'>$label</a></li>";
            } else {
                $html .= "<li class='breadcrumb-item active'>$label</li>\n";
            }
        }
        $html .= "</ol></nav>";
        return $html;
    }
}
