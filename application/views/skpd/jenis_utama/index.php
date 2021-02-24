<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-5 mb-4 mb-xl-0">
                    <h4 class="font-weight-normal mb-0"><?php echo $title; ?></h4>
                </div>
                <div class="col-12 col-xl-7">
                    <div class="mb-3 mb-xl-0 pull-right">
                        <a href="<?php echo base_url("skpd/Jenis_utama/tambah") ?>" class="btn btn-primary btn-sm rounded-0 text-white">Tambah</a>
                    </div>
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
                                <table id="tab_jenis" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="row-order">#</th>
                                            <th class="row-order">Tahun</th>
                                            <th>Nama</th>
                                            <th class="row-aksi">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($jenis_utama as $key => $val) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i; ?></td>
                                                <td class="text-center"><?php echo $val["tahun"]; ?></td>
                                                <td><?php echo $val["nama_jenis_utama"]; ?></td>
                                                <td>
                                                    <div class="text-center">
                                                        <button type="button" class="btn btn-warning btn-sm edit" data-id="<?php echo $val["id"]; ?>"><i class="ti-pencil"></i></button>
                                                        <button type="button" class="btn btn-danger btn-sm delete" data-id="<?php echo $val["id"]; ?>"><i class="ti-trash"></i></button>
                                                        <button type="button" class="btn btn-primary btn-sm add-urusan" data-id="<?php echo $val["id"]; ?>"><i class="ti-plus"></i></button>
                                                    </div>
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