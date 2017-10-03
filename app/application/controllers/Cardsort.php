<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Cardsort extends CI_Controller {

	public function index( $lang = 'en' ) {
		if ( $this->input->server( 'REQUEST_METHOD' ) == "POST" ) {
			// Proccess the form data
			$ip_address = $_SERVER['REMOTE_ADDR'];
			$data       = [
				'name'              => $this->input->post( 'username' ),
				'age_group'         => $this->input->post( 'age' ),
				'ip_address'        => $ip_address,
				'gender'            => $this->input->post( 'gender' ),
				'started_timestamp' => time()
			];
			$this->db->insert( 'applicants', $data );
			$applicant_id = $this->db->insert_id();
			// Create session.
			$this->session->set_userdata( 'username', $this->input->post( 'username' ) );
			$this->session->set_userdata( 'applicant_id', $applicant_id );
			// Redirect, to prevent form ressubmition.
			redirect( base_url() . 'cardsort' );
		}

		if ( ! $this->session->applicant_id ) {
			redirect( base_url() );
		}

		$this->load->view( 'cardsort', [
			'applicant_id' => $this->session->applicant_id
		] );
	}

	public function process_results() {
		// TODO
		// Save results to database
		/*var_dump($this->input->post());
		echo '<pre>';
		print_r( $this->session->application_id );
		echo '</pre>';
		exit;*/
		$this->db->set('data', $this->input->post('result'));
		$this->db->set('end_timestamp', time());
		$this->db->where('id', $this->session->application_id);
		$this->db->insert('applicants');

		$this->session->unset_userdata( 'username' );
		$this->session->unset_userdata( 'applicant_id' );
		$this->session->set_flashdata( 'finish', TRUE );

		redirect( base_url() );
	}
}
