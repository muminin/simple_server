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
                        <input type="hidden" name="id_program" id="id_program" value="<?php echo $data["id_program"]; ?>">
                        <input type="hidden" name="id_parent" id="id_parent" value="<?php echo $data["id"]; ?>">
                        <input type="hidden" name="level" id="level" value="<?php echo $data["level"]; ?>">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group pull-right">
                                    <input class="btn btn-primary btn-sm" type="submit" value="Simpan">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tahun">Tahun</label>
                                    <input type="text" class="form-control form-control-sm" name="tahun" id="tahun" value="<?php echo $data["tahun"]; ?>" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="jenis">Jenis Utama</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="jenis" id="jenis" value="<?php echo $data["nama_jenis_utama"]; ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kode">Kode Urusan</label>
                                    <input type="text" class="form-control form-control-sm" name="kd_urusan" id="kd_urusan" value="<?php echo $data["kd_urusan"]; ?>" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="program">Nama Urusan</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="nm_urusan" id="nm_urusan" value="<?php echo $data["nm_urusan"]; ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kode">Kode Bidang</label>
                                    <input type="text" class="form-control form-control-sm" name="kd_bidang" id="kd_bidang" value="<?php echo $data["kd_bidang"]; ?>" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="program">Nama Bidang</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="nm_bidang" id="nm_bidang" value="<?php echo $data["nm_bidang"]; ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kode">Kode Program</label>
                                    <input type="text" class="form-control form-control-sm" name="kd_program" id="kd_program" value="<?php echo $data["kd_program"]; ?>" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="program">Nama Program</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="nm_program" id="nm_program" value="<?php echo $data["nm_program"]; ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kode">Uraian</label>
                                    <input type="text" class="form-control form-control-sm" name="uraian" id="uraian" value="<?php echo $data["uraian"]; ?>" readonly>
                                </div>
                            </div>
                            <!-- <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="program">Nama Program</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="nm_program" id="nm_program" value="<?php echo $data["nm_program"]; ?>" readonly>
                                </div>
                            </div> -->
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