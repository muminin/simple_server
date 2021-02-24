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
                        <input type="hidden" name="id" id="id" value="<?php echo $data["id"]; ?>">
                        <input type="hidden" name="id_program" id="id_program" value="<?php echo $data["id_program"]; ?>">

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
                                            if ($value == $data["tahun"]) {
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
                                        Jenis Utama <span class="text-youtube">* <small id="err_jenis_utama"></small></span>
                                    </label>
                                    <select class="form-control form-control-sm" id="jenis_utama" name="jenis_utama">
                                        <?php foreach ($jenis_utama as $key => $val) {
                                            $selected = "";
                                            if ($value == $data["id_jenis_utama"]) {
                                                $selected = "selected";
                                            }

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
                                    <label>
                                        Program <span class="text-youtube">* <small id="err_program"></small></span>
                                    </label>
                                    <select class="form-control form-control-sm" id="program" name="program"></select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kode">
                                        Uraian <span class="text-youtube">* <small id="err_uraian"></small></span>
                                    </label>
                                    <input type="text" id="uraian" class="form-control form-control-sm" name="uraian" value="<?php echo $data["uraian"] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama">
                                        Satuan
                                    </label>
                                    <input type="text" id="satuan" class="form-control form-control-sm" name="satuan" value="<?php echo $data["satuan"] ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama">
                                        Nilai
                                    </label>
                                    <input type="text" id="nilai" class="form-control form-control-sm" name="nilai" value="<?php echo $data["nilai"] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <input class="btn btn-primary btn-sm" type="submit" value="Simpan">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>