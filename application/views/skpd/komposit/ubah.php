<?php
$group_id = $this->session->userdata("group");
?>

<style>
    .w800 {
        font-weight: 800;
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
                    <form class="cmxform" id="ubah">
                        <input type="hidden" name="id" value="<?php echo $komposit["id"]; ?>">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tahun">
                                        Tahun <span class="text-youtube">* <small id="err_tahun"></small></span>
                                    </label>
                                    <select class="form-control form-control-sm" id="tahun" name="tahun">
                                        <option value="">-- Pilih Tahun</option>
                                        <?php foreach ($tahun as $val) {
                                            $selected = "";
                                            if ($val["tahun"] == $komposit["jenis_tahun"]) {
                                                $selected = "selected";
                                            }

                                            echo "<option value='" . $val["tahun"] . "' $selected>" . $val["tahun"] . "</option>";
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="bidang">
                                        Bidang <span class="text-youtube">* <small id="err_bidang"></small></span>
                                    </label>
                                    <select class="form-control form-control-sm" id="bidang" name="bidang">
                                        <option value="">-- Pilih Bidang</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="uraian">
                                        Uraian <span class="text-youtube">* <small id="err_uraian"></small></span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" id="uraian" name="uraian" placeholder="Uraian" value="<?php echo $komposit["uraian"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="satuan">
                                        Satuan <span class="text-youtube">* <small id="err_satuan"></small></span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" id="satuan" name="satuan" placeholder="Satuan" value="<?php echo $komposit["satuan"]; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="keterangan">
                                        Keterangan
                                    </label>
                                    <textarea class="form-control" name="keterangan" id="keterangan" rows="3" style="resize: vertical;" placeholder="Keterangan"><?php echo $komposit["keterangan"]; ?></textarea>
                                </div>
                            </div>

                            <?php if ($group_id != 1) { ?>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="nilai">
                                            Nilai <span class="text-youtube">* <small id="err_nilai"></small></span>
                                        </label>
                                        <input type="text" class="form-control form-control-sm" id="nilai" name="nilai" placeholder="Nilai" value="<?php echo $komposit["nilai"]; ?>">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <br>
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="button" class="btn btn-primary btn-sm" id="simpan" name="simpan">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>