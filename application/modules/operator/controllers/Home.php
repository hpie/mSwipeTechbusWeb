<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Home extends Admin_Controller {



	public function index()

	{

		$loggedinUser = $this->mUser;

		// Users
		$this->load->model('user_model', 'users');
		$users_count = $this->db->get_where('users', array('operator_id' => $loggedinUser->operator_id))->num_rows();
		
		// output last query
        //exit($this->db->last_query());
		
		// Vehicles 
		$this->load->model('Operator_model', 'vehicle_master');
		$vehicle_count = $this->db->get_where('vehicle_master', array('operator_id' => $loggedinUser->operator_id))->num_rows();
		
		
		// Routes 
		$this->load->model('Operator_model', 'master_routes');
		$routes_count = $this->db->get_where('master_routes', array('operator_id' => $loggedinUser->operator_id))->num_rows();
		
		// Devices 
		$this->load->model('Operator_model', 'vehicle_operator_devices');
		$device_count = $this->db->get_where('vehicle_operator_devices', array('operator_id' => $loggedinUser->operator_id))->num_rows();
		
		
		// Fares 
		$this->load->model('Operator_model', 'vehicle_fare_master');
		$fares_count = $this->db->get_where('vehicle_fare_master', array('operator_id' => $loggedinUser->operator_id))->num_rows();
		
		// Tickets 
		$this->load->model('Operator_model', 'ticket_bookings');
		$tickets_count = $this->db->get_where('ticket_bookings', array('operator_id' => $loggedinUser->operator_id))->num_rows();
		
		
		// Tickets Amount 
		//$this->load->model('Operator_model', 'ticket_bookings');
		$this->db->select_sum('total_fare');
		$this->db->from('ticket_bookings');
		$this->db->where('operator_id',  $loggedinUser->operator_id);
		//$this->db->get_where('ticket_bookings', array('operator_id' => $loggedinUser->operator_id));
		$query = $this->db->get();
		$result = $query->result();
		$tickets_amount = $result[0]->total_fare;
		


		// Tickets Today
		$this->load->model('Operator_model', 'ticket_bookings');
		$tickets_count_today = $this->db->get_where('ticket_bookings', array('operator_id' => $loggedinUser->operator_id, 'DATE(booking_time)' => date("Y-m-d") ))->num_rows();
		

		// Tickets Amount Today
		//$this->load->model('Operator_model', 'ticket_bookings');
		$this->db->select_sum('total_fare');
		$this->db->from('ticket_bookings');
		$this->db->where('operator_id',  $loggedinUser->operator_id);
		$this->db->where('DATE(booking_time)',  date("Y-m-d") );
		//$this->db->get_where('ticket_bookings', array('operator_id' => $loggedinUser->operator_id));
		$query = $this->db->get();
		$result = $query->result();
		$tickets_amount_today = $result[0]->total_fare;
		
		
		
		$this->mViewData['count'] = array(
			//'users' => $this->users->count_all(),
			'users' => $users_count,
			'vehicles' => $vehicle_count,
			'routes' => $routes_count,
			'devices' => $routes_count,
			'fares' => $fares_count,
			'tickets' => $tickets_count,
			'ticketsamount' => $tickets_amount,
			'ticketstoday' => $tickets_count_today,
			'ticketsamounttoday' => $tickets_amount_today,
		);
		
		
		$this->render('home');

	}
	
	public function DownloadApp()

	{
		$this->load->helper('download');
		$loggedinUser = $this->mUser;

		$pth    =   file_get_contents(base_url()."operator/MyTechBus-App.apk");
		$nme    =   "MyTechBus-App.apk";
		force_download($nme, $pth);  

		//$this->render('home');

	}

}

?>
