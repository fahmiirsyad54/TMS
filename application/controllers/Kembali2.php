<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kembali extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('KembaliModel');
        $this->model = $this->KembaliModel;
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
                    'intid'        => 0,
                    'vckode'       => '',
                    'intqty'       => 0,
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 0
                );

        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listmodel']  = $this->modelapp->getdatalist('m_models');
        //$data['listproses'] = $this->modelapp->getdatalist('m_proses');
        $data['listsize']   = $this->modelapp->getdatalist('m_size');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $datakembali = $this->model->getdatakembali($intid);
        $data = array(
                    'intid'         => $datakembali[0]->intid,
                    'intpinjam'     => $datakembali[0]->intpinjam,
                    'vckodekembali' => $datakembali[0]->vckode,
                    'vcgedung'      => $datakembali[0]->vcgedung,
                    'vccell'        => $datakembali[0]->vccell,
                    'vckaryawan'    => $datakembali[0]->vckaryawan,
                    'vckodepinjam'  => $datakembali[0]->vckodepinjam,
                    'dtpinjam'      => $datakembali[0]->dtpinjam

                );
        $data['title']       = $this->title;
        $data['action']      = 'Edit';
        $data['controller']  = $this->controller;
        $data['datakembali'] = $this->model->getdatakembalipallet($intid);

        if ($datakembali) {
            $this->template->set_layout('default')->build($this->view . '/form',$data);
        }
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
    
    function transaksi($intkembali) {
        $datakembali = $this->model->getdatakembali($intkembali);
        $data = array(
                    'intid'         => $datakembali[0]->intid,
                    'intpinjam'     => $datakembali[0]->intpinjam,
                    'vckodekembali' => $datakembali[0]->vckode,
                    'vcgedung'      => $datakembali[0]->vcgedung,
                    'vccell'        => $datakembali[0]->vccell,
                    'vckaryawan'    => $datakembali[0]->vckaryawan,
                    'vckodepinjam'  => $datakembali[0]->vckodepinjam,
                    'dtpinjam'      => $datakembali[0]->dtpinjam,
                    'vckode'        => ''

                );
        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        $data['listroom']   = $this->model->getdataroom($datakembali[0]->intgedung);
        if ($datakembali) {
            $this->template->set_layout('default')->build($this->view . '/transaksi',$data);
        }
    }

    function getdatadefault_ajax($intkembali){
        $datapinjam = $this->modelapp->getdatadetail('pr_kembali', $intkembali);

        $data['listkondisi'] = $this->modelapp->getdatalist('m_kondisi');
        $data['datakembali'] = $this->model->getdatakembalipallet($intkembali);
        $data['datapinjam']  = $this->model->getdatapinjam($datapinjam[0]->intpinjam);
        
        echo json_encode($data);
    }

    function get_cell_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_cell',$intid,'intgedung');
        echo json_encode($data);
    }

    function get_karyawan_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_karyawan',$intid,'intgedung');
        echo json_encode($data);
    }

    function simpan_kembali_scan($vckode, $intkembali, $introom){
        // $datakembali = $this->model->getdatadetailpallet($vckode, $intkembali);
        // if (count($datakembali) >= 1) {
        //     $data['intstatus'] = 2;
        //     echo json_encode($data);
        // } else {
            $datapinjam = $this->model->getdatapinjampallet($vckode, $intkembali);
            if (count($datapinjam) == 0) {
                $data['intstatus'] = 2;
                echo json_encode($data);
            } else {
                if ($datapinjam[0]->intstatus == 0) {
                    $data['intstatus'] = 2;
                    echo json_encode($data);
                } else {
                    $intpallet = $datapinjam[0]->intpallet;
                    //insert to detail kembali
                    $datadetail = array(
                        'intkembali' => $intkembali,
                        'intpallet'  => $intpallet,
                        'intkondisi' => 1,
                        'dtkembali'  => date('Y-m-d H:i:s'),
                        'intuser'    => $this->session->intid,
                        'introom'    => $introom
                    );
                    $result = $this->modelapp->insertdata('pr_kembali_detail',$datadetail);

                    //Update data pallet
                    $dataroom = $this->modelapp->getdatadetailcustom('m_room', $introom, 'intid');
                    $updateroom = array(
                        'intlokasi' => 0,
                        'introom'   => $introom,
                        'vclokasi'  => $dataroom[0]->vcnama,
                        'intstatus' => 1
                    );
                    $result = $this->modelapp->updatedata('m_pallet',$updateroom,$intpallet);

                    //update status peminjaman pallet
                    $intpinjam = $datapinjam[0]->intpinjam;
                    $dataupdatepinjam = array(
                        'intstatus' => 0
                    );
                    $result = $this->model->updatedatapinjam($intpinjam, $intpallet, $dataupdatepinjam);
                    if ($result) {
                        $data['datapinjam']  = $this->model->getdatapinjam($intpinjam);
                        $data['datakembali'] = $this->model->getdatakembalipallet($intkembali);
                        echo json_encode($data);
                    }
                }
            }
        // }
    }

    function ubahKondisi($intid, $intkembali){
        $datadetail = $this->modelapp->getdatadetailcustom('pr_kembali_detail', $intid, 'intid');
        $data['controller']  = $this->controller;
        $data['intid']       = $intid;
        $data['intkembali']  = $intkembali;
        $data['intkondisi']  = $datadetail[0]->intkondisi;
        
        $this->load->view($this->view . '/ganti_kondisi',$data);
    }

    function ubahkondisi_ajax($intkondisi, $intid, $intkembali){
        $datadetail = $this->modelapp->getdatadetailcustom('pr_kembali_detail', $intid, 'intid');
        $intpallet  = $datadetail[0]->intpallet;
        if ($intkondisi == 2) {
            $updatepallet = array(
                'intlokasi' => 0,
                'introom'   => 0,
                'vclokasi'  => 'Repairing',
                'intstatus' => 3
            );
            $this->modelapp->updatedata('m_pallet',$updatepallet,$intpallet);

            $dataperbaikan = array(
                'intpallet'   => $intpallet,
                'intkondisi'  => $intkondisi,
                'intuser'     => $this->session->intid,
                'dtperbaikan' => date('Y-m-d H:i:s'),
                'intadd'      => $this->session->intid,
                'dtadd'       => date('Y-m-d H:i:s'),
                'intupdate'   => $this->session->intid,
                'dtupdate'    => date('Y-m-d H:i:s'),
                'intstatus'   => 1
            );
            $result      = $this->modelapp->insertdata('pr_perbaikan',$dataperbaikan);
        } else if ($intkondisi == 3) {
            $updatepallet = array(
                'intlokasi' => 0,
                'introom'   => 0,
                'vclokasi'  => 'Damaged',
                'intstatus' => 0
            );
            $this->modelapp->updatedata('m_pallet',$updatepallet,$intpallet);

            $datarusak = array(
                'intpallet' => $intpallet,
                'intuser'   => $this->session->intid,
                'dtrusak'   => date('Y-m-d H:i:s'),
                'intadd'    => $this->session->intid,
                'dtadd'     => date('Y-m-d H:i:s'),
                'intupdate' => $this->session->intid,
                'dtupdate'  => date('Y-m-d H:i:s'),
                'intstatus' => 1
            );
            $result      = $this->modelapp->insertdata('pr_rusak',$datarusak);
        }
        if ($result) {
            $data = array(
                'intkondisi' => $intkondisi,
                'intuser'    => $this->session->intid
            );
            $this->modelapp->updatedata('pr_kembali_detail',$data,$intid);
            redirect(base_url($this->controller . '/edit/' . $intkembali));
        }
    }
}
