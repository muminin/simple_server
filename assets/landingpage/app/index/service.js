'use strict';

aplikasi
.service('sipd', function($http,$rootScope){
	

	this.kategori = function (parent) {
		$rootScope.loading=true;
		var kirim = $http.get(base_url('api/kategori/'+parent+'/true'))
			.then(function (response) {
				$rootScope.loading=false;
				return response.data;
			});
		return kirim;
	}
	this.element = function (kat,tahun) {
		$rootScope.loading=true;
		var kirim = $http.get(base_url('api/element/'+kat+'/'+tahun+'/true'))
			.then(function (response) {
				$rootScope.loading=false;
				return response.data;
			})
		return kirim;
	}

	this.ceklogin = function () {
		$rootScope.loading=true;
		var kirim2 = $http.get(base_url('pusatdata/'))
			.then(function (response) {
				$rootScope.loading=false;
				return response.data;
			});
		return kirim2;
	}

	this.graph_batang = function (element) {
		$rootScope.loading=true;
		var kirim2 = $http.post(base_url('api/graph_batang'),element)
			.then(function (response) {
				$rootScope.loading=false;
				return response.data;
			});
		return kirim2;
	}

	this.graph_garis = function (element) {
		$rootScope.loading=true;
		var kirim2 = $http.post(base_url('api/graph_garis'),element)
			.then(function (response) {
				$rootScope.loading=false;
				return response.data;
			});
		return kirim2;
	}

	this.datagraph = function (data) {
		$rootScope.loading=true;
		var kirim = $http.post(base_url('api/grafik_tahun'),data)
			.then(function (response) {
				$rootScope.loading=false;
				return response.data;
			});
		return kirim;
	}

	this.select_child=function (element) {
		$rootScope.loading=true;
		var kirim = $http.post(base_url('api/sel_grafik'),element)
			.then(function (response) {
				$rootScope.loading=false;
				return response.data;
			});
		return kirim;
	}
})