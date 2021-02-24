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

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="parent">
                                        Data Sub <span class="text-youtube">*</span>
                                    </label>
                                    <select class="form-control form-control-sm" id="parent" name="parent">
                                        <?php foreach ($parent_list as $key => $val) {
                                            $selected = "";
                                            if ($val["id"] == $data["id_parent"]) {
                                                $selected = "selected";
                                            }

                                            echo "<option value='" . $val["id"] . "' $selected>" . $val["uraian"] . "</option>";
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="uraian">
                                        Uraian <span class="text-youtube">* <small id="err_uraian"></small></span>
                                    </label>
                                    <input type="text" id="uraian" class="form-control form-control-sm" name="uraian" value="<?php echo $data["uraian"] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="satuan">
                                        Satuan
                                    </label>
                                    <input type="text" id="satuan" class="form-control form-control-sm" name="satuan" value="<?php echo $data["satuan"] ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nilai">
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