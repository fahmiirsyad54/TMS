<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rusak extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('RusakModel');
        $this->model = $this->RusakModel;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $intgedung = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intmodel  = ($this->input->get('intmodel') == '') ? 0 : $this->input->get('intmodel');
        $intproses = ($this->input->get('intproses') == '') ? 0 : $this->input->get('intproses');
        $from      = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to        = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $keyword   = $this->input->get('key');
        $date1     = date( "Y-m-d 07:00:00", strtotime( $from) );
        $date2     = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
        $jmldata   = $this->model->getjmldata($this->table, $date1, $date2, $intgedung, $intmodel, $intproses);
        $offset    = ($halaman - 1) * $this->limit;
        $jmlpage   = ceil($jmldata[0]->jmldata / $this->limit);

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['from']         = $from;
        $data['to']           = $to;
        $data['from_input']   = ($this->input->get('from')) ? date('m/d/Y', strtotime($from)) : '';
        $data['to_input']     = ($this->input->get('to')) ? date('m/d/Y', strtotime($to)) : '';
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
        $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit, $date1, $date2, $intgedung, $intmodel, $intproses);
        
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

    function exportexcel(){
        $intgedung  = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intmodel   = ($this->input->get('intmodel') == '') ? 0 : $this->input->get('intmodel');
        $intproses  = ($this->input->get('intproses') == '') ? 0 : $this->input->get('intproses');
        $from       = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to         = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $datagedung = $this->model->getdatagedung($intgedung);
        $judul      = 'All Building';

        if ($intgedung > 0) {
            $dtgedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
            $judul    = $dtgedung[0]->vcnama;
        }
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report Broken Pallet " . $judul)
                     ->setSubject("Report Broken Pallet")
                     ->setDescription("Report Broken Pallet")
                     ->setKeywords("Report Broken Pallet");

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

            $excel->setActiveSheetIndex($loop)->setCellValue('B1', "Report Broken Pallet " . $gedung->vcnama);
            $excel->getActiveSheet()->mergeCells('B1:K1'); // Set Merge Cell
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
            $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

            $excel->setActiveSheetIndex($loop)->setCellValue('B2', "Report Broken Pallet, on Date : ". date('d-m-Y',strtotime($from)) . " To ". date('d-m-Y',strtotime($to)));
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
            $excel->setActiveSheetIndex($loop)->setCellValue('J3', "Admin");

            $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->setActiveSheetIndex($loop)->getStyle('B3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('C3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('D3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('E3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('F3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('G3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('H3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('I3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('J3')->applyFromArray($style_col);

            $date1 = date( "Y-m-d 07:00:00", strtotime( $from) );
            $date2 = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
            $data   = $this->model->getdata($date1,$date2, $gedung->intid, $intmodel, $intproses);

            $numrow = 4;
            $no = 0;
            foreach ($data as $dataset) {
                $excel->setActiveSheetIndex($loop)->setCellValue('B'.$numrow, ++$no);
                $excel->setActiveSheetIndex($loop)->setCellValue('C'.$numrow, date('d M Y', strtotime($dataset->dtrusak)));
                $excel->setActiveSheetIndex($loop)->setCellValue('D'.$numrow, $dataset->vckode);
                $excel->setActiveSheetIndex($loop)->setCellValue('E'.$numrow, $dataset->vcmodel);
                $excel->setActiveSheetIndex($loop)->setCellValue('F'.$numrow, $dataset->vcproses);
                $excel->setActiveSheetIndex($loop)->setCellValue('G'.$numrow, $dataset->vcsize);
                $excel->setActiveSheetIndex($loop)->setCellValue('H'.$numrow, $dataset->vcside);
                $excel->setActiveSheetIndex($loop)->setCellValue('I'.$numrow, $dataset->vcgedung);
                $excel->setActiveSheetIndex($loop)->setCellValue('J'.$numrow, $dataset->vcuser);
     
                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);

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
        header('Content-Disposition: attachment; filename="Report Broken Pallet ' .$judul. '.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
