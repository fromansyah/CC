<?php
class Contract_model extends CI_Model {
    
    public $title;
    public $content;
    public $date;
    private $_ci;
    var $table = 'contract';
    var $column_order = array('contract_no', 'exp_date', 'other_party', 'description', 'emp_check.emp_name', 'emp_req.emp_name', 'emp_req_2.emp_name', 'doc_name', 'name', null); //set column field database for datatable orderable
    var $column_search = array('contract_no', 'exp_date', 'other_party', 'description', 'emp_check.emp_name', 'emp_req.emp_name', 'emp_req_2.emp_name', 'doc_name', 'name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('contract_id' => 'desc'); // default order 
    
    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->_ci =& get_instance();
        $this->load->database();
    }
    
    private function _get_datatables_query($division)
    {
        $this->db->select('*, DATEDIFF(exp_date, now()) as due_date, emp_check.emp_name as check_name, emp_req.emp_name as req_name, emp_req_2.emp_name as req_name_2');
        $this->db->from($this->table);
        $this->db->join('employee as emp_check', 'check_by = emp_check.emp_id', 'left');
        $this->db->join('employee as emp_req', 'requestor = emp_req.emp_id', 'left');
        $this->db->join('employee as emp_req_2', 'req_2 = emp_req_2.emp_id', 'left outer');
        $this->db->join('master_data', "status = value and type='CONTRACT_STATUS'", 'left');
        $this->db->where('division_id', $division);
 
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
 
    function get_datatables($division)
    {
        $this->_get_datatables_query($division);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($division)
    {
        $this->_get_datatables_query($division);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($division)
    {
        $this->db->from($this->table);
        $this->db->join('employee', 'check_by = emp_id', 'left');
        $this->db->where('division_id', $division);
        return $this->db->count_all_results();
    }
 
    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('contract_id', $id);
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
        $this->db->where('contract_id', $id);
        $this->db->delete($this->table);
    }
    
    public function getContract($fields=null, $limit=null, $where=null, $orderby=null) {
        ($fields != null) ? $this->db->select($fields) : '';
        ($where != null) ? $this->db->where($where) : '';
        ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
        ($orderby != null) ? $this->db->order_by($orderby) : '';
        return $this->db->get($this->table);
    }

    public function getContractOnFilter($cond, $limit=null, $orderby=null) {
        $this->db->like($cond);
        ($limit != null) ? $this->db->limit($limit['start'], $limit['end']) : '';
        ($orderby != null) ? $this->db->order_by($orderby) : '';
        return $this->db->get($this->table);
    }

    public function insertContract($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function deleteContract($id)
    {
        $this->db->where('contract_id', $id);
        $this->db->delete($this->table);
    }

    public function getContractById($id)
    {
        $this->db->where('contract_id', $id);
        return $this->db->get($this->table);
    }

    public function updateContract($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function idexists($id) {
        $opt = array('contract_id'=>$id);
        $q = $this->db->getwhere($this->table, $opt);
        $result = false;
        if ($q->num_rows() > 0) {
          $result = true;
        }
        $q->free_result();
        return $result;
    }

    public function get_contract_flexigrid()
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
    
    function getAllContractList()
    {	
        $this->db->select('contract_id, contract_name');
        $this->db->order_by('contract_name', 'asc'); 
        $query = $this->db->get($this->table);

        $contracts = array();
        $contracts[0] = 'Null Value';
        if($query->result()){
            foreach ($query->result() as $contract) {
                $contracts[$contract->contract_id] = '['.$contract->contract_id.'] '.$contract->contract_name;
            }
        }
        return $contracts;
    }
    
    function getAllContractListSearch()
    {	
        $this->db->select('contract_id, contract_name');
        $this->db->order_by('contract_name', 'asc'); 
        $query = $this->db->get($this->table);

        $contracts = array();
        $contracts[0] = 'Null Value';
        if($query->result()){
            foreach ($query->result() as $contract) {
                $contracts[$contract->contract_id] = $contract->contract_name;
            }
        }
        return $contracts;
    }
    
    
    function getOtherPartyList(){
        $query = $this->db->query("select distinct other_party
                                   from contract");
        
        $other_party = array();
        if($query->result()){
            foreach ($query->result() as $contract) {
                $other_party[$contract->other_party] = $contract->other_party;
            }
        }
        
        return $other_party;
    }

    public function getContractSummary($id)
    {
        $this->db->select('contract.*, division_name, company_name, company.address, emp_rep.emp_name as rep_name, emp_rep.position as rep_title, emp_check.emp_name as check_by, emp_ack.emp_name as ack_by, emp_req.emp_name as req_by, emp_req_2.emp_name as req_by_2, emp_req_3.emp_name as req_by_3, emp_req_4.emp_name as req_by_4, emp_req_5.emp_name as req_by_5, emp_app.emp_name as app_by, TIMESTAMPDIFF(MONTH, eff_date, exp_date) as period, FLOOR(TIMESTAMPDIFF(MONTH, eff_date, exp_date)/12) as period_year, TIMESTAMPDIFF(MONTH, eff_date, exp_date)%12 as period_month');
        $this->db->from($this->table);
        $this->db->join('employee as emp_rep', 'fp_rep = emp_rep.emp_id', 'left');
        $this->db->join('employee as emp_check', 'check_by = emp_check.emp_id', 'left');
        $this->db->join('employee as emp_ack', 'ack_by = emp_ack.emp_id', 'left');
        $this->db->join('employee as emp_req', 'requestor = emp_req.emp_id', 'left');
        $this->db->join('employee as emp_req_2', 'req_2 = emp_req_2.emp_id', 'left outer');
        $this->db->join('employee as emp_req_3', 'req_3 = emp_req_3.emp_id', 'left outer');
        $this->db->join('employee as emp_req_4', 'req_4 = emp_req_4.emp_id', 'left outer');
        $this->db->join('employee as emp_req_5', 'req_5 = emp_req_5.emp_id', 'left outer');
        $this->db->join('employee as emp_app', 'approved_by = emp_app.emp_id', 'left');
        $this->db->join('company', 'first_party = company_id', 'left');
        $this->db->join('division', 'contract.division_id = division.division_id', 'left');
        $this->db->where('contract_id', $id);
        return $this->db->get();
    }

    public function getContractReminder()
    {
        $this->db->select("contract.*, division_name, company_name, company.address, emp_rep.emp_name as rep_name, 
                           emp_rep.position as rep_title, emp_check.emp_name as check_by, emp_ack.emp_name as ack_by, 
                           emp_req.emp_name as req_by, emp_req_2.emp_name as req_by_2, emp_req_3.emp_name as req_by_3, emp_req_4.emp_name as req_by_4, emp_req_5.emp_name as req_by_5, emp_app.emp_name as app_by, TIMESTAMPDIFF(MONTH, eff_date, exp_date) as period, 
                           FLOOR(TIMESTAMPDIFF(MONTH, eff_date, exp_date)/12) as period_year, TIMESTAMPDIFF(MONTH, eff_date, exp_date)%12 as period_month, 
                           master_data.name as status_name, datediff(exp_date, now()) as due_date,
                           case
                            when datediff(exp_date, now()) = 90 then '1st reminder (90 days before expire)'
                            when datediff(exp_date, now()) = 60 then '2nd reminder (60 days before expire)'
                            when datediff(exp_date, now()) = 30 then '3rd reminder (30 days before expire)'
                            when datediff(exp_date, now()) = 0 then 'Expired'
                           end as reminder");
        $this->db->from($this->table);
        $this->db->join('employee as emp_rep', 'fp_rep = emp_rep.emp_id', 'left');
        $this->db->join('employee as emp_check', 'check_by = emp_check.emp_id', 'left');
        $this->db->join('employee as emp_ack', 'ack_by = emp_ack.emp_id', 'left');
        $this->db->join('employee as emp_req', 'requestor = emp_req.emp_id', 'left');
        $this->db->join('employee as emp_req_2', 'req_2 = emp_req_2.emp_id', 'left outer');
        $this->db->join('employee as emp_req_3', 'req_3 = emp_req_3.emp_id', 'left outer');
        $this->db->join('employee as emp_req_4', 'req_4 = emp_req_4.emp_id', 'left outer');
        $this->db->join('employee as emp_req_5', 'req_5 = emp_req_5.emp_id', 'left outer');
        $this->db->join('employee as emp_app', 'approved_by = emp_app.emp_id', 'left');
        $this->db->join('company', 'first_party = company_id', 'left');
        $this->db->join('division', 'contract.division_id = division.division_id', 'left');
        $this->db->join('master_data', "contract.status = master_data.value and master_data.type = 'CONTRACT_STATUS'", 'left');
        $this->db->where("(datediff(exp_date, now()) = 90
                          or datediff(exp_date, now()) = 60
                          or datediff(exp_date, now()) = 30
                          or datediff(exp_date, now()) = 0)
                          and status in (0, 1)");
        $this->db->order_by("due_date", "asc");
        return $this->db->get();
    }

    public function getContractSummaryReport($status, $from, $to, $cutoff)
    {
        $new_status = $this->db->escape($status);
        $from = $this->db->escape($from);
        $to = $this->db->escape($to);
        $cutoff = $this->db->escape($cutoff);
        
        $where = '';
        if($status != -1){
            $where .= "contract.status = $new_status
                       and exp_date >= $from
                       and exp_date <= $to";
        }else{
            $where .= "exp_date >= $from
                       and exp_date <= $to";
        }
        
        $this->db->select("contract.*, division_name, company_name, company.address, emp_rep.emp_name as rep_name, 
                           emp_rep.position as rep_title, emp_check.emp_name as check_by, emp_ack.emp_name as ack_by, 
                           emp_req.emp_name as req_by, emp_req_2.emp_name as req_by_2, emp_req_3.emp_name as req_by_3, emp_req_4.emp_name as req_by_4, emp_req_5.emp_name as req_by_5, emp_app.emp_name as app_by, TIMESTAMPDIFF(MONTH, eff_date, exp_date) as period, 
                           FLOOR(TIMESTAMPDIFF(MONTH, eff_date, exp_date)/12) as period_year, TIMESTAMPDIFF(MONTH, eff_date, exp_date)%12 as period_month, 
                           master_data.name as status_name, datediff(exp_date, $cutoff) as due_date");
        $this->db->from($this->table);
        $this->db->join('employee as emp_rep', 'fp_rep = emp_rep.emp_id', 'left');
        $this->db->join('employee as emp_check', 'check_by = emp_check.emp_id', 'left');
        $this->db->join('employee as emp_ack', 'ack_by = emp_ack.emp_id', 'left');
        $this->db->join('employee as emp_req', 'requestor = emp_req.emp_id', 'left');
        $this->db->join('employee as emp_req_2', 'req_2 = emp_req_2.emp_id', 'left outer');
        $this->db->join('employee as emp_req_3', 'req_3 = emp_req_3.emp_id', 'left outer');
        $this->db->join('employee as emp_req_4', 'req_4 = emp_req_4.emp_id', 'left outer');
        $this->db->join('employee as emp_req_5', 'req_5 = emp_req_5.emp_id', 'left outer');
        $this->db->join('employee as emp_app', 'approved_by = emp_app.emp_id', 'left');
        $this->db->join('company', 'first_party = company_id', 'left');
        $this->db->join('division', 'contract.division_id = division.division_id', 'left');
        $this->db->join('master_data', "contract.status = master_data.value and master_data.type = 'CONTRACT_STATUS'", 'left');
        $this->db->where($where);
        $this->db->order_by("due_date", "asc");
        return $this->db->get();
    }

    public function getContractDueDateReport($status, $day, $cutoff)
    {
        $new_status = $this->db->escape($status);
        $day = $this->db->escape($day);
        $cutoff = $this->db->escape($cutoff);
        
        $where = '';
        if($status != -1){
            $where .= "contract.status = $new_status
                       and datediff(exp_date, $cutoff) = $day";
        }else{
            $where .= "datediff(exp_date, $cutoff) = $day";
        }
        
        $this->db->select("contract.*, division_name, company_name, company.address, emp_rep.emp_name as rep_name, 
                           emp_rep.position as rep_title, emp_check.emp_name as check_by, emp_ack.emp_name as ack_by, 
                           emp_req.emp_name as req_by, emp_req_2.emp_name as req_by_2, emp_req_3.emp_name as req_by_3, emp_req_4.emp_name as req_by_4, emp_req_5.emp_name as req_by_5, emp_app.emp_name as app_by, TIMESTAMPDIFF(MONTH, eff_date, exp_date) as period, 
                           FLOOR(TIMESTAMPDIFF(MONTH, eff_date, exp_date)/12) as period_year, TIMESTAMPDIFF(MONTH, eff_date, exp_date)%12 as period_month, 
                           master_data.name as status_name, datediff(exp_date, $cutoff) as due_date");
        $this->db->from($this->table);
        $this->db->join('employee as emp_rep', 'fp_rep = emp_rep.emp_id', 'left');
        $this->db->join('employee as emp_check', 'check_by = emp_check.emp_id', 'left');
        $this->db->join('employee as emp_ack', 'ack_by = emp_ack.emp_id', 'left');
        $this->db->join('employee as emp_req', 'requestor = emp_req.emp_id', 'left');
        $this->db->join('employee as emp_req_2', 'req_2 = emp_req_2.emp_id', 'left outer');
        $this->db->join('employee as emp_req_3', 'req_3 = emp_req_3.emp_id', 'left outer');
        $this->db->join('employee as emp_req_4', 'req_4 = emp_req_4.emp_id', 'left outer');
        $this->db->join('employee as emp_req_5', 'req_5 = emp_req_5.emp_id', 'left outer');
        $this->db->join('employee as emp_app', 'approved_by = emp_app.emp_id', 'left');
        $this->db->join('company', 'first_party = company_id', 'left');
        $this->db->join('division', 'contract.division_id = division.division_id', 'left');
        $this->db->join('master_data', "contract.status = master_data.value and master_data.type = 'CONTRACT_STATUS'", 'left');
        $this->db->where($where);
        $this->db->order_by("due_date", "asc");
        return $this->db->get();
    }
}
?>