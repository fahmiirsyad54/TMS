<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('AboutModel');
        $this->model        = $this->AboutModel;
        $this->intid        = $this->session->intid;
        $this->indexview    = '';
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $data['title']          = $this->title;
        $data['namaaplikasi']   = $this->modelapp->getappsetting('app-name')[0]->vcvalue;
        $data['versiapp']       = $this->modelapp->getappsetting('app-version')[0]->vcvalue;
        $data['deskripsi']       = $this->modelapp->getappsetting('app-description')[0]->vcvalue;
        $data['namaperusahaan'] = $this->modelapp->getappsetting('company-name')[0]->vcvalue;
        $data['alamat']         = $this->modelapp->getappsetting('company-address')[0]->vcvalue;
        $data['departement']    = $this->modelapp->getappsetting('company-departement')[0]->vcvalue;
        $data['team']           = $this->modelapp->getappsetting('company-team')[0]->vcvalue;
        
        $this->template->set_layout('default')->build($this->view . '/index'.$this->indexview,$data);
    }

}
