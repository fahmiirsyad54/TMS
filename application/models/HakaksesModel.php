<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HakaksesModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getdata($table, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarnat',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.dtupdate, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }
    
    function getmenuheader($table){
        $this->db->where('intis_header', 1);
        return $this->db->get($table)->result();
    }

    function getmenu($table='app_mmenu'){
        $this->db->select('a.intid, a.vcnama, ISNULL(b.vcnama,0) as vcparent',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mmenu as b', 'a.intparent = b.intid','left');
        $this->db->order_by('a.intsorter ASC, a.intis_header DESC');
        return $this->db->get()->result();
    }

    function getmenuakses($table,$intid){
        $this->db->select('a.intmenu, b.vcnama, ISNULL(c.vcnama, 0) as vcparent',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mmenu as b', 'a.intmenu = b.intid');
        $this->db->join('app_mmenu as c', 'b.intparent = c.intid','left');
        $this->db->where('a.intheader', $intid);
        return $this->db->get()->result();
    }
}