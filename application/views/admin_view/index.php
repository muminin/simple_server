<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="note note-success">
            <h3>Update log 13-07-2018</h3>
            <p>
                <ul>
                    <li>Tambah fitur download laporan </li>
                    <li>Tambahan Pada profil data OPD</li>

                </ul>
            </p>
        </div>
    </div>

</div>

<div class="row widget-row">
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
            <h4 class="widget-thumb-heading">Keterisian Data Tahun ini</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-green icon-bulb"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-subtitle">Persen</span>
                    <span class="widget-thumb-body-stat">{{(groups[profile.group_id].terisi/groups[profile.group_id].jmlh)*100 | number:2}}%</span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
            <h4 class="widget-thumb-heading">Jumlah Elemen</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-red icon-layers"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-subtitle">Elemen</span>
                    <span class="widget-thumb-body-stat">{{groups[profile.group_id].jmlh}} </span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
            <h4 class="widget-thumb-heading">Nama SKPD</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-purple icon-screen-desktop"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-subtitle">&nbsp;</span>
                    <span class="widget-thumb-body-stat" >{{groups[profile.group_id].name}} </span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
            <h4 class="widget-thumb-heading">Update Data Bulan ini</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-blue icon-bar-chart"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-subtitle">Data</span>
                    <span class="widget-thumb-body-stat">{{groups[profile.group_id].updated}}</span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-xs-12 col-sm-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Grafik Keterisian Data</span>
                </div>
                <div class="actions">
                    <label> Tahun :</label>
                    <select name="thn1"
                                  ng-model="grfk.tahun"
                                  ng-options="tahun for (k_thn,tahun) in tahuns track by tahun"
                                  ng-change="gantithn()">
                    </select>

                </div>
            </div>
            <div class="portlet-body" style="height: 1210px">
                <div id="dashboard_amchart_1" class="CSSAnimationChart">
                    <div class="graph">
                    <div class="graph-export">
                        <a ng-href="<?php echo base_url(); ?>/export/grafik_excel/{{grfk.tahun}}" target="_blank" ><i class="fa fa-download fa-3" aria-hidden="true" style="font-size: 2.3em;"></i></a>
                    </div>
                    </div>
                    <span my-grafik3="grfk.tahun" ></span>

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-xs-12 col-sm-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption ">
                    <span class="caption-subject font-dark bold uppercase">History</span>
                </div>
                <div class="actions">

                </div>
            </div>
            <div class="portlet-body" style="overflow-y: auto;max-height: 550px;">
                 <ul class="feeds">
                    <li ng-repeat="log in logs  track by $index">
                        <div class="col1">
                            <div class="cont">
                                <div class="cont-col1">
                                    <div class="label label-sm label-info">
                                        <i class="fa fa-bullhorn"></i>
                                    </div>
                                </div>
                                <div class="cont-col2">
                                    <div class="desc"><b>{{log.skpd}}</b> Telah Memperbarui Jenis Data :  {{log.kat}} ( {{log.tahun}} )

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col2">
                            <div class="date"> {{log.update}} </div>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>