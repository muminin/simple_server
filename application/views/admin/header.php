<!--BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="<?php echo base_url('admin') ?>">
                        <img src="<?php echo base_url()?>assets/images/Logo3.png" alt="Pusat Data" height="20px" class="logo-default" style="    margin-top: 24px;" /> </a>
                    <div class="menu-toggler sidebar-toggler">
                        <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                    </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse" ng-click="isCollapsed = !isCollapsed"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN PAGE ACTIONS -->
                <!-- DOC: Remove "hide" class to enable the page header actions -->
               
                <!-- END PAGE ACTIONS -->
                <!-- BEGIN PAGE TOP -->
                <div class="page-top">
                    <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
                    
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                           
                            <li class="dropdown dropdown-user" uib-dropdown>
                                <a href="javascript:;" class="dropdown-toggle" uib-dropdown-toggle>
                                    <span class="username username-hide-on-mobile">{{profile.first_name}} </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default" uib-dropdown-menu>
                                    <li>
                                        <a href="<?php echo base_url() ?>admin#!/user/profile/">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>
                                   
                                    <li>
                                        <a href="<?php echo base_url('admin'); ?>#!/inputdata/skpd/">
                                            <i class="icon-rocket"></i>Input Data
                                            <span class="badge badge-success"> {{groups[profile.group_id].jmlh}}  </span>
                                        </a>
                                    </li>
                                    <li class="divider"> </li>
                                    
                                    <li>
                                        <a href="<?php echo base_url('/login/logout') ?>">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <!-- <li class="dropdown dropdown-extended quick-sidebar-toggler">
                                <span class="sr-only">Toggle Quick Sidebar</span>
                                <i class="icon-logout"></i>
                            </li> -->
                            <!-- END QUICK SIDEBAR TOGGLER -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END PAGE TOP -->
            </div>
            <!-- END HEADER INNER