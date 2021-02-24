<div class="portlet box blue-steel" id="modalsub">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-table"></i>Element-Api</div>
        <div class="tools">
            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
            <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
            <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
            <a href="" class="fullscreen" data-original-title="" title=""> </a>
            <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
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
                      <label class="control-label col-md-2">SKPD</label>
                      <div class="col-md-6 select2-wrapper">
                        <div class="form-loading" ng-if="api_groups==null"> <img src="<?php echo base_url('/assets/images/form-loading.svg') ?>"> </div>
                        <select name="Kategori" class="btn btn-sm btn-outline  uppercase"
                              ng-model="pil_skpd"
                              ng-options="group.name for group in api_groups track by group.group_id"
                              ng-change="ganti_skpd()"
                              style="border-color: #2f353b;">
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label col-md-2">Jenis Data</label>
                      <div class="col-md-6 select2-wrapper">
                        <div class="form-loading" ng-if="jenisdata==null && pil_skpd!=null"> <img src="<?php echo base_url('/assets/images/form-loading.svg') ?>"> </div>
                        <select name="foo" class="form-control" 
                              ui-select2="select2Options"
                              ng-model="Pilih_jenis" 
                              ng-options="Jenis.nama for Jenis in jenisdata track by Jenis.id" 
                              ng-change="update_jenis()" >  
                        </select>
                      </div>
                    </div>
                  </div>
                  <!--/span-->

                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label col-md-2">Kategori</label>
                      <div class="col-md-6">
                        <div class="form-loading" ng-if="kategori==null && Pilih_jenis!=null"> <img src="<?php echo base_url('/assets/images/form-loading.svg') ?>"> </div>
                          <select name="Kategori" class="form-control"
                                  ui-select2="select2Options"
                                  ng-model="Pilih_kat"
                                  ng-options="kat.nama for kat in kategori track by kat.id"
                                  ng-change="update_kat()"
                                  >
                          </select>
                      </div>
                    </div>
                  </div>
                  <!--/span-->
                </div>
               
              </div>
           
          </form>
        </div>
         
      </div>
      <div class="row">
        <div class="col-md-12">
          
        
          <table class="table table-bordered table-hover"  ng-show="Pilih_kat!=null || Pilih_kat!=undefinned">
             <thead>
                <tr class="blue-steel ">
                     <td rowspan="2" >Nama</td>

                     <td colspan="2" style="min-width: 98px">Setting</td>
                     
                </tr>
                <tr class="blue-steel ">
                    <td style="min-width: 98px"><!-- <input type="checkbox" ng-true-value="1" ng-false-value="0" ng-model="check.manual" ng-click="checkAll('manual')" style=" -ms-transform: scale(1.2);-moz-transform: scale(1.2);-webkit-transform: scale(1.2);-o-transform: scale(1.2);margin: 5px;"> -->Manual</td>
                    <td style="min-width: 98px"><!-- <input type="checkbox" ng-true-value="1" ng-false-value="0" ng-model="check.bridging" ng-click="checkAll('bridging')" style=" -ms-transform: scale(1.2);-moz-transform: scale(1.2);-webkit-transform: scale(1.2);-o-transform: scale(1.2);margin: 5px;"> -->Bridging</td>
                </tr>
             </thead>
             <tbody>
                <tr ng-hide="load_el">
                  <td colspan="3" class="text-center"><img width="35px" src="<?php echo base_url('assets/images/ring-alt.svg') ?>"></td>
                </tr>
                <tr ng-repeat="element1 in element | carielement:cari" class="data-element lvl-{{element1.child}} ">
                     
                  <td style="padding-left: {{element1.sub * 15}}px">
                      <a ng-click="show_grafik(element1)">
                        {{element1.no}} {{element1.nama}}
                      </a>
                  </td>

                  <td colspan="2" ng-show="element1.child != null">
                  <td ng-show="element1.child == null">
                    <input type="checkbox" 
                            name="{{element1.id}}" 
                            ng-model="element1.manual" 
                            ng-true-value="1"
                            ng-false-value="0"
                            ng-change="element1.edited = true" 
                            ng-checked="chekbok(element1.manual)"
                            icheck 
                            judul ="Manual"
                           >

                  </td>
                  <td ng-show="element1.child == null">
                    <input type="checkbox" 
                            name="{{element1.id}}" 
                            ng-model="element1.bridging" 
                            ng-true-value="1"
                            ng-false-value="0"
                            ng-change="element1.edited = true" 
                            ng-checked="chekbok(element1.bridging)"
                            icheck 
                            judul ="Bridging">
                           
                  </td>
                   
                </tr>
             </tbody>
          </table>
        </div>
        <div class="col-md-8 col-sm-12">
          <div class="btn-group input-actions">
            <a class="btn uppercase btn-outline blue-steel" ng-click="simpan()">Simpan</a>
          </div>
        </div>
      </div>
    </div>

</div>


<!-- modal tambah sub -->
<script type="text/ng-template" id="grafik.html" >
    <div class="modal-header" style="background-color: #14b9d6;color: #fefefe;border: 0px">
    <a href=javascript:; ng-click="cancel()" class="pull-right" style="padding-top: 5px;"><img src="<?php echo base_url('assets/images/close.png')?>" style="width: 15px"></a>
        <h3 class="modal-title" id="modal-title">Grafik</h3>
    </div>
    <div class="modal-body" id="modal-body" resize style="height: {{windowHeight-67}}px; margin: 0;padding: 0; min-height: 425px;">
       <div class="row" style="padding: 0;margin: 0">
        <div class="col-md-3" style="height: {{windowHeight-67}}px; padding: 0;margin: 0; background-color: #4d5b69;color: #f2f4f6; min-height: 425px;" >
            <div class="col-md-12 text-center" style="background-color: #465460">
              <h4>Setting Grafik</h4>
            </div>
            <div class="col-md-12">
            <div class="note note-success">
                  <p>data yang di pilih secara default adalah jumlah keseluruhan sub element</p>
              </div>
                <form name="form_grafik" role="form" ng-submit="submit()">
                  <div class="form-body">
                      <div class="form-group col-lg-6">
                          <label class="control-label">Start</label>
                          <select name="thn1" class="form-control"
                                  ng-model="grfk.awalThn"
                                  ng-options="tahun for (k_thn,tahun) in grf_tahun track by tahun"
                                  ng-change="change_thn()"> 
                          </select>
                        </div>

                      <div class="form-group col-lg-6">
                          <label class="control-label">End</label>
                          <select name="thn2" class="form-control"
                                  ng-model="grfk.akhirThn"
                                  ng-options="tahun for (k_thn,tahun) in select_thnakhir track by tahun"
                                  ng-change="sembunyi()"
                                  > 
                          </select>
                      </div>
                      <div class="form-group col-lg-12">
                          <label class="control-label">Grafik</label>
                          <select class="form-control" ng-model="grfk.diagram" ng-change="sembunyi()">
                            <option value="1">Diagram Batang</option>
                            <option value="2">Diagram garis</option>
                          </select>
                      </div>

                       <div class="form-group col-lg-12">
                          <label class="control-label">Pilih Data</label>
                          <select ui-select2 
                                  name="sub" 
                                  ng-model="grfk.pilSub" 
                                  data-placeholder="Pilih sub" 
                                  ng-options="sub.nama for sub in sub_select track by sub.id"
                                  multiple="multiple" 
                                  ng-change="sembunyi()"
                                  class="col-lg-12" 
                                  class="form-control">
                          </select>
                      </div>

                  </div>

                  <div class="form-actions col-lg-12">
                      <button type="submit" class="btn blue pull-right" value="Submit">Submit</button>
                      <button type="button" class="btn green pull-right" value="Submit" style="margin-right: 20px;" ng-click="cancel()">Close</button>
                  </div>
              </form>

            </div>
             
          
       </div>
          <div ng-if="tampil1==true && tampil2==true && grfk.diagram == 1" style="height: {{windowHeight-67}}px;" >
            <my-grafik1 datagrafik="datagrafik" dataoption="graphoption" satuan="satuan"></my-grafik1>    
          </div>
          <div ng-if="tampil1==true && tampil2==true && grfk.diagram == 2" style="height: {{windowHeight-67}}px;" >
            <my-grafik2 datagrafik="datagrafik" dataoption="graphoption" satuan="satuan"></my-grafik2>    
          </div>
        
       <!-- here -->
      </div>
    </div>
</script>

