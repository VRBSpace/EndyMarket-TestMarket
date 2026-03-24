<?php
// Състояния за мобилните бутони
$hasBrand = (bool) $brandSel;
$hasModel = (bool) $model;

// Категория изисква избрана година; ако липсва нещо – към първото липсващо
//$hrefCat = !$hasBrand ? '#modal_relationFilter' : (!$hasModel ? '#pickModel' : '#pickCat');
// disabled визия (по желание)
$isModelDisabled = !$hasBrand;
$isCatDisabled   = !$hasBrand || !$hasModel;
?>

<!-- MOBILE: 4 бутона винаги -->
<div class="d-flex flex-row align-items-center justify-content-between flex-wrap p-3">
    <a id="js-popupOpen-brand" href="javascript:;" class="btn btn-outline-secondary m-trigger text-truncate flex-fill rounded-0 col-6">
<?= $hasBrand ? $brandSel['brandTxt'] ?? $brandId : 'Марка' ?>
    </a>

    <a id="js-popupOpen-model" href="javascript:;" class="btn btn-outline-secondary m-trigger text-truncate flex-fill rounded-0 col-6 <?= $isModelDisabled ? 'text-muted' : '' ?>" <?= $isModelDisabled ? 'style="pointer-events:none;"' : '' ?>>
        <?= $hasModel ? $model : 'Модел' ?>
    </a>

        <?php
        if ($subs):
            for ($i = 0; $i < 3; $i++):
                if (isset($subPath[$i], $subs[$i][$subPath[$i]]['category_name'])) {
                    $name[$i] = $subs[$i][$subPath[$i]]['category_name'];
                }
                ?>
            <a class="js-popupOpen-cat btn btn-outline-secondary m-trigger text-truncate flex-fill rounded-0 col-6 <?= $isCatDisabled ? 'text-muted' : '' ?>" href="javascript:;" data-level="<?= $i + 1 ?>"  <?= $isCatDisabled ? 'style="pointer-events:none;"' : '' ?>>
            <?= !empty($name[$i]) ? $name[$i] : 'Подкатегория ' . $i + 1 ?>
            </a>
        <?php endfor ?>
        <?php endif ?>
</div>