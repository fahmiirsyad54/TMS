<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RakModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getdata($table, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, count(c.intid) as intjumlahroom, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus,
                        ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_room as c', 'a.intid = c.intrak', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, count(c.intid) as intjumlahroom, a.intstatus, ISNULL(d.vcnama, 0) as vcgedung,
                            ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna, ',false);
         $this->db->from($table . ' as a');
         $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
         $this->db->join('m_room as c', 'a.intid = c.intrak', 'left');
         $this->db->join('m_gedung as d', 'd.intid = a.intgedung', 'left');
         $this->db->like('a.vcnama', $keyword);
         $this->db->or_like('a.vckode', $keyword);
         $this->db->group_by('a.intid, a.vckode, a.vcnama, a.intstatus, b.vcnama, b.vcwarna, a.dtupdate, d.vcnama');
         $this->db->order_by('a.vcnama','asc');
         $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
       $this->db->select('a.intid, a.vckode, a.vcnama, a.vcwarna,  a.dtupdate, a.intstatus, count(c.intid) as intjumlahroom, 
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
          $this->db->select('RIGHT(m_rak.vckode,3) as kode', FALSE);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          $query = $this->db->get('m_rak');      //cek dulu apakah ada sudah ada kode di tabel.    
          if($query->num_rows() <> 0){      
           //jika kode ternyata sudah ada.      
           $data = $query->row();      
           $kode = intval($data->kode) + 1;    
          }
          else {      
           //jika kode belum ada      
           $kode = 1;    
          }
          $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
          $kodejadi = "BD".$kodemax;    // hasilnya ODJ-9921-0001 dst.
          return $kodemax;  
    }

    public function buat_nama($intgedung)   {
        $this->db->select('RIGHT(m_rak.vcnama,2) as kode', FALSE);
        $this->db->where('intgedung',$intgedung);
        $this->db->order_by('intid','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('m_rak');      //cek dulu apakah ada sudah ada kode di tabel.    
        if($query->num_rows() <> 0){      
         //jika kode ternyata sudah ada.      
         $data = $query->row();      
         $kode = intval($data->kode) + 1;    
        }
        else {      
         //jika kode belum ada      
         $kode = 01;    
        }
        $kodemax = str_pad($kode, 2, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
        $kodejadi = "BD".$kodemax;    // hasilnya ODJ-9921-0001 dst.
        return $kodemax;  
    }
}