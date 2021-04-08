<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('DashboardModel');
        $this->model = $this->DashboardModel;

        $this->load->model('AppModel');
        $this->modelapp = $this->AppModel;

        if (!$this->session->intid && $this->session->intid != 'pms') {
            redirect(base_url('akses/login'));
        }
    }

    function index(){
        redirect(base_url('dashboard/view'));
    }

    function view(){
        $intgedung = $this->session->intgedung;
        $month              = date('m');
        $year               = date('Y');
        $day                = date('d');
        $datagedung         = $this->modelapp->getdatalistall('m_gedung');
        $datakaryawan       = $this->modelapp->getdatalistall('m_karyawan');
        $datapallet         = $this->modelapp->getdatalistall('m_pallet');
        $datapinjam         = $this->model->getdatapinjam();
        $datapinjamtoday    = $this->model->getdatapinjamtoday($day, $month, $year);
        $datakembali        = $this->model->getdatakembali();
        $datakembalitoday   = $this->model->getdatakembalitoday($day, $month, $year);
        $dataperbaikan      = $this->model->getdataperbaikan();
        $dataperbaikantoday = $this->model->getdataperbaikantoday($day, $month, $year);
        $datarusak          = $this->model->getdatarusak();
        $datarusaktoday     = $this->model->getdatarusaktoday($day, $month, $year);

        $data['title']             = 'Dashboard';
        $data['controller']        = 'dashboard';
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

        $this->template->set_layout('default')->build('/dashboard_view/index',$data);
    }
}
