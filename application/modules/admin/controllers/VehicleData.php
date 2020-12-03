<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class VehicleData extends Admin_Controller {



	public function __construct()

	{

		parent::__construct();

		$this->load->library('form_builder');

	}



	// Frontend VEHICLE CRUD

	public function index()
	{

		$this->load->model('user_model', 'users');

		$this->mViewData['count'] = array(

			'users' => $this->users->count_all(),

		);

		$this->render('home');

	}
	
	// VehicleMaster CRUD
	public function VehicleMaster()
	{
		$loggedinUser = $this->mUser;

		$crud = $this->generate_crud('vehicle_master');

		$crud->columns( 'operator_id', 'vehicle_number', 'vehicle_make', 'vehicle_model', 'vehicle_registration_date', 'vehicle_engine_no', 'vehicle_chassis_no', 'vehicle_capacity', 'vehicle_type', 'vehicle_status');

		//Show for add

		$crud->add_fields('operator_id', 'vehicle_number', 'vehicle_make', 'vehicle_model', 'vehicle_registration_date', 'vehicle_engine_no', 'vehicle_chassis_no', 'vehicle_capacity', 'vehicle_type', 'vehicle_status');
	

		//Show only for Update

		$crud->edit_fields('operator_id', 'vehicle_number', 'vehicle_make', 'vehicle_model', 'vehicle_registration_date', 'vehicle_engine_no', 'vehicle_chassis_no', 'vehicle_capacity', 'vehicle_type', 'vehicle_status');

		$crud->field_type('vehicle_status','dropdown',array('ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE','DELETED'=>'DELETED'));	

		
		//Assocoation with operator_id
		$crud->set_relation('operator_id','vehicle_operators','{row_id} [{operator_name}-{operator_city}]',array('operator_status' => 'ACTIVE'), 'operator_name ASC');

		//Assocoation with vehicle_type
		$crud->set_relation('vehicle_type','vehicle_types','{vehicle_type}  [{vehicle_basefare}]',array('vehicle_type_status' => 'ACTIVE'), 'vehicle_type ASC');


		// Action to get current location of bus

		if ($crud->getState()=='list' || $this->ion_auth->in_group(array('webmaster', 'admin')))

		{

			$crud->add_action('Track', '', 'admin/track/bus', 'fa fa-map-marker');

		}
		

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

		

		$state = $crud->getState();

		$state_info = $crud->getStateInfo();

		// add edit logic

		if ($state == 'add' || $state == 'insert_validation' || $state == 'insert')

		{

			//Mandatory Feilds

			$crud->required_fields('operator_id', 'vehicle_number', 'vehicle_type', 'vehicle_status');

			

			$crud->field_type('created_by', 'hidden', $loggedinUser->username);

		}

		elseif ($state == 'edit' || $state == 'update_validation' || $state == 'update')

		{

			//Mandatory Feilds

			$crud->required_fields('vehicle_type', 'vehicle_status');

			

				$crud->field_type('operator_id', 'readonly');

				$crud->field_type('vehicle_number', 'readonly');

				

			$crud->field_type('modified_by', 'hidden', $loggedinUser->username);

		}

		

		// disable direct create / delete Frontend User

		//$crud->unset_add();

		$crud->unset_delete();



		$this->mPageTitle = 'Vehicle Details';

		$this->render_crud();

	}
	
	
	// VehicleOperatorRoutes CRUD
	public function VehicleOperatorRoutes()
	{
		$loggedinUser = $this->mUser;

		$crud = $this->generate_crud('master_routes');

		$crud->columns('operator_id', 'route_code', 'route_start_stage', 'route_end_stage', 'route_stop_count', 'route_type', 'route_status');

		//Show for add

		$crud->add_fields('operator_id', 'route_code', 'route_start_stage', 'route_end_stage', 'route_type', 'route_status');

		
		//Show only for Update

		$crud->edit_fields('operator_id', 'route_code', 'route_start_stage', 'route_end_stage', 'route_type', 'route_status');

			

		$crud->field_type('route_type','dropdown',array('TWOWAY'=>'TWOWAY','CIRCULAR'=>'CIRCULAR'));

		$crud->field_type('route_status','dropdown',array('ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE','DELETED'=>'DELETED'));	
	

		///Assocoation with operator_id
		$crud->set_relation('operator_id','vehicle_operators','{row_id} [{operator_name}-{operator_city}]',array('operator_status' => 'ACTIVE'), 'operator_name ASC');

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

		

		$state = $crud->getState();

		$state_info = $crud->getStateInfo();

		// add edit logic

		if ($state == 'add' || $state == 'insert_validation' || $state == 'insert')

		{

			//Mandatory Feilds

			$crud->required_fields('operator_id', 'route_code', 'route_start_stage', 'route_end_stage', 'route_type', 'route_status');

					

			$crud->field_type('created_by', 'hidden', $loggedinUser->username);

		}

		elseif ($state == 'edit' || $state == 'update_validation' || $state == 'update')

		{

			//Mandatory Feilds

			$crud->required_fields('route_start_stage', 'route_end_stage', 'route_type', 'route_status');

			

				$crud->field_type('operator_id', 'readonly');

				$crud->field_type('route_code', 'readonly');

				

			$crud->field_type('modified_by', 'hidden', $loggedinUser->username);

		}

		
		// disable direct create / delete Frontend User

		//$crud->unset_add();

		$crud->unset_delete();



		$this->mPageTitle = 'Operator Routes';

		$this->render_crud();
	}
	
	// VehicleRouteStages CRUD
	public function VehicleRouteStages()
	{

		$loggedinUser = $this->mUser;

		$crud = $this->generate_crud('route_stages');

		$crud->columns('operator_id', 'route_id', 'stage_code', 'stage_name', 'stage_km', 'stage_no', 'stage_status');


		//Show for add

		$crud->add_fields('operator_id', 'route_id', 'stage_code', 'stage_name', 'stage_km', 'stage_no', 'stage_status');

		//Show only for Update

		$crud->edit_fields('operator_id', 'route_id', 'stage_code', 'stage_name', 'stage_km', 'stage_no', 'stage_status');


		$crud->field_type('stage_status','dropdown',array('ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE','DELETED'=>'DELETED'));	



	
		//Assocoation with operator_id
		$crud->set_relation('operator_id','vehicle_operators','{row_id} [{operator_name}-{operator_city}]',array('operator_status' => 'ACTIVE'), 'operator_name ASC');

		//Assocoation with route_id
		$crud->set_relation('route_id','master_routes','{route_code} [{route_start_stage}-{route_end_stage}]',array('route_status' => 'ACTIVE'), 'route_code, route_start_stage ASC');
		

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

		

		$state = $crud->getState();

		$state_info = $crud->getStateInfo();

		// add edit logic

		if ($state == 'add' || $state == 'insert_validation' || $state == 'insert')

		{

			//Mandatory Feilds

			$crud->required_fields('operator_id', 'route_id', 'stage_code', 'stage_name', 'stage_km', 'stage_no', 'stage_status');

			

			$crud->field_type('created_by', 'hidden', $loggedinUser->username);

		}

		elseif ($state == 'edit' || $state == 'update_validation' || $state == 'update')

		{

			//Mandatory Feilds

			$crud->required_fields('stage_code', 'stage_name', 'stage_km', 'stage_no', 'stage_status');

			

				$crud->field_type('operator_id', 'readonly');

				$crud->field_type('route_id', 'readonly');

				

			$crud->field_type('modified_by', 'hidden', $loggedinUser->username);

		}

		

		// disable direct create / delete Frontend User

		//$crud->unset_add();

		$crud->unset_delete();



		$this->mPageTitle = 'Operator Route Stages';

		$this->render_crud();

	}
	
	
	
	
	// VehicleOperatorFareDiscounts CRUD
	public function VehicleOperatorFareDiscounts()
	{

		$loggedinUser = $this->mUser;

		$crud = $this->generate_crud('vehicle_operator_fare_discounts');

		$crud->columns('operator_id', 'discount_type', 'discount_percentage', 'vehicle_type', 'discount_status');


		//Show for add

		$crud->add_fields('operator_id', 'discount_type', 'discount_percentage', 'vehicle_type', 'discount_status');

		//Show only for Update

		$crud->edit_fields('operator_id', 'discount_type', 'discount_percentage', 'vehicle_type', 'discount_status');

		$crud->field_type('discount_status','dropdown',array('ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE','DELETED'=>'DELETED'));	



		

		//Assocoation with operator_id
		$crud->set_relation('operator_id','vehicle_operators','{row_id} [{operator_name}-{operator_city}]',array('operator_status' => 'ACTIVE'), 'operator_name ASC');

		//Assocoation with vehicle_type
		$crud->set_relation('vehicle_type','vehicle_types','{vehicle_type}  [{vehicle_basefare}]',array('vehicle_type_status' => 'ACTIVE'), 'vehicle_type ASC');

		

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

		

		$state = $crud->getState();

		$state_info = $crud->getStateInfo();

		// add edit logic

		if ($state == 'add' || $state == 'insert_validation' || $state == 'insert')

		{

			//Mandatory Feilds

			$crud->required_fields('operator_id', 'discount_type', 'discount_percentage', 'vehicle_type', 'discount_status');

			

			$crud->field_type('created_by', 'hidden', $loggedinUser->username);

		}

		elseif ($state == 'edit' || $state == 'update_validation' || $state == 'update')

		{

			//Mandatory Feilds

			$crud->required_fields('discount_type', 'discount_percentage', 'discount_status');

			

				$crud->field_type('operator_id', 'readonly');

				$crud->field_type('vehicle_type', 'readonly');

				

			$crud->field_type('modified_by', 'hidden', $loggedinUser->username);

		}

		

		// disable direct create / delete Frontend User

		//$crud->unset_add();

		$crud->unset_delete();



		$this->mPageTitle = 'Operator Discounts';

		$this->render_crud();

	}

}

?>