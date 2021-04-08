<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends MY_Controller {

	function __construct(){
        parent::__construct();
        $this->load->model('KaryawanModel');
        $this->model = $this->KaryawanModel;
    }

    function index(){
    	redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $keyword = $this->input->get('key');

        $jmldata            = $this->modelapp->getjmldata($this->table,$keyword);
        $offset             = ($halaman - 1) * $this->limit;
        $jmlpage            = ceil($jmldata[0]->jmldata / $this->limit);

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['keyword']    = $keyword;
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit,$keyword);

        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function detail($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail($this->table,$intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add($intjabatan=0){
        $data = array(
                    'intid'      => 0,
                    'vckode'     => '',
                    'vcnama'     => '',
                    'intjabatan' => $intjabatan,
                    'intgedung'  => 0,
                    'intadd'     => $this->session->intid,
                    'dtadd'      => date('Y-m-d H:i:s'),
                    'intupdate'  => $this->session->intid,
                    'dtupdate'   => date('Y-m-d H:i:s'),
                    'intstatus'  => 0
                );

        $data['title']       = $this->title;
        $data['action']      = 'Add';
        $data['controller']  = $this->controller;
        $data['listjabatan'] = $this->modelapp->getdatalist('m_jabatan');
        $data['listgedung']  = $this->modelapp->getdatalist('m_gedung');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->modelapp->getdatadetail($this->table,$intid);
        $data = array(
                    'intid'      => $resultData[0]->intid,
                    'vckode'     => $resultData[0]->vckode,
                    'vcnama'     => $resultData[0]->vcnama,
                    'intjabatan' => $resultData[0]->intjabatan,
                    'intgedung'  => $resultData[0]->intgedung,
                    'intupdate'  => $this->session->intid,
                    'dtupdate'   => date('Y-m-d H:i:s')
                );

        $data['title']       = $this->title;
        $data['action']      = 'Edit';
        $data['controller']  = $this->controller;
        $data['listjabatan'] = $this->modelapp->getdatalist('m_jabatan');
        $data['listgedung']  = $this->modelapp->getdatalist('m_gedung');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
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

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                redirect(base_url($this->controller . '/view'));
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
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            if ($result) {
                redirect(base_url($this->controller . '/view'));
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

    function getmahasiswaajax($intid){
        $data = $this->modelapp->getdatadetailcustom('mmahasiswa',$intid,'intid');

        echo json_encode($data);
    }

    function ceknamakaryawan(){
        $vcnama     = $this->input->post('vcnama');
        $intjabatan = $this->input->post('intjabatan');
        $data       = $this->model->ceknamakaryawan($vcnama, $intjabatan);

        echo json_encode($data);
    }

}
