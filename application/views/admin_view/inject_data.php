<div class="portlet box blue-steel" id="modalsub">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Edit Element</div>
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
                              ng-options="Jenis.nama for Jenis in jenisdata" 
                              ng-change="update_jenis()" >  
                        </select>
                      </div>
<!--                       <div class="control-label col-md-4 font-red-thunderbird" style="text-align: left;" ><a ng-click="kelola('Jenis Data',jenisdata,0)"><i class="fa fa-cogs"></i> Kelola</a></div>
 -->                  </div>
              </div>
              <!--/span-->
              <div class="col-md-12">
                  <div class="form-group">
                      <label class="control-label col-md-2">Kategori</label>
                      <div class="col-md-6">
                          <select name="Kategori" class="form-control"
                                  ui-select2="select2Options"
                                  ng-model="Pilih_kat"
                                  ng-options="kat.nama for kat in kategori"
                                  ng-change="update_kat()">
                              
                          </select>
                      </div>
<!--                   <div class="control-label col-md-4 font-red-thunderbird" style="text-align: left;" ng-if="Pilih_jenis" ><a ng-click="kelola('Kategori',kategori,Pilih_jenis.id)"><i class="fa fa-cogs"></i> Kelola</div></a>
 -->                  </div>
              </div>

               <div class="col-md-12">
                  <div class="form-group">
                      <label class="control-label col-md-2">Tahun</label>
                      <div class="col-md-3">
                         <select name="Kategori" class="btn btn-sm btn-outline form-control uppercase"
                                  ng-model="pil_thn"
                                  ui-select2="select2Options"
                                  ng-options="tahun for (k_thn,tahun) in thn_data track by tahun"
                                  ng-change="change_thn(tahun)" 
                                  style="border-color: #2f353b;">
                          </select>
                      </div>
<!--                   <div class="control-label col-md-4 font-red-thunderbird" style="text-align: left;" ng-if="Pilih_jenis" ><a ng-click="kelola('Kategori',kategori,Pilih_jenis.id)"><i class="fa fa-cogs"></i> Kelola</div></a>
 -->                  </div>
              </div>

              <!--/span-->
              <div class="col-md-12">
                  <div class="form-group">
                      <label class="control-label col-md-2"></label>
                      <div class="col-md-4" style="display: flex;">
                          <input type="text" class="form-control" placeholder="Cari Element">
                          <span class="input-group-btn">
                              <button type="submit" class="btn green">
                                  <i class="fa fa-search"></i>
                              </button>
                          </span>
                      </div>
                      <!-- <div class="control-label col-md-4 font-red-thunderbird" style="text-align: left;padding-left: 45px" ng-if="Pilih_kat"  ><a ng-click="kelola('Element',edit_element,Pilih_kat)"><i class="fa fa-cogs"></i> Kelola</a></div> -->
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
                   <td>Nama</td>
                   <td style="width: 100px;">Satuan</td>
                   <td style="width: 130px;">Nilai</td>
                   <td style="width: 185px;">Action</td>
               </tr>
           </thead>
             
           <tbody>
                <tr ng-hide="load_el">
                  <td colspan="4" class="text-center">
                  <img width="35px" src="<?php echo base_url('assets/images/ring-alt.svg') ?>">
                  </td>
                </tr>
               <tr ng-repeat="element1 in edit_element" class="lvl-{{element1.child}}">
                  <td style="padding-left: {{element1.sub * 15}}px">
                  <div class="col-md-10">
                    <span>{{element1.no}} {{element1.nama}}</span>
                   
                   </div>
                   
                  </td>
                   
                  <td>
                    <span >{{element1.satuan}}</span>
                  </td>
                  <td>
                    <span>{{skpd[element1.id_group].name}}</span>
                  </td>
                  <td >
                      <a class="btn default btn-xs" 
                                ng-click="injectdata(element1)" 
                                style="width: 90px"> 
                                Edit <i class="fa fa-pencil" aria-hidden="true"></i>
                      </a>
                  </td>
               </tr>
           </tbody>
       </table>
        <div class="row">
          <span class="col-md-6">
          <!--  -->
          </span>
          <div class="col-md-6" ng-if="edit_element">
          <ul ng-change="pageChanged()" uib-pagination total-items="totalItems" items-per-page="1" ng-model="currentPage" max-size="5" class="pagination-sm pagination pull-right" boundary-links="true" num-pages="numPages"></ul>
          </div>
        </div>

    </div>
</div>




<!-- modal tambah sub -->
<script type="text/ng-template" id="inject.html" >
    <div class="modal-header">
        <h3 class="modal-title" id="modal-title">Tambah Sub {{parent.nama}}</h3>
    </div>
    <div class="modal-body form" id="modal-body">
       <form class="form-horizontal form-bordered form-label-stripped ng-pristine ng-invalid ng-invalid-required ng-valid-pattern ng-valid-minlength" name="form_user">
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label col-md-3">Nama Element</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="First Name" class="form-control" name="first" ng-model="datainject.nama" required="" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Url </label>
                    <div class="col-md-9">
                        <input type="text" 
                              placeholder="Last Name" 
                              class="form-control" 
                              ng-model="datainject.inject_data"
                              on ng-change="validate=false;status_validasi='';">
                        <span class="help-block" ng-show="validate==false"><a ng-click="validated()">Validasi {{status_validasi}}</a></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Satuan</label>
                    <div class="col-md-9">
                         <input type="text" placeholder="Satuan" class="form-control" name="satuan" ng-model="datainject.satuan">
                    </div>
                </div>
              </div>
            </div>
        </form>
    <div class="modal-footer">
        <button class="btn btn-primary" type="button" ng-click="simpan()" ng-disabled="validate==false" >Simpan</button>
        <button class="btn btn-warning" type="button" ng-click="cancel()">Cancel</button>
    </div>
</script> 