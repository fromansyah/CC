<?php
class Master_data_model extends CI_Model {
    
    public $title;
    public $content;
    public $date;
    private $_ci;
    var $table = 'master_data';
    var $column_order = array('id', 'value', 'type', 'name', null); //set column field database for datatable orderable
    var $column_search = array('id', 'value', 'type', 'name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('value' => 'ASC'); // default order 
    
    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->_ci =& get_instance();
        $this->load->database();
    }
    
    private function _get_datatables_query($type)
    {
        $this->db->where('type',$type);
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
 
    function get_datatables($type='')
    {
        $this->_get_datatables_query($type);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($type='')
    {
        $this->_get_datatables_query($type);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($type='')
    {
        $this->db->where('type',$type);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
 
    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where("id = $id");
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
        $this->db->where("id = $id");
        $this->db->delete($this->table);
    }
    
    public function getMasterData($fields=null, $limit=null, $where=null, $orderby=null) {
        ($fields != null) ? $this->db->select($fields) : '';
        ($where != null) ? $this->db->where($where) : '';
        ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
        ($orderby != null) ? $this->db->order_by($orderby) : '';
        return $this->db->get($this->table);
    }

    public function getMasterDataOnFilter($cond, $limit=null, $orderby=null) {
        $this->db->like($cond);
        ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
        ($orderby != null) ? $this->db->order_by($orderby) : '';
        return $this->db->get($this->table);
    }

    public function insertMasterData($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function deleteMasterData($id)
    {
        $this->db->where("id = $id");
        $this->db->delete($this->table);
    }

    public function getMasterDataById($value, $type)
    {
        $this->db->where("value = '$value' and type = '$type'");
        return $this->db->get($this->table);
    }

    public function updateMasterData($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function idexists($value, $type) {
        $opt = array('value'=> $value, 'type'=> $type);
        $q = $this->db->getwhere($this->table, $opt);
        $result = false;
        if ($q->num_rows() > 0) {
          $result = true;
        }
        $q->free_result();
        return $result;
    }

    public function get_master_data_flexigrid()
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
    
    function getMasterDataList($type)
    {	
        $this->db->select('value, name');
        $this->db->where("type = '$type'");
        $this->db->order_by('name', 'asc'); 
        $query = $this->db->get($this->table);

        $master_datas = array();
        
        if($query->result()){
            foreach ($query->result() as $master_data) {
                $master_datas[$master_data->value] = $master_data->name;
            }
        }
        return $master_datas;
    }
    
    function getMasterDataListForReport($type)
    {	
        $this->db->select('value, name');
        $this->db->where("type = '$type'");
        $this->db->order_by('name', 'asc'); 
        $query = $this->db->get($this->table);

        $master_datas = array();
        $master_datas[-1] = 'All';
        if($query->result()){
            foreach ($query->result() as $master_data) {
                $master_datas[$master_data->value] = $master_data->name;
            }
        }
        return $master_datas;
    }
    
    function getMasterDataName($value, $type){
        $this->db->select('name');
        $this->db->where("value = $value and type = '$type'");
        $query = $this->db->get($this->table)->result();
        
        return $query[0]->name;
        
    }
}
?>