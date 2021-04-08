<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    //model pinjam
    function getjmldata($keyword='',$intgedung){
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
    
    function getdatalimit($halaman=0, $limit=5, $keyword='', $intgedung){
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

    function getdatapallet($intgedung) {
        $this->db->select('sum(a.intqty) as intqty');
        $this->db->from('m_pallet as a');
        $this->db->where('a.intgedung',$intgedung);
        return $this->db->get()->result();
    }

    function getdatapinjam($intgedung) {
        $this->db->select('count(a.intid) as inttotal');
        $this->db->from('pr_pinjam_detail as a');
        $this->db->join('pr_pinjam' . ' as b', 'b.intid = a.intpinjam', 'left');
        $this->db->where('b.intgedung',$intgedung);
        return $this->db->get()->result();
    }

    function getdatapinjamtoday($intgedung, $day, $month, $year) {
        $this->db->select('count(a.intid) as inttotal');
        $this->db->from('pr_pinjam_detail as a');
        $this->db->join('pr_pinjam' . ' as b', 'b.intid = a.intpinjam', 'left');
        $this->db->where('b.intgedung',$intgedung);
        $this->db->where('DAY(b.dtpinjam) = '.$day);
        $this->db->where('MONTH(b.dtpinjam) = '.$month);
        $this->db->where('YEAR(b.dtpinjam) = '.$year);
        return $this->db->get()->result();
    }

    function getdatakembali($intgedung) {
        $this->db->select('count(a.intid) as inttotal');
        $this->db->from('pr_kembali_detail as a');
        $this->db->join('pr_kembali' . ' as b', 'b.intid = a.intkembali', 'left');
        $this->db->where('b.intgedung',$intgedung);
        return $this->db->get()->result();
    }

    function getdatakembalitoday($intgedung, $day, $month, $year) {
        $this->db->select('count(a.intid) as inttotal');
        $this->db->from('pr_kembali_detail as a');
        $this->db->join('pr_kembali' . ' as b', 'b.intid = a.intkembali', 'left');
        $this->db->where('b.intgedung',$intgedung);
        $this->db->where('DAY(b.dtkembali) = '.$day);
        $this->db->where('MONTH(b.dtkembali) = '.$month);
        $this->db->where('YEAR(b.dtkembali) = '.$year);
        return $this->db->get()->result();
    }

    function getdataperbaikan($intgedung) {
        $this->db->select('count(a.intid) as inttotal');
        $this->db->from('pr_perbaikan as a');
        $this->db->join('m_pallet' . ' as b', 'b.intid = a.intpallet', 'left');
        $this->db->where('b.intgedung',$intgedung);
        return $this->db->get()->result();
    }

    function getdataperbaikantoday($intgedung, $day, $month, $year) {
        $this->db->select('count(a.intid) as inttotal');
        $this->db->from('pr_perbaikan as a');
        $this->db->join('m_pallet' . ' as b', 'b.intid = a.intpallet', 'left');
        $this->db->where('b.intgedung',$intgedung);
        $this->db->where('DAY(a.dtadd) = '.$day);
        $this->db->where('MONTH(a.dtadd) = '.$month);
        $this->db->where('YEAR(a.dtadd) = '.$year);
        return $this->db->get()->result();
    }

    function getdatarusak($intgedung) {
        $this->db->select('count(a.intid) as inttotal');
        $this->db->from('pr_rusak as a');
        $this->db->join('m_pallet' . ' as b', 'b.intid = a.intpallet', 'left');
        $this->db->where('b.intgedung',$intgedung);
        return $this->db->get()->result();
    }

    function getdatarusaktoday($intgedung, $day, $month, $year) {
        $this->db->select('count(a.intid) as inttotal');
        $this->db->from('pr_rusak as a');
        $this->db->join('m_pallet' . ' as b', 'b.intid = a.intpallet', 'left');
        $this->db->where('b.intgedung',$intgedung);
        $this->db->where('DAY(a.dtadd) = '.$day);
        $this->db->where('MONTH(a.dtadd) = '.$month);
        $this->db->where('YEAR(a.dtadd) = '.$year);
        return $this->db->get()->result();
    }

    
}