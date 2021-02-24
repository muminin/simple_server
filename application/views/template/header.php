<!-- Header -->

<!-- Server status -->

<!-- End server status -->

<!-- Logo section -->
<div id="header-bg">
	<img src="assets/images/Logo2.png" width="229" height="37">
	<div class="pull-right" style="padding-left: 10px">
		<a href="<?php echo  base_url('uploads/definisi_operasional.xlsx') ?>"><img src="<?php echo base_url(); ?>/assets/images/operasional.png" height="40px"></a>
	</div>
	<div class="pull-right text-center">
		<a href="<?php echo  base_url('uploads/modul_sipd.pdf') ?>"><img src="<?php echo base_url('/assets/landingpage/images/MODUL1.png'); ?> " style=" height: 46px;"></a>
	</div>
	<div class="pull-right text-center">
		<a href="https://app.swaggerhub.com/apis/cv-prima/pusatdata/1.0.0" rel="nofollow"><img src="<?php echo base_url('/assets/images/API.png'); ?> " style=" height: 46px; margin-right: 20px;"></a>
	</div>

</div>

<!-- Sub nav -->
<div id="sub-nav">
	<div class="container_12" style="text-align: left;">
		Sistem Informasi Manajemen Plan Center <strong>(SIMPLE)</strong>

	</div>
</div>
<!-- End sub nav -->

<!-- Status bar -->
<div id="status-bar" class="row">
	<div class="container_12 col-lg-12">

		<ul id="status-infos" class="col-sm-12 col-lg-6 pull-right">
			<li ng-if="user.first_name != undefined" class="pull-right"><a href="<?php echo base_url('/logout'); ?>" class="button red" title="Logout"><span class="smaller">LOGOUT</span></a></li>
			<li ng-if="user.first_name != undefined" class="spaced pull-right">Logged as: <a href="<?php echo base_url('admin'); ?>"><strong>{{user.first_name}}</strong></a></li>
			<li ng-if="user.first_name == undefined" class="pull-right"><a href="<?php echo base_url('admin/login'); ?>" class="button blue" title="Login"><span class="smaller">LOGIN</span></a></li>


		</ul>

	</div>
</div>
<!-- End status bar -->

<div id="header-shadow"></div>
<!-- End header -->