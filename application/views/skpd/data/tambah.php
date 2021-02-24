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
                    <form class="cmxform" id="data">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tahun">
                                        Tahun <span class="text-youtube">*</span>
                                    </label>
                                    <select class="form-control form-control-sm" id="tahun" name="tahun">
                                        <?php
                                        for ($i = -2; $i <= 2; $i++) {
                                            $value = date("Y") + $i;
                                            $selected = "";
                                            if ($value == $tahun) {
                                                $selected = "selected";
                                            }

                                            echo "<option value='$value' $selected>$value</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Jenis Utama <span class="text-youtube">*</span>
                                    </label>
                                    <select class="form-control form-control-sm" id="jenis_utama" name="jenis_utama">
                                        <?php foreach ($jenis_utama as $key => $val) {
                                            echo "<option value='" . $val["id"] . "'>" . $val["nama_jenis_utama"] . "</option>";
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Urusan <span class="text-youtube">* <small id="err_urusan"></small></span>
                                    </label>
                                    <select class="form-control form-control-sm" id="urusan" name="urusan"></select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Bidang <span class="text-youtube">* <small id="err_bidang"></small></span>
                                    </label>
                                    <select class="form-control form-control-sm" id="bidang" name="bidang"></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kode">
                                        Level Data <span class="text-youtube">* <small id="err_level"></small></span>
                                    </label>
                                    <select class="form-control form-control-sm" id="level" name="level">
                                        <option value="0">Pilih Level Data</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="data_list" style="display: none;">
                            <div class="row">
                                <div class="col-lg-6">&nbsp;</div>
                                <div class="col-lg-6">
                                    <div class="pull-right">
                                        <button class="btn btn-primary btn-sm">SIMPAN</button>
                                    </div>
                                </div>
                            </div>
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
                                        <table id="tab_data" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Satuan</th>
                                                    <th>Uraian</th>
                                                    <th>Kecamatan</th>
                                                    <th>Nilai</th>
                                                    <th class="row-aksi">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
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