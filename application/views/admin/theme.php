<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html ng-app="admin">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <head>
        <meta charset="utf-8" />
        <title>Dasboard PLAN CENTER | Kota Mojokerto</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="author" />
        <?php echo $core_css ?>
        <?php echo $js ?>

        <link rel="shortcut icon" href="favicon.ico" /> 
        <script type="text/javascript">
            var thn_data = <?php echo date('Y'); ?>;
            var id_bulan= new Array("Januari","Pebruari","Maret","April","Mei","Juni","Juli","Agustus","September", "Oktober","Nopember","Desember");
            var bulan =id_bulan[(<?php echo date('n'); ?>-1)];
            var tahun = function () {
            var d = new Date();
            var tahun = d.getFullYear();
            var tahuns = new Array;
            for (var i = 2010; i <= thn_data; i++) {
                    tahuns[i] = i;
                }
                return tahuns;
            }
        </script>

        <script type="text/javascript">
            var url = '<?php echo base_url() ?>';
        </script>
    </head>
    <!-- END HEAD -->
   
    <body class="page-sidebar-closed-hide-logo page-container-bg-solid" ng-controller="main"  ng-class="{ loaded: loading}" >
        <div class="saving" ng-show="saving==true" style="position: fixed; height: 100%;width: 100%;z-index: 5000;margin: auto;background-color: rgba(204, 204, 204, 0.50); display: none; ">
            <div class="loading-saving" style="text-align: center;top: 40%;right: 0px;left: 0px;    position: absolute;">
                <div style="background-color: WHITE; width: 50%;left: 0px;right: 0px;position: absolute;margin: auto;    border: 1px solid #aaa;box-shadow: rgba(0,0,0,0.2) 0 4px 16px 0px;">   
                    <img src="<?php echo base_url('assets/loading/loading-bars.svg') ?>" style="padding-top: 20px;" />
                     <h3 class="mt2 mb0" style="padding-bottom: 20px;">Sedang menyimpan...</h3>
                </div>
                
            </div>
        </div>
       
        <!-- loading page -->
       <div id="loader-wrapper">
            <div id="loader"></div>        
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>

        
        <!-- //loading page -->
        <!-- BEGIN HEADER -->   
        <div class="page-header navbar navbar-static-top">
            <?php echo $header ?>
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- END SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse" uib-collapse="isCollapsed">
                    <!-- BEGIN SIDEBAR MENU -->
                     <!-- <div class="form-loading" ng-if="menus==null" style="min-height: 700px; margin: auto; padding-top: 73px;top: 700px;"> <img src="<?php echo base_url('/assets/images/form-loading.svg') ?>"> </div> -->
                    <?php echo $leftnav ?>                   
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <h1 class="page-title"> 

                    PLAN CENTER
                        <small></small>
                    </h1>
                    <div class="page-bar">
                        <ul class="page-breadcrumb" ng-if="breadcumb==null">
                            <li>
                                <i class="icon-home"></i>
                                <a href="#!">Home</a>
                               
                            </li>
                            <li >
                                 <i class="fa fa-angle-right"></i>
                                <span>{{breadcumb.lvl[0].Name}}</span>
                            </li>
                        </ul>
                        <ul class="page-breadcrumb" ng-if="breadcumb.lvl.length==1">
                            <li>
                                <i class="icon-home"></i>
                                <a href="#!">Home</a>
                               
                            </li>
                            <li >
                                 <i class="fa fa-angle-right"></i>
                                <span>{{breadcumb.lvl[0].Name}}</span>
                            </li>
                        </ul>

                         <ul class="page-breadcrumb" ng-if="breadcumb.lvl.length>1">
                            <li>
                                <i class="icon-home"></i>
                                <a href="#!">Home</a>
                               
                            </li>
                            <li ng-repeat="(k_bread, v_bred) in breadcumb.lvl">
                                 <i class="fa fa-angle-right"></i>
                                <span>{{v_bred.Name}}</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE HEADER-->

                    <div  class="animate" ng-view >
                        
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->
            <a href="javascript:;" class="page-quick-sidebar-toggler">
                <i class="icon-login"></i>
            </a>
            <div class="page-quick-sidebar-wrapper" data-close-on-body-click="false">
                <div class="page-quick-sidebar">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="javascript:;" data-target="#quick_sidebar_tab_1" data-toggle="tab"> Users
                                <span class="badge badge-danger">2</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_2" data-toggle="tab"> Alerts
                                <span class="badge badge-success">7</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> More
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                        <i class="icon-bell"></i> Alerts </a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                        <i class="icon-info"></i> Notifications </a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                        <i class="icon-speech"></i> Activities </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                        <i class="icon-settings"></i> Settings </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                   
                </div>
            </div>
            <!-- END QUICK SIDEBAR -->
        </div>
        <!-- END CONTAINER -->
        


<script type="text/javascript">

</script>

</body>

</html>