<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pallet extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('PalletModel');
        $this->model = $this->PalletModel;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $intgedung = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intmodel  = ($this->input->get('intmodel') == '') ? 0 : $this->input->get('intmodel');
        $intproses = ($this->input->get('intproses') == '') ? 0 : $this->input->get('intproses');
        $keyword   = $this->input->get('key');

        $jmldata            = $this->model->getjmldata($this->table,$intgedung, $intmodel, $intproses, $keyword);
        $offset             = ($halaman - 1) * $this->limit;
        $jmlpage            = ceil($jmldata[0]->jmldata / $this->limit);

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['intgedung']  = $intgedung;
        $data['intmodel']   = $intmodel;
        $data['intproses']  = $intproses;
        $data['listgedung'] = $this->modelapp->getdatalistall('m_gedung');
        $data['listmodel']  = $this->modelapp->getdatalistall('m_models');
        $data['listproses'] = $this->modelapp->getdatalistall('m_proses');
        $data['keyword']    = $keyword;
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit,$intgedung, $intmodel, $intproses,$keyword);
        
        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function detail($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail($this->table,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){
        $sisi = array(  '1' => 'Right',
                        '2' => 'Left'
                );
        $data = array(
                    'intid'     => 0,
                    'vckode'    => '',
                    'vclokasi'  => '',
                    'intadd'    => $this->session->intid,
                    'dtadd'     => date('Y-m-d H:i:s'),
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s'),
                    'intstatus' => 0
                );

        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listmodel']  = $this->modelapp->getdatalistall('m_models');
        //$data['listproses'] = $this->modelapp->getdatalist('m_proses');
        $data['listsize']   = $this->modelapp->getdatalist('m_size');
        $data['listsisi']   = $this->modelapp->getdatalist('m_side');


        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $sisi = array(  '1' => 'Right',
                        '2' => 'Left'
                );
        $resultData = $this->model->getdatadetail($this->table,$intid);
        $data = array(
                    'intid'     => $resultData[0]->intid,
                    'vckode'    => $resultData[0]->vckode,
                    'intgedung' => $resultData[0]->intgedung,
                    'intmodel'  => $resultData[0]->intmodel,
                    'intproses' => $resultData[0]->intproses,
                    'intsize'   => $resultData[0]->intsize,
                    'intsisi'   => $resultData[0]->intsisi,
                    'intlokasi' => $resultData[0]->intlokasi,
                    'introom'   => $resultData[0]->introom,
                    'vclokasi'  => $resultData[0]->vclokasi,
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s')
                );

        $data['title']      = $this->title;
        $data['action']     = 'Edit';
        $data['controller'] = $this->controller;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listmodel']  = $this->modelapp->getdatalistall('m_models');
        $data['listproses'] = $this->modelapp->getdatalist('m_proses');
        $data['listsize']   = $this->modelapp->getdatalist('m_size');
        $data['listcell']   = $this->modelapp->getdatalist('m_cell');
        $data['listroom']   = $this->modelapp->getdatalistall('m_room');
        $data['listsisi']   = $this->modelapp->getdatalist('m_side');

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
            $intsisi   = $this->input->post('intsisi');
            $intlokasi = $this->input->post('intlokasi');
            $introom   = $this->input->post('introom');
            $vclokasi  = $this->input->post('vclokasi');
            $data         = array(
                            'vckode'    => $vckode,
                            'intgedung' => $intgedung,
                            'intmodel'  => $intmodel,
                            'intproses' => $intproses,
                            'intsize'   => $intsize,
                            'intsisi'   => $intsisi,
                            'intlokasi' => $intlokasi,
                            'introom'   => $introom,
                            'vclokasi'  => $vclokasi,
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
            $intsisi   = $this->input->post('intsisi');
            $intlokasi = $this->input->post('intlokasi');
            $introom   = $this->input->post('introom');
            $vclokasi  = $this->input->post('vclokasi');
            $data         = array(
                            'vckode'    => $vckode,
                            'intgedung' => $intgedung,
                            'intmodel'  => $intmodel,
                            'intproses' => $intproses,
                            'intsize'   => $intsize,
                            'intsisi'   => $intsisi,
                            'intlokasi' => $intlokasi,
                            'introom'   => $introom,
                            'vclokasi'  => $vclokasi,
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

    function get_cell_ajax($intid){
        $data = $this->model->getcell($intid);
        echo json_encode($data);
    }

    function get_room_ajax($intid){
        $data = $this->model->getroom($intid);
        echo json_encode($data);
    }

    function getkode($intsisi, $intgedung, $intmodel, $intproses, $intsize, $intid){
        $datapallet = $this->modelapp->getdatadetailcustom('m_pallet',$intid,'intid');
        if ($intid == 0) {
            $getlastkode = $this->model->buat_kode();
        }else {
            $vckode      = $datapallet[0]->vckode;
            $getlastkode = substr($vckode,-5);
        }

        $datagroup1  = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $datagroup2  = $this->modelapp->getdatadetailcustom('m_models',$intmodel,'intid');
        $datagroup3  = $this->modelapp->getdatadetailcustom('m_proses',$intproses,'intid');
        $datagroup4  = $this->modelapp->getdatadetailcustom('m_size',$intsize,'intid');
        
        echo $datagroup1[0]->vckode . $datagroup2[0]->vckode . $datagroup3[0]->vckode . $datagroup4[0]->vckode . $intsisi . $getlastkode;
    }

    function addbarcode() {
        $data   = array(
            'intuser'   => $this->session->intid,
            'dttanggal' => date('Y-m-d H:i:s')
        );
        $result     = $this->modelapp->insertdata('m_pallettemp',$data);
        
        if ($result) {
            $datatemp = $this->model->getdatatemp();
            redirect(base_url('pallet/add_barcode/' . $datatemp[0]->intid));
        }
    }

    function add_barcode($intid) {
        $data = array(
                'intid' => $intid,        
                'vckode'       => ''
                );
        $data['listroom']   = $this->modelapp->getdatalist('m_room');
        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        
        $this->template->set_layout('default')->build($this->view . '/formbarcode',$data);
    }

    function getdatadefault_ajax($intid){
        $data['datapallet'] = $this->model->getdatapallet($intid);
        echo json_encode($data);
    }

    function simpan_pallet_scan($vckode, $introom, $intid){
        $kodegedung = substr($vckode,0,2);
        $kodemodel  = substr($vckode,2,3);
        $kodeproses = substr($vckode,5,3);
        $kodesize   = substr($vckode,8,2);
        $kodeside   = substr($vckode,10,1);
        
        $datadetail = $this->model->getdatadetailpallet($vckode, $intid);
        $datapallet = $this->modelapp->getdatadetailcustom('m_pallet', $vckode, 'vckode');
        $datagedung = $this->modelapp->getdatadetailcustom('m_gedung', $kodegedung, 'vckode');
        $datamodel  = $this->modelapp->getdatadetailcustom('m_models', $kodemodel, 'vckode');
        $dataproses = $this->modelapp->getdatadetailcustom('m_proses', $kodeproses, 'vckode');
        $datasize   = $this->modelapp->getdatadetailcustom('m_size', $kodesize, 'vckode');
        $dataside   = $this->modelapp->getdatadetailcustom('m_side', $kodeside, 'vckode');

        if (count($datapallet) == 1 || count($datadetail) == 1) {
            $data['intstatus'] = 1;
            echo json_encode($data);
        } else if (count($datagedung) == 0) {
            $data['intstatus'] = 2;
            echo json_encode($data);
        } else if (count($datamodel) == 0) {
            $data['intstatus'] = 3;
            echo json_encode($data);
        } else if (count($dataproses) == 0) {
            $data['intstatus'] = 4;
            echo json_encode($data);
        } else if (count($datasize) == 0) {
            $data['intstatus'] = 5;
            echo json_encode($data);
        } else if (count($dataside) == 0) {
            $data['intstatus'] = 6;
            echo json_encode($data);
        } else {
            $intgedung = $datagedung[0]->intid;
            $intmodel  = $datamodel[0]->intid;
            $intproses = $dataproses[0]->intid;
            $intsize   = $datasize[0]->intid;
            $intside   = $dataside[0]->intid;

            $data2 = array(
                'inttemp'   => $intid,
                'introom'   => $introom,
                'vckode'    => $vckode,
                'intgedung' => $intgedung,
                'intmodel'  => $intmodel,
                'intproses' => $intproses,
                'intsize'   => $intsize,
                'intsisi'   => $intside
            );

            $result = $this->modelapp->insertdata('m_pallettemp_detail',$data2);
            if ($result) {
                $data['datapallet'] = $this->model->getdatapallet($intid);
                echo json_encode($data);
            }
        }
    }       

    function hapusdata_ajax($intpallet, $intid){
        $this->modelapp->deletedata('m_pallettemp_detail',$intpallet,'intid');
        $data['datapallet'] = $this->model->getdatapallet($intid);
        echo json_encode($data);
    }

    function batalSimpan($intid){
        $data      = $this->modelapp->getdatadetailcustom('m_pallettemp_detail',$intid,'inttemp');
        if (count($data) === 0) {
            $this->modelapp->deletedata('m_pallettemp',$intid,'intid');
            redirect(base_url('pallet/view'));
         } else {
            $this->modelapp->deletedata('m_pallettemp',$intid,'intid');
            $this->modelapp->deletedata('m_pallettemp_detail',$intid,'inttemp');
            redirect(base_url('pallet/view'));
        }
    }

    function simpanPallet($intid){
        //update data pallet
        $data       = $this->modelapp->getdatadetailcustom('m_pallettemp_detail',$intid,'inttemp');
        foreach ($data as $dataset) { 
                $dataroom = $this->modelapp->getdatadetailcustom('m_room', $dataset->introom, 'intid');
                $vclokasi   = $dataroom[0]->vcnama;
                $data         = array(
                    'vckode'    => $dataset->vckode,
                    'intgedung' => $dataset->intgedung,
                    'intmodel'  => $dataset->intmodel,
                    'intproses' => $dataset->intproses,
                    'intsize'   => $dataset->intsize,
                    'intsisi'   => $dataset->intsisi,
                    'intlokasi' => 0,
                    'introom'   => $dataset->introom,
                    'vclokasi'  => $vclokasi,
                    'intadd'    => $this->session->intid,
                    'dtadd'     => date('Y-m-d H:i:s'),
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s'),
                    'intstatus' => 1
                );
                $result = $this->modelapp->insertdata($this->table,$data);
        }
        if ($result) {
            redirect(base_url('pallet/view'));
        }
    }

}
