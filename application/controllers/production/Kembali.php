<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kembali extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        
        $this->load->model('Production/KembaliModel');
        $this->model = $this->KembaliModel;

        $this->load->model('AppModel');
        $this->modelapp = $this->AppModel;

        if (!$this->session->intid && $this->session->intid != 'pms_prod') {
            redirect(base_url('akses/login'));
        }
    }

    function index(){
        redirect(base_url('production/kembali' . '/view'));
    }

    function view($halaman=1){
        $intgedung   = $this->session->intgedung;
        $datagedung  = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $intcell     = ($this->input->get('intcell') == '') ? 0 : $this->input->get('intcell');
        $intkaryawan = ($this->input->get('intkaryawan') == '') ? 0 : $this->input->get('intkaryawan');
        $from        = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to          = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $keyword     = $this->input->get('key');
        $date1       = date( "Y-m-d 07:00:00", strtotime( $from) );
        $date2       = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
        $jmldata     = $this->model->getjmldata('pr_kembali', $date1, $date2, $intgedung, $intcell, $intkaryawan);
        $offset      = ($halaman - 1) * 10;
        $jmlpage     = ceil($jmldata[0]->jmldata / 10);

        $data['title']      = $datagedung[0]->vcnama . ' - Return';
        $data['controller'] = 'production/kembali';
        $data['from']         = $from;
        $data['to']           = $to;
        $data['intgedung']    = $intgedung;
        $data['intcell']      = $intcell;
        $data['intkaryawan']  = $intkaryawan;
        $data['from_input']   = ($this->input->get('from')) ? date('m/d/Y', strtotime($from)) : '';
        $data['to_input']     = ($this->input->get('to')) ? date('m/d/Y', strtotime($to)) : '';
        $data['keyword']    = $keyword;
        $data['listcell']     = $this->modelapp->getdatalistall('m_cell', $intgedung, 'intgedung');
        $data['listkaryawan'] = $this->modelapp->getdatalistall('m_karyawan', $intgedung, 'intgedung');
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $this->model->getdatalimit('pr_kembali',$offset,10, $date1, $date2, $intgedung, $intcell, $intkaryawan);
        
        $this->template->set_layout('default_prod')->build('production/kembali_view' . '/index',$data);
    }

    function detail($intid){
        $data['controller'] = 'production/kembali';
        $data['dataMain']   = $this->model->getdatadetail('pr_kembali',$intid);
        $data['datapallet'] = $this->model->getdatakembalipallet($intid);
        $this->load->view('production/kembali_view' . '/detail',$data);
    }

    function add(){
        $intgedung  = $this->session->intgedung;
        $datagedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $datenow  = date('Ymd-His');
        $kodeunik = $this->KembaliModel->buat_kode($datenow);
        $data = array(
            'intid'     => 0,
            'vckode'    => $kodeunik,
            'vcgedung'  => $datagedung[0]->vcnama,
            'intgedung' => $intgedung,
            'intadd'    => $this->session->intid,
            'dtadd'     => date('Y-m-d H:i:s'),
            'intupdate' => $this->session->intid,
            'dtupdate'  => date('Y-m-d H:i:s'),
            'intstatus' => 0
        );

        $data['title']        = ' Return';
        $data['action']       = 'Add';
        $data['controller']   = 'production/kembali';
        $data['listcell']     = $this->modelapp->getdatalistall('m_cell', $intgedung, 'intgedung');
        $data['listkaryawan'] = $this->modelapp->getdatalistall('m_karyawan', $intgedung, 'intgedung');

        $this->template->set_layout('default_prod')->build('production/kembali_view' . '/form_add',$data);
    }

    function edit($intid){
        $resultData = $this->model->getdatadetail('pr_kembali',$intid);
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

        $data['title']      = $datagedung[0]->vcnama . ' - Return';
        $data['action']     = 'Edit';
        $data['controller'] = 'production/kembali';
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listmodel']  = $this->modelapp->getdatalist('m_models');
        $data['listproses'] = $this->modelapp->getdatalist('m_proses');
        $data['listsize']   = $this->modelapp->getdatalist('m_size');

        $this->template->set_layout('default_prod')->build('production/kembali_view' . '/form',$data);
    }

    function validasiform($tipe){
        $array = array();
        $data = $this->input->post();
        if ($tipe == 'data') {
            foreach ($data as $key => $value) {
                $result = $this->modelapp->getdatadetailcustom('pr_kembali',$value,$key);
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
            $result = $this->modelapp->insertdata('pr_kembali',$data);
            if ($result) {
                redirect(base_url('production/kembali' . '/view'));
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
            $result = $this->modelapp->updatedata('pr_kembali',$data,$intid);
            if ($result) {
                redirect(base_url('production/kembali' . '/view'));
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
            $result = $this->modelapp->updatedata('pr_kembali',$data,$intid);
            if ($result) {
                redirect(base_url('production/kembali' . '/view'));
            }
        }
    }

    function simpan_kembali() {
        $vckode      = $this->input->post('vckode');
        $intgedung   = $this->input->post('intgedung');
        $intcell     = $this->input->post('intcell');
        $intkaryawan = $this->input->post('intkaryawan');
        $data         = array(
                        'vckode'      => $vckode,
                        'intgedung'   => $intgedung,
                        'intcell'     => $intcell,
                        'intkaryawan' => $intkaryawan,
                        'dtkembali'   => date('Y-m-d H:i:s'),
                        'intuser'     => $this->session->intid,
                        'intadd'      => $this->session->intid,
                        'dtadd'       => date('Y-m-d H:i:s'),
                        'intupdate'   => $this->session->intid,
                        'dtupdate'    => date('Y-m-d H:i:s'),
                        'intstatus'   => 1
                    );
        $result      = $this->modelapp->insertdata('pr_kembali',$data);
        if ($result) {
            $datakembali = $this->model->getdatakembali($vckode);
            redirect(base_url('production/kembali/transaksi/' . $datakembali[0]->intid));
        }
    }

    function transaksi($intkembali) {
        $intgedung   = $this->session->intgedung;
        $datagedung  = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $datakembali = $this->model->getdatakembali2($intkembali);
        $data = array(
                    'intid'         => $datakembali[0]->intid,
                    'vckodekembali' => $datakembali[0]->vckode,
                    'vcgedung'      => $datakembali[0]->vcgedung,
                    'vccell'        => $datakembali[0]->vccell,
                    'vckaryawan'    => $datakembali[0]->vckaryawan,
                    'vckode'        => ''
                );
        $data['title']      = $datagedung[0]->vcnama . ' - Return';
        $data['action']     = 'Add';
        $data['controller'] = 'production/kembali';
        $data['listroom']   = $this->model->getdataroom($intgedung);
        if ($datakembali) {
            $this->template->set_layout('default_prod')->build('production/kembali_view' . '/form',$data);
        }
    }

    function getdatadefault_ajax($intkembali){
        $data['datakembali'] = $this->model->getdatakembalipallet($intkembali);
        echo json_encode($data);
    }

    function simpan_kembali_scan($vckode, $intkembali, $introom){
        $datadetail = $this->model->getdatadetailpallet($vckode, $intkembali);
        $datapallet = $this->modelapp->getdatadetailcustom('m_pallet', $vckode, 'vckode');
        $datapinjam = $this->model->getdatapinjampallet($vckode);
        if (count($datadetail) >= 1 || count($datapallet) == 0 || count($datapinjam) == 0) {
            $data['intstatus'] = 2;
            echo json_encode($data);
        } else {
            $intpallet = $datapallet[0]->intid;
            $intstatus = $datapallet[0]->intstatus;
            if ($intstatus == 1 || $intstatus == 3 || $intstatus == 4) {
                $data['intstatus'] = 2;
                echo json_encode($data);
            }
            else if ($intstatus == 2) {
                //insert to data kembali detail
                $data2 = array(
                    'intkembali' => $intkembali,
                    'intpinjam'  => $datapinjam[0]->intpinjam,
                    'introom'    => $introom,
                    'intpallet'  => $intpallet,
                    'intkondisi' => 1
                );
                $result = $this->modelapp->insertdata('pr_kembali_detail',$data2);
                if ($result) {
                    $data['datakembali'] = $this->model->getdatakembalipallet($intkembali);
                    echo json_encode($data);
                }
            }
        }
    }

    function hapusdata_ajax($intid, $intkembali){
        $this->modelapp->deletedata('pr_kembali_detail',$intid,'intid');
        $data['datakembali']  = $this->model->getdatakembalipallet($intkembali);
        echo json_encode($data);
    }

    function ubahKondisi($intid, $intkembali){
        $datadetail = $this->modelapp->getdatadetailcustom('pr_kembali_detail', $intid, 'intid');
        $data['controller'] = 'production/kembali';
        $data['intid']       = $intid;
        $data['intkembali']  = $intkembali;
        $data['intkondisi']  = $datadetail[0]->intkondisi;
        
        $this->load->view('production/kembali_view/ganti_kondisi',$data);
    }

    function ubahkondisi_ajax($intkondisi, $intid, $intkembali){
        $data = array(
            'intkondisi' => $intkondisi
        );
        $result = $this->modelapp->updatedata('pr_kembali_detail',$data,$intid);
        if ($result) {
            redirect(base_url('production/kembali/transaksi/' . $intkembali));
        }
    }

    function batalSimpan($intid){
        $data      = $this->modelapp->getdatadetailcustom('pr_kembali_detail',$intid,'intkembali');
        if (count($data) === 0) {
            $this->modelapp->deletedata('pr_kembali',$intid,'intid');
            redirect(base_url('production/kembali/view'));
         } else {
            $this->modelapp->deletedata('pr_kembali',$intid,'intid');
            $this->modelapp->deletedata('pr_kembali_detail',$intid,'intkembali');
            redirect(base_url('production/kembali/view'));
        }
    }

    function simpanPallet($intid){
        $data       = $this->modelapp->getdatadetailcustom('pr_kembali_detail',$intid,'intkembali');
        foreach ($data as $dataset) {
            $dataroom   = $this->modelapp->getdatadetailcustom('m_room', $dataset->introom, 'intid');
            if ($dataset->intkondisi == 1) {
                $datapinjam = array(
                    'intstatus' => 1
                );
                $this->model->updatedata('pr_pinjam_detail',$datapinjam,$dataset->intpinjam, $dataset->intpallet);
                $dataupdate = array(
                    'intlokasi' => 0,
                    'introom'   => $dataset->introom,
                    'vclokasi'  => $dataroom[0]->vcnama,
                    'intstatus' => 1
                );
                $result = $this->modelapp->updatedata('m_pallet',$dataupdate,$dataset->intpallet);
            } elseif ($dataset->intkondisi == 2) {
                $datapinjam = array(
                    'intstatus' => 3
                );
                $this->model->updatedata('pr_pinjam_detail',$datapinjam,$dataset->intpinjam, $dataset->intpallet);
                $dataperbaikan = array(
                    'intpallet'   => $dataset->intpallet,
                    'intkondisi'  => $dataset->intkondisi,
                    'intuser'     => $this->session->intid,
                    'dtperbaikan' => date('Y-m-d H:i:s'),
                    'intadd'      => $this->session->intid,
                    'dtadd'       => date('Y-m-d H:i:s'),
                    'intupdate'   => $this->session->intid,
                    'dtupdate'    => date('Y-m-d H:i:s'),
                    'intstatus'   => 1
                );
                $this->modelapp->insertdata('pr_perbaikan',$dataperbaikan);
                $dataupdate = array(
                    'intlokasi' => 0,
                    'introom'   => 0,
                    'vclokasi'  => 'Repairing',
                    'intstatus' => 3
                );
                $result = $this->modelapp->updatedata('m_pallet',$dataupdate,$dataset->intpallet);
            } elseif ($dataset->intkondisi == 3) {
                $datapinjam = array(
                    'intstatus' => 4
                );
                $this->model->updatedata('pr_pinjam_detail',$datapinjam,$dataset->intpinjam, $dataset->intpallet);
                $datarusak = array(
                    'intpallet' => $dataset->intpallet,
                    'intuser'   => $this->session->intid,
                    'dtrusak'   => date('Y-m-d H:i:s'),
                    'intadd'    => $this->session->intid,
                    'dtadd'     => date('Y-m-d H:i:s'),
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s'),
                    'intstatus' => 1
                );
                $this->modelapp->insertdata('pr_rusak',$datarusak);
                $dataupdate = array(
                    'intlokasi' => 0,
                    'introom'   => 0,
                    'vclokasi'  => 'Destroy',
                    'intstatus' => 4
                );
                $result = $this->modelapp->updatedata('m_pallet',$dataupdate,$dataset->intpallet);
            }
        }
        if ($result) {
            redirect(base_url('production/kembali/view'));
         }
    }

    function get_cell_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_cell',$intid,'intgedung');
        echo json_encode($data);
    }

    function get_karyawan_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_karyawan',$intid,'intgedung');
        echo json_encode($data);
    }

    function getpinjam_ajax($intpinjam){
        $data['datapinjam']  = $this->model->getcaripallet($intpinjam);
        echo json_encode($data);
    }

    function get_palletpinjam_ajax($intpinjam){
        $data  = $this->model->getcaripallet($intpinjam);
        echo json_encode($data);
    }

    function add_detailkembali($intpallet, $intkondisi, $intpinjam, $intkembali){
        //insert to detail kembali
        $datadetail = array(
            'intkembali' => $intkembali,
            'intpallet'  => $intpallet,
            'intkondisi' => $intkondisi
        );
        $this->modelapp->insertdata('pr_kembali_detail',$datadetail);
        
        //update data pinjam (qty pinjam dikurangi 1)
        $datapinjam     = $this->model->getdatapinjampallet($intpinjam, $intpallet);
        $inttotalbefore = $datapinjam[0]->inttotal;
        $inttotalafter  = $inttotalbefore - 1;
        $dataupdatepinjam = array(
            'inttotal' => $inttotalafter
        );
        $this->model->updatedatapinjam($intpinjam, $intpallet, $dataupdatepinjam);

        if ($intkondisi == 1) {
            $datapallet = $this->modelapp->getdatadetail('m_pallet',$intpallet);
            $intqty     = $datapallet[0]->intqty;
            $inttotal   = $intqty + 1;
            $datatambah = array(
                'intqty' => $inttotal
            );
            $result = $this->model->updatedatapallet($intpallet, $datatambah);

        } else if ($intkondisi == 2) {
            $dataperbaikan = array(
                'intpallet'  => $intpallet,
                'intkondisi' => $intkondisi,
                'intadd'     => $this->session->intid,
                'dtadd'      => date('Y-m-d H:i:s'),
                'intupdate'  => $this->session->intid,
                'dtupdate'   => date('Y-m-d H:i:s'),
                'intstatus'  => 1
            );
            $result      = $this->modelapp->insertdata('pr_perbaikan',$dataperbaikan);
        } else if ($intkondisi == 3) {
            $datarusak = array(
                'intpallet'  => $intpallet,
                'intadd'     => $this->session->intid,
                'dtadd'      => date('Y-m-d H:i:s'),
                'intupdate'  => $this->session->intid,
                'dtupdate'   => date('Y-m-d H:i:s'),
                'intstatus'  => 1
            );
            $result      = $this->modelapp->insertdata('pr_rusak',$datarusak);
        }

        if ($result) {
            $data['datapinjam']  = $this->model->getcaripallet($intpinjam);
            $data['datakembali'] = $this->model->getdatakembalipallet($intkembali);
            echo json_encode($data);
        }
    }

    function exportexcel(){
        $intgedung   = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intcell     = ($this->input->get('intcell') == '') ? 0 : $this->input->get('intcell');
        $intkaryawan = ($this->input->get('intkaryawan') == '') ? 0 : $this->input->get('intkaryawan');
        $from        = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to          = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $datacell    = $this->model->getdatacell($intgedung, $intcell);
        $judul       = '';

        if ($intgedung > 0) {
            $dtgedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
            $judul    = $dtgedung[0]->vcnama;
        }
        
        if ($intcell > 0) {
            $dtcell = $this->modelapp->getdatadetailcustom('m_cell',$intcell,'intid');
            $judul  = $dtcell[0]->vckode . ' - ' . $dtcell[0]->vcnama;
        }
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report Return " . $judul)
                     ->setSubject("Report Return")
                     ->setDescription("Report Return")
                     ->setKeywords("Report Return");

        // variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
                'font'       => array('bold' => true), // Set font nya jadi bold
                'alignment'  => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
                ),
                'borders' => array(
                'top'     => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom'  => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left'    => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
                )
        );

        // variabel untuk menampung pengaturan style dari isi tabel
        $style_row  = array(
            'alignment' => array(
                'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top'     => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom'  => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left'    => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $loop = 0;
        foreach ($datacell as $cell) {
            if ($loop > 0) {
                $excel->createSheet();
            }

            $excel->setActiveSheetIndex($loop)->setCellValue('B1', "Report Return " . $cell->vckode . ' - ' . $cell->vcnama);
            $excel->getActiveSheet()->mergeCells('B1:M1'); // Set Merge Cell
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
            $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

            $excel->setActiveSheetIndex($loop)->setCellValue('B2', "Report Return, on Date : ". date('d-m-Y',strtotime($from)) . " To ". date('d-m-Y',strtotime($to)));
            $excel->getActiveSheet()->mergeCells('B2:M2'); // Set Merge Cell
            $excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(TRUE); // Set bold
            $excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(12); // Set font size 15
            $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center

            $excel->setActiveSheetIndex($loop)->setCellValue('B3', "NO");
            $excel->setActiveSheetIndex($loop)->setCellValue('C3', "Date");
            $excel->setActiveSheetIndex($loop)->setCellValue('D3', "Pallet Code");
            $excel->setActiveSheetIndex($loop)->setCellValue('E3', "Model");
            $excel->setActiveSheetIndex($loop)->setCellValue('F3', "Process");
            $excel->setActiveSheetIndex($loop)->setCellValue('G3', "Size");
            $excel->setActiveSheetIndex($loop)->setCellValue('H3', "Side");
            $excel->setActiveSheetIndex($loop)->setCellValue('I3', "Building");
            $excel->setActiveSheetIndex($loop)->setCellValue('J3', "Cell");
            $excel->setActiveSheetIndex($loop)->setCellValue('K3', "Returner");
            $excel->setActiveSheetIndex($loop)->setCellValue('L3', "Condition");
            $excel->setActiveSheetIndex($loop)->setCellValue('M3', "Admin");

            $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('M3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->setActiveSheetIndex($loop)->getStyle('B3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('C3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('D3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('E3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('F3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('G3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('H3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('I3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('J3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('K3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('L3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('M3')->applyFromArray($style_col);

            $date1 = date( "Y-m-d 07:00:00", strtotime( $from) );
            $date2 = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
            $data   = $this->model->getdata($cell->intid,$date1,$date2);

            $numrow = 4;
            $no = 0;
            foreach ($data as $dataset) {
                $excel->setActiveSheetIndex($loop)->setCellValue('B'.$numrow, ++$no);
                $excel->setActiveSheetIndex($loop)->setCellValue('C'.$numrow, date('d M Y', strtotime($dataset->dtkembali)));
                $excel->setActiveSheetIndex($loop)->setCellValue('D'.$numrow, $dataset->vckode);
                $excel->setActiveSheetIndex($loop)->setCellValue('E'.$numrow, $dataset->vcmodel);
                $excel->setActiveSheetIndex($loop)->setCellValue('F'.$numrow, $dataset->vcproses);
                $excel->setActiveSheetIndex($loop)->setCellValue('G'.$numrow, $dataset->vcsize);
                $excel->setActiveSheetIndex($loop)->setCellValue('H'.$numrow, $dataset->vcside);
                $excel->setActiveSheetIndex($loop)->setCellValue('I'.$numrow, $dataset->vcgedung);
                $excel->setActiveSheetIndex($loop)->setCellValue('J'.$numrow, $dataset->vccell);
                $excel->setActiveSheetIndex($loop)->setCellValue('K'.$numrow, $dataset->vckaryawan);
                $excel->setActiveSheetIndex($loop)->setCellValue('L'.$numrow, $dataset->vckondisi);
                $excel->setActiveSheetIndex($loop)->setCellValue('M'.$numrow, $dataset->vcuser);
     
                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);

                $numrow++;
            }

            // Set width kolom
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth('5');
            $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $excel->getActiveSheet($loop)->getDefaultRowDimension()->setRowHeight(-1);

            // Set orientasi kertas jadi LANDSCAPE
            $excel->getActiveSheet($loop)->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

            // Set judul file excel nya
            $excel->getActiveSheet($loop)->setTitle($cell->vckode . '-' . $cell->vcnama);
            $loop++;
        }

        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Return ' .$judul. '.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
