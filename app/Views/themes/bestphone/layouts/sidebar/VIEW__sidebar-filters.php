<!-- Sidebar Navigation -->
<aside id="sidebarContent1" class="sidebar border u-sidebar u-sidebar--left" aria-labelledby="sidebarNavToggler1">
    
    <div class="u-sidebar__scroller">
        <div class="u-sidebar__container">
            <div class="pb-0">
                <!-- Toggle Button -->
                <div class="d-flex align-items-center pt-3 px-4 bg-white">
                    <button type="button" class="close ml-auto"
                            aria-controls="sidebarContent1"
                            aria-haspopup="true"
                            aria-expanded="false"
                            data-unfold-event="click"
                            data-unfold-hide-on-scroll="false"
                            data-unfold-target="#sidebarContent1"
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
                    <div class="u-sidebar__content u-header-sidebar__content px-4">
                        <div class="mb-6">
                            <?php
                            if (ISMOBILE) {
                                echo view($views['shop-filter']);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- End -->
            </div>
        </div>
    </div>
</aside>
<!-- End Sidebar Navigation -->
