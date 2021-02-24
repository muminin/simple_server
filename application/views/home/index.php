<div class="block-border"><div class="block-content">
	<!-- We could put the menu inside a H1, but to get valid syntax we'll use a wrapper -->
	<div class="h1 with-menu">
		<h1>{{judul}}</h1>
		
	</div>



	<div class="block-controls">
		<!-- block control grafik -->
		<div class="" ng-hide="grafik_el && grafik_op">
			<div class="float-right">
				<label for="table-display" style="display:inline">Tahun</label>
				<select name="tahundata"
                      ng-model="datatahun"
                      ng-options="tahun for (k_thn,tahun) in tahuns"
                      class="" 
                      ng-change="gantitahun()"
                      > 
              	</select>
			</div>
		</div>
		<ul class="controls-buttons"  ng-if="grafik_el && grafik_op">
			<li ng-click="grafikback()"><a class="btn btn-info" ><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span> Kembali</a></li>
		</ul>
		
		<!-- end block contorl grafik-->
		<!-- block pagination tabel -->
		<!-- <ul ng-if="elements !=null && tabel !=false" uib-pagination total-items="totalItems" ng-model="currentPage" max-size="maxSize" ng-change="pageChanged()" style="margin-top: 0px"></ul> -->
		<!-- end block pagination tabel -->
	</div>
	
	<form class="form" id="tab-stats" method="post" action="#">
		<!-- loading bar -->
		<div class="loading" ng-show="loading"><div class="loading loading-backdrop"></div><div class="loading-default-wrapper" style="position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px;">

		   <div class="loading-default-sign">

		      <div class="loading-default-spinner">
		         <div class="bar1"></div>
		         <div class="bar2"></div>
		         <div class="bar3"></div>
		         <div class="bar4"></div>
		         <div class="bar5"></div>
		         <div class="bar6"></div>
		         <div class="bar7"></div>
		         <div class="bar8"></div>
		         <div class="bar9"></div>
		         <div class="bar10"></div>
		         <div class="bar11"></div>
		         <div class="bar12"></div>
		      </div>

		      <div class="loading-default-text ng-binding">Please Wait...</div>

		   </div>

		</div></div>
	
		<!-- end loading bar -->
		
		<fieldset class="grey-bg" ng-class="{collapsed :!pilihan}" ng-if="grafik_el">
			<legend style="width: 75px;"><a ng-click="pilihan =!pilihan" style="cursor: pointer;">Options</a></legend>
			
			<form role="form" name="option">
				<div class="col-lg-2 form-group">
					<label for="stats-period">Tahun Mulai</label>
					<select name="thn1" class="form-control"
	                      ng-model="grafik.awalThn"
	                      ng-options="tahun for (k_thn,tahun) in tahuns"
	                      ng-change="change_thn()"
	                      > 
	              	</select>
				</div>

				<div class="col-lg-2 form-group">
					<label for="stats-period">Tahun Akhir</label>
					<select name="thn1" class="form-control"
	                      ng-model="grafik.akhirThn"
	                      ng-options="tahun for (k_thn,tahun) in select_thnakhir"
	                      > 
	              	</select>
				</div>

				<div class="col-lg-6 form-group">
					<label for="stats-period">Data sub</label>
					<select ui-select2 
	                      name="sub" 
	                      ng-model="grafik.pilSub" 
	                      data-placeholder="Pilih sub" 
	                      ng-options="child.nama for child in childs track by child.id"
	                      multiple="multiple" 
	                      ng-change="sembunyi()"
	                      class="col-lg-12"
	                      style="width: 100%" 
	                      >
	              </select>
				</div>

				<div class="col-lg-2 form-group">
					<label for="stats-period">Grafik</label>
					<select name="thn1" ng-model="grafik.diagram">
	                        <option value="1">Grafik Batang</option>
	                        <option value="2">Grafik Garis</option>
	              	</select>
				</div>
				<p>
				<div class="col-lg-12 form-group" >
					<p>
						<button type="button" ng-click="tampil()" class="pull-left">Apply</button>
					</p>		
				</div>
			</form>
			
			
			
		</fieldset>
		<div >
			
		</div>



		<div class="no-margin ">

			<!-- data sipd -->
			
			<table class="table sortable responsive tabel-element" cellspacing="0" width="100%" ng-if="elements !=null && tabel !=false">
	
				<thead>
					<tr>
						<th scope="col" class="sorting" style="width: 369px;">Title</th>
						<th scope="col" class="sorting_disabled">Nilai</th>
						<th scope="col" class="sorting" >Satuan</th>
						<th scope="col" class="sorting" >Update Terakhir</th>
					</tr>
				</thead>
	
				<tbody>
					<tr ng-repeat="element in elements">
						
						<td style="padding-left: {{16*element.sub}}px;">
							<a ng-click="showgrafik(element);" style="cursor: pointer;"> {{element.no}} {{element.nama}}</a>
						</td>
						<td>{{element.nilai | number}} {{cekparent(element.id,$index)}}<span ng-show="element.nilai == null">
                          n/a
                        </span></td>
						<td>{{element.satuan}}</td>
						<td>{{element.update | tanggal}}</td>
					</tr>

					

				</tbody>
			</table>
			<!-- data sipd -->


			<ul class="message no-margin infomation" ng-if="elements !=null && tabel !=false">
				<li>MENAMPILKAN DATA {{judul}}, JUMLAH DATA : {{elements.length}}</li>
			</ul>


			<div class="block-footer infomation" ng-show="elements !=null && tabel !=false">
					
					
				&nbsp;
			</div>


		</div>


		<!-- pilih kategori -->
		<ul class="message no-margin" ng-repeat="kategori in sipd">
			<li ng-click="pil_kat(kategori)">{{kategori.nama}}</li>
		</ul>

		<!-- end pilih kategori -->

		<!-- grafik -->
			<div ng-if="grafik_el && grafik_op" style="padding-top: 40px">
	            <span my-grafik3="ganti" ></span>         
          	</div>
		<!-- end Grafik -->


		
	</form>
	
	<div id="tab-comments" class="with-margin">
		<!-- Content is loaded dynamically at bottom in the javascript section -->
	</div>
	
	
	
	
	
</div></div>