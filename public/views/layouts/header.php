<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-bs-theme-color="theme-color-default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description">
    <title>CMA</title>
    <link rel="icon" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/icon.ico" type="image/x-icon">
    <!-- <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/bootstrap/bootstrap.min.css"> -->
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/core/libs.min.css" />
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/vendor/aos/dist/aos.css" />
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/hope-ui.min.css?v=5.0.0" />
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/custom.min.css?v=5.0.0" />
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/customizer.min.css?v=5.0.0" />
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/rtl.min.css?v=5.0.0" />
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/vendor/flatpickr/dist/flatpickr.min.css" />
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/dataTables.bootstrap5.css">
    <!-- <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/driver.css"> -->
    <link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/style.css">

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                "gtm.start": new Date().getTime(),
                event: "gtm.js",
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != "dataLayer" ? "&l=" + l : "";
            j.async = true;
            j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, "script", "dataLayer", "GTM-WNGH9RL");
        window.tag_manager_event = "dashboard-free-preview";
        window.tag_manager_product = "HopeUI";
    </script>
    <!-- End Google Tag Manager -->
</head>

<body>
    <div class="overlay" id="overlay"></div>
    <div class="spinner-container" id="spinner-container">
        <div class="loader d-none" id="spinner"></div>
    </div>
    <?php include_once "menu.php"; ?>