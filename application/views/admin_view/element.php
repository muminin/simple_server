<div class="portlet box blue-steel" id="modalsub">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-table"></i>{{jenisdata.nama}} -- {{kategori_tabel.nama}} </div>
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
        <div class="col-md-8 col-sm-12">
          <div class="btn-group input-actions" style="border-right: 1px solid #aaa;padding-right: 4px">
            <a class="btn dark btn-sm btn-outline sbold uppercase" target="_blank" href="<?php echo base_url('/export/excel')?>/{{posisi.id_kat}}/{{posisi.tahun}}">Download Excel</a>
          </div>
          <div class="btn-group input-actions" style="border-right: 1px solid #aaa;padding-right: 4px">

            <select name="Kategori" class="btn btn-sm btn-outline  uppercase"
                                  ng-model="pil_thn"
                                  ng-options="tahun for (k_thn,tahun) in thn_data track by tahun"
                                  ng-change="change_thn(tahun)" 
                                  style="border-color: #2f353b;">
            </select>
            <!-- <select class="btn dark btn-sm btn-outline  uppercase">
              <option>2018</option>
              <option>2019</option>
            </select> -->
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <div class="table-group-actions pull-right">
            <form class="form-inline pull-right" action="index.html">
                <div class="input-group input-medium">
                    <input type="text" class="form-control" placeholder="Cari" ng-model="cari">
                    <span class="input-group-btn">
                        <button type="button" class="btn green">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          
        
          <table class="table table-bordered table-hover"  ng-show="Pilih_kat!=null || Pilih_kat!=undefinned">
             <thead>
                 <tr class="blue-steel ">
                     <td >Nama</td>

                     <td style="min-width: 98px">Nilai</td>
                     <td>Satuan</td>
                     <td>Ketersediaan</td>
                     <td>Update Terakhir</td>
                 </tr>
             </thead>
             <tbody>
                 <tr ng-repeat="element1 in element | carielement:cari" class="data-element lvl-{{element1.child}} ">
                     <td style="padding-left: {{element1.sub * 15}}px">
                        <a ng-click="show_grafik(element1)">
                          {{element1.no}} {{element1.nama}}
                        </a>
                      <div ng-if="element1.publish === 1" class="pull-right">
                          <a class="pull-right publish" 
                              ng-show="element1.child != null" 
                              ng-click="element1.publish = 0; element1.edited = true">
                          Publish</a>
                      </div>
                      <div ng-if="element1.publish != 1" class="pull-right">
                          <a class="pull-right publish" 
                              ng-show="element1.child != null" 
                              ng-click="element1.publish = 1; element1.edited = true">
                          Hidden</a>
                      </div>
                     </td>
                     <td ng-click="tampil(element1)" style="padding: 4px 8px; vertical-align: middle;" >
                     <span ng-if="element1.child != null">{{element1.nilai | number}} {{cekparent(element1.id,$index)}}</span>
                     <span ng-hide="element1.editing" ng-if="element1.child == null">{{element1.nilai | number}} {{cekparent(element1.id,$index)}}
                        <span ng-show="element1.nilai == null">
                          n/a
                        </span>
                     </span>

                      <div ng-show="element1.editing" ng-if="!element1.child" class="form-group" style="padding: 0px;margin: 0px;min-height: 22px;">
                          <input ng-change="element1.edited = true" 
                                ng-model="element1.nilai" 
                                ng-blur="element1.editing = false" 
                                ng-enter="element1.editing = false"
                                type="text" 
                                class="form-control input-xsmall" 
                                id="edit_nilai{{element1.id}}" 
                                placeholder="N/A" 
                                style="padding: 0px;height: 30px;">
                      </div>
                     </td>
                     <td ng-click="unit_tmpl(element1)" ng-if="element1.child == null">
                        <span ng-hide="element1.unit_edit==true" >{{element1.satuan}}</span>
                        <div ng-show="element1.unit_edit==true" ng-if="!element1.child" class="form-group" style="padding: 0px;margin: 0px;min-height: 22px;">
                          <input ng-change="element1.edited = true" 
                                ng-model="element1.satuan" 
                                ng-blur="element1.unit_edit=false" 
                                ng-enter="element1.unit_edit=false"
                                type="text" 
                                class="form-control input-xsmall" 
                                id="edit_unit{{element1.id}}" 
                                placeholder="N/A" 
                                style="padding: 0px;height: 30px;">
                      </div>
                     </td>
                     <td ng-if="element1.child != null"></td>

                     <td ng-show="element1.child == null">
                       <input type="checkbox" 
                              name="{{element1.id}}" 
                              ng-model="element1.ketersediaan" 
                              ng-true-value="1"
                              ng-false-value="0"
                              ng-change="element1.edited = true" 
                              ng-checked="chekbok(element1.ketersediaan)">
                       Ada
                    </td>
                    <td ng-if="element1.child != null"> </td>
                    <td>{{element1.update | tanggal}}</td>
                      
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

