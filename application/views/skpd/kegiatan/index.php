<?php
$group_id = $this->session->userdata("group");
?>

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
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-info btn-sm rounded-0 text-white" data-toggle="modal" data-target="#modalImport">
                                Impor Kegiatan dari Excel
                            </button>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-12">

                            <table id="tab_program" class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th class="row-order">#</th>
                                        <th class="row-order">Tahun</th>
                                        <th>Jenis Utama</th>
                                        <th>Urusan</th>
                                        <th>Bidang</th>
                                        <th>Program</th>
                                        <th>Kode Rekening</th>
                                        <th>Kegiatan</th>
                                        <th class="row-aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if (!empty($kegiatan)) {
                                        foreach ($kegiatan as $key => $val) { ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i; ?></td>
                                                <td class="text-center"><?php echo $val["jenis_tahun"]; ?></td>
                                                <td><?php echo $val["jenis_nama"]; ?></td>
                                                <td><?php echo $val["urusan_nama"]; ?></td>
                                                <td><?php echo $val["bidang_nama"]; ?></td>
                                                <td><?php echo $val["program_nama"]; ?></td>
                                                <td><?php echo $val["urusan_kode"] . " . " . $val["bidang_kode"] . " . " . $val["program_kode"] . " . " . $val["kode"]; ?></td>
                                                <td class="break-word">
                                                    <?php echo $val["nama"]; ?>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <?php if ($group_id == 1) { ?>
                                                            <button type="button" class="btn btn-warning btn-sm edit" data-id="<?php echo $val["id"]; ?>"><i class="ti-pencil"></i></button>
                                                            <button type="button" class="btn btn-danger btn-sm delete" data-id="<?php echo $val["id"]; ?>"><i class="ti-trash"></i></button>
                                                            <button type="button" class="btn btn-info btn-sm mapping-data" data-id="<?php echo $val["id"]; ?>"><i class="ti-reload"></i></button>
                                                        <?php } ?>

                                                        <button type="button" class="btn btn-primary btn-sm add-data" data-id="<?php echo $val["id"]; ?>"><i class="ti-plus"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php $i++;
                                        }
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

<div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel-3">Import Nilai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding-bottom: 10px; padding-top: 25px;">
                <div class="row">
                    <div class="col-12">
                        <form id="import_form" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <button type="button" class="btn btn-info btn-sm rounded-0 text-white" id="import_btn">
                                    Download Template Kegiatan
                                </button>
                            </div>
                            <hr>

                            <input type="hidden" name="tahun" id="import_tahun">

                            <div class="form-group">
                                <input type="file" name="nilai" class="file-upload-default" accept=".xlsx">
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info form-control-sm" disabled="" placeholder="Upload File">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary btn-sm" type="button">Upload</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-sm rounded-0 text-white" id="upload_data">Upload</button>
                <button type="button" class="btn btn-default btn-sm rounded-0" style="border: 1px solid #c9ccd7;" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>