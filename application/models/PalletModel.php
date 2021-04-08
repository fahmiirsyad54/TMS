<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PalletModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $intgedung=0, $intmodel=0, $intproses=0, $keyword=''){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        if ($intgedung > 0) {
            $this->db->where('a.intgedung',$intgedung); 
        }

        if ($intmodel > 0) {
            $this->db->where('a.intmodel',$intmodel); 
        }

        if ($intproses > 0) {
            $this->db->where('a.intproses',$intproses); 
        }
        $this->db->like('a.vckode', $keyword);
        $this->db->like('a.vclokasi', $keyword);
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
    
    function getdatalimit($table,$halaman=0, $limit=5, $intgedung=0, $intmodel=0, $intproses=0, $keyword=''){
        $this->db->select('a.intid, a.vckode, ISNULL(g.vcnama, 0) as vcstatus, ISNULL(g.vcwarna, 0) as vcwarna,
                            ISNULL(c.vcnama, 0) as vcgedung, ISNULL(d.vcnama, 0) as vcmodel, 
                            ISNULL(e.vcnama, 0) as vcproses, a.vclokasi, ISNULL(f.vcnama, 0) as vcsize',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_gedung as c','a.intgedung = c.intid','left');
        $this->db->join('m_models as d','a.intmodel = d.intid','left');
        $this->db->join('m_proses as e','a.intproses = e.intid','left');
        $this->db->join('m_size as f','a.intsize = f.intid','left');
        $this->db->join('m_status as g','a.intstatus = g.intid','left');
        if ($intgedung > 0) {
            $this->db->where('a.intgedung',$intgedung); 
        }

        if ($intmodel > 0) {
            $this->db->where('a.intmodel',$intmodel); 
        }

        if ($intproses > 0) {
            $this->db->where('a.intproses',$intproses); 
        }

        $this->db->like('a.vckode', $keyword);
        $this->db->or_like('a.vclokasi', $keyword);
    	$this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
        $this->db->select('a.intid, a.vckode, a.intgedung, a.intmodel, a.intproses, a.intsize, a.intstatus, 
                            ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna,
                            ISNULL(c.vcnama, 0) as vcgedung, ISNULL(d.vcnama, 0) as vcmodel, 
                            ISNULL(e.vcnama, 0) as vcproses, ISNULL(f.vcnama, 0) as vcsize, 
                            ISNULL(g.vcnama, 0) as vcside, vclokasi, a.intsisi, a.intlokasi, a.introom,
                            convert(varchar, a.dtadd, 6) as dttanggal, ISNULL(h.vcnama, 0) as vcuser',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung as c','a.intgedung = c.intid','left');
        $this->db->join('m_models as d','a.intmodel = d.intid','left');
        $this->db->join('m_proses as e','a.intproses = e.intid','left');
        $this->db->join('m_size as f','a.intsize = f.intid','left');
        $this->db->join('m_side as g','a.intsisi = g.intid','left');
        $this->db->join('app_muser as h','a.intadd = h.intid','left');
    	$this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    // function custom
    function getmenuheader($table){
        $this->db->where('intis_header', 1);
        return $this->db->get($table)->result();
    }

    function getproses($intid){
        $this->db->select('a.intid, a.intmodel, a.intproses, b.vckode as vckode, b.vcnama as vcnama',false);
        $this->db->from('m_models_proses as a');
        $this->db->join('m_proses as b','b.intid = a.intproses');
        $this->db->where('a.intmodel',$intid);

        return $this->db->get()->result();
    }

    function getcell($intid){
        $this->db->select('a.intid, a.vckode, a.vcnama',false);
        $this->db->from('m_cell as a');
        $this->db->where('a.intgedung',$intid);

        return $this->db->get()->result();
    }

    function getroom($intid){
        $this->db->select('a.intid as introom, a.vckode, a.vcnama',false);
        $this->db->from('m_room as a');
        $this->db->join('m_rak as b','b.intid = a.intrak');
        $this->db->where('b.intgedung',$intid);

        return $this->db->get()->result();
    }

    function getdataroom(){
        $this->db->select('a.intid, a.vckode',false);
        $this->db->from('m_room as a');
        //$this->db->join('m_rak as b','b.intid = a.intrak');

        return $this->db->get()->result();
    }

    public function buat_kode()   {
        $this->db->select('RIGHT(m_pallet.vckode,5) as kode', FALSE);
        $this->db->order_by('intid','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('m_pallet');      //cek dulu apakah ada sudah ada kode di tabel.    
        if($query->num_rows() <> 0){      
         //jika kode ternyata sudah ada.      
         $data = $query->row();      
         $kode = intval($data->kode) + 1;    
        }
        else {      
         //jika kode belum ada      
         $kode = 1;    
        }
        $kodejadi = str_pad($kode, 5, "0", STR_PAD_LEFT); // angka 5 menunjukkan jumlah digit angka 0
        //$kodejadi = "DT".$kodemax;    // hasilnya ODJ-9921-0001 dst.
        return $kodejadi;  
    }

    public function buat_kode_pallet()   {
        $this->db->select('RIGHT(m_pallettemp.vckode) as kode', FALSE);
        $this->db->order_by('intid','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('m_pallettemp');      //cek dulu apakah ada sudah ada kode di tabel.    
        if($query->num_rows() <> 0){      
         //jika kode ternyata sudah ada.      
         $data = $query->row();      
         $kode = intval($data->kode) + 1;    
        }
        else {      
         //jika kode belum ada      
         $kode = 1;    
        }
        $kodemax = str_pad($kode, 5, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
        return $kodemax;  
    }

    function getdatatemp(){
        $this->db->select('intid',false);
        $this->db->from('m_pallettemp');
        $this->db->order_by('intid','DESC');    
        $this->db->limit(1);

        return $this->db->get()->result();
    }

    function getdatapallet($intid){
        $this->db->select('a.intid, a.vckode, ISNULL(c.vcnama, 0) as vcgedung, ISNULL(d.vcnama, 0) as vcmodel, 
                        ISNULL(e.vcnama, 0) as vcproses, ISNULL(f.vcnama, 0) as vcsize, 
                        ISNULL(g.vcnama, 0) as vcside, ISNULL(h.vcnama, 0) as vcroom',false);
        $this->db->from('m_pallettemp_detail as a');
        $this->db->join('m_pallettemp as b','b.intid = a.inttemp');
        $this->db->join('m_gedung as c','c.intid = a.intgedung');
        $this->db->join('m_models as d','d.intid = a.intmodel');
        $this->db->join('m_proses as e','e.intid = a.intproses');
        $this->db->join('m_size as f','f.intid = a.intsize');
        $this->db->join('m_side as g','g.intid = a.intsisi');
        $this->db->join('m_room as h','h.intid = a.introom');
        $this->db->where('b.intid',$intid);

        return $this->db->get()->result();
    }

    function getdatadetailpallet($vckode, $intid){
        $this->db->select('a.intid',false);
        $this->db->from('m_pallettemp_detail as a');
        $this->db->where('a.vckode',$vckode);
        $this->db->where('a.inttemp',$intid);

        return $this->db->get()->result();
    }
}