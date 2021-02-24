<!--<div class="block-border"><div class="block-content">-->
				<h1>Navigation</h1>
				
				<ul class="favorites no-margin with-tip" title="Context menu available!">
					<li>
						<img src="assets/images/icons/web-app/48/home.png" width="48" height="48">
						<a ng-click="navback(-1)" style="cursor: pointer;">DATA<br></a>
					</li>
					<li ng-repeat="navigation in nav" class="left-nav-recent">
						<img ng-src="<?php echo base_url() ?>{{ikon[$index]}}" width="48" height="48">
						<a ng-click="navback($index)" style="cursor: pointer;">{{navigation.nama}}<br></a>
					</li>
					
					
				</ul>