<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PerbaikanModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $from=null, $to=null, $intgedung=0, $intmodel=0, $intproses=0){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_pallet as b','a.intpallet = b.intid','left');
        if ($from) {
            $this->db->where('a.dtperbaikan >= ', $from);
            $this->db->where('a.dtperbaikan <= ', $to);
        }

        if ($intgedung > 0) {
            $this->db->where('b.intgedung',$intgedung);
        }
        if ($intmodel > 0) {
            $this->db->where('b.intmodel',$intmodel); 
        }
        if ($intproses > 0) {
            $this->db->where('b.intproses',$intproses); 
        }
        
        return $this->db->get()->result();
    }

    function getdata($from=null, $to=null, $intgedung=0, $intmodel=0, $intproses=0){
        $this->db->select('a.intid, a.dtperbaikan, ISNULL(c.vckode, 0) as vckode, ISNULL(d.vcnama, 0) as vcmodel, 
                        ISNULL(e.vcnama, 0) as vcproses, ISNULL(f.vcnama, 0) as vcsize, ISNULL(g.vcnama, 0) as vcside, 
                        ISNULL(i.vcnama, 0) as vcgedung, ISNULL(l.vcnama, 0) as vcuser, ISNULL(h.vcnama, 0) as vckondisi',false);
        $this->db->from('pr_perbaikan as a');
        $this->db->join('m_pallet as c','c.intid = a.intpallet');
        $this->db->join('m_models as d','d.intid = c.intmodel');
        $this->db->join('m_proses as e','e.intid = c.intproses');
        $this->db->join('m_size as f','f.intid = c.intsize');
        $this->db->join('m_side as g','g.intid = c.intsisi');
        $this->db->join('m_kondisi as h','h.intid = a.intkondisi');
        $this->db->join('m_gedung as i','i.intid = c.intgedung');
        $this->db->join('app_muser as l','l.intid = a.intuser');
        if ($from) {
            $this->db->where('a.dtperbaikan >= ', $from);
            $this->db->where('a.dtperbaikan <= ', $to);
        }
        if ($intgedung > 0) {
        $this->db->where('c.intgedung',$intgedung); 
        }
        if ($intmodel > 0) {
            $this->db->where('c.intmodel',$intmodel); 
        }
        if ($intproses > 0) {
            $this->db->where('c.intproses',$intproses); 
        }
        $this->db->order_by('a.dtupdate','desc');

        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5,  $from=null, $to=null, $intgedung=0, $intmodel=0, $intproses=0){
        $this->db->select('a.intid, convert(varchar, a.dtperbaikan, 6) as dtperbaikan, a.intpallet, a.intstatus, a.intkondisi, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna,
                            ISNULL(c.vcnama, 0) as vcgedung, ISNULL(d.vcnama, 0) as vcmodel, ISNULL(e.vcnama, 0) as vcproses, ISNULL(f.vckode, 0) as vckode, ISNULL(g.vcnama, 0) as vcuser',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_pallet as f','a.intpallet = f.intid','left');
        $this->db->join('m_kondisi' . ' as b', 'a.intkondisi = b.intid', 'left');
        $this->db->join('m_gedung as c','f.intgedung = c.intid','left');
        $this->db->join('m_models as d','f.intmodel = d.intid','left');
        $this->db->join('m_proses as e','f.intproses = e.intid','left');
        $this->db->join('app_muser as g','g.intid = a.intuser','left');
        if ($from) {
            $this->db->where('a.dtperbaikan >= ', $from);
            $this->db->where('a.dtperbaikan <= ', $to);
        }
        if ($intgedung > 0) {
            $this->db->where('f.intgedung',$intgedung);
        }
        if ($intmodel > 0) {
            $this->db->where('f.intmodel',$intmodel); 
        }
        if ($intproses > 0) {
            $this->db->where('f.intproses',$intproses); 
        }
    	$this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
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
    function getproses($intid){
        $this->db->select('a.intid, a.intmodel, a.intproses, b.vckode as vckode, b.vcnama as vcnama',false);
        $this->db->from('m_models_proses as a');
        $this->db->join('m_proses as b','b.intid = a.intproses');
        $this->db->where('a.intmodel',$intid);

        return $this->db->get()->result();
    }

    function getroom($intid){
        $this->db->select('a.intid as introom, a.vckode, a.vcnama',false);
        $this->db->from('m_room as a');
        $this->db->join('m_rak as b','b.intid = a.intrak');
        $this->db->where('b.intgedung',$intid);

        return $this->db->get()->result();
    }

    function getdatagedung($intgedung=0){
        if ($intgedung > 0) {
          $this->db->where('intid',$intgedung);
        }
        
        $this->db->order_by('intid','asc');
        return $this->db->get('m_gedung')->result();
    }
}