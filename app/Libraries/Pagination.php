<?php

namespace App\Libraries;
 class Pagination {

    // генериране на номериране на страници
    public function generate($data = []) {

        $page        = (int) $data['page'];
        $total_pages = $data['total_pages'];

        $urlParams = [];
        $li        = '';
        $s         = '';
        $next      = '';
        $prev      = '';

        if (!empty($_GET)) {
            $urlParams = $_GET;
        }

        $baseUrl = trim((string) ($data['base_url'] ?? '/shop'));
        if ($baseUrl === '') {
            $baseUrl = '/shop';
        }

        if ($page > 1) {

            $urlParams['page'] = ($page - 1) . $s;

            $li .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?' . http_build_query($urlParams) . '">
                                    <span>&#10094; ' . $prev . '</span></a></li>';
        }

        if (($page - 2) > 1) {

            $urlParams['page'] = 1;

            $li .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?' . http_build_query($urlParams) . '">1</a></li>
                    <li><a class="page-item">...</a></li>';
        }

        $index = $total_pages <= 10 ? 10 : 4;

        for ($i = ($page - 2); $i <= ($page + $index); $i++) {
            $dataPage = '';
            $disabled = '';
            $current  = '';

            if ($i < 1)
                continue;
            if ($i > $total_pages)
                break;
            if ($i == 1) {
                $dataPage = 'data-page = "1"';
                // $current = "current";
                // if (!$tab) {
                //     $tab   = 'zenova';
                //     $current = "current";
                // }
            }
            if ($i == $page) {
                $disabled = 'disabled';
                $current  = 'current';
            }

            $urlParams['page'] = $i . $s;

            $li .= '<li class="page-item ' . $disabled . '"' . $dataPage . '><a class="page-link ' . $current . '" href="' . $baseUrl . '?' . http_build_query($urlParams) . '">' . $i . '</a></li>';
        }

        if (($total_pages - ($page + 1)) >= 1 && $total_pages > 10) {
            $li .= '<li class="page-item"><a class="page-link">...</a></li>';
        }

        if (($total_pages - ($page + 1)) > 0 && $total_pages > 10) {
            $urlParams['page'] = $total_pages . $s;

            $li .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?' . http_build_query($urlParams) . '">' . $total_pages . '</a></li>';
        }


        if ($page < $total_pages) {
            $urlParams['page'] = ($page + 1) . $s;

            $li .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?' . http_build_query($urlParams) . '"><span>' . $next . ' &#10095;</span></a></li>';
        }

        return $li;
    }
}
