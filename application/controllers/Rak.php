<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rak extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('RakModel');
        $this->model = $this->RakModel;
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
        $kodeunik   = $this->RakModel->buat_kode();
        $data = array(
                    'intid'         => 0,
                    'intjumlahrak' => 0,
                    'intadd'        => $this->session->intid,
                    'dtadd'         => date('Y-m-d H:i:s'),
                    'intupdate'     => $this->session->intid,
                    'dtupdate'      => date('Y-m-d H:i:s'),
                    'intstatus'     => 0
                );
        
        $data['listgedung'] = $this->modelapp->getdatalistall('m_gedung');
        $data['title']       = $this->title;
        $data['action']      = 'Add';
        $data['controller']  = $this->controller;

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
            $intgedung    = $this->input->post('intgedung');
            $intjumlahrak = $this->input->post('intjumlahrak');
            $vcgedung     = $this->input->post('vcgedung');
            
            // $datarak = $this->modelapp->getdatadetailcustom('m_rak', $intgedung, 'intgedung');
            // $jmlrak = $intjumlahrak - count($datarak);
            
            for ($i=0; $i < $intjumlahrak; $i++) {
                $kodeunik          = $this->RakModel->buat_kode();
                $getlastkode       = $this->model->buat_nama($intgedung);
                
                $data         = array(
                    'intgedung' => $intgedung,
                    'vckode'    => $kodeunik,
                    'vcnama'    => $vcgedung . $getlastkode,
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

}
