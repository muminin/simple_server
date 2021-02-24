<!DOCTYPE html>
<html lang="en">

<?php
$sidebar = $this->session->userdata("sidebar");
$navbar = $this->session->userdata("navbar");
$user_id = $this->session->userdata("user_id");
$group_id = $this->session->userdata("group");
?>

<head>
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

        .break-word {
            white-space: normal !important;
            word-wrap: break-word !important;
            word-break: normal !important;
            width: auto !important;
        }
    </style>
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
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row navbar-<?php echo $navbar; ?>">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url("assets"); ?>/images/Logo3.png" class="mr-2" alt="logo" />
                </a>
                <a class="navbar-brand brand-logo-mini" href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url("assets"); ?>/images/logo-small.png" alt="logo" />
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="ti-layout-grid2"></span>
                </button>

                <ul class="navbar-nav navbar-nav-right">
                    <a href="javascript:history.go(-1)" class="navbar-toggler navbar-toggler align-self-center">
                        <i class="ti-back-left mx-0"></i>
                    </a>
                    &nbsp;
                    &nbsp;

                    <a class="navbar-toggler navbar-toggler align-self-center">
                        <?php echo $tahun_sess; ?>
                    </a>
                    &nbsp;
                    &nbsp;

                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <img src="<?php echo base_url("assets"); ?>/pages/media/users/user.png" alt="profile" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                            <a href="<?php echo base_url("admin"); ?>" class="dropdown-item">
                                <i class="ti-close text-primary"></i>
                                Kembali
                            </a>
                            <a href="<?php echo base_url("logout"); ?>" class="dropdown-item">
                                <i class="ti-power-off text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="ti-layout-grid2"></span>
                </button>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            <?php if (strtolower($title) == "dashboard") { ?>
                <div class="theme-setting-wrapper">
                    <div id="settings-trigger"><i class="ti-settings"></i></div>
                    <div id="theme-settings" class="settings-panel">
                        <i class="settings-close ti-close"></i>
                        <p class="settings-heading">SIDEBAR SKINS</p>
                        <div class="sidebar-bg-options <?php echo ($sidebar == SIDE_LIGHT) ? "selected" : ""; ?>" id="sidebar-light-theme">
                            <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
                        </div>
                        <div class="sidebar-bg-options <?php echo ($sidebar == SIDE_DARK) ? "selected" : ""; ?>" id="sidebar-dark-theme">
                            <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
                        </div>
                        <p class="settings-heading mt-2">HEADER SKINS</p>
                        <div class="color-tiles mx-0 px-4">
                            <div class="tiles nav-select success <?php echo ($navbar == NAV_SUCCESS) ? "selected" : ""; ?>" data-color=" success"></div>
                            <div class="tiles nav-select warning <?php echo ($navbar == NAV_WARNING) ? "selected" : ""; ?>" data-color="warning"></div>
                            <div class="tiles nav-select danger <?php echo ($navbar == NAV_DANGER) ? "selected" : ""; ?>" data-color="danger"></div>
                            <div class="tiles nav-select info <?php echo ($navbar == NAV_INFO) ? "selected" : ""; ?>" data-color="info"></div>
                            <div class="tiles nav-select dark <?php echo ($navbar == NAV_DARK) ? "selected" : ""; ?>" data-color="dark"></div>
                            <div class="tiles nav-select default <?php echo ($navbar == NAV_DEFAULT) ? "selected" : ""; ?>" data-color="default"></div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url("skpd/Dashboard"); ?>">
                            <i class="ti-home menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#data_tunggal" aria-expanded="false" aria-controls="data_tunggal">
                            <i class="ti-agenda menu-icon"></i>
                            <span class="menu-title">Data Tunggal</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="data_tunggal">
                            <ul class="nav flex-column sub-menu">
                                <?php if ($group_id == 1) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo base_url("skpd/Jenis_utama"); ?>">Jenis Utama</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo base_url("skpd/Urusan"); ?>">Urusan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo base_url("skpd/Bidang"); ?>">Bidang</a>
                                    </li>
                                <?php } ?>

                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url("skpd/Program"); ?>">Program</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url("skpd/Kegiatan"); ?>">Kegiatan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url("skpd/Data"); ?>">Data</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url("skpd/Komposit"); ?>">
                            <i class="ti-wallet menu-icon"></i>
                            <span class="menu-title">Komposit</span>
                        </a>
                    </li>

                    <?php if ($group_id == 1) { ?>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                                <i class="ti-settings menu-icon"></i>
                                <span class="menu-title">Konfigurasi</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="ui-basic">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo base_url("skpd/Konfigurasi"); ?>">OPD - Bidang</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo base_url("skpd/Konfigurasi/copy_simple"); ?>">Salin Simple</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url("skpd/Dhistory"); ?>">
                                <i class="ti-layers menu-icon"></i>
                                <span class="menu-title">Rekam Data</span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>

            <div class="main-panel">

                <?php
                if (isset($_view) && $_view)
                    $this->load->view($_view);
                ?>

                <!-- <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">&nbsp;</div>
                </footer> -->
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

    <script>
        $(document).ready(function() {
            $(".sidebar-bg-options").click(function() {
                $.ajax({
                    url: "<?php echo base_url("skpd/Dashboard/change_sidebar_color") ?>",
                    type: "post",
                    data: {
                        sidebar_color: $(this).attr("id"),
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });

            $(".nav-select").click(function() {
                $.ajax({
                    url: "<?php echo base_url("skpd/Dashboard/change_navbar_color") ?>",
                    type: "post",
                    data: {
                        navbar_color: $(this).attr("data-color"),
                    },
                    success: function() {
                        location.reload();
                    }
                });
            })
        });
    </script>

    <?php
    if (isset($_plugin) && $_plugin) {
        if (isset($_plugin[PLUG_VALIDATION])) { ?>
            <script>
                function change_jenis_utama(tahun, urusan = 0, bidang = 0, program = 0, kegiatan = 0) {
                    $.ajax({
                        url: "<?php echo base_url("skpd/Jenis_utama/get_data_bytahun") ?>",
                        type: "post",
                        data: {
                            tahun: tahun,
                        },
                        beforeSend: function(x) {
                            $(".loading").show();
                        },
                        success: function(data) {
                            data = JSON.parse(data);

                            var temp_index = 0;
                            var selected;
                            $("#jenis_utama option").remove();
                            data.forEach(function(item) {
                                selected = "";
                                if (temp_index == 0) {
                                    temp_index = item.id;
                                    selected = "selected";
                                }

                                $("#jenis_utama").append("<option value='" + item.id + "' " + selected + ">" + item.nama_jenis_utama + "</option>");
                            });
                            $(".loading").hide();

                            if (urusan > 0) {
                                if (bidang > 0) {
                                    change_urusan(temp_index, 1);
                                    if (program > 0) {
                                        change_urusan(temp_index, 1, 1);
                                        if (kegiatan > 0) {
                                            change_urusan(temp_index, 1, 1, 1);
                                        }
                                    }
                                } else {
                                    change_urusan(temp_index);
                                }
                            }
                        },
                    })
                }

                function change_urusan(id, bidang = 0, program = 0, kegiatan = 0) {
                    $.ajax({
                        url: "<?php echo base_url("skpd/Urusan/get_urusan_byjenis") ?>",
                        type: "post",
                        data: {
                            jenis: id,
                        },
                        beforeSend: function(x) {
                            $(".loading").show();
                        },
                        success: function(data) {
                            data = JSON.parse(data);

                            var temp_index = 0;
                            var selected;
                            $("#urusan option").remove();
                            data.forEach(function(item) {
                                selected = "";
                                if (temp_index == 0) {
                                    temp_index = item.id;
                                    selected = "selected";
                                }

                                $("#urusan").append("<option value='" + item.id + "' " + selected + ">" + item.nm_urusan + "</option>");
                            });
                            $(".loading").hide();

                            if (bidang > 0) {
                                if (program > 0) {
                                    change_bidang(temp_index, 1);
                                    if (kegiatan > 0) {
                                        change_bidang(temp_index, 1, 1);
                                    }
                                } else {
                                    change_bidang(temp_index);
                                }
                            }
                        },
                    });
                }

                function change_bidang(id, program = 0, kegiatan = 0) {
                    $.ajax({
                        url: "<?php echo base_url("skpd/Bidang/get_bidang_byurusan") ?>",
                        type: "post",
                        data: {
                            urusan: id,
                        },
                        beforeSend: function(x) {
                            $(".loading").show();
                        },
                        success: function(data) {
                            data = JSON.parse(data);

                            var temp_index = 0;
                            var selected;
                            $("#bidang option").remove();
                            data.forEach(function(item) {
                                selected = "";
                                if (temp_index == 0) {
                                    temp_index = item.id;
                                    selected = "selected";
                                }

                                $("#bidang").append("<option value='" + item.id + "' " + selected + ">" + item.nm_bidang + "</option>");
                            });
                            $(".loading").hide();

                            if (program > 0) {
                                change_program(temp_index);
                                if (kegiatan > 0) {
                                    change_program(temp_index, 1);
                                }
                            }
                        },
                    });
                }

                function change_program(id, kegiatan = 0) {
                    $.ajax({
                        url: "<?php echo base_url("skpd/Program/get_program_bybidang") ?>",
                        type: "post",
                        data: {
                            bidang: id,
                        },
                        beforeSend: function(x) {
                            $(".loading").show();
                        },
                        success: function(data) {
                            data = JSON.parse(data);

                            var temp_index = 0;
                            var selected;
                            $("#program option").remove();
                            data.forEach(function(item) {
                                selected = "";
                                if (temp_index == 0) {
                                    temp_index = item.id;
                                    selected = "selected";
                                }

                                $("#program").append("<option value='" + item.id + "' " + selected + ">" + item.nm_program + "</option>");
                            });
                            $(".loading").hide();

                            if (kegiatan > 0) {
                                change_kegiatan(temp_index, 1);
                            }
                        },
                    });
                }

                function change_kegiatan(id) {
                    $.ajax({
                        url: "<?php echo base_url("skpd/Kegiatan/get_kegiatan_byprogram") ?>",
                        type: "post",
                        data: {
                            program: id,
                        },
                        beforeSend: function(x) {
                            $(".loading").show();
                        },
                        success: function(data) {
                            data = JSON.parse(data);

                            var temp_index = 0;
                            var selected;
                            $("#kegiatan option").remove();
                            data.forEach(function(item) {
                                selected = "";
                                if (temp_index == 0) {
                                    temp_index = item.id;
                                    selected = "selected";
                                }

                                $("#kegiatan").append("<option value='" + item.id + "' " + selected + ">" + item.nama + "</option>");
                            });
                            $(".loading").hide();
                        },
                    });
                }

                function validation_text(input, message) {
                    $("#err_" + input).html("(" + message + ")");
                    $("#err_" + input).show();

                    if ($("#" + input).val() != "") {
                        $("#err_" + input).hide();
                    }
                }

                function validation_check(input) {
                    var count = 0;

                    input.forEach(function(item) {
                        if (!$("#" + item).val()) {
                            count++;
                        }
                    });

                    return count;
                }
            </script>
        <?php }

        if (isset($_plugin[PLUG_DATEPICKER])) { ?>
            <script src="<?php echo base_url("assets/skpd"); ?>/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <?php }

        if (isset($_plugin[PLUG_NOTIFICATION])) { ?>
            <script src="<?php echo base_url("assets/skpd"); ?>/vendors/jquery-toast-plugin/jquery.toast.min.js"></script>
            <script src="<?php echo base_url("assets/skpd"); ?>/js/toastDemo.js"></script>
            <!-- <script src="<?php echo base_url("assets/skpd"); ?>/js/desktop-notification.js"></script> -->
        <?php }
        if (isset($_plugin[PLUG_DATATABLE])) { ?>
            <script src="<?php echo base_url("assets/skpd"); ?>/vendors/datatables.net/jquery.dataTables.js"></script>
            <script src="<?php echo base_url("assets/skpd"); ?>/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
            <!-- <script src="<?php echo base_url("assets/skpd"); ?>/js/data-table.js"></script> -->
        <?php }

        if (isset($_plugin[PLUG_SELECT2])) { ?>
            <script src="<?php echo base_url("assets/skpd"); ?>/vendors/select2/select2.min.js"></script>
        <?php }

        if (isset($_plugin[PLUG_UPLOAD])) { ?>
            <script src="<?php echo base_url("assets/skpd"); ?>/js/file-upload.js"></script>
    <?php }
    }

    if (isset($_js) && $_js) $this->load->view($_js);
    ?>

</body>

</html>