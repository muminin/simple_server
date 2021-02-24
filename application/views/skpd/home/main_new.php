<style>
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .content-wrapper {
        padding-top: 0px !important;
    }

    .fixed-here {
        position: fixed;
        right: 1.8%;
        top: 11.19%;
        z-index: 1999;
    }

    .btn-sm {
        padding: 7px !important;
        color: #ffffff !important;
    }

    .strong {
        font-weight: 700;
    }
</style>

<?php
$user_id = $this->session->userdata("user_id");
$username = $this->session->userdata("username");
?>

<div class="content-wrapper">
    <div class="row" style="margin-bottom: 50px;">
        <div class="col-md-12">
            <div class="card fixed-here">
                <div style="padding: 10px;">
                    <?php if (!empty($user_id)) { ?>
                        <span>Logged in as : <strong><?php echo $username; ?></strong></span>&nbsp;
                        <a href="<?php echo base_url('/logout'); ?>" class="btn btn-sm btn-danger">LOGOUT</a>
                    <?php } else { ?>
                        <a href="<?php echo base_url('admin/login'); ?>" class="btn btn-sm btn-primary">LOGIN</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row grid-margin">
        <div class="col-lg-12">
            <div class="card" style="margin-top: 10px;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-11">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-primary back-landing">
                                    <li class="breadcrumb-item active" id="l_jenis" data-id=""><a href="#" id="a_jenis">JENIS</a></li>
                                    <li class="breadcrumb-item hide" id="l_urusan" data-id=""><a href="#" id="a_urusan">URUSAN</a></li>
                                    <li class="breadcrumb-item hide" id="l_bidang" data-id=""><a href="#" id="a_bidang">BIDANG</a></li>
                                    <li class="breadcrumb-item hide" id="l_program" data-id=""><a href="#" id="a_program">PROGRAM</a></li>
                                    <li class="breadcrumb-item hide" id="l_kegiatan" data-id=""><a href="#" id="a_kegiatan">KEGIATAN</a></li>
                                    <li class="breadcrumb-item hide" id="l_data" data-id=""><a href="#" id="a_data">DATA</a></li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-1">
                            <select class="form-control form-control-sm" id="tahun" name="tahun">
                                <?php
                                for ($i = -4; $i <= 2; $i++) {
                                    $value = date("Y") + $i;
                                    $selected = "";
                                    if ($value == date("Y")) {
                                        $selected = "selected";
                                    }

                                    echo "<option value='$value' $selected>$value</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tab_landing" class="table table-bordered">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>