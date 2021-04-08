<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('ProfilModel');
        $this->model        = $this->ProfilModel;
        $this->intid        = $this->session->intid;
        $this->indexview    = '';

        if ($this->session->intusertipe == 1) {
            $this->intid     = $this->session->intpegawai;
            $this->table     = 'mpegawai';
            $this->indexview = '_pegawai';
        } elseif ($this->session->intusertipe == 2) {
            $this->intid     = $this->session->intmahasiswa;
            $this->table     = 'mmahasiswa';
            $this->indexview = '_mahasiswa';
        }
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
        $data['action']     = 'Ubah';
        $data['intid']      = $this->session->intid;
        $data['keyword']    = $keyword;
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataMain']   = $this->model->getdatadetail($this->table,$this->intid);
        
        $this->template->set_layout('default')->build($this->view . '/index'.$this->indexview,$data);
    }

    function detail($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail($this->table,$intid);
        $data['dataHistory'] = $this->model->getdatahistory($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

     function editPassword($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->modelapp->getdatadetail($this->table,$intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory($this->tablehistory,$intid);
        $this->load->view($this->view . '/editPassword',$data);
    }

    function tambah(){
        $data = array(
                    'intid'              => 0,
                    'vckode'             => '',
                    'vcnama'             => '',
                    'intadd'             => $this->session->intid,
                    'dtadd'              => date('Y-m-d H:i:s'),
                    'intupdate'          => $this->session->intid,
                    'dtupdate'           => date('Y-m-d H:i:s'),
                    'intstatus'          => 0
                );

        $data['title']               = $this->title;
        $data['action']              = 'Tambah';
        $data['controller']          = $this->controller;
        $data['listprovinsi']        = $this->model->getdatalist('mprovinsi');
        $data['listkota']            = [];
        $data['listfakultas']        = $this->model->getdatalist('mfakultas');
        $data['listprodi']           = [];
        $data['listjeniskelamin']    = $this->model->getdatalist('mjeniskelamin');
        $data['listagama']           = $this->model->getdatalist('magama');
        $data['listkewarganegaraan'] = $this->model->getdatalist('mkewarganegaraan');
        $data['listtahunangkatan']   = $this->model->getdatalist('mtahunangkatan');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function ubah($intid){
        $resultData = $this->model->getdatadetail($this->table,$intid);
        $data = array(
                    'intid'              => $resultData[0]->intid,
                    'vckode'             => $resultData[0]->vckode,
                    'vcnama'             => $resultData[0]->vcnama,
                    'intupdate'          => $this->session->intid,
                    'dtupdate'           => date('Y-m-d H:i:s')
                );

        $data['title']               = $this->title;
        $data['action']              = 'Ubah';
        $data['controller']          = $this->controller;
        $data['listprovinsi']        = $this->model->getdatalist('mprovinsi');
        $data['listkota']            = $this->model->getdatalist('mkota',$resultData[0]->intprovinsi,'intprovinsi');
        $data['listfakultas']        = $this->model->getdatalist('mfakultas');
        $data['listprodi']           = $this->model->getdatadetailcustom('mprodi',$resultData[0]->intfakultas,'intfakultas');
        $data['listjeniskelamin']    = $this->model->getdatalist('mjeniskelamin');
        $data['listagama']           = $this->model->getdatalist('magama');
        $data['listkewarganegaraan'] = $this->model->getdatalist('mkewarganegaraan');
        $data['listtahunangkatan']   = $this->model->getdatalist('mtahunangkatan');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function validasiform($tipe){
        $array = array();
        $data = $this->input->post();
        if ($tipe == 'data') {
            foreach ($data as $key => $value) {
                $result = $this->model->getdatadetailcustom($this->table,$value,$key);
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
        if ($tipe == 'Tambah') {
            $vckode             = $this->input->post('vckode');
            $vcnama             = $this->input->post('vcnama');
            $vctempatlahir      = $this->input->post('vctempatlahir');
            $dttanggallahir     = $this->input->post('dttanggallahir');
            $vcalamat           = $this->input->post('vcalamat');
            $intprovinsi        = $this->input->post('intprovinsi');
            $vcprovinsi         = $this->input->post('vcprovinsi');
            $intkota            = $this->input->post('intkota');
            $vckota             = $this->input->post('vckota');
            $vcnomortelepon     = $this->input->post('vcnomortelepon');
            $vcnomorhp          = $this->input->post('vcnomorhp');
            $intjeniskelamin    = $this->input->post('intjeniskelamin');
            $vcjeniskelamin     = $this->input->post('vcjeniskelamin');
            $intagama           = $this->input->post('intagama');
            $vcagama            = $this->input->post('vcagama');
            $intkewarganegaraan = $this->input->post('intkewarganegaraan');
            $vckewarganegaraan  = $this->input->post('vckewarganegaraan');
            $vcemail            = $this->input->post('vcemail');
            $vcnamaayah         = $this->input->post('vcnamaayah');
            $vcpekerjaanayah    = $this->input->post('vcpekerjaanayah');
            $vcnamaibu          = $this->input->post('vcnamaibu');
            $vcpekerjaanibu     = $this->input->post('vcpekerjaanibu');
            $vcnomorteleponortu = $this->input->post('vcnomorteleponortu');
            $vcnomorhportu      = $this->input->post('vcnomorhportu');
            $intfakultas        = $this->input->post('intfakultas');
            $vcfakultas         = $this->input->post('vcfakultas');
            $intprodi           = $this->input->post('intprodi');
            $vcprodi            = $this->input->post('vcprodi');
            $inttahunangkatan   = $this->input->post('inttahunangkatan');
            $vctahunangkatan    = $this->input->post('vctahunangkatan');
            $data         = array(
                            'vckode'             => $vckode,
                            'vcnama'             => $vcnama,
                            'vctempatlahir'      => $vctempatlahir,
                            'dttanggallahir'     => date('Y-m-d',strtotime($dttanggallahir)),
                            'vcalamat'           => $vcalamat,
                            'intprovinsi'        => $intprovinsi,
                            'vcprovinsi'         => $vcprovinsi,
                            'intkota'            => $intkota,
                            'vckota'             => $vckota,
                            'vcnomortelepon'     => $vcnomortelepon,
                            'vcnomorhp'          => $vcnomorhp,
                            'intjeniskelamin'    => $intjeniskelamin,
                            'vcjeniskelamin'     => $vcjeniskelamin,
                            'intagama'           => $intagama,
                            'vcagama'            => $vcagama,
                            'intkewarganegaraan' => $intkewarganegaraan,
                            'vckewarganegaraan'  => $vckewarganegaraan,
                            'vcemail'            => $vcemail,
                            'vcnamaayah'         => $vcnamaayah,
                            'vcpekerjaanayah'    => $vcpekerjaanayah,
                            'vcnamaibu'          => $vcnamaibu,
                            'vcpekerjaanibu'     => $vcpekerjaanibu,
                            'vcnomorteleponortu' => $vcnomorteleponortu,
                            'vcnomorhportu'      => $vcnomorhportu,
                            'intfakultas'        => $intfakultas,
                            'vcfakultas'         => $vcfakultas,
                            'intprodi'           => $intprodi,
                            'vcprodi'            => $vcprodi,
                            'inttahunangkatan'   => $inttahunangkatan,
                            'vctahunangkatan'    => $vctahunangkatan,
                            'intadd'             => $this->session->intid,
                            'dtadd'              => date('Y-m-d H:i:s'),
                            'intupdate'          => $this->session->intid,
                            'dtupdate'           => date('Y-m-d H:i:s'),
                            'intstatus'          => 1
                        );
            $result = $this->model->insertdata($this->table,$data);
            if ($result) {
                redirect(base_url($this->controller . '/lihat'));
            }
        } elseif ($tipe == 'Ubah') {
            $vcpassword     = $this->input->post('vcpassword');
            $data           = array(
                            'vcpassword'         => $vcpassword,
                            'intupdate'          => $this->session->intid,
                            'dtupdate'           => date('Y-m-d H:i:s')
                        );
            $result = $this->model->updatedata($this->table,$data,$intid);
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
            $result = $this->model->updatedata($this->table,$data,$intid);
            if ($result) {
                redirect(base_url($this->controller . '/lihat'));
            }
        }
    }

    function validasi_password($vcpassword) {
        $intid = $this->session->intid;

        $data = $this->model->validasi_password($intid, $vcpassword);

        echo json_encode($data);
    }

}
