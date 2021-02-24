<!DOCTYPE html>
<html lang="en">

<?php
$sidebar = $this->session->userdata("sidebar");
$navbar = $this->session->userdata("navbar");
$user_id = $this->session->userdata("user_id");
$group_id = $this->session->userdata("group");
?>

<head>
    <style>
        select {
            color: #000000 !important;
        }

        input.form-control-sm {
            border-radius: 0px;
        }

        .loading {
            position: fixed;
            z-index: 9999;
            height: 2em;
            width: 2em;
            overflow: visible;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .row-order {
            width: 20px;
        }

        .row-aksi {
            width: 100px;
        }

        .dataTables_wrapper .dataTable .btn {
            padding: 0.2rem 0.2rem !important;
        }

        .dataTables_wrapper .dataTable .btn i {
            margin-right: 0rem !important;
        }

        thead>tr th {
            text-align: center;
        }

        .table td {
            padding: 1rem 0.9375rem !important;
        }

        .dataTables_wrapper select,
        .dataTables_filter>label>input {
            height: 2rem !important;
        }

        .pull-right {
            text-align: right;
        }

        .grid-margin {
            margin-bottom: 1rem;
        }

        .select2-container--default .select2-selection--single {
            height: 2.575rem;
            padding-left: .4375rem !important;
            padding-right: .4375rem !important;
        }

        .table td {
            padding: 10px 0.9375rem !important;
        }

        .sidebar .nav .nav-item .nav-link .menu-title {
            line-height: 1.5 !important;
        }

        .back-landing {
            background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#014a7d), to(#3399cc)) !important;
        }

        .sidebar-dark .sidebar {
            position: fixed !important;
            width: 195px !important;
        }

        .main-panel {
            width: 100% !important;
        }

        .menu-title {
            padding-top: 4px;
        }

        .hide {
            display: none;
        }

        .nav-item {
            margin-left: 0px !important;
        }
    </style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SIMPLE</title>

    <link rel="stylesheet" href="<?php echo base_url("assets/skpd"); ?>/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="<?php echo base_url("assets/skpd"); ?>/vendors/css/vendor.bundle.base.css">

    <?php
    if (isset($_plugin) && $_plugin) {
        if (isset($_plugin[PLUG_DATEPICKER])) { ?>
            <link rel="stylesheet" href="<?php echo base_url("assets/skpd"); ?>/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
        <?php }

            if (isset($_plugin[PLUG_NOTIFICATION])) { ?>
            <link rel="stylesheet" href="<?php echo base_url("assets/skpd"); ?>/vendors/jquery-toast-plugin/jquery.toast.min.css">
        <?php }

            if (isset($_plugin[PLUG_DATATABLE])) { ?>
            <link rel="stylesheet" href="<?php echo base_url("assets/skpd"); ?>/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
        <?php }

            if (isset($_plugin[PLUG_SELECT2])) { ?>
            <link rel="stylesheet" href="<?php echo base_url("assets/skpd") ?>/vendors/select2/select2.min.css">
            <link rel="stylesheet" href="<?php echo base_url("assets/skpd") ?>/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <?php }
    } ?>

    <link rel="stylesheet" href="<?php echo base_url("assets/skpd"); ?>/css/style.css">
    <link rel="shortcut icon" href="<?php echo base_url("assets/skpd"); ?>/images/favicon.png" />
</head>

<body class="<?php echo $sidebar; ?>">
    <div class="loading" style="display: none;">
        <div class="jumping-dots-loader">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center back-landing">
                <a class="navbar-brand brand-logo" href="<?php echo base_url("admin#!"); ?>">
                    <img src="<?php echo base_url("assets"); ?>/images/Logo3.png" class="mr-2" alt="logo" />
                </a>
                <a class="navbar-brand brand-logo-mini" href="index.html">
                    <img src="<?php echo base_url("assets"); ?>/images/logo-small.png" alt="logo" />
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end back-landing" style="padding-right: 20px;">
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item">
                        <a href="https://app.swaggerhub.com/apis/cv-prima/pusatdata/1.0.0" rel="nofollow"><img src="<?php echo base_url('/assets/images/API.png'); ?> " style="height: 46px;"></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo  base_url('uploads/modul_sipd.pdf') ?>"><img src="<?php echo base_url('/assets/landingpage/images/MODUL1.png'); ?> " style=" height: 46px;"></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo  base_url('uploads/definisi_operasional.xlsx') ?>"><img src="<?php echo base_url(); ?>/assets/images/operasional.png" height="40px"></a>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="ti-layout-grid2"></span>
                </button>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">

                <?php
                if (isset($_view) && $_view)
                    $this->load->view($_view);
                ?>

            </div>
        </div>
    </div>

    <script src="<?php echo base_url("assets/skpd"); ?>/vendors/js/vendor.bundle.base.js"></script>

    <!-- <script src="<?php echo base_url("assets/skpd"); ?>/vendors/chart.js/Chart.min.js"></script> -->

    <!-- <script src="<?php echo base_url("assets/skpd"); ?>/js/off-canvas.js"></script> -->
    <script src="<?php echo base_url("assets/skpd"); ?>/js/hoverable-collapse.js"></script>
    <script src="<?php echo base_url("assets/skpd"); ?>/js/template.js"></script>
    <script src="<?php echo base_url("assets/skpd"); ?>/js/settings.js"></script>
    <script src="<?php echo base_url("assets/skpd"); ?>/js/todolist.js"></script>

    <script src="<?php echo base_url("assets/skpd"); ?>/js/dashboard.js"></script>

    <?php
    if (isset($_plugin) && $_plugin) {
        if (isset($_plugin[PLUG_DATATABLE])) { ?>
            <script src="<?php echo base_url("assets/skpd"); ?>/vendors/datatables.net/jquery.dataTables.js"></script>
            <script src="<?php echo base_url("assets/skpd"); ?>/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
            <!-- <script src="<?php echo base_url("assets/skpd"); ?>/js/data-table.js"></script> -->
        <?php }

            if (isset($_plugin[PLUG_SELECT2])) { ?>
            <script src="<?php echo base_url("assets/skpd"); ?>/vendors/select2/select2.min.js"></script>
    <?php }
    }

    if (isset($_js) && $_js) $this->load->view($_js);
    ?>

</body>

</html>