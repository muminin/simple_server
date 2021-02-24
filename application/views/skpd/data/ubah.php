<?php
$disabled = "";
$readonly = "";
$group_id = $this->session->userdata("group");
if ($group_id != 1) {
    $disabled = "disabled";
    $readonly = "readonly";
}
?>

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
                        <input type="hidden" name="uniq" id="uniq" value="<?php echo $data["uniq"]; ?>">

                        <input type="hidden" name="last_jenis" id="last_jenis" value="<?php echo $data["jenis_id"]; ?>">
                        <input type="hidden" name="last_urusan" id="last_urusan" value="<?php echo $data["urusan_id"]; ?>">
                        <input type="hidden" name="last_bidang" id="last_bidang" value="<?php echo $data["bidang_id"]; ?>">
                        <input type="hidden" name="last_program" id="last_program" value="<?php echo $data["program_id"]; ?>">
                        <input type="hidden" name="last_kegiatan" id="last_kegiatan" value="<?php echo $data["kegiatan_id"]; ?>">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tahun">
                                        Tahun <span class="text-youtube">*</span>
                                    </label>
                                    <select class="form-control form-control-sm" id="tahun" name="tahun" <?php echo $disabled; ?>>
                                        <?php foreach ($tahun_list as $val) {
                                            $tahun = $val["tahun"];
                                            $selected = "";
                                            if ($tahun == $data["jenis_tahun"]) {
                                                $selected = "selected";
                                            }

                                            echo "<option value='$tahun' $selected>$tahun</option>";
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Jenis Utama <span class="text-youtube">* <small id="err_jenis_utama"></small></span>
                                    </label>
                                    <select class="form-control form-control-sm" id="jenis_utama" name="jenis_utama" <?php echo $disabled; ?>>
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
                                    <select class="form-control form-control-sm" id="urusan" name="urusan" <?php echo $disabled; ?>></select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Bidang <span class="text-youtube">* <small id="err_bidang"></small></span>
                                    </label>
                                    <select class="form-control form-control-sm" id="bidang" name="bidang" <?php echo $disabled; ?>></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Program <span class="text-youtube">* <small id="err_program"></small></span>
                                    </label>
                                    <select class="form-control form-control-sm" id="program" name="program" <?php echo $disabled; ?>></select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Kegiatan <span class="text-youtube">* <small id="err_kegiatan"></small></span>
                                    </label>
                                    <select class="form-control form-control-sm" id="kegiatan" name="kegiatan" <?php echo $disabled; ?>></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kode">
                                        Uraian <span class="text-youtube">* <small id="err_uraian"></small></span>
                                    </label>
                                    <input type="text" id="uraian" class="form-control form-control-sm" name="uraian" value="<?php echo $data["uraian"] ?>" <?php echo $readonly; ?>>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama">
                                        Satuan
                                    </label>
                                    <input type="text" id="satuan" class="form-control form-control-sm" name="satuan" value="<?php echo $data["satuan"] ?>" <?php echo $readonly; ?>>
                                </div>
                            </div>
                        </div>

                        <div class="row">
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