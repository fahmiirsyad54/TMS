<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Models extends MY_Controller {

	function __construct(){
        parent::__construct();
        $this->load->model('ModelsModel');
        $this->model = $this->ModelsModel;
    }

 
    function index(){
    	redirect(base_url($this->controller . '/lihat'));
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
        $data['controller'] = $this->controller;
        $data['dataMain']   = $this->modelapp->getdatadetail($this->table,$intid);
        $data['dataDetail'] = $this->model->get_detail_proses($intid);
        
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){ 
        $data = array(
                    'intid'        => 0,
                    'vckode'       => '',
                    'vcnama'       => '',
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 0
                );

        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        $data['dataModels'] = [];
        $data['listproses'] = $this->modelapp->getdatalist('m_proses');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData  = $this->modelapp->getdatadetail($this->table,$intid);
        $dataModels  = $this->model->get_detail_proses($intid);
        $proses    = [];

        foreach ($dataModels as $dm) {
            $listproses = $this->modelapp->getdatalist('m_proses');
            $datatemp = array(
                        'intid'      => $dm->intid,
                        'intproses'  => $dm->intproses,
                        'listproses' => $listproses

                    );
            array_push($proses, $datatemp);   
        }

        $data = array(
                    'intid'        => $resultData[0]->intid,
                    'vckode'       => $resultData[0]->vckode,
                    'vcnama'       => $resultData[0]->vcnama,
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s')
                );

        $data['title']         = $this->title;
        $data['action']        = 'Edit';
        $data['controller']    = $this->controller;
        $data['dataModels']    = $proses;
        $data['listproses']  = (count($dataModels) == 0) ? $this->modelapp->getdatalist('m_proses') : $proses;

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
                    $array[]['error'] = $error . ' Already Exist !';
                }
            }
        } elseif ($tipe == 'required') {
            foreach ($data as $key => $value) {
                if ($value == '') {
                    $front = substr($key,0,2);
                    $end   = substr($key,2);
                    $end2  = substr($key,3);
                    $error = ($front == 'vc') ? $end : $end2 ;
                    $array[]['error'] = 'column ' . $error . ' can not be empty !';
                }
            }
        }
        echo json_encode($array);
    }

    function aksi($tipe,$intid,$status=0){
        if ($tipe == 'Add') {
            //
            $vckode      = $this->input->post('vckode');
            $vcnama      = $this->input->post('vcnama');
            $intproses   = $this->input->post('intproses');
            $countdetail = count($intproses);

            $data    = array(
                    'vckode'       => $vckode,
                    'vcnama'       => $vcnama,
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 1
                );

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                for ($i=0; $i < $countdetail; $i++) {
                    $data_detail = array(
                                    'intmodel'     => $result,
                                    'intproses'   => $intproses[$i]
                                );
                        $resultproses = $this->modelapp->insertdata($this->table . '_proses',$data_detail);
                }
                
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Edit') {
            $vckode         = $this->input->post('vckode');
            $vcnama         = $this->input->post('vcnama');
            $intmodelproses = $this->input->post('intmodelproses');
            $intproses      = $this->input->post('intproses');
            $countdetail    = count($intproses);

            $data    = array(
                    'vckode'    => $vckode,
                    'vcnama'    => $vcnama,
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s')
                );
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            if ($result) {
                //comelz
                $this->modelapp->deletedata($this->table . '_proses',$intid,'intmodel');
                for ($i=0; $i < $countdetail; $i++) { 
                    $data_detail = array(
                                    'intmodel'  => $intid,
                                    'intproses' => $intproses[$i]
                                );
                        $resultproses = $this->modelapp->insertdata($this->table . '_proses',$data_detail);
                }

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

    function getdetailajax($intid){
        $data = $this->model->get_detail_komponen($intid);
        echo json_encode($data);
    }

    function form_detail_models(){
        $data['listproses'] = $this->modelapp->getdatalist('m_proses');
        $data['controller'] = $this->controller;

        $this->load->view('models_view/form_models',$data);
    }

    function getintkomponen($intkomponen){
        $data = $this->model->getintkomponen($intkomponen);
        echo json_encode($data);
    }

}
