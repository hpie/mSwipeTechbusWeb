<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

// Login functionality
$app->post('/login', function() {

	require_once('db.php');

	//Check if device is registered
	$deive_query = "select * from `vehicle_device_access` WHERE device_imie = '".$_POST['imei']."'";

	$device_result = $conn->query($deive_query);
	
	// Initially success parameter set to zero
	$data['status'] = '0';

	//$data['query_1'] = $deive_query;

	if($device_result->num_rows > 0) {
		
		$query = "select * from `vehicle_device_access` WHERE loginid = '".$_POST['username']."' AND PASSWORD = '".$_POST['password']."' AND device_imie = '".$_POST['imei']."'";

		$query = "select vda.*, mr.route_code as actual_route_code, vo.operator_name, vm.vehicle_number, vm.vehicle_type from `vehicle_device_access` vda 
JOIN `master_routes` mr ON mr.row_id = vda.route_id 
JOIN `vehicle_operators` vo ON mr.row_id = vda.operator_id 
JOIN `vehicle_master` vm ON vm.row_id = vda.vehicle_id WHERE loginid = '".$_POST['username']."' AND PASSWORD = '".$_POST['password']."' AND device_imie = '".$_POST['imei']."'"; //exit;

		$data['user_query'] = $query;

		$result = $conn->query($query);

		if($result->num_rows > 0) {

			while ($row = $result->fetch_assoc()){
				$user_data = $row;
			}
			
			//Change login attempt to zero on successful login
			$login_attempts = 0;
			
			$data['loginid']		= $user_data['loginid'];
			$data['device_imie']	= $user_data['device_imie'];
			$data['route_code']		= $user_data['actual_route_code'];
			
			$data['operator_id']	= $user_data['operator_id'];
			$data['operator_name']	= $user_data['operator_name'];

			$data['vehicle_code']	= $user_data['vehicle_id'];
			$data['vehicle_number']	= $user_data['vehicle_number'];
			$data['vehicle_type']	= $user_data['vehicle_type'];

			$data['todays_date']	= date('Y-m-d');


			$data['status']			= '1';
			$data['message']		= 'Login Successfull';
		} else {
			$data['message']		= 'Wrong username or password';

			while ($row = $device_result->fetch_assoc()){
				$user_data = $row;
			}
			
			// Get initial login attempt count from database
			$login_attempts = ($user_data['device_login_attempts']) ?: 1;

			$todays_date = date('Y-m-d'); 
			
			//remove time from date
			$last_login_attempt  = date('Y-m-d', strtotime($user_data['modified_dt'])); 
			
			// Check if it is not first login attempt
			if($user_data['device_login_attempts'] > 0) {

				// Check if login attempt is from same day
				if(strtotime($todays_date) == strtotime($last_login_attempt)) {
					$login_attempts++;
				} else {
					$login_attempts = 1;
				}
			}
		}
		
		//Update login details
		$sql = "UPDATE `vehicle_device_access` SET `latitude` = '".$_POST['latitude']."',"; 
		
		if($data['status'] == '1') {
		$sql .= "`device_last_login` = '".date('Y-m-d h:i:s')."',";	
		$login_attempts = 0;
		}
		$sql .= "`longitude` = '".$_POST['longitude']."', `altitude` = '', `altutude_accuracy` = '', `device_login_attempts` = '".$login_attempts."' WHERE `device_imie` = '".$_POST['imei']."'";

		$conn->query($sql);
	} else {
		$data['message'] = 'Device does not exist on system. Please contact Operator!';
	}


	// /* Commented the code for now as login_attempt table do not exist
	// Insert login attempts log
	//$sql = "INSERT INTO `login_attempts` (`username`, `password`, `imei`, `latitude`, `longitude`, `status`) VALUES ('".$_POST['username']."', '".$_POST['password']."', '".$_POST['imei']."', '".$_POST['latitude']."', '".$_POST['longitude']."', '".$data['status']."')";

	$sql = "INSERT INTO `login_attempts` (`username`, `password`, `imei`, `latitude`, `longitude`) VALUES ('".$_POST['username']."', '".$_POST['password']."', '".$_POST['imei']."', '".$_POST['latitude']."', '".$_POST['longitude']."')";

	$conn->query($sql);

	//$data['sql'] = $sql;
	// */	

	//$data['post'] = json_encode($_POST);
	//$data['all_data'] = json_encode($user_data);
 

 //$data['new_message'] = "hhhhhhhhhhhhhhhhhhhhhhhhhhh";
 echo json_encode($data);
 
});

// Login functionality
$app->post('/login2', function() {

	require_once('db.php');

	//Check if device is registered
	$device_query = "select * from `vehicle_device_access` WHERE device_imie = '".$_POST['imei']."'";

	$device_result = $conn->query($device_query);
	
	// Initially success parameter set to zero
	$data['status'] = '0';

	if($device_result->num_rows > 0) {
		
		$query = "select * from `vehicle_device_access` WHERE loginid = '".$_POST['username']."' AND PASSWORD = '".$_POST['password']."' AND device_imie = '".$_POST['imei']."'";

		$query = "select vda.*, mr.route_code as actual_route_code, vo.operator_name, vm.vehicle_number, vm.vehicle_type from `vehicle_device_access` vda 
JOIN `master_routes` mr ON mr.row_id = vda.route_id 
JOIN `vehicle_operators` vo ON vo.row_id = vda.operator_id 
JOIN `vehicle_master` vm ON vm.row_id = vda.vehicle_id WHERE loginid = '".$_POST['username']."' AND PASSWORD = '".$_POST['password']."' AND device_imie = '".$_POST['imei']."'"; //exit;

		//$data['user_query'] = $query;

		$result = $conn->query($query);

		if($result->num_rows > 0) {

			while ($row = $result->fetch_assoc()){
				$user_data = $row;
			}
			
			//Change login attempt to zero on successful login
			$login_attempts = 0;
			
			$data['user_id']		= $user_data['row_id'];
			$data['loginid']		= $user_data['loginid'];
			$data['device_imie']	= $user_data['device_imie'];
			$data['operator_id']	= $user_data['operator_id'];
			$data['route_code']		= $user_data['actual_route_code'];			
			$data['operator_id']	= $user_data['operator_id'];
			$data['operator_name']	= $user_data['operator_name'];
			$data['vehicle_code']	= $user_data['vehicle_id'];
			$data['vehicle_number']	= $user_data['vehicle_number'];
			$data['vehicle_type']	= $user_data['vehicle_type'];
			$data['todays_date']	= date('Y-m-d');

			//-----------------------------------------------------------------------------------------------------------------------------------------------------------
				//Get operators discount data 
				$discount_query = "select * from `vehicle_operator_fare_discounts` WHERE operator_id = '".$user_data['operator_id']."' AND vehicle_type = '".$user_data['vehicle_type']."' AND discount_status = 'ACTIVE'";

				$discount_result = $conn->query($discount_query);

				$discounts = array();

				if($discount_result->num_rows > 0) {

					while ($row = $discount_result->fetch_assoc()){
						$discounts[$row['discount_type']] = $row['discount_percentage'];
					}
				}

				$data['discounts'] = $discounts;
			//-----------------------------------------------------------------------------------------------------------------------------------------------------------

			//-----------------------------------------------------------------------------------------------------------------------------------------------------------
				//Get routes details
				$routes_query = "select * from route_fare_matrix WHERE route_code = '". $user_data['actual_route_code'] ."' AND vehicle_type = '". $user_data['vehicle_type'] ."' ORDER BY row_id";

				$routes_result = $conn->query($routes_query);

				$routes = array();
				
				if($routes_result->num_rows > 0) {

					//$routes['route_code'] = $user_data['actual_route_code'];
					$data['routes_available'] = 1;

					$i = 0;
					while ($row = $routes_result->fetch_assoc()) {
						if(!isset($routes[$row['journey_type']])) {
							$i = 0;
						}
						// /*
						if((!isset($routes[$row['journey_type']]) || !isset($routes[$row['journey_type']]['start_stages'])) || !in_array($row['start_stage_code'], $routes[$row['journey_type']]['start_stages'])) {
						//$routes[$row['journey_type']]['start_stages'][$i] = $row['start_stage_code'];
						$routes[$row['journey_type']]['start_stages'][$i] = $row['start_stage_code'];
						$i++;
						}
						$routes[$row['journey_type']]['end_stages'][$row['start_stage_code']][] = $row['end_stage_code'];

						$routes[$row['journey_type']]['fare_km'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_km'];
						$routes[$row['journey_type']]['fare_full'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_full'];
						$routes[$row['journey_type']]['fare_half'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_half'];
						$routes[$row['journey_type']]['fare_luggage'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_luggage'];
					}
				} else {
					$data['routes_available'] = 0;
				}

				$data['routes'] = $routes;
			//-----------------------------------------------------------------------------------------------------------------------------------------------------------

			$data['status']			= '1';
			$data['message']		= 'Login Successfull';
		} else {
			$data['message']		= 'Wrong username or password';

			while ($row = $device_result->fetch_assoc()){
				$user_data = $row;
			}
			
			// Get initial login attempt count from database
			$login_attempts = ($user_data['device_login_attempts']) ?: 1;

			$todays_date = date('Y-m-d'); 
			
			//remove time from date
			$last_login_attempt  = date('Y-m-d', strtotime($user_data['modified_dt'])); 
			
			// Check if it is not first login attempt
			if($user_data['device_login_attempts'] > 0) {

				// Check if login attempt is from same day
				if(strtotime($todays_date) == strtotime($last_login_attempt)) {
					$login_attempts++;
				} else {
					$login_attempts = 1;
				}
			}
		}
		
		//Update login details in vehicle_device_access table
		$sql = "UPDATE `vehicle_device_access` SET `latitude` = '".$_POST['latitude']."',"; 
		
		if($data['status'] == '1') {
		$sql .= "`device_last_login` = '".date('Y-m-d h:i:s')."',";	
		$login_attempts = 0;
		}
		$sql .= "`longitude` = '".$_POST['longitude']."', `altitude` = '', `altutude_accuracy` = '', `device_login_attempts` = '".$login_attempts."' WHERE `device_imie` = '".$_POST['imei']."'";

		$conn->query($sql);
	} else {
		$data['message'] = 'Device does not exist on system. Please contact Operator!';
	}

	//Record the login attempts in login_attempts table
	$sql = "INSERT INTO `login_attempts` (`username`, `password`, `imei`, `latitude`, `longitude`) VALUES ('".$_POST['username']."', '".$_POST['password']."', '".$_POST['imei']."', '".$_POST['latitude']."', '".$_POST['longitude']."')";

	$conn->query($sql);
 
	echo json_encode($data);
 
});

//Location log of the vehicle
$app->post('/position_log', function() {

	require_once('db.php');

	$sql = "INSERT INTO `location_log` (`vehical_code`, `route_code`, `conductor_code`, `latitude`, `longitude`, `altitude`, `altutude_accuracy`, `heading`, `speed`, `timestamp`) VALUES ('".$_POST['vehicle_code']."', '".$_POST['route_code']."', '".$_POST['conductor_code']."', '".$_POST['latitude']."', '".$_POST['longitude']."', '".$_POST['altitude']."', '".$_POST['altutude_accuracy']."', '".$_POST['heading']."', '".$_POST['speed']."', '".$_POST['timestamp']."')";

	if ($conn->query($sql) === TRUE) {
		$data['status'] = '1';
		$data['message'] = "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;

		$data['status'] = '0';
		$data['message'] = "Error: " . $sql . "<br>" . $conn->error;
	}

	echo json_encode($data);
});



// Get available routes stages
$app->post('/routes_stages', function() {
	//echo "======>>> <pre>"; print_r($_POST); exit;

	require_once('db.php');

	//Get start stages
	$query = "select * from route_fare_matrix WHERE route_code = '". $_POST['route_code']."' ORDER BY row_id";

	$result = $conn->query($query);

	$data['status'] = 0;
	//$data['query'] = $query;

	if($result->num_rows > 0) {

		$data['status'] = 1;
		$data['message'] = "Route details found successfully!";

		$data['route_code'] = $_POST['route_code'];
		
		$i = 0;
		while ($row = $result->fetch_assoc()) {
			
			if(!in_array($row['start_stage_code'], $data['start_stages'])) {
			$data['start_stages'][$i] = $row['start_stage_code'];
			$i++;
			}
			$data['end_stages'][$row['start_stage_code']][] = $row['end_stage_code'];

			//$data['end_stages'][$row['start_stage_code']][$row['end_stage_code']]['name'] = $row['end_stage_code'];
			$data['fare_km'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_km'];
			$data['fare_full'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_full'];
			$data['fare_half'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_half'];
			$data['fare_luggage'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_luggage'];

			//$data['start_stages'][$row['start_stage_code']]['position'] = $i;

			
		}
	} else {
		$data['message'] = "No Routes data found! Please contact administrator.";		
	}

	echo json_encode($data);
});

// Get available routes stages
$app->post('/routes_stages2', function() {
	//echo "======>>> <pre>"; print_r($_POST); exit;

	require_once('db.php');

	//Get start stages
	$query = "select * from route_fare_matrix WHERE route_code = '". $_POST['route_code']."' ORDER BY row_id";

	$result = $conn->query($query);

	$data['status'] = 0;
	//$data['query'] = $query;

	if($result->num_rows > 0) {

		$data['status'] = 1;
		$data['message'] = "Route details found successfully!";

		$data['route_code'] = $_POST['route_code'];
		
		$i = 0;
		while ($row = $result->fetch_assoc()) {
			
			if(!in_array($row['start_stage_code'], $data['start_stages'])) {
			$data['start_stages'][$i] = $row['start_stage_code'];
			$i++;
			}
			$data['end_stages'][$row['start_stage_code']][] = $row['end_stage_code'];

			//$data['end_stages'][$row['start_stage_code']][$row['end_stage_code']]['name'] = $row['end_stage_code'];
			$data['fare_km'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_km'];
			$data['fare_full'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_full'];
			$data['fare_half'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_half'];
			$data['fare_luggage'][$row['start_stage_code']][$row['end_stage_code']] = $row['fare_luggage'];

			//$data['start_stages'][$row['start_stage_code']]['position'] = $i;

			
		}
	} else {
		$data['message'] = "No Routes data found! Please contact administrator.";		
	}

	echo json_encode($data);
});

//Book ticket
$app->post('/book_ticket', function() {

	require_once('db.php');
	
	//--------------------------Get user and operator id------------------------------
	
	//Check if device is registered
	$device_query = "select * from `vehicle_device_access` WHERE row_id = '".$_POST['created_by']."'";

	$device_result = $conn->query($device_query);


	while ($row = $device_result->fetch_assoc()){
		$user_data = $row;
	}
	
	//--------------------------------------------------------

	$sql = "INSERT INTO `ticket_bookings` (`booking_reference`, `operator_id`, `route_code`, `start_stage`, `end_stage`, `fare_full_passengers`, `fare_full_cost`, `fare_half_passengers`, `fare_half_cost`, `fare_luggage`, `fare_luggage_cost`, `total_fare`, `mobile`, `booking_time`, `created_by`) VALUES ('".$_POST['booking_reference']."', '".$user_data['operator_id']."', '".$_POST['route_code']."', '".$_POST['start_stage']."', '".$_POST['end_stage']."', '".$_POST['fare_full_passengers']."', '".$_POST['fare_full_cost']."', '".$_POST['fare_half_passengers']."', '".$_POST['fare_half_cost']."', '".$_POST['fare_luggage']."', '".$_POST['fare_luggage_cost']."', '".$_POST['discounted_total']."', '".$_POST['mobile']."', '".date('Y-m-d h:i:s', strtotime($_POST['booking_time']))."', '".$_POST['created_by']."')";

	if ($conn->query($sql) === TRUE) {
		$data['status'] = '1';
		$data['message'] = "Ticket booked successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;

		$data['status'] = '0';
		$data['message'] = "Error: " . $sql . "<br>" . $conn->error;
	}

	echo json_encode($data);

});


//Book ticket called after time inverval and inserts muliple records
$app->post('/book_ticket_call', function() {

	require_once('db.php');
	
	$is_first = 0;

	$sql = "INSERT IGNORE INTO `ticket_bookings` (`booking_reference`, `vehicle_number`, `operator_id`, `route_code`, `start_stage`, `end_stage`, `fare_full_passengers`, `fare_full_cost`, `fare_half_passengers`, `fare_half_cost`, `fare_luggage`, `fare_luggage_cost`, `total_fare`, `mobile`, `booking_time`, `created_by`) VALUES ";
	
	$tickets = json_decode('['.$_POST['ticket_data'].']', true);

	foreach($tickets as $ticket) {
		//echo "=====>>>>>>> <pre>"; print_r();

		//$ticket_data = json_decode($ticket, true);

		$sql .= ($is_first++ == 0) ? '': ', ';
		$sql .= "('".$ticket['booking_reference']."', '".$ticket['vehicle_number']."', '".$ticket['operator_id']."', '".$ticket['route_code']."', '".$ticket['start_stage']."', '".$ticket['end_stage']."', '".$ticket['fare_full_passengers']."', '".$ticket['fare_full_cost']."', '".$ticket['fare_half_passengers']."', '".$ticket['fare_half_cost']."', '".$ticket['fare_luggage']."', '".$ticket['fare_luggage_cost']."', '".$ticket['discounted_total']."', '".$ticket['mobile']."', '".date('Y-m-d h:i:s', strtotime($ticket['booking_time']))."', '".$ticket['created_by']."')";
	}
	
	//$data['sql'] = $sql;
	//$data['ticket_data'] = tickets;

	if ($conn->query($sql) === TRUE) {
		$data['status'] = '1';
		$data['message'] = "Ticket booked successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;

		$data['status'] = '0';
		$data['message'] = "Error: " . $sql . "<br>" . $conn->error;
	}
	
	
	
	echo json_encode($data);

});


//Book ticket called after time inverval and inserts muliple records
$app->post('/book_ticket_call1', function() {

	require_once('db.php');
	
	$data['status'] = '1';
	$data['message'] = $_POST;
	
	echo json_encode($data);

});


//--------------------------------------- All get call ---------------------------------------------------

//Update imei in the records
$app->get('/update_imei', function() {
	require_once('db.php');

	//echo "==========>>>>>>>>>> <pre>"; print_r($_GET); exit;

	
	
	//Update login details
	$sql = "UPDATE `vehicle_device_access` SET `device_imie` = '".'356513084790874'."' WHERE `row_id` = '".'1'."'"; 

		$conn->query($sql);

});

//Home page call
$app->get('/', function() {

	$data['welcom'] = 'Welcome to Tech Bus Ticketing';

	echo json_encode($data);

});

// Get available routes
$app->get('/data', function() {

	require_once('db.php');

	$query = "select * from ".$_GET['table'];

	$result = $conn->query($query);

	while ($row = $result->fetch_assoc()) {
		$data[] = $row;
	}

	echo json_encode($data);
});

// Get available routes
$app->get('/routes', function() {

	require_once('db.php');

	$query = "select * from master_routes order by route_code";

	$result = $conn->query($query);

	while ($row = $result->fetch_assoc()) {
		$data[] = $row;
	}

	echo json_encode($data);
});

// Get ticket booking listing
$app->get('/bookings', function() {

	require_once('db.php');

	$query = "select * from `ticket_bookings`";

	$result = $conn->query($query);

	while ($row = $result->fetch_assoc()){
		$data[] = $row;
	}

	echo json_encode($data);
});

// Get ticket booking listing
$app->get('/attempts', function() {

	require_once('db.php');

	$query = "select * from `login_attempts`";
	
	$data = array();
	if ($result = $conn->query($query)) {

		while ($row = $result->fetch_assoc()){
			$data[] = $row;
		}

		echo json_encode($data);
	} else  {
		echo("Error description: " . mysqli_error($conn));
	}

	echo json_encode($data);
});

// Get request for vehicletypes
$app->get('/types', function() {

	require_once('db.php');

	$query = "select * from vehicle_types order by vehicle_type";

	//$result = $conn->query($query);

	if ($result = $conn->query($query)) {

		while ($row = $result->fetch_assoc()){
			$data[] = $row;
		}

		echo json_encode($data);
	} else  {
		echo("Error description: " . mysqli_error($conn));
	}

	
});

// Get request for vehicletypes
$app->get('/device_access', function() {

	require_once('db.php');

	$query = "select * from `vehicle_device_access` order by row_id";

	$result = $conn->query($query);

	while ($row = $result->fetch_assoc()){
		$data[] = $row;
	}

	echo json_encode($data);
});



// Get request for position log
$app->get('/list_positions', function() {

	require_once('db.php');

	echo $query = "select * from `location_log` order by row_id DESC";

	$result = $conn->query($query);

	while ($row = $result->fetch_assoc()) {
		$data[] = $row;
	}

	echo json_encode($data);
});

// List all booking for device
$app->get('/list_booking', function() {

	require_once('db.php');

	$query = "select * from ticket_booking order by row_id DESC";

	$result = $conn->query($query);

	while ($row = $result->fetch_assoc()){
	$data[] = $row;
	}
	echo json_encode($data);

});
