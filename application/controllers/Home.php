<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
    protected $models = array();

    protected $helpers = array('url', 'file');

    protected $asides = array(
        'header' => 'template/header',
        'footer' => 'template/footer',
        'topnav' => 'template/top_nav',
        'leftnav' => 'template/left_nav',
    );


    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        redirect("home");
        //angular 1.6 framework
        //module for angular js
        // $this->addJS(base_url('assets/angular/angular.js'));
        //$this->addJS(base_url('assets/angular/angular-route.min.js'));
        //$this->addJS(base_url('assets/angular/ui-bootstrap-tpls-2.4.0.js'));

        //main app angular 1.6
        /// $this->addJS(base_url('assets/app/main.js'));
        //$this->addJS(base_url('assets/app/service/service.js'));
        //$this->addJS(base_url('assets/app/controller/fasilitas.js'));
        //$this->addJS(base_url('assets/app/route/app_route.js'));


        //load Jquery
        $this->addJS(base_url('assets/global/plugins/jquery.min.js'));

        //load jquery UI
        //$this->addJS(base_url('assets/global/plugins/jquery-ui.min.js'));

        //load bottstrap
        $this->addCSS(base_url('assets/bootstrap/css/bootstrap.css'));
        //$this->addJS(base_url('assets/bootstrap/js/bootstrap.min.js'));


        //load font awesome
        //$this->addCSS( base_url('assets/font-awesome/css/font-awesome.css') );

        //load fast click
        //$this->addJS(base_url('assets/fastclick/fastclick.min.js'));

        //load slim scroll
        //$this->addJS(base_url('assets/slimScroll/jquery.slimscroll.min.js'));

        //load css dan js thema
        $this->addCSS(base_url('assets/css/style.css'));
        $this->addCSS(base_url('assets/css/mini.css'));
        $this->addCSS(base_url('assets/landingpage/custom.css'));

        //$this->addJS_Suport(base_url('assets/js/custom.js'));


        //
        // select 2 js
        $this->addCSS(base_url('assets/global/plugins/select2/css/select2.min.css'));
        $this->addCSS(base_url('assets/global/plugins/select2/css/select2-bootstrap.min.css'));
        $this->addJS(base_url('assets/global/plugins/select2/js/select2.full.min.js'));

        //amrchar
        $this->addJS(base_url('assets/global/plugins/amcharts/amcharts/amcharts.js'));
        $this->addJS(base_url('assets/global/plugins/amcharts/amcharts/pie.js'));
        $this->addJS(base_url('assets/global/plugins/amcharts/amcharts/serial.js'));
        $this->addJS(base_url('assets/global/plugins/amcharts/amcharts/themes/light.js'));
        $this->addJS(base_url('assets/global/plugins/amcharts/amcharts/plugins/export/export.min.js'));
        $this->addCSS(base_url('assets/global/plugins/amcharts/amcharts/plugins/export/export.css'));
        $this->addJS(base_url('assets/global/plugins/amcharts/amcharts/lang/id.js'));


        // core aplikasi angular
        $this->addJS(base_url('assets/angular/angular.min.js'));
        $this->addJS(base_url('assets/angular/angular-route.min.js'));
        $this->addJS(base_url('assets/angular/ui-bootstrap-tpls-2.4.0.js'));
        $this->addJS(base_url('assets/angular/angular-messages.min.js?=' . $this->_app_version));
        $this->addJS(base_url('assets/angular/amChartsDirective.js'));
        $this->addJS(base_url('assets/angular/angular-animate.min.js?=' . $this->_app_version));
        $this->addJS(base_url('assets/angular/angular-select2.js'));







        //angular app
        $this->addJS(base_url('assets/landingpage/app/main.js?=' . $this->_app_version));
        $this->addJS(base_url('assets/landingpage/app/filter.js'));
        $this->addJS(base_url('assets/landingpage/app/index/ctrl.js'));
        $this->addJS(base_url('assets/landingpage/app/index/service.js'));




        //chose template layout
        $this->layout = 'template/index';
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
