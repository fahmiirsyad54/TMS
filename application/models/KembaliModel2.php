<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KembaliModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $keyword=''){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        $this->db->like('a.vckode', $keyword);
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
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna,
                            ISNULL(c.vcnama, 0) as vcgedung, ISNULL(d.vcnama, 0) as vccell, ISNULL(e.vcnama, 0) as vckaryawan',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('pr_pinjam as h','a.intpinjam = h.intid','left');
        $this->db->join('m_gedung as c','h.intgedung = c.intid','left');
        $this->db->join('m_cell as d','h.intcell = d.intid','left');
        $this->db->join('m_karyawan as e','h.intkaryawan = e.intid','left');
        $this->db->join('pr_kembali_detail as f','a.intid = f.intkembali','left');
        $this->db->like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->group_by('a.intid, a.vckode, a.intstatus, b.vcnama,b.vcwarna, c.vcnama,d.vcnama, e.vcnama, a.dtupdate');
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

    function getdatakembali($intkembali){
        $this->db->select('a.intid, a.intpinjam, a.vckode, b.intgedung, b.intcell, b.intkaryawan, ISNULL(c.vcnama, 0) as vcgedung,
                            ISNULL(d.vcnama, 0) as vccell, ISNULL(e.vcnama, 0) as vckaryawan,
                            ISNULL(b.vckode, 0) as vckodepinjam, convert(varchar, b.dtpinjam, 6) as dtpinjam',false);
        $this->db->from('pr_kembali as a');
        $this->db->join('pr_pinjam as b','b.intid = a.intpinjam');
        $this->db->join('m_gedung as c','c.intid = b.intgedung');
        $this->db->join('m_cell as d','d.intid = b.intcell');
        $this->db->join('m_karyawan as e','e.intid = b.intkaryawan');
        $this->db->where('a.intid',$intkembali);

        return $this->db->get()->result();
    }

    function updatedatapinjam($intpinjam, $intpallet, $data){
        $this->db->where('intpinjam',$intpinjam);
        $this->db->where('intpallet',$intpallet);

        return $this->db->update('pr_pinjam_detail',$data);
    }

    // function getdatakembalipallet($intkembali){
    //     $this->db->select('ISNULL(c.vckode, 0) as vckode, ISNULL(d.vcnama, 0) as vcmodel, ISNULL(e.vcnama, 0) as vcproses,
    //                      ISNULL(f.vcnama, 0) as vcsize, ISNULL(g.vcnama, 0) as vcroom',false);
    //     $this->db->from('pr_kembali_detail as a');
    //     $this->db->join('pr_kembali as b','b.intid = a.intkembali');
    //     $this->db->join('m_pallet as c','c.intid = a.intpallet');
    //     $this->db->join('m_models as d','d.intid = c.intmodel');
    //     $this->db->join('m_proses as e','e.intid = c.intproses');
    //     $this->db->join('m_size as f','f.intid = c.intsize');
    //     $this->db->join('m_room as g','g.intid = a.introom');
    //     //$this->db->group_by('c.vckode, d.vcnama, e.vcnama, f.vcnama');
    //     $this->db->where('a.intkembali',$intkembali);

    //     return $this->db->get()->result();
    // }

    function getdatakembalipallet($intkembali){
        $this->db->select('a.intid, ISNULL(c.vckode, 0) as vckode, ISNULL(d.vcnama, 0) as vcmodel, 
                        ISNULL(e.vcnama, 0) as vcproses, ISNULL(f.vcnama, 0) as vcsize, ISNULL(g.vcnama, 0) as vckondisi, 
                        ISNULL(h.vcnama, 0) as vcuser, ISNULL(i.vcnama, 0) as vcroom',false);
        $this->db->from('pr_kembali_detail as a');
        $this->db->join('pr_kembali as b','b.intid = a.intkembali');
        $this->db->join('m_pallet as c','c.intid = a.intpallet');
        $this->db->join('m_models as d','d.intid = c.intmodel');
        $this->db->join('m_proses as e','e.intid = c.intproses');
        $this->db->join('m_size as f','f.intid = c.intsize');
        $this->db->join('m_kondisi as g','g.intid = a.intkondisi');
        $this->db->join('app_muser as h','h.intid = a.intuser');
        $this->db->join('m_room as i','i.intid = a.introom');
        $this->db->where('b.intid',$intkembali);

        return $this->db->get()->result();
    }

    function getdatapinjam($intpinjam){
        $this->db->select('a.intid, ISNULL(c.vckode, 0) as vckode, ISNULL(d.vcnama, 0) as vcmodel, ISNULL(e.vcnama, 0) as vcproses,
                         ISNULL(f.vcnama, 0) as vcsize',false);
        $this->db->from('pr_pinjam_detail as a');
        $this->db->join('pr_pinjam as b','b.intid = a.intpinjam');
        $this->db->join('m_pallet as c','c.intid = a.intpallet');
        $this->db->join('m_models as d','d.intid = c.intmodel');
        $this->db->join('m_proses as e','e.intid = c.intproses');
        $this->db->join('m_size as f','f.intid = c.intsize');
        $this->db->where('b.intid',$intpinjam);

        return $this->db->get()->result();
    }

    function getdataroom($intgedung){
        $this->db->select('a.intid, a.vcnama',false);
        $this->db->from('m_room as a');
        $this->db->join('m_rak as b','b.intid = a.intrak');
        $this->db->where('b.intgedung',$intgedung);

        return $this->db->get()->result();
    }

    function getdatadetailpallet($vckode, $intkembali){
        $this->db->select('a.intid',false);
        $this->db->from('pr_kembali_detail as a');
        $this->db->join('m_pallet as b','b.intid = a.intpallet');
        $this->db->where('b.vckode',$vckode);
        $this->db->where('a.intkembali',$intkembali);

        return $this->db->get()->result();
    }

    function getdatapinjampallet($vckode, $intkembali){
        $this->db->select('a.intpinjam, b.intpallet, b.intstatus',false);
        $this->db->from('pr_kembali as a');
        $this->db->join('pr_pinjam_detail as b','b.intpinjam = a.intpinjam');
        $this->db->join('m_pallet as c','c.intid = b.intpallet');
        $this->db->where('c.vckode',$vckode);
        $this->db->where('a.intid',$intkembali);

        return $this->db->get()->result();
    }
}