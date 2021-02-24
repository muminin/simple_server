<html ng-app="pusadata">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>Plan Center || KOTA MOJOKERTO</title>
	<meta name="description" content="">
	<meta name="author" content="">
	
	<!-- Combined stylesheets load -->
	<!-- Load either 960.gs.fluid or 960.gs to toggle between fixed and fluid layout -->
	<script type="text/javascript">
        var thn_data = <?php echo date('Y'); ?>;
        var tahun = function () {
        var d = new Date();
        var tahun = d.getFullYear();
        var tahuns = new Array;
        for (var i = 2010; i <= thn_data; i++) {
                tahuns[i] = i;
            }
            return tahuns;
        }
        var bulan = new Array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    </script>

    <script type="text/javascript">
    	var url = '<?php echo base_url() ?>';
    </script>


	<?php echo $core_css ?>
    <?php echo $js ?>
	
	
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link rel="icon" type="image/png" href="favicon-large.png">
	
	<!-- Modernizr for support detection, all javascript libs are moved right above </body> for better performance -->
	<!-- <script src="/js/libs/modernizr.custom.min.js"></script> -->
	
	
	
</head>

<body ng-controller="main">
	
	<!-- Header -->
	
	<?php echo $header ?> 
	<!-- End header -->
	
	
	
	<!-- Content -->
	<article class="row col-md-12">
		
		<section class="col-lg-3">
			<?php echo $leftnav ?>
		</section>
		
		<section class="col-lg-9">
			<?php echo $yield ?>
		</section>
		
		<div class="clear"></div>
		
	</article>
	
	<!-- End content -->
	
</body>

</html>