<style>
    th,
    td {
        background: #fff;
    }

    .table-responsive {
        overflow: auto;
        max-height: 500px;
    }

    .table-responsive thead th {
        position: sticky;
        top: 0;
    }

    .input-group-text {
        padding: 0.7rem 0.75rem !important;
        background-color: #248afd !important;
        color: #ffffff !important;
        border-color: #248afd !important;
    }

    .row-aksi {
        width: 72px;
    }

    table,
    th,
    td {
        background: #ffffff;
        padding: 5px;
    }

    table {
        background: #999999;
        border-spacing: 15px;
    }
</style>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="font-weight-normal mb-0"><?php echo $title; ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row grid-margin">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table>
                                <tr>
                                    <td style="width: 150px;">Tahun</td>
                                    <td>:</td>
                                    <td><?php echo $kegiatan["jenis_tahun"]; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Jenis Utama</td>
                                    <td>:</td>
                                    <td><?php echo $kegiatan["jenis_nama"]; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Urusan</td>
                                    <td>:</td>
                                    <td><?php echo $kegiatan["urusan_kode"] . ". " . $kegiatan["urusan_nama"]; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Bidang</td>
                                    <td>:</td>
                                    <td><?php echo $kegiatan["bidang_kode"] . ". " . $kegiatan["bidang_nama"]; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Program</td>
                                    <td>:</td>
                                    <td><?php echo $kegiatan["program_kode"] . ". " . $kegiatan["program_nama"]; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Kegiatan</td>
                                    <td>:</td>
                                    <td><?php echo $kegiatan["kode"] . ". " . $kegiatan["nama"]; ?></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group pull-right" style="margin-bottom: 0px !important;">
                                <button class="btn btn-primary btn-sm" id="btn_simpan">Simpan</button>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="">
                                <table id="tab_data" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 20px !important;">Aksi</th>
                                            <th>Elemen Data</th>
                                            <th style="width: 30px !important;">Satuan</th>
                                            <th>Nilai</th>
                                            <th class="break-word" style="width: 40px !important;">Berada dalam kegiatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data as $key => $val) {
                                            $chekced = "";
                                            if (in_array($val["id"], $selected_data)) {
                                                $chekced = "checked";
                                            } ?>

                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-check form-check-flat form-check-primary" style="margin: 0px !important">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input select-data" data-id="<?php echo $val["id"]; ?>" <?php echo $chekced; ?>>
                                                            Pilih <?php // echo $val["id"]; ?>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="text-center break-word"><?php echo $val["uraian"]; ?></td>
                                                <td class="text-center break-word"><?php echo $val["satuan"]; ?></td>
                                                <td class="text-center break-word"><?php echo $val["nilai"]; ?></td>
                                                <td class="text-center break-word">
                                                    <?php if (!empty($val["kegiatan_check"])) {
                                                        echo '<div class="badge badge-warning badge-pill view-kegiatan" data-kegiatan="' . $val["kegiatan_check"] . '" style="cursor: pointer;">Iya</div>';
                                                    } else {
                                                        echo '<div class="badge badge-success badge-pill">Tidak</div>';
                                                    } ?>
                                                </td>
                                            </tr>

                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <form class="cmxform" id="tambah">
                        <input type="hidden" name="id" id="id" value="<?php echo $kegiatan["id"]; ?>">

                        <input type="hidden" name="jenis" value="<?php echo $kegiatan["jenis_id"]; ?>">
                        <input type="hidden" name="urusan" value="<?php echo $kegiatan["urusan_id"]; ?>">
                        <input type="hidden" name="bidang" value="<?php echo $kegiatan["bidang_id"]; ?>">
                        <input type="hidden" name="program" value="<?php echo $kegiatan["program_id"]; ?>">
                        <input type="hidden" name="kegiatan" value="<?php echo $kegiatan["id"]; ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="modal_viewLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 10px; padding-top: 10px;">
                <h5 class="modal-title" id="exampleModalLabel-3">Nama Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding-bottom: 20px; padding-top: 20px;">
                <div class="row">
                    <div class="col-12">
                        <h4 class="modal-title" id="nama_kegiatan"></h4>
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm rounded-0" style="border: 1px solid #c9ccd7;" data-dismiss="modal">Tutup</button>
            </div> -->
        </div>
    </div>
</div>