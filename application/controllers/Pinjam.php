<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjam extends MY_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('PinjamModel');
        $this->model = $this->PinjamModel;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $intgedung   = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intcell     = ($this->input->get('intcell') == '') ? 0 : $this->input->get('intcell');
        $intkaryawan = ($this->input->get('intkaryawan') == '') ? 0 : $this->input->get('intkaryawan');
        $from        = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to          = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $keyword     = $this->input->get('key');
        $date1       = date( "Y-m-d 07:00:00", strtotime( $from) );
        $date2       = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
        $jmldata     = $this->model->getjmldata($this->table, $date1, $date2, $intgedung, $intcell, $intkaryawan);
        $offset      = ($halaman - 1) * $this->limit;
        $jmlpage     = ceil($jmldata[0]->jmldata / $this->limit);

        $data['title']        = $this->title;
        $data['controller']   = $this->controller;
        $data['from']         = $from;
        $data['to']           = $to;
        $data['intgedung']    = $intgedung;
        $data['intcell']      = $intcell;
        $data['intkaryawan']  = $intkaryawan;
        $data['from_input']   = ($this->input->get('from')) ? date('m/d/Y', strtotime($from)) : '';
        $data['to_input']     = ($this->input->get('to')) ? date('m/d/Y', strtotime($to)) : '';
        $data['listgedung']   = $this->modelapp->getdatalistall('m_gedung');
        $data['listcell']     = $this->modelapp->getdatalistall('m_cell');
        $data['listkaryawan'] = $this->modelapp->getdatalistall('m_karyawan');
        $data['keyword']      = $keyword;
        $data['halaman']      = $halaman;
        $data['jmlpage']      = $jmlpage;
        $data['firstnum']     = $offset;
        $data['dataP']        = $this->model->getdatalimit($this->table,$offset,$this->limit, $date1, $date2, $intgedung, $intcell, $intkaryawan);
        
        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function detail($intid){
        $data['controller'] = $this->controller;
        $data['dataMain']   = $this->model->getdatadetail($this->table,$intid);
        $data['datapallet'] = $this->model->getdatapinjampallet($intid);
        $this->load->view($this->view . '/detail',$data);
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

    function add(){
        $datenow  = date('Ymd-His');
        $kodeunik = $this->PinjamModel->buat_kode($datenow);
        $data = array(
            'intid'        => 0,
            'vckode'       => $kodeunik,
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
        
        $this->template->set_layout('default')->build($this->view . '/form_add',$data);
    }

    function get_cell_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_cell',$intid,'intgedung');
        echo json_encode($data);
    }

    function get_karyawan_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_karyawan',$intid,'intgedung');
        echo json_encode($data);
    }

    function simpan_peminjam() {
        $vckode      = $this->input->post('vckode');
        $intgedung   = $this->input->post('intgedung');
        $intcell     = $this->input->post('intcell');
        $intkaryawan = $this->input->post('intkaryawan');
        $data   = array(
                'vckode'      => $vckode,
                'intgedung'   => $intgedung,
                'intcell'     => $intcell,
                'intkaryawan' => $intkaryawan,
                'dtpinjam'    => date('Y-m-d H:i:s'),
                'intuser'     => $this->session->intid,
                'intadd'      => $this->session->intid,
                'dtadd'       => date('Y-m-d H:i:s'),
                'intupdate'   => $this->session->intid,
                'dtupdate'    => date('Y-m-d H:i:s'),
                'intstatus'   => 1
            );
        $result     = $this->modelapp->insertdata($this->table,$data);
        
        if ($result) {
            $datapinjam  = $this->model->getdatapinjam($vckode);
            // $datenow     = date('Ymd-His');
            // $kodekembali = $this->model->buat_kode_kembali($datenow);
            // $data2   = array(
            //         'vckode'      => $kodekembali,
            //         'intpinjam'   => $datapinjam[0]->intid,
            //         'intadd'      => $this->session->intid,
            //         'dtadd'       => date('Y-m-d H:i:s'),
            //         'intupdate'   => $this->session->intid,
            //         'dtupdate'    => date('Y-m-d H:i:s'),
            //         'intstatus'   => 1
            //     );
            // $this->modelapp->insertdata('pr_kembali',$data2);
            redirect(base_url('pinjam/transaksi/' . $datapinjam[0]->intid));
        }
    }
    
    function transaksi($intpinjam) {
        $datapinjam = $this->model->getdatapinjam2($intpinjam);
        $data = array(
                    'intid'        => $datapinjam[0]->intid,
                    'vckodepinjam' => $datapinjam[0]->vckode,
                    'vcgedung'     => $datapinjam[0]->vcgedung,
                    'vccell'       => $datapinjam[0]->vccell,
                    'vckaryawan'   => $datapinjam[0]->vckaryawan,
                    'vckode'       => ''

                );
        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        if ($datapinjam) {
            $this->template->set_layout('default')->build($this->view . '/form',$data);
        }
    }

    function getdatadefault_ajax($intpinjam){
        $data['datapinjam'] = $this->model->getdatapinjampallet($intpinjam);
        echo json_encode($data);
    }

    function simpan_pinjam_scan($vckode, $intpinjam){
        $datadetail = $this->model->getdatadetailpallet($vckode, $intpinjam);
        $datapallet = $this->modelapp->getdatadetailcustom('m_pallet', $vckode, 'vckode');
        if (count($datadetail) >= 1 || count($datapallet) == 0 ) {
            $data['intstatus'] = 2;
            echo json_encode($data);
        } else {
            $intstatus = $datapallet[0]->intstatus;
            $intpallet  = $datapallet[0]->intid;
            if ($intstatus == 2 || $intstatus == 3 || $intstatus == 4) {
                $data['intstatus'] = $intstatus;
                echo json_encode($data);
            }
            else if ($intstatus == 1) {
                //insert or update to data pinjam detail
                $data2 = array(
                    'intpinjam' => $intpinjam,
                    'intpallet' => $intpallet,
                    'intstatus' => 2
                );
                $result = $this->modelapp->insertdata('pr_pinjam_detail',$data2);
                if ($result) {
                    $data['datapinjam'] = $this->model->getdatapinjampallet($intpinjam);
                    echo json_encode($data);
                }
            }
        }
    }

    function hapusdata_ajax($intid, $intpinjam){
        $this->modelapp->deletedata('pr_pinjam_detail',$intid,'intid');
        $data['datapinjam']  = $this->model->getdatapinjampallet($intpinjam);
        echo json_encode($data);
    }
    
    function batalSimpan($intid){
        $data      = $this->modelapp->getdatadetailcustom('pr_pinjam_detail',$intid,'intpinjam');
        if (count($data) === 0) {
            $this->modelapp->deletedata('pr_pinjam',$intid,'intid');
            redirect(base_url('pinjam/view'));
         } else {
            $this->modelapp->deletedata('pr_pinjam',$intid,'intid');
            $this->modelapp->deletedata('pr_pinjam_detail',$intid,'intpinjam');
            redirect(base_url('pinjam/view'));
        }
    }

    function simpanPallet($intid){
        //update data pallet
        $data       = $this->modelapp->getdatadetailcustom('pr_pinjam_detail',$intid,'intpinjam');
        foreach ($data as $dataset) { 
                $datapinjam = $this->modelapp->getdatadetailcustom('pr_pinjam', $intid, 'intid');
                $intcell    = $datapinjam[0]->intcell;
                $datacell   = $this->modelapp->getdatadetailcustom('m_cell', $intcell, 'intid');
                $vccell     = $datacell[0]->vcnama;
                $dataupdate = array(
                            'intlokasi' => $intcell,
                            'introom'   => 0,
                            'vclokasi'  => $vccell,
                            'intstatus' => 2
                    );
                $result = $this->modelapp->updatedata('m_pallet',$dataupdate,$dataset->intpallet);
            
        }
        if ($result) {
            redirect(base_url('pinjam/view'));
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
                     ->setTitle("Report Borrow " . $judul)
                     ->setSubject("Report Borrow")
                     ->setDescription("Report Borrow")
                     ->setKeywords("Report Borrow");

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

            $excel->setActiveSheetIndex($loop)->setCellValue('B1', "Report Borrow " . $cell->vckode . ' - ' . $cell->vcnama);
            $excel->getActiveSheet()->mergeCells('B1:M1'); // Set Merge Cell
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
            $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

            $excel->setActiveSheetIndex($loop)->setCellValue('B2', "Report Borrow, on Date : ". date('d-m-Y',strtotime($from)) . " To ". date('d-m-Y',strtotime($to)));
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
            $excel->setActiveSheetIndex($loop)->setCellValue('K3', "Borrower");
            $excel->setActiveSheetIndex($loop)->setCellValue('L3', "Status");
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
                $excel->setActiveSheetIndex($loop)->setCellValue('C'.$numrow, date('d M Y', strtotime($dataset->dtpinjam)));
                $excel->setActiveSheetIndex($loop)->setCellValue('D'.$numrow, $dataset->vckode);
                $excel->setActiveSheetIndex($loop)->setCellValue('E'.$numrow, $dataset->vcmodel);
                $excel->setActiveSheetIndex($loop)->setCellValue('F'.$numrow, $dataset->vcproses);
                $excel->setActiveSheetIndex($loop)->setCellValue('G'.$numrow, $dataset->vcsize);
                $excel->setActiveSheetIndex($loop)->setCellValue('H'.$numrow, $dataset->vcside);
                $excel->setActiveSheetIndex($loop)->setCellValue('I'.$numrow, $dataset->vcgedung);
                $excel->setActiveSheetIndex($loop)->setCellValue('J'.$numrow, $dataset->vccell);
                $excel->setActiveSheetIndex($loop)->setCellValue('K'.$numrow, $dataset->vckaryawan);
                $excel->setActiveSheetIndex($loop)->setCellValue('L'.$numrow, $dataset->vcstatus);
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
        header('Content-Disposition: attachment; filename="Report Borrow ' .$judul. '.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
