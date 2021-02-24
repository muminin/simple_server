<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-5 mb-4 mb-xl-0">
                    <h4 class="font-weight-normal mb-0"><?php echo $title; ?></h4>
                </div>
                <div class="col-12 col-xl-7">
                    <div class="row">
                        <div class="col-7">&nbsp;</div>
                        <div class="col-2">
                            <!-- <select class="form-control form-control-sm " id="tahun" name="tahun">
                                <?php
                                for ($i = 2015; $i <= date("Y"); $i++) {
                                    $selected = "";
                                    if ($i == $tahun) {
                                        $selected = "selected";
                                    }

                                    echo "<option value='" . $i . "' " . $selected . ">" . $i . "</option>";
                                }
                                ?>
                            </select> -->
                        </div>
                        <div class="col-3">
                            <!-- <a class="btn btn-primary btn-sm rounded-0 text-white" id="btn_filter">Filter Data</a> -->
                        </div>
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
                                <table id="tab_bidang" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="row-order">#</th>
                                            <th class="row-order">Tahun</th>
                                            <th>Jenis Utama</th>
                                            <th>Urusan</th>
                                            <th class="row-order">Kode Rekening</th>
                                            <th>Bidang</th>
                                            <th class="row-aksi">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($bidang as $key => $val) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i; ?></td>
                                                <td class="text-center"><?php echo $val["jenis_tahun"]; ?></td>
                                                <td><?php echo $val["jenis_nama"]; ?></td>
                                                <td><?php echo $val["urusan_nama"]; ?></td>
                                                <td><?php echo $val["urusan_kode"] . " . " . $val["kd_bidang"]; ?></td>
                                                <td><?php echo $val["nm_bidang"]; ?></td>
                                                <td>
                                                    <div class="text-center">
                                                        <button type="button" class="btn btn-warning btn-sm edit" data-id="<?php echo $val["id"]; ?>"><i class="ti-pencil"></i></button>
                                                        <button type="button" class="btn btn-danger btn-sm delete" data-id="<?php echo $val["id"]; ?>"><i class="ti-trash"></i></button>
                                                        <button type="button" class="btn btn-primary btn-sm add-program" data-id="<?php echo $val["id"]; ?>"><i class="ti-plus"></i></button>
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