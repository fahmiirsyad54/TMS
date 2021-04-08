<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perbaikan extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('production/PerbaikanModel');
        $this->model = $this->PerbaikanModel;

        $this->load->model('AppModel');
        $this->modelapp = $this->AppModel;

        if (!$this->session->intid && $this->session->intid != 'pms_prod') {
            redirect(base_url('akses/login'));
        }
    }

    function index(){
        redirect(base_url('production/perbaikan' . '/view'));
    }

    function view($halaman=1){
        $intgedung  = $this->session->intgedung;
        $datagedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $from       = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to         = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $keyword    = $this->input->get('key');
        $date1      = date( "Y-m-d 07:00:00", strtotime( $from) );
        $date2      = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
        $intmodel   = ($this->input->get('intmodel') == '') ? 0 : $this->input->get('intmodel');
        $intproses  = ($this->input->get('intproses') == '') ? 0 : $this->input->get('intproses');
        $keyword    = $this->input->get('key');
        $jmldata    = $this->model->getjmldata('pr_perbaikan', $date1, $date2, $intgedung, $intmodel, $intproses);
        $offset     = ($halaman - 1) * 10;
        $jmlpage    = ceil($jmldata[0]->jmldata / 10);

        $data['title']      = $datagedung[0]->vcnama . ' - Repair';
        $data['controller'] = 'production/perbaikan';
        $data['from']       = $from;
        $data['to']         = $to;
        $data['from_input'] = ($this->input->get('from')) ? date('m/d/Y', strtotime($from)) : '';
        $data['to_input']   = ($this->input->get('to')) ? date('m/d/Y', strtotime($to)) : '';
        $data['intgedung']  = $intgedung;
        $data['intmodel']   = $intmodel;
        $data['intproses']  = $intproses;
        $data['keyword']    = $keyword;
        $data['listmodel']  = $this->modelapp->getdatalistall('m_models');
        $data['listproses'] = $this->modelapp->getdatalistall('m_proses');
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $this->model->getdatalimit('pr_perbaikan',$offset,10, $date1, $date2, $intgedung, $intmodel, $intproses);
        
        $this->template->set_layout('default_prod')->build('production/perbaikan_view' . '/index',$data);
    }

    function detail($intid){
        $data['controller']  = 'production/perbaikan';
        $data['dataMain']    = $this->model->getdatadetail('pr_perbaikan',$intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory($this->tablehistory,$intid);
        $this->load->view('production/perbaikan_view' . '/detail',$data);
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

        $data['title']      = $datagedung[0]->vcnama . ' - Repair';
        $data['action']     = 'Add';
        $data['controller'] = 'production/perbaikan';
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listmodel']  = $this->modelapp->getdatalist('m_models');
        //$data['listproses'] = $this->modelapp->getdatalist('m_proses');
        $data['listsize']   = $this->modelapp->getdatalist('m_size');

        $this->template->set_layout('default_prod')->build('production/perbaikan_view' . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->model->getdatadetail('pr_perbaikan',$intid);
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

        $data['title']      = $datagedung[0]->vcnama . ' - Repair';
        $data['action']     = 'Edit';
        $data['controller'] = 'production/perbaikan';
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listmodel']  = $this->modelapp->getdatalist('m_models');
        $data['listproses'] = $this->modelapp->getdatalist('m_proses');
        $data['listsize']   = $this->modelapp->getdatalist('m_size');

        $this->template->set_layout('default_prod')->build('production/perbaikan_view' . '/form',$data);
    }

    function validasiform($tipe){
        $array = array();
        $data = $this->input->post();
        if ($tipe == 'data') {
            foreach ($data as $key => $value) {
                $result = $this->modelapp->getdatadetailcustom('pr_perbaikan',$value,$key);
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
            $result = $this->modelapp->insertdata('pr_perbaikan',$data);
            if ($result) {
                redirect(base_url('production/perbaikan' . '/view'));
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
            $result = $this->modelapp->updatedata('pr_perbaikan',$data,$intid);
            if ($result) {
                redirect(base_url('production/perbaikan' . '/view'));
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
            $result = $this->modelapp->updatedata('pr_perbaikan',$data,$intid);
            if ($result) {
                redirect(base_url('production/perbaikan' . '/view'));
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

    function ubahkondisi($intid){
        $datadetail = $this->modelapp->getdatadetailcustom('pr_perbaikan',$intid,'intid');
        $datapallet = $this->modelapp->getdatadetailcustom('m_pallet',$datadetail[0]->intpallet,'intid');
        $data['controller'] = 'production/perbaikan';
        $data['intid']      = $intid;
        $data['intpallet']  = $datadetail[0]->intpallet;
        $data['intkondisi'] = $datadetail[0]->intkondisi;
        $data['listroom']   = $this->model->getroom($datapallet[0]->intgedung);

        $this->load->view('production/perbaikan_view' . '/ubahkondisi',$data);
    }

    function kondisiubah($intpallet, $intid){
        $datarusak         = array(
            'intpallet' => $intpallet,
            'intuser'   => $this->session->intid,
            'dtrusak'   => date('Y-m-d H:i:s'),
            'intadd'    => $this->session->intid,
            'dtadd'     => date('Y-m-d H:i:s'),
            'intupdate' => $this->session->intid,
            'dtupdate'  => date('Y-m-d H:i:s'),
            'intstatus' => 1
        );
        $result = $this->modelapp->insertdata('pr_rusak',$datarusak);
        $data = array(
            'intkondisi'  => 3,
            'intuser'     => $this->session->intid,
            'dtperbaikan' => date('Y-m-d H:i:s'),
            'intupdate'   => $this->session->intid,
            'dtupdate'    => date('Y-m-d H:i:s')
            );
        $result = $this->modelapp->updatedata('pr_perbaikan',$data,$intid);
        $dataupdate = array(
            'intlokasi' => 0,
            'introom'   => 0,
            'vclokasi'  => 'Destroy',
            'intstatus' => 4
        );
        $result = $this->modelapp->updatedata('m_pallet',$dataupdate,$intpallet);
        if ($result) {
            redirect(base_url('production/perbaikan/view'));
        }
    }

    function simpanroom($intperbaikan, $intpallet, $introom){
        $dataroom = $this->modelapp->getdatadetailcustom('m_room',$introom,'intid');
        $updatepallet = array(
            'intlokasi' => 0,
            'introom'   => $introom,
            'vclokasi'  => $dataroom[0]->vcnama,
            'intstatus' => 1,
            'intupdate' => $this->session->intid,
            'dtupdate'  => date('Y-m-d H:i:s')
            );
        $this->modelapp->updatedata('m_pallet',$updatepallet,$intpallet);

        $data = array(
            'intkondisi'  => 1,
            'intuser'     => $this->session->intid,
            'dtperbaikan' => date('Y-m-d H:i:s'),
            'intupdate'   => $this->session->intid,
            'dtupdate'    => date('Y-m-d H:i:s')
            );
        $result = $this->modelapp->updatedata('pr_perbaikan',$data,$intperbaikan);
        if ($result) {
            redirect(base_url($this->controller . 'production/perbaikan/view'));
        }
    }
    function exportexcel(){
        $intgedung  = $this->session->intgedung;
        $datagedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $intmodel   = ($this->input->get('intmodel') == '') ? 0 : $this->input->get('intmodel');
        $intproses  = ($this->input->get('intproses') == '') ? 0 : $this->input->get('intproses');
        $from       = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to         = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $judul      = $datagedung[0]->vcnama;
        
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report Repair " . $judul)
                     ->setSubject("Report Repair")
                     ->setDescription("Report Repair")
                     ->setKeywords("Report Repair");

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
        foreach ($datagedung as $gedung) {
            if ($loop > 0) {
                $excel->createSheet();
            }

            $excel->setActiveSheetIndex($loop)->setCellValue('B1', "Report Repair " . $gedung->vcnama);
            $excel->getActiveSheet()->mergeCells('B1:K1'); // Set Merge Cell
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
            $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

            $excel->setActiveSheetIndex($loop)->setCellValue('B2', "Report Repair, on Date : ". date('d-m-Y',strtotime($from)) . " To ". date('d-m-Y',strtotime($to)));
            $excel->getActiveSheet()->mergeCells('B2:K2'); // Set Merge Cell
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
            $excel->setActiveSheetIndex($loop)->setCellValue('J3', "Condition");
            $excel->setActiveSheetIndex($loop)->setCellValue('K3', "Admin");

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

            $date1 = date( "Y-m-d 07:00:00", strtotime( $from) );
            $date2 = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
            $data   = $this->model->getdata($date1,$date2, $gedung->intid, $intmodel, $intproses);

            $numrow = 4;
            $no = 0;
            foreach ($data as $dataset) {
                $excel->setActiveSheetIndex($loop)->setCellValue('B'.$numrow, ++$no);
                $excel->setActiveSheetIndex($loop)->setCellValue('C'.$numrow, date('d M Y', strtotime($dataset->dtperbaikan)));
                $excel->setActiveSheetIndex($loop)->setCellValue('D'.$numrow, $dataset->vckode);
                $excel->setActiveSheetIndex($loop)->setCellValue('E'.$numrow, $dataset->vcmodel);
                $excel->setActiveSheetIndex($loop)->setCellValue('F'.$numrow, $dataset->vcproses);
                $excel->setActiveSheetIndex($loop)->setCellValue('G'.$numrow, $dataset->vcsize);
                $excel->setActiveSheetIndex($loop)->setCellValue('H'.$numrow, $dataset->vcside);
                $excel->setActiveSheetIndex($loop)->setCellValue('I'.$numrow, $dataset->vcgedung);
                $excel->setActiveSheetIndex($loop)->setCellValue('J'.$numrow, $dataset->vckondisi);
                $excel->setActiveSheetIndex($loop)->setCellValue('K'.$numrow, $dataset->vcuser);
     
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

            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $excel->getActiveSheet($loop)->getDefaultRowDimension()->setRowHeight(-1);

            // Set orientasi kertas jadi LANDSCAPE
            $excel->getActiveSheet($loop)->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

            // Set judul file excel nya
            $excel->getActiveSheet($loop)->setTitle( $gedung->vcnama);
            $loop++;
        }

        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Repair ' .$judul. '.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
