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
                                            <th>Data Uraian</th>
                                            <th>Satuan</th>
                                            <th>Nilai</th>
                                            <th style="width: 90px !important;">Tanggal Update</th>
                                            <th>OPD</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($history as $key => $val) { ?>
                                            <tr class="selected-opd" data-id="<?php echo $val["id"]; ?>" data-dataid="<?php echo $val["id_data"] ?>" data-nama="<?php echo $val["data_uraian"]; ?>">
                                                <td class="text-center"><?php echo $i; ?></td>
                                                <td><?php echo $val["uraian"]; ?></td>
                                                <td class="text-center"><?php echo $val["satuan"]; ?></td>
                                                <td><?php echo $val["nilai"]; ?></td>
                                                <td class="text-center"><?php echo date("d/m/Y", strtotime($val["created_date"])); ?></td>
                                                <td class="text-center"><?php echo $val["name"]; ?></td>
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
                <div class="row">
                    <div class="col-lg-2">
                        <label>Uraian Data</label>
                    </div>
                    <div class="col-lg-1">
                        <label>:</label>
                    </div>
                    <div class="col-lg-9">
                        <label id="uraian"></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <label>Satuan</label>
                    </div>
                    <div class="col-lg-1">
                        <label>:</label>
                    </div>
                    <div class="col-lg-9">
                        <label id="satuan"></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <label>Nilai</label>
                    </div>
                    <div class="col-lg-1">
                        <label>:</label>
                    </div>
                    <div class="col-lg-9">
                        <label id="nilai"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>