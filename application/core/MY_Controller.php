<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

	public $data = array();

	public function __construct() {

		parent::__construct();

        $this->load->model('AppModel');
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->intid && $this->session->appname != 'pms') {
            redirect(base_url('akses/login'));
        } else {
        	if (empty($this->uri->segment(1))) {
        		redirect(base_url('dashboard'));
        	} else {
				$parameterkode = $this->uri->segment(1);
				$datamenu      = $this->AppModel->getdatamenu($parameterkode);
				$inthakakses   = $this->session->inthakakses;
				$hakaksesmenu  = $this->AppModel->hakaksesmenu($inthakakses,$parameterkode);

				// $this->datanotes     = $this->AppModel->getdatanotes();
				// $this->jmlnotes      = $this->AppModel->getjmlnotes()[0]->jmldata;
				// $this->notesin       = $this->AppModel->getnotesin()[0]->notesin;
				

		        $this->modelapp     = $this->AppModel;
		        $this->controller   = $datamenu[0]->vccontroller;
		        $this->view         = $datamenu[0]->vccontroller . '_view';
		        $this->title        = $datamenu[0]->vcnama;
		        $this->table        = $datamenu[0]->vctabel;
		        $this->tablehistory = $datamenu[0]->vctabel . '_history';
		        $this->limit        = 10;

		        $this->hideaction = ($this->session->userdata('inthakakses') == 9) ? 'hidden' : '';

		        if (count($hakaksesmenu) == 0) {
		            redirect(base_url('akses/error'));
		        }
        	}
        }

	}
}