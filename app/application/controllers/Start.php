<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Start extends CI_Controller {

	public function index($lang = 'en') {
		/*$this->session->sess_destroy();*/
		if ($lang === 'en' || $lang === 'dk') {
			$this->lang->load('translation', $lang);
		} else {
			die('Lang is not supported');
		}

		// User already
		if ($this->session->applicant_id) {
			redirect( base_url() . 'cardsort' );
		}
		$this->load->view( 'start', [
			'lang' => $lang
		] );
	}
}
