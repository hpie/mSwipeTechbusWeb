<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class MasterData extends Admin_Controller {



	public function __construct()

	{

		parent::__construct();

		$this->load->library('form_builder');

	}



	// Frontend MASTER CRUD

	public function index()
	{

		$this->load->model('user_model', 'users');

		$this->mViewData['count'] = array(

			'users' => $this->users->count_all(),

		);

		$this->render('home');

	}
	
	
	// VehicleType CRUD
	public function VehicleOperators()
	{
		$loggedinUser = $this->mUser;

		$crud = $this->generate_crud('vehicle_operators');
		$crud->where('row_id', $loggedinUser->operator_id);

		$crud->columns('operator_name', 'operator_address1', 'operator_address2', 'operator_city', 'operator_state', 'operator_zipcode', 'operator_phone', 'operator_email', 'operator_helpline', 'operator_status');

		//Show for add

		$crud->add_fields('operator_name', 'operator_address1', 'operator_address2', 'operator_city', 'operator_state', 'operator_zipcode', 'operator_phone', 'operator_email', 'operator_helpline', 'operator_status');

		//Show only for Update

		$crud->edit_fields('operator_name', 'operator_address1', 'operator_address2', 'operator_city', 'operator_state', 'operator_zipcode', 'operator_phone', 'operator_email', 'operator_helpline', 'operator_status');

			
		$crud->field_type('operator_status','dropdown',array('ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE','DELETED'=>'DELETED'));	


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

			$crud->required_fields('operator_name', 'operator_address1', 'operator_city', 'operator_state', 'operator_zipcode', 'operator_phone', 'operator_email', 'operator_helpline', 'operator_status');

			

			$crud->field_type('created_by', 'hidden', $loggedinUser->username);

		}

		elseif ($state == 'edit' || $state == 'update_validation' || $state == 'update')

		{

			//Mandatory Feilds

			$crud->required_fields('operator_address1', 'operator_city', 'operator_state', 'operator_zipcode', 'operator_phone', 'operator_email', 'operator_helpline', 'operator_status');

			

				$crud->field_type('operator_name', 'readonly');

				//$crud->field_type('vehicle_type', 'readonly');

				

			$crud->field_type('modified_by', 'hidden', $loggedinUser->username);

		}

		

		// disable direct create / delete Frontend User

		$crud->unset_add();

		$crud->unset_delete();

		
		$this->mPageTitle = 'Vehicle Operators';

		$this->render_crud();
	}
	
	
	public function VehicleFareMaster()

	{

		$loggedinUser = $this->mUser;

		$crud = $this->generate_crud('vehicle_fare_master');
		$crud->where('`vehicle_fare_master`.operator_id', $loggedinUser->operator_id);

		$crud->columns('operator_id', 'vehicle_type', 'fare_full', 'fare_half', 'fare_luggage');

		//Show for add

		$crud->add_fields('operator_id', 'vehicle_type', 'fare_full', 'fare_half', 'fare_luggage');

		

		//Show only for Update

		$crud->edit_fields('operator_id', 'vehicle_type', 'fare_full', 'fare_half', 'fare_luggage');

			
		//$crud->field_type('operator_status','dropdown',array('ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE','DELETED'=>'DELETED'));	


				
		//Show Operator name on List
		if ($crud->getState()=='list')
		{
			$crud->set_relation('operator_id','vehicle_operators','{row_id} [{operator_name}-{operator_city}]',array('operator_status' => 'ACTIVE'), 'operator_name ASC');
		}
		
		//Assocoation with operator_id
		//$crud->set_relation('operator_id','vehicle_operators','{row_id} [{operator_name}-{operator_city}]',array('operator_status' => 'ACTIVE'), 'operator_name ASC');

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
			$crud->required_fields('operator_id', 'vehicle_type', 'fare_full', 'fare_half', 'fare_luggage');
			
			$crud->field_type('operator_id', 'hidden', $loggedinUser->operator_id);
			$crud->field_type('created_by', 'hidden', $loggedinUser->username);
		
		
		}
		elseif ($state == 'edit' || $state == 'update_validation' || $state == 'update')
		{
			//Mandatory Feilds
			$crud->required_fields('fare_full', 'fare_half', 'fare_luggage');
			
				$crud->field_type('operator_id', 'hidden', $loggedinUser->operator_id);
				$crud->field_type('vehicle_type', 'readonly');
				
			$crud->field_type('modified_by', 'hidden', $loggedinUser->username);
		}
		
		// disable direct create / delete Frontend User
		//$crud->unset_add();
		$crud->unset_delete();

		$this->mPageTitle = 'Vehicle Operator Fare ';
		$this->render_crud();

	}

}

?>
