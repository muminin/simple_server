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
                    <form class="cmxform" id="tambah">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tahun">
                                        Tahun <span class="text-youtube">*</span>
                                    </label>
                                    <select class="form-control form-control-sm" id="tahun" name="tahun">
                                        <option value="">-- Pilih Tahun</option>
                                        <?php foreach ($tahun as $val) {
                                            echo "<option value='" . $val["tahun"] . "'>" . $val["tahun"] . "</option>";
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="bidang">
                                        Bidang <span class="text-youtube">*</span>
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
                                    <label for="nama">
                                        Nama <span class="text-youtube">* <small id="err_nama"></small></span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" id="nama" name="nama" placeholder="Nama">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="satuan">
                                        Satuan <span class="text-youtube">* <small id="err_satuan"></small></span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm" id="satuan" name="satuan" placeholder="Satuan">
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="data"> Data Rumus </label>
                                    <select class="form-control form-control-sm" id="data" name="data">
                                        <option value="">-- Pilih Data</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="in_rumus">
                                        Inputan Rumus </span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-dark" type="button" id="in_tambah">
                                                <i class="ti-plus w800"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" placeholder="Inputan Rumus" name="in_rumus" id="in_rumus">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group" style="margin-bottom: 1rem;">
                                    <label for="data">Simbol</label>
                                    <br>

                                    <button class="btn btn-primary btn-sm math" data-view="+" data-math="+">
                                        <i class="ti-plus w800"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm math" data-view="-" data-math="-">
                                        <i class="ti-minus w800"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm math" data-view="x" data-math="*">
                                        <i class="ti-close w800"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm math" data-view="/" data-math="/">
                                        <i class="ti-Italic w800"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm math" data-view="del" data-math="del">
                                        <i class="ti-arrow-left w800"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="data">
                                        Rumus <span class="text-youtube">*</span>
                                        <br>

                                        <label id="rumus_show"></label>
                                        <textarea class="form-control form-control-sm" name="rumus" id="rumus" rows="2" style="display: none;"></textarea>
                                    </label>
                                </div>
                            </div>
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