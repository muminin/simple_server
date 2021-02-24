'use strict';

aplikasi
.controller('main', function($scope,sipd,$rootScope){
  $scope.datatahun = thn_data;
  $scope.judul = 'Data';
  $scope.ikon = new Array('assets/images/icons/web-app/48/archive.png','assets/images/icons/web-app/48/database.png');
  var i = 0;
  var id_kat;
  var latest = new Array();


  $scope.select2Options = {
    tags: "true",
		theme: "bootstrap"
  };

  


  //$scope.change_thn();

  $scope.navback = function (index) {
    $scope.grafik_el=false;
    $scope.grafik_op=false;
    var ulang = $scope.nav.length;
    for (var i = index+1; i < ulang; i++) {
       $scope.nav.pop();
       $scope.tabel = false;
    }
    $scope.sipd = latest[$scope.nav.length];
    if ($scope.nav.length == 0) {$scope.judul='data'}else{$scope.judul = $scope.nav[$scope.nav.length-1].nama;}
  }
	$scope.nav = new Array();

  sipd.ceklogin().then(function (response) {
    $scope.user = response;
  })

	sipd.kategori(0).then(function(response){
		$scope.sipd = response;
    latest[0]=response;
	});

  var cekheight = function () {
    $scope.tinggi = $("form#tab-stats").height();
  }

  //
  
  $scope.gantitahun = function () {
    //  console.log(latest[1]);
    sipd.element($scope.kategori.id,$scope.datatahun).then(function (response) {
        $scope.judul  = $scope.kategori.nama +' '+ $scope.datatahun  

        // pakai halaman
        //$scope.elementdata = cek_data(response);
        //$scope.elements = $scope.elementdata[$scope.currentPage];

        //tidak pakai halaman
        $scope.elements = response;
    })
  }

	$scope.pil_kat = function (kategori) {
    //console.log('masuk');
    cekheight();
		if ($scope.nav[0] != undefined || $scope.nav[0] != null) {
			$scope.sipd=null;
      latest[2]=null;
      
      $scope.kategori = kategori;
      $scope.judul = kategori.nama +' '+ $scope.datatahun ;
      //id_kat = kategori.id;
      $scope.tabel = true;
			sipd.element(kategori.id,$scope.datatahun).then(function (response) {
        //pakai halaman
        //$scope.elementdata = cek_data(response);
				//$scope.elements = $scope.elementdata[$scope.currentPage];
        // end pakai halaman

        $scope.elements = response;

			})
			$scope.nav.push(kategori);
		}else{
      $scope.judul = kategori.nama;
			sipd.kategori(kategori.id).then(function(response){
				$scope.sipd = response;
        latest[1]=response;
				$scope.nav.push(kategori);
			});
		}
	}


  //hitung

  $scope.cekparent = function (id,index) {
    angular.forEach($scope.elements, function(value, key) {
      // if ((''+value.nilai).length < karakter) {
      //  karakter  = (value.nilai).length;
      // }
      if (value.id_parent == id && value.nilai != null) {
        if (i == 0 ) {
          $scope.elements[index].nilai = Number(value.nilai);
          i=1;
        }else{
          $scope.elements[index].nilai = Number($scope.elements[index].nilai)+Number(value.nilai);
        }
        return 'parent';
      }
    });
    //$scope.karakter = karakter;
    i=0;
  }

  //hide tabel
	$scope.tabel = true;
  
  //pagination tabel
  $scope.currentPage = 1;
  $scope.maxSize = 5;
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
    $scope.elements = $scope.elementdata[$scope.currentPage];  //trigered when page pageChanged
  };

  // end pagination tabel 

  $scope.change_thn = function () {
    var baru_tahun = new Array;
    for (var i =$scope.grafik.awalThn; i <= thn_data; i++) {
                baru_tahun[i] = i;
        }
    $scope.select_thnakhir = baru_tahun;

    if ($scope.grafik.akhirThn < $scope.grafik.awalThn) {
      $scope.grafik.akhirThn =$scope.grafik.awalThn;
    }

    return baru_tahun;
  }

	$scope.tahuns = tahun();
	// menggunakan amchart untuk menampilkan grafik
	$scope.grafik = {}; //set scope grafik sebagai array
	$scope.tampil = {}//set for wacth variable
	$scope.grafik_el = false;
	$scope.grafik_op = false;
	$scope.ganti =0;
	$scope.showgrafik = function (element) {
    $scope.judul = 'Grafik '+ element.nama;
		$scope.tabel = false;
		$scope.grafik.element = element;
		$scope.grafik.akhirThn = thn_data;
		$scope.grafik.awalThn = thn_data-5;
		$scope.grafik.diagram = '1';
		$scope.grafik.id_kat = element.id_kat;
    $scope.change_thn();
		if ($scope.grafik.pilSub  == undefined || $scope.grafik.pilSub.length === 0) {
 			$scope.grafik.pilSub = [{id:element.id,nama:element.nama}];
 		}
 		sipd.select_child(element).then(function (response) {
			$scope.childs = response;
		})
		//console.log($scope.grafik);
		sipd.graph_batang($scope.grafik).then(function (response) {
			$scope.graph = response;
			$scope.grafik_op = true;
			$scope.tampil.title =0;
		})
		sipd.datagraph($scope.grafik).then(function (response) {
			$scope.datagraph = response;
			$scope.grafik_el = true;
			$scope.tampil.data =0;
		})
	}

	$scope.tampil=function () {
		if ($scope.grafik.pilSub  == undefined || $scope.grafik.pilSub.length === 0) {
 			$scope.grafik.pilSub = [{id:$scope.grafik.element.id,nama:$scope.grafik.element.nama}];
 		}
		//
		switch ($scope.grafik.diagram) {
			case '1':
		        sipd.graph_batang($scope.grafik).then(function (response) {
					$scope.graph = response;
					$scope.tampil.title = $scope.tampil.title+1;
					$scope.ganti = $scope.ganti+1;
				})
		        break;
		     case '2' :
		     	sipd.graph_garis($scope.grafik).then(function (response) {
					$scope.graph = response;
					$scope.tampil.title = $scope.tampil.title+1;
					$scope.ganti = $scope.ganti+1;
				})
		        break;
		}
		
		sipd.datagraph($scope.grafik).then(function (response) {
			$scope.datagraph = response;
			$scope.tampil.data =  $scope.tampil.data+1
			$scope.ganti = $scope.ganti+1;
		})
   
	}
  $scope.grafikback = function () {
      $scope.judul  = $scope.kategori.nama +' '+ $scope.datatahun  
      $scope.grafik_el = false;
      $scope.grafik_op = true;
      $scope.tabel = true;
      $scope.grafik = {};
  } 
  // perintah untuk menampilkan grafik selesai disini
  //console.log($scope.ganti);
})
.run(function($rootScope) {
  $rootScope.ganti=0;
})
.directive('myGrafik3',function ($http) {
       return {
           // restrict: 'E',
           replace:true,
           // scope: {
           //    datatahun :"="
           // },
          
           template: '<div id="chartdiv" style="width: 100%;min-height: 350px;max-height:500px; height: 100%; margin: 0 auto"></div>',
           link: function (scope, element, attrs,$rootScope) {
                 scope.$watch(attrs.myGrafik3, function(value) {
                 	if (scope.$parent.tampil.data == scope.$parent.tampil.title) {
                 		switch(scope.$parent.grafik.diagram){
                 			case "1":
						        grafikbatang();
						        break;
						    case "2":
						        grafikgaris();
						    break;	
                 		}
                 		
                 	}
                    
                  });
                 var data_grafik=function (url) {
                    var kirim = $http.get(url)
                      .then(function (response) {
                        return response.data;
                      });
                    return kirim;
                  }

                 //console.log(scope.$root.tahun);
                
                var chart = false;
                
                var grafikbatang = function() {
                  if (chart) chart.destroy();
                 // console.log(scope.$parent.datagraph);
                  var config = scope.config || {};
                   chart = AmCharts.makeChart("chartdiv", {
                   	"language": "id",
                    "theme": "light",
                    "type": "serial",
                    "dataProvider": scope.$parent.datagraph,
                    "startDuration": 1,
                    "valueAxes": [{
                        "unit": " ",
                        "position": "left",
                    }],
                    "legend": {
                                "horizontalGap": 10,
                                "useGraphSettings": true,
                                "markerSize": 10
                              },

                    "graphs": scope.$parent.graph,
                    "plotAreaFillAlphas": 0.1,
                    "depth3D": 15,
                    "angle": 10,
                    "categoryField": "tahun",
                    "categoryAxis": {
                        "gridPosition": "start"
                    },
                    "export": {
					         "enabled": true,
                    "menu": [ {
                        "class": "export-main",
                        "menu": [ {
                          "label": "Download",
                          "menu": [ "PNG", "JPG", "CSV" ]
                        }, {
                          "label": "Annotate",
                          "action": "draw",
                          "menu": [ {
                            "class": "export-drawing",
                            "menu": [ "PNG", "JPG" ]
                          } ]
                        } ]
                      } ]
					}

                 });
                    
                        
                };
                var grafikgaris = function() {
                  if (chart) chart.destroy();
                  var config = scope.config || {};
                   chart = AmCharts.makeChart("chartdiv", {
                   	"language": "id",
                    "type": "serial",
                    "theme": "light",
                    "legend": {
                        "useGraphSettings": true
                    },
                    "dataProvider": scope.$parent.datagraph,
                    "marginTop": 20,
                    "valueAxes": [{
                        "integersOnly": true,
                        "minimum": 0,
                        "axisAlpha": 0,
                        "dashLength": 5,
                        "gridCount": 10,
                        "position": "left",
                        "unit": " ",
                    }],
                    "startDuration": 0.5,
                    "graphs": scope.$parent.graph,
                    "chartCursor": {
                        "cursorAlpha": 0,
                        "zoomable": false
                    },
                    "categoryField": "tahun",
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "fillAlpha": 0.05,
                        "fillColor": "#000000",
                        "gridAlpha": 0,
                    },
                    "export": {
						        "enabled": true,
                    "menu": [ {
                        "class": "export-main",
                        "menu": [ {
                          "label": "Download",
                          "menu": [ "PNG", "JPG", "CSV" ]
                        }, {
                          "label": "Annotate",
                          "action": "draw",
                          "menu": [ {
                            "class": "export-drawing",
                            "menu": [ "PNG", "JPG" ]
                          } ]
                        } ]
                      } ]
					          }

                    
                 });
                    
                        
                };
         }//end watch           
       }
   })