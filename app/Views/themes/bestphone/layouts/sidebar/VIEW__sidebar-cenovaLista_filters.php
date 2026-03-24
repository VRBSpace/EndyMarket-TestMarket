<aside id="sidebarCenovaListaContent" class="sidebar border u-sidebar u-sidebar--left" aria-labelledby="sidebarCenovaListaContent">

    <div class="u-sidebar__scroller">
        <div class="u-sidebar__container">

            <!-- Toggle Button -->
            <div class="d-flex align-items-center pt-3 px-4 bg-white">
                <button type="button" class="close ml-auto"
                        aria-controls="sidebarCenovaListaContent"
                        aria-haspopup="true"
                        aria-expanded="false"
                        data-unfold-event="click"
                        data-unfold-hide-on-scroll="false"
                        data-unfold-target="#sidebarCenovaListaContent"
                        data-unfold-type="css-animation"
                        data-unfold-animation-in="fadeInLeft"
                        data-unfold-animation-out="fadeOutLeft"
                        data-unfold-duration="500">
                    <span aria-hidden="true"><i class="ec ec-close-remove"></i></span>
                </button>
            </div>
            <!-- End -->

            <!-- sidebar-filters -->
            <div class="js-scrollbar u-sidebar__body">
                <div class="u-sidebar__content u-header-sidebar__content px-2">

                    <div class="border-bottom mx-auto mb-2">
                        <h6 class="section-title section-title__sm mb-0 p-2 font-weight-bold font-size-16 mx-1"><i class="fas fa-sliders-h"></i>&nbsp;ФИЛТРИ ЗА ЦЕНОВА ЛИСТА</h6>

                        <a class="btn font-size-12 float-right p-2 text-danger" href="<?= $_SERVER['REQUEST_URI'] ?>"><i class="fa fa-trash"></i>&nbsp;изчисти филтрите</a>
                    </div>    

                    <br>

                    <?php if (!empty($res)): ?>
                        <div class="mb-6">
                            <label>Цена</label> 
                            <section class="range-slide">
                                <input class="js-filter-range-cenova" type="text"
                                       data-route="<?= route_to('CenovaLista-filter', $_GET['categoryId'] ?? 'null') ?>"
                                       data-extra-classes="u-range-slider u-range-slider-indicator u-range-slider-grid"
                                       data-type="double"
                                       data-grid="false"
                                       data-hide-from-to="true"
                                       data-prefix="$"
                                       data-min="0"
                                       data-max="<?= $maxPrice ?>"
                                       data-from="0"
                                       data-to="<?= $maxPrice ?>"
                                       data-result-min="#rangeSliderExample3MinResult"
                                       data-result-max="#rangeSliderExample3MaxResult">

                                <div class="text-gray-111 d-flex mb-1">                       
                                    <span id="rangeSliderExample3MinResult"></span>
                                    <input type="hidden" name="min_price" id="min_price">
                                    <span>&nbsp;<?= get_valuta() ?></span>

                                    <span class="mx-0dot5"> — </span>
                                    <span id="rangeSliderExample3MaxResult"></span>
                                    <input type="hidden" name="max_price" id="max_price">
                                    <span>&nbsp;<?= get_valuta() ?></span>
                                </div>
                            </section> 

                            <div class="my-2">
                                <label>име и производител</label> 
                                <input id="js-filter-productAndBrandName" class="form-control" data-route="<?= route_to('CenovaLista-filter', $cenovaListaId) ?>" type="text">
                            </div>

                            <div class="my-2">
                                <label>Сортировка</label> 
                                <div>
                                    <select id="js-filter-sorting-select" class="js-productFilter form-control selectpicker border" data-route="<?= route_to('CenovaLista-filter', $cenovaListaId) ?>" data-style="btn bg-white font-weight-normal  border  bg-lg-down-transparent border-lg-down-0">
                                        <option value="">---</option>
                                        <option value="product_name ASC">Име Възходящ ред</option>
                                        <option value="product_name DESC">Име Низходящ ред</option>
                                        <option value="<?= session() -> has('user_id') ? $productPriceLevel : 'cenaKKC' ?> DESC">Цена низходящо</option>
                                        <option value="<?= session() -> has('user_id') ? $productPriceLevel : 'cenaKKC' ?> ASC">Цена възходящо</option>
                                    </select> 
                                </div>
                            </div>

                            <div class="custom-control custom-checkbox">
                                <input id="js-nalichChk" class="js-productFilter custom-control-input" data-route="<?= route_to('CenovaLista-filter', $cenovaListaId) ?>" type="checkbox" value="1">
                                <label class="custom-control-label" for="js-nalichChk">В наличност</label>
                            </div>
                        </div>
                    <? endif ?>
                </div>
                <!-- End -->
            </div>
        </div>
</aside>
<!-- End order filters -->
