<?php $_companyData = $settings_portal['companyData'] ?? ''; ?> <!-- in basecontroller  -->
<!-- ========== FOOTER ========== -->
<footer>

    <!-- Social Media Icons -->
    <div class="footer-social-icons py-3">
        <div class="container d-flex justify-content-center">
            <a href="<?= $settings_portal['links']['facebook'] ?? '' ?>" class="social-icon mx-2" target="_blank">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="<?= $settings_portal['links']['instagram'] ?? '' ?>" class="social-icon mx-2" target="_blank">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="<?= $settings_portal['links']['twitter'] ?? '' ?>" class="social-icon mx-2" target="_blank">
                <i class="fab fa-twitter"></i>
