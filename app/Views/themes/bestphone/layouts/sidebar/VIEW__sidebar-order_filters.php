<!-- Sidebar order filters -->
<?php
if (!ISMOBILE) {
    return;
}
?>
<aside id="sidebarOrderContent" class="sidebar border u-sidebar u-sidebar--left" aria-labelledby="sidebarOrderContent">

    <div class="u-sidebar__scroller">
        <div class="u-sidebar__container">

            <!-- Toggle Button -->
            <div class="d-flex align-items-center pt-3 px-4 bg-white">
                <button type="button" class="close ml-auto"
                        aria-controls="sidebarOrderContent"
                        aria-haspopup="true"
                        aria-expanded="false"
                        data-unfold-event="click"
                        data-unfold-hide-on-scroll="false"
                        data-unfold-target="#sidebarOrderContent"
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
                        <h6 class="section-title section-title__sm mb-0 p-2 font-weight-bold font-size-16 mx-1"><i class="fas fa-sliders-h"></i>&nbsp;ФИЛТРИ ЗА ПОРЪЧКИТЕ</h6>

                        <a id="js-clear-filter-mobile" class="btn font-size-12 float-right p-2 text-danger"><i class="fa fa-trash"></i>&nbsp;изчисти филтрите</a>
                    </div>    

                    <br>

                    <div class="mb-6">
                        <label>поръчка №</label> 
                        <input id="js-orderId" class="js-filer-mobile my-1 form-control h-2rem" type="search">

                        <label>документ № </label> 
                        <input id="js-doc" class="js-filer-mobile my-1 form-control h-2rem" type="text">

                        <label>товарителница № </label> 
                        <input id="js-tovaritelniza" class="js-filer-mobile my-1 form-control h-2rem" type="text">

                        <label>статус</label> 
                        <select id="js-status" class="js-filer-mobile form-control px-2 py-1 h-2rem">
                            <option value=""></option>
                            <option value="не приключена">не приключена</option>
                            <option value="обработва се">обработва се</option>
                            <option value="авансово">авансово</option>
                            <option value="завършена">завършена</option>
                        </select>

                        <label>дата</label>
                        <input id="js-date" class="js-filer-mobile my-1 form-control h-2rem" type="date">
                    </div>
                </div>
                <!-- End -->
            </div>
        </div>
</aside>
<!-- End order filters -->
