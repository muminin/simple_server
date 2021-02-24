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
                <div class="col-12 col-xl-5 mb-4 mb-xl-0">
                    <h4 class="font-weight-normal mb-0"><?php echo $title; ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row grid-margin">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form class="cmxform" id="tambah">
                        <input type="hidden" name="tahun" id="tahun" value="<?php echo $data["jenis_tahun"]; ?>">

                        <input type="hidden" name="id_parent" id="id_parent" value="<?php echo $data["id"]; ?>">
                        <input type="hidden" name="kegiatan" id="kegiatan" value="<?php echo $data["kegiatan_id"]; ?>">
                        <input type="hidden" name="level" id="level" value="<?php echo $data["level"]; ?>">
                        <input type="hidden" name="uniq" id="uniq" value="<?php echo $data["uniq"]; ?>">

                        <div class="row">
                            <div class="col-lg-12">
                                <table>
                                    <tr>
                                        <td style="width: 150px;">Tahun</td>
                                        <td>:</td>
                                        <td><?php echo $data["jenis_tahun"]; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Utama</td>
                                        <td>:</td>
                                        <td><?php echo $data["jenis_nama"]; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Urusan</td>
                                        <td>:</td>
                                        <td><?php echo $data["urusan_kode"] . ". " . $data["urusan_nama"]; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Bidang</td>
                                        <td>:</td>
                                        <td><?php echo $data["bidang_kode"] . ". " . $data["bidang_nama"]; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Program</td>
                                        <td>:</td>
                                        <td><?php echo $data["program_kode"] . ". " . $data["program_nama"]; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Kegiatan</td>
                                        <td>:</td>
                                        <td><?php echo $data["kegiatan_kode"] . ". " . $data["kegiatan_nama"]; ?></td>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group pull-right" style="margin-bottom: 0px !important;">
                                    <input class="btn btn-primary btn-sm" type="submit" value="Simpan">
                                </div>
                            </div>
                        </div>

                        <div id="data_list">
                            <hr>

                            <div class="row">
                                <div class="col-lg-6">&nbsp;</div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="number" class="form-control form-control-sm" id="count_data" placeholder="Isi Baris Data" min="1">
                                            <span class="input-group-addon input-group-append" id="tambah_data">
                                                <span class="ti-plus input-group-text" style=""></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="tab_data" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="row-aksi">#</th>
                                                    <th>Nama Data</th>
                                                    <th>Satuan</th>
                                                    <th>Nilai</th>
                                                    <th class="row-aksi">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                foreach ($existed as $key => $val) { ?>
                                                    <tr>
                                                        <td style="text-align: center;"><?php echo $i; ?></td>
                                                        <td><?php echo $val["uraian"]; ?></td>
                                                        <td><?php echo $val["satuan"]; ?></td>
                                                        <td colspan="2"><?php echo $val["nilai"]; ?></td>
                                                    </tr>
                                                <?php
                                                    $i++;
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>