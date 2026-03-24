<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__xml extends BaseModel {

    // при търсене на ктегория
    public function autocomplete($arg = '', $val = '') {

        if ($arg == 'category') {
            return $this -> db -> table(self::TBL_CATEGORY . ' c')
                            -> select('c.*')
                            -> join(self::TBL_PRODUCT . ' p', 'category_id', 'inner')
                            -> like('c.category_name', $val, 'both')
                            -> join(self::TBL_PRODUCT_SITES . ' ps', 'product_id', 'inner')
                            -> groupStart()
                            -> where('ps.sp_site_id', $_ENV['app.siteId'])
                            -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                            -> groupEnd()
                            -> groupBy('c.category_id')
                            -> orderBy('c.category_name', 'ASC')
                            -> get() -> getResultArray();
        } elseif ($arg == 'cenova') {
            return $this -> db -> table(self::TBL_ZENOVA_LISTA)
                            -> select('offersName,productsID')
                            -> like('offersName', $val, 'both')
                            -> groupStart()
                            -> where('is_file IS NULL')
                            -> orWhere('is_file', '')
                            -> groupEnd()
                            -> orderBy('offersName', 'ASC')
                            -> get() -> getResultArray();
        }
    }

    public function get__all_product($categoryIds = null, $productIds = null, $productPriceLevel = 'cenaKKC') {
        $sql = $this -> db -> table(self::TBL_PRODUCT . ' p')
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'product_id', 'left')
                -> join('(SELECT category_id as cid,category_name AS category_name FROM ' . self::TBL_CATEGORY . ' ) as c', 'c.cid = p.categoryRoot_id', 'left')
                -> join('(SELECT category_id as cid,category_name AS subCategory_name FROM ' . self::TBL_CATEGORY . ' ) as cs', 'cs.cid = p.category_id', 'left')
                -> join(self::TBL_BRAND . ' b', 'brand_id', 'left')
                -> join('(SELECT model_id,model FROM ' . self::TBL_MODEL . ' ) as pm', 'pm.model_id=p.model_id', 'left')
                -> join(self::TBL_SP_MQRKA . ' m', 'p.sp_mqrka_id = m.sp_mqrka_id', 'left')
                // -> join(self::TBL_SP_TEGLO . ' t', 'p.sp_teglo_id = t.sp_teglo_id', 'left')
                // -> join(self::TBL_SP_RAZMER . ' r', 'p.sp_razmer_id = r.sp_razmer_id', 'left')
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'product_id', 'inner')
                -> groupStart()
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> groupEnd()
                -> groupStart()
                -> where("pl.{$productPriceLevel} IS NOT NULL")
                -> orwhere('pl.zavishena_zena !="" and pl.is_promo = 1')
                -> groupEnd();

        if (!empty($categoryIds)) {
            $sql -> whereIn('category_id', $categoryIds);
        }

        if (!empty($productIds)) {
            $ids = '';
            foreach ($productIds as $val) {
                $ids .= $val;
            }

            $sql -> whereIn('product_id', (array) $ids, false);
        }

        return $sql -> get() -> getResultArray();
    }

    // атрибути на продукт
    public function get__all_productAttr($productIds = []) {

        $query = $this -> db -> table(self::TBL_PRODUCT_CHARACTERISTIC . ' pc')
                        -> select('pc.product_id,ca.value, pcv.product_characteristic_text')
                        -> join(self::TBL_PRODUCT_CHAR_VALUE . ' pcv', 'product_characteristic_value_id', 'inner')
                        -> join(self::TBL_CATEGORY_ATTRIBUTE . ' ca', 'category_characteristic_id', 'left')
                        -> whereIn('pc.product_id', $productIds)
                        -> get() -> getResultArray();

        return $query;
    }

    public function export($data = [], $user = [], $productPriceLevel = 'cenaKKC') {

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Products></Products>');
        $xml -> addChild('Date_created', date("Y-m-d H:i:s"));

        $categoryIds = array_values($data['product_category'] ?? []);
        $productIds  = array_values($data['productIds_fromCenova'] ?? []);

        $products    = $this -> get__all_product($categoryIds, $productIds, $productPriceLevel);
        $productAttr = $this -> get__all_productAttr(array_column($products, 'product_id'));

        $this -> save_xmlConf('xml', $data); // запис на избраните опции за xml

        foreach ($products as $product) {
            if (empty($product['image'])) {
                continue;
            }

            $tag = $xml -> addChild('product');

            foreach (array_keys($data) as $v) {

                switch ($v) {
                    case 'product_id':
                        $this -> addXmlElement($tag, $v, 'valp_' . $product['product_id']);
                        break;

                    case 'product_url':
                        $productUrl = base_url() . 'product/' . $product['product_id'];
                        $this -> addXmlElement($tag, $v, '<![CDATA[ ' . $productUrl . ' ]]>');
                        break;

                    case 'name':
                        $this -> addXmlElement($tag, $v, $product['product_name']);
                        break;

                    case 'model':
                        $this -> addXmlElement($tag, $v, $product['model']);
                        break;

                    case 'price':
                        $this -> addXmlElement($tag, $v, number_format((float) $product['cenaKKC'], 2, '.', ''));
                        break;

                    case 'price_dilar':
                        $this -> addXmlElement($tag, $v, number_format((float) $product[$productPriceLevel], 2, '.', ''));
                        break;

                    case 'specialPrice':

                        if ($product['is_promo'] && !empty($product['zavishena_zena'])) {
                            $promoPrice      = $product['cenaPromo'];
                            $promo_dateStart = $product['promo_dateStart'] ?? '';
                            $promo_dateEnd   = $product['promo_dateEnd'] ?? '';
                            $parentTag       = $tag -> addChild($v);

                            $this -> addXmlElement($parentTag, 'price', number_format((float) $promoPrice, 2, '.', ''));
                            $this -> addXmlElement($parentTag, 'data_start', $promo_dateStart);
                            $this -> addXmlElement($parentTag, 'data_end', $promo_dateEnd);
                        }
                        break;

                    case 'quantity':
                        $this -> addXmlElement($tag, $v, $product['nalichnost']);
                        break;

                    case 'description':
                        $this -> addXmlElement($tag, $v, '<![CDATA[' . str_replace(array(';', '&'), ' ', html_entity_decode($product['description'], ENT_QUOTES)) . ']]>');
                        break;

                    case 'manufacturer':
                        $this -> addXmlElement($tag, $v, $product['brandTxt']);
                        break;

                    case 'product_attribute':

                        if ($productAttr) {
                            $parentTag = $tag -> addChild($v);

                            foreach ($productAttr as $i => $attr) {
                                if ($product['product_id'] == $attr['product_id']) {

                                    $childTag = $parentTag -> addChild('attribute');
                                    $this -> addXmlElement($childTag, 'attr_name', $attr['value']);
                                    $this -> addXmlElement($childTag, 'attr_value', $attr['product_characteristic_text']);
                                }
                            }
                        }
                        break;

                    case 'category':
                        $categoryName = $product['category_name'];
                        $subCatName   = $product['subCategory_name'];

                        if (!empty($categoryName)) {
                            $categoryVal = $subCatName ? $categoryName . ' > ' . $subCatName : $categoryName;
                            $this -> addXmlElement($tag, $v, $categoryVal);
                        }
                        break;

                    case 'images':
                        $baseUrl   = $_ENV['app.imageDir'];
                        $imagePath = $baseUrl . $product['image'];

                        $imgBase = (isset($data['platf']) && $data['platf'] == 'wordpress') ? $imagePath : "<![CDATA[ {$imagePath} ]]>";

                        $this -> addXmlElement($tag, $v, $imgBase);
                        break;

                    case 'aditional_images':
                        $URL       = $_ENV['app.imageDir'];
                        $imageAdit = explode(',', $product['image_aditional']);
                        $parentTag = $tag -> addChild($v);

                        foreach ($imageAdit as $i => $image) {
                            $this -> addXmlElement($parentTag, 'image_' . ($i + 1), '<![CDATA[ ' . $URL . $image . ']]>');
                        }
                        break;

                    case 'seo_keyword':
                        $seo = $this -> generateSeoURL($product['product_name']);

                        if ($seo !== '') {
                            $this -> addXmlElement($tag, $v, $seo);
                        }
                        break;

                    case 'meta_keyword':
                        $metaKeywords = $this -> extract_keywords($product['description'] != '' ? strip_tags($product['description']) : $product['product_name']);

                        if ($metaKeywords !== '') {
                            $this -> addXmlElement($tag, $v, $metaKeywords);
                        }
                        break;

                    case 'meta_description':
                        $this -> addXmlElement($tag, $v, '<![CDATA[' . $product['description'] . ']]>');
                        break;

                    case 'stock_status':
                        $status = $product['nalichnost'] > 0 ? 'в наличност' : 'с поръчка';
                        $this -> addXmlElement($tag, $v, $status);
                        break;

                    case 'weight':
                        $this -> addXmlElement($tag, $v, $product['teglo']);
                        break;

                    case 'length':
                        $this -> addXmlElement($tag, $v, $product['length']);
                        break;

                    case 'width':
                        $this -> addXmlElement($tag, $v, $product['width']);
                        break;

                    case 'height':
                        $this -> addXmlElement($tag, $v, $product['height']);
                        break;

                    case 'weight_class':
                        $this -> addXmlElement($tag, $v, $product['mqrka_unit']);
                        break;

                    case 'length_class':
                        $this -> addXmlElement($tag, $v, $product['mqrka_unit']);
                        break;

                    case 'date_added':
                        $this -> addXmlElement($tag, $v, $product['date_product_create']);
                        break;

//                    case 'sort_order':
//                        $this -> addXmlElement($tag, $v, $product['site_sort_order']);
//                        break;
                }
            }
        }

        //$formattedXml = $xml -> asXML();

        $dom                       = new \DOMDocument('1.0', 'UTF-8');
        $dom -> preserveWhiteSpace = false;
        $dom -> formatOutput       = true;
        $dom_xml                   = dom_import_simplexml($xml);
        $dom_xml                   = $dom -> importNode($dom_xml, true);
        $dom_xml                   = $dom -> appendChild($dom_xml);

        $fileName = 'xmlFeed_' . $user['bulstat'] . '_products.xml';
        $filePath = getcwd() . "/public/xml/$fileName";

        $dom -> save($filePath);

        ob_flush();
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        ob_clean();

        //echo  $xml -> asXML();
        if (!is_cli()) {
            echo $dom -> saveXML();
        }
    }

    public function csv_export($data = [], $user = [], $productPriceLevel = 'cenaKKC') {

        $categoryIds = $data['product_category'] ?? null;
        $productIds  = $data['productIds_fromCenova'] ?? null;

        $response    = $this -> get__all_product($categoryIds, $productIds, $productPriceLevel);
        $productAttr = $this -> get__all_productAttr(array_column($response, 'product_id'));

        $date        = date("d-m-Y");
        $file_name   = "$date.xlsx";
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet -> getActiveSheet();

        $Letters_arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z'];

        $this -> save_xmlConf('csv', $data); // запис на избраните опции за xml

        foreach ($response as $key => $product) {
            $i = 0;
            if (empty($product['image'])) {
                continue;
            }

            foreach (array_keys($data) as $k) {

                switch ($k) {
                    case 'product_id':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'product_id');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), 'valp_' . $product['product_id']);
                        $i++;
                        break;

                    case 'product_url':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'product_url');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), 'https://' . $_SERVER['HTTP_HOST'] . '/index.php?route=product/product&product_id=' . $product['product_id']);
                        $i++;
                        break;

                    case 'name':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'name');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['product_name']);
                        $i++;
                        break;

                    case 'model':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'model');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['model']);
                        $i++;
                        break;

                    case 'price':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'price');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), number_format((float) $product['cenaKKC'], 2, '.', ''));
                        $i++;
                        break;

                    case 'price_dilar':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'price_dilar');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), number_format((float) $product[$productPriceLevel], 2, '.', ''));
                        $i++;
                        break;

                    case 'specialPrice':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'specialPrice');
                        $promoPrice = '';
                        if ($product['is_promo'] && !empty($product['zavishena_zena'])) {
                            $promoPrice = $product['cenaPromo'];
                        }

                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), !empty($promoPrice) ? number_format((float) $promoPrice, 2, '.', '') : '');
                        $i++;
                        break;

                    case 'quantity':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'quantity');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['nalichnost']);
                        $i++;
                        break;

                    case 'description':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'description');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), str_replace(array(';', '&'), ' ', html_entity_decode($product['description'], ENT_QUOTES)));
                        $i++;
                        break;

                    case 'manufacturer':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'manufacturer');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['brandTxt']);
                        $i++;
                        break;

                    case 'product_attribute1':

//                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'v');
//                        if ($productAttr) {
//                            foreach ($productAttr as  $attr) {
//                                if ($product['product_id'] == $attr['product_id']) {
//
//                                    $childTag = $parentTag -> addChild('attribute');
//                                    $this -> addXmlElement($childTag, 'attr_name', $attr['value']);
//                                    $this -> addXmlElement($childTag, 'attr_value', $attr['product_characteristic_text']);
//                                }
//                            }
//                        }
                        break;

                    case 'category':
                        $categoryName = $product['category_name'];
                        $subCatName   = $product['subCategory_name'];

                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'category');

                        if (!empty($categoryName)) {
                            $categoryVal = $subCatName ? $categoryName . ' > ' . $subCatName : $categoryName;
                            $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $categoryVal);
                        }
                        $i++;
                        break;

                    case 'images':
                        $URL = $_ENV['app.imageDir'];
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'images');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $URL . $product['image']);
                        $i++;
                        break;

                    case 'aditional_images':
                        $URL = $_ENV['app.imageDir'];

                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'aditional_images');
                        if (!empty($product['image_aditional'])) {
                            $imageAditArr = explode(',', $product['image_aditional']);

                            foreach ($imageAditArr as $image) {
                                $imageAdit[] = $URL . $image;
                            }
                            $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), implode(',', $imageAdit));
                        }
                        $i++;
                        break;

                    case 'seo_keyword':
                        $seo = $this -> generateSeoURL($product['product_name']);
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'seo_keyword');

                        if ($seo !== '') {
                            $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $seo);
                        }
                        $i++;
                        break;

                    case 'meta_description':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'meta_description');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), str_replace(array(';', '&'), ' ', html_entity_decode($product['description'], ENT_QUOTES)));
                        $i++;
                        break;

                    case 'stock_status':
                        $status = $product['nalichnost'] > 0 ? 'в наличност' : 'с поръчка';
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'stock_status');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $status);
                        $i++;
                        break;

                    case 'weight':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'weight');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['teglo']);
                        $i++;
                        break;

                    case 'length':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'length');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['length']);
                        $i++;
                        break;

                    case 'width':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'width');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['width']);
                        $i++;
                        break;

                    case 'height':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'height');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['height']);
                        $i++;
                        break;

                    case 'weight_class':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'weight_class');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['mqrka_unit']);
                        $i++;
                        break;

                    case 'length_class':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'length_class');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['mqrka_unit']);
                        $i++;
                        break;

                    case 'date_added':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'date_added');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['date_product_create']);
                        $i++;
                        break;

                    case 'sort_order':
                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'sort_order');
                        $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $product['site_sort_order']);
                        $i++;
                        break;

                    case 'meta_keyword':
                        $metaKeywords = $this -> extract_keywords($product['description'] != '' ? strip_tags($product['description']) : $product['product_name']);

                        $sheet -> SetCellValue($Letters_arr[$i] . '1', 'meta_keyword');

                        if ($metaKeywords !== '') {
                            $sheet -> SetCellValue($Letters_arr[$i] . ($key + 2), $metaKeywords);
                        }
                        $i++;
                        break;
                }
            }
            $i++;
        }

        if (!is_dir(getcwd() . "/public/xml/csv")) {
            mkdir(getcwd() . "/public/xml/csv", 0755, true);
        }

        $fileName = 'csvFeed_' . $user['bulstat'] . '_products.csv';
        $filePath = getcwd() . "/public/xml/csv/$fileName";

        ob_flush();
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        ob_clean();

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer -> setUseBOM(true);
        $writer -> setDelimiter(';');
        $writer -> setEnclosure('"'); // How to enclose fields (default is double quotes)
        $writer -> setLineEnding("\r\n"); // Set line ending


        $writer -> save($filePath);

        if (!is_cli()) {
            $writer -> save('php://output');
        }

        $spreadsheet -> disconnectWorksheets();
        unset($spreadsheet);
    }

    public function save_xmlConf($tip = 'xml', $json = []) {

        $this -> db -> table(self::TBL_USERSITE . ' u')
                -> set('xml_conf_json', json_encode([$tip => $json], JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE))
                -> where('id', session('user_id'))
                -> update();
    }

    private function addXmlElement($parent, $tagementName, $tagementValue) {
        $tagementValue = htmlspecialchars($tagementValue);
        $parent -> addChild($tagementName, $tagementValue);
    }

    private function generateSeoURL($string, $wordLimit = 5) {
        $separator = '-';

        if ($wordLimit != 0) {
            $wordArr = explode(' ', $string);
            $string  = implode(' ', array_slice($wordArr, 0, $wordLimit));
        }

        $quoteSeparator = preg_quote($separator, '#');

        $trans = array(
            '&.+?;'                      => '',
            '[^\w\d _-]'                 => '',
            '\s+'                        => $separator,
            '(' . $quoteSeparator . ')+' => $separator
        );

        $string = strip_tags($string);
        foreach ($trans as $key => $val) {
            $string = preg_replace('#' . $key . '#i' . 'u', $val, $string);
        }

        $string = strtolower($string);

        return trim(trim($string, $separator));
    }

    // ---------------------------------------------------
    // ФУНКЦИЯ ЗА ИЗВЛИЧАНЕ НА КЛЮЧОВИ ДУМИ ОТ ОПИСАНИЕТО
    // ---------------------------------------------------
    private function extract_keywords($str, $minWordLen = 5, $minWordOccurrences = 1, $asArray = false) {
        $str = mb_strtolower($str);

        $stopWords = array('ceнзop', 'мn34172');

        foreach ($stopWords as $stopWord) {
            if (strpos($str, $stopWord) != false) {
                $str = str_replace($stopWord, '', $str);
            }
        }

        $str = preg_replace('/[^\p{L}а-яА-Яa-zA-Z0-9]/u', ' ', $str);
        $str = trim(preg_replace('/\s+/', ' ', $str));

        $words    = explode(' ', $str);
        $keywords = array();
        while (($c_word   = array_shift($words)) !== null) {
            if (strlen($c_word) < $minWordLen)
                continue;

            $c_word            = strtolower($c_word);
            if (array_key_exists($c_word, $keywords))
                $keywords[$c_word][1]++;
            else
                $keywords[$c_word] = array($c_word, 1);
        }

        usort($keywords, function ($first, $sec) {
            return $sec[1] - $first[1];
        });

        $final_keywords = array();
        foreach ($keywords as $keyword_det) {
            if ($keyword_det[1] < $minWordOccurrences)
                break;
            array_push($final_keywords, $keyword_det[0]);
        }

        $final_keywords = array_slice($final_keywords, 0, 10); //дължина на масива за кл думи

        return $asArray ? $final_keywords : implode(', ', $final_keywords);
    }
}
