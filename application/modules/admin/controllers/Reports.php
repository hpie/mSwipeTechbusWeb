<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	}

	public function index()
	{
		$this->load->model('user_model', 'users');

		$this->mViewData['count'] = array(

			'users' => $this->users->count_all(),

		);

		$this->render('home');
		
	}
	
	
	public function Collection()
	{
		$loggedinUser = $this->mUser;
		
		//$this->mViewData['all_routes'] = $this->db->get_where('master_routes', array('operator_id' => $loggedinUser->operator_id))->result();

		$this->mViewData['all_operators'] = $this->db->get('vehicle_operators')->result();

		$this->mViewData['all_routes'] = $this->db->get('master_routes')->result();

		$this->mViewData['all_vehicles'] = $this->db->get_where('vehicle_master')->result();

		$crud = $this->generate_crud('ticket_bookings');
		$this->db ->select('*, sum(total_fare) as grand_total, sum(fare_full_passengers) as full_passengers, sum(fare_half_passengers) as half_passengers, sum(fare_luggage) as luggage');
		//$this->db->from('ticket_bookings');
		//$this->db->where('operator_id', $loggedinUser->operator_id);
		
		if($this->input->post('filter_operator') != '') {
			$this->db->where('operator_id', $this->input->post('filter_operator'));
		}

		if($this->input->post('filter_route') != '') {
			$this->db->where('route_code', $this->input->post('filter_route'));
		}

		if($this->input->post('filter_vehicle') != '') {
			$this->db->where('vehicle_number', $this->input->post('filter_vehicle'));
		}

		if($this->input->post('filter_start_date') != '') {			
			//$this->db->where('booking_time >= ' , $this->input->post('filter_start_date'));

			$this->db->where('cast(booking_time as date) >= ' , $this->input->post('filter_start_date'));
		}

		if($this->input->post('filter_end_date') != '') {
			//$this->db->where('booking_time <= ' , $this->input->post('filter_end_date'));

			$this->db->where('cast(booking_time as date) <= ' , $this->input->post('filter_end_date'));

			
		}

		$this->db->group_by('route_code');
		//->order_by('our_sum', 'desc');
		$crud->columns('operator_id', 'route_code', 'vehicle_number', 'full_passengers', 'half_passengers', 'luggage', 'grand_total');

		//how to add others? and create a new record if others
		$crud->set_relation('operator_id','vehicle_operators','{operator_name}-{operator_city}',array('operator_status' => 'ACTIVE'), 'operator_name ASC');
		
		$this->mViewData['filter_operator'] = $this->input->post('filter_operator');

		$this->mViewData['filter_route'] = $this->input->post('filter_route');

		$this->mViewData['filter_vehicle'] = $this->input->post('filter_vehicle');

		$this->mViewData['filter_start_date'] = $this->input->post('filter_start_date');

		$this->mViewData['filter_end_date'] = $this->input->post('filter_end_date');

		
		//$this->mViewData['all_routes'] = $this->db->get_where('master_routes', array('operator_id' => $loggedinUser->operator_id))->result();
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();

		$this->mPageTitle = 'Collections';
		$this->render_crud();
	}

	public function CollectionToday()
	{
		$loggedinUser = $this->mUser;
		
		//$this->mViewData['all_routes'] = $this->db->get_where('master_routes', array('operator_id' => $loggedinUser->operator_id))->result();

		$this->mViewData['all_operators'] = $this->db->get('vehicle_operators')->result();

		$this->mViewData['all_routes'] = $this->db->get('master_routes')->result();

		$this->mViewData['all_vehicles'] = $this->db->get_where('vehicle_master')->result();

		$crud = $this->generate_crud('ticket_bookings');
		$this->db ->select('*, sum(total_fare) as grand_total, sum(fare_full_passengers) as full_passengers, sum(fare_half_passengers) as half_passengers, sum(fare_luggage) as luggage');
		//$this->db->from('ticket_bookings');
		//$this->db->where('operator_id', $loggedinUser->operator_id);
		
		if($this->input->post('filter_operator') != '') {
			$this->db->where('operator_id', $this->input->post('filter_operator'));
		}

		if($this->input->post('filter_route') != '') {
			$this->db->where('route_code', $this->input->post('filter_route'));
		}

		if($this->input->post('filter_vehicle') != '') {
			$this->db->where('vehicle_number', $this->input->post('filter_vehicle'));
		}

		$this->db->where('cast(booking_time as date) >= ' , date('Y-m-d', strtotime('now')));

		$this->db->where('cast(booking_time as date) <= ' , date('Y-m-d', strtotime('now')));

		$this->db->group_by('route_code');
		//->order_by('our_sum', 'desc');
		$crud->columns('operator_id', 'route_code', 'vehicle_number', 'full_passengers', 'half_passengers', 'luggage', 'grand_total');

		//how to add others? and create a new record if others
		$crud->set_relation('operator_id','vehicle_operators','{operator_name}-{operator_city}',array('operator_status' => 'ACTIVE'), 'operator_name ASC');
		
		$this->mViewData['filter_operator'] = $this->input->post('filter_operator');

		$this->mViewData['filter_route'] = $this->input->post('filter_route');

		$this->mViewData['filter_vehicle'] = $this->input->post('filter_vehicle');

		$this->mViewData['filter_start_date'] = $this->input->post('filter_start_date');

		$this->mViewData['filter_end_date'] = $this->input->post('filter_end_date');

		
		//$this->mViewData['all_routes'] = $this->db->get_where('master_routes', array('operator_id' => $loggedinUser->operator_id))->result();
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();

		$this->mPageTitle = 'Todays Collection';
		$this->render_crud();
	}
}

?>