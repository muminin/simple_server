<style>
    table.dataTable tbody tr:hover {
        font-weight: 600;
        background-color: #248afd !important;
        cursor: pointer;
        color: #ffffff;
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
                                            <th>OPD</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($group as $key => $val) { ?>
                                            <tr class="selected-opd" data-id="<?php echo $val["id"]; ?>" data-name="<?php echo $val["name"]; ?>" data-desc="<?php echo $val["description"]; ?>">
                                                <td class="text-center"><?php echo $i; ?></td>
                                                <td><?php echo $val["name"]; ?></td>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="cmxform" id="tambah">
                    <input type="hidden" name="group" id="group">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="bidang">Pilih Bidang <span class="text-youtube">*</span></label>
                                <select class="form-control form-control-sm" id="bidang" name="bidang[]" multiple="multiple" style="width: 100% !important;">
                                    <?php
                                    foreach ($bidang as $key => $val) {
                                        echo "<option value='" . $val["id"] . "'>" . $val["nm_bidang"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="simpan_bidang">Simpan</button>
            </div>
        </div>
    </div>
</div>