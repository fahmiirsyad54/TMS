<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cell extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('CellModel');
        $this->model = $this->CellModel;
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
                    'vckode'        => '',
                    'vcnama'        => '',
                    'intgedung'     => 0,
                    'intjumlahcell' => 0,
                    'vcusername'    => '',
                    'vcpassword'    => '',
                    'inthakakses'   => 0,
                    'vchakakses'    => '',
                    'intadd'        => $this->session->intid,
                    'dtadd'         => date('Y-m-d H:i:s'),
                    'intupdate'     => $this->session->intid,
                    'dtupdate'      => date('Y-m-d H:i:s'),
                    'intstatus'     => 0
                );

        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listtype']   = array('1' => 'Cell',
                                    '2' => 'Central Cutting',
                                    '3' => 'Central Computer Stitching',
                                    '4' => 'Training',
                                    '5' => 'Stand By',
                                    '6' => 'No Sew',
                                    '7' => 'Central Emboss',
                                    '8' => 'Hot Press',
                                    '9' => 'Compound Rolling',
                                    '10' => 'UV',
                                    '11' => 'Stockfit',
                                    '12' => 'Coating');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->modelapp->getdatadetail($this->table,$intid);
        $vcwarna = ($resultData[0]->vcwarna == '') ? '#ff0000' : $resultData[0]->vcwarna;
        $data = array(
                    'intid'     => $resultData[0]->intid,
                    'vckode'    => $resultData[0]->vckode,
                    'vcnama'    => $resultData[0]->vcnama,
                    'intgedung' => $resultData[0]->intgedung,
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s')
                );

        $data['title']      = $this->title;
        $data['action']     = 'Edit';
        $data['controller'] = $this->controller;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listtype']   = array('1' => 'Cell', '2' => 'Central Cutting', '3' => 'Central Computer Stitching', '4' => 'Training', '5' => 'Stand By');
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
            $intgedung                 = $this->input->post('intgedung');
            $intjumlahcell             = $this->input->post('intjumlahcell');
            $inttype                   = $this->input->post('inttype');
            $vcgedung                  = $this->input->post('vcgedung');
            $vcnama                    = substr($vcgedung, -1);
            $vclastcode                = (count($this->model->get_last_name($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name($intgedung,$inttype)[0]->vcnama;
            $vclastcodecutting         = (count($this->model->get_last_name_cutting($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name_cutting($intgedung,$inttype)[0]->vcnama;
            $vclastcodestitching       = (count($this->model->get_last_name_stitching($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name_stitching($intgedung,$inttype)[0]->vcnama;
            $vclastcodetraining        = (count($this->model->get_last_name_training($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name_training($intgedung,$inttype)[0]->vcnama;
            $vclastcodestandby         = (count($this->model->get_last_name_standby($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name_standby($intgedung,$inttype)[0]->vcnama;
            $vclastcodenosew           = (count($this->model->get_last_name_nosew($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name_nosew($intgedung,$inttype)[0]->vcnama;
            $vclastcodeemboss          = (count($this->model->get_last_name_emboss($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name_emboss($intgedung,$inttype)[0]->vcnama;
            $vclastcodehotpress        = (count($this->model->get_last_name_hotpress($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name_hotpress($intgedung,$inttype)[0]->vcnama;
            $vclastcodecompoundrolling = (count($this->model->get_last_name_compoundrolling($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name_compoundrolling($intgedung,$inttype)[0]->vcnama;
            $vclastcodeuv              = (count($this->model->get_last_name_uv($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name_uv($intgedung,$inttype)[0]->vcnama;
            $vclastcodestockfit        = (count($this->model->get_last_name_stockfit($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name_stockfit($intgedung,$inttype)[0]->vcnama;
            $vclastcodecoating         = (count($this->model->get_last_name_coating($intgedung,$inttype)) == 0) ? 0 : $this->model->get_last_name_coating($intgedung,$inttype)[0]->vcnama;

            if ($inttype == 2) {
                $vccell = 'Central Cutting';
                $intjumlahcell = 1;
            } elseif ($inttype == 3) {
                $vccell = 'Central Stitching';
                $intjumlahcell = 1;
            } elseif ($inttype == 4) {
                $vccell = 'Training';
                $intjumlahcell = 1;
            } elseif ($inttype == 5) {
                $vccell = 'Stand By';
                $intjumlahcell = 1;
            } elseif ($inttype == 6) {
                $vccell = 'No Sew';
            } elseif ($inttype == 7) {
                $vccell = 'Central Emboss';
                $intjumlahcell = 1;
            }  elseif ($inttype == 8) {
                $vccell = 'Hot Press';
            }  elseif ($inttype == 9) {
                $vccell = 'Compound Rolling';
            }  elseif ($inttype == 10) {
                $vccell = 'UV';
            }  elseif ($inttype == 11) {
                $vccell = 'Stockfit';
            }  elseif ($inttype == 12) {
                $vccell = 'Coating';
            }  else {
                $vccell = 'Cell';
            }

            for ($i=0; $i < $intjumlahcell; $i++) {
                if ($inttype == 2) {
                    $cellnumber   = ++$vclastcodecutting;
                } elseif ($inttype == 3) {
                    $cellnumber   = ++$vclastcodestitching;
                } elseif ($inttype == 4) {
                    $cellnumber   = ++$vclastcodetraining;
                } elseif ($inttype == 5) {
                    $cellnumber   = ++$vclastcodestandby;
                } elseif ($inttype == 6) {
                    $cellnumber   = ++$vclastcodenosew;
                } elseif ($inttype == 7) {
                    $cellnumber   = ++$vclastcodeemboss;
                } elseif ($inttype == 8) {
                    $cellnumber   = ++$vclastcodehotpress;
                } elseif ($inttype == 9) {
                    $cellnumber   = ++$vclastcodecompoundrolling;
                } elseif ($inttype == 10) {
                    $cellnumber   = ++$vclastcodeuv;
                } elseif ($inttype == 11) {
                    $cellnumber   = ++$vclastcodestockfit;
                } elseif ($inttype == 12) {
                    $cellnumber   = ++$vclastcodecoating;
                } else {
                    $cellnumber   = ++$vclastcode;
                }
                $kodeunikcell = $this->CellModel->buat_kode_cell();
                
                $data         = array(
                    'intgedung' => $intgedung,
                    'vckode'    => $kodeunikcell,
                    'vcnama'    => ($inttype < 6) ? $vccell.' ' . $vcnama . $cellnumber : $vccell.' ' . $cellnumber,
                    'inttype'   => $inttype,
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
             $vckode    = $this->input->post('vckode');
             $vcnama    = $this->input->post('vcnama');
             $intgedung = $this->input->post('intgedung');
           
            $data    = array(
                    'vckode'    => $vckode,
                    'vcnama'    => $vcnama,
                    'intgedung' => $intgedung,
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s')
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

}
