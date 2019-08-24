<?php
class Role_menu_model extends CI_Model {
    public $title;
    public $content;
    public $date;
    private $_ci;
    var $table = 'role_menu';
    var $column_order = array('menu_id', null); //set column field database for datatable orderable
    var $column_search = array('menu_id'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('menu_id' => 'ASC');
    
    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->_ci =& get_instance();
        $this->load->database();
    }
 
    private function _get_datatables_query($role_id='')
    {
        $this->db->where('role_id',$role_id);
        $this->db->from($this->table);
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables($role_id = '')
    {
        $this->_get_datatables_query($role_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($role_id = '')
    {
        $this->_get_datatables_query($role_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($role_id = '')
    {
        $this->db->where('role_id',$role_id);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
 
    public function get_by_id($role_id, $menu_id)
    {
        $this->db->from($this->table);
        $this->db->where("role_id = $role_id and menu_id = $menu_id");
        $query = $this->db->get();
 
        return $query->row();
    }
 
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
 
    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }
 
    public function delete_by_id($role_id, $menu_id)
    {
        $this->db->where("role_id = $role_id and menu_id = $menu_id");
        $this->db->delete($this->table);
    }

    

  function getRoleMenu($fields=null, $limit=null, $where=null, $orderby=null) {
    ($fields != null) ? $this->db->select($fields) : '';
    ($where != null) ? $this->db->where($where) : '';
    ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
    ($orderby != null) ? $this->db->order_by($orderby) : '';
    return $this->db->get($this->table);
  }

  function getRoleMenuOnFilter($cond, $limit=null, $orderby=null) {
    $this->db->like($cond);
    ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
    ($orderby != null) ? $this->db->order_by($orderby) : '';
    return $this->db->get($this->table);
  }

  function insertRole_menu($data)
  {
    $this->db->insert($this->table, $data);
  }

  function deleteRoleMenu($role_id, $menu_id)
  {
    //$this->db->where('id', $id);
    $this->db->where("role_id = $role_id and menu_id = $menu_id");
    $this->db->delete($this->table);
  }

  function getRoleMenuById($role_id, $menu_id)
  {
    //$this->db->where('id', $id);
    $this->db->where("role_id = $role_id and menu_id = $menu_id");
    return $this->db->get($this->table);
  }

  function updateRoleMenu($where, $data)
  {
    $this->db->where($where);
    $this->db->update($this->table, $data);
    return $this->db->affected_rows();
  }
  
  public function get_roleMenu_flexigrid($role_id)
  {
	$this->db->select('*')->from($this->table)->where("role_id = $role_id");
        $this->_ci->flexigrid->build_query();

	$return['records'] = $this->db->get();
        
        $this->db->select('*')->from($this->table)->where("role_id = $role_id");
        $this->_ci->flexigrid->build_query();
        $query = $this->db->get();
        $return['record_count'] = count($query->result());

	return $return;
  }
}
?>