<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductionModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    //model pinjam
    function getjmldata_pinjam($keyword='',$intgedung){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from('pr_pinjam as a');
        $this->db->like('a.vckode', $keyword);
    	$this->db->where('a.intgedung',$intgedung);
        return $this->db->get()->result();
    }

    function getdata($table, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna, ISNULL(c.vcnama, 0) as vcparent',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join($table . ' as c', 'a.intparent = c.intid', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit_pinjam($halaman=0, $limit=5, $keyword='', $intgedung){
        $this->db->select('a.intid, a.vckode, a.dtpinjam, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna,
                            ISNULL(c.vcnama, 0) as vcgedung, ISNULL(d.vcnama, 0) as vccell, ISNULL(e.vcnama, 0) as vckaryawan,
                            sum(f.inttotal) as total',false);
        $this->db->from('pr_pinjam as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung as c','a.intgedung = c.intid','left');
        $this->db->join('m_cell as d','a.intcell = d.intid','left');
        $this->db->join('m_karyawan as e','a.intkaryawan = e.intid','left');
        $this->db->join('pr_pinjam_detail as f','a.intid = f.intpinjam','left');
        $this->db->like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->group_by('a.intid, a.vckode, a.dtpinjam, a.intstatus, b.vcnama,b.vcwarna, c.vcnama,d.vcnama, e.vcnama, a.dtupdate');
        $this->db->limit($limit, $halaman);
    	$this->db->where('a.intgedung',$intgedung);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
        $this->db->select('a.intid, a.vckode, a.intgedung, a.intmodel, a.intproses, a.intsize, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna,
        ISNULL(c.vcnama, 0) as vcgedung, ISNULL(d.vcnama, 0) as vcmodel, ISNULL(e.vcnama, 0) as vcproses, a.intqty',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung as c','a.intgedung = c.intid','left');
        $this->db->join('m_models as d','a.intmodel = d.intid','left');
        $this->db->join('m_proses as e','a.intproses = e.intid','left');
    	$this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    // function custom
    function getmenuheader($table){
        $this->db->where('intis_header', 1);
        return $this->db->get($table)->result();
    }

    function getdatapinjam($vckode){
        $this->db->select('a.intid, a.vckode, a.intgedung, a.intcell, a.intkaryawan, ISNULL(b.vcnama, 0) as vcgedung,
                            ISNULL(c.vcnama, 0) as vccell, ISNULL(d.vcnama, 0) as vckaryawan',false);
        $this->db->from('pr_pinjam as a');
        $this->db->join('m_gedung as b','b.intid = a.intgedung');
        $this->db->join('m_cell as c','c.intid = a.intcell');
        $this->db->join('m_karyawan as d','d.intid = a.intkaryawan');
        $this->db->where('a.vckode',$vckode);

        return $this->db->get()->result();
    }

    public function buat_kode($datenow)   {
        $this->db->select('RIGHT(pr_pinjam.vckode,4) as kode', FALSE);
        $this->db->order_by('intid','DESC');    
        $this->db->limit(1);
            
        $query = $this->db->get('pr_pinjam');      //cek dulu apakah ada sudah ada kode di tabel.    
        if($query->num_rows() <> 0){      
         //jika kode ternyata sudah ada.      
         $data = $query->row();      
         $kode = intval($data->kode) + 1;    
        }
        else {      
         //jika kode belum ada      
         $kode = 1;    
        }
        $kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
        $kodejadi = "BRW".$datenow;    // hasilnya ODJ-9921-0001 dst.
        return $kodejadi;  
    }

    function getpallet(){
        $this->db->select('a.intid, a.vckode, a.intqty, ISNULL(b.vcnama, 0) as vcmodel, ISNULL(c.vcnama, 0) as vcproses,
                            ISNULL(d.vcnama, 0) as vcsize',false);
        $this->db->from('m_pallet as a');
        $this->db->join('m_models as b','b.intid = a.intmodel','left');
        $this->db->join('m_proses as c','c.intid = a.intproses','left');
        $this->db->join('m_size as d','d.intid = a.intsize','left');

        return $this->db->get()->result();
    }

    function getdatapinjampallet($intpinjam){
        $this->db->select('a.intid, ISNULL(c.vckode, 0) as vckode, ISNULL(d.vcnama, 0) as vcmodel, ISNULL(e.vcnama, 0) as vcproses,
                         ISNULL(f.vcnama, 0) as vcsize, a.inttotal',false);
        $this->db->from('pr_pinjam_detail as a');
        $this->db->join('pr_pinjam as b','b.intid = a.intpinjam');
        $this->db->join('m_pallet as c','c.intid = a.intpallet');
        $this->db->join('m_models as d','d.intid = c.intmodel');
        $this->db->join('m_proses as e','e.intid = c.intproses');
        $this->db->join('m_size as f','f.intid = c.intsize');
        $this->db->where('b.intid',$intpinjam);

        return $this->db->get()->result();
    }

    function getdatapinjamdetail($intpinjam=0, $intpallet=0){
        $this->db->select('ISNULL(a.inttotal, 0) as inttotal',false);
        $this->db->from('pr_pinjam_detail as a');
        if ($intpinjam > 0) {
            $this->db->where('a.intpinjam',$intpinjam);
        }

        if ($intpallet > 0) {
            $this->db->where('a.intpallet',$intpallet);
        }
        //$this->db->where('a.intpinjam',$intpinjam);
        //$this->db->where('a.intpallet',$intpallet);
        //$this->db->group_by('a.intid, a.inttotal');

        return $this->db->get()->result();
    }

    function updatedata($intpinjam, $intpallet, $data){
        $this->db->where('intpinjam',$intpinjam);
        $this->db->where('intpallet',$intpallet);

        return $this->db->update('pr_pinjam_detail',$data);
    }

    function updatedatapallet($intpallet, $data){
        $this->db->where('intid',$intpallet);

        return $this->db->update('m_pallet',$data);
    }
}