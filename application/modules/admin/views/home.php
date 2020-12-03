<div class="row">

	<div class="col-md-4">
		<?php echo modules::run('adminlte/widget/box_open', 'Shortcuts'); ?>
			<?php echo modules::run('adminlte/widget/app_btn', 'fa fa-user', 'Account', 'panel/account'); ?>
			<?php echo modules::run('adminlte/widget/app_btn', 'fa fa-sign-out', 'Logout', 'panel/logout'); ?>
		<?php echo modules::run('adminlte/widget/box_close'); ?>
	</div>

	<div class="col-md-4">
		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['users'], 'Users', 'fa fa-users', 'user'); ?>
	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['operators'], 'Operators Configured', 'fa fa-th', 'MasterData/VehicleOperators'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['vehicles'], 'Total Vehicles', 'fa fa-truck', 'VehicleData/VehicleMaster'); ?>	</div>	<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['routes'], 'Total Routes', 'fa fa-bars', 'VehicleData/VehicleOperatorRoutes'); ?>	</div>	<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['devices'], 'Total Devices', 'fa fa-mobile', 'Devices/VehicleOperatorDevices'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['tickets'], 'Total Tickets Sold', 'fa fa-ticket', 'Track/VehicleTicketBookings'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['ticketsamount'], 'Total Tickets Amount', 'fa fa-money', 'Reports/Collection'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['ticketstoday'], 'Sold Today', 'fa fa-ticket', 'Track/VehicleTicketBookings'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['ticketsamounttoday'], 'Amount Today', 'fa fa-money', 'Reports/CollectionToday'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['tickets_test'], 'Test Total Tickets Sold', 'fa fa-ticket', 'Track/VehicleTicketBookings'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['ticketsamount_test'], 'Test Total Tickets Amount', 'fa fa-money', 'Reports/Collection'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['ticketstoday_test'], 'Test Sold Today', 'fa fa-ticket', 'Track/VehicleTicketBookings'); ?>	</div>		<div class="col-md-4">		<?php echo modules::run('adminlte/widget/info_box', 'green', $count['ticketsamounttoday_test'], 'Test Amount Today', 'fa fa-money', 'Reports/CollectionToday'); ?>	</div>
	
</div>
