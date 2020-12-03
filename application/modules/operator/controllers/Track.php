<?php

defined('BASEPATH') OR exit('No direct script access allowed');



/**

 * Home page

 */

class Track extends Admin_Controller {

	

	public function __construct()

	{

		parent::__construct();



		$this->load->helper('url');

		$this->load->library('form_builder');

	}



	public function index()

	{

		$form = $this->form_builder->create_form();



		// get list of Frontend user groups

		//$this->load->model('group_model', 'groups');

		//$this->mViewData['groups'] = $this->groups->get_all();

		$this->mPageTitle = 'Track Bus';



		//$this->mViewData['form'] = $form;

		$this->render('track');

	}



	public function Bus()

	{

		$form = $this->form_builder->create_form();



		$loggedinUser = $this->mUser;



		$all_vehicles = $this->db->get_where('vehicle_master', array('operator_id' => $loggedinUser->operator_id))->result();

		$this->mPageTitle = 'Track Bus';



		$this->mViewData['all_vehicles'] = $all_vehicles;

		$this->render('track');

	}



	public function get_location() {



		if($this->input->post('route') != '' || $this->input->post('bus') != '') {

			$this->db->select('*');

			$this->db->from('location_log');



			if($this->input->post('route') != '') {

				$this->db->where('route_code', $this->input->post('route'));

			} else if($this->input->post('bus') != '') {

				$this->db->where('vehical_code', $this->input->post('bus'));

			}		

			

			$this->db->limit(1);

			$this->db->order_by('row_id','DESC');



			$location_data = $this->db->get()->row();



			if(count($location_data) > 0) {

				$response = array(

					'status'	=> 1,

					'message'	=> 'Record found.',

					'location_time' => $location_data->timestamp,

					'latitude'		=> $location_data->latitude,

					'longitude'		=> $location_data->longitude,

					'query'		=> $this->db->last_query()

				);

			} else {

				$response = array(

					'status'	=> 0,

					'message'	=> 'No record found.',

					'query'		=> $this->db->last_query()

				);

			}

		} else {

			$response = array(

				'status'	=> 0,

				'message'	=> 'Please enter bus code or route code.'

			);

		}



		echo json_encode($response);

	}
	
	public function VehicleTicketBookings()
	{
		$loggedinUser = $this->mUser;
		$crud = $this->generate_crud('ticket_bookings');
		$crud->where('operator_id', $loggedinUser->operator_id);

		$crud->order_by('booking_time', 'DESC');
		
		$crud->columns('route_code', 'booking_reference', 'ticket_number', 'start_stage', 'end_stage', 'fare_full_passengers', 'fare_full_cost', 'fare_half_passengers', 'fare_half_cost', 'fare_luggage', 'fare_luggage_cost', 'total_fare', 'mobile', 'booking_time');
		

		// disable direct create / delete Frontend User
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();

		$this->mPageTitle = 'Tickets';
		$this->render_crud();

	}

}

?>