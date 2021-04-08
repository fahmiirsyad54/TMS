<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses extends CI_Controller {

	function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        
			$this->load->model('AppModel');
			$this->load->model('AksesModel');
			$this->model    = $this->AksesModel;
			$this->modelapp = $this->AppModel;
    }

    function login($message=false){
			$errorLogin           = ($message) ? true : false ;
			$data['errorLogin']   = $errorLogin;
			$data['errorMessage'] = 'The combination of username and password is not appropriate !';
    	$this->load->view('akses_view/login',$data);
    }

    function auth(){
    	$vcusername  = $this->input->post('vcusername');
    	$vcpassword  = ($this->input->post('vcpassword'));
    	$SU          = 'Super User';
    	$production  = 'Production';
    	$development = 'Development';
    	$data        = $this->model->auth($vcusername,$vcpassword);
    	$hakakses    = $data[0]->vchakakses;
    	$jmldata     = count($data);
		if ($jmldata > 0 && $jmldata == 1 && $hakakses == $SU) {
			$sessiondata = array(
								'intid'       => $data[0]->intid,
								'vcnama'      => $data[0]->vcnama,
								'vckode'      => $data[0]->vckode,
								'inthakakses' => $data[0]->inthakakses,
								'appname'     => 'pms'
							);

			$this->session->set_userdata($sessiondata);
			
			redirect(base_url('dashboard/view'));
		} else if ($jmldata > 0 && $jmldata == 1 && $hakakses == $development) {
			$sessiondata = array(
								'intid'       => $data[0]->intid,
								'vcnama'      => $data[0]->vcnama,
								'vckode'      => $data[0]->vckode,
								'inthakakses' => $data[0]->inthakakses,
								'appname'     => 'pms_dev'
							);

			$this->session->set_userdata($sessiondata);
			
			redirect(base_url('development/dashboard/view'));
		} else if ($jmldata > 0 && $jmldata == 1 && $hakakses == $production) {
			$sessiondata = array(
								'intid'       => $data[0]->intid,
								'vcnama'      => $data[0]->vcnama,
								'vckode'      => $data[0]->vckode,
								'inthakakses' => $data[0]->inthakakses,
								'intgedung'   => $data[0]->intgedung,
								'appname'     => 'pms_prod'
							);

			$this->session->set_userdata($sessiondata);
			
			redirect(base_url('production/dashboard/view'));
		} else {
			redirect(base_url('akses/login/error'));
		}
    }

    function logout(){
    	$this->session->unset_userdata('intid','vcnama','vckode','inthakakses');
    	// $this->session->sess_destroy();
    	redirect(base_url());
    }

    function error(){
    	$data['title']      = 'error';
    	$this->template->set_layout('default')->build('akses_view/error',$data);
    }
	
}
