<?php
class Branch_model extends CI_Model {
    
    public $title;
    public $content;
    public $date;
    private $_ci;
    var $table = 'branch';
    var $column_order = array('branch_id', 'branch_name', 'address'); //set column field database for datatable orderable
    var $column_search = array('branch_id', 'branch_name', 'address'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('branch_name' => 'ASC'); // default order 
    
    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->_ci =& get_instance();
        $this->load->database();
    }
    
    private function _get_datatables_query($company_id='')
    {
         $this->db->where('company_id',$company_id);
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
 
    function get_datatables($company_id='')
    {
        $this->_get_datatables_query($company_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($company_id='')
    {
        $this->_get_datatables_query($company_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($company_id='')
    {
        $this->db->where('company_id',$company_id);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
 
    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('branch_id', $id);
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
 
    public function delete_by_id($id)
    {
        $this->db->where('branch_id', $id);
        $this->db->delete($this->table);
    }
    
    public function getBranch($fields=null, $limit=null, $where=null, $orderby=null) {
        ($fields != null) ? $this->db->select($fields) : '';
        ($where != null) ? $this->db->where($where) : '';
        ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
        ($orderby != null) ? $this->db->order_by($orderby) : '';
        return $this->db->get($this->table);
    }

    public function getBranchOnFilter($cond, $limit=null, $orderby=null) {
        $this->db->like($cond);
        ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
        ($orderby != null) ? $this->db->order_by($orderby) : '';
        return $this->db->get($this->table);
    }

    public function insertBranch($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function deleteBranch($id)
    {
        $this->db->where('branch_id', $id);
        $this->db->delete($this->table);
    }

    public function getBranchById($id)
    {
        $this->db->where('branch_id', $id);
        return $this->db->get($this->table);
    }

    public function updateBranch($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function idexists($id) {
        $opt = array('branch_id'=>$id);
        $q = $this->db->getwhere($this->table, $opt);
        $result = false;
        if ($q->num_rows() > 0) {
          $result = true;
        }
        $q->free_result();
        return $result;
    }

    public function get_Branch_flexigrid()
    {
        //Build contents query
	$this->db->select('*')->from($this->table);
        $this->_ci->flexigrid->build_query();

	$return['records'] = $this->db->get();
        
        $this->db->select('*')->from($this->table);
        $this->_ci->flexigrid->build_query();
        $query = $this->db->get();
        $return['record_count'] = count($query->result());

	return $return;
    }
    
    function getBranchList($category)
    {	
        $this->db->select('branch_id, branch_name');
        $this->db->order_by('branch_name', 'asc');
        $this->db->where('company_id', $category);
        $query = $this->db->get($this->table);

        $Sub_categories = array();
        $Sub_categories[0] = 'All';
        if($query->result()){
            foreach ($query->result() as $Branch) {
                $Sub_categories[$Branch->branch_id] = $Branch->branch_name;
            }
        }
        return $Sub_categories;
    }
    
    public function get_Branch_all()
    {
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
    
    public function get_Branch_by_company($company)
    {
        
        $this->db->where('company_id', $company);
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
}
?>