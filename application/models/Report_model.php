<?php
class Report_model extends CI_Model {
    public $title;
    public $content;
    public $date;
    private $_ci;
    var $table = 'report';
    var $column_order = array('group', 'name', null); //set column field database for datatable orderable
    var $column_search = array('name', 'group'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('group, name' => 'ASC'); // default order 
    
    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->_ci =& get_instance();
        $this->load->database();
    }
    
    private function _get_datatables_query()
    {
         
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
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
 
    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id',$id);
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
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function insertReport($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function deleteReport($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function getReportById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table);
    }

    public function updateReport($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    function getAllReportList()
    {	
        $query = $this->db->get($this->table);

        $report_list = array();
        $report_list['#'] = 'Please Choose One';
                
        if($query->result()){
            foreach ($query->result() as $report) {
                $report_list[$report->id] = $report->name;
            }
        }
        return $report_list;
    }

    function getReportList()
    {	
        $this->db->order_by("name", "asc");
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    public function getNotificationList(){
//        $query = $this->db->query("select concat('Contract - 1st reminder notification(s) : ',count(*)) as notification,
//                                            'view_contract_reminder(90)' as link, 
//                                            'button_notif_warning' as class, 
//                                            count(*) as notif_count,
//                                            datediff(exp_date, now()) as due_date
//                                     from contract
//                                     where datediff(exp_date, now()) = 90
//                                     union all
//                                     select concat('Contract - 2st reminder notification(s) : ',count(*)) as notification,
//                                            'view_contract_reminder(60)' as link, 
//                                            'button_notif_warning' as class, 
//                                            count(*) as notif_count,
//                                            datediff(exp_date, now()) as due_date
//                                     from contract
//                                     where datediff(exp_date, now()) = 60
//                                     union all
//                                     select concat('Contract - 3st reminder notification(s) : ',count(*)) as notification,
//                                            'view_contract_reminder(30)' as link, 
//                                            'button_notif_warning' as class, 
//                                            count(*) as notif_count,
//                                            datediff(exp_date, now()) as due_date
//                                     from contract
//                                     where datediff(exp_date, now()) = 30
//                                     union all
//                                     select concat('Expired Contract notification(s) : ',count(*)) as notification,
//                                            'view_contract_reminder(0)' as link, 
//                                            'button_notif_warning' as class, 
//                                            count(*) as notif_count,
//                                            datediff(exp_date, now()) as due_date
//                                     from contract
//                                     where datediff(exp_date, now()) = 0
//                                     union all
//                                     select concat('License - 1st reminder notification(s) : ',count(*)) as notification,
//                                            'view_license_reminder(90)' as link, 
//                                            'button_notif_warning' as class, 
//                                            count(*) as notif_count,
//                                            datediff(exp_date, now()) as due_date
//                                     from license
//                                     where datediff(exp_date, now()) = 90
//                                     union all
//                                     select concat('License - 2st reminder notification(s) : ',count(*)) as notification,
//                                            'view_license_reminder(60)' as link, 
//                                            'button_notif_warning' as class, 
//                                            count(*) as notif_count,
//                                            datediff(exp_date, now()) as due_date
//                                     from license
//                                     where datediff(exp_date, now()) = 60
//                                     union all
//                                     select concat('License - 3st reminder notification(s) : ',count(*)) as notification,
//                                            'view_license_reminder(30)' as link, 
//                                            'button_notif_warning' as class, 
//                                            count(*) as notif_count,
//                                            datediff(exp_date, now()) as due_date
//                                     from license
//                                     where datediff(exp_date, now()) = 30
//                                     union all
//                                     select concat('Expired License notification(s) : ',count(*)) as notification,
//                                            'view_license_reminder(0)' as link, 
//                                            'button_notif_warning' as class, 
//                                            count(*) as notif_count,
//                                            datediff(exp_date, now()) as due_date
//                                     from license
//                                     where datediff(exp_date, now()) = 0");
        
        $query = $this->db->query("select concat('Contract - reminder notification(s) : ',count(*)) as notification,
                                            'view_contract_reminder()' as link, 
                                            'button_notif_warning' as class, 
                                            count(*) as notif_count,
                                            datediff(exp_date, now()) as due_date
                                     from contract
                                     where (datediff(exp_date, now()) = 90
                                           or datediff(exp_date, now()) = 60
                                           or datediff(exp_date, now()) = 30
                                           or datediff(exp_date, now()) = 0)
                                           and status in (0,1)
                                     union all
                                     select concat('License - reminder notification(s) : ',count(*)) as notification,
                                            'view_license_reminder()' as link, 
                                            'button_notif_warning' as class, 
                                            count(*) as notif_count,
                                            datediff(exp_date, now()) as due_date
                                     from license
                                     where (datediff(exp_date, now()) = 90
                                           or datediff(exp_date, now()) = 60
                                           or datediff(exp_date, now()) = 30
                                           or datediff(exp_date, now()) = 0)
                                           and status in (0,1)");
        
        return $query->result();
    }
}
?>