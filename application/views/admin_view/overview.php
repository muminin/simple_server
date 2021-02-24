<div class="col-md-12">
    <!-- BEGIN PORTLET -->
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption caption-md">
                <i class="icon-bar-chart theme-font hide"></i>
                <span class="caption-subject font-blue-madison bold uppercase">Your Activity</span>
                <span class="caption-helper hide">weekly stats...</span>
            </div>
            
        </div>
        <div class="portlet-body">
            <div class="row number-stats margin-bottom-30">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <div class=" text-center">
                       
                        <div class="stat-number text-center">
                            <div class="title"> Jumlah Element </div>
                            <div class="number"> {{groups[profile.group_id].jmlh}} </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <div class=" text-center">
                        
                        <div class="stat-number">
                            <div class="title"> Keterisian Data Tahun <?php echo date('Y'); ?></div>
                            <div class="number"> {{groups[profile.group_id].terisi}} </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-4">
                     <div class=" text-center">
                        <div class="stat-number">
                            <div class="title"> Update Bulan ini </div>
                            <div class="number"> {{groups[profile.group_id].updated}} </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-scrollable table-scrollable-borderless">
                <table class="table table-hover table-light">
                    <thead>
                        <tr class="uppercase">
                            
                            <th> Jenis Data </th>
                            <th> Jumlah Data </th>
                            <th> Keterisian Data % </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="(k_activity, v_activity) in activity">
                            <td> {{k_activity}} </td>
                            <td> {{v_activity.jumlah}} </td>
                            <td><span class="bold theme-font">{{(v_activity.terisi/v_activity.jumlah)*100}}%</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END PORTLET -->
</div>