<?php

class Mymenu {

    private $ci;                // for CodeIgniter Super Global Reference.

    function __construct(){
        $this->ci =& get_instance();    // get a reference to CodeIgniter.
    }

    // --------------------------------------------------------------------

    function get_menu(){
        
        $datamenu = $this->get_data_menu();
        $html = '';

        foreach ($datamenu as $menu) {
            if ($menu->intis_header == 0) {
                $uri_menu = $this->ci->uri->segment(1);
                $active = ($uri_menu == $menu->vccontroller) ? 'active' : '' ;
                $html .= '<li class="' . $active . '">';
                $html .= '<a href="'. base_url($menu->vclink) .'"><i class="' . $menu->vcicon . '"></i> <span>'. $menu->vcnama .'</span></a>';
                $html .= '</li>';
            } else {
                $html .= '<li class="treeview '. $this->get_submenu($menu->intid)['open'] .'">';
                $html .= '<a href="#"><i class="' . $menu->vcicon . '"></i> <span>' . $menu->vcnama . '</span>';
                $html .= '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
                $html .= '<ul class="treeview-menu">';
                $html .= $this->get_submenu($menu->intid)['html'];
                $html .= '</ul>';
                $html .= '</li>';
            }
        }

        return $html;
    }  


    function get_submenu($intparent){
        $uri_menu = $this->ci->uri->segment(1);
        $datamenu = $this->get_data_menu($intparent);
        $html     = '';
        $open     = array();

        foreach ($datamenu as $menu) {
            if ($uri_menu == $menu->vccontroller) {
                array_push($open, 'active');
            }
            if ($menu->intis_header == 2) {
                $html .= '<li class="treeview '. $this->get_submenu2($menu->intid)['open'] .'">';
                $html .= '<a href="#"><i class="fa fa-circle-o"></i> <span>' . $menu->vcnama . '</span>';
                $html .= '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
                $html .= '<ul class="treeview-menu">';
                $html .= $this->get_submenu2($menu->intid)['html'];
                $html .= '</ul>';
                $html .= '</li>';
                if ($this->get_submenu2($menu->intid)['open'] != '') {
                    array_push($open, 'active');
                }
            } else {
                $active = ($uri_menu == $menu->vccontroller) ? 'active' : '' ;
                $html .= '<li class="'. $active .'">';
                $html .= '<a href="'. base_url($menu->vclink) .'"><i class="fa fa-circle-o"></i> '. $menu->vcnama .'</a>';
                $html .= '</li>';
            }
        }
        $preopen = (count($open) > 0) ? 'active' : '' ;
        $data = array(
                    'open' => $preopen,
                    'html' => $html
                );

        return $data;
    }

    function get_submenu2($intparent){
        $uri_menu = $this->ci->uri->segment(1);
        $datamenu = $this->get_data_menu($intparent);
        $html     = '';
        $open     = array();

        foreach ($datamenu as $menu) {
            if ($uri_menu == $menu->vccontroller) {
                array_push($open, 'active');
            }
            $active = ($uri_menu == $menu->vccontroller) ? 'active' : '' ;
            $html .= '<li class="'. $active .'">';
            $html .= '<a href="'. base_url($menu->vclink) .'"><i class="fa fa-circle-o"></i> '. $menu->vcnama .'</a>';
            $html .= '</li>';
        }
        $preopen = (count($open) > 0) ? 'active' : '' ;
        $data = array(
                    'open' => $preopen,
                    'html' => $html
                );

        return $data;
    }

    private function get_data_menu($intparent = 0) {
        $inthakakses = $this->ci->session->inthakakses;
        
        $this->ci->db->select('a.*');
        $this->ci->db->from('app_mmenu as a');
        $this->ci->db->join('app_mhakakses_menu as b','a.intid = b.intmenu');
        $this->ci->db->where(array('a.intparent' => $intparent, 'a.intstatus' => 1, 'b.intheader' => $inthakakses));
        $this->ci->db->order_by('a.intsorter asc, a.vcnama  asc');
        $query = $this->ci->db->get();

        return $query->result();
    }

    function get_app_setting($parameter){
        $data = $this->get_data_appsetting($parameter);

        return $data[0]->vcvalue;
    }

    private function get_data_appsetting($parameter) {
        $inthakakses = $this->ci->session->inthakakses;
        
        $this->ci->db->select('a.*');
        $this->ci->db->from('app_setting as a');
        $this->ci->db->where('vcnama',$parameter);
        $query = $this->ci->db->get();

        return $query->result();
    }
}
