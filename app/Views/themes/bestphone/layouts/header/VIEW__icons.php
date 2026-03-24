<?php $_companyData = $settings_portal['companyData'] ?? ''; ?> <!-- in basecontroller  -->
<!-- Header Icons -->
<div class="col d-flex justify-content-end  align-self-center">
    <div class="d-flex">
        <ul class="d-flex list-unstyled mb-0">
            <!-- <li class="col"><a href="../shop/compare.html" class="text-gray-90" data-toggle="tooltip" data-placement="top" title="Compare"><i class="font-size-22 ec ec-compare"></i></a></li> -->
            <!-- <li class="col"><a href="../shop/wishlist.html" class="text-gray-90" data-toggle="tooltip" data-placement="top" title="Favorites"><i class="font-size-22 ec ec-favorites"></i></a></li> -->
            <?php if (session() -> has('user_id')): ?>   

            <?php endif; ?>
            <!-- <li class="nav-item u-header__nav-item align-self-center mx-2 header-icon">
                <a class="text-dark fa fa-home font-size-20" href="/"></a>
            </li>

            <li class="nav-item u-header__nav-item align-self-center mx-2 header-icon">
                <a class="text-dark" href="<?= $settings_portal['links']['gMaps'] ?? '' ?>" target="_blank"><i class="ec ec-map-pointer align-self-center font-size-20"></i></a>
            </li>

            <li class="nav-item u-header__nav-item align-self-center mx-2 header-icon">
                <a class="text-dark" href="<?= $settings_portal['links']['facebook'] ?? ''; ?>" target="_blank">
                    <i class=" fab fa-facebook-square align-self-center font-size-20"></i></a>
            </li> -->

            <li class="nav-item u-header__nav-item align-self-center mx-2 header-icon">
                <!-- Customer Care -->
                <div class="d-none d-xl-block">
                    <div class="d-flex">
                        <!-- <i class="ec1 ec-support1 fa fa-envelope align-self-center font-size-20 text-dark"></i> -->


                        <div class="ml-2 text-dark">
                            <!-- <div class="email">
                                <a href="mailto:<?= $_companyData['email'] ?? '' ?>?subject=Help Need" class="text-white"><?= $_companyData['email'] ?? '' ?></a>
                            </div> -->

                            <!-- <div class="phone mt-1"> -->
                                <!-- <i class="bi bi-telephone-fill text-white"></i> -->
                                <a href="tel:<?= $_companyData['tel'] ?? '' ?>" class="text-white header-call-btn"><i class="bi bi-telephone-fill text-white"></i> <?= $_companyData['tel'] ?? '' ?></a>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                <!-- End Customer Care -->
            </li>
        </ul>
    </div>
</div>
<!-- End Header Icons -->
