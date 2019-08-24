<?php
class Users_model extends CI_Model {
    public $title;
    public $content;
    public $date;
    
    public function __construct(){
        parent::__construct();
        $this->_table='user';
    }
    
    function getUsers($fields=null, $limit=null, $where=null, $orderby=null) {
        ($fields != null) ? $this->db->select($fields) : '';
        ($where != null) ? $this->db->where($where) : '';
        ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
        ($orderby != null) ? $this->db->order_by($orderby) : '';
        return $this->db->get($this->_table);
    }

    function getUsersOnFilter($cond, $limit=null, $orderby=null) {
        $this->db->like($cond);
        ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
        ($orderby != null) ? $this->db->order_by($orderby) : '';
        return $this->db->get($this->_table);
    }

    function insertUsers($data)
    {
        $this->db->insert($this->_table, $data);
    }

    function deleteUsers($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->_table);
    }

    function getUsersById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->_table);
    }

    function updateUsers($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->_table, $data);
        return $this->db->affected_rows();
    }

    function idexists($id) {
        $opt = array('id'=>$id);
        $q = $this->db->getwhere('username', $opt);
        $result = false;
        if ($q->num_rows() > 0) {
          $result = true;
        }
        $q->free_result();
        return $result;
    }

    public function get_users_flexigrid()
    {
          //Select table name
          $table_name = "user";

          //Build contents query
          $this->db->select('*')->from($table_name);
          $this->CI->flexigrid->build_query();

          //Get contents
          $return['records'] = $this->db->get();

          //Build count query
          $this->db->select("count(id) as record_count")->from($table_name);
          $this->CI->flexigrid->build_query(FALSE);
          $record_count = $this->db->get();
          $row = $record_count->row();

          //Get Record Count
          $return['record_count'] = $row->record_count;

          //Return all
          return $return;
    }


    function login($username, $password) {
        $username = $this->db->escape($username);
        $password = $this->db->escape($password);

        $result = $this->db->query("SELECT a.id,
                                    a.username,
                                    a.fullname,
                                    a.user_group,
                                    a.role
                                    FROM user a
                                    WHERE a.username=$username
                                          AND a.password=$password");

        //return false;
        if ($result->num_rows() == 0) {
            return false;
        } else {
            $result = $result->row();
            $this->session->set_userdata('user_id',$result->id);
            $this->session->set_userdata('username',$result->username);
            $this->session->set_userdata('fullname',$result->fullname);
            $this->session->set_userdata('user_group',$result->user_group);
            $this->session->set_userdata('role',$result->role);

            if($result->user_group == 'ADMIN'){
                $this->session->set_userdata('mode','USER');
                $this->session->set_userdata('template','user_utama');
                $this->session->set_userdata('edit_template','edit');
            }else{
                $this->session->set_userdata('mode','USER');
                $this->session->set_userdata('template','user_utama');
                $this->session->set_userdata('edit_template','user_edit');
            }
            
            return true;
        }
    }

    function list_login($emp_id){
        //$result = $this->db->query("select user.id, user.username from user where id not in (select distinct(login_id) from employee) union select user.id, user.username from user, employee where id = login_id and EMPLOYEE_ID = $emp_id");
        $result = $this->db->query("select * from user where user.id not in (select distinct(login_id) from employee where employee.employee_id <> $emp_id)");

        return $result;
    }


    function test(){

        $result = $this->db->query("select a.id,
                                    a.username,
                                    a.fullname,
                                    a.user_group
                                    FROM user a");

        return $result->result();
    }
    
    function getRoleMenu($url, $role){
        $query = $this->db->query("select role.id, role.role, role_menu.menu_id, dyn_menu.url
                                   from role, role_menu, dyn_menu
                                   where role.id = $role
                                         and role.id = role_menu.role_id
                                         and role_menu.menu_id = dyn_menu.id
                                         and dyn_menu.url = '$url'");
                                         
        return $query->result();
    }
    
    function email_login($email) {
        $email = $this->db->escape($email);

        $result = $this->db->query("SELECT a.id,
                                    a.username,
                                    a.fullname,
                                    a.user_group,
                                    a.role
                                    FROM user a
                                    WHERE a.email=$email");

        //return false;
        if ($result->num_rows() == 0) {
            return false;
        } else {
            $result = $result->row();
            $this->session->set_userdata('user_id',$result->id);
            $this->session->set_userdata('username',$result->username);
            $this->session->set_userdata('fullname',$result->fullname);
            $this->session->set_userdata('user_group',$result->user_group);
            $this->session->set_userdata('role',$result->role);
            $this->session->set_userdata('csrf_token',md5(uniqid(rand(), true)));

            if($result->user_group == 'ADMIN'){
                $this->session->set_userdata('mode','USER');
                $this->session->set_userdata('template','user_utama');
                $this->session->set_userdata('edit_template','edit');
            }else{
                $this->session->set_userdata('mode','USER');
                $this->session->set_userdata('template','user_utama');
                $this->session->set_userdata('edit_template','user_edit');
            }
            
            return true;
        }
    }
}
?>