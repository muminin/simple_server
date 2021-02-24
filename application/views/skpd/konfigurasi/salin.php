<style>
    .dataTables_wrapper .dataTable .btn {
        padding: 0.3rem 0.5rem !important;
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
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tab_jenis" class="table table-bordered hover">
                                    <thead>
                                        <tr>
                                            <th class="row-order">#</th>
                                            <th class="row-order">Tahun</th>
                                            <th>Jenis Utama</th>
                                            <th class="row-aksi">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($jenis as $key => $val) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i; ?></td>
                                                <td class="text-center"><?php echo $val["tahun"]; ?></td>
                                                <td><?php echo $val["nama_jenis_utama"]; ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary salin-jenis" data-id="<?php echo $val["id"]; ?>" data-tahun="<?php echo $val["tahun"]; ?>" data-jenis="<?php echo $val["nama_jenis_utama"]; ?>">
                                                        Salin
                                                    </button>
                                                </td>
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
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="cmxform" id="tambah">
                    <input type="hidden" name="jenis" id="jenis">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="tahun_copy">Salin ke Tahun <span class="text-youtube">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="tahun_copy" name="tahun_copy" minlength="4" maxlength="4">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="simpan">Simpan</button>
            </div>
        </div>
    </div>
</div>