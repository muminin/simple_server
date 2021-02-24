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
                        <div class="col-2">
                            <select class="form-control form-control-sm " id="tahun" name="tahun">
                                <?php
                                for ($i = 2015; $i <= date("Y"); $i++) {
                                    $selected = "";
                                    if ($i == $tahun) {
                                        $selected = "selected";
                                    }

                                    echo "<option value='" . $i . "' " . $selected . ">" . $i . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-7">
                            <select class="form-control form-control-sm " id="filter" name="filter">
                                <option value="0">Semua Data</option>
                                <?php
                                foreach ($program as $val) {
                                    $selected = "";
                                    if (isset($_GET["program"]) && ($val["id"] == $_GET["program"])) {
                                        $selected = "selected";
                                    }

                                    echo "<option value='" . $val["id"] . "' " . $selected . ">" . $val["nm_program"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <a class="btn btn-primary btn-sm rounded-0 text-white" id="btn_filter">Filter Data</a>
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
                                <table id="tab_program" class="table table-bordered" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="row-order text-center">#</th>
                                            <th class="row-order text-center">Tahun</th>
                                            <th>Jenis Utama</th>
                                            <th>Urusan</th>
                                            <th>Bidang</th>
                                            <th>Program</th>

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
                                                    <td class="text-center"><?php echo $val["tahun"]; ?></td>
                                                    <td class="text-center"><?php echo $val["nama_jenis_utama"]; ?></td>
                                                    <!-- <td class="text-center"><?php echo $val["nm_urusan"]; ?></td> -->
                                                    <!-- <td class="text-center"><?php echo $val["nm_bidang"]; ?></td> -->

                                                    <td class="text-center">
                                                        <a href="<?php echo base_url("skpd/Data?tahun=" . $val["tahun"] . "&urusan=" . $val["id_urusan"]); ?>">
                                                            <?php echo $val["nm_urusan"]; ?>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?php echo base_url("skpd/Data?tahun=" . $val["tahun"] . "&bidang=" . $val["id_bidang"]); ?>">
                                                            <?php echo $val["nm_bidang"]; ?>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?php echo base_url("skpd/Data?tahun=" . $val["tahun"] . "&program=" . $val["id_program"]); ?>">
                                                            <?php echo $val["nm_program"]; ?>
                                                        </a>
                                                    </td>

                                                    <?php if ($level > 1) {
                                                        for ($i = 1; $i < $level; $i++) {
                                                            $url = "";
                                                            if ($i == 1) {
                                                                $url = base_url("skpd/Data?tahun=" . $val["tahun"] . "&program=" . $val["id_program"]);
                                                            } else {
                                                                $url = base_url($val["parent_url_" . $i]);
                                                            } ?>

                                                            <td>
                                                                <a href="<?php echo $url; ?>">
                                                                    <?php echo $val["parent_uraian_" . $i] . " (" . $val["parent_total_data_" . $i] . ")"; ?>
                                                                </a>
                                                            </td>

                                                        <?php } ?>

                                                        <td>
                                                            <?php if ($val["total_data"] != 0) { ?>
                                                                <a href="<?php echo base_url("skpd/Data?tahun=" . $val["tahun"] . "&id_parent=" . $val["id"] . "&level=" . ($val["level"] + 1)); ?>">
                                                                    <?php echo $val["uraian"] . " (" . $val["total_data"] . ")"; ?>
                                                                </a>
                                                            <?php } else { ?>
                                                                <?php echo $val["uraian"] . " (" . $val["total_data"] . ")"; ?>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center"><?php echo !empty($val["satuan"]) ? $val["satuan"] : "-"; ?></td>
                                                        <td class="text-center"><?php echo !empty($val["nilai"]) ? $val["nilai"] : "-"; ?></td>

                                                    <?php } else { ?>

                                                        <td>
                                                            <?php if ($val["total_data"] != 0) { ?>
                                                                <a href="<?php echo base_url("skpd/Data?tahun=" . $val["tahun"] . "&id_parent=" . $val["id"] . "&level=" . ($val["level"] + 1)); ?>">
                                                                    <?php echo $val["uraian"] . " (" . $val["total_data"] . ")"; ?>
                                                                </a>
                                                            <?php } else { ?>
                                                                <?php echo $val["uraian"] . " (" . $val["total_data"] . ")"; ?>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center"><?php echo !empty($val["satuan"]) ? $val["satuan"] : "-"; ?></td>
                                                        <td class="text-center"><?php echo !empty($val["nilai"]) ? $val["nilai"] : "-"; ?></td>

                                                    <?php } ?>

                                                    <td>
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-warning btn-sm edit" data-id="<?php echo $val["id"]; ?>" data-level="<?php echo $val["level"]; ?>"><i class="ti-pencil"></i></button>
                                                            <?php if ($group_id == 1) { ?>
                                                                <button type="button" class="btn btn-danger btn-sm delete" data-id="<?php echo $val["id"]; ?>"><i class="ti-trash"></i></button>
                                                            <?php } ?>

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