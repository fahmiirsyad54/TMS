<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GedungModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getdata($table, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, count(c.intid) as intjumlahcell, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_cell as c', 'a.intid = c.intgedung', 'left');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->or_like('a.intjumlahcell', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, count(c.intid) as intjumlahcell, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
         $this->db->from($table . ' as a');
         $this->db->join('m_cell as c', 'a.intid = c.intgedung', 'left');
         $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
         $this->db->like('a.vcnama', $keyword);
         $this->db->or_like('a.vckode', $keyword);
         $this->db->group_by('a.intid, a.vckode, a.vcnama, a.intstatus, b.vcnama, b.vcwarna, a.dtupdate');
         $this->db->order_by('a.dtupdate','desc');
         $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
       $this->db->select('a.intid, a.vckode, a.vcnama, a.vcwarna,  a.dtupdate, a.intstatus, count(c.intid) as intjumlahcell, 
                          ISNULL(b.vcnama, 0) as vcstatus, 
                          ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
       $this->db->from($table . ' as a');
       $this->db->join('m_cell as c', 'a.intid = c.intgedung', 'left');
       $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
       $this->db->where('a.intid',$intid);
       $this->db->group_by('a.intid, a.vckode, a.vcnama, a.vcwarna, a.intoeemonitoring, a.dtupdate, a.intstatus, b.vcnama, b.vcwarna');
       return $this->db->get()->result();
    }
     public function buat_kode()   {
          $this->db->select('RIGHT(m_gedung.vckode,4) as kode', FALSE);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          $query = $this->db->get('m_gedung');      //cek dulu apakah ada sudah ada kode di tabel.    
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
          $kodejadi = "BD".$kodemax;    // hasilnya ODJ-9921-0001 dst.
          return $kodejadi;  
    }

    public function buat_kode_cell()   {
          $this->db->select('RIGHT(m_cell.vckode,4) as kode', FALSE);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          $query = $this->db->get('m_cell');      //cek dulu apakah ada sudah ada kode di tabel.    
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
          $kodejadi = "C".$kodemax;    // hasilnya ODJ-9921-0001 dst.
          return $kodejadi;  
    }

}