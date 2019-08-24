<?php
class License_model extends CI_Model {
    
    public $title;
    public $content;
    public $date;
    private $_ci;
    var $table = 'license';
    var $column_order = array('license_no', 'exp_date', 'license_name', 'issued_by', 'description', 'doc_name', 'name', null); //set column field database for datatable orderable
    var $column_search = array('license_no', 'exp_date', 'license_name', 'issued_by', 'description', 'doc_name', 'name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('license_id' => 'desc'); // default order 
    
    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->_ci =& get_instance();
        $this->load->database();
    }
    
    private function _get_datatables_query($branch)
    {
        $this->db->select('*, DATEDIFF(exp_date, now()) as due_date');
        $this->db->from($this->table);
        $this->db->join('master_data', "status = value and type='LICENSE_STATUS'", 'left');
        $this->db->where('branch_id', $branch);
 
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
 
    function get_datatables($branch)
    {
        $this->_get_datatables_query($branch);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($branch)
    {
        $this->_get_datatables_query($branch);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($branch)
    {
        $this->db->from($this->table);
        $this->db->join('master_data', "status = value and type='LICENSE_STATUS'", 'left');
        $this->db->where('branch_id', $branch);
        return $this->db->count_all_results();
    }
 
    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('license_id', $id);
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
        $this->db->where('license_id', $id);
        $this->db->delete($this->table);
    }
    
    public function getLicense($fields=null, $limit=null, $where=null, $orderby=null) {
        ($fields != null) ? $this->db->select($fields) : '';
        ($where != null) ? $this->db->where($where) : '';
        ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
        ($orderby != null) ? $this->db->order_by($orderby) : '';
        return $this->db->get($this->table);
    }

    public function getLicenseOnFilter($cond, $limit=null, $orderby=null) {
        $this->db->like($cond);
        ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
        ($orderby != null) ? $this->db->order_by($orderby) : '';
        return $this->db->get($this->table);
    }

    public function insertLicense($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function deleteLicense($id)
    {
        $this->db->where('license_id', $id);
        $this->db->delete($this->table);
    }

    public function getLicenseById($id)
    {
        $this->db->where('license_id', $id);
        return $this->db->get($this->table);
    }

    public function updateLicense($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function idexists($id) {
        $opt = array('license_id'=>$id);
        $q = $this->db->getwhere($this->table, $opt);
        $result = false;
        if ($q->num_rows() > 0) {
          $result = true;
        }
        $q->free_result();
        return $result;
    }

    public function get_license_flexigrid()
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
    
    function getAllLicenseList()
    {	
        $this->db->select('license_id, license_name');
        $this->db->order_by('license_name', 'asc'); 
        $query = $this->db->get($this->table);

        $licenses = array();
        $licenses[0] = 'Null Value';
        if($query->result()){
            foreach ($query->result() as $license) {
                $licenses[$license->license_id] = '['.$license->license_id.'] '.$license->license_name;
            }
        }
        return $licenses;
    }
    
    function getAllLicenseListSearch()
    {	
        $this->db->select('license_id, license_name');
        $this->db->order_by('license_name', 'asc'); 
        $query = $this->db->get($this->table);

        $licenses = array();
        $licenses[0] = 'Null Value';
        if($query->result()){
            foreach ($query->result() as $license) {
                $licenses[$license->license_id] = $license->license_name;
            }
        }
        return $licenses;
    }
    
    
    function getOtherPartyList(){
        $query = $this->db->query("select distinct issued_by
                                   from license");
        
        $other_party = array();
        if($query->result()){
            foreach ($query->result() as $license) {
                $other_party[$license->issued_by] = $license->issued_by;
            }
        }
        
        return $other_party;
    }

    public function getLicenseReminder()
    {
        $this->db->select("*, master_data.name as status_name, datediff(exp_date, now()) as due_date,
                           case
                            when datediff(exp_date, now()) = 90 then '1st reminder (90 days before expire)'
                            when datediff(exp_date, now()) = 60 then '2nd reminder (60 days before expire)'
                            when datediff(exp_date, now()) = 30 then '3rd reminder (30 days before expire)'
                            when datediff(exp_date, now()) = 0 then 'Expired'
                           end as reminder");
        $this->db->from($this->table);
        $this->db->join('company', 'license.company_id = company.company_id', 'left');
        $this->db->join('branch', 'license.branch_id = branch.branch_id', 'left');
        $this->db->join('master_data', "license.status = master_data.value and master_data.type = 'CONTRACT_STATUS'", 'left');
        $this->db->where("(datediff(exp_date, now()) = 90
                          or datediff(exp_date, now()) = 60
                          or datediff(exp_date, now()) = 30
                          or datediff(exp_date, now()) = 0)
                          and status in (0, 1)");
        $this->db->order_by("due_date", "asc");
        return $this->db->get();
    }

    public function getLicenseSummaryReport($status, $from, $to, $cutoff)
    {
        $new_status = $this->db->escape($status);
        $from = $this->db->escape($from);
        $to = $this->db->escape($to);
        $cutoff = $this->db->escape($cutoff);
        
        $where = '';
        if($status != -1){
            $where .= "license.status = $new_status
                       and exp_date >= $from
                       and exp_date <= $to";
        }else{
            $where .= "exp_date >= $from
                       and exp_date <= $to";
        }
        
        $this->db->select("*, master_data.name as status_name, datediff(exp_date, $cutoff) as due_date");
        $this->db->from($this->table);
        $this->db->join('company', 'license.company_id = company.company_id', 'left');
        $this->db->join('branch', 'license.branch_id = branch.branch_id', 'left');
        $this->db->join('master_data', "license.status = master_data.value and master_data.type = 'CONTRACT_STATUS'", 'left');
        $this->db->where($where);
        $this->db->order_by("due_date", "asc");
        return $this->db->get();
    }

    public function getLicenseDueDateReport($status, $day, $cutoff)
    {
        $new_status = $this->db->escape($status);
        $day = $this->db->escape($day);
        $cutoff = $this->db->escape($cutoff);
        
        $where = '';
        if($status != -1){
            $where .= "license.status = $new_status
                       and datediff(exp_date, $cutoff) = $day";
        }else{
            $where .= " datediff(exp_date, $cutoff) = $day";
        }
        
        $this->db->select("*, master_data.name as status_name, datediff(exp_date, $cutoff) as due_date");
        $this->db->from($this->table);
        $this->db->join('company', 'license.company_id = company.company_id', 'left');
        $this->db->join('branch', 'license.branch_id = branch.branch_id', 'left');
        $this->db->join('master_data', "license.status = master_data.value and master_data.type = 'CONTRACT_STATUS'", 'left');
        $this->db->where($where);
        $this->db->order_by("due_date", "asc");
        return $this->db->get();
    }
}
?>