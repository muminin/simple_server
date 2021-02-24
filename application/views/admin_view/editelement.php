<div class="portlet box blue-steel" id="modalsub">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Edit Elemen</div>
        <div class="tools">
            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
            <a  class="reload" ng-click="reload()" title=""> </a>
            <a href="" class="fullscreen" data-original-title="" title=""> </a>
        </div>
    </div>
    <div class="portlet-body">
   
      
      <div class="row" style="padding-bottom: 40px;padding-top: 20px;"> 

        <div class="col-md-12 col-sm-12">
           <!-- pilih kategori -->
          <form action="#" class="form-horizontal">
              <div class="form-body">
                <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label class="control-label col-md-2">Jenis Data</label>
                          <div class="col-md-6 select2-wrapper">
                            <select name="foo" class="form-control" 
                                  ui-select2="select2Options"
                                  ng-model="Pilih_jenis" 
                                  ng-options="Jenis.nama for Jenis in jenisdata track by Jenis.id" 
                                  ng-change="update_jenis()" >  
                            </select>
                          </div>
                          <div class="control-label col-md-4 font-red-thunderbird" style="text-align: left;" ><a ng-click="kelola('Jenis Data',jenisdata,0)"><i class="fa fa-cogs"></i> Kelola Jenis Data</a></div>
                      </div>
                  </div>
                  <!--/span-->
                  <div class="col-md-12">
                      <div class="form-group">
                          <label class="control-label col-md-2">Kategori</label>
                          <div class="col-md-6">
                              <select name="Kategori" class="form-control"
                                      ui-select2="select2Options"
                                      ng-model="Pilih_kat"
                                      ng-options="kat.nama for kat in kategori track by kat.id"
                                      ng-change="update_kat()"
                                      >
                                  
                              </select>
                          </div>
                      <div class="control-label col-md-4 font-red-thunderbird" style="text-align: left;" ng-if="Pilih_jenis" ><a ng-click="kelola('Kategori',kategori,Pilih_jenis.id)"><i class="fa fa-cogs"></i> Kelola Kategori</div></a>
                      </div>
                  </div>
                  <!--/span-->
                  <div class="col-md-12">
                      <div class="form-group">
                          <label class="control-label col-md-2"></label>
                          <div class="col-md-4" style="display: flex;">
                              <input type="text" class="form-control" placeholder="Cari Element" ng-model="cari">
                              <span class="input-group-btn">
                                  <button type="submit" class="btn green">
                                      <i class="fa fa-search"></i>
                                  </button>
                              </span>
                          </div>
                          <div class="control-label col-md-4 font-red-thunderbird" style="text-align: left;padding-left: 45px" ng-if="Pilih_kat"  ><a ng-click="kelola('Element',edit_element,Pilih_kat)"><i class="fa fa-cogs"></i> Kelola Element</a></div>
                      </div>
                  </div>
              </div>
              </div>
           
          </form>
        </div>

      </div>

       <table class="table table-bordered table-hover"  ng-show="Pilih_kat!=null || Pilih_kat!=undefinned">
           <thead style="text-align: center;">
               <tr class="blue-steel ">
                   <td>Nama  <a  
                        class="btn default btn-xs edit-input add pull-right blue" 
                        style="margin-right: 12px;"
                        ng-click="lockall(semua)"
                        ng-show="!semua"
                       > 
                      <i class="fa fa-unlock"></i>&nbsp;Semua</a>
                       <a  
                        class="btn default btn-xs edit-input add pull-right blue" 
                        style="margin-right: 12px;"
                        ng-click="lockall(semua)"
                        ng-show="semua"
                       > 
                      <i class="fa fa-lock"></i>&nbsp;Semua</a>
                   </td>
                   <td style="width: 100px;">Satuan</td>
                   <td style="width: 130px;">Sumber Data</td>
                   <td style="width: 185px;">Action</td>
               </tr>
           </thead>
             
           <tbody>
                <tr ng-hide="load_el">
                  <td colspan="4" class="text-center">
                  <img width="35px" src="<?php echo base_url('assets/images/ring-alt.svg') ?>">
                  </td>
                </tr>
               <tr ng-repeat="element1 in edit_element | filter:cari " class="lvl-{{element1.child}} no-animated" >
                  <td style="padding-left: {{element1.sub * 15}}px">
                  <div class="col-md-10">
                    <span ng-if="!el_edit" class="edit-input">{{element1.no}} {{element1.nama}}</span>
                   <input type="text" ng-model="element1.nama" class="form-control edit-input" ng-if="el_edit" style="margin-top: -7px;margin-bottom: -7px;">
                    <div ng-show="element1.minlength == false" role="alert" class="font-red" style="padding-top: 10px">
                      <div>Minimal 3 karakter</div>
                    </div>
                   </div>
                   
                    <a class="add pull-right"  
                         
                          ng-click="sub(element1,$index)"> 
                      <i class="fa fa-plus"></i> Sub 
                    </a> 
                    

                    <a   
                        class="btn default btn-xs edit-input add pull-right red " 
                        style="margin-right: 12px;"
                        ng-show="element1.lock==true"
                        ng-click="lock_el(element1.id,element1.lock,$index)"
                        ng-if="element1.child == null && !el_edit">
                      <i class="fa fa-lock "></i> 
                    </a>
                     <a  
                        class="btn default btn-xs edit-input add pull-right blue" 
                        style="margin-right: 12px;"
                        ng-hide="element1.lock==true "
                        ng-click="lock_el(element1.id,element1.lock,$index)"
                        ng-if="element1.child == null && !el_edit">
                      <i class="fa fa-unlock"></i> 
                    </a>

                    <a   
                        class="btn default btn-xs edit-input add pull-right blue " 
                        style="margin-right: 12px; position:absolute"
                        ng-show="element1.loading"
                        ng-if="element1.child == null && !el_edit"
                        ng-disabled="true" >
                      <i class="fa fa-circle-o-notch fa-spin"></i> 
                    </a>


                  </td>
                   
                  <td>
                    <span ng-show="!el_edit" class="edit-input">{{element1.satuan}}</span>
                    <input type="text" ng-model="element1.satuan" class="form-control edit-input" ng-if="el_edit" style="margin-top: -7px;margin-bottom: -7px;">
                  </td>
                  <td>
                    <span ng-show="!el_edit" class="edit-input">{{skpd[element1.id_group].name}}</span>
                    <select name="Kategori" class="form-control edit-input" style="margin-bottom: -7px;margin-top: -7px;"
                                ng-model="id_skpd"
                                ng-options="sumber.name for sumber in skpd track by sumber.id"
                                ng-init="id_skpd.id = element1.id_group"
                                ng-change="element1.id_group = id_skpd.id"
                                ng-if="el_edit"
                               ">
                    </select>
                  </td>

                    <td >
                        <a class="btn default btn-xs edit-input" 
                                  ng-show="!el_edit" 
                                  ng-click="edit($index);el_edit = true" 
                                  style="width: 90px"> 
                                  Edit <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>

                        <a class="btn blue btn-xs edit-input" 
                                  ng-show="el_edit"  
                                  ng-click="el_edit = edit_el(element1,$index);" 
                                  style="width: 90px"> 
                                  Simpan <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        </a>
                         <a class="btn red btn-xs edit-input" 
                                  ng-show="el_edit" 
                                  ng-click="cancel($index);el_edit = false"> 
                                  Batal <i class="fa fa-ban" aria-hidden="true"></i>
                        </a>
                        <a class="btn red btn-xs edit-input " 
                                  ng-if="element1.child == null && !el_edit" 
                                  ng-click="hapus_el(element1.id,$index)"> 
                                  Hapus <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </td>

               </tr>
           </tbody>
       </table>
        <div class="row">
          <span class="col-md-6">
          <!--  -->
          </span>
          <!-- <div class="col-md-6" ng-if="edit_element">
          <ul ng-change="pageChanged()" uib-pagination total-items="totalItems" items-per-page="1" ng-model="currentPage" max-size="5" class="pagination-sm pagination pull-right" boundary-links="true" num-pages="numPages"></ul>
          </div> -->
        </div>

    </div>
</div>

<script type="text/ng-template" id="modal.html">
    <div class="modal-header">
        <h3 class="modal-title" id="modal-title">{{title}}</h3>
    </div>
    <div class="modal-body" id="modal-body">
        <table class="table table-responsive table-bordered">
          <thead class="text-center">
            <tr>
              <td width="80px">ID</td>
              <td>Nama</td>
              <td width="95px">Action</td>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="edit_data in edit track by $index" class="">
              <td class="text-center">
                <span ng-show ="!edit_data.edit">{{edit_data.id}}</span>
                <span ng-hide="!edit_data.edit">
                      <input class="form-control" type="text" 
                              name="id"
                              ng-model="edit_data.id"
                              ng-change="edited = true"   
                              required="">
                    </span> 

              </td>
              <td>
                <span ng-show ="!edit_data.edit">{{edit_data.nama}}</span>
                <form  name="form_edit">
                  <div ng-class="{'has-error' : form_edit.nama.$error.required || form_edit.nama.$error.minlength }" >
                    <span ng-hide="!edit_data.edit">
                      <input class="form-control" type="text" 
                              name="nama"
                              ng-change="edited = true"
                              ng-model="edit_data.nama"   
                              ng-minlength="3"
                              required="">
                    </span>
                    <div ng-messages="form_edit.nama.$error" role="alert" class="font-red">
                      <div ng-message="required">You did not enter a field</div>
                      <div ng-message="minlength">Your field is too short</div>
                    </div>
                  </div>
                </form>
              </td>
              <td style="display: flex;">
                <a class="btn btn-icon-only default" 
                        uib-tooltip="Edit {{title}}" 
                        tooltip-trigger="focus"
                        ng-click ="editdata($index);edit_data.edit = true; awal = edit_data.id"
                        ng-show ="!edit_data.edit">
                    <i class="fa fa-pencil"></i>
                </a>
                <button class="btn btn-icon-only blue"
                        ng-disabled="form_edit.nama.$error.required || form_edit.nama.$error.minlength || edited == TRUE"
                        uib-tooltip="Save" 
                        tooltip-trigger="focus"
                        ng-click="update(edit_data,edited,awal,$index )"
                        ng-show="edit_data.edit">
                  <i class="fa fa-floppy-o"></i>
                </button>

                <a class="btn btn-icon-only red"
                        uib-tooltip="Cancel" 
                        tooltip-trigger="focus"
                        ng-click="batalEdit($index);edit_data.edit = false"
                        ng-show="edit_data.edit">
                  <i class="fa fa-times-circle"></i>
                </a>
                
                <a class="btn btn-icon-only red" 
                      uib-tooltip="Hapus {{title}}" 
                      tooltip-trigger="focus"
                      ng-click="hps_kat(edit_data.id,$index)"
                      ng-show ="!edit_data.edit && title != 'Element'">
                  <i class="fa fa-trash"></i>
                </a>
                <a class="btn btn-icon-only red" 
                      uib-tooltip="Hapus {{title}}" 
                      tooltip-trigger="focus"
                      ng-click="hps_el(edit_data.id,$index)"
                      ng-show ="!edit_data.edit && title == 'Element'">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
            <tr ng-if="tambah == true">
              <td><input class="form-control" type="text" name="id" ng-model="new_kat.id" ng-change="edited = true"></td>
              <td><input type="text" ng-model="new_kat.nama" class="form-control"></td>
              <td>
                <span ng-if="title == 'Element'">
                  <button type="button" class="btn green" ng-click="simpan_el(new_kat)">
                    <i class="fa fa-floppy-o"> Simpan</i> 
                  </button>
                </span>
                <span ng-if="title != 'Element'">
                  <button type="button" class="btn green" ng-click="simpan_kat(new_kat)">
                    <i class="fa fa-floppy-o"> Simpan</i> 
                  </button>
                </span>
              </td>
            </tr>
          </tbody>
        </table>
        <a class="btn green" ng-click="tambah = true" ng-show="!tambah" > Tambah
            <i class="fa fa-plus"></i>
        </a>
        <a class="btn red" ng-click="tambah = false" ng-show="tambah"> Cancel
           
        </a>
    </div>
    <div class="modal-footer">
        <button class="btn btn-warning" type="button" ng-click="cancel()">Close</button>
    </div>
</script>



<!-- modal tambah sub -->
<script type="text/ng-template" id="sub.html" >
    <div class="modal-header">
        <h3 class="modal-title" id="modal-title">Tambah Sub {{parent.nama}}</h3>
    </div>
    <div class="modal-body" id="modal-body">
      <form name="formsub">
        <div class="form-horizontal form-bordered form-row-stripped">
          <div class="form-body">
            <div class="form-group">
              <div class="col-md-3"><label>ID</label></div>
              <div class="col-md-9">
                <input type="text" name='id' class="form-control"  ng-model="hasil.id" placeholder="Kosongkan ID Untuk Mendapakan ID Secara Otomatis" value="" is-integer>
                
              </div>
            </div>
            <div class="form-group" ng-class="{'has-error' : formsub.nama.$touched && (formsub.nama.$error.required || formsub.nama.$error.minlength)}" >
              <div class="col-md-3"><label>Nama Elemen</label></div>
              <div class="col-md-9" >
                <input type="text" 
                        name="nama" 
                        placeholder="Masukkan sub Elemen {{parent.nama}}" 
                        class="form-control" 
                        ng-model="hasil.nama" 
                        ng-minlength="3"
                        required="">
                <div ng-messages="formsub.nama.$error" ng-show="formsub.nama.$touched" role="alert" class="font-red">
                  <div ng-message="required">Wajib di isi</div>
                  <div ng-message="minlength">Minimal 3 karakter</div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-3"><label>Satuan</label></div>
              <div class="col-md-9">
                  <input type="text" name="satuan" placeholder="Masukkan Satuan" class="form-control" ng-model="hasil.satuan" >
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-3"><label>Sumber Data</label></div>
              <div class="col-md-9">
                <select name="kategori" class="form-control edit-input" style="margin-bottom: -7px;margin-top: -7px;"
                    ng-model="hasil.id_skpd"
                    ng-options="sumber.name for sumber in skpd track by sumber.id"
                   ">
                </select>
                
              </div>
            </div>
          </div> 
        </div>
      </form>
    </div>
    <br>&nbsp;</br>
    <div class="modal-footer">
        <button class="btn btn-primary" type="button" ng-disabled="!formsub.$valid" ng-click="simpan()" >Simpan</button>
        <button class="btn btn-warning" type="button" ng-click="cancel()">Cancel</button>
    </div>
</script>