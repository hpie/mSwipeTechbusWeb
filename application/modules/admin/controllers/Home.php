<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Home extends Admin_Controller {



	public function index()

	{

		//$this->load->model('user_model', 'users');

		//$this->mViewData['count'] = array(

			//'users' => $this->users->count_all(),

		//);
		
		// Users
		$this->load->model('user_model', 'users');
		//$users_count = $this->db->get_where('users', array('operator_id' => $loggedinUser->operator_id))->num_rows();
		$users_count = $this->db->get_where('users', array('active' => '1'))->num_rows();
		
		// output last query
        //exit($this->db->last_query());
		
		
		// Operators 
		$this->load->model('Admin_model', 'vehicle_operators');
		$operator_count = $this->db->get_where('vehicle_operators', array('operator_status' => 'ACTIVE'))->num_rows();
		
		// Vehicles 
		$this->load->model('Admin_model', 'vehicle_master');
		$vehicle_count = $this->db->get_where('vehicle_master', array('vehicle_status' => 'ACTIVE'))->num_rows();
		
		
		// Routes 
		$this->load->model('Admin_model', 'master_routes');
		$routes_count = $this->db->get_where('master_routes', array('route_status' => 'ACTIVE'))->num_rows();
		
		// Devices 
		$this->load->model('Admin_model', 'vehicle_operator_devices');
		$device_count = $this->db->get_where('vehicle_operator_devices', array('device_status' => 'ACTIVE'))->num_rows();
		
		
		// Tickets 
		$this->load->model('Admin_model', 'ticket_bookings');
		$tickets_count = $this->db->get_where('ticket_bookings', array('operator_id != ' => '1'))->num_rows();
		
		
		// Tickets Amount 
		//$this->load->model('Admin_model', 'ticket_bookings');
		$this->db->select_sum('total_fare');
		$this->db->from('ticket_bookings');
		$this->db->where('operator_id != ',  '1');
		//$this->db->get_where('ticket_bookings', array('operator_id' => $loggedinUser->operator_id));
		$query = $this->db->get();
		$result = $query->result();
		$tickets_amount = $result[0]->total_fare;
		


		// Tickets Today
		$this->load->model('Admin_model', 'ticket_bookings');
		$tickets_count_today = $this->db->get_where('ticket_bookings', array('operator_id != ' => '1', 'DATE(booking_time)' => date("Y-m-d") ))->num_rows();
		

		// Tickets Amount Today
		//$this->load->model('Admin_model', 'ticket_bookings');
		$this->db->select_sum('total_fare');
		$this->db->from('ticket_bookings');
		$this->db->where('operator_id != ',  '1');
		$this->db->where('DATE(booking_time)',  date("Y-m-d") );
		//$this->db->get_where('ticket_bookings', array('operator_id' => $loggedinUser->operator_id));
		$query = $this->db->get();
		$result = $query->result();
		$tickets_amount_today = $result[0]->total_fare;
		
		
		
		//  --------------------- Data For TEST USER ----------------------------
		// Tickets 
		$this->load->model('Admin_model', 'ticket_bookings');
		$tickets_count_test = $this->db->get_where('ticket_bookings', array('operator_id' => '1'))->num_rows();
		
		
		// Tickets Amount 
		//$this->load->model('Admin_model', 'ticket_bookings');
		$this->db->select_sum('total_fare');
		$this->db->from('ticket_bookings');
		$this->db->where('operator_id',  '1');
		//$this->db->get_where('ticket_bookings', array('operator_id' => $loggedinUser->operator_id));
		$query = $this->db->get();
		$result = $query->result();
		$tickets_amount_test = $result[0]->total_fare;
		


		// Tickets Today
		$this->load->model('Admin_model', 'ticket_bookings');
		$tickets_count_today_test = $this->db->get_where('ticket_bookings', array('operator_id' => '1', 'DATE(booking_time)' => date("Y-m-d") ))->num_rows();
		

		// Tickets Amount Today
		//$this->load->model('Admin_model', 'ticket_bookings');
		$this->db->select_sum('total_fare');
		$this->db->from('ticket_bookings');
		$this->db->where('operator_id',  '1');
		$this->db->where('DATE(booking_time)',  date("Y-m-d") );
		//$this->db->get_where('ticket_bookings', array('operator_id' => $loggedinUser->operator_id));
		$query = $this->db->get();
		$result = $query->result();
		$tickets_amount_today_test = $result[0]->total_fare;
		
		
		
		
		$this->mViewData['count'] = array(
			//'users' => $this->users->count_all(),
			'users' => $users_count,
			'operators' => $operator_count,
			'vehicles' => $vehicle_count,
			'routes' => $routes_count,
			'devices' => $routes_count,
			'tickets' => $tickets_count,
			'ticketsamount' => $tickets_amount,
			'ticketstoday' => $tickets_count_today,
			'ticketsamounttoday' => $tickets_amount_today,
			
			'tickets_test' => $tickets_count_test,
			'ticketsamount_test' => $tickets_amount_test,
			'ticketstoday_test' => $tickets_count_today_test,
			'ticketsamounttoday_test' => $tickets_amount_today_test,
		);

		$this->render('home');

	}

}

?>