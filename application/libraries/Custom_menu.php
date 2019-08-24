<?php
class custom_menu {
 
    function custom_menu()
    {
        $this->CI=& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->helper('form');
        $this->CI->load->helper('url');   
        //$this->CI->load->library('aad_auth');
    }
    
    function build_menu()
    {
        $role_id = $this->CI->session->userdata("role");
        
        $query = $this->CI->db->query("select dyn_menu.*
                                       from role_menu, dyn_menu
                                       where role_id = $role_id
                                             and role_menu.menu_id = dyn_menu.id
                                       order by page_id");
 
        // now we will build the dynamic menus.
        //$html_out  = "\t".'<div '.$this->id_menu.'>'."\n";
        $html_out='';
        foreach ($query->result() as $row){
            $html_out .= '<li class="nav-item active"><a class="nav-link" href="'.base_url().$row->url.'">'.$row->title.'</a></li>';
        }
        
        return $html_out;
    }
    
    function build_menu_2()
    {
        $role_id = $this->CI->session->userdata("role");
        
        $query = $this->CI->db->query("select dyn_menu.*
                                       from role_menu, dyn_menu
                                       where role_id = $role_id
                                             and role_menu.menu_id = dyn_menu.id
                                       order by page_id");
 
        $html_out='';
        foreach ($query->result() as $row){
            //$html_out .= '<li class="nav-item active"><a class="nav-link" href="'.base_url().$row->url.'">'.$row->title.'</a></li>';
            $html_out .= '&nbsp;<a style="text-decoration: none;" href="'.base_url().$row->url.'"><font color="white">'.$row->title.'</font></a>&nbsp;&nbsp;';
        }
        
        return $html_out;
    }
    
}
?>
