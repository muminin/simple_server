<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PROFILE SIDEBAR -->
        <div class="profile-sidebar">
            <!-- PORTLET MAIN -->
            <div class="portlet light profile-sidebar-portlet ">
                <!-- SIDEBAR USERPIC -->
                <div class="profile-userpic">
                    <img ng-src="<?php echo base_url() ?>{{profile.picture}}" class="img-responsive" alt=""> </div>
                <!-- END SIDEBAR USERPIC -->
                <!-- SIDEBAR USER TITLE -->
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name">{{profile.username}} </div>
                    <div class="profile-usertitle-job"> {{profile.group}} </div>
                </div>
                <!-- END SIDEBAR USER TITLE -->
               
                <!-- SIDEBAR MENU -->
                <div class="profile-usermenu">
                    <ul class="nav">
                        <li ng-class="{ 'active': menu_user =='<?php echo base_url('admin_view/overview') ?>' }">
                            <a ng-click="menu_user = '<?php echo base_url('admin_view/overview') ?>'">
                                <i class="icon-home"></i> Overview </a>
                        </li>
                        <li ng-class="{ 'active': menu_user =='<?php echo base_url('admin_view/accountsetting') ?>' }">
                            <a ng-click="menu_user = '<?php echo base_url('admin_view/accountsetting') ?>'">
                                <i class="icon-settings"></i> Account Settings </a>
                        </li>

                         <li ng-class="{ 'active': menu_user =='<?php echo base_url('admin_view/profile_opd') ?>' }">
                            <a ng-click="menu_user = '<?php echo base_url('admin_view/profile_opd') ?>'">
                                <i class="icon-settings"></i>Profile OPD</a>
                        </li>
                        
                    </ul>
                </div>
                <!-- END MENU -->
            </div>
            <!-- END PORTLET MAIN -->
        </div>
        <!-- END BEGIN PROFILE SIDEBAR -->
        <!-- BEGIN PROFILE CONTENT -->
        <div class="profile-content">
            <div class="row">
            <div class="profile" ng-include src="menu_user"></div>
            </div>
        </div>
        <!-- END PROFILE CONTENT -->
    </div>
</div>