<?php

defined('BASEPATH') OR exit('No direct script access allowed');

    /**
     * PropertiMax
	 * @author Edward An
	 */
	 
class Admin_Model extends CI_Model {

    function __construct() {
        
        $this->load->database();
    }

    function selectAgentList() {
        $this->db->select('pm_agent.agent_id, pm_agent.agent_first_name, pm_agent.agent_last_name, pm_agent.agent_mobile, pm_agent.agent_email, pm_agent.agent_phone, pm_agent.agent_pic, pm_agency.*');
        $this->db->from('pm_agent');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_agent.fk_agency_id');
        $this->db->join('pm_location', 'pm_agent.fk_location_id = pm_location.location_id');
        $this->db->where('pm_agent.admin_flag', 0);
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->order_by("pm_agent.agent_id","desc");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    function selectAdminList() {
        $this->db->select('pm_agent.agent_id, pm_agent.agent_first_name, pm_agent.agent_last_name, pm_agent.agent_mobile, pm_agent.agent_email, pm_agent.agent_phone, pm_agent.agent_pic');
        $this->db->from('pm_agent');
        $this->db->join('pm_agency', 'pm_agency.agency_id = pm_agent.fk_agency_id');
        $this->db->join('pm_location', 'pm_agent.fk_location_id = pm_location.location_id');
        $this->db->where('pm_agent.admin_flag', 1);
        $this->db->where('pm_agent.delete_flag', 0);
        $this->db->order_by("pm_agent.agent_id","desc");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
   
    // Show Add List
    function selectAssistList($agent_id) { // for Agent (UNION ALL and IN Query)
        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where('pm_property.fk_agent_id', $agent_id);
        $this->db->where('pm_property.delete_flag', 0);
        $query1 = $this->db->get_compiled_select();

        $this->db->select('*');
        $this->db->from('pm_property');
        $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
        $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
        $this->db->where("pm_property.property_id IN (select assist_property_id from pm_assist where assist_agent_id = '$agent_id')");
        $this->db->where('pm_property.delete_flag', 0);
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query($query1 . ' UNION ALL ' . $query2);
        $result = $query->result_array();
        return $result;
    }

    // Show Add List
    function selectPropertyList($agent_id, $admin_flag) { // for Agent (UNION ALL and IN Query)
        if ($admin_flag == 0) {
            $this->db->select('*, CONCAT("1") as tp');
            $this->db->from('pm_property');
            $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
            $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
            $this->db->where('pm_property.fk_agent_id', $agent_id);
            $this->db->where('pm_property.delete_flag', 0);
            $query1 = $this->db->get_compiled_select();
            
            $this->db->select('*, CONCAT("2") as tp');
            $this->db->from('pm_property');
            $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
            $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
            $this->db->where("pm_property.property_id IN (select assist_property_id from pm_assist where assist_agent_id = '$agent_id')");
            $this->db->where('pm_property.delete_flag', 0);
            $query2 = $this->db->get_compiled_select();
            
            $query = $this->db->query($query1 . ' UNION ALL ' . $query2);
        } else {
            $this->db->select('*');
            $this->db->from('pm_property');
            $this->db->join('pm_property_type', 'pm_property.fk_property_type_id = pm_property_type.property_type_id', 'left outer');
            $this->db->join('pm_location', 'pm_property.fk_location_id = pm_location.location_id');
            $this->db->where('pm_property.delete_flag', 0);
            $query = $this->db->get();
        }
        $result = $query->result_array();
        return $result;
    }


}

?>
