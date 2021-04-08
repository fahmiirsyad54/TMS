<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AksesModel extends CI_Model {

    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getdata($table){
        return $this->db->get($table)->result();
    }

    function auth($vcusername,$vcpassword){
      // $SU   = 'SU';
      // $TD   = 'TD';
      // $PROD = 'PROD';
      // $DEV  = 'DEV';
      // $access_condition = "((b.vckode = '".$SU."') or (b.vckode = '".$TD."') or (b.vckode = '".$PROD."') or (b.vckode = '".$DEV."'))";
      // $this->db->select('a.intid, a.vcusername, a.vcpassword, a.intgedung, b.vckode as vckodehakakses');
      // $this->db->from('app_muser as a');
      // $this->db->join('app_mhakakses as b','b.intid = a.inthakakses');

      $this->db->where('vcusername',$vcusername);
    	$this->db->where('vcpassword',$vcpassword);
        $this->db->where('intstatus',1);
    	return $this->db->get('app_muser')->result();
    }

    function authoee($vcusername,$vcpassword){
        $SU = 'SU';
        $TD = 'TD';
        $OEEME = 'OEEME';
        $OEEBD = 'OEEBD';
        $access_condition = "((b.vckode = '".$SU."') or (b.vckode = '".$TD."') or (b.vckode = '".$OEEME."') or (b.vckode = '".$OEEBD."'))";

        $this->db->select('a.intid, a.vcusername, a.vcpassword, a.intgedung, b.vckode as vckodehakakses');
        $this->db->from('app_muser as a');
        $this->db->join('app_mhakakses as b','b.intid = a.inthakakses');
        $this->db->where('a.vcusername',$vcusername);
        $this->db->where('a.vcpassword',$vcpassword);
        $this->db->where('a.intstatus',1);
        $this->db->where($access_condition);
        
        return $this->db->get()->result();
    }

    function authoee2($vcusername,$vcpassword){
        $SU = 'SU';
        $TD = 'TD';
        $OEEME = 'OEEME';
        $OEEBD = 'OEEBD';
        $access_condition = "((b.vckode = '".$SU."') or (b.vckode = '".$TD."') or (b.vckode = '".$OEEME."') or (b.vckode = '".$OEEBD."'))";

        $this->db->select('a.intid, a.vcusername, a.vcpassword, a.intgedung, b.vckode as vckodehakakses');
        $this->db->from('app_muser as a');
        $this->db->join('app_mhakakses as b','b.intid = a.inthakakses');
        $this->db->where('a.vcusername',$vcusername);
        $this->db->where('a.vcpassword',$vcpassword);
        $this->db->where('a.intstatus',1);
        $this->db->where($access_condition);
        
        return $this->db->get()->result();
    }

    function karyawanvalidasi($intmesin,$nik){
        $this->db->select('a.intid as intmesin, a.vckode as vckodemesin, a.intgedung as intgedung, a.intcell as intcell, b.intid as intkaryawan, b.vcnama as vckaryawan, b.vckode as vcnik',false);
        $this->db->from('m_mesin as a');
        $this->db->join('m_karyawan as b','a.intgedung = b.intgedung');
        $this->db->where('a.intid',$intmesin);
        $this->db->where('b.vckode',$nik);

        return $this->db->get()->result();
    }

    function getoperator($intmesin, $dttanggal, $intshift, $intlogin){
      $this->db->select('a.intid, a.intuser, a.intkaryawan, a.intshift, a.intlogin, a.dtlogin, a.intjamkerja, a.intjamlembur, c.vcnama as vcoperator, c.vckode as vcnik');
      $this->db->from('a_log_login a');
      $this->db->join('app_muser b', 'b.intid = a.intuser');
      $this->db->join('m_karyawan c', 'c.intid = a.intkaryawan');
      $this->db->where('convert(varchar(26),a.dtlogin,23)', $dttanggal);
      $this->db->where('a.intshift', $intshift);
      $this->db->where('a.intlogin', $intlogin);
      $this->db->where('b.intmesin', $intmesin);

      if ($intlogin == 1) {
        $this->db->order_by('a.intid','ASC');
        $this->db->limit(1);
      } else {
        $this->db->order_by('a.intid','DESC');
      }

      return $this->db->get()->result();
    }

    function getdatagedung(){
      $this->db->where('intoeemonitoring > 0');
      return $this->db->get('m_gedung')->result();
    }

    function getwaktu($intmesin, $date1, $date2, $intshift) {
      $this->db->select('intid, dttanggal, intmesin, intshift, CONVERT(varchar(8),ttemp,108) as ttemp, inttype');
      $this->db->from('temp_time');
      $this->db->where('intmesin', $intmesin);
      $this->db->where("dttanggal >= '$date1' AND dttanggal <= '$date2'");
      $this->db->where('intshift', $intshift);
      $this->db->order_by('intid','DESC');
      $this->db->limit(1);

      return $this->db->get()->result();
    }
}