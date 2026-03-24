<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__head") ?>
<?= view("{$_ENV['app.theme']}/layouts/header/VIEW__header") ?>

<?php
$_xmlConf_json = json_decode($user['xml_conf_json'], true) ?? [];

// Функция за проверка дали дадено поле трябва да е отметнато
function isChecked($key, $_xmlConf_json) {

    if ($key == 'wordpress' || $key == 'none') {
        return in_array($key, $_xmlConf_json['xml'] ?? []) ? 'checked' : '';
    }

    return array_key_exists($key, $_xmlConf_json['xml'] ?? []) ? 'checked' : '';
}
?>

<style>
    /* The container */
    .containerC {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    /* Hide the browser's default checkbox */
    .containerC input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }
    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #eee;
        border: 1px solid #999;
    }
    /* On mouse-over, add a grey background color */
    .containerC:hover input ~ .checkmark {
        background-color: #ccc;
    }
    /* When the checkbox is checked, add a blue background */
    .containerC input:checked ~ .checkmark {
        background-color: #2196F3;
    }
    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    /* Show the checkmark when checked */
    .containerC input:checked ~ .checkmark:after {
        display: block;
    }
    /* Style the checkmark/indicator */
    .containerC .checkmark::after {
        left: 5px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
    /* The container_radio */
    .container_radio {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    /* Hide the browser's default radio button */
    .container_radio input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    /* Create a custom radio button */
    .checkmark_radio {
        position: absolute;
        top: 0;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #eee;
        border-radius: 50%;
        border: 1px solid #999;
    }
    /* On mouse-over, add a grey background color */
    .container_radio:hover input ~ .checkmark_radio {
        background-color: #ccc;
    }
    /* When the radio button is checked, add a blue background */
    .container_radio input:checked ~ .checkmark_radio {
        background-color: #2196F3;
    }
    /* Create the indicator (the dot/circle - hidden when not checked) */
    .checkmark_radio:after {
        content: "";
        position: absolute;
        display: none;
    }
    /* Show the indicator (dot/circle) when checked */
    .container_radio input:checked ~ .checkmark_radio:after {
        display: block;
    }
    /* Style the indicator (dot/circle) */
    .container_radio .checkmark_radio:after {
        top: 5px;
        left: 5px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: white;
    }
</style>

<script>
    const XML_CONF_JSON = <?= $user['xml_conf_json'] ?? 0 ?>;
</script>

<main id="content" role="main">
    <div class="container">
        <div class="mb-4">
            <h1 class="text-center"><b>XML /csv генериране</b></h1>

            <button class="js-export btn btn-primary-orange rounded-0" type="button" data-route="<?= route_to('Xml-xmlExport') ?>">
                <i class="fa fa-download" style="font-size:16px;margin-right: 10px;"></i>XML генериране на линк
            </button> 

            <button class="js-export btn btn-primary-orange rounded-0" data-route="<?= route_to('Xml-csvExport') ?>" type="button">
                <i class="fa fa-download" style="font-size:16px;margin-right: 10px;"></i>CSV генериране на линк
            </button>

            <div class="row d-flex w-50 align-items-center float-right">
                <label class="col-3 m-0">Xml /csv линк</label>
                <?php
                $_filePath = '';

                if (!empty($_xmlConf_json)) {
                    $_ext    = array_keys($_xmlConf_json)[0];
                    $_dirCsv = ($_ext === 'csv') ? $_ext . '/' : '';

                    $_filePath = base_url() . "public/xml/{$_dirCsv}{$_ext}Feed_{$user['bulstat']}_products.$_ext";
                }
                ?>
                <input type="text" value="<?= $_filePath ?>" autocomplete="off" class="col form-control">
            </div>
        </div>

        <label class="text-secondary">Легенда: Mоже да избере конкретни атрибути, които ще присътват в xml /csv файла. При всяко ново генериране на xml /csv линк избраните атрибути ще бъдат записани в базата данни. След като се генерира веднъж xml /csv линк, всеки ден данните ще бъде актуализирани автоматично според избраните атрибути. </label> <br><br>

        <form id="form-export" class="row" action="<?= route_to('Xml-xmlExport') ?>" method="post" enctype="multipart/form-data">
            <div id="tbl_checkboxItems" class="w-100 d-flex" >
                <div class="col">

                    <table cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td>
                                    <label class="containerC">ID на продукт
                                        <input type="checkbox" name="form[product_id]" id="chk_product_id" value="1" <?= isChecked('product_id', $_xmlConf_json) ?>>
                                        <span class="checkmark"></span></label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Име на продукт
                                        <input type="checkbox" name="form[name]" id="chk_name" value="1"  <?= isChecked('name', $_xmlConf_json) ?>>
                                        <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">URL на продукт
                                        <input type="checkbox" name="form[product_url]" id="chk_product_url" value="1" <?= isChecked('product_url', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Модел
                                        <input type="checkbox" name="form[model]" id="chk_model" value="1"  <?= isChecked('model', $_xmlConf_json) ?>>
                                        <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Цена ККС
                                        <input type="checkbox" name="form[price]" id="chk_price" value="1"  <?= isChecked('price', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Дилърска цена
                                        <input type="checkbox" name="form[price_dilar]" id="chk_price_dilar" value="1" <?= isChecked('price_dilar', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Цена - промоционална
                                        <input type="checkbox" name="form[specialPrice]" id="chk_specialPrice" value="1" <?= isChecked('specialPrice', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Количество
                                        <input type="checkbox" name="form[quantity]" id="chk_quantity" value="1" disabled <?= isChecked('quantity', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Описание
                                        <input type="checkbox" name="form[description]" id="chk_description" value="1" <?= isChecked('description', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Стокова наличност
                                        <input type="checkbox" name="form[stock_status]" id="chk_stock_status" value="1"  <?= isChecked('stock_status', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Снимкa - базова
                                        <input type="checkbox" name="form[images]" id="chk_images" value="1" <?= isChecked('images', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Допълнителни снимки                              
                                        <input type="checkbox" name="form[aditional_images]" id="chk_aditional_images" value="1" <?= isChecked('aditional_images', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Категория
                                        <input type="checkbox" name="form[category]" id="chk_category" value="1" <?= isChecked('category', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Производител
                                        <input type="checkbox" name="form[manufacturer]" id="chk_manufacturer" value="1" <?= isChecked('manufacturer', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col">
                    <table cellspacing="0" cellpadding="0">
                        <tbody>

                            <tr>
                                <td>
                                    <label class="containerC">Мярка за тегло
                                        <input type="checkbox" name="form[weight_class]" id="chk_weight_class" value="1" <?= isChecked('weight_class', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Мярка за размер
                                        <input type="checkbox" name="form[length_class]" id="chk_length_class" value="1" <?= isChecked('length_class', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Тегло
                                        <input type="checkbox" name="form[weight]" id="chk_weight" value="1" <?= isChecked('weight', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Дължина
                                        <input type="checkbox" name="form[length]" id="chk_length" value="1" <?= isChecked('length', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Широчина
                                        <input type="checkbox" name="form[width]" id="chk_width" value="1"  <?= isChecked('width', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Височина
                                        <input type="checkbox" name="form[height]" id="chk_height" value="1" <?= isChecked('height', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col">
                    <table cellspacing="0" cellpadding="0">
                        <tbody>

                            <tr>
                                <td>
                                    <label class="containerC">Характеристики
                                        <input type="checkbox" name="form[product_attribute]" id="chk_product_attribute" value="1" <?= isChecked('product_attribute', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Seo
                                        <input type="checkbox" name="form[seo_keyword]" id="chk_seo_keyword" value="1" <?= isChecked('seo_keyword', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col">
                    <table cellspacing="0" cellpadding="0">
                        <tbody>

                            <tr>
                                <td>
                                    <label class="containerC">Мета описание
                                        <input type="checkbox" name="form[meta_description]" id="chk_meta_description" value="1" <?= isChecked('meta_description', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Мета ключови думи
                                        <input type="checkbox" name="form[meta_keyword]" id="chk_meta_keyword" value="1" <?= isChecked('meta_keyword', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Налично от дата
                                        <input type="checkbox" name="form[date_available]" id="chk_date_available" value="1" <?= isChecked('date_available', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Дата на добавяне
                                        <input type="checkbox" name="form[date_added]" id="chk_date_added" value="1" <?= isChecked('date_added', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="containerC">Дата на промяна
                                        <input type="checkbox" name="form[date_modified]" id="chk_date_modified" value="1" <?= isChecked('date_modified', $_xmlConf_json) ?>> <span class="checkmark"></span> </label>
                                </td>
                            </tr>

<!--                            <tr>
                                <td>
                                    <label class="containerC" for="chk_sort">Ред на подреждане
                                        <input type="checkbox" name="form[sort_order]" id="chk_sort" value="1"> <span class="checkmark"></span> </label>
                                </td>
                            </tr>-->
                        </tbody>
                    </table>
                </div>
            </div>

            <h3>Платформа</h3>
            <div class="d-flex w-100">
                <div class="col">
                    <label class="container_radio">Wordpress
                        <input id="chk_wordpress" type="radio" name="form[platf]" value="wordpress" <?= isChecked('wordpress', $_xmlConf_json) ?>> 
                        <span class="checkmark_radio"></span> 
                    </label>

                    <label class="container_radio">Неизвестна
                        <input id="chk_none" type="radio" name="form[platf]" value="none" <?= empty($_xmlConf_json) ? 'checked' : isChecked('none', $_xmlConf_json); ?>> 
                        <span class="checkmark_radio"></span> 
                    </label>
                </div>
            </div>   

            <br>

            <h3>Избор на атрибути, които ще присъстват в xml експорт</h3>

            <div  class="d-flex w-100">

                <br>

                <div id="radioBtns" class="col">
                    <table cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td>
                                    <label class="container_radio">Основни атрибути
                                        <input type="radio" name="checked" value="check_main_data" id="check_main_data" checked="checked">
                                        <span class="checkmark_radio"></span></label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="container_radio">Основни атрибути + тегло и размери
                                        <input type="radio" name="checked" value="check_all_important_data" id="check_all_important_data" > <span class="checkmark_radio"></span></label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="container_radio">Всички атрибути
                                        <input type="radio" name="checked" value="check_all" id="check_all" > <span class="checkmark_radio"></span></label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label class="container_radio">Нищо
                                        <input type="radio" name="checked" value="check_none" id="check_none" > <span class="checkmark_radio"></span></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <br>
            </div>

            <div>
                <h3>Филтриране по категория или ценова листа</h3>   
                <label class="text-secondary d-block col">Забележка: Mоже да избере филтриране или по категория само, или по ценова листа, но не и по двата критерия заедно.</label> <br>
            </div>

            <div  class="d-flex w-100">
                <div class="col">
                    <label>
                        <span data-toggle="tooltip" title="Оставете празно за всички категории">Филтър по категории</span>
                    </label>

                    <input id="category" class="w-100 p-2"  type="text" placeholder="Въведете име на категорията" data-route="<?= route_to('Xml-autocomplete', 'category') ?>" style="background: #ffef74;border: 1px solid #999;"/>

                    <div class="autocomplete"></div>

                    <div id="category-box" class="well well-sm w-100 p-2" style="height: 137px; overflow: auto;border: 1px solid #999;">

                        <?php
                        if (!empty($_xmlConf_json['xml']['product_category'])) {
                            foreach ($_xmlConf_json['xml']['product_category'] as $k => $v) {
                                ?>
                                <div>
                                    <i class="fa fa-minus-circle btn p-1"></i> <?= $k ?>
                                    <input type="hidden" name="form[product_category][<?= $k ?>]" value="<?= $v ?>">
                                </div>
                                <?
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="col">
                    <label>
                        <span data-toggle="tooltip" title="Оставете празно за всички ценовата листи">Филтър по ценови листи</span>
                    </label>

                    <input id="cenova" class="w-100 p-2"  type="text" name="zenoviListi" placeholder="Въведете име на ценовата листа" data-route="<?= route_to('Xml-autocomplete', 'cenova') ?>" style="background: #ffef74;border: 1px solid #999;"/>

                    <div class="autocomplete"></div>

                    <div id="productIds-box" class="well well-sm w-100 p-2" style="height: 137px; overflow: auto;border: 1px solid #999;">

                        <?php
                        if (!empty($_xmlConf_json['xml']['productIds_fromCenova'])) {
                            foreach ($_xmlConf_json['xml']['productIds_fromCenova'] as $k => $v) {
                                ?>
                                <div>
                                    <i class="fa fa-minus-circle btn p-1"></i> <?= $k ?>
                                    <input type="hidden" name="form[productIds_fromCenova][<?= $k ?>]" value="<?= $v ?>">
                                </div>
                                <?
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
<br>

<?php
echo view("{$_ENV['app.theme']}/layouts/sidebar/VIEW__sidebar-account");  // Account Sidebar Navigation
echo view("{$_ENV['app.theme']}/layouts/footer/VIEW__footer");
?>
