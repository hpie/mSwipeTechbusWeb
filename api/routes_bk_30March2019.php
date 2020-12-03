<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
//Manage the api access log
function log_access($conn = "", $access_action = "", $user_id = "", $latitude="", $longitude="") {
	
	//require_once('db.php');

	//echo "hiiiiiii"; 
	$user_data = "";
	$username = "";
	$operator_id = "";
	$device_imie = "";


	if(isset($user_id)) {
		$device_query = "select * from `vehicle_device_access` WHERE row_id = '".$user_id."'";

		$device_result = $conn->query($device_query);


		while ($row = $device_result->fetch_assoc()){
			$user_data = $row;
		}
	}

	if($user_data) {
		$username = $user_data['loginid'];
		$operator_id = $user_data['operator_id'];
		$device_imie = $user_data['device_imie'];

		 //echo "=====>>>>>>> <pre>"; exit;
	}

	//Check if device is registered
	$access_query = "select * from `last_device_access` WHERE access_action = '".$access_action."' AND user_id = '".$user_id."'";

	$access_result = $conn->query($access_query);
	/*
	if($access_result->num_rows > 0) {
		$sql = "UPDATE `last_device_access` SET `latitude` = '".$latitude."', `longitude` = '".$longitude."' WHERE `access_action` = '".$access_action."' AND `user_id` = '".$user_id."'";

		$conn->query($sql);
	} else {
		*/

		//Record the login attempts in login_attempts table
		$sql = "INSERT INTO `last_device_access` (`user_id`, `username`, `operator_id`, `device_imie`, `access_action`, `latitude`, `longitude`, `created_by`) VALUES ('".$user_id."','".$username."','".$operator_id."', '".$device_imie."', '".$access_action."', '".$latitude."', '".$longitude."', '".$user_id."')";

		$conn->query($sql);

	// }
}

// Login functionality
$app->post('/login2', function() {

	require_once('db.php');

	//Check if device is registered
	$device_query = "select * from `vehicle_device_access` WHERE device_imie = '".$_POST['imei']."'";

	$device_result = $conn->query($device_query);
	
	// Initially success parameter set to zero
	$data['status'] = '0';

	$data['post'] = $_POST;

	if($device_result->num_rows > 0) {
		
		$query = "select * from `vehicle_device_access` WHERE loginid = '".$_POST['username']."' AND PASSWORD = '".$_POST['password']."' AND device_imie = '".$_POST['imei']."'";

		$query = "select vda.*, mr.route_code as actual_route_code, vo.operator_name, vo.operator_address1, vo.operator_address2, vo.operator_city, vo.operator_helpline, vm.vehicle_number, vm.vehicle_type, vt.vehicle_basefare  from `vehicle_device_access` vda JOIN `master_routes` mr ON mr.row_id = vda.route_id JOIN `vehicle_operators` vo ON vo.row_id = vda.operator_id JOIN `vehicle_master` vm ON vm.row_id = vda.vehicle_id JOIN `vehicle_types` vt ON vt.vehicle_type = vm.vehicle_type WHERE loginid = '".$_POST['username']."' AND PASSWORD = '".$_POST['password']."' AND device_imie = '".$_POST['imei']."'"; //exit;

		$data['user_query'] = $query;

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
			$data['operator_address1']	= $user_data['operator_address1'];
			$data['operator_address2']	= $user_data['operator_address2'];
			$data['operator_city']	= $user_data['operator_city'];
			$data['operator_helpline']	= $user_data['operator_helpline'];
			$data['vehicle_code']	= $user_data['vehicle_id'];
			$data['vehicle_number']	= $user_data['vehicle_number'];
			$data['vehicle_type']	= $user_data['vehicle_type'];
			$data['min_ticket']		= $user_data['vehicle_basefare'];
			$data['todays_date']	= date('Y-m-d');
			$data['housekeeping']	= $user_data['housekeeping'];

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
				
				$data['routes_query'] = $routes_query;

				$routes_result = $conn->query($routes_query);

				$routes = array();
				$data['ticket_message'] = "Have a nice Journey.";
				
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

						if($row['operator_message'] != "") {
							$data['ticket_message'] = $row['operator_message'];
						}
					}
				} else {
					$data['routes_available'] = 0;
				}

				$data['routes'] = $routes;
			//-----------------------------------------------------------------------------------------------------------------------------------------------------------

			$data['status']			= '1';
			$data['message']		= 'Login Successfull';

			log_access($conn, 'login', $user_data['row_id'], $_POST['latitude'], $_POST['longitude']);
			log_access($conn, 'log_position', $user_data['row_id'], $_POST['latitude'], $_POST['longitude']);
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
		$sql .= "`device_model` = '".$_POST['device_model']."',";	
		$sql .= "android_version = '".$_POST['android_version']."',";	
		
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
$app->post('/housekeeping', function() {
	require_once('db.php');

	$sql = "UPDATE `vehicle_device_access` SET `housekeeping` = '0'"; 
	
	$sql .= " WHERE `device_imie` = '".$_POST['imei']."'";	

	$conn->query($sql);
	
	$data['status']  = '1';
	$data['sql']     = $sql;
	$data['message'] = 'Housekeeping carried out successfully';

	log_access($conn, 'housekeeping', $_POST['user_id'], $_POST['longitude'], $_POST['longitude']);

	echo json_encode($data);

});

//Location log of the vehicle
$app->post('/position_log', function() {

	require_once('db.php');

	$sql = "INSERT INTO `location_log` (`vehical_code`, `user_id`, `username`, `device_imie`, `route_code`, `conductor_code`, `latitude`, `longitude`, `altitude`, `altutude_accuracy`, `heading`, `speed`, `timestamp`) VALUES ('".$_POST['vehicle_code']."', '".$_POST['user_id']."','".$_POST['username']."','".$_POST['device_imie']."','".$_POST['route_code']."', '".$_POST['conductor_code']."', '".$_POST['latitude']."', '".$_POST['longitude']."', '".$_POST['altitude']."', '".$_POST['altutude_accuracy']."', '".$_POST['heading']."', '".$_POST['speed']."', '".$_POST['timestamp']."')";

	if ($conn->query($sql) === TRUE) {
		$data['status'] = '1';
		$data['message'] = "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;

		$data['status'] = '0';
		$data['message'] = "Error: " . $sql . "<br>" . $conn->error;
	}

	if($_POST['vehicle_code']) {
		$device_query = "select * from `vehicle_device_access` WHERE vehicle_id = '".$_POST['vehicle_code']."'";

		$device_result = $conn->query($device_query);


		while ($row = $device_result->fetch_assoc()){
			$user_data = $row;
		}
	}
	$user_id = (isset($_POST['user_id'])) ? $_POST['user_id'] : $user_data['row_id'];
	log_access($conn, 'log_position', $user_id, $_POST['latitude'], $_POST['longitude']);
	echo json_encode($data);
});


//Book ticket called after time inverval and inserts muliple records
$app->post('/book_ticket_call', function() {

	require_once('db.php');
	
	$is_first = 0;

	$sql = "INSERT IGNORE INTO `ticket_bookings` (`booking_reference`, `ticket_number`, `vehicle_number`, `operator_id`, `route_code`, `start_stage`, `end_stage`, `fare_full_passengers`, `fare_full_cost`, `fare_half_passengers`, `fare_half_cost`, `fare_luggage`, `fare_luggage_cost`, `total_fare`, `mobile`, `booking_time`, `created_by`) VALUES ";
	
	$tickets = json_decode('['.$_POST['ticket_data'].']', true);

	foreach($tickets as $ticket) {
		//echo "=====>>>>>>> <pre>"; print_r();

		//$ticket_data = json_decode($ticket, true);

		$sql .= ($is_first++ == 0) ? '': ', ';
		$sql .= "('".$ticket['booking_reference']."', '".$ticket['ticket_number']."', '".$ticket['vehicle_number']."', '".$ticket['operator_id']."', '".$ticket['route_code']."', '".$ticket['start_stage']."', '".$ticket['end_stage']."', '".$ticket['fare_full_passengers']."', '".$ticket['fare_full_cost']."', '".$ticket['fare_half_passengers']."', '".$ticket['fare_half_cost']."', '".$ticket['fare_luggage']."', '".$ticket['fare_luggage_cost']."', '".$ticket['discounted_total']."', '".$ticket['mobile']."', '".date('Y-m-d h:i:s', strtotime($ticket['booking_time']))."', '".$ticket['created_by']."')";
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
	
	if($_POST['user_id'] != '' && $_POST['user_id'] > 0) {
	log_access($conn, 'book_ticket', $_POST['user_id'], $_POST['latitude'], $_POST['longitude']);
	}
	echo json_encode($data);

});

/*
// when upload log button clicked in app update ticket log in ticket booking table
function update_book_ticket($ticket_data) {

	require_once('db.php');
	
	$is_first = 0;

	$sql = "INSERT IGNORE INTO `ticket_bookings` (`booking_reference`, `vehicle_number`, `operator_id`, `route_code`, `start_stage`, `end_stage`, `fare_full_passengers`, `fare_full_cost`, `fare_half_passengers`, `fare_half_cost`, `fare_luggage`, `fare_luggage_cost`, `total_fare`, `mobile`, `booking_time`, `created_by`) VALUES ";
	
	$tickets = json_decode('['.$ticket_data.']', true);

	foreach($tickets as $ticket) {

		$sql .= ($is_first++ == 0) ? '': ', ';
		$sql .= "('".$ticket['booking_reference']."', '".$ticket['vehicle_number']."', '".$ticket['operator_id']."', '".$ticket['route_code']."', '".$ticket['start_stage']."', '".$ticket['end_stage']."', '".$ticket['fare_full_passengers']."', '".$ticket['fare_full_cost']."', '".$ticket['fare_half_passengers']."', '".$ticket['fare_half_cost']."', '".$ticket['fare_luggage']."', '".$ticket['fare_luggage_cost']."', '".$ticket['discounted_total']."', '".$ticket['mobile']."', '".date('Y-m-d h:i:s', strtotime($ticket['booking_time']))."', '".$ticket['created_by']."')";
	}

	if ($conn->query($sql) === TRUE) {
		return 1;
	} else {
		return 0;
	}
}
*/

//Log upload functionlaity
$app->post('/upload_logs', function() {

	require_once('db.php');

	//echo "=====>>>>>>> <pre>"; print_r($_POST); exit;
	
	$user_id = $_POST['user_id'];

	$latitude  = $_POST['latitude'];
	$longitude = $_POST['longitude'];

	unset($_POST['user_id']);
	unset($_POST['latitude']);
	unset($_POST['longitude']);
	
	$dir = 'uploads/'.$user_id;

	if (!file_exists($dir)) {
		mkdir($dir, 0777, true);
	}

	$ticket_logs = 'uploads/'.$user_id.'/ticket_logs';

	if (!file_exists($ticket_logs)) {
		mkdir($ticket_logs, 0777, true);
	}

	$crash_reports_dir = 'uploads/'.$user_id.'/crash_reports';

	if (!file_exists($crash_reports_dir)) {
		mkdir($crash_reports_dir, 0777, true);
	}

	$location_logs_dir = 'uploads/'.$user_id.'/location_logs';

	if (!file_exists($location_logs_dir)) {
		mkdir($location_logs_dir, 0777, true);
	}

	$activity_logs_dir = 'uploads/'.$user_id.'/activity_logs';

	if (!file_exists($activity_logs_dir)) {
		mkdir($activity_logs_dir, 0777, true);
	}
	
	//success all tickets updated
	$is_updated = 1;
	$success = 1;
	foreach($_POST as $key => $file) {
		
		$file_ext_arr = explode('_', $key);

		if(in_array('txt', $file_ext_arr)) {
			$upload_dir = $ticket_logs;

			//$success = update_book_ticket($file);

			//---------------------------------------------------------------------------------------
			$is_first = 0;

			$sql = "INSERT IGNORE INTO `ticket_bookings` (`booking_reference`, `vehicle_number`, `operator_id`, `route_code`, `start_stage`, `end_stage`, `fare_full_passengers`, `fare_full_cost`, `fare_half_passengers`, `fare_half_cost`, `fare_luggage`, `fare_luggage_cost`, `total_fare`, `mobile`, `booking_time`, `created_by`) VALUES ";
			
			$tickets = json_decode('['.$file.']', true);

			foreach($tickets as $ticket) {

				$sql .= ($is_first++ == 0) ? '': ', ';
				$sql .= "('".$ticket['booking_reference']."', '".$ticket['vehicle_number']."', '".$ticket['operator_id']."', '".$ticket['route_code']."', '".$ticket['start_stage']."', '".$ticket['end_stage']."', '".$ticket['fare_full_passengers']."', '".$ticket['fare_full_cost']."', '".$ticket['fare_half_passengers']."', '".$ticket['fare_half_cost']."', '".$ticket['fare_luggage']."', '".$ticket['fare_luggage_cost']."', '".$ticket['discounted_total']."', '".$ticket['mobile']."', '".date('Y-m-d h:i:s', strtotime($ticket['booking_time']))."', '".$ticket['created_by']."')";
			}

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			} else {
				$success = 0;
			}
			//------------------------------------------------------------------------------------------
			
			$is_updated = $is_updated * $success;

		} else if(in_array('STACKTRACE', $file_ext_arr)) {
			$upload_dir = $crash_reports_dir;
		} else if(in_array('loc', $file_ext_arr)) {
			$upload_dir = $location_logs_dir;
		} else if(in_array('log', $file_ext_arr)) {
			$upload_dir = $activity_logs_dir;
		} else {
			$upload_dir = $dir;
		}
		$myfile = fopen($upload_dir."/".$key. ".txt", "w") or die("Unable to open file!");
		$txt = $file;
		fwrite($myfile, $txt);
		fclose($myfile);
	}

	log_access($conn, 'upload_logs', $user_id, $latitude, $longitude);
	
	$data['status'] = $is_updated;
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

	log_access($conn, 'test', '1');

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

// List all booking for device
$app->get('/test_upload', function() {

	$file = '{"booking_reference":"356513084790874_190313130114+0530","route_code":"R-102","ticket_number":0,"start_stage":"Shimla","end_stage":"Shoghi","fare_full_cost":"26.0","fare_half_cost":"11.0","fare_luggage_cost":"23.0","fare_full_passengers":"3","fare_half_passengers":"3","fare_luggage":"0","mobile":"","booking_time":"2019-03-13 13:01:14","total_fare":"111","discount":"0","discount_applied":0,"discounted_total":111,"vehicle_number":"HP03A1111","operator_id":"1","created_by":"1"},{"booking_reference":"356513084790874_190313130118+0530","route_code":"R-102","ticket_number":1,"start_stage":"Shimla","end_stage":"Shoghi","fare_full_cost":"26.0","fare_half_cost":"11.0","fare_luggage_cost":"23.0","fare_full_passengers":"2","fare_half_passengers":"4","fare_luggage":"0","mobile":"","booking_time":"2019-03-13 13:01:18","total_fare":"96","discount":"0","discount_applied":0,"discounted_total":96,"vehicle_number":"HP03A1111","operator_id":"1","created_by":"1"},{"booking_reference":"356513084790874_190313140032+0530","route_code":"R-102","ticket_number":2,"start_stage":"Shimla","end_stage":"Shoghi","fare_full_cost":"26.0","fare_half_cost":"11.0","fare_luggage_cost":"23.0","fare_full_passengers":"2","fare_half_passengers":"2","fare_luggage":"0","mobile":"","booking_time":"2019-03-13 14:00:32","total_fare":"74","discount":"0","discount_applied":0,"discounted_total":74,"vehicle_number":"HP03A1111","operator_id":"1","created_by":"1"},{"booking_reference":"356513084790874_190313140036+0530","route_code":"R-102","ticket_number":3,"start_stage":"Shimla","end_stage":"Shoghi","fare_full_cost":"26.0","fare_half_cost":"11.0","fare_luggage_cost":"23.0","fare_full_passengers":"3","fare_half_passengers":"4","fare_luggage":"0","mobile":"","booking_time":"2019-03-13 14:00:36","total_fare":"122","discount":"0","discount_applied":0,"discounted_total":122,"vehicle_number":"HP03A1111","operator_id":"1","created_by":"1"}';

	echo "Result : " . $success = update_book_ticket($file);

});



