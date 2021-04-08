<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AboutModel extends CI_Model {

    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }

    // function default

    function getdatamenu($kode){
        $this->db->where('vccontroller', $kode);
        return $this->db->get('mmenu')->result();
    }

    function getdatalist($table, $keyword='',$parameter=''){
        $this->db->select('intid, ifnull(vcnama,"") as vcnama',false);
        if ($keyword != '' && $parameter != '') {
            $this->db->where($parameter, $keyword);
        }
        return $this->db->get($table)->result();
    }

    function getjmldata($table, $keyword=''){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        $this->db->like('a.vcnama', $keyword);
        return $this->db->get()->result();
    }

    function getdata($table, $keyword=''){
        $this->db->select('a.intid,
                            a.vcnama,
                            a.intstatus,
                            IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus,
                            IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid,
                            a.vcnama,
                            a.intstatus,
                            IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus,
                            IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->like('a.vcnama', $keyword);
      $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($intid){ 
        $this->db->select('a.*',false);
        $this->db->from('app_setting as a');
      $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    function getdatahistory($table,$intid,$limit=10){
      $this->db->select('a.dtupdate, b.vcnama as user, c.vcnama as aksi, c.vcwarna as warna',false);
      $this->db->from($table . ' as a');
    $this->db->join('muser as b', 'a.intupdate = b.intid');
    $this->db->join('maction as c', 'a.intaction = c.intid');
    $this->db->where('a.intheader', $intid);
    $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit);
    return $this->db->get()->result();
    }

    function insertdata($table,$data){
      return $this->db->insert($table,$data);
    }

    function updatedata($table,$data,$intid){
      $this->db->where('intid',$intid);
      return $this->db->update($table,$data);
    }

    function getdatadetailcustom($table,$keyword,$parameter){
        $this->db->where($parameter, $keyword);
        return $this->db->get($table)->result();
    }
    function validasi_password ($intid, $vcpassword) {
        $this->db->select('count(intid) as intpasswordcek');
        $this->db->where('intid',$intid);
        $this->db->where('vcpassword',$vcpassword);
        return $this->db->get('app_muser')->result();
    }

    // function custom

}