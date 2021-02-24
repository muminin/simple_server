'use strict';
aplikasi.directive('overview', function() {
  return {
    templateUrl: base_url('admin_view/overview')
  };
})
.directive('ngEnter', function() {
    return function(scope, element, attrs) {
        element.bind("keydown keypress", function(event) {
            if(event.which === 13) {
                scope.$apply(function(){
                    scope.$eval(attrs.ngEnter, {'event': event});
                });
                event.preventDefault();
            }
        });
    };
})
.directive('isNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) {   
        element.bind("keydown keypress", function (event) {
          if(event.which === 32) {
            event.returnValue = false;
            return false;
          }
       }); 
            scope.$watch(attrs.ngModel, function(newValue,oldValue) {

                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.' )) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    //scope.wks.number = oldValue;
                    ngModel.$setViewValue(oldValue);
                                    ngModel.$render();
                }
            });

        }
    };
})
.directive('isInteger', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) {   
        element.bind("keydown keypress", function (event) {
          if(event.which === 32) {
            event.returnValue = false;
            return false;
          }
       }); 
            scope.$watch(attrs.ngModel, function(newValue,oldValue) {
                if (isNaN(newValue)) {
                    //scope.wks.number = oldValue;
                    ngModel.$setViewValue(oldValue);
                                    ngModel.$render();
                }
            });

        }
    };
})

.directive('focusOn', function() {
   return function(scope, elem, attr) {
      scope.$on('focusOn', function(e, name) {
        if(name === attr.focusOn) {
          elem[0].focus();
        }
      });
   };
})
.directive('resize', function ($window) {
    return function (scope, element) {
        var w = angular.element($window);
        scope.getWindowDimensions = function () {
            return {
                'h': w.height(),
                'w': w.width()
            };
        };
        scope.$watch(scope.getWindowDimensions, function (newValue, oldValue) {
            scope.windowHeight = newValue.h;
            scope.windowWidth = newValue.w;

            scope.style = function () {
                return {
                    'height': (newValue.h - 100) + 'px',
                        'width': (newValue.w - 100) + 'px'
                };
            };

        }, true);

        w.bind('resize', function () {
            scope.$apply();
        });
    }
})

.directive('myGrafik1',function (grafik) {
       return {
           restrict: 'E',
           replace:true,
           scope: {
              datagrafik :"=",
              dataoption :"=",
              satuan : "="
           },
          
           template: '<div id="chartdiv" style="min-width: 310px; height: 100%; margin: 0 auto"></div> ',
           link: function (scope, element, attrs) {
           
                var chart = false;
                if (scope.satuan == null) {
                  scope.satuan ="";
                }
                var initChart = function() {
                  if (chart) chart.destroy();
                  var config = scope.config || {};
                   chart = AmCharts.makeChart("chartdiv", {
                     "theme": "light",
                    "type": "serial",
                    "dataProvider": scope.datagrafik,
                    "startDuration": 1,
                    "valueAxes": [{
                        "unit": " "+scope.satuan,
                        "position": "left",
                    }],
                    "legend": {
                                "horizontalGap": 10,
                                "useGraphSettings": true,
                                "markerSize": 10
                              },

                    "graphs": scope.dataoption,
                    "plotAreaFillAlphas": 0.1,
                    "depth3D": 15,
                    "angle": 10,
                    "categoryField": "tahun",
                    "categoryAxis": {
                        "gridPosition": "start"
                    } 
                 });
                    
                        
                };
                initChart();
                   
         }//end watch           
       }
   })
.directive('myGrafik2',function (grafik) {
       return {
           restrict: 'E',
           replace:true,
           scope: {
              datagrafik :"=",
              dataoption :"=",
              satuan : "="
           },
          
           template: '<div id="chartdiv" style="min-width: 310px; height: 100%; margin: 0 auto"></div>',
           link: function (scope, element, attrs) {
                var chart = false;
                if (scope.satuan == null) {
                  scope.satuan ="";
                }
                var initChart = function() {
                  if (chart) chart.destroy();
                  var config = scope.config || {};
                   chart = AmCharts.makeChart("chartdiv", {
                    "type": "serial",
                    "theme": "light",
                    "legend": {
                        "useGraphSettings": true
                    },
                    "dataProvider": scope.datagrafik,
                    "marginTop": 20,
                    "valueAxes": [{
                        "integersOnly": true,
                        "minimum": 0,
                        "axisAlpha": 0,
                        "dashLength": 5,
                        "gridCount": 10,
                        "position": "left",
                        "unit": " "+scope.satuan,
                    }],
                    "startDuration": 0.5,
                    "graphs": scope.dataoption,
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
                    }
                    
                 });
                    
                        
                };
                initChart();
                   
         }//end watch           
       }
   })

.directive('myGrafik3',function (grafik,$http) {
 return {
     // restrict: 'E',
     replace:true,
     // scope: {
     //    datatahun :"="
     // },
    
     template: '<div id="chartdiv" style="min-width: 310px; height: 1200px; margin: 0 auto"></div>',
     link: function (scope, element, attrs) {
           scope.$watch(attrs.myGrafik3, function(value) {
              var url = base_url('pusatdata/grfketersediaan/'+value);
              //console.log(data_grafik(url));
              data_grafik(url).then(function(response){
                scope.dataload = response;
                initChart();
              });
              //
            });
           var data_grafik=function (url) {
              var kirim = $http.get(url)
                .then(function (response) {
                  return response.data;
                });
              return kirim;
            }

          var chart = false;
          var initChart = function() {
            if (chart) chart.destroy();
            var config = scope.config || {};
             chart = AmCharts.makeChart("chartdiv", {
                "type": "serial",
                "categoryField": "skpd",
                "rotate": true,
                "startDuration": 1,
                "categoryAxis": {
                  "gridPosition": "start",
                  "position": "left"
                },
                "trendLines": [],
                "graphs": [
                  {
                    "balloonText": "keterisian data :[[value]]%",
                    "fillAlphas": 0.8,
                    "id": "AmGraph-1",
                    "lineAlpha": 0.2,
                    "title": "Income",
                    "type": "column",
                    "colorField": "color",
                    "valueField": "persen"
                  }
                ],
                "guides": [],
                "valueAxes": [
                  {
                    "id": "ValueAxis-1",
                    "position": "top",
                    "axisAlpha": 0,
                    "minimum": 0,
                    "maximum": 100
                  }
                ],
                
                "dataProvider": scope.dataload  
                
           });
              
                  
          };
          initChart();
             
   }//end watch           
 }
})

// .directive('bootstrapSwitch', [
//   function() {
//       return {
//           restrict: 'A',
//           require: '?ngModel',
//           link: function(scope, element, attrs, ngModel) {
//               element.bootstrapSwitch();

//               element.on('switchChange.bootstrapSwitch', function(event, state) {
//                   if (ngModel) {
//                       scope.$apply(function() {
//                           ngModel.$setViewValue(state);
//                       });
//                   }
//               });

//               scope.$watch(attrs.ngModel, function(newValue, oldValue) {
//                   if (newValue) {
//                       element.bootstrapSwitch('state', true, true);
//                   } else {
//                       element.bootstrapSwitch('state', false, true);
//                   }
//               });
//           }
//       };
//   }
// ])
.directive('icheck', function($timeout,$parse) {
   return {
        link: function($scope, element, $attrs) {
            return $timeout(function() {
                var ngModelGetter, value;
                ngModelGetter = $parse($attrs['ngModel']);
                value = $parse($attrs['ngValue'])($scope);
                var label  =  $attrs['judul'];
                return $(element).iCheck({
                     checkboxClass: 'icheckbox_line-blue',
                      radioClass: 'iradio_line-blue',
                      insert: '<div class="icheck_line-icon"></div>' +label
                }).on('ifChanged', function(event) {
                        if ($(element).attr('type') === 'checkbox' && $attrs['ngModel']) {
                            $scope.$apply(function() {
                                return ngModelGetter.assign($scope, event.target.checked);
                            });
                        }
                        if ($(element).attr('type') === 'radio' && $attrs['ngModel']) {
                            return $scope.$apply(function() {
                                return ngModelGetter.assign($scope, value);
                            });
                        }
                    });
            });
        }
    };
})

.directive('squarecheck', function($timeout,$parse) {
   return {
        link: function($scope, element, $attrs) {
            return $timeout(function() {
                var ngModelGetter, value;
                ngModelGetter = $parse($attrs['ngModel']);
                value = $parse($attrs['ngValue'])($scope);
                return $(element).iCheck({
                      checkboxClass: 'icheckbox_square-blue',
                      radioClass: 'icheckbox_square-blue',
                      increaseArea: '5%'
                }).on('ifChanged', function(event) {
                        if ($(element).attr('type') === 'checkbox' && $attrs['ngModel']) {
                            $scope.$apply(function() {
                                return ngModelGetter.assign($scope, event.target.checked);
                            });
                        }
                        if ($(element).attr('type') === 'radio' && $attrs['ngModel']) {
                            return $scope.$apply(function() {
                                return ngModelGetter.assign($scope, value);
                            });
                        }
                    });
            });
        }
    };
})