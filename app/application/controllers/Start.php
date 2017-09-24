<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Start extends CI_Controller {

	public function index() {
		// User already
		if ($this->session->applicant_id) {
			redirect( base_url() . 'cardsort' );
		}
		$this->load->view( 'start' );
	}
}
