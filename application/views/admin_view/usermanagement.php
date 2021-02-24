<div class="row" id="modalsub">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title tabbable-line">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-blue-madison bold uppercase">User Management</span>
                </div>
                <ul class="nav nav-tabs">
                    <li ng-class="{ 'active': active == 0||active ==undefined }">
                        <a ng-click="active = 0">Users</a>
                    </li>
                    <li ng-class="{ 'active': active == 1}">
                        <a ng-click="active = 1">Groups</a>
                    </li>
                </ul>
            </div>
            <div class="portlet-body">
                <div class="tab-content">
                    <!-- PERSONAL INFO TAB -->
                    <div class="tab-pane active" ng-if="active == 0||active ==undefined ">
                        <div class="row">
                            <div class="clearfix col-lg-12 text-center">
                                <button class="btn blue btn-sm pull-right" style="width: 150px;" ng-click="tambahuser()">Tambah User <i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                       <div class="portlet-body">
                            <div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 table-responsive">
                                        <table class="table-bordered table-hover table">
                                            <thead>
                                                <tr class="text-center">
                                                    <td>Nama</td>
                                                    <td>Username</td>
                                                    <td>Group</td>
                                                    <td>Email</td>
                                                    <td>Telepon</td>
                                                    <td width="200px" rowspan="2">Aksi</td>
                                                </tr>
                                                <tr>
                                                    <td><input type="text" class="form-control" ng-model="search.first_name"></td>
                                                    <td><input type="text" class="form-control" ng-model="search.username"></td>
                                                    <td><input type="text" class="form-control" ng-model="search.group_name"></td>
                                                    <td><input type="text" class="form-control" ng-model="search.email"></td>
                                                    <td><input type="text" class="form-control" ng-model="search.phone"> <input type="hidden" value="0" ng-model="search.api"></td>
                                                   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="user in users | filter:search">
                                                    <td>{{user.first_name}}</td>
                                                    <td>{{user.username}} </td>
                                                    <td>{{user.group_name}}</td>
                                                    <td>{{user.email}}</td>
                                                    <td>{{user.phone}}</td>
                                                    <td>
                                                        <a ng-click="edit_user(user)" class="btn btn-default font-blue">
                                                            <i class="icon-bubbles"></i> Edit</a>
                                                        <a class="btn btn-default font-red" ng-click="hps_user(user,$index)">
                                                            <i class="icon-emoticon-smile"></i> Hapus</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                   
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PERSONAL INFO TAB -->
                    <!-- CHANGE 2 -->
                    <div class="tab-pane active" ng-if="active == 1">
                        <div class="row fx-grid"> 
                            <table aria-readonly="true" class="fx-grid-full col-md-12">
                            <thead data-link="">
                                <tr>
                                    <th >
                                        <a>
                                            <span class="fx-grid-headerlabel">Name</span>
                                            
                                        </a>
                                    </th>
                                    <th >
                                        <a>
                                            <span class="fx-grid-headerlabel">Deskripsi</span>
                                           
                                        </a>
                                    </th>
                                    <th >
                                        <a>
                                            <span class="fx-grid-headerlabel">Anggota</span>
                                            
                                        </a>
                                        
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="fx-grid-groupdata" aria-expanded="true">
                                 <tr ng-repeat="(key,group) in usergroups track by $index">
                                    <td class="main">
                                        <a style="color: inherit;display: inline-block;margin-right: -37px; width: 100%;" ng-click="edit_group(group)" class="even">
                                            <span>{{group.name}}</span>
                                        </a>
                                        <a ng-click="hps_group(group,key)" class="btn btn-icon-only red btn-outline hapus" uib-tooltip="Hapus" tooltip-is-open="tooltipIsOpen" tooltip-placement="right">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </td>
                                    <td class="text text ">
                                        {{group.description}}
                                    </td>
                                    <td class="text text ">
                                       <span ng-repeat="user in users | anggotagroup:group.id">
                                          <strong> {{user}} <span ng-if=""></span>, </strong>
                                       </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="clearfix col-lg-12 text-center">
                            <button class="btn blue btn-sm pull-right" style="width: 150px;" ng-click="tambah_group()">Tambah Group <i class="fa fa-plus"></i></button>
                        </div>

                        </div>
                    </div>
                    <!-- END CHANGE 2 -->
                    
                </div>
            </div>
        </div>
    </div>
</div>


<!-- modal add new user -->
<script type="text/ng-template" id="add_user.html">
    <div class="modal-header">
        <h3 class="modal-title" id="modal-title">Form User</h3>
    </div>
    <div class="modal-body form" id="modal-body">
        <form class="form-horizontal form-bordered form-label-stripped" name="form_user" >
            <div class="form-body">
                <div class="form-group" ng-class="{'has-error' : form_user.group.$touched && form_user.group.$invalid}">
                    <label class="control-label col-md-3" >First Name</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="First Name" class="form-control" 
                                name="first" 
                                ng-model="user.first_name"
                                required=""
                                tabindex="1">
                        <span class="help-block" ng-show="form_user.first.$touched && form_user.first.$invalid"> Wajib di isi</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Last Name</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="Last Name" class="form-control" ng-model="user.last_name" tabindex="2">
                    </div>
                </div>
                <div class="form-group" ng-class="{'has-error' : form_user.username.$touched && form_user.username.$invalid}">
                    <label class="control-label col-md-3">Username</label>
                    <div class="col-md-9">
                         <input type="text" placeholder="Username" class="form-control" 
                                name="username" 
                                ng-model="user.username"
                                ng-change="user.change=true"
                                ng-pattern="/^[a-zA-Z0-9]*$/"
                                ng-minlength="5"
                                required=""
                                tabindex="3">
                        <span class="help-block" ng-show="form_user.username.$error.minlength && form_user.username.$touched"> Minimal terdiri dari 5 karakter</span>
                        <span class="help-block" ng-show="form_user.username.$error.required && form_user.username.$touched"> Field ini wajib di isi</span>
                        <span class="help-block" ng-show="form_user.username.$error.pattern && form_user.username.$touched">Karakter khusus tidak di perbolehkan</span>
                         <span class="help-block" ng-hide="username==warning">Username telah di gunakan</span>
                    </div>
                </div>
                <div class="form-group" ng-if="user.status != 'edit'">
                    <label class="control-label col-md-3">Password</label>
                    <div class="col-md-9">
                         <input type="password" placeholder="Password" 
                                 name="password" 
                                 class="form-control" 
                                 ng-model="user.password"
                                 ng-minlength="5"
                                 required="" 
                                 tabindex="4">
                        <span class="help-block" ng-show="form_user.password.$touched && form_user.password.$invalid"> Minimal terdiri dari 5 karakter</span>

                    </div>
                </div>
                <div class="form-group" ng-class="{'has-error' : form_user.group.$touched && form_user.group.$invalid}">
                    <label class="control-label col-md-3">Group</label>
                    <div class="col-md-9">
                        <select name="group" class="form-control" 
                              ui-select2="select2Options"
                              ng-model="user.group" 
                              ng-options="group.name for group in groups track by group.id"
                              required=""
                              tabindex="5">  
                        </select>
                        <span class="help-block" ng-show="form_user.group.$touched && form_user.group.$invalid">Wajib di isi</span>

                    </div>
                </div>
                <div class="form-group" ng-class="{'has-error' : form_user.email.$touched && form_user.email.$invalid}">
                    <label class="control-label col-md-3">Email</label>
                    <div class="col-md-9">
                         <input type="text" placeholder="Email" name="email" class="form-control" ng-model="user.email" tabindex="6">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">SKPD</label>
                    <div class="col-md-9">
                         <input type="text" placeholder="SKPD" class="form-control" ng-model="user.company" tabindex="7">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Telephone</label>
                    <div class="col-md-9">
                         <input type="text" placeholder="No Telephone" class="form-control" ng-model="user.phone" tabindex="8">
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                    <span class="help-block" style="color: #e73d4a;" ng-show="error"> Terdapat Kesalahan Pengisian. Mohon Periksa kembali</span>
                        <button type="button" class="btn green" ng-if="!user.status" ng-click="add_user()" tabindex="8">
                            <i class="fa fa-check"></i> Submit</button>
                        <button type="button" class="btn green" ng-if="user.status" ng-click="update()" tabindex="8">
                            <i class="fa fa-check" ></i> Update</button>
                        <button type="button" class="btn green pull-right" ng-if="user.status" ng-click="reset()">
                            <i class="fa fa-check" ></i> Reset Password </button>
                        <button type="button" class="btn default" ng-click="cancel()" ng-if="username==warning" tabindex="9">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
   
</script>


<!-- modal add new user -->
<script type="text/ng-template" id="add_group.html">
    <div class="modal-header">
        <h3 class="modal-title" id="modal-title">Form Group</h3>
    </div>
    <div class="modal-body form" id="modal-body">
        <form class="form-horizontal form-bordered form-label-stripped" name="form_user" >
            <div class="form-body">
                <div class="form-group" ng-class="{'has-error' : form_user.group.$touched && form_user.group.$invalid}">
                    <label class="control-label col-md-3">Nama Group</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="Nama Group" class="form-control" 
                                name="first" 
                                ng-model="group.name"
                                required=""
                                tabindex="1">
                        <span class="help-block" ng-show="form_user.first.$touched && form_user.first.$invalid"> Wajib di isi</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Deskripsi</label>
                    <div class="col-md-9">
                    <textarea rows="4" cols="50" ng-model="group.description" class="form-control" tabindex="2">

                    </textarea>
                    </div>
                </div>
                
                
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="button" class="btn green" ng-if="!group.status" ng-click="tmb_group()" tabindex="3">
                            <i class="fa fa-check"></i> Submit</button>
                        <button type="button" class="btn green" ng-if="group.status" ng-click="up_group()">
                            <i class="fa fa-check"></i>Update</button>
                     
                        <button type="button" class="btn default" ng-click="cancel()" tabindex="4">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
   
</script>