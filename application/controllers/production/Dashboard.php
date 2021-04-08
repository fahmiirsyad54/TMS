<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('Production/DashboardModel');
        $this->model = $this->DashboardModel;

        $this->load->model('AppModel');
        $this->modelapp = $this->AppModel;

        if (!$this->session->intid && $this->session->intid != 'pms_prod') {
            redirect(base_url('akses/login'));
        }
    }

    function index(){
        redirect(base_url('production/dashboard/view'));
    }

    function view(){
        $intgedung = $this->session->intgedung;
        $month              = date('m');
        $year               = date('Y');
        $day                = date('d');
        $datagedung         = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $datakaryawan       = $this->modelapp->getdatadetailcustom('m_karyawan',$intgedung,'intgedung');
        $datapallet         = $this->modelapp->getdatadetailcustom('m_pallet',$intgedung,'intgedung');
        $datapinjam         = $this->model->getdatapinjam($intgedung);
        $datapinjamtoday    = $this->model->getdatapinjamtoday($intgedung, $day, $month, $year);
        $datakembali        = $this->model->getdatakembali($intgedung);
        $datakembalitoday   = $this->model->getdatakembalitoday($intgedung, $day, $month, $year);
        $dataperbaikan      = $this->model->getdataperbaikan($intgedung);
        $dataperbaikantoday = $this->model->getdataperbaikantoday($intgedung, $day, $month, $year);
        $datarusak          = $this->model->getdatarusak($intgedung);
        $datarusaktoday     = $this->model->getdatarusaktoday($intgedung, $day, $month, $year);

        $data['title']             = $datagedung[0]->vcnama;
        $data['controller']        = 'production';
        $data['jumkaryawan']       = count($datakaryawan);
        $data['jumpallet']         = count($datapallet);
        $data['jumpinjam']         = $datapinjam[0]->inttotal;
        $data['jumpinjamtoday']    = $datapinjamtoday[0]->inttotal ? $datapinjamtoday[0]->inttotal : 0 ;
        $data['jumkembali']        = $datakembali[0]->inttotal;
        $data['jumkembalitoday']   = $datakembalitoday[0]->inttotal ? $datakembalitoday[0]->inttotal : 0 ;
        $data['jumperbaikan']      = $dataperbaikan[0]->inttotal;
        $data['jumperbaikantoday'] = $dataperbaikantoday[0]->inttotal ? $dataperbaikantoday[0]->inttotal : 0 ;
        $data['jumrusak']          = $datarusak[0]->inttotal;
        $data['jumrusaktoday']     = $datarusaktoday[0]->inttotal ? $datarusaktoday[0]->inttotal : 0 ;

        $this->template->set_layout('default_prod')->build('production/dashboard_view/index',$data);
    }
}
