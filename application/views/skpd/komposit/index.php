<?php
$group_id = $this->session->userdata("group");
?>

<style>
    .row-aksi {
        width: 34px !important;
    }

    .modal .modal-dialog .modal-content .modal-header {
        padding: 17px 25px;
    }

    .modal .modal-dialog .modal-content .modal-body {
        padding: 25px 25px;
    }

    .form-group {
        margin-bottom: 1rem !important;
    }
</style>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-5 mb-4 mb-xl-0">
                    <h4 class="font-weight-normal mb-0"><?php echo $title; ?></h4>
                </div>
                <div class="col-12 col-xl-7">
                    <div class="row">
                        <div class="col-7">&nbsp;</div>
                        <div class="col-2">
                            <select class="form-control form-control-sm " id="tahun" name="tahun">
                                <?php foreach ($tahun as $val) {
                                    $tahun = $val["tahun"];
                                    $selected = "";
                                    if ($tahun == $tahun_selected) {
                                        $selected = "selected";
                                    }

                                    echo "<option value='$tahun' $selected>$tahun</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <a class="btn btn-primary btn-sm rounded-0 text-white" id="btn_filter">Filter Data</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row grid-margin">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-12">
                            <a class="btn btn-primary btn-sm text-white" id="btn_add" style="cursor: pointer;">Tambah Komposit</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tab_program" class="table table-bordered" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="row-order">#</th>
                                            <th class="row-order">Tahun</th>
                                            <th>Jenis Utama</th>
                                            <th>Urusan</th>
                                            <th>Bidang</th>
                                            <th>Nama Komposit</th>
                                            <th class="row-aksi">Satuan</th>
                                            <th>Nilai</th>
                                            <th class="row-aksi">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        if (!empty($komposit)) {
                                            foreach ($komposit as $key => $val) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $i; ?></td>
                                                    <td class="text-center"><?php echo $val["jenis_tahun"]; ?></td>
                                                    <td><?php echo $val["jenis_nama"]; ?></td>
                                                    <td><?php echo $val["urusan_nama"]; ?></td>
                                                    <td><?php echo $val["bidang_nama"]; ?></td>
                                                    <td class="break-word"><?php echo $val["uraian"]; ?></td>
                                                    <td class="text-center"><?php echo $val["satuan"]; ?></td>
                                                    <td class="text-right">
                                                        <?php
                                                        $nilai = $val["nilai"];
                                                        if (!preg_match("/[a-z]/i", $nilai) && !empty($nilai)) {
                                                            $nilai = strval($nilai);
                                                            $check = substr($nilai, -3);

                                                            $check2 = substr($nilai, 0, -3);
                                                            $check2 = str_replace(".", "", $check2);
                                                            if (strpos($check, '.00') !== false) {
                                                                $nilai = $check2;
                                                            } elseif (strpos($check, '.') !== false) {
                                                                $nilai = $check2 . $check;
                                                            } else {
                                                                $nilai = str_replace(".", "", $nilai);
                                                            }

                                                            $nilai = (float) $nilai;
                                                            $nilai = number_format($nilai, 2);
                                                        }

                                                        echo $nilai; ?>
                                                    </td>

                                                    <td>
                                                        <div class="text-center">
                                                            <?php if ($group_id != 1) { ?>
                                                                <button type="button" class="btn btn-success btn-sm salin-jenis" data-id="<?php echo $val["id"]; ?>" data-nama="<?php echo $val["uraian"]; ?>">
                                                                    <i class="ti-eye"></i>
                                                                </button>
                                                            <?php } ?>

                                                            <?php if ($group_id == 1) { ?>
                                                                <button type="button" class="btn btn-warning btn-sm edit" data-id="<?php echo $val["id"]; ?>" data-tahun="<?php echo $val["jenis_tahun"]; ?>"><i class="ti-pencil"></i></button>
                                                                <button type="button" class="btn btn-danger btn-sm delete" data-id="<?php echo $val["id"]; ?>"><i class="ti-trash"></i></button>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php $i++;
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="nilai_mod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="cmxform" id="nilai_form">
                    <input type="hidden" name="komposit" id="komposit">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <label for="tahun">
                                    Nilai <span class="text-youtube">* <small id="err_nilai"></small></span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="nilai" name="nilai" placeholder="Isi Nilai di sini">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="simpan_nilai">Simpan</button>
            </div>
        </div>
    </div>
</div>