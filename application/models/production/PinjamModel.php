<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PinjamModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $from=null, $to=null, $intgedung=0, $intcell=0, $intkaryawan=0){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        if ($from) {
            $this->db->where('a.dtpinjam >= ', $from);
            $this->db->where('a.dtpinjam <= ', $to);
        }
        
        if ($intcell > 0) {
            $this->db->where('a.intcell',$intcell); 
        }
        if ($intkaryawan > 0) {
            $this->db->where('a.intkaryawan',$intkaryawan); 
        }
        $this->db->where('a.intgedung',$intgedung);
        return $this->db->get()->result();
    }

    function getdata($intcell=0, $from=null, $to=null){
        $this->db->select('a.intid, b.dtpinjam, ISNULL(c.vckode, 0) as vckode, ISNULL(d.vcnama, 0) as vcmodel, 
                        ISNULL(e.vcnama, 0) as vcproses, ISNULL(f.vcnama, 0) as vcsize, ISNULL(g.vcnama, 0) as vcside, 
                        ISNULL(i.vcnama, 0) as vcgedung, ISNULL(j.vcnama, 0) as vccell, ISNULL(k.vcnama, 0) as vckaryawan, 
                        ISNULL(l.vcnama, 0) as vcuser, ISNULL(h.vcnama, 0) as vcstatus',false);
        $this->db->from('pr_pinjam_detail as a');
        $this->db->join('pr_pinjam as b','b.intid = a.intpinjam');
        $this->db->join('m_pallet as c','c.intid = a.intpallet');
        $this->db->join('m_models as d','d.intid = c.intmodel');
        $this->db->join('m_proses as e','e.intid = c.intproses');
        $this->db->join('m_size as f','f.intid = c.intsize');
        $this->db->join('m_side as g','g.intid = c.intsisi');
        $this->db->join('m_status as h','h.intid = a.intstatus');
        $this->db->join('m_gedung as i','i.intid = b.intgedung');
        $this->db->join('m_cell as j','j.intid = b.intcell');
        $this->db->join('m_karyawan as k','k.intid = b.intkaryawan');
        $this->db->join('app_muser as l','l.intid = b.intuser');
        if ($from) {
            $this->db->where('b.dtpinjam >= ', $from);
            $this->db->where('b.dtpinjam <= ', $to);
          }
  
          if ($intcell > 0) {
            $this->db->where('b.intcell',$intcell); 
          }
          $this->db->order_by('b.dtupdate','desc');

        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $from=null, $to=null, $intgedung=0, $intcell=0, $intkaryawan=0){
        $this->db->select('a.intid, a.vckode, convert(varchar, a.dtpinjam, 6) as dtpinjam, a.intstatus, 
                            ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna,
                            ISNULL(c.vcnama, 0) as vcgedung, ISNULL(d.vcnama, 0) as vccell, 
                            ISNULL(e.vcnama, 0) as vckaryawan, count(f.intid) as total,
                            ISNULL(h.vcnama, 0) as vcuser',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung as c','a.intgedung = c.intid','left');
        $this->db->join('m_cell as d','a.intcell = d.intid','left');
        $this->db->join('m_karyawan as e','a.intkaryawan = e.intid','left');
        $this->db->join('pr_pinjam_detail as f','a.intid = f.intpinjam','left');
        $this->db->join('app_muser as h','h.intid = a.intuser','left');
        $this->db->where('a.intgedung',$intgedung);
        if ($from) {
            $this->db->where('a.dtpinjam >= ', $from);
            $this->db->where('a.dtpinjam <= ', $to);
        }
        if ($intcell > 0) {
            $this->db->where('a.intcell',$intcell); 
        }
        if ($intkaryawan > 0) {
            $this->db->where('a.intkaryawan',$intkaryawan); 
        }
        $this->db->order_by('a.dtupdate','desc');
        $this->db->group_by('a.intid, a.vckode, a.dtpinjam, a.intstatus, b.vcnama,b.vcwarna, c.vcnama,d.vcnama, e.vcnama, a.dtupdate, h.vcnama');
        $this->db->having('count(f.intid) > 0');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
        $this->db->select('a.vckode, ISNULL(b.vcnama, 0) as vcgedung, ISNULL(c.vcnama, 0) as vccell,
                            ISNULL(d.vcnama, 0) as vckaryawan, convert(varchar, a.dtpinjam, 6) as dtpinjam,
                            ISNULL(e.vcnama, 0) as vcuser',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_gedung as b','a.intgedung = b.intid','left');
        $this->db->join('m_cell as c','a.intcell = c.intid','left');
        $this->db->join('m_karyawan as d','a.intkaryawan = d.intid','left');
        $this->db->join('app_muser as e','a.intuser = e.intid','left');
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

    function getdatapinjam2($intpinjam){
        $this->db->select('a.intid, a.vckode, a.intgedung, a.intcell, a.intkaryawan, ISNULL(b.vcnama, 0) as vcgedung,
                            ISNULL(c.vcnama, 0) as vccell, ISNULL(d.vcnama, 0) as vckaryawan',false);
        $this->db->from('pr_pinjam as a');
        $this->db->join('m_gedung as b','b.intid = a.intgedung');
        $this->db->join('m_cell as c','c.intid = a.intcell');
        $this->db->join('m_karyawan as d','d.intid = a.intkaryawan');
        $this->db->where('a.intid',$intpinjam);

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

    function getdatapinjampallet($intpinjam){
        $this->db->select('a.intid, a.intpallet, ISNULL(c.vckode, 0) as vckode, ISNULL(d.vcnama, 0) as vcmodel, 
                        ISNULL(e.vcnama, 0) as vcproses, ISNULL(f.vcnama, 0) as vcsize, 
                        ISNULL(g.vcnama, 0) as vcside, ISNULL(h.vcnama, 0) as vcstatus',false);
        $this->db->from('pr_pinjam_detail as a');
        $this->db->join('pr_pinjam as b','b.intid = a.intpinjam');
        $this->db->join('m_pallet as c','c.intid = a.intpallet');
        $this->db->join('m_models as d','d.intid = c.intmodel');
        $this->db->join('m_proses as e','e.intid = c.intproses');
        $this->db->join('m_size as f','f.intid = c.intsize');
        $this->db->join('m_side as g','g.intid = c.intsisi');
        $this->db->join('m_status as h','h.intid = a.intstatus');
        $this->db->where('b.intid',$intpinjam);

        return $this->db->get()->result();
    }

    function getdatadetailpallet($vckode, $intpinjam){
        $this->db->select('a.intid',false);
        $this->db->from('pr_pinjam_detail as a');
        $this->db->join('m_pallet as b','b.intid = a.intpallet');
        $this->db->where('b.vckode',$vckode);
        $this->db->where('a.intpinjam',$intpinjam);

        return $this->db->get()->result();
    }

    public function buat_kode_kembali($datenow)   {
        $this->db->select('RIGHT(pr_kembali.vckode,4) as kode', FALSE);
        $this->db->order_by('intid','DESC');    
        $this->db->limit(1);
            
        $query = $this->db->get('pr_kembali');      //cek dulu apakah ada sudah ada kode di tabel.    
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
        $kodejadi = "RTN".$datenow;    // hasilnya ODJ-9921-0001 dst.
        return $kodejadi;  
    }

    function getdatacell($intgedung=0, $intcell=0){
        if ($intgedung > 0) {
          $this->db->where('intgedung',$intgedung);
        }
  
        if ($intcell > 0) {
          $this->db->where('intid',$intcell);  
        }

        $this->db->order_by('intid','asc');
        return $this->db->get('m_cell')->result();
    }
}