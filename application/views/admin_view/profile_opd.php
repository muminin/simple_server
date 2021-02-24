<div class="col-md-12">
    <div class="portlet light">
        <div class="portlet-title tabbable-line">
            <div class="caption caption-md">
                <i class="icon-globe theme-font hide"></i>
                <span class="caption-subject font-blue-madison bold uppercase">Profile OPD</span>
            </div>
            
        </div>
        <div class="portlet-body">
           
            <form role="form" action="#">
                <input type="hidden" name="id" ng-model="profile.id" >
                <div class="form-group">
                    <label class="control-label">OPD</label>
                    <input type="text" placeholder="Nama OPD (Kepanjangan) " class="form-control" ng-model="profile_opd.description" /> </div>
                <div class="form-group">
                    <label class="control-label">Alamat</label>
                    <input type="text" placeholder="Nama OPD (Kepanjangan) " class="form-control" ng-model="profile_opd.alamat" /> </div>

                <div class="form-group">
                    <label class="control-label">Kode Pos</label>
                    <input type="text" placeholder="Kode Pos" class="form-control" ng-model="profile_opd.kode_pos" /> </div>

                <div class="form-group">
                    <label class="control-label">No. Telepon</label>
                    <div class="input-group">
                        <span class="input-group-addon">(0321)</span>
                        <input type="text" class="form-control" placeholder="Nomor Telepon" ng-model="profile_opd.telepon">
                    </div>

                <div class="form-group">
                    <label class="control-label">Fax</label>
                    <input type="text" placeholder="Fax" class="form-control" ng-model="profile_opd.fax" /> </div>

                <div class="form-group">
                    <label class="control-label">Alamat Website</label>
                    <input type="text" placeholder="Website" class="form-control" ng-model="profile_opd.web" /> </div>

                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" placeholder="Email" class="form-control" ng-model="profile_opd.email" /> </div>

                <div class="form-group">
                    <label class="control-label">Nama Kepala OPD</label>
                    <input type="text" placeholder="Nama Kepala OPD" class="form-control"  ng-model="profile_opd.kepala_opd"/> </div>
                <div class="form-group">
                    <label class="control-label">NIP Kepala OPD</label>
                    <input type="text" placeholder="Nama SKPD" class="form-control" ng-model="profile_opd.nip" /> </div>
                
                <div class="margiv-top-10">
                    <a ng-click="save_opd(profile_opd)" class="btn green-haze">Simpan</a>
                </div>
            </form>
        </div>
        
      
    </div>
</div>
