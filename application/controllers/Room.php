<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('RoomModel');
        $this->model = $this->RoomModel;
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

    function add(){
        $data = array(
                    'intid'         => 0,
                    'intjumlahroom' => 0,
                    'intadd'        => $this->session->intid,
                    'dtadd'         => date('Y-m-d H:i:s'),
                    'intupdate'     => $this->session->intid,
                    'dtupdate'      => date('Y-m-d H:i:s'),
                    'intstatus'     => 0
                );
        
        $data['listgedung'] = $this->modelapp->getdatalistall('m_gedung');
        $data['listrak']    = $this->modelapp->getdatalistall('m_rak');
        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->modelapp->getdatadetail($this->table,$intid);
        $dataroom = $this->modelapp->getdatadetailcustom('m_room', $intid , 'intrak');
        $data = array(
                    'intid'         => $resultData[0]->intid,
                    'vckode'        => $resultData[0]->vckode,
                    'vcnama'        => $resultData[0]->vcnama,
                    'intgedung'     => $resultData[0]->intgedung,
                    'intjumlahroom' => count($dataroom),
                    'intupdate'     => $this->session->intid,
                    'dtupdate'      => date('Y-m-d H:i:s')
                );

        $data['listgedung'] = $this->modelapp->getdatalistall('m_gedung');
        $data['action']     = 'Edit';
        $data['title']       = $this->title;
        $data['controller'] = $this->controller;
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
                    $array[]['error'] = 'Column ' . $error . ' can not be empty !';
                }
            }
        }
        echo json_encode($array);
    }

    function aksi($tipe,$intid,$status=0){
        if ($tipe == 'Add') {
            $intrak        = $this->input->post('intrak');
            $intjumlahroom = $this->input->post('intjumlahroom');
            $datarak       = $this->modelapp->getdatadetail('m_rak', $intrak);
            $vcrak         = $datarak[0]->vcnama;
            
            for ($i=0; $i < $intjumlahroom; $i++) {
                $kodeunik          = $this->RoomModel->buat_kode();
                $getlastkode       = $this->model->buat_nama($intrak);
                $data         = array(
                    'intrak'    => $intrak,
                    'vckode'    => $kodeunik,
                    'vcnama'    => $vcrak . $getlastkode,
                    'intadd'    => $this->session->intid,
                    'dtadd'     => date('Y-m-d H:i:s'),
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s'),
                    'intstatus' => 1
                );
                $this->modelapp->insertdata($this->table,$data);
            }
                redirect(base_url($this->controller . '/view'));
            
        } elseif ($tipe == 'Edit') {
            $vckode        = $this->input->post('vckode');
            $vcnama        = $this->input->post('vcnama');
            $intgedung     = $this->input->post('intgedung');
            $intjumlahroom = $this->input->post('intjumlahroom');
           
            $data    = array(
                    'vckode'     => $vckode,
                    'vcnama'     => $vcnama,
                    'vcwarna'    => $vcwarna,
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

    function getnama($intgedung){
        $datagedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $getlastkode = $this->model->buat_nama($intgedung);
        
        echo $datagedung[0]->vckode . $getlastkode;
    }

    function get_rak_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_rak',$intid,'intgedung');
        echo json_encode($data);
    }

}
