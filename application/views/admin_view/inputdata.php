<div class="portlet box blue-steel" id="modalsub">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-table"></i>Input Data</div>
        <div class="tools">

            <a  class="collapse" data-original-title="" title=""> </a>
            <a  class="reload" ng-click="reload()" title=""> </a>
            <a  class="fullscreen" data-original-title="" title=""> </a>
        </div>
    </div>
    <div class="portlet-body">

      <div class="row" style="padding-top: 20px">
        <div class="col-md-12">
          <form action="#" class="form-horizontal">
            <div class="form-body">
              <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-md-2">Jenis Data</label>
                        <div class="col-md-6 select2-wrapper">
                          <div class="form-loading" ng-if="jenisdata==null"> <img src="<?php echo base_url('/assets/images/form-loading.svg') ?>"> </div>
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
                            <div class="form-loading" ng-if="Pilih_jenis!=null && kategori==null"> <img src="<?php echo base_url('/assets/images/form-loading.svg') ?>"> </div>
                            <select name="Kategori" class="form-control"
                                    ui-select2="select2Options"
                                    ng-model="Pilih_kat"
                                    ng-options="kat.nama for kat in kategori "
                                    ng-change="update_kat()">

                            </select>
                        </div>
                   </div>
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

                    </div>
                </div>
            </div>
            </div>

          </form>

          <div class="row" ng-show="Pilih_kat!=null || Pilih_kat!=undefinned">
            <div class="col-md-12">
              <div class="col-md-8" style="padding: 5px 1px;margin: 5px 0px">
                <div class="btn-group input-actions">
                  <a class="btn dark btn-sm btn-outline sbold uppercase" target="_blank" href="<?php echo base_url('/export/excel')?>/{{posisi.id_kat}}/{{posisi.tahun}}">Download Excel</a>
                </div>
                <div class="btn-group input-actions">
                  <div class="btn-group input-actions">
                    <a class="btn dark btn-sm btn-outline sbold uppercase"  ng-click="upload()">Import Data</a>
                  </div>
                   <input type="file" nv-file-select="" uploader="uploader" name="upload" id="upload" style="display: none" />
                </div>


                <div class="btn-group input-actions">
                  <a class="btn dark btn-sm btn-outline sbold uppercase" ng-click="salin()" >Salin Data</a>
                </div>

                <div class="btn-group input-actions">
                  <a href="<?= base_url('/laporan/urusan') ?>?id={{Pilih_kat.id}}&tahun={{pil_thn}}" target="_blank" class="btn dark btn-sm btn-outline sbold uppercase">Download Laporan</a>
                </div>

              </div>

              <div class="col-md-4 pull-right" style="padding: 5px 1px;margin: 5px 0px">
                <div class="btn-group input-actions pull-right">
                  <!-- <a class="btn uppercase btn-outline blue-steel" ng-click="simpan()">Simpan</a> -->
                </div>
              </div>


            </div>
          </div>

          <table class="table table-bordered table-hover" ng-show="Pilih_kat!=null || Pilih_kat!=undefinned">
             <thead>
                 <tr class="blue-steel tbl-header">
                     <td >Nama</td>

                     <td style="min-width: 98px">Nilai</td>
                     <td>Satuan</td>
                     <td>Ketersediaan</td>
                     <td>Update Terakhir</td>
                 </tr>
             </thead>
             <tbody>
                <tr ng-hide="load_el">
                  <td colspan="4" class="text-center">
                  <img width="35px" src="<?php echo base_url('assets/images/ring-alt.svg') ?>">
                  </td>
                </tr>
                 <tr ng-repeat="element1 in element | carielement:cari" class="data-element lvl-{{element1.child}}" ng-show="load_el">
                     <td style="padding-left: {{element1.sub * 15}}px">
                        <a ng-click="show_grafik(element1)">
                          {{element1.no}} {{element1.nama}}
                        </a>
                      <div ng-if="element1.publish === 1" class="pull-right">
                          <a class="pull-right publish"
                              ng-click="unpublish($index)">
                          Publish</a>
                      </div>
                      <div ng-if="element1.publish != 1" class="pull-right">
                          <a class="pull-right publish"
                              ng-click="publish($index)">
                          Unpublish</a>
                      </div>
                     </td>
                     <td ng-click="tampil(element1)" style="padding: 4px 8px; vertical-align: middle;" >
                     <span ng-if="element1.child != null">{{element1.nilai | number}} {{cekparent(element1.id,$index)}}</span>
                      <span ng-show="element1.ketersediaan==0">
                         0
                        </span>
                     <span ng-hide="element1.editing" ng-if="element1.child == null  && element1.ketersediaan==1">{{element1.nilai | number}} {{cekparent(element1.id,$index)}}
                        <span ng-show="element1.nilai == null">
                          n/a
                        </span>

                     </span>

                      <div ng-show="element1.editing  && element1.ketersediaan==1" ng-if="!element1.child" class="form-group" style="padding: 0px;margin: 0px;min-height: 22px;">
                          <input ng-change="element1.edited = true;inputdata_change($index,element_input[$index].nilai);"
                                ng-model="element_input[$index].nilai"
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
                        <span ng-hide="element1.unit_edit==true && element1.ketersediaan==1" >{{element1.satuan}}</span>
                        <div ng-show="element1.unit_edit==true && element1.ketersediaan==1" ng-if="!element1.child" class="form-group" style="padding: 0px;margin: 0px;min-height: 22px;">
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
                              ng-disabled="element1.lock"
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
        <div class="col-md-6 col-sm-12"  ng-show="Pilih_kat!=null || Pilih_kat!=undefinned">
          <div class="btn-group input-actions">
            <!-- <a class="btn uppercase btn-outline blue-steel" ng-click="simpan()">Simpan</a> -->
          </div>
        </div>
        <!-- <div class="col-md-6" ng-if="element">
          <ul ng-change="pageChanged()" uib-pagination total-items="totalItems" items-per-page="1" ng-model="currentPage" max-size="5" class="pagination-sm pagination pull-right" boundary-links="true" num-pages="numPages"></ul>
          </div>
        </div> -->
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
                <form name="form_grafik" role="form" ng-submit="submit()" class="grafik">
                  <div class="form-body">
                      <div class="form-group col-lg-6">
                          <label class="control-label">Tahun Mulai</label>
                          <select name="thn1" class="form-control"
                                  ng-model="grfk.awalThn"
                                  ng-options="tahun for (k_thn,tahun) in grf_tahun track by tahun"
                                  ng-change="change_thn()">
                          </select>
                        </div>

                      <div class="form-group col-lg-6">
                          <label class="control-label">Tahun Akhir</label>
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

<!-- modal import -->
<script type="text/ng-template" id="print.html" >

    <div class="modal-body" id="modal-body"">
      <div class="row">
        <!-- navigasi -->
        <div class="col-lg-3 col-md-3 col-sm-12 nav-container no-padding">
          <header class="header-nav">
            <h1 id="navbar-content-title">Preview</h1>

            <div class="content">
                <span>Total Data : {{excels.length}} Data</span>
                <div class="button">
                    <button class="btn btn-info" ng-click="simpan()" ng-if="status.length==0 && keterangan[4].C==kategori.id " ng-disabled="simpanload==false||simpanload==true">
                      <i class="fa fa-spinner fa-spin fa-fw" ng-show="simpanload==true"></i>
                      <span ng-hide="simpanload==true">Simpan</span>
                      <span ng-show="simpanload==true">Menyimpan</span>
                    </button>
                    <button class="btn btn-default" ng-click="cancel()" ng-hide="simpanload==false">Batal</button>
                    <button class="btn btn-default" ng-click="cancel()" ng-show="simpanload==false">Close</button>
                </div>
            </div>
          </header>
          <div class="navigation">
            <div class="group">
              <div class="col-lg-4">Jenis Data</div>
              <div class="col-lg-8" >
                : {{keterangan[3].C}}
              </div>
            </div>
            <div class="group">
              <div class="col-lg-4">ID Jenis Data</div>
              <div class="col-lg-8" >
                : {{keterangan[4].C}}
                <div class="alert alert-danger label-danger" ng-if="keterangan[4].C!=kategori.id"><strong>Error!</strong> ID Kategori File Tidak Sesuai Dengan Kategori yang Dipilih</div>
              </div>
            </div>

            <div class="group">
              <div class="col-lg-4">Tahun</div>
              <div class="col-lg-8"> : {{keterangan[5].C}}</div>

            </div>
            <div class="group">
              <div class="col-lg-12" ng-if="status.length!=0"><div class="alert alert-warning"><strong>Error!</strong> Data Tidak Dapat Di Simpan Karena Terdapat Kesalahan Pada Pengisian File Excel. <b>{{error}}. {{error2}}.</b></div></div>

            </div>
          </div>
        </div>
        <!-- end navigasi -->

        <!-- preview -->
        <div class="col-lg-9 col-md-8 col-sm-12 preview no-padding" style="height: 100%">
          <div style="display: block;position: absolute;background: rgba(231, 236, 241, 0.61);width: 100%;height: 100%;text-align: center;" ng-show="simpanload==true">
            <img src="<?php echo base_url('assets/loading/') ?>loading-bars.svg" style="padding-top:90px;width: 64px;" />
            <h1 class="h1 mt2 mb0" style="font-family: 'Helvetica Neue', Helvetica, sans-serif;font-size: 40px;line-height: 5rem;    font-weight: bold;">Sedang Menyimpan</h1>
          </div>


          <div class="preview-content">
            <div style="padding: 50px">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <td>ID</td>
                    <td>Parent</td>
                    <td>level</td>
                    <td>Nama</td>
                    <td>Nilai</td>
                    <td>Satuan</td>
                    <td>ketersediaan</td>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="excel in excels track by $index"  ng-class="{warning : status[$index]}" >
                    <td>{{cekdata(excel.A,excel.B,$index)}}</td>
                    <td>{{excel.B}}</td>
                    <td>{{excel.C}}</td>
                    <td>{{excel.D}}</td>
                    <td>{{excel.E}}</td>
                    <td>{{excel.F}}</td>
                    <td>{{excel.G}}</td>
                  </tr>
                </tbody>
              </table>
            </div>


          </div>
        </div>
        <!--  -->
      </div>

    </div>
</script>

<!-- modal salin data -->
<script type="text/ng-template" id="salidData.html" >

    <div class="modal-body" id="modal-body"">
      <div class="row">
        <!-- navigasi -->
        <div class="col-lg-3 col-md-3 col-sm-12 nav-container no-padding">
          <header class="header-nav">
            <h1 id="navbar-content-title">Preview</h1>

            <div class="content">
                <span>Total Data : {{elements.length}} Data</span>
                <div class="button">
                    <button class="btn btn-info" ng-click="simpan()" ng-disabled="simpanload==false||simpanload==true">
                      <i class="fa fa-spinner fa-spin fa-fw" ng-show="simpanload==true"></i>
                      <span ng-hide="simpanload==true">Simpan</span>
                      <span ng-show="simpanload==true">Menyimpan</span>
                    </button>
                    <button class="btn btn-default" ng-click="cancel()" ng-hide="simpanload==false">Batal</button>
                    <button class="btn btn-default" ng-click="cancel()" ng-show="simpanload==false">Close</button>
                </div>
            </div>
          </header>
          <div class="navigation">
            <div class="group">
              <div class="col-lg-4">Jenis Data</div>
              <div class="col-lg-8">{{jenisElement}}</div>
            </div>

            <div class="group">
              <div class="col-lg-4">Tahun</div>
              <div class="col-lg-8">{{tahuntarget}}</div>
            </div>

            <div class="group">
              <div class="col-lg-4">Salin Tahun</div>
              <div class="col-lg-8">
                  <select name="Kategori" class="btn btn-sm btn-outline form-control uppercase"
                                    ng-model="pil_thn"
                                    ui-select2="select2Options"
                                    ng-options="tahun for (k_thn,tahun) in thn_data track by tahun"
                                    ng-change="change_thn(tahun)"
                                    style="border-color: #2f353b;">
                  </select>
              </div>
            </div>
          </div>
        </div>
        <!-- end navigasi -->

        <!-- salin data -->
        <div class="col-lg-9 col-md-8 col-sm-12 preview no-padding" style="height: 100%">
          <div style="display: block;position: absolute;background: rgba(231, 236, 241, 0.61);width: 100%;height: 100%;text-align: center;" ng-show="simpanload==true">
            <img src="<?php echo base_url('assets/loading/') ?>loading-bars.svg" style="padding-top:90px;width: 64px;" />
            <h1 class="h1 mt2 mb0" style="font-family: 'Helvetica Neue', Helvetica, sans-serif;font-size: 40px;line-height: 5rem;    font-weight: bold;">Sedang Menyimpan</h1>
          </div>


          <div class="preview-content">
            <div style="padding: 50px">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <td>
                        <label class="mt-checkbox mt-checkbox-outline">
                          <input type="checkbox" name="cekall" ng-click="cekAll()" ng-model='cekAllValue' ng-checked="cekAllValue">
                          <span></span>
                        </label>
                    </td>
                    <td>Nama</td>
                    <td>Nilai</td>
                    <td>Satuan</td>
                    <td>ketersediaan</td>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="element in elements track by $index">
                    <td>
                      <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="cek" ng-model='element.cek' ng-checked="element.cek==1">
                            <span></span>
                        </label>
                    </td>
                    <td>{{element.nama}}</td>
                    <td>{{element.nilai}}</td>
                    <td>{{element.satuan}}</td>
                    <td>{{ketersediaan[element.ketersediaan]}}</td>
                  </tr>
                </tbody>
              </table>
            </div>


          </div>
        </div>
        <!--  -->
      </div>

    </div>
</script>