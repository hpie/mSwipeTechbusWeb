<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class RouteMatrix extends Admin_Controller {



	public function __construct()

	{

		parent::__construct();

		$this->load->library('form_builder');

	}



	// Frontend DEVICES CRUD
	public function index()
	{

		$this->load->model('user_model', 'users');

		$this->mViewData['count'] = array(

			'users' => $this->users->count_all(),

		);

		$this->render('home');

	}
	
	
	// View RouteMatrix
	public function ViewRouteMatrix()

	{

		$loggedinUser = $this->mUser;

		$crud = $this->generate_crud('route_fare_matrix');

		$crud->columns('route_code', 'route_start_stage', 'route_end_stage', 'start_stage_code', 'end_stage_code', 'fare_km', 'fare_full', 'fare_half', 'fare_luggage', 'vehicle_type');

		//Show for add

		//$crud->add_fields('operator_id', 'route_code', 'stage_code', 'stage_name', 'stage_km', 'stage_no', 'stage_status');


		//Show only for Update

		//$crud->edit_fields('stage_code', 'stage_name', 'stage_km', 'stage_no', 'stage_status');

			
		//$crud->field_type('stage_status','dropdown',array('ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE','DELETED'=>'DELETED'));	



		//$crud->field_type('created_by', 'hidden', $loggedinUser->username);

		//$crud->field_type('modified_by', 'hidden', $loggedinUser->username);

		
		//how to add others? and create a new record if others

		//$crud->set_relation('operator_id','vehicle_operators','{operator_name}-{operator_city}',array('operator_status' => 'ACTIVE'), 'operator_name ASC');

		

		//how to add others? and create a new record if others

		//$crud->set_relation('route_code','master_routes','{route_start_stage}-{route_end_stage}',array('route_status' => 'ACTIVE'), 'route_code, route_start_stage ASC');

		

		/*

		// only webmaster and admin can change member groups

		if ($crud->getState()=='list' || $this->ion_auth->in_group(array('webmaster', 'admin')))

		{

			$crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');

		}



		// only webmaster and admin can reset user password

		if ($this->ion_auth->in_group(array('webmaster', 'admin')))

		{

			$crud->add_action('Reset Password', '', 'admin/user/reset_password', 'fa fa-repeat');

		}

		*/

		

		// disable direct create / delete Frontend User

		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();



		$this->mPageTitle = 'Operator Route - Stage Fare';

		$this->render_crud();

	}

	// Create RouteMatrix for Operator and Route and its Stages
	public function CreateRouteMatrix()
	{
		$form = $this->form_builder->create_form();

		if ($form->validate())
		{
			//echo "========>>>>>>>> <pre>"; print_r($_POST); exit;
			$this->generate_route_matrix($_POST['routes'], $_POST['operator_id'], $_POST['vehicle_types']);
		}

		// get list of Frontend user groups
		$this->load->model('group_model', 'groups');
		$this->mViewData['groups'] = $this->groups->get_all();
		$this->mViewData['operators'] = $this->db->get('vehicle_operators')->result();


		$this->mPageTitle = 'Generate Route Fare Matrix';

		$this->mViewData['form'] = $form;
		$this->render('route_fare_matrix');
	}

	// Get operator routes
	public function get_routes()
	{
		$routes_data = $this->db->get_where('master_routes', array('operator_id' => $this->input->post('operator_id')))->result();

		$routes_select = '<label for="routes">Routes</label> <select id="routes" name="routes" class="form-control"> <option value="" disabled selected>Please select Route</option>';


		foreach ($routes_data as $routes) {
			$routes_select .= '<option value="'.$routes->route_code.'">'.$routes->route_code.'</option>';
		}
							
		$routes_select .= '</select>';
		
		echo $routes_select;
						
	}

	// Get operator routes
	public function get_vehicle_types()
	{
		//$vehicles_data = $this->db->get_where('vehicle_master', array('operator_id' => $this->input->post('operator_id')))->result();

		$this->db->distinct();
		$this->db->select('vehicle_type');
		$this->db->from('vehicle_master');
		$this->db->where('operator_id', $this->input->post('operator_id'));

		$vehicles_data = $this->db->get()->result();

		$vehicle_type_select = '<label for="vehicle_types">Vehicle Type</label> <select id="vehicle_types" name="vehicle_types" class="form-control"> <option value="" disabled selected>Please select Vehicle Type</option>';


		foreach ($vehicles_data as $vehicle) {
			$vehicle_type_select .= '<option value="'.$vehicle->vehicle_type.'">'.$vehicle->vehicle_type.'</option>';
		}
							
		$vehicle_type_select .= '</select>';
		
		echo $vehicle_type_select;
						
	}

	// Get operator routes
	public function generate_route_matrix($route_code = "", $operator_id = "", $vehicle_type = "")
	{
		// get data
		//$route_code="R-301";
		//$operator_id="3";
		//$vehicle_type="ORDINARY";

		$route_start_stage="";
		$route_end_stage="";
		$route_type="";

		$fare_full=0;
		$fare_half=0;
		$fare_luggage=0;

		$flag = FALSE;

		$fareQuery= " SELECT
		fare_full,
		fare_half,
		fare_luggage,
		vehicle_type
		FROM
		vehicle_fare_master
		WHERE
		operator_id='$operator_id'
		AND
		vehicle_type='$vehicle_type'; ";

		$routeQuery = "SELECT 
		row_id,
		route_code,
		route_start_stage,
		route_end_stage,
		route_type,
		route_stop_count,
		route_status,
		operator_id
		FROM
		master_routes
		WHERE
		route_code='$route_code'
		AND
		route_status='ACTIVE'
		AND
		operator_id='$operator_id'";

		$result = $this->db->query($fareQuery)->result_array();
		
		echo ("<table> <tr> <td>Full</td> <td>Half</td> <td>Luggage</td> <td>Vehicle</td> </tr>" );	

		//while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) 
		foreach($result as $row)
		{    
			//echo ($row['stage_code']); 
			 echo ("<tr> <td>".$row['fare_full']."</td> <td>".$row['fare_half']."</td> <td>".$row['fare_luggage']."</td> <td>".$row['vehicle_type']."</td> </tr>");  	
			 $fare_full=$row['fare_full'];
			 $fare_half=$row['fare_half'];
			 $fare_luggage=$row['fare_luggage'];
		}
		echo ("</table> <hr />");


		//Array for Matrix
		$route_id = 0;
		$startRoutArray = [];
		$endRoutArray = [];

		//ROUTE
		$result = $this->db->query($routeQuery)->result_array();

		$flag = FALSE;
		echo ("<table> <tr> <td>Route ID</td> <td>Route Code</td> <td>Route Type</td> <td>Start Stage</td> <td>End Stage</td> <td>Operator</td> </tr>" );
		$index=0;
		$routlist = array();	
		foreach($result as $row)
		{    
			//echo ($row['stage_code']); 
			 echo ("<tr> <td>".$row['row_id']."</td> <td>".$row['route_code']."</td> <td>".$row['route_type']."</td> <td>".$row['route_start_stage']."</td> <td>".$row['route_end_stage']."</td> <td>".$row['operator_id']."</td> </tr>");

			 $route_id = $row['row_id'];
			 $route_type= $row['route_type'];
			 $route_start_stage = $row['route_start_stage'];
			 $route_end_stage = $row['route_end_stage'];
		}
		echo ("</table> <hr />");


		$routeStageQuery = "SELECT 

		route_id,

		stage_no,

		stage_code,

		stage_km,

		stage_status

		FROM

		route_stages

		WHERE

		route_id='$route_id'

		AND

		stage_status='ACTIVE'

		ORDER BY stage_no;";

		//ROUTE STAGE
		$result = $this->db->query($routeStageQuery)->result_array();

		$flag = FALSE;
		echo ("<table> <tr> <td>Route Code</td> <td>Stage No</td> <td>Stage Code</td> <td>Stage KM</td> </tr>" );
		$index=0;
		$routlist = array();		
		foreach($result as $row)
		{    
			 echo ("<tr> <td>".$row['route_id']."</td> <td>".$row['stage_no']."</td> <td>".$row['stage_code']."</td> <td>".$row['stage_km']."</td> </tr>");
			 $routarr = array("stage_no"=>$row['stage_no'], "stage_code"=>$row['stage_code'], "stage_km"=>$row['stage_km']);	
			 array_push($routlist,$routarr);
		}
		echo ("</table> <hr />");

		$query_count = 0;

		$transaction_queries = array();

		$transaction_queries[] = $delete_query= "DELETE FROM `route_fare_matrix` WHERE `route_code` = '".$route_code."' AND `vehicle_type` = '".$vehicle_type."' AND `operator_id` = '".$operator_id."'";

		///////////////////  GENERATE MATRIX /////////////////////////
		if($route_type=="TWOWAY")
		{

			$journey_type="ONWARD";
			//$routlistrev = $routlist;
			$arrlength = count($routlist);
			$rcount = 0;
			$insertsql = "INSERT INTO `route_fare_matrix`(`route_code`, `route_start_stage`, `route_end_stage`, `start_stage_code`, `end_stage_code`, `journey_type`, `fare_km`, `fare_full`, `fare_half`, `fare_luggage`, `vehicle_type`, `operator_id`) VALUES ";
			
			$insert_array = array();

			for($x = 0; $x < $arrlength; $x++) {
				$fromstage = $routlist[$x]['stage_code'];
				echo ("<table> <tr> <td>".$fromstage."</td> </tr>" ); 
				echo ("<tr>");
				for($y = $x+1; $y < $arrlength; $y++) {
					$rcount ++;
					$tostage = $routlist[$y]['stage_code'];
					$distance = $routlist[$y]['stage_km']-$routlist[$x]['stage_km'];
					$full_fare_val = $fare_full*$distance;
					$half_fare_val = $fare_half*$distance;
					$luggage_fare_val = $fare_luggage*$distance;
					
					// convet to positive value
					$distance = abs($distance);
					$full_fare_val = abs($full_fare_val);
					$half_fare_val = abs($half_fare_val);
					$luggage_fare_val = abs($luggage_fare_val);

					$insert_array[] = array(
						'route_code' =>  $route_code, 'route_start_stage' =>  $route_start_stage, 'route_end_stage' =>  $route_end_stage, 'start_stage_code' =>  $fromstage, 'end_stage_code' =>  $tostage, 'journey_type' =>  $journey_type, 'fare_km' =>  $distance, 'fare_full' =>  $full_fare_val, 'fare_half' =>  $half_fare_val, 'fare_luggage' =>  $luggage_fare_val, 'vehicle_type' =>  $vehicle_type, 'operator_id' => $operator_id
					);

					
				}
				echo ("<tr> </table>");
				
				echo ("<hr />");
			}
			$transaction_queries[] = $insert_array;
			
			$revroutlist = array_reverse($routlist);
			$journey_type="RETURN";
			$insertsql = "INSERT INTO `route_fare_matrix`(`route_code`, `route_start_stage`, `route_end_stage`, `start_stage_code`, `end_stage_code`, `journey_type`, `fare_km`, `fare_full`, `fare_half`, `fare_luggage`, `vehicle_type`, `operator_id`) VALUES ";
			
			$insert_array = array();

			for($x = 0; $x < $arrlength; $x++) {
				$fromstage = $revroutlist[$x]['stage_code'];
				echo ("<table> <tr> <td>".$fromstage."</td> </tr>" ); 
				echo ("<tr>");
				for($y = $x+1; $y < $arrlength; $y++) {
					$rcount ++;
					$tostage = $revroutlist[$y]['stage_code'];

					$distance = $revroutlist[$y]['stage_km']-$revroutlist[$x]['stage_km'];
					$full_fare_val = $fare_full*$distance;
					$half_fare_val = $fare_half*$distance;
					$luggage_fare_val = $fare_luggage*$distance;
					
					// convet to positive value
					$distance = abs($distance);
					$full_fare_val = abs($full_fare_val);
					$half_fare_val = abs($half_fare_val);
					$luggage_fare_val = abs($luggage_fare_val);

					$insert_array[] = array(
						'route_code' =>  $route_code, 'route_start_stage' =>  $route_start_stage, 'route_end_stage' =>  $route_end_stage, 'start_stage_code' =>  $fromstage, 'end_stage_code' =>  $tostage, 'journey_type' =>  $journey_type, 'fare_km' =>  $distance, 'fare_full' =>  $full_fare_val, 'fare_half' =>  $half_fare_val, 'fare_luggage' =>  $luggage_fare_val, 'vehicle_type' =>  $vehicle_type, 'operator_id' => $operator_id
					);

					
				
				}
				echo ("<tr> </table>");
				
				echo ("<hr />");
			}
			$transaction_queries[] = $insert_array;
			
		}else
		{
			$journey_type="CIRCULAR";
			//$routlistrev = $routlist;
			$arrlength = count($routlist);
			$rcount = 0;
			$insertsql = "INSERT INTO `route_fare_matrix`(`route_code`, `route_start_stage`, `route_end_stage`, `start_stage_code`, `end_stage_code`, `journey_type`, `fare_km`, `fare_full`, `fare_half`, `fare_luggage`, `vehicle_type`, `operator_id`) VALUES ";
			
			$insert_array = array();

			for($x = 0; $x < $arrlength; $x++) {
				$fromstage = $routlist[$x]['stage_code'];
				echo ("<table> <tr> <td>".$fromstage."</td> </tr>" ); 
				echo ("<tr>");
				for($y = $x+1; $y < $arrlength; $y++) {
					$rcount ++;
					$tostage = $routlist[$y]['stage_code'];
					//echo ("<td>".$tostage." <br />" );
					$distance = $routlist[$y]['stage_km']-$routlist[$x]['stage_km'];
					$full_fare_val = $fare_full*$distance;
					$half_fare_val = $fare_half*$distance;
					$luggage_fare_val = $fare_luggage*$distance;

					$insert_array[] = array(
						'route_code' =>  $route_code, 'route_start_stage' =>  $route_start_stage, 'route_end_stage' =>  $route_end_stage, 'start_stage_code' =>  $fromstage, 'end_stage_code' =>  $tostage, 'journey_type' =>  $journey_type, 'fare_km' =>  $distance, 'fare_full' =>  $full_fare_val, 'fare_half' =>  $half_fare_val, 'fare_luggage' =>  $luggage_fare_val, 'vehicle_type' =>  $vehicle_type, 'operator_id' => $operator_id
					);
				}
				echo ("<tr> </table>");
				
				echo ("<hr />");
			}
			$transaction_queries[] = $insert_array;
		}


		echo ("<hr /> <h3> Row Count ".$rcount."</h3>");

		$this->db->trans_start();		
		
		if(isset($transaction_queries[0])) {
			$this->db->query($transaction_queries[0]);
		}

		if(isset($transaction_queries[1])) {
			$this->db->insert_batch('route_fare_matrix', $transaction_queries[1]);
		}

		if(isset($transaction_queries[2])) {
			$this->db->insert_batch('route_fare_matrix', $transaction_queries[2]);
		}

		$this->db->trans_complete();
		
		exit;
		
	}

}

?>