'use strict';
aplikasi.controller('main',function($scope, user,$location,$rootScope){
	$scope.isCollapsed = true;
	$scope.profile_opd = {};

	user.load_opd().then(function (response) {
    	$scope.profile_opd = response;
  	})
	user.profile(function(response) { 
	    $scope.profile = response.data;
	    //$rootScope.profile = $scope.profile;
	    //console.log(response.data);
	});
	$scope.selectedmenu = null;
	
	user.menu(function(response) { 
	    $scope.menus = response.data;
	});



	$scope.selectedparent = function (menu) {
		$scope.selectedmenu = menu; 
	}
        $scope.loading = true;
})
.controller('home', function($scope,breadcumb,$rootScope,user){
	$rootScope.breadcumb = breadcumb;


	$scope.gantithn = function () {
		$rootScope.tahun = $scope.grfk.tahun
	}
	user.history().then(function (response) {
		$scope.logs= response;	
	})
	$scope.grfk = {
						tahun : thn_data,
                     };
	$scope.tahuns= tahun();

	user.data_group(thn_data ).then(function(response){
		$scope.groups =response;
	});

})
.controller('kategori', function($scope, kategori,$routeParams,$location,breadcumb,$rootScope){
	$routeParams.parent = 0;
	$rootScope.breadcumb = breadcumb;
	$scope.breadcumb1 = 0;
	$scope.katpilih = function (kat) {
		$scope.breadcumb1 = 1;
		$scope.curent_kat=kat;
		if ($routeParams.parent == 0) {
			$routeParams.parent = kat.id;
			kategori.kategori(function (response) {
				$scope.listkategori = response.data;
			})
		}else{
			$location.path('/inputdata/element/'+kat.id)
		}
	}

	kategori.kategori(function (response) {
			$scope.listkategori = response.data;
	})

	$rootScope.$on('$viewContentLoading', function(event, viewConfig)
	{

		allert('belum');
	  // code that will be executed ... 
	  // before the view begins loading
	});

})
.controller('element', function($scope, element,$routeParams,$location,$uibModal,SweetAlert,breadcumb,FileUploader,$rootScope){
	$rootScope.breadcumb = breadcumb;
	var nilai = new Array();
	var anak = new Array();
	var i = 0;
	$scope.edit_on = false;
	$scope.pnilai = 1;
	$scope.thn_data = tahun();
	$scope.pil_thn= thn_data;
	$scope.posisi = $routeParams;

	$scope.element_input = new Array();

	// declare file upload
	var uploader = $scope.uploader = new FileUploader({
            url: 'excel/upload',
            autoUpload :true
    });
   
	$scope.upload = function () {
		var coba = angular.element(document.getElementById('upload').click());
	}

	$scope.edit = function (index) {
		$scope.undo[index]=angular.copy($scope.edit_element[index]);
	}

	$scope.cancel = function (index) {
		$scope.edit_element[index] =  $scope.undo[index];
		//console.log($scope.edit_element[index]);
	}

	$scope.publish = function (index) {
		$scope.element[index].publish = 1;
		$scope.element[index].edited = true;
		changePublish($scope.element[index].id,1);
	}
	
	$scope.unpublish = function (index) {
		$scope.element[index].publish = 0;
		$scope.element[index].edited = true;
		changePublish($scope.element[index].id,0);
	}

	var changePublish = function (id,publish) {
		angular.forEach($scope.element, function(value, key) {
			if (id == value.id_parent) {
				$scope.element[key].publish = publish;
				$scope.element[key].edited = true;
				changePublish($scope.element[key].id,publish);
			}
		})
	}

	$scope.inputdata_change = function (index,data_input) {
		var angka = data_input.replace(/\./g, "");
		angka = angka.replace(',','.');
		angka = parseFloat(angka);
		$scope.element[index].nilai=angka;
	}

    uploader.onSuccessItem  = function(fileItem, response, status, header) {
    	if (response.status == "success") {
			$scope.preview(response);
    	}else{
    		gagal(response.error,'Error');
    	}
    };

    $scope.preview= function (dataExcel) {
		var modalInstance = $uibModal.open({
	      	templateUrl:'print.html',
	      	controller: 'preview',
	      	backdrop : 'static',
	      	animation : true,
	      	openedClass : 'modal-open-noscroll',
	      	size :'print',
	      	resolve: {
						params: function(){
							return {
								lokasi : dataExcel,
								dataElement :$scope.element ,
								kategori :$scope.Pilih_kat
							};
						}
					}
	      	
	      	
	    });
	    modalInstance.result.then(function () {
		  
		}, function () {
			$scope.reload();
		  	//cope.update_kat();
		  	//console.log('simpan');
		});
	}
	$scope.salin= function () {
		var modalInstance = $uibModal.open({
	      	templateUrl:'salidData.html',
	      	controller: 'salinData',
	      	backdrop : 'static',
	      	animation : true,
	      	openedClass : 'modal-open-noscroll',
	      	size :'print',
	      	resolve: {
						params: function(){
							return {
								id_kat: $scope.Pilih_kat.id,
								nama : $scope.Pilih_kat.nama,
								tahun : $scope.pil_thn
							};
						}
					}
	      	
	      	
	    });
	    modalInstance.result.then(function () {
		}, function () {
			$scope.reload();
		});
	}
	$scope.reload = function () {
		element.element(function (response) {
		 	$scope.element = response.data;
		 	// conver number format
		 	$scope.element_input = new Array();
		 	angular.forEach(response.data, function(data_elemen, key) {
		 		var str = ''+data_elemen.nilai;
			 	$scope.element_input[key]= {'nilai':str.replace('.',',')};
			});
		})
	}

	var sukses = function (pesan, judul) {
		SweetAlert.swal(judul, pesan, "success");
	}

	var gagal = function (pesan,judul) {
		SweetAlert.swal(judul, pesan, "error");
	}

	// select 2 option
	$scope.select2Options = {
        tags: "true",
  		theme: "bootstrap"
    };
	//$scope.kategori;

	//get skpd
	element.getskpd(function (response) {
		$scope.skpd = response.data;
	})

	// ambil data element
	// element.element(function (response) {
	// 	$scope.element = response.data;
	// })

	// fungsi ganti tahun
	$scope.change_thn= function (tahun) {
		$scope.load_el = false;
		$routeParams.tahun = $scope.pil_thn;
		element.element(function (response) {
			$scope.element = response.data;

			$scope.element_input = new Array();
		 	angular.forEach(response.data, function(data_elemen, key) {
		 		var str = ''+data_elemen.nilai;
			 		$scope.element_input[key]= {'nilai':str.replace('.',',')};
			});

			$scope.load_el = true;	


		})

	}

	$scope.chekbok =function ($value) {
	 	if ($value == 1) {
	 		return true;
	 	}else{
	 		return false;
	 	}
	}

	//ambil data jenis
	element.jenisdata(function (response) {
		$scope.jenisdata = response.data;
	})

	$scope.to_excel = function () {

	}

	$scope.update_jenis = function () {
		$scope.edit_element = null;
		$routeParams.jenisdata = $scope.Pilih_jenis;
		element.kategori(function (response) {
			$scope.kategori = response.data;
		});
	}

	$scope.update_kat =function () {
		$scope.load_el = false;
		$routeParams.id_kat = $scope.Pilih_kat.id;
		//console.log('masuk');

		element.element(function (response) {
			// Halaman
		 	//$scope.elementdata = cek_data(response.data);
		 	//$scope.element = $scope.elementdata[$scope.currentPage];
		 	//end Halaman

		 	//single data
		 	$scope.element = response.data;
		 	// end single data

		 	$scope.element_input = new Array();
		 	angular.forEach(response.data, function(data_elemen, key) {
		 		
		 		var str = ''+data_elemen.nilai;
		  		$scope.element_input[key]= {'nilai':str.replace('.',',')};
			});

		 	$scope.load_el = true;
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

 	$scope.pageChanged = function() {
 		$scope.edit_element = $scope.elementdata[$scope.currentPage];
  	};

	// end pagination

	$scope.hitungpanjang = function (item) {
		if (item !=null) {
			if (item.length > $scope.pnilai ) {
				$scope.pnilai = item.length
			}
			return (item.length)*12;
		}
	}

	$scope.cekparent = function (id,index) {
		angular.forEach($scope.element, function(value, key) {
			// if ((''+value.nilai).length < karakter) {
			// 	karakter  = (value.nilai).length;
			// }
			
			if (value.id_parent == id && value.nilai != null) {
				if (i == 0 ) {
					$scope.element[index].nilai = Number(value.nilai);
					i=1;
				}else{
					$scope.element[index].nilai = Number($scope.element[index].nilai)+Number(value.nilai);
				}
				return 'parent';
			}
		});
		//$scope.karakter = karakter;
		i=0;
	}

	$scope.tampil = function (data) {
		angular.forEach($scope.element, function(value, key) {
			value.editing = false;
			value.unit_edit = false;
		});
		data.editing =true;
		data.unit_edit =false;
		$('#edit_nilai'+data.id).focus(0);
	}

	$scope.simpan = function () {
		var save_Edited = $scope.element.filter(function(dosave) {
		  	if(dosave.edited) {
		    	return dosave;
		  	};
		});
		
		$rootScope.saving = true;
		element.simpan_elm(save_Edited).then(function(response){
			//console.log(response);
			if (response.status) {
				sukses('Data Berhasil di simpan','Berhasil');
			}else if (response.status == false) {
				gagal("Terjadi Kesalahan Dalam Pengisian Data",'Error')
			}else{
				gagal("Tidak ada yang di simpan karena tidak data perubahan data",'Error')
			}
			$rootScope.saving = false;
		});
	}

	$scope.change = function () {
		$scope.edit_on = !$scope.edit_on;
	}

	$scope.show_grafik= function (element) {
		var modalInstance = $uibModal.open({
	      	templateUrl:'grafik.html',
	      	controller: 'grafik',
	      	backdrop : 'static',
	      	animation : true,
	      	openedClass : 'modal-open-noscroll',
	      	size :'fullsize',
	      	resolve: {
						params: function(){
							return {
								element: element,
							};
						}
					}
	      	
	    });
	    modalInstance.result.then(function () {
		  
		}, function () {
		  	$scope.update_kat();
		  	//console.log('simpan');
		});
	}

	


})

.controller('edit_element', function($scope, element,$routeParams,$location,$uibModal,SweetAlert,breadcumb,$rootScope){
	$rootScope.breadcumb = breadcumb;
	var nilai = new Array();
	var anak = new Array();
	var i = 0;
	$scope.edit_on = false;
	$scope.pnilai = 1;
	$scope.thn_data = tahun();
	$scope.semua = false;
	$scope.loadinglockall =false;

	$scope.lock_el = function (id,status,index) {
		$scope.edit_element[index].loading = true;
		element.lock(status,id).then(function (response) {
			if (response.status == 'success') {
				$scope.edit_element[index].lock = !status;
				$scope.edit_element[index].loading = false
				//setTimeout(function(){ $scope.edit_element[index].loading = false; }, 500);
			}
		})
	}

	$scope.lockall = function (status) {
		$scope.loadinglockall = true;
		element.lockall(status,$scope.Pilih_kat).then(function (response) {
			if (response.status == 'success') {
				$scope.semua =!status;
				$scope.loadinglockall = false
				//setTimeout(function(){ $scope.edit_element[index].loading = false; }, 500);
			}
		})
		angular.forEach($scope.edit_element, function(value, key){
		     $scope.edit_element[key].lock = !status;
		});
	}

	/*var cek_el = function (id,status) {
		var cek_data = new array({id:id});
		angular.forEach($scope.element, function(cek, key) {
		  if (cek_data[cek.id]!= undefined || cek_data[cek.parent]!= undefined ) {
		  	$scope.element[key].lock= !status;
		  	cek_data[cek.id]=cek.id;
		  }
		});
	}*/

	var sukses = function (pesan, judul) {
		SweetAlert.swal(judul, pesan, "success");
	}

	var gagal = function (pesan,judul) {
		SweetAlert.swal(judul, pesan, "error");
	}

	$scope.edit = function (index) {
		$scope.undo = new Array();
		$scope.undo[index]=angular.copy($scope.edit_element[index]);
	}

	$scope.cancel = function (index) {
		$scope.edit_element[index] =  $scope.undo[index];
	}


	// select 2 option
	$scope.select2Options = {
        tags: "true",
  		theme: "bootstrap"
    };
	//$scope.kategori;

	//get skpd
	element.getskpd(function (response) {
		$scope.skpd = response.data;
	})

	
	// fungsi ganti tahun
	$scope.change_thn= function (tahun) {
		$routeParams.tahun = $scope.pil_thn;
		element.element(function (response) {
			$scope.element = response.data;
		})

	}

	$scope.chekbok =function ($value) {
	 	if ($value == 1) {
	 		return true;
	 	}else{
	 		return false;
	 	}
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
		element.getkat(function (response) {
			$scope.load_el = true;

			// bila pake halaman.. masih ngebut
			$scope.elementdata = cek_data(response.data);
			$scope.edit_element = $scope.elementdata[$scope.currentPage];
			// end halaman

			$scope.edit_element = response.data;
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
		}else if (qelement=="null") {
			halaman[1]= [];

		}else{
			halaman[1]= qelement;
		}
		$scope.totalItems = page;
		return halaman
	}

 	$scope.pageChanged = function() {
 		$scope.edit_element = $scope.elementdata[$scope.currentPage];
  	};

	// end pagination

	
	$scope.hitungpanjang = function (item) {
		if (item !=null) {
			if (item.length > $scope.pnilai ) {
				$scope.pnilai = item.length
			}
			return (item.length)*12;
		}
	}


	$scope.tampil = function (data) {
		angular.forEach($scope.element, function(value, key) {
			value.editing = false;
			value.unit_edit = false;
		});
		data.editing =true;
		data.unit_edit =false;
		$('#edit_nilai'+data.id).focus(0);
	}

	$scope.unit_tmpl = function (data) {
		angular.forEach($scope.element, function(value, key) {
			value.unit_edit = false;
			value.editing = false;
		});
		data.editing =false;
		data.unit_edit =true;
		$('#edit_nilai'+data.id).focus(0);
	}


	$scope.simpan = function () {
		var save_Edited = $scope.element.filter(function(dosave) {
		  	if(dosave.edited) {
		    	return dosave;
		  	};
		});
		element.simpan_elm(save_Edited).then(function(response){
			if (response.status) {
				sukses('Data Berhasil di simpan','Berhasil');
			}else if (response.status == false) {
				gagal("Terjadi Kesalahan Dalam Pengisian Data",'Error')
			}else{
				gagal("Tidak ada yang di simpan karena tidak data perubahan data",'Error')
			}
		});
	}

	$scope.change = function () {
		$scope.edit_on = !$scope.edit_on;
	}

	$scope.hapus = function (dohapus,index) {
		
	}

	$scope.hapus_el = function (dohapus,index) {
		SweetAlert.swal({
		   	title: "Apakah Anda Yakin",
		   	text: "Data yang Dalam Kategori ini akan di hapus dari database",
		   	type: "warning",
		   	showCancelButton: true,
		   	confirmButtonColor: "#DD6B55",
		   	confirmButtonText: "Yes, delete it!",
		   	closeOnConfirm: false}, 
			function(isConfirm){ 
				if (isConfirm) {
					element.hapus(dohapus).then(function (response) {
						if (response.status == 'success') {
							$scope.update_kat();
						}
					});
			   		SweetAlert.swal("Data Berhasil Di hapus");
				}
				
			});
	}

	
	$scope.edit_el = function (hasil,index) {
		if (hasil.nama.length < 3) {
			$scope.edit_element[index].minlength =false;
			return true;
		}
		element.up_el(hasil);
		$scope.edit_element[index].minlength =true;
		return false;
	}

	$scope.kelola = function (kelola,edit,id) {
		var modalInstance = $uibModal.open({
	      	templateUrl:'modal.html',
	      	controller: 'ModalInstanceCtrl',
	      	appendTo :$('#modalsub'),
	      	backdrop : 'static',
	      	animation : true,
	      	size :'lg',
	      	resolve: {
						params: function(){
							return {
								title: kelola,
								edit : edit,
								id : id
							};
						}
					}
	    });
	    modalInstance.result.then(function () {
		  
		}, function () {
		  	$scope.update_kat();
		  	//console.log('simpan');
		});
	};
	
	$scope.sub = function (tambah,index) {
		var modalInstance = $uibModal.open({
	      	templateUrl:'sub.html',
	      	controller: 'tambahsub',
	      	appendTo :$('#modalsub'),
	      	backdrop : 'static',
	      	animation : true,
	      	size :'md',
	      	resolve: {
						params: function(){
							return {
								parent: tambah,
								kat : $scope.Pilih_kat.id,
								index : index,
								skpd : $scope.skpd
							};
						}
					}
	    });
	    modalInstance.result.then(function (hasil) {
	    	if (hasil.edit == true) {
	    		element.up_el(hasil)
	    	}else{
	    		element.tmb_el(hasil).then(function (response) {
	    			if (response.status == "success") {
	    				sukses('Data Berhasil di simpan','Berhasil');
	    				$scope.update_kat();	
	    			}else{
	    				gagal(response.error,'Error')
	    			}
	    			
	    		});
	    	}
	    	
		});
	}
	
})

.controller('users', function($scope, user, $uibModal, SweetAlert,breadcumb,$rootScope){
	$rootScope.breadcumb = breadcumb;
	var sukses = function (pesan, judul) {
		SweetAlert.swal(judul, pesan, "success");
	}

	var gagal = function (pesan,judul) {
		SweetAlert.swal(judul, pesan, "error");
	}

	user.users(function(response) { 
	    $scope.users = response.data;
	});
	user.groups(function(response) { 
	    $scope.usergroups = response.data;
	});

	$scope.hps_user = function (data_user,index) {
		SweetAlert.swal({
		   title: "Apakah Anda Yakin",
		   text: "User Yang Telah Di Hapus Tidak Akan Bisa Di Kembalikan Kembali",
		   type: "warning",
		   showCancelButton: true,
		   confirmButtonColor: "#DD6B55",confirmButtonText: "Hapus",
		   cancelButtonText: "Tidak",
		   closeOnConfirm: false,
		   closeOnCancel: false }, 
		function(isConfirm){ 
		   	if (isConfirm) {
			   	user.del_user(data_user).then(function(response){
					if (response.status == "success") {
				      	sukses('User '+data_user.username+' Berhasil di Hapus', 'Berhasil');
				      	$scope.users.splice(index,1);
					}else{
						gagal(response.message, 'Error')
					}
				});
		   	}else {
		      	gagal('Penghapusan User '+data_user.username+' Dibatalkan', 'Dibatalkan')
		   	}
		});
	}

	$scope.hps_group = function (data_group,index) {
		SweetAlert.swal({
		   title: "Apakah Anda Yakin",
		   text: "Group Yang Telah Di Hapus Tidak Akan Bisa Di Kembalikan Kembali",
		   type: "warning",
		   showCancelButton: true,
		   confirmButtonColor: "#DD6B55",confirmButtonText: "Hapus",
		   cancelButtonText: "Tidak",
		   closeOnConfirm: false,
		   closeOnCancel: false }, 
		function(isConfirm){ 
		   	if (isConfirm) {
			   	user.del_group(data_group).then(function(response){
					if (response.status == "success") {
				      	sukses('Group '+data_group.name+' Berhasil di Hapus', 'Berhasil');
				      	delete $scope.usergroups[index];
				      	//$scope.usergroups[index]={};
					}else{
						gagal(response.message, 'Error')
					}
				});
		   	}else {
		      	gagal('Penghapusan User '+data_group.name+' Dibatalkan', 'Dibatalkan')
		   	}
		});
	}


	// edit user modal show
	$scope.edit_user=function (user) {
		var modalInstance = $uibModal.open({
	      	templateUrl:'add_user.html',
	      	controller: 'user_management',
	      	appendTo :$('#modalsub'),
	      	backdrop : 'static',
	      	animation : true,
	      	keyboard  : false,
	      	size :'lg',
	      	resolve: {
						params: function(){
							return {
								edit_user: user,
								status :'edit',
								jenis : 'user'
							};
						}
					}
	    });
	    modalInstance.result.then(function (respon) {
		  	if (respon.status = "success") {
		  		if (respon.message == undefined) {
	    			respon.message = "Pembaruan Data User Berhasil"
	    		}
	    		sukses("Berhasil",respon.message);
	    	}else{
	    		sukses("Gagal","Pembaruan Data User Gagal Mohon Hubungi Operator");
	    	}
		});
	};





	$scope.tambahuser = function () {
		var modalInstance = $uibModal.open({
	      	templateUrl:'add_user.html',
	      	controller: 'user_management',
	      	appendTo :$('#modalsub'),
	      	backdrop : 'static',
	      	animation : true,
	      	keyboard  : false,
	      	size :'lg',
	      	resolve: {
						params: function(){
							return {				
								status : 'add',
								jenis : 'user'			
							};
						}
					}
	    });
	    modalInstance.result.then(function (respon) {
	    	if (respon.status = "success") {
	    		var index_user = $scope.users.length;
	    		$scope.users[index_user]= respon.data;
	    		sukses("Berhasil","Penambahan username "+respon.data.username+" Berhasil");
	    	}else{
	    		gagal("Gagal","Penambahan Group '"+respon.data.username+"' Gagal, Mohon Hubungi Operator");
	    	}
		  
		}, function () {
		  	//$scope.update_kat();
		  	//console.log('simpan');
		});
	}


	// tambah group modal 
	$scope.tambah_group=function (user) {
		var modalInstance = $uibModal.open({
	      	templateUrl:'add_group.html',
	      	controller: 'user_management',
	      	appendTo :$('#modalsub'),
	      	backdrop : 'static',
	      	animation : true,
	      	keyboard  : false,
	      	size :'md',
	      	resolve: {
						params: function(){
							return {
								status :'add'
							};
						}
					}
	    });

	    modalInstance.result.then(function (respon) {
	    	if (respon.status = "success") {
	    		$scope.usergroups[respon.data.id]= respon.data;
	    		sukses("Berhasil","Penambahan Group "+respon.data.name+" Berhasil");
	    	}else{
	    		sukses("Gagal","Penambahan Group '"+respon.data.name+"'' Gagal, Mohon Hubungi Operator");
	    	}
		});
	}

	//edit group...
	$scope.edit_group = function (group) {
		var modalInstance = $uibModal.open({
	      	templateUrl:'add_group.html',
	      	controller: 'user_management',
	      	appendTo :$('#modalsub'),
	      	backdrop : 'static',
	      	animation : true,
	      	keyboard  : false,
	      	size :'md',
	      	resolve: {
						params: function(){
							return {
								edit_group: group,
								status :'edit'
							};
						}
					}
	    });
	    modalInstance.result.then(function (respon) {
	    	if (respon.status = "success") {
	    		var index_group = $scope.groups.length;
	    		$scope.groups[index_group]= respon.data;
	    		if (respon.message == undefined) {
	    			respon.message = "Pembaruan Group Berhasil"
	    		}
	    		sukses("Berhasil",respon.message);
	    	}else{
	    		sukses("Gagal",respon.message);
	    	}
		});
	}

})
.controller('user_management', function($scope, $uibModalInstance,user,params,SweetAlert){
	if (params.edit_user) {
		$scope.user = params.edit_user;
		$scope.user.status = params.status;
		$scope.user.change = false;
	}

	if (params.edit_group) {
		$scope.group = params.edit_group;
		$scope.group.status = params.status
	}
	if (params.jenis) {
		user.groups(function(response) { 
	    	$scope.groups = response.data;
		});
	}
	$scope.cancel = function () {
    	$uibModalInstance.dismiss('cancel');
  	};

  	$scope.close = function (balik) {
  		$uibModalInstance.close(balik);
  	}
  	
	$scope.add_user = function() {
		if ($scope.form_user.$valid) {
			user.add_user($scope.user).then(function(response){
				$scope.close(response);
			});
		}
	};

	$scope.tmb_group = function () {
		if ($scope.form_user.$valid) {
			user.add_group($scope.group).then(function(response){
				$scope.close(response);
			});
		}
	}

	$scope.up_group = function () {
		if ($scope.form_user.$valid) {
			user.up_group($scope.group).then(function(response){
				$scope.close(response);
			});
		}
	}

	$scope.reset = function () {
		SweetAlert.swal({
		   title: "Reset Password",
		   text: "Apakah anda yakin mereset password user. Default password adalah username user",
		   type: "warning",
		   showCancelButton: true,
		   confirmButtonColor: "#DD6B55",confirmButtonText: "Reset",
		   cancelButtonText: "Tidak",
		   closeOnConfirm: true,
		   closeOnCancel: true }, 
		function(isConfirm){ 
		   	if (isConfirm) {
		   		user.resetpass($scope.user.username).then(function (response) {
		   			$scope.close(response);
		   		});
		   	}
		});
	}

	$scope.update = function () {
		if ($scope.form_user.$valid) {
			user.update_user($scope.user).then(function(response){
				if (response.status == "taken") {
					SweetAlert.swal("Error", response.message, "error");
					$scope.username = "warning";
					return;
				}else if (response.status == "error"){
					SweetAlert.swal("Error", "Username Sudah Terpakai.", "error");
				}
				$scope.close(response);
			});
		}else{
			$scope.error= true;
		}
	}

  	$scope.valid = function () {
  		if (cek) {
  			$scope.validation = $scope.form_user
  		}
  	}
})
.controller('bridging_aplikasi', function($scope,$uibModal,user,SweetAlert){

	var sukses = function (pesan, judul) {
		SweetAlert.swal(judul, pesan, "success");
	}

	var gagal = function (pesan,judul) {
		SweetAlert.swal(judul, pesan, "error");
	}

	user.api_users(function(response) { 
	    $scope.users = response.data;
	});

	$scope.tambah_api=function () {
		var modalInstance = $uibModal.open({
	      	templateUrl:'add_api.html',
	      	controller: 'crud_api',
	      	appendTo :$('#modalsub'),
	      	backdrop : 'static',
	      	animation : true,
	      	keyboard  : false,
	      	size :'md',
	      	resolve: {
						params: function(){
							return {
								status :'add'
							};
						}
					}
	    });

	    modalInstance.result.then(function (respons) {
	    	if (respons.status = "success") {
	    		sukses("Berhasil","Penambahan Api user "+respons.data.username+" Berhasil");
	    		 $scope.users.push(respons.data);
	    	}else{
	    		sukses("Gagal","Penambahan Api User '"+respons.data.username+"'' Gagal, Mohon Hubungi Operator");
	    	}
		});
	}

	$scope.hps_user = function (data_user,index) {
		SweetAlert.swal({
		   title: "Apakah Anda Yakin",
		   text: "Api Yang Telah Di Hapus Tidak Akan Bisa Di Kembalikan Kembali",
		   type: "warning",
		   showCancelButton: true,
		   confirmButtonColor: "#DD6B55",confirmButtonText: "Hapus",
		   cancelButtonText: "Tidak",
		   closeOnConfirm: false,
		   closeOnCancel: false }, 
		function(isConfirm){ 
		   	if (isConfirm) {
			   	user.del_user(data_user).then(function(response){
					if (response.status == "success") {
				      	sukses('User '+data_user.username+' Berhasil di Hapus', 'Berhasil');
				      	$scope.users.splice(index,1);
					}else{
						gagal(response.message, 'Error')
					}
				});
		   	}else {
		      	gagal('Penghapusan User '+data_user.username+' Dibatalkan', 'Dibatalkan')
		   	}
		});
	}


})

.controller('crud_api', function($scope,params,user,$uibModalInstance){
	
	$scope.user = {};

	if (params.edit) {
		$scope.user = params.edit_user;
		$scope.user.status = params.status;
		$scope.user.change = false;
	}
	
	user.groups(function(response) { 
	    $scope.groups = response.data;
	});

	$scope.random = function(L) {
		var s = '';
		var randomchar = function() {
		    var n = Math.floor(Math.random() * 62);
		    if (n < 10) return n; //1-10
		    if (n < 36) return String.fromCharCode(n + 55); //A-Z
		    return String.fromCharCode(n + 61); //a-z
		}
		while (s.length < L) s += randomchar();
		$scope.user.key = s;
	}

	$scope.random(25);

	$scope.add_user = function() {
		
		$scope.user.is_key=1;

		if ($scope.form_user.$valid) {
			//console.log($scope.user);
			user.add_user($scope.user).then(function(response){
				$scope.close(response);
			});
		}
	};

	$scope.close = function (balik) {
  		$uibModalInstance.close(balik);
  	}

  	$scope.cancel = function () {
  		$uibModalInstance.close();
  	}
})

.controller('bridging_element', function($scope,$uibModal,user,element,$routeParams,$rootScope,SweetAlert){
	
	user.api_groups(function (response) {
		$scope.api_groups = response.data;
	})
	//$scope.element = {};
	//	$scope.kategori ={};

	$scope.ganti_skpd= function () {
		$scope.pil_skpd;
			$scope.element  = null;
	     	$scope.kategori = null;
	     	$scope.Pilih_kat= null;
	     	$scope.Pilih_jenis = null;

	     if ($scope.pil_skpd.group_id == null) {
	     	return true;
	     }
		//alert(skpd_aktif);
		element.jenisdata_api($scope.pil_skpd.group_id).then(function (response) {
	     	$scope.jenisdata = response;
   		});
	}

	

	$scope.update_jenis = function () {
		$scope.element = null;
		$scope.Pilih_kat= null
		if ($scope.Pilih_jenis == null) {
			return true;
		}
		$routeParams.jenisdata = $scope.Pilih_jenis;
		element.kategori_api($scope.pil_skpd.group_id).then(function (response) {
			$scope.kategori = response;
			
		});
	}

	$scope.update_kat = function () {
		if ($scope.Pilih_kat== null) {
			return true;
		}
		$scope.load_el = false;
		$routeParams.id_kat =  $scope.Pilih_kat.id;
		element.element(function (response) {
		 	$scope.element = response.data;
		 	$scope.load_el = true;
		})
	}

	$scope.chekbok =function ($value) {
	 	if ($value == 1) {
	 		return true;
	 	}else{
	 		return false;
	 	}
	}

	$scope.simpan = function () {
		var save_Edited = $scope.element.filter(function(dosave) {
		  	if(dosave.edited) {
		    	return dosave;
		  	};
		});
		$rootScope.saving = true;
		element.simpan_elm(save_Edited).then(function(response){
			//console.log(response);
			if (response.status) {
				sukses('Data Berhasil di simpan','Berhasil');
			}else if (response.status == false) {
				gagal("Terjadi Kesalahan Dalam Pengisian Data",'Error')
			}else{
				gagal("Tidak ada yang di simpan karena tidak data perubahan data",'Error')
			}
			$rootScope.saving = false;
		});
	}

	$scope.simpan = function () {
		var save_Edited = $scope.element.filter(function(dosave) {
		  	if(dosave.manual === false || dosave.manual === true || dosave.bridging === true || dosave.bridging === false) {
		    	return dosave;
		  	};
		});
		element.simpan_set_api(save_Edited).then(function(response){
			if (response.status) {
				sukses('Data Berhasil di simpan','Berhasil');
			}else if (response.status == false) {
				gagal("Terjadi Kesalahan Dalam Pengisian Data",'Error')
			}else{
				gagal("Tidak ada yang di simpan karena tidak data perubahan data",'Error')
			}
		});
	}

	var sukses = function (pesan, judul) {
		SweetAlert.swal(judul, pesan, "success");
	}

	var gagal = function (pesan,judul) {
		SweetAlert.swal(judul, pesan, "error");
	}
	

	// element.jenisdata_api(function (response) {
	// 	$scope.jenisdata = response.data;
	// })
	
})
