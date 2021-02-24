var body_load = false;
var ketersediaan = {1:"ada", 2:"Tidak"};


function base_url(name) {
      return url+name;
}

var aplikasi = angular.module('admin', ['ngRoute','ui.bootstrap','oitozero.ngSweetAlert','ngMessages','ui.select2','oitozero.ngSweetAlert','amChartsDirective','ngAnimate','angularFileUpload','ngProgress'])

aplikasi.config(function($routeProvider,$locationProvider) {
	$routeProvider
	// route for the home page
	 .when('/', {
		templateUrl : base_url('admin_view'),
		controller : 'home',
		resolve:{
	 		breadcumb : function(){
	 			return null
	 		}
	 	}
	 })
	.when('/inputdata/kategori/', {
		templateUrl :  base_url('admin_view/data'),
	 	controller : 'kategori',
	 	resolve:{
	 		breadcumb : function(){
	 			return {
	 				lvl:[{'Name':'Input Data','url':'/inputdata/kategori/'}]
	 			}
	 		}
	 	}
	})


	.when('/inputdata/skpd/', {
		templateUrl :  base_url('admin_view/inputdata'),
	 	controller : 'element',
	 	resolve:{
	 		breadcumb : function(){
	 			return {
	 				lvl:[{'Name':'Input Data','url':'/inputdata/skpd/'}]
	 			}
	 		}
	 	}
	})

	.when('/inputdata/element/:id_kat',{
		templateUrl : base_url('admin_view/element'),
		controller : 'element',
		resolve:{
	 		breadcumb : function(){
	 			return {
	 				lvl:[{'Name':'element','url':'/user/profile/'}]
	 			}
	 		}
	 	}
	})
	.when('/inputdata/editelement/',{
		templateUrl : base_url('admin_view/editelement'),
		controller : 'edit_element',
		resolve:{
	 		breadcumb : function(){
	 			return {
	 				lvl:[{'Name':'Edit element','url':'/user/profile/'}]
	 			}
	 		}
	 	}
	})
	.when('/user/profile/',{
		templateUrl : base_url('admin_view/profile'),
		controller : 'profile',
		resolve:{
	 		breadcumb : function(){
	 			return {
	 				lvl:[{'Name':'Profile','url':'/user/profile/'}]
	 			}
	 		}
	 	}
	})
	.when('/usermanagement',{
		templateUrl :base_url('admin_view/usermanagement'),
		controller : 'users',
		resolve:{
	 		breadcumb : function(){
	 			return {
	 				lvl:[{'Name':'User Management','url':'/usermanagement'}]
	 			}
	 		}
	 	}
	})
	.when('/inputdata/inject/',{
		templateUrl : base_url('admin_view/inject_data'),
		controller : 'inject_data',
		resolve:{
	 		breadcumb : function(){
	 			return {
	 				lvl:[{'Name':'Inject Data','url':'/usermanagement'}]
	 			}
	 		}
	 	}
	})
	.when('/setting/api_management',{
		templateUrl : base_url('admin_view/bridging_aplikasi'),
		controller : 'bridging_aplikasi'
	})
	.when('/setting/api_element',{
		templateUrl : base_url('admin_view/api_element'),
		controller : 'bridging_element'
	})
	.otherwise({
        redirectTo : '/'
  	 });
})

.run(function($rootScope,ngProgressFactory) {
	$rootScope.progressbar = ngProgressFactory.createInstance();
	 $rootScope.$on('$routeChangeSuccess', function (event) {
    //If login data not available, make sure we request for it
    	 $rootScope.progressbar.complete();
    })


  	$rootScope.$on('$locationChangeStart', function (event) {
    //If login data not available, make sure we request for it
    	$rootScope.progressbar.start();
    })
  	$rootScope.saving = false;
  	
   
});
