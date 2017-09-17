<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Cardsort extends CI_Controller {

	public function index() {
		if ( $this->input->server( 'REQUEST_METHOD' ) == "POST" ) {
			// Proccess the form data
			$ip_address   = $_SERVER['REMOTE_ADDR'];
			$data         = [
				'name'              => $this->input->post( 'username' ),
				'age_group'         => $this->input->post( 'age' ),
				'ip_address'        => $ip_address,
				'gender'            => $this->input->post( 'gender' ),
				'started_timestamp' => time()
			];
			$applicant_id = $this->db->insert_id();
			$this->db->insert( 'applicants', $data );
			// Create session.
			$this->session->set_userdata( 'name', $this->input->post( 'username' ) );
			$this->session->set_userdata( 'applicant_id', $applicant_id );
			// Redirect, to prevent form ressubmition.
			redirect( base_url() . 'cardsort' );
		}

		$this->load->view( 'cardsort', [
			'applicant_id' => $this->session->applicant_id
		] );
	}
}
