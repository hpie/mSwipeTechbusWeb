<?php if ( !empty($crud_note) ) echo "<p>$crud_note</p>"; ?>

<?php 
	//echo "=========>>>>>>>>> <pre>"; print_r($this->uri->segment(2)); exit;
if($this->uri->segment(2) == "Reports") {
$form_url = base_url().'admin/Reports';

$form_url .= (($this->uri->segment(3) == "todays_collection")) ? '/todays_collection' : '';

?>

<div>
	<!-- <form action="<?php echo base_url().'admin/Reports'; ?>" method="POST"> -->

	<form action="<?php echo $form_url; ?>" method="POST">

	<select id="filter_operator" name="filter_operator">
		<option value="">Operator</option>
		<?php foreach($all_operators as $operator) { ?>
			<option value="<?php echo $operator->row_id;?>" <?php if($filter_operator != "" && $filter_operator == $operator->row_id) { echo "selected"; } ?>><?php echo $operator->operator_name . "-". $operator->operator_city;?></option>
		<?php } ?>
	</select>

	<select id="filter_route" name="filter_route">
		<option value="">Route</option>
		<?php foreach($all_routes as $route) { ?>
			<option value="<?php echo $route->route_code;?>" <?php if($filter_route != "" && $filter_route == $route->route_code) { echo "selected"; } ?>><?php echo $route->route_code;?></option>
		<?php } ?>
	</select>

	<select id="filter_vehicle" name="filter_vehicle">
		<option value="">Vehicle</option>
		<?php foreach($all_vehicles as $vehicle) { ?>
			<option value="<?php echo $vehicle->vehicle_number;?>" <?php if($filter_vehicle != "" && $filter_vehicle == $vehicle->vehicle_number) { echo "selected"; } ?>><?php echo $vehicle->vehicle_number;?></option>
		<?php } ?>
	</select>
	
	<?php if($this->uri->segment(3) != "todays_collection") { ?>
	<input type="text" id="filter_start_date" name="filter_start_date" autocomplete="off" <?php if($filter_start_date != "") { ?>value="<?php echo $filter_start_date; ?>" <?php } ?> placeholder="Start date">
	<input type="text" id="filter_end_date" name="filter_end_date" autocomplete="off" <?php if($filter_end_date != "") { ?>value="<?php echo $filter_end_date; ?>" <?php } ?> placeholder="End date">
	<?php } ?>

	<input type="submit" name="apply_filter" value="Apply Filter">
	</form>
</div>

<script>
	$("#filter_start_date").datepicker({ dateFormat: 'yy-mm-dd' });
	$("#filter_end_date").datepicker({ dateFormat: 'yy-mm-dd' });
</script>

<?php } ?>

<?php if ( !empty($crud_output) ) echo $crud_output; ?>