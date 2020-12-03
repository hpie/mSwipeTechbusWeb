<div class="row">

	<div class="col-md-4">
		<?php echo modules::run('adminlte/widget/box_open', 'Shortcuts'); ?>
			<?php echo modules::run('adminlte/widget/app_btn', 'fa fa-user', 'Account', 'panel/account'); ?>						<?php // echo modules::run('adminlte/widget/app_btn', 'fa fa-cloud-download', 'Download App', 'Home/DownloadApp'); ?>
			<?php echo modules::run('adminlte/widget/app_btn', 'fa fa-sign-out', 'Logout', 'panel/logout'); ?>
		<?php echo modules::run('adminlte/widget/box_close'); ?>
	</div>

	<div class="col-md-4">
		<?php echo modules::run('adminlte/widget/info_box', 'blue', $count['users'], 'Total Users', 'fa fa-users', 'user'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'blue', $count['vehicles'], 'Total Vehicles', 'fa fa-truck', 'user'); ?>	</div>
	<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'blue', $count['routes'], 'Total Routes', 'fa fa-bars', 'user'); ?>	</div>	<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'blue', $count['devices'], 'Total Devices', 'fa fa-mobile', 'user'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'blue', $count['fares'], 'Fares Configured', 'fa fa-tags', 'user'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'blue', $count['tickets'], 'Total Tickets Sold', 'fa fa-ticket', 'user'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'blue', $count['ticketsamount'], 'Total Tickets Amount', 'fa fa-money', 'user'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'blue', $count['ticketstoday'], 'Sold Today', 'fa fa-ticket', 'user'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'blue', $count['ticketsamounttoday'], 'Amount Today', 'fa fa-money', 'user'); ?>	</div>
</div>
