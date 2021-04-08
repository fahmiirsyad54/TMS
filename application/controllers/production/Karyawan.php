<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends CI_Controller {

	function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        
        $this->load->model('Production/KaryawanModel');
        $this->model = $this->KaryawanModel;

        $this->load->model('AppModel');
        $this->modelapp = $this->AppModel;

        if (!$this->session->intid && $this->session->intid != 'pms_prod') {
            redirect(base_url('akses/login'));
        }
    }

    function index(){
    	redirect(base_url('production/karyawan/view'));
    }

    function view($halaman=1){
        $intgedung  = $this->session->intgedung;
        $datagedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $keyword    = $this->input->get('key');
        $jmldata    = $this->model->getjmldata('m_karyawan',$keyword, $intgedung);
        $offset     = ($halaman - 1) * 10;
        $jmlpage    = ceil($jmldata[0]->jmldata / 10);
        
        $data['title']      = $datagedung[0]->vcnama . ' - Employee';
        $data['controller'] = 'production/karyawan';
        $data['keyword']    = $keyword;
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $this->model->getdatalimit('m_karyawan',$offset,10,$keyword, $intgedung);

        $this->template->set_layout('default_prod')->build('production/karyawan_view/index',$data);
    }

    function detail($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail($this->table,$intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add($intjabatan=0){
        $intgedung  = $this->session->intgedung;
        $datagedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $data = array(
                    'intid'      => 0,
                    'vckode'     => '',
                    'vcnama'     => '',
                    'intjabatan' => $intjabatan,
                    'intgedung'  => $intgedung,
                    'intadd'     => $this->session->intid,
                    'dtadd'      => date('Y-m-d H:i:s'),
                    'intupdate'  => $this->session->intid,
                    'dtupdate'   => date('Y-m-d H:i:s'),
                    'intstatus'  => 0
                );

        $data['title']       = $datagedung[0]->vcnama . ' - Employee';
        $data['action']      = 'Add';
        $data['controller']  = 'production/karyawan';
        $data['listjabatan'] = $this->modelapp->getdatalist('m_jabatan');


        $this->template->set_layout('default_prod')->build('production/karyawan_view/form',$data);
    }

    function edit($intid){
        $intgedung  = $this->session->intgedung;
        $datagedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $resultData = $this->modelapp->getdatadetail('m_karyawan',$intid);
        $data = array(
                    'intid'      => $resultData[0]->intid,
                    'vckode'     => $resultData[0]->vckode,
                    'vcnama'     => $resultData[0]->vcnama,
                    'intjabatan' => $resultData[0]->intjabatan,
                    'intgedung'  => $resultData[0]->intgedung,
                    'intupdate'  => $this->session->intid,
                    'dtupdate'   => date('Y-m-d H:i:s')
                );

        $data['title']       = $datagedung[0]->vcnama . ' - Employee';
        $data['action']      = 'Edit';
        $data['controller']  = 'production/karyawan';
        $data['listjabatan'] = $this->modelapp->getdatalist('m_jabatan');

        $this->template->set_layout('default_prod')->build('production/karyawan_view/form',$data);
    }

    function validasiform($tipe){
        $array = array();
        $data = $this->input->post();
        if ($tipe == 'data') {
            foreach ($data as $key => $value) {
                $result = $this->modelapp->getdatadetailcustom($this->table,$value,$key);
                if (count($result) > 0 && $value != '') {
                    $front = substr($key,0,2);
                    $end   = substr($key,2);
                    $end2  = substr($key,3);
                    $error = ($front == 'vc') ? $end : $end2 ;
                    $array[]['error'] = $error . ' Sudah ada !';
                }
            }
        } elseif ($tipe == 'required') {
            foreach ($data as $key => $value) {
                if ($value == '') {
                    $front = substr($key,0,2);
                    $end   = substr($key,2);
                    $end2  = substr($key,3);
                    $error = ($front == 'vc') ? $end : $end2 ;
                    $array[]['error'] = 'Kolom ' . $error . ' tidak boleh kosong !';
                }
            }
        }
        echo json_encode($array);
    }

    function aksi($tipe,$intid,$status=0){
        if ($tipe == 'Add') {
            $vckode     = $this->input->post('vckode');
            $vcnama     = $this->input->post('vcnama');
            $intjabatan = $this->input->post('intjabatan');
            $intgedung  = $this->input->post('intgedung');
            $data    = array(
                    'vckode'     => $vckode,
                    'vcnama'     => $vcnama,
                    'intjabatan' => $intjabatan,
                    'intgedung'  => $intgedung,
                    'intadd'     => $this->session->intid,
                    'dtadd'      => date('Y-m-d H:i:s'),
                    'intupdate'  => $this->session->intid,
                    'dtupdate'   => date('Y-m-d H:i:s'),
                    'intstatus'  => 1
                );

            $result = $this->modelapp->insertdata('m_karyawan',$data);

            if ($result) {
                redirect(base_url('production/karyawan/view'));
            }
        } elseif ($tipe == 'Edit') {
            $vckode     = $this->input->post('vckode');
            $vcnama     = $this->input->post('vcnama');
            $intjabatan = $this->input->post('intjabatan');
            $intgedung  = $this->input->post('intgedung');
            $data    = array(
                    'vckode'     => $vckode,
                    'vcnama'     => $vcnama,
                    'intjabatan' => $intjabatan,
                    'intgedung'  => $intgedung,
                    'intupdate'  => $this->session->intid,
                    'dtupdate'   => date('Y-m-d H:i:s')
                );
            $result = $this->modelapp->updatedata('m_karyawan',$data,$intid);
            if ($result) {
                redirect(base_url('production/karyawan/view'));
            }
        } elseif ($tipe == 'Hapus') {
            # code...
        } elseif ($tipe == 'ubahstatus') {
            $intstatus = 0;
            if ($status == 1) {
                $intstatus = 0;
            } elseif ($status == 0) {
                $intstatus = 1;
            }
            $data = array(
                'intstatus' => $intstatus,
                'intupdate' => $this->session->intid,
                'dtupdate'  => date('Y-m-d H:i:s')
            );
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        }
    }

    function ceknamakaryawan(){
        $vcnama     = $this->input->post('vcnama');
        $intjabatan = $this->input->post('intjabatan');
        $data       = $this->model->ceknamakaryawan($vcnama, $intjabatan);

        echo json_encode($data);
    }

}
