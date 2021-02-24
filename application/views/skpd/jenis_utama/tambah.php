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
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tahun">Tahun <span class="text-youtube">*</span></label>
                                    <select class="form-control form-control-sm" id="tahun" name="tahun" required>
                                        <?php
                                        for ($i = 2018; $i <= date("Y"); $i++) {
                                            $value = $i;
                                            $selected = "";
                                            if ($value == $tahun_sess) {
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
                                    <label for="nama">
                                        Nama <span class="text-youtube">* <small id="err_nama"></small></span>
                                    </label>
                                    <input id="nama" class="form-control form-control-sm" type="nama" name="nama">
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