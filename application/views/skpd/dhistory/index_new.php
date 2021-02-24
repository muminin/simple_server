<style>
    /* table.dataTable tbody tr:hover {
        font-weight: 600;
        background-color: #248afd !important;
        cursor: pointer;
        color: #ffffff;
    } */
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
                                            <th>Program</th>
                                            <th style="width: 90px !important;">Tanggal Update</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($new_history as $key => $val) { ?>
                                            <tr class="selected-opd" data-id="<?php echo $val["id"]; ?>" data-dataid="<?php echo $val["id_data"] ?>" data-nama="<?php echo $val["data_uraian"]; ?>">
                                                <td class="text-center"><?php echo $i; ?></td>
                                                <td><?php echo $val["program_nama"]; ?></td>
                                                <td class="text-center"><?php echo date("d/m/Y", strtotime($val["created_date"])); ?></td>
                                                <td class="text-center">
                                                    <div class="text-center">
                                                        <button type="button" class="btn btn-dark btn-sm print" data-program="<?php echo $val["program_id"]; ?>" style="color: #ffffff;">
                                                            <i class="ti-printer"></i>
                                                        </button>
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