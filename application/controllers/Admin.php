<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller{

	protected $models = array();

    protected $helpers = array( 'url', 'file');

    protected $asides = array(
        'header' => 'admin/header',
        'footer' => 'admin/footer',
        'topnav' => 'admin/top_nav',
        'leftnav' => 'admin/left_nav',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('ion_auth','form_validation'));
        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect(base_url('login/login'), 'refresh');
        }
    }


	public function index()
	{


        $users = $this->ion_auth->user()->row();
        if ($users->first_name === NULL || $users->NIP==NULL || $users->phone==NULL) {
        	redirect(base_url('login/first'),'refresh');
        }
        //load global framework css
        //load font google
        //$this->addCSS('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all');

        //load font awesome
        $this->addCSS(base_url('assets/global/plugins/font-awesome/css/font-awesome.min.css'));

        //load google fonts
        $this->addCSS('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all');

        //load simple line icon
        $this->addCSS(base_url('assets/global/plugins/simple-line-icons/simple-line-icons.min.css'));

        //bootstrap framework
        $this->addCSS(base_url('assets/global/plugins/bootstrap/css/bootstrap.min.css'));

        //bootstrap switch
       // $this->addCSS(base_url('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css'));




        //plugin for advanced features
        //calender
        //$this->addCSS(base_url('assets/global/plugins/morris/morris.css'));

        //full calender
        //$this->addCSS(base_url('assets/global/plugins/fullcalendar/fullcalendar.min.css'));

        //jqv map
       // $this->addCSS(base_url('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'));

        



        //load main css
        //laod component
        $this->addCSS(base_url('assets/global/css/components.min.css'));
        $this->addCSS(base_url('assets/global/css/plugins.min.css?='. $this->_app_version));

        //main Design layout
        $this->addCSS(base_url('assets/layouts/layout2/css/layout.min.css?='. $this->_app_version));

        //main theme color set
        $this->addCSS(base_url('assets/layouts/layout2/css/themes/grey.min.css'));

        //inbox
        //main theme for costum
        $this->addCSS(base_url('assets/layouts/layout2/css/custom.css?='. $this->_app_version));
        $this->addCSS(base_url('assets/layouts/layout2/css/inbox.min.css?='. $this->_app_version));

          // Jquery
       $this->addJS(base_url('assets/global/plugins/jquery.min.js'));

        // select 2 js
        $this->addCSS(base_url('assets/global/plugins/select2/css/select2.min.css'));
        $this->addCSS(base_url('assets/global/plugins/select2/css/select2-bootstrap.min.css'));
        $this->addJS(base_url('assets/global/plugins/select2/js/select2.full.min.js'));

        //sweet alert
        $this->addCSS(base_url('assets/global/plugins/sweet-alert/sweetalert.css'));
        $this->addJS(base_url('assets/global/plugins/sweet-alert/sweetalert.min.js'));


        //amrchar 
        //$this->addCSS(base_url('assets/global/plugins/sweet-alert/sweetalert.css'));
        $this->addJS(base_url('assets/global/plugins/amcharts/amcharts/amcharts.js'));
        $this->addJS(base_url('assets/global/plugins/amcharts/amcharts/pie.js'));
        $this->addJS(base_url('assets/global/plugins/amcharts/amcharts/serial.js'));
        $this->addJS(base_url('assets/global/plugins/amcharts/amcharts/themes/light.js'));

        // bootstrap switcer
        // $this->addCSS(base_url('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css'));
        // $this->addJS(base_url('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js'));

        // ichekc
        $this->addCSS(base_url('assets/global/plugins/icheck/skins/square/blue.css'));
        $this->addCSS(base_url('assets/global/plugins/icheck/skins/line/blue.css'));
        $this->addJS(base_url('assets/global/plugins/icheck/icheck.min.js'));
        $this->addJS(base_url('assets/global/plugins/currency.min.js'));



        // 
        // core aplikasi angular
        // 


        $this->addJS(base_url('assets/angular/angular.min.js'));
        $this->addJS(base_url('assets/angular/i18n/angular-locale_id.js'));
        $this->addJS(base_url('assets/angular/angular-route.min.js'));
        $this->addJS(base_url('assets/angular/ui-bootstrap-tpls-2.4.0.js'));
        $this->addJS(base_url('assets/angular/angular-messages.min.js'));
        $this->addJS(base_url('assets/angular/angular-select2.js'));
        $this->addJS(base_url('assets/angular/Ng-SweetAlert.min.js'));
        $this->addJS(base_url('assets/angular/amChartsDirective.js'));
        $this->addJS(base_url('assets/angular/angular-animate.min.js?=' . $this->_app_version));
        $this->addJS(base_url('assets/angular/angular-file-upload.min.js'));
        //$this->addJS(base_url('assets/angular/angular-bootstrap-switch.min.js'));



        //ng progress
        $this->addJS(base_url('assets/angular/ngprogress.min.js'));
        $this->addCSS(base_url('assets/layouts/layout2/css/ngProgress.css'));

        // 
        // block ui for angular
        //
        //$this->addJS(base_url('assets/global/plugins/angular-block-ui/angular-block-ui.min.js'));
        $this->addCSS(base_url('assets/global/plugins/angular-block-ui/angular-block-ui.css'));

        $this->addJS(base_url('assets/app/main.js?='. $this->_app_version));
        $this->addJS(base_url('assets/app/service/admin.js?='. $this->_app_version));
        $this->addJS(base_url('assets/app/controller/admin.js?='. $this->_app_version));
        $this->addJS(base_url('assets/app/controller/modal.js?='. $this->_app_version));
        $this->addJS(base_url('assets/app/directive/directive.js?='. $this->_app_version));
        $this->addJS(base_url('assets/app/filter/filter.js?='.$this->_app_version));


        $this->addJS(base_url('assets/layouts/layout2/scripts/app.min.js'));
        $this->addJS(base_url('assets/layouts/layout2/scripts/layout.min.js'));
        //$this->addJS(base_url('assets/layouts/layout2/scripts/quick-sidebar.min.js'));
        //$this->addJS(base_url('assets/layouts/layout2/scripts/demo.min.js'));

      



      




        // set for This data send to view

        //var_dump($users);


        $this->data['user'] = $users;
        //var_dump($users);
        $this->layout = 'admin/theme';
	}

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */