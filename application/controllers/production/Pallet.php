<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pallet extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        
        $this->load->model('Production/PalletModel');
        $this->model = $this->PalletModel;

        $this->load->model('AppModel');
        $this->modelapp = $this->AppModel;

        if (!$this->session->intid && $this->session->intid != 'pms_prod') {
            redirect(base_url('akses/login'));
        }
    }

    function index(){
        redirect(base_url('production/pallet/view'));
    }

    function view($halaman=1){
        $intgedung  = $this->session->intgedung;
        $datagedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');

        $intmodel  = ($this->input->get('intmodel') == '') ? 0 : $this->input->get('intmodel');
        $intproses = ($this->input->get('intproses') == '') ? 0 : $this->input->get('intproses');
        $keyword   = $this->input->get('key');
        
        $jmldata    = $this->model->getjmldata('m_pallet', $intgedung, $intmodel, $intproses, $keyword);
        $offset     = ($halaman - 1) * 10;
        $jmlpage    = ceil($jmldata[0]->jmldata / 10);

        $data['title']      = $datagedung[0]->vcnama . ' - Pallet';
        $data['controller'] = 'production/pallet';
        $data['intgedung']  = $intgedung;
        $data['intmodel']   = $intmodel;
        $data['intproses']  = $intproses;
        $data['keyword']    = $keyword;
        $data['listmodel']  = $this->modelapp->getdatalistall('m_models');
        $data['listproses'] = $this->modelapp->getdatalistall('m_proses');
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $this->model->getdatalimit('m_pallet',$offset,10, $intgedung, $intmodel, $intproses,$keyword);
        
        $this->template->set_layout('default_prod')->build('production/pallet_view/index',$data);
    }

    function detail($intid){
        $data['controller']  = 'production/pallet';
        $data['dataMain']    = $this->model->getdatadetail('m_pallet',$intid);
        $this->load->view('production/pallet_view/detail',$data);
    }

    function add(){
        $intgedung  = $this->session->intgedung;
        $datagedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $data = array(
                    'intid'     => 0,
                    'vckode'    => '',
                    'vclokasi'  => '',
                    'intgedung' => $intgedung,
                    'vcgedung'  => $datagedung[0]->vcnama,
                    'intadd'    => $this->session->intid,
                    'dtadd'     => date('Y-m-d H:i:s'),
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s'),
                    'intstatus' => 0
                );

        $data['title']      = $datagedung[0]->vcnama . ' - Pallet';
        $data['action']     = 'Add';
        $data['controller'] = 'production/pallet';
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listcell']   = $this->modelapp->getdatalistall('m_cell', $intgedung, 'intgedung');
        $data['listroom']   = $this->model->getroom($intgedung);
        $data['listmodel']  = $this->modelapp->getdatalist('m_models');
        $data['listsisi']   = $this->modelapp->getdatalist('m_side');;
        $data['listsize']   = $this->modelapp->getdatalist('m_size');

        $this->template->set_layout('default_prod')->build('production/pallet_view/form',$data);
    }

    function edit($intid){
        $resultData = $this->model->getdatadetail('m_pallet',$intid);
        $intgedung  = $this->session->intgedung;
        $datagedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $data = array(
                    'intid'     => $resultData[0]->intid,
                    'vckode'    => $resultData[0]->vckode,
                    'intgedung' => $resultData[0]->intgedung,
                    'vcgedung'  => $datagedung[0]->vcnama,
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

        $data['title']      = $datagedung[0]->vcnama . ' - Pallet';
        $data['action']     = 'Edit';
        $data['controller'] = 'production/pallet';
        $data['listcell']   = $this->modelapp->getdatalistall('m_cell', $intgedung, 'intgedung');
        $data['listroom']   = $this->model->getroom($intgedung);
        $data['listmodel']  = $this->modelapp->getdatalist('m_models');
        $data['listproses'] = $this->modelapp->getdatalist('m_proses');
        $data['listsize']   = $this->modelapp->getdatalist('m_size');
        $data['listsisi']   = $this->modelapp->getdatalistall('m_side');

        $this->template->set_layout('default_prod')->build('production/pallet_view/form',$data);
    }

    function validasiform($tipe){
        $array = array();
        $data = $this->input->post();
        if ($tipe == 'data') {
            foreach ($data as $key => $value) {
                $result = $this->modelapp->getdatadetailcustom('m_pallet',$value,$key);
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
            $result = $this->modelapp->insertdata('m_pallet',$data);
            if ($result) {
                redirect(base_url('production/pallet/view'));
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
            $result = $this->modelapp->updatedata('m_pallet',$data,$intid);
            if ($result) {
                redirect(base_url('production/pallet/view'));
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
            $result = $this->modelapp->updatedata('m_pallet',$data,$intid);
            if ($result) {
                redirect(base_url('production/pallet/view'));
            }
        }
    }

    function get_proses_ajax($intid){
        $data = $this->model->getproses($intid);
        echo json_encode($data);
    }

    function getkode($intsisi, $intgedung, $intmodel, $intproses, $intsize, $intid){
        if ($intgedung == 1) {
            $vcgedung = 'A';
        } else if ($intgedung == 2) {
            $vcgedung = 'B';
        } else if ($intgedung == 3) {
            $vcgedung = 'C';
        } else if ($intgedung == 4) {
            $vcgedung = 'D';
        } else if ($intgedung == 5) {
            $vcgedung = 'E';
        }
        $datapallet = $this->modelapp->getdatadetailcustom('m_pallet',$intid,'intid');
        if ($intid == 0) {
            $getlastkode = $this->model->buat_kode();
        }else {
            $vckode      = $datapallet[0]->vckode;
            $getlastkode = substr($vckode,-5);
        }

        $datagroup1  = $this->modelapp->getdatadetailcustom('m_models',$intmodel,'intid');
        $datagroup2  = $this->modelapp->getdatadetailcustom('m_proses',$intproses,'intid');
        $datagroup3  = $this->modelapp->getdatadetailcustom('m_size',$intsize,'intid');
        
        echo $vcgedung . $datagroup1[0]->vckode . $datagroup2[0]->vckode . $datagroup3[0]->vckode . $intsisi . $getlastkode;
    }

}
