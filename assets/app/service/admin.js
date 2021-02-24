'use strict';

aplikasi.service('user', function($http){
	this.profile = function(callback){
	    $http.get(base_url('pusatdata/user'))
	    .then(callback);
	}; 
	this.menu = function (callback) {
		$http.get(base_url('pusatdata/menu'))	
		.then(callback);
	};
	this.users = function (callback) {
		$http.get(base_url('pusatdata/users'))	
		.then(callback);	
	}
	this.groups = function (callback) {
		$http.get(base_url('pusatdata/groups'))	
		.then(callback);	
	}

	this.api_users = function (callback) {
		$http.get(base_url('pusatdata/api_users'))	
		.then(callback);	
	}
	this.api_groups = function (callback) {
		$http.get(base_url('pusatdata/api_skpd'))
		.then(callback);
	}

	this.add_user = function (user) {
		var kirim = $http.post(base_url('pusatdata/add_user'),user)
			.then(function (response) {
				return response.data;
			});
		return kirim;
	}

	this.resetpass = function (user) {
		var kirim = $http.post(base_url('pusatdata/resetpass/'),user)
			.then(function (response) {
				return response.data;
			});
		return kirim;
	}

	this.update_user = function (user) {
		var kirim = $http.post(base_url('pusatdata/update_user'),user)
			.then(function (response) {
				return response.data;
			});
		return kirim;
	}
	this.del_user = function (user) {
		var kirim = $http.post(base_url('pusatdata/del_user'),user)
			.then(function (response) {
				return response.data;	
			});
		return kirim;
	}
	this.add_group = function (group) {
		var kirim = $http.post(base_url('pusatdata/add_group'),group)
			.then(function (response) {
				return response.data;
			});
		return kirim;
	}
	this.up_group = function (group) {
		var kirim = $http.post(base_url('pusatdata/up_group'),group)
			.then(function (response) {
				return response.data;
			});
		return kirim;
	}
	this.del_group = function (group) {
		var kirim = $http.post(base_url('pusatdata/del_group'),group)
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}

	this.data_group = function (tahun) {
		var kirim = $http.get(base_url('pusatdata/jmlhelem/'+tahun))
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}

	this.history = function () {
		var kirim = $http.get(base_url('pusatdata/getlog/'))
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}

	this.load_opd = function (callback) {
		var kirim = $http.get(base_url('user/get_opd/'))
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}
	
	

})

.service('kategori', function($http,$routeParams){
	
	this.kategori = function (callback) {
		$http.get(base_url('pusatdata/kategori/'+$routeParams.parent))
		.then(callback);
	}
	this.newkat = function (newkat) {
		var kirim =$http.post('pusatdata/new_kat', newkat)
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}
	this.upkat = function (upkat) {
		var kirim =$http.post('pusatdata/up_kat', upkat)
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}
	this.hapus = function (id_kat) {
		var kirim = $http.post('pusatdata/hps_kat', id_kat)
			.then(function (response) {
				return response.data;	
			})
		return kirim;
	}



})

.service('element', function($http,$routeParams){
	this.element = function (callback) {
		if ($routeParams.tahun) {
			$http.get(base_url('api/element/'+$routeParams.id_kat+'/'+$routeParams.tahun))
			.then(callback);
		}else{
			$http.get(base_url('api/element/'+$routeParams.id_kat))
			.then(callback);
		}

	}

	this.lock = function (lock,id) {
		var data = {"id_el":id,"lock":lock}
		var kirim = $http.post(base_url('pusatdata/active/'),data)
			.then(function (response) {
				return response.data;
			})
		return kirim
	}

	this.lockall = function (lock,kat) {
		var data = {"kat":kat,"lock":lock}
		var kirim = $http.post(base_url('pusatdata/activeall/'),data)
			.then(function (response) {
				return response.data;
			})
		return kirim
	}

	this.cekapi = function (urldata) {
		var kirim = $http.get(urldata)
			.then(function (response) {
				return response.data;
			});
		return kirim;
	}
	this.simpanapi = function (data) {
		var kirim = $http.post(base_url('pusatdata/simpan_inject/'),data)
			.then(function (response) {
				return response.data;
			});
		return kirim;
	}

	this.get_parent = function (id_parent) {
		var kirim =$http.get('api/get_parentkat/'+id_parent, id_parent)
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}


	this.simpan_elm = function (simpandata) {
		var kirim =$http.post('pusatdata/simpanelement', simpandata)
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}

	this.simpan_set_api = function (simpandata) {
		var kirim =$http.post('pusatdata/up_el_api', simpandata)
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}

	this.hapus = function (data) {
		var kirim =$http.post('pusatdata/del_el', data)
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}


	this.tmb_el = function (tambah) {
		var kirim = $http.post(base_url('pusatdata/tmb_el'),tambah)
			.then(function (response) {
				return response.data;
			});
		return kirim;
	}

	this.up_el = function (update) {
		var kirim =$http.post('pusatdata/up_el', update)
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}

	this.up_el2 = function (update) {
		var kirim =$http.post('pusatdata/up_el2', update)
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}

	this.jenisdata = function (callback) {
		$http.get(base_url('api/kategori/0'))
		.then(callback);
	}

	// this.jenisdata_api = function (callback) {
	// 	$http.get(base_url('api/kategori/0'),{})
	// 	.then(callback);
	// }

	this.jenisdata_api = function (skpd) {
		var kirim = $http.get(base_url('api/kategori/0'),{
				headers: {'skpd': skpd}})
			.then(function (response) {
				return response.data;		
		})
		return kirim;
	}


	this.kategori = function (callback) {
		if ($routeParams.jenisdata) {
			$http.get(base_url('pusatdata/kategori/'+$routeParams.jenisdata.id))
			.then(callback);
		}
		//
	}

	this.kategori_api = function (skpd) {
		var kirim = $http.get(base_url('pusatdata/kategori/'+$routeParams.jenisdata.id),{
				headers: {'skpd': skpd}})
			.then(function (response) {
				return response.data;		
		})
		return kirim;
	}

	this.getkat = function (callback) {
		if ($routeParams.kategori) {
			$http.get(base_url('api/element/'+$routeParams.kategori.id))
			.then(callback);
		}
	}

	this.get_inject = function (callback) {
		if ($routeParams.kategori) {
			$http.get(base_url('pusatdata/inject_data/'+$routeParams.kategori.id+'/'+$routeParams.tahun))
			.then(callback);
		}
	}

	this.importdata = function (fileLocation) {
		var kirim =$http.post('pusatdata/import', fileLocation)
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}

	this.salinData = function (dataid, tahun) {
		var salin = {'data': dataid, 'tahun' :tahun};
		var kirim = $http.post(base_url('pusatdata/salinData'),(salin))
			.then(function (response) {
				return response.data;		
		})
		return kirim;
	}

	this.simpanImport = function (simpanexcel) {
		//console.log(excel);
		var kirim =$http.post(base_url('pusatdata/simpanImport'), simpanexcel)
			.then(function (response) {
				return response.data;
		});
		return kirim;
	}

	this.getskpd = function (callback) {
		$http.get(base_url('pusatdata/get_skpd'))
		.then(callback);
	}
})
.service('profile', function($http){
	this.getuser = function (callback) {
		$http.get(base_url('user/get_user/'+$routeParams.jenisdata.id))
			.then(callback);
	}

	

	this.getgroup = function (callback) {
		
	}



	this.update_opd = function (profile) {
		var kirim = $http.post(base_url('pusatdata/update_opd/'),profile)
			.then(function (response) {
				return response.data;
			})
		return kirim;
	}


	this.changeuser = function (profile) {
		var kirim = $http.post(base_url('pusatdata/changeuser/'),profile)
			.then(function (response) {
				return response.data;
			})
		return kirim;
	}
	this.gantipass = function (pass) {
		var kirim = $http.post(base_url('pusatdata/changepass/'),pass)
			.then(function (response) {
				return response.data;
			})
		return kirim;
	}

	this.activity = function () {
		var kirim = $http.post(base_url('pusatdata/cekprofil'))
			.then(function (response) {
				return response.data;
			})
		return kirim;
		//
	}
})
.service('grafik', function($http){
	this.graph_batang = function (element) {
		var kirim2 = $http.post(base_url('api/graph_batang'),element)
			.then(function (response) {
				return response.data;
			});
		return kirim2;
	}

	this.graph_garis = function (element) {
		var kirim2 = $http.post(base_url('api/graph_garis'),element)
			.then(function (response) {
				return response.data;
			});
		return kirim2;
	}

	this.datagraph = function (data) {
		var kirim = $http.post(base_url('api/grafik_tahun'),data)
			.then(function (response) {
				return response.data;
			});
		return kirim;
	}

	this.select_child=function (element) {
		var kirim = $http.post(base_url('api/sel_grafik'),element)
			.then(function (response) {
				return response.data;
			});
		return kirim;
	}
});