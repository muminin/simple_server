<div class="row" id="modalsub">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title tabbable-line">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-blue-madison bold uppercase">Api Key</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="tab-content">
                    <!-- PERSONAL INFO TAB -->
                    <div class="tab-pane active">
                        <div class="row">
                            <div class="clearfix col-lg-12 text-center">
                                <button class="btn blue btn-sm pull-right" style="width: 150px;" ng-click="tambah_api()">Tambah Api <i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                <div class="row">
                                    <div class="col-md-4" style="margin-bottom: 10px" ng-repeat="user in users ">
                                        <!--begin: widget 1-1 -->
                                        <div class="mt-widget-1" >
                                           <div class="mt-img">
                                                <img ng-src="{{user.img}}" > 
                                            </div>
                                            <div class="mt-body">
                                                <h3 class="mt-username ng-binding">{{user.first_name}} {{user.last_name}}</h3>
                                                <p class="mt-user-title ng-binding" style="text-align: left;">Group : {{user.group_name}}</p>
                                                <p class="mt-user-title ng-binding" style="text-align: left;">Api : {{user.key}}</p>
                                                 <p class="mt-user-title ng-binding" style="text-align: left;">IP : {{user.ip}}</p>
                                                <div class="mt-stats">
                                                    <div class="btn-group btn-group btn-group-justified">
                                                        <a style="cursor: not-allowed;" class="btn font-blue">
                                                            <i class="icon-bubbles"></i>Edit</a>
                                                       
                                                        <a class="btn font-red" ng-click="hps_user(user,$index)">
                                                            <i class="icon-emoticon-smile"></i>Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end: widget 1-1 -->
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PERSONAL INFO TAB -->
                </div>
            </div>
        </div>
    </div>
</div>



<!-- modal add new user -->
<script type="text/ng-template" id="add_api.html">
    <div class="modal-header">
        <h3 class="modal-title" id="modal-title">Tambah Api</h3>
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
                
                <div class="form-group">
                    <label class="control-label col-md-3">Api-Key</label>
                    <div class="col-md-6">
                         <input type="text" placeholder="Api-Key" class="form-control" ng-model="user.key" tabindex="8" disabled="true">
                    </div>
                     <div class="col-md-3">
                         <button type="button" class="btn btn-blue" ng-click="random(25)">Random</button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Alamat-Ip</label>
                    <div class="col-md-9">
                         <input type="text" placeholder="Alamat Ip" class="form-control" ng-model="user.ip" tabindex="9">
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