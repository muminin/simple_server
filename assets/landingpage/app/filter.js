'use strict';
aplikasi.filter('tanggal', function($filter)
{
 return function(input)
 {
  if(input == null){ return ""; } 
	var _date = $filter('date')(new Date(input), 'dd MMM yyyy');
    return _date.toUpperCase();
 };
})
.filter('anggotagroup',function ($filter) {
	return function (input,group) {
		if(input == null){ return ""; } 
		var filtered = [];
		var item =new Array();
		for (var i = 0; i < input.length; i++) {
           item = input[i];
            if (item.group.id == group) {
            	filtered.push(input[i].username);
        	}
        }
        return filtered;      
  	}
})
.filter('carielement',function($filter) {
  return function (input,k_search) {
    //onsole.log(input);
    if(input == null){ return ""; } 
    var p_dta = input.length;
    var pilih = [];
    var data_id = [];
    var cari;
    if (k_search === undefined ) {
      var keyword = '';
    }else{
      var keyword = k_search.toLowerCase();
    }
    //var keyword = k_search.toLowerCase();
   
    var lvl = function (id_parent) {

      angular.forEach(input, function(v_input, k_input) {
        if (v_input.child === undefined && v_input.id_parent==0) {
          cari = v_input.nama.toLowerCase();
          if (cari.search(keyword) != -1) {
            pilih.push(v_input);
          }
        }else{
          if (v_input.id_parent==0) {
              pilih.push(v_input);
              lvlsub(filter_byid(v_input.id),v_input.id);
              if (data_id[v_input.id] != undefined) {
                  data_id[v_input.id_parent] = 1;
              }else{
                 pilih.splice(-1, 1);
              }
          }
        }
      });
    }

    var lvlsub = function (sub_data,id_parent) {
     angular.forEach(sub_data, function(v_sub, k_sub) {
        if (v_sub.child === undefined && v_sub.id_parent==id_parent) {
           cari = v_sub.nama.toLowerCase();
           if (cari.search(keyword) != -1) {
              pilih.push(v_sub)
              data_id[v_sub.id_parent] = 1;
           }
        }else{
          pilih.push(v_sub);  
          lvlsub(filter_byid(v_sub.id),v_sub.id);
          if (data_id[v_sub.id] != undefined) {
              data_id[v_sub.id_parent] = 1;
          }else{
             pilih.splice(-1, 1);
          }
        }
         //console.log(v_input.child);
      });
    }


    var filter_byid = function (id_parent) {
      var sub = [];
      angular.forEach(input, function(v_input, k_input) {
        if (v_input.id_parent == id_parent) {
           sub.push(v_input);
        }
      });
      return sub;
    }

    lvl(0);
    return pilih;
    // var lvl2 = function (id_parent) {
      
    // }

  };
}); 