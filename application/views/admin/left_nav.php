 <ul class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200"  >
    <li class="nav-item" ng-repeat="menu in menus" ng-class="{'active' : selectedmenu === menu.menu, 'start' : selectedmenu === menu.menu }" ng-click="selectedparent(menu.menu)">
        <a class="nav-link nav-toggle" ng-if="menu.child">
            <i class="{{menu.icons}}"></i>
            <span class="title">{{menu.menu}}</span>
            <!-- <span class="selected"></span> -->
            <span class="arrow left"></span>
        </a>
        <a href="{{menu.url}}" class="nav-link nav-toggle" ng-if="!menu.child">
            <i class="{{menu.icons}}"></i>
            <span class="title">{{menu.menu}}</span>
        </a>
        <ul class="sub-menu" ng-if="menu.child">
            <li class="" ng-repeat=" sub in menu.child">
                <a href="{{sub.url}}" class="nav-link ">
                    <i class="{{sub.icons}}"></i>
                    {{sub.menu}}
                </a>
            </li>
        </ul>
    </li>
</ul>


    <!-- END SIDEBAR MENU -->