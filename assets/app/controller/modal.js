'use strict';
 aplikasi.controller('grafik', function ($scope, $uibModalInstance, params, kategori,filterFilter,grafik) {
 	$scope.grf_tahun = tahun();
 	// select 2 option
	$scope.select2Options = {
    tags: "true",
		theme: "bootstrap"
  };
  $scope.grfk = {
          				pilSub: new Array,
          				awalThn : thn_data-5,
          				akhirThn : thn_data,
          				diagram :'1',
                  id_kat : params.element.id_kat
          				};
 	var element = params.element;
 	$scope.satuan = element.satuan;
 	$scope.tampil1 = false;
 	$scope.tampil2 = false;
 	$scope.submit = function () {

 		if ($scope.grfk.pilSub  == undefined || $scope.grfk.pilSub.length === 0) {
 			$scope.grfk.pilSub = [{id:element.id,nama:element.nama}];
 		}

 		grafik.datagraph($scope.grfk).then(function(response){
			$scope.datagrafik = response;
			$scope.tampil1 = true;
		});

    switch($scope.grfk.diagram) {
    case '1':
        grafik.graph_batang($scope.grfk).then(function(response){
          $scope.graphoption = response;
          $scope.tampil2 = true;
        });
        break;
    case '2':
        grafik.graph_garis($scope.grfk).then(function(response){
          $scope.graphoption = response;
          $scope.tampil2 = true;
        });
        break;
   
    }
 	}

 	$scope.sembunyi = function () {
 		$scope.tampil1 = false;
 		$scope.tampil2 = false
 	}
 	

 	grafik.select_child(element).then(function(response){
			$scope.sub_select = response;
	});

 	//$scope.grf_tahun_akhir = 
 	$scope.change_thn = function () {
 		$scope.tampil1 = false;
 		$scope.tampil2 = false;
 		var baru_tahun = new Array;
 		for (var i =$scope.grfk.awalThn; i <= thn_data ; i++) {
                baru_tahun[i] = i;
        }
    $scope.select_thnakhir = baru_tahun;

    if ($scope.grfk.akhirThn < $scope.grfk.awalThn) {
      $scope.grfk.akhirThn =$scope.grfk.awalThn;
    }

    return baru_tahun;
 	}
 	$scope.change_thn();
	
  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
    
}).controller('inject_url', function($scope, $uibModalInstance, params,element){

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.validated= function () {
    element.cekapi($scope.datainject.inject_data).then(function (response) {
      var data_api = parseInt(response);
      if (isNaN(data_api)) {
        $scope.status_validasi= " data gagal. Cek kembali Api";
      }else{
        $scope.status_validasi = " data berhasil";
        $scope.validate= true;
      }
    });
  }

  $scope.simpan = function(){
    element.simpanapi($scope.datainject).then(function (response) {
      $scope.datainject
    })
  }

  $scope.datainject = params.data;
})
.controller('inject_data', function($scope, element,$routeParams,$location,$uibModal,SweetAlert,breadcumb,$rootScope){

  $rootScope.breadcumb = breadcumb;

  $scope.thn_data = tahun();
  $scope.pil_thn = thn_data;
  var sukses = function (pesan, judul) {
    SweetAlert.swal(judul, pesan, "success");
  }

  var gagal = function (pesan,judul) {
    SweetAlert.swal(judul, pesan, "error");
  }

  //ambil data jenis
  element.jenisdata(function (response) {
    $scope.jenisdata = response.data;
  })

  $scope.update_jenis = function () {
    $scope.edit_element = [];
    //$scope.edit_element.splice(0,$scope.edit_element.length)
    $routeParams.jenisdata = $scope.Pilih_jenis;
    element.kategori(function (response) {
      $scope.kategori = response.data;
    });
    //console.log($scope.kategori);
  } 

  $scope.update_kat =function () {
    $scope.edit_element = []; 
    $scope.load_el = false;
    $routeParams.kategori = $scope.Pilih_kat;
    $routeParams.tahun = $scope.pil_thn
    element.get_inject(function (response) {
      $scope.load_el = true;
      $scope.elementdata = cek_data(response.data);
      $scope.edit_element = $scope.elementdata[$scope.currentPage];
    })
  }

  // pagination
  $scope.currentPage = 1;
  var jml_perpage = 50;
  var hitung=0;
  var halaman = new Array();
  var item = new Array;
  var cek_data = function (qelement) {
    var page =1;
    if (qelement.length > jml_perpage) {
      if (!halaman[page]) {
          halaman[page]= new Array();
        }
      for (var i = 0; i < qelement.length; i++) {
        
        if (hitung >= 10 ) {
          if (qelement[i].sub ==1) {
            page++;
            halaman[page]= new Array();
            hitung=0;
            halaman[page].splice(i,0,qelement[i]) ;
          }else{

            halaman[page].splice(i,0,qelement[i]) ;

          }
        }else{
          halaman[page].splice(i,0,qelement[i]) ;
        }
        hitung++;
      }
    }else{
      halaman[1]= qelement;
    }
    $scope.totalItems = page;
    return halaman
  }

  $scope.injectdata = function (inject) {
    var modalInstance = $uibModal.open({
          templateUrl:'inject.html',
          controller: 'inject_url',
          appendTo :$('#modalsub'),
          backdrop : 'static',
          animation : true,
          size :'md',
          resolve: {
            params: function(){
              return {
                data: inject
              };
            }
          }
      });
      modalInstance.result.then(function (hasil) {
        
        
    });
  }


})
.controller('ModalInstanceCtrl', function ($scope, $uibModalInstance, params, kategori,filterFilter,element,SweetAlert) {

  $scope.title = params.title;  
  $scope.edit = params.edit;
  var next_id = $scope.edit.length;
  var sukses = function (pesan, judul) {
    SweetAlert.swal(judul, pesan, "success");
  }

  var gagal = function (pesan,judul) {
    SweetAlert.swal(judul, pesan, "error");
  }

  $scope.hps_kat = function (dohapus,index) {
    SweetAlert.swal({
        title: "Apakah Anda Yakin",
        text: "Data yang Dalam Kategori ini akan di hapus dari database",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Hapus",
        closeOnConfirm: false}, 
      function(isConfirm){ 
        if (isConfirm) {
          kategori.hapus(dohapus).then(function (response) {
            if (response.status == 'success') {
              $scope.edit.splice(index,1);
            }
          });
            SweetAlert.swal("Data Berhasil Di hapus");
        }
        
      });
  }
  $scope.hps_el = function (dohapus,index) {
    SweetAlert.swal({
        title: "Apakah Anda Yakin",
        text: "Data yang Dalam Kategori ini akan di hapus dari database",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Hapus",
        closeOnConfirm: false}, 
      function(isConfirm){ 
        if (isConfirm) {
          element.hapus(dohapus).then(function (response) {
            if (response.status == 'success') {
              $scope.edit.splice(index,1);
            }
          });
            SweetAlert.swal("Data Berhasil Di hapus");
        }
        
      });
  }

  if (params.title == 'Element') {
    $scope.edit = filterFilter(params.edit,{sub :'1'})
  }
  $scope.ok = function () {
    $uibModalInstance.close('ok');
  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.editdata = function (index) {
    $scope.undo = new Array();
    $scope.undo[index] = angular.copy($scope.edit[index]);
  }

  $scope.batalEdit = function (index) {
    $scope.edit[index] = $scope.undo[index];
  }

  $scope.simpan_kat = function (newkat) {
    if (newkat ==undefined ) {
      gagal('Data belum ada yang di isi','Gagal');
      return;
         
    }
     newkat.id_parent = params.id;
      //  console.log(newkat.id != "" || newkat.id != undefined);

    if (newkat.nama.length < 2) {
      gagal('Field nama minimal 3 karakter','Gagal');
      return;
    }else if(newkat.id ==""){
      console.log('id kosong');
    }else if(!(newkat.id == parseInt(newkat.id, 10)) && (newkat.id != undefined)){
      gagal('Field Id Harus Bilangan Bulat','Gagal');
      return
    }else if(newkat.nama == undefined){
      gagal('Field nama wajib di isi','Gagal');
      return;
    }

    kategori.newkat(newkat).then(function (response) {
      if (response.status == 'success') {
        sukses('Data berhasil Ditambahkan','Berhasil');
        $scope.edit.splice(next_id,0,response);
        $scope.tambah = false;
        next_id++;
      }else{
        gagal(response.error,'Gagal');
      }
      
    });
  }

  $scope.update = function (up,edited,awal,index) {
    if (up.nama.length <2) {
      gagal('Field nama minimal 2 karakter','Gagal');
      $scope.edit[index].id = awal;
      $scope.edit[index].edit = true;
      return;
    }else if(!(up.id == parseInt(up.id, 10))){
      gagal('Field Id Harus Bilangan Bulat','Gagal');
      $scope.edit[index].id = awal; 
      $scope.edit[index].edit = true;
      return
    }
    up.awal = awal;
    if (edited) {
      if (params.title == 'Element') {
        element.up_el2(up).then(function (response) {
          if (response.status == 'success') {
            $scope.hasil = true;
            sukses('Data berhasil diperbarui','Berhasil');
            $scope.edit[index].edit = false;
          }else{
            gagal(response.error,'Gagal');
            $scope.edit[index].id = awal;
            $scope.edit[index].edit = true;
          }
        });
      }else{
        kategori.upkat(up).then(function (response) {
            if (response.status == 'success') {
              $scope.hasil = true;
              sukses('Data berhasil diperbarui','Berhasil');
              $scope.edit[index].edit = false;
            }else{
              gagal(response.error,'Gagal');
              $scope.edit[index].id = awal;
              $scope.edit[index].edit = true;
            }
          });
      }
    }else{
      $scope.edit[index].edit = false;
    }
  
  };

  var up_kat = function (up_kat,awal,index) {
    
  }

  var up_el = function (up_el) {
    element.up_el(up_el);
  }

  $scope.simpan_el = function (new_el) {
    new_el.parent = 0;
    new_el.kat = params.id.id;
    if (new_el.nama.length <2) {
        gagal('Field nama minimal 2 karakter','Gagal');
        return;
    }
    //else if(!(new_el.id == parseInt(new_el.id, 10))){
      //gagal('Field Id Harus Bilangan Bulat','Gagal');
      //return
   // }

    element.tmb_el(new_el).then(function (response) {
      if (response.status == 'success') {
        $scope.edit.splice(next_id,0,response);
        $scope.tambah = false;
        next_id++;
        sukses('Data berhasil diperbarui','Berhasil');
      }else{
        gagal(response.error,'Gagal');
      
      }
    });
  }
})

.controller('tambahsub', function($scope, $uibModalInstance, params){
  
  var data_el = function () {
    return {
      parent : params.parent.id,
      kat : params.kat,
      nama : params.edit,
      id : ''
    }
  }
  $scope.skpd = params.skpd;

  $scope.hasil = data_el();
  $scope.simpan = function () {
    if ($scope.formsub.$valid) {
      $uibModalInstance.close($scope.hasil);
    }
    //console.log($scope.formsub.$valid);
  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.edit = function (index) {
    $scope.undo = new Array();
    $scope.undo[index]=angular.copy($scope.edit_element[index]);
  }

  // $scope.cancel = function (index) {
  //   $scope.edit_element[index] =  $scope.undo[index];
  // }
})

.controller('profile', function($scope,profile,breadcumb,$rootScope,SweetAlert){
  $scope.menu_user = base_url('admin_view/overview');
  $scope.bulan = bulan;
  $scope.password ={};
  $scope.formpass ={};   
  $scope.formpass ={};    
  
  var sukses = function (pesan, judul) {
    SweetAlert.swal(judul, pesan, "success");
  }

  var gagal = function (pesan,judul) {
    SweetAlert.swal(judul, pesan, "error");
  }


  profile.activity().then(function(response) { 
      $scope.activity = response;
  });

  $scope.save_profile = function (dataprofile) {
    profile.changeuser(dataprofile).then(function (response) {
      if (response.status =="success") {
        sukses('Data profile berhasil di update','Berhasil')
      }else{
        gagal('Gagal mengupdate profile','Gagal');
      }
    });
  }

  $scope.save_opd = function (data_opd ) {
    profile.update_opd(data_opd).then(function (response) {
      if (response.status =="success") {
        sukses('Data profile OPD berhasil di update','Berhasil')
      }else{
        gagal('Gagal mengupdate profile','Gagal');
      }
    })
    
  }
 
  

  $scope.verifikasi = function (element) {
    if (element.new == element.verif) {
      $scope.passvalid == true;
    }else{
      $scope.passvalid == false;
    }
  }

  $scope.gantipass = function (formvalid,password) {
    if (formvalid.$valid != true || $scope.passvalid == false) {
      if ($scope.passvalid == false) {
        gagal('Password yang di masukkan tidak sama','Gagal');
      }else{
        gagal('Password harus lebih dari 8 karakter','Gagal');
      }
      return;
    }
    profile.gantipass(password).then(function (response) {
      if (response.status =="success") {
        sukses(response.message,'Berhasil');
      }else{
        gagal(response.message,'Gagal');
      }
      $scope.password= {};
    });
  }
  $rootScope.breadcumb = breadcumb;
})
.controller('preview', function($scope,params,element,SweetAlert,$uibModalInstance){
    //cope.excels= params;
  //console.log(params.dataElement);


  $scope.excels = new Array();
  $scope.keterangan = new Array();
  $scope.dataElement = new Array();
  $scope.status = new Array();
  $scope.kategori = params.kategori;
  var infofile = params.lokasi;
  var jumlahdataasli = 0;
  angular.forEach( params.dataElement, function(value,key) {
    jumlahdataasli++;
    $scope.dataElement [value.id] =  'ada';
    $scope.dataElement [value.id+value.id_parent] =  'ada';
  });


  //
   var sukses = function (pesan, judul) {
    SweetAlert.swal(judul, pesan, "success");
  }

  var gagal = function (pesan,judul) {
    SweetAlert.swal(judul, pesan, "error");
  }

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };



  $scope.cekdata = function (iddata,parent,index,$uibModalInstance) {
    
    if ($scope.dataElement[iddata]==undefined || $scope.dataElement[iddata+(parent.toString())]==undefined) {
       $scope.status[index]='true';
       $scope.error = 'ID data tidak sama'
    }
    return iddata;
  }
  element.importdata(infofile).then(function (response) {
    angular.forEach(response, function(value, key) {
      if (key < 8) {
        $scope.keterangan[key] = value;
      }else{
        $scope.excels.push(value);
        if (isNaN(value.E)) {
          $scope.status[key-8]='true';
           $scope.error2 = 'Kolom nilai harus angka.'
        }
      }
    });
    if ($scope.excels.length != jumlahdataasli) {
      $scope.status[$scope.excels.length]='true';
      $scope.error = 'Jumlah data di excel tidak sama dengan data di dalam database.'
    }

    if ($scope.kategori.id != response[4].C) {
       $scope.status['jenis']='true';
    }

    $scope.modalLoading = false;
  })
  
  $scope.simpan = function () {
    $scope.simpanload = true;
    var excelsimpan = new Array();
    excelsimpan['keterangan'] =  $scope.keterangan;
    excelsimpan['excel'] =   $scope.excels;
    //excelsimpan['infofile'] =   infofile;

    //console.log(excelsimpan);

    element.simpanImport(infofile).then(function (response) {
         $scope.simpanload = false;
         if (response.status == 'success') {
          sukses('Data Berhasil Tersimpan','Berhasil');
         }else{
          Gagal(error,'Error');
         }
    });
  }
})
.controller('salinData', function($scope,params,element,$routeParams,SweetAlert,$uibModalInstance,$rootScope){
  $routeParams.tahun  = thn_data-1 ;
  $scope.jenisElement = params.nama;
  $routeParams.id_kat = params.id_kat;
  $scope.ketersediaan = ketersediaan;
  $scope.tahuntarget = params.tahun;
  $scope.thn_data = tahun();
  $scope.pil_thn = thn_data-1;
  $scope.cekAllValue = false;
  $scope.thn_awal = angular.copy(params.tahun);


  var sukses = function (pesan, judul) {
    SweetAlert.swal(judul, pesan, "success");
  }

  var gagal = function (pesan,judul) {
    SweetAlert.swal(judul, pesan, "error");
  }

   $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };


  element.element(function (response) {
      //single data

      $scope.elements = response.data;
  })

  $scope.simpan = function () {
    $scope.simpanload = true;
    var simpan= $scope.elements.filter(function (dosave) {
      if (dosave.cek) {
        return dosave.id;
      }
    })
    element.salinData(simpan,$scope.tahuntarget).then(function (response) {
       $scope.simpanload = false;
      if (response.status =='success') {
        $routeParams.tahun = $scope.thn_awal;
        sukses('Data Berhasil di salin','Berhasil');
      }else{
        gagal('gagal','data Gagal Di Salin');
        $routeParams.tahun = $scope.thn_awal;
      }
    })

  }

  $scope.change_thn= function (tahun) {
    $routeParams.tahun = $scope.pil_thn;
    element.element(function (response) {
      $scope.elements = response.data;
    })
  }
  $scope.cekAll = function () {
    if ($scope.cekAllValue ==true) {
      angular.forEach( $scope.elements, function(value, key) {
         $scope.elements[key].cek = 1;
        //if (elements[key].cek == undefined) {}
      });
    }else{
      angular.forEach( $scope.elements, function(value, key) {
         $scope.elements[key].cek = 0;
        //if (elements[key].cek == undefined) {}
     });
    }
     
  }

})