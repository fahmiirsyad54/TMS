<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permintaan extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('PermintaanModel');
        $this->model = $this->PermintaanModel;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $keyword = $this->input->get('key');

        $jmldata            = $this->model->getjmldata($this->table,$keyword);
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
        $data['dataHistory'] = $this->modelapp->getdatahistory($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){
        $data = array(
                    'intid'     => 0,
                    'vckode'    => '',
                    'intallset' => 0,
                    'intqty'    => 0,
                    'intadd'    => $this->session->intid,
                    'dtadd'     => date('Y-m-d H:i:s'),
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s'),
                    'intstatus' => 0
                );

        $data['title']          = $this->title;
        $data['action']         = 'Add';
        $data['controller']     = $this->controller;
        $data['dataPermintaan'] = [];
        $data['listgedung']     = $this->modelapp->getdatalist('m_gedung');
        $data['listmodel']      = $this->modelapp->getdatalist('m_models');
        //$data['listproses'] = $this->modelapp->getdatalist('m_proses');
        $data['listsize']       = $this->modelapp->getdatalist('m_size');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->model->getdatadetail($this->table,$intid);
        $data = array(
                    'intid'     => $resultData[0]->intid,
                    'vckode'    => $resultData[0]->vckode,
                    'intgedung' => $resultData[0]->intgedung,
                    'intmodel'  => $resultData[0]->intmodel,
                    'intproses' => $resultData[0]->intproses,
                    'intsize'   => $resultData[0]->intsize,
                    'intqty'    => $resultData[0]->intqty,
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s')
                );

        $data['title']      = $this->title;
        $data['action']     = 'Edit';
        $data['controller'] = $this->controller;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listmodel']  = $this->modelapp->getdatalist('m_models');
        $data['listproses'] = $this->modelapp->getdatalist('m_proses');
        $data['listsize']   = $this->modelapp->getdatalist('m_size');

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
            $vckode    = $this->input->post('vckode');
            $intgedung = $this->input->post('intgedung');
            $intmodel  = $this->input->post('intmodel');
            $intproses = $this->input->post('intproses');
            $intsize   = $this->input->post('intsize');
            $intqty    = $this->input->post('intqty');
            $data         = array(
                            'vckode'    => $vckode,
                            'intgedung' => $intgedung,
                            'intmodel'  => $intmodel,
                            'intproses' => $intproses,
                            'intsize'   => $intsize,
                            'intqty'    => $intqty,
                            'intadd'    => $this->session->intid,
                            'dtadd'     => date('Y-m-d H:i:s'),
                            'intupdate' => $this->session->intid,
                            'dtupdate'  => date('Y-m-d H:i:s'),
                            'intstatus' => 1
                        );
            $result = $this->modelapp->insertdata($this->table,$data);
            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Edit') {
            $vckode    = $this->input->post('vckode');
            $intgedung = $this->input->post('intgedung');
            $intmodel  = $this->input->post('intmodel');
            $intproses = $this->input->post('intproses');
            $intsize   = $this->input->post('intsize');
            $intqty    = $this->input->post('intqty');
            $data         = array(
                            'vckode'    => $vckode,
                            'intgedung' => $intgedung,
                            'intmodel'  => $intmodel,
                            'intproses' => $intproses,
                            'intsize'   => $intsize,
                            'intqty'    => $intqty,
                            'intupdate' => $this->session->intid,
                            'dtupdate'  => date('Y-m-d H:i:s')
                        );
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Delete') {
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

    function get_proses_ajax($intid){
        $data = $this->model->getproses($intid);

        echo json_encode($data);
    }

    function getkode($intsize, $intmodel, $intproses){
        $datagroup1  = $this->modelapp->getdatadetailcustom('m_models',$intmodel,'intid');
        $datagroup2  = $this->modelapp->getdatadetailcustom('m_proses',$intproses,'intid');
        $datagroup3  = $this->modelapp->getdatadetailcustom('m_size',$intsize,'intid');
        
        echo $datagroup1[0]->vckode . $datagroup2[0]->vckode . $datagroup3[0]->vckode;
    }

    function form_detail_permintaan(){
        $data['listproses'] = $this->modelapp->getdatalist('m_proses');
        $data['listsize']   = $this->modelapp->getdatalist('m_size');
        $data['controller'] = $this->controller;

        $this->load->view('permintaan_view/form_permintaan',$data);
    }

}
