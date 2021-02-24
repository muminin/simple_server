<div class="col-md-12">
    <div class="portlet light">
        <div class="portlet-title tabbable-line">
            <div class="caption caption-md">
                <i class="icon-globe theme-font hide"></i>
                <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
            </div>
            <ul class="nav nav-tabs">
                <li ng-class="{ 'active': active == 0||active ==undefined }">
                    <a ng-click="active = 0">Informasi Pengguna</a>
                </li>
               <!--  <li ng-class="{ 'active': active == 1}">
                    <a ng-click="active = 1" >Ubah Avatar</a>
                </li> -->
                <li ng-class="{ 'active': active == 2}">
                    <a ng-click="active = 2"  >Ubah Password</a>
                </li>
            </ul>
        </div>
        <div class="portlet-body">
            <div class="tab-content" >
                <!-- PERSONAL INFO TAB -->
                <div class="tab-pane active" ng-if="active == undefined || active == 0"  >
                    <form role="form" action="#">
                        <input type="hidden" name="id" ng-model="profile.id" >
                        <div class="form-group">
                            <label class="control-label">First Name</label>
                            <input type="text" placeholder="First Name" class="form-control" ng-model="profile.first_name" /> </div>
                        <div class="form-group">
                            <label class="control-label">Last Name</label>
                            <input type="text" placeholder="Last Name" class="form-control"  ng-model="profile.last_name"/> </div>
                        <div class="form-group">
                            <label class="control-label">SKPD</label>
                            <input type="text" placeholder="Nama SKPD" class="form-control" ng-model="profile.company" /> </div>
                        <div class="form-group">
                            <label class="control-label">Telephone</label>
                            <input type="text" placeholder="Nomor Telephone" class="form-control" ng-model="profile.phone" /> </div>
                        <div class="form-group">
                            <label class="control-label">NIP</label>
                            <input type="text" placeholder="NIP" class="form-control" ng-model="profile.Nip" /> </div>
                        <div class="margiv-top-10">
                            <a ng-click="save_profile(profile)" class="btn green-haze"> Save Changes </a>
                        </div>
                    </form>
                </div>
                <!-- END PERSONAL INFO TAB -->
                <!-- CHANGE AVATAR TAB -->
                <!-- <div class="tab-pane active" ng-if="active == 1">
                    
                    <form role="form" name="changepicture">
                        <div class="form-group">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    <img ng-src="<?php echo base_url() ?>{{profile.picture}}" alt="" /> </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                <div>
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change </span>
                                        <input type="file" ng-model="Avatar"> </span>
                                    <a href="#" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                </div>
                            </div>
                        </div>
                        <div class="margin-top-10">
                            <a href="#" class="btn green-haze"> Simpan Perubahan </a>
                            <a href="#" class="btn default"> Batal </a>
                        </div>
                    </form>
                </div> -->
                <!-- END CHANGE AVATAR TAB -->
                <!-- CHANGE PASSWORD TAB -->
                <div class="tab-pane active" ng-if="active == 2">
                    <form name="formpass" ng-submit="gantipass(formpass,password)">
                        <div class="form-group" ng-class="{'has-error' : formpass.passold.$touched && formpass.passold.$invalid}">
                            <label class="control-label">Kata Sandi Saat Ini</label>
                            <input type="password" class="form-control" name="passold" ng-model="password.old" required="" /> </div>
                        <div class="form-group" ng-class="{'has-error' : formpass.passnew.$touched && formpass.passnew.$invalid}">
                            <label class="control-label">Password Baru</label>
                            <input type="password" class="form-control" name="passnew" ng-model="password.new" required="" ng-minlength="8"/> </div>
                        <div class="form-group">
                            <label class="control-label" ng-class="{'has-error' : formpass.passverif.$touched && formpass.passverif.$invalid}">Ketik ulang Password Baru</label>
                            <input type="password" class="form-control" name="passverif" ng-model="password.verif" required="" ng-minlength="8" /> </div>
                        <div class="margin-top-10">
                            <button tipe="submit" class="btn green-haze">Ubah Kata Sandi</button>
                        </div>
                    </form>
                </div>
                <!-- END CHANGE PASSWORD TAB -->
            </div>
        </div>
    </div>
</div>
