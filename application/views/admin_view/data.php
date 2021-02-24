<div class="inbox font-blue-sharp">
    <div class="row font-blue-sharp">
        <div class="col-md-12">
            <div class="inbox-body font-blue-sharp">
                <div class="inbox-header">
                    <h1 class="pull-left">Data Informasi Pembangunan Daerah</h1>
                    <form class="form-inline pull-right" action="index.html">
                        <div class="input-group input-medium">
                            <input type="text" class="form-control" placeholder="Password">
                            <span class="input-group-btn">
                                <button type="submit" class="btn green">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="inbox-content">
                    <table class="table table-striped table-advance table-hover">
                        <thead>
                            <tr>
                                <th>
                                </th>
                                <th class="pagination-control  page-bar" colspan="3">
                                    <ul class="page-breadcrumb pull-left" style="padding: 5px 2px;">
                                        <li>
                                            <i class="fa fa-database"></i>
                                            <a href="#!/inputdata/kategori">Data</a>
                                            <i class="fa fa-angle-right"></i>
                                        </li>
                                        <li ng-if="breadcumb1!=0">
                                            <span>{{curent_kat.nama}}</span>
                                        </li>
                                    </ul>
                                    <a class="btn btn-sm blue btn-outline">
                                         Kembali
                                    </a>
                                   
                                </th>
                            </tr>
                        </thead>
                        <tbody >
                            <tr ng-repeat="kategori in listkategori" ng-click="katpilih(kategori)" class="fade-in-down">
                                <td class="inbox-small-cells">
                                    
                                </td>
                                <td class="view-message ">{{kategori.nama}}</td>
                                
                            </tr>
                           

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>