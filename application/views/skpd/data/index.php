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
                        <div class="col-3">
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
                            &nbsp;
                        </div>
                        <div class="col-7">
                            <select class="form-control form-control-sm " id="filter" name="filter">
                                <option value="0">Semua Kegiatan</option>
                                <?php
                                if (!empty($kegiatan)) {
                                    foreach ($kegiatan as $val) {
                                        $selected = "";
                                        if (isset($_GET["kegiatan"]) && ($val["id"] == $_GET["kegiatan"])) {
                                            $selected = "selected";
                                        }

                                        echo "<option value='" . $val["id"] . "' " . $selected . ">" . $val["nama"] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-2">
                            <a class="btn btn-primary btn-sm btn-block rounded-0 text-white" id="btn_filter">Filter Data</a>
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
                                Impor Nilai dari Excel
                            </button>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tab_program" class="table table-bordered" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="row-order text-center">#</th>
                                            <th class="row-order text-center">Tahun</th>
                                            <th>Jenis Utama</th>
                                            <th>Urusan</th>
                                            <th>Bidang</th>
                                            <th>Program</th>
                                            <th>Kegiatan</th>

                                            <?php if ($level > 1) {
                                                for ($i = 1; $i < $level; $i++) {
                                                    echo "<th>Data</th>";
                                                }

                                                echo "<th>Data</th>";
                                            } else {
                                                echo "<th>Data</th>";
                                            } ?>

                                            <th class="row-aksi">Satuan</th>
                                            <th class="row-aksi">Nilai</th>
                                            <th class="row-aksi">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $icount = 1;
                                        if (!empty($data)) {
                                            foreach ($data as $key => $val) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $icount; ?></td>
                                                    <td class="text-center"><?php echo $val["jenis_tahun"]; ?></td>
                                                    <td class="text-center"><?php echo $val["jenis_nama"]; ?></td>

                                                    <td class="text-center">
                                                        <a href="<?php echo base_url("skpd/Data?tahun=" . $val["jenis_tahun"] . "&urusan=" . $val["urusan_id"]); ?>">
                                                            <?php echo $val["urusan_nama"]; ?>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?php echo base_url("skpd/Data?tahun=" . $val["jenis_tahun"] . "&bidang=" . $val["bidang_id"]); ?>">
                                                            <?php echo $val["bidang_nama"]; ?>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?php echo base_url("skpd/Data?tahun=" . $val["jenis_tahun"] . "&program=" . $val["program_id"]); ?>">
                                                            <?php echo $val["program_nama"]; ?>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?php echo base_url("skpd/Data?tahun=" . $val["jenis_tahun"] . "&kegiatan=" . $val["kegiatan_id"]); ?>">
                                                            <?php echo $val["kegiatan_nama"]; ?>
                                                        </a>
                                                    </td>

                                                    <?php if ($level > 1) {
                                                        for ($i = 1; $i < $level; $i++) {
                                                            $url = "";
                                                            if ($i == 1) {
                                                                $url = base_url("skpd/Data?tahun=" . $val["jenis_tahun"] . "&program=" . $val["program_id"]);
                                                            } else {
                                                                $url = base_url($val["parent_url_" . $i]);
                                                            } ?>

                                                            <td>
                                                                <a href="<?php echo $url; ?>">
                                                                    <?php echo $val["parent_uraian_" . $i] . " (" . $val["parent_total_data_" . $i] . ")"; ?>
                                                                </a>
                                                            </td>

                                                        <?php } ?>

                                                        <td class="break-word">
                                                            <?php if ($val["total_data"] != 0) { ?>
                                                                <a href="<?php echo base_url("skpd/Data?tahun=" . $val["jenis_tahun"] . "&id_parent=" . $val["id"] . "&level=" . ($val["level"] + 1)); ?>">
                                                                    <?php echo $val["uraian"] . " (" . $val["total_data"] . ")"; ?>
                                                                </a>
                                                            <?php } else { ?>
                                                                <?php echo $val["uraian"] . " (" . $val["total_data"] . ")"; ?>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center"><?php echo !empty($val["satuan"]) ? $val["satuan"] : "-"; ?></td>
                                                        <td class="text-right">
                                                            <?php
                                                            $nilai = ($val["nilai_sum"] != "" && empty($val["nilai"])) ? $val["nilai_sum"] : $val["nilai"];
                                                            if (!preg_match("/[a-z]/i", $nilai) && !empty($nilai)) {
                                                                $nilai = strval($nilai);
                                                                $check = substr($nilai, -3);

                                                                $check2 = substr($nilai, 0, -3);
                                                                $check2 = str_replace(".", "", $check2);
                                                                if (strpos($check, '.00') !== false) {
                                                                    $nilai = $check2;
                                                                } elseif (strpos($check, '.') !== false) {
                                                                    $nilai = $check2 . $check;
                                                                } else {
                                                                    $nilai = str_replace(".", "", $nilai);
                                                                }

                                                                $nilai = (float) $nilai;
                                                                $nilai = number_format($nilai, 2);
                                                            }

                                                            echo $nilai;
                                                            ?>
                                                        </td>

                                                    <?php } else { ?>

                                                        <td class="break-word">
                                                            <?php if ($val["total_data"] != 0) { ?>
                                                                <a href="<?php echo base_url("skpd/Data?tahun=" . $val["jenis_tahun"] . "&id_parent=" . $val["id"] . "&level=" . ($val["level"] + 1)); ?>">
                                                                    <?php echo $val["uraian"] . " (" . $val["total_data"] . ")"; ?>
                                                                </a>
                                                            <?php } else { ?>
                                                                <?php echo $val["uraian"] . " (" . $val["total_data"] . ")"; ?>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center"><?php echo !empty($val["satuan"]) ? $val["satuan"] : "-"; ?></td>
                                                        <td class="text-right">
                                                            <?php
                                                            // $nilai = ($val["nilai_sum"] != "") ? $val["nilai_sum"] : $val["nilai"];
                                                            $nilai = ($val["nilai_sum"] != "" && empty($val["nilai"])) ? $val["nilai_sum"] : $val["nilai"];
                                                            if (!preg_match("/[a-z]/i", $nilai) && !empty($nilai)) {
                                                                $nilai = strval($nilai);
                                                                $check = substr($nilai, -3);

                                                                $check2 = substr($nilai, 0, -3);
                                                                $check2 = str_replace(".", "", $check2);
                                                                if (strpos($check, '.00') !== false) {
                                                                    $nilai = $check2;
                                                                } elseif (strpos($check, '.') !== false) {
                                                                    $nilai = $check2 . $check;
                                                                } else {
                                                                    $nilai = str_replace(".", "", $nilai);
                                                                }

                                                                $nilai = (float) $nilai;
                                                                $nilai = number_format($nilai, 2);
                                                            }

                                                            echo $nilai;
                                                            ?>
                                                        </td>

                                                    <?php } ?>

                                                    <td>
                                                        <div class="text-center">
                                                            <?php if ($group_id == 1) { ?>
                                                                <button type="button" class="btn btn-danger btn-sm delete" data-id="<?php echo $val["id"]; ?>"><i class="ti-trash"></i></button>
                                                            <?php } ?>

                                                            <button type="button" class="btn btn-warning btn-sm edit" data-id="<?php echo $val["id"]; ?>" data-level="<?php echo $val["level"]; ?>"><i class="ti-pencil"></i></button>
                                                            <button type="button" class="btn btn-primary btn-sm add-data" data-id="<?php echo $val["id"]; ?>"><i class="ti-plus"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php
                                                $icount++;
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
            <div class="modal-body" style="padding-bottom: 10px;">
                <div class="row">
                    <div class="col-12">
                        <form id="import_form" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <button type="button" class="btn btn-info btn-sm rounded-0 text-white" id="import_btn">
                                    Download Template Nilai
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