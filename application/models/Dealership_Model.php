<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * MaxAuto
 * @author Alvaro Pavez
 */
class Dealership_Model extends CI_Model
{

    function __construct()
    {
        $this->db = $this->load->database('maxauto', TRUE);
    }

    function insertLanguageAgent($agentId, $insertId)
    {
        $data = array(
            'fk_language_id' => $insertId,
            'fk_agent_id' => $agentId
        );
        return $this->db->insert('ma_language_agent', $data);
    }

    function updateMessage($data, $id)
    {
        $this->db->where('message_id', $id);
        $this->db->update('ma_messages', $data);
    }


    function updateVehicle($data, $id)
    {
        $this->db->where('vehicule_id', $id);
        $this->db->update('ma_vehicle', $data);
    }




    function selectTargetAgentListLanguages($agent_id)
    {
        $this->db->select('*');
        $this->db->from('ma_language_agent');
        $this->db->join('ma_language', 'ma_language.language_id = ma_language_agent.fk_language_id');
        $this->db->where('fk_agent_id', $agent_id);
        $query = $this->db->get();
        $result = $query->result_array();
        //print_r($this->db->last_query());
        return $result;
    }


    function selectLanguage()
    {
        $this->db->select('*');
        $this->db->from('ma_language');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    function getHoursById($id)
    {
        $this->db->select('*');
        $this->db->from('ma_dealerships_hours');
        $this->db->where('fk_dealership_id', $id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function businessHours($data)
    {
        $this->db->select('*');
        $this->db->from('ma_dealerships_hours');
        $this->db->where('fk_dealership_id', $data['fk_dealership_id']);
        $this->db->where('day', $data['day']);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $this->db->where('fk_dealership_id', $data['fk_dealership_id']);
            $this->db->where('day', $data['day']);
            return $this->db->update('ma_dealerships_hours', $data);
        } else {
            $this->db->insert('ma_dealerships_hours', $data);
        }
    }

    function selectLoginDealership($user, $pass)
    {
        $this->db->select('*');
        $this->db->from('ma_dealership');
        $this->db->join('ma_location', 'ma_dealership.fk_location = ma_location.location_id');
        $this->db->join('ma_subscription', 'ma_dealership.dealership_id = ma_subscription.fk_branch_id');
        $this->db->where('dealership_email', $user);
        $query = $this->db->get();
        return $query->result();
    }

    function selectAgentByName($id, $key)
    {
        $this->db->select('ma_sales_consultant.*');
        $this->db->from('ma_sales_consultant');
        $this->db->where('fk_dealership_id', $id);
        $this->db->like('consultant_first_name', $key, 'both');
        $this->db->or_like('consultant_last_name', $key, 'both');
        $this->db->or_like('CONCAT(ma_sales_consultant.consultant_first_name, " ",ma_sales_consultant.consultant_last_name)', $key, 'both');
        $this->db->order_by('indate', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectVehicleByDealership($id)
    {
        $this->db->select('*');
        $this->db->from('ma_vehicle');
        $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
        $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
        $this->db->where('fk_dealership_id', $id);
        $this->db->order_by('indate', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectVehicleByDealershipByStatus($id, $idflag)
    {
        $this->db->select('*');
        $this->db->from('ma_vehicle');
        $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
        $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
        $this->db->where('fk_dealership_id', $id);
        $this->db->where('delete_flag', $idflag);
        $this->db->order_by('indate', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectTradeIn($id, $idflag)
    {
        $this->db->select('*');
        $this->db->from('ma_tradein');
        $this->db->join('ma_customer', 'ma_tradein.fk_customer = ma_customer.customer_id');
        $this->db->join('ma_make', 'ma_tradein.make_id = ma_make.make_id');
        $this->db->join('ma_model', 'ma_tradein.model_id = ma_model.model_id');
        $this->db->where('ma_tradein.fk_dealership', $id);
        $this->db->where('ma_tradein.delete_flag', $idflag);
        $this->db->order_by('ma_tradein.indate', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectAgentByDealership($id)
    {
        $this->db->select('*');
        $this->db->from('ma_sales_consultant');
        $this->db->where('fk_dealership_id', $id);
        $this->db->where('delete_flag', "0");
        $this->db->order_by('indate', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectAgentById($id)
    {
        $this->db->select('*');
        $this->db->from('ma_sales_consultant');
        $this->db->where('id_consultant', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function selectDashNewMessages($id)
    {
        $this->db->where('fk_dealership', $id);
        $this->db->where('status', "0");
        return $this->db->count_all_results('ma_messages');
    }

    function selectDashLastSold($id)
    {
        $this->db->select('*');
        $this->db->from('ma_vehicle');
        $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
        $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
        $this->db->where('fk_dealership_id', $id);
        $this->db->where('delete_flag', "2");
        $this->db->limit(5);
        $this->db->order_by('indate', 'desc');
        $query = $this->db->get();
        return $query->result();
    }


    function selectDashSalesConsultants($id)
    {
        $this->db->where('fk_dealership_id', $id);
        $this->db->where('delete_flag', 0);
        return $this->db->count_all_results('ma_sales_consultant');
    }

    function selectDashVehicle($id)
    {
        $this->db->where('fk_dealership_id', $id);
        return $this->db->count_all_results('ma_vehicle');
    }

    /**
     * CRUD - Select
     */

    function insertSold($data)
    {
        return $this->db->insert('ma_vehicle_sold', $data);
    }

    function insertPhoto($data)
    {
        return $this->db->insert('ma_vehicle_picture', $data);
    }

    function insertCharging($data)
    {
        return $this->db->insert('ma_charging_station', $data);
    }

    function deleteCharging($id)
    {
        $this->db->delete('ma_charging_station', array('id' => $id));
    }

    function deleteVehiclePhotos($id)
    {
        $this->db->delete('ma_vehicle_picture', array('fk_vehicule_id' => $id));
    }

    function deleteNewCar($id)
    {
        $this->db->delete('ma_new_car', array('id' => $id));
    }

    function deleteLanguageAgent($agentId)
    {
        $this->db->delete('ma_language_agent', array('fk_agent_id' => $agentId));
    }

    function updateNewCar($data, $car_id)
    {
        $this->db->where('id', $car_id);
        return $this->db->update('ma_new_car', $data);
    }

    function updateSalesperson2($data, $car_id)
    {
        $this->db->where('id_consultant', $car_id);
        return $this->db->update('ma_sales_consultant', $data);
    }

    function updateDealership($data, $car_id)
    {
        $this->db->where('dealership_id', $car_id);
        return $this->db->update('ma_dealership', $data);
    }


    function addWashList($data)
    {
        return $this->db->insert('ma_watchlist', $data);
    }

    function deleteWashList($customer_id, $vehicle_id)
    {
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->where('fk_vehicule_id', $vehicle_id);
        return $this->db->delete('ma_watchlist');
    }

    function insertNewCar($data)
    {
        return $this->db->insert('ma_new_car', $data);
    }

    function insertSalesPerson($data)
    {
        $this->db->insert('ma_sales_consultant', $data);
        return $this->db->insert_id();
    }

    function insertVehicule($data)
    {
        return $this->db->insert('ma_vehicle', $data);
    }

    function sendMessage($data)
    {
        return $this->db->insert('ma_messages', $data);
    }

    function selectSubscriptionByDealership($id)
    {
        $this->db->select('*');
        $this->db->from('ma_subscription');
        $this->db->join('`ma_products_subs`', 'ma_subscription.fk_product = `ma_products_subs`.id');
        $this->db->join('ma_dealership', 'ma_subscription.fk_branch_id = ma_dealership.dealership_id');
        $this->db->where('ma_subscription.delete_flag', 0);
        $this->db->where('fk_branch_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function selectSoldThisMonth($id)
    {

        $this->db->select('COUNT(id_sold) as number,SUM(ma_vehicle_sold.sold_price) as total');
        $this->db->from('ma_vehicle_sold');
        $this->db->join('ma_vehicle', 'ma_vehicle_sold.fk_vehicle_id = ma_vehicle.vehicule_id ');
        $this->db->where('MONTH(ma_vehicle_sold.indate)', date('m'));
        $this->db->where('YEAR(ma_vehicle_sold.indate)', date('Y'));
        $this->db->where('fk_dealership_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function selectVehicleByDealershipMonthly($id)
    {

        $this->db->select('count(vehicule_id) as total');
        $this->db->from('ma_vehicle');
        $this->db->where('MONTH(indate)', date('m'));
        $this->db->where('YEAR(indate)', date('Y'));
        $this->db->where('fk_dealership_id', $id);
        $query = $this->db->get();
        return $query->result();
    }


    function selectDashboardGraph($id)
    {
        $this->db->select('YEAR(ma_vehicle.sold_date) as year,MONTH(ma_vehicle.sold_date) as month,COUNT(vehicule_id) as total');
        $this->db->from('ma_vehicle');
        $this->db->where('delete_flag', 2);
        $this->db->where('fk_dealership_id', $id);
        $this->db->where('ma_vehicle.sold_date >= ', "date_sub(now(), interval 6 month)");
        $this->db->group_by(array("MONTH(ma_vehicle.sold_date)", "YEAR(ma_vehicle.sold_date)"));
        $this->db->order_by('month DESC, year desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectDashboardGraphListed($id)
    {
        $this->db->select('YEAR(ma_vehicle.indate) as year,MONTH(ma_vehicle.indate) as month,COUNT(vehicule_id) as total');
        $this->db->from('ma_vehicle');
        $this->db->where('fk_dealership_id', $id);
        $this->db->where('ma_vehicle.indate >= ', "date_sub(now(), interval 6 month)");
        $this->db->group_by(array("MONTH(ma_vehicle.indate)", "YEAR(ma_vehicle.indate)"));
        $this->db->order_by('month DESC, year desc');
        $query = $this->db->get();
        return $query->result();
    }


    function insertProduct($data)
    {
        return $this->db->insert('ma_products_subs', $data);
    }

    function updateProduct($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('ma_products_subs', $data);
        return ($this->db->affected_rows() > 0);
    }


    function sendReply($data)
    {
        return $this->db->insert('ma_message_reply', $data);
    }


    function insertModel($data)
    {
        //return $this->db->insert('ma_model', $data);
    }

    function insertOwner($data)
    {
        return $this->db->insert('ma_owner_statio', $data);
    }

    function selectAllCharginStation()
    {
        $this->db->select('*');
        $this->db->from('ma_charging_station');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectRecentVehicule()
    {
        $this->db->select('*');
        $this->db->from('ma_vehicle');
        $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
        $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
        $this->db->order_by('vehicule_id', 'desc');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result();
    }


    function selectWashList($customer_id)
    {
        $this->db->select('*');
        $this->db->from('ma_watchlist');
        $this->db->join('ma_vehicle', 'ma_vehicle.vehicule_id = ma_watchlist.fk_vehicule_id');
        $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
        $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->order_by('vehicule_id', 'desc');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result();
    }

    function getFirstImage($id)
    {
        $this->db->select('pic_url');
        $this->db->from('ma_vehicle_picture');
        //$this->db->join('ma_vehicle_picture', 'ma_vehicle_picture.fk_vehicule_id = ma_vehicle.vehicule_id');
        $this->db->where('fk_vehicule_id', $id);
        $this->db->order_by('pic_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();
    }

    function getAllImages($id)
    {
        $this->db->select('pic_url');
        $this->db->from('ma_vehicle_picture');
        //$this->db->join('ma_vehicle_picture', 'ma_vehicle_picture.fk_vehicule_id = ma_vehicle.vehicule_id');
        $this->db->where('fk_vehicule_id', $id);
        $this->db->order_by('pic_id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function findVehiculeById($id)
    {
        $this->db->select('*');
        $this->db->from('ma_vehicle');
        $this->db->join('ma_customer', 'ma_vehicle.fk_customer = ma_customer.customer_id');
        $this->db->join('ma_body_type', 'ma_vehicle.fk_vehicule_body_id = ma_body_type.body_type_id');
        $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
        //ma_body_type.body_type_id
        $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
        $this->db->join('ma_region', 'ma_vehicle.fk_region = ma_region.region_id');
        $this->db->where('vehicule_id', $id);
        $query = $this->db->get();
        //print_r($this->db->last_query());
        return $query->result();
    }

    //--------------------------------------------------------------------Dealer --------------------------------------

    function findDealerById($id)
    {
        $this->db->select('*');
        $this->db->from('ma_dealership');
        $this->db->join('ma_business_group', 'ma_dealership.fk_business_group = ma_business_group.business_id', "left outer");
        $this->db->join('ma_location', 'ma_dealership.fk_location = ma_location.location_id');
        $this->db->join('ma_region', 'ma_region.region_id = ma_location.fk_region_id');
        $this->db->where('dealership_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function findMessageByAdmin()
    {
        $this->db->select('*');
        $this->db->from('ma_messages');
        $this->db->join('ma_dealership', 'ma_dealership.dealership_id = ma_messages.fk_dealership');
        $this->db->order_by('ma_messages.indate', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function findMessageByDealership2($id)
    {
        $this->db->select('*');
        $this->db->from('ma_messages');
        $this->db->join('ma_dealership', 'ma_dealership.dealership_id = ma_messages.fk_dealership');
        $this->db->where('fk_dealership', $id);
        $this->db->order_by('ma_messages.indate', 'desc');
        $this->db->limit(2);
        $query = $this->db->get();
        return $query->result();
    }

    function findMessageByDealership($id)
    {
        $this->db->select('*');
        $this->db->from('ma_messages');
        $this->db->join('ma_dealership', 'ma_dealership.dealership_id = ma_messages.fk_dealership');
        $this->db->where('fk_dealership', $id);
        $this->db->order_by('ma_messages.indate', 'desc');
        $query = $this->db->get();
        return $query->result();
    }


    function findMessageById($id)
    {
        $this->db->select('*');
        $this->db->from('ma_messages');
        $this->db->join('ma_dealership', 'ma_dealership.dealership_id = ma_messages.fk_dealership');
        $this->db->where('message_id', $id);
        $query = $this->db->get();
        return $query->result();
    }


    function findReplyByMessage($id)
    {
        $this->db->select('*');
        $this->db->from('ma_message_reply');
        $this->db->join('ma_messages', 'ma_messages.message_id = ma_message_reply.fk_message_id');
        $this->db->join('ma_dealership', 'ma_dealership.dealership_id = ma_messages.fk_dealership');
        $this->db->where('fk_message_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function findSubsById($id)
    {
        $this->db->select('*');
        $this->db->from('ma_subscription');

        $this->db->join('`ma_products_subs`', 'ma_subscription.fk_product = `ma_products_subs`.id');
        $this->db->join('ma_status', 'ma_status.status_id = ma_subscription.delete_flag', "left");
        $this->db->where('fk_branch_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function updateDealer($data, $hours, $id)
    {
        $this->db->where('dealership_id', $id);
        $this->db->update('ma_dealership', $data);
        $this->db->where('fk_dealership_id', $id);
        $this->db->update('ma_dealerships_hours', $hours);
        return ($this->db->affected_rows() > 0);
    }


    //------------------------------------------------------------------------Salesperson --------------------------------------------------------

    function findSalespersonById($Id)
    {
        $this->db->select('*');
        $this->db->from('ma_sales_consultant');
        $this->db->where('id_consultant', $Id);
        $query = $this->db->get();
        return $query->result();
    }

    function getAllSalesperson()
    {
        $this->db->select('*');
        $this->db->from('ma_sales_consultant');
        $query = $this->db->get();
        return $query->result();
    }

    function updateSalesperson($data, $id)
    {
        $this->db->where('id_consultant', $id);
        $this->db->update('ma_sales_consultant', $data);
        return ($this->db->affected_rows() > 0);
    }




    function deleteSaleperson($id)
    {
        $this->db->where('id_consultant', $id);
        return ($this->db->delete('ma_sales_consultant'));
    }

    function insertSaleperson($data)
    {
        retuen($this->db->insert('ma_sales_consultant', $data));
    }

    function getContactAgency($id)
    {
        $this->db->select('*');
        $this->db->from('ma_dealership');
        $this->db->where('dealership_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function getContactCustomer($id)
    {
        $this->db->select('*');
        $this->db->from('ma_customer');
        $this->db->where('customer_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function findVehicleByCustomerId($id)
    {
        $this->db->select('*');
        $this->db->from('ma_vehicle');
        $this->db->join('ma_customer', 'ma_vehicle.fk_customer = ma_customer.customer_id');
        $this->db->join('ma_body_type', 'ma_vehicle.fk_vehicule_body_id = ma_body_type.body_type_id');
        $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
        //ma_body_type.body_type_id
        $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
        $this->db->join('ma_region', 'ma_vehicle.fk_region = ma_region.region_id');
        $this->db->where('fk_customer', $id);
        $this->db->order_by('ma_vehicle.vehicule_id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }


    function findVehicule(
        $flag,
        $fk_region,
        $priceFrom,
        $priceTo,
        $bodyType,
        $makeId,
        $modelId,
        $odoFrom,
        $odoTo,
        $yearFrom,
        $yearTo,
        $page,
        $sort,
        $logged
    ) {
        if ($logged == 0) {
            $this->db->select('ma_vehicle.*,ma_make.*,ma_model.*,0 as is_added');
            $this->db->from('ma_vehicle');

            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');

            //Region
            if ($fk_region != 0) {
                $this->db->where('ma_vehicle.fk_region', $fk_region);
            }

            //Make ID
            if ($makeId != 0) {
                $this->db->where('ma_vehicle.fk_vehicule_make', $makeId);
            }

            //Model ID
            if ($modelId != 0) {
                $this->db->where('ma_vehicle.fk_vehicule_model', $modelId);
            }

            //Price
            $this->db->where('ma_vehicle.vehicule_price >=', $priceFrom);
            if ($priceTo != "100000") {
                $this->db->where('ma_vehicle.vehicule_price <=', $priceTo);
            }

            //Year
            $this->db->where('ma_vehicle.vehicule_year >=', $yearFrom);
            if ($priceTo != "2021") {
                $this->db->where('ma_vehicle.vehicule_year <=', $yearTo);
            }

            //Odometer
            $this->db->where('ma_vehicle.vehicule_odometer >=', $odoFrom);
            if ($priceTo != "300000") {
                $this->db->where('ma_vehicle.vehicule_odometer <=', $odoTo);
            }

            //BodyType
            if (in_array("0", $bodyType)) {
            } else {
                $this->db->where_in('ma_vehicle.fk_vehicule_body_id', $bodyType);
            }

            //Flag
            $this->db->where('ma_vehicle.fk_vehicule_type', $flag);

            //page 1
            //if ($page == "1") {
            //    $this->db->limit(10);
            //} else {
            //page2
            //    $pageStart = (intval($page) - 1) * 10;  //20
            //    $pageTo = intval($page) * 10; //30
            //    $this->db->limit(10, $pageStart); //30,20
            //}



            if ($sort == "0") {
                $this->db->order_by('ma_vehicle.vehicule_id', 'desc');
            } else if ($sort == "1") {
                $this->db->order_by('ma_vehicle.vehicule_price', 'asc');
            } else if ($sort == "2") {
                $this->db->order_by('ma_make.make_description', 'asc');
            }
        } else {
            $this->db->select('ma_vehicle.*,ma_make.*,ma_model.*,
            IF(ma_watchlist.fk_customer_id IS NULL,0,1) as is_added,');
            $this->db->from('ma_vehicle');
            $this->db->join('ma_watchlist', "ma_watchlist.fk_vehicule_id = ma_vehicle.vehicule_id and ma_watchlist.fk_customer_id = '$logged'", 'left outer');
            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');

            //Region
            if ($fk_region != 0) {
                $this->db->where('ma_vehicle.fk_region', $fk_region);
            }

            //Make ID
            if ($makeId != 0) {
                $this->db->where('ma_vehicle.fk_vehicule_make', $makeId);
            }

            //Model ID
            if ($modelId != 0) {
                $this->db->where('ma_vehicle.fk_vehicule_model', $modelId);
            }

            //Price
            $this->db->where('ma_vehicle.vehicule_price >=', $priceFrom);
            if ($priceTo != "100000") {
                $this->db->where('ma_vehicle.vehicule_price <=', $priceTo);
            }

            //Year
            $this->db->where('ma_vehicle.vehicule_year >=', $yearFrom);
            if ($priceTo != "2021") {
                $this->db->where('ma_vehicle.vehicule_year <=', $yearTo);
            }

            //Odometer
            $this->db->where('ma_vehicle.vehicule_odometer >=', $odoFrom);
            if ($priceTo != "300000") {
                $this->db->where('ma_vehicle.vehicule_odometer <=', $odoTo);
            }

            //BodyType
            if (in_array("0", $bodyType)) {
            } else {
                $this->db->where_in('ma_vehicle.fk_vehicule_body_id', $bodyType);
            }

            //Flag
            $this->db->where('ma_vehicle.fk_vehicule_type', $flag);

            if ($sort == "0") {
                $this->db->order_by('ma_vehicle.vehicule_id', 'desc');
            } else if ($sort == "1") {
                $this->db->order_by('ma_vehicle.vehicule_price', 'asc');
            } else if ($sort == "2") {
                $this->db->order_by('ma_make.make_description', 'asc');
            }
        }


        $query = $this->db->get();

        //print_r( $this->db->last_query());
        return $query->result();
    }

    function selectAllMainNewCar()
    {
        $this->db->select('*');
        $this->db->from('ma_new_car');
        $this->db->where('mainmodel', "1");
        $this->db->join('ma_make', 'ma_make.make_id = ma_new_car.fk_make', 'left outer');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectAllSubModelNewCar($submodel, $fk_make)
    {
        $this->db->select('*');
        $this->db->from('ma_new_car');
        $this->db->where('model', $submodel);
        $this->db->where('fk_make', $fk_make);
        $this->db->join('ma_make', 'ma_make.make_id = ma_new_car.fk_make', 'left outer');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();


        return $query->result();
    }


    function selectAllNewCar()
    {
        $this->db->select('*');
        $this->db->from('ma_new_car');
        $this->db->join('ma_make', 'ma_make.make_id = ma_new_car.fk_make', 'left outer');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectOwnerChargin()
    {
        $this->db->select('*');
        $this->db->from('ma_owner_statio');
        $query = $this->db->get();
        return $query->result();
    }

    function findMake($key)
    {
        $this->db->select('make_id');
        $this->db->from('ma_make');
        $this->db->where('make_description', $key);
        $query = $this->db->get();
        return $query->result();
    }

    function selectAllMake()
    {
        $this->db->select('*');
        $this->db->from('ma_make');
        $this->db->where('fk_vehicule_type', 0);
        $query = $this->db->get();
        return $query->result();
    }

    function selectAllMakeMoto()
    {
        $this->db->select('*');
        $this->db->from('ma_make');
        $this->db->where('fk_vehicule_type', 1);
        $query = $this->db->get();
        return $query->result();
    }

    function selectAllMakeSearch()
    {
        $ids = array(0, 3);

        $this->db->select('*');
        $this->db->from('ma_make');
        $this->db->where_in('fk_vehicule_type', $ids);
        $this->db->where('make_description !=', "Other");
        //$this->db->limit(40);
        $query = $this->db->get();
        return $query->result();
    }

    function selectAllMakeMotoSearch()
    {
        $ids = array(1, 3);

        $this->db->select('*');
        $this->db->from('ma_make');
        $this->db->where_in('fk_vehicule_type', $ids);
        $query = $this->db->get();
        return $query->result();
    }

    function selectModelByMake($make_id)
    {
        $this->db->select('*');
        $this->db->from('ma_model');
        $this->db->where('fk_make_id', $make_id);
        $query = $this->db->get();
        return $query->result();
    }


    function findModel($make_id, $key)
    {
        $this->db->select('model_id');
        $this->db->from('ma_model');
        $this->db->where('model_desc', $key);
        $this->db->where('fk_make_id', $make_id);
        $query = $this->db->get();
        return $query->result();
    }

    function findModelByOther($make_id)
    {
        $this->db->select('model_id');
        $this->db->from('ma_model');
        $this->db->where('model_desc', "Other");
        $this->db->where('fk_make_id', $make_id);
        $query = $this->db->get();
        return $query->result();
    }

    function checkVehiculeByREGO($rego)
    {
        $this->db->select('*');
        $this->db->from('ma_police_check');
        $this->db->where('rego', $rego);
        $query = $this->db->get();
        return $query->result();
    }

    function selectAllSuburb()
    {
        $this->db->select('*');
        $this->db->from('ma_suburb');
        $this->db->join('ma_district', 'pm_suburb.fk_district_id = ma_district.district_id', 'left outer');
        $this->db->where('suburb_id !=', "0");
        $query = $this->db->get();
        return $query->result();
    }

    function selectRegionList()
    {
        $this->db->select('*');
        $this->db->from('ma_region');
        $this->db->order_by('region_id', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectSuburbListAll()
    {
        $this->db->select('*');
        $this->db->from('ma_suburb');
        $this->db->join('ma_district', 'ma_suburb.fk_district_id = ma_district.district_id', 'left outer');
        $this->db->order_by('suburb_id', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectCityListAll()
    {
        $this->db->select('*');
        $this->db->from('ma_district');
        $this->db->order_by('district_id', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectAllBodies()
    {
        $this->db->select('*');
        $this->db->from('ma_body_type');
        $this->db->order_by('body_type_id', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectFuelType()
    {
        $this->db->select('*');
        $this->db->from('ma_body_type');
        $this->db->order_by('body_type_id', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectABodiesById($id)
    {
        $this->db->select('*');
        $this->db->from('ma_body_type');
        $this->db->where('fk_vehicule_type_id', $id);
        $this->db->order_by('body_type_id', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


    /**
     * CRUD - LOGIN AND REGISTER
     */


    function selectCustomerSocial($social_id)
    {
        $this->db->select('
            ma_customer.customer_id, 
            ma_customer.customer_type,
            ma_customer.customer_fb_token, 
            ma_customer.social_id,
            ma_customer.social_token,
            ma_customer.customer_password, 
            ma_customer.customer_name, 
            ma_customer.customer_description,
            ma_customer.customer_email,
            ma_customer.customer_mobile, 
            ma_customer.customer_sort, 
            ma_customer.customer_pic, 
            ma_customer.notifi_auction_3days,
            ma_customer.notifi_auction_day,
            ma_customer.notifi_agent_updates,
            ma_customer.notifi_saved_search,
            ma_customer.nofifi_frequency,
            ma_customer.verify_flag,
            count(ma_customer.customer_email) as chk_email
        ');
        $this->db->from('ma_customer');
        $this->db->where('ma_customer.social_id', $social_id);
        $this->db->where('ma_customer.delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0];
    }

    function insertTokenLog($data)
    {
        return $this->db->insert('ma_token_log', $data);
    }


    function updateCustomer($data, $customer_id)
    {
        $this->db->where('customer_id', $customer_id);
        return $this->db->update('ma_customer', $data);
    }

    function selectCustomerInfo($customer_id)
    {
        $this->db->select('
            ma_customer.customer_id, 
            ma_customer.customer_type,
            ma_customer.customer_fb_token, 
            ma_customer.social_id,
            ma_customer.social_token,
            ma_customer.customer_name, 
            ma_customer.customer_description,
            ma_customer.customer_email,
            ma_customer.customer_mobile, 
            ma_customer.customer_phone,
            ma_customer.customer_contact,
            ma_customer.customer_sort, 
            ma_customer.customer_pic, 
            ma_customer.notifi_auction_3days,
            ma_customer.notifi_auction_day,
            ma_customer.notifi_agent_updates,
            ma_customer.notifi_saved_search,
            ma_customer.nofifi_frequency,
            ma_customer.notifi_available_day,
            ma_customer.verify_flag
        ');
        $this->db->from('ma_customer');
        $this->db->where('customer_id', $customer_id);
        $result = $this->db->get()->result();
        return $result[0];
    }

    function insertCustomerId($data)
    {
        $this->db->insert('ma_customer', $data);
        return $this->db->insert_id();
    }

    function insertCar($data)
    {
        $this->db->insert('ma_vehicle', $data);
        return $this->db->insert_id();
    }



    function selectCustomerByIdAndToken($customer_id, $is_token)
    {
        $this->db->select('*');
        $this->db->from('ma_customer');
        $this->db->join('ma_token_log', 'ma_token_log.fk_customer_id = ma_customer.customer_id');
        $this->db->where('ma_customer.customer_id', $customer_id);
        $this->db->where('ma_token_log.is_token', $is_token);
        $this->db->where('ma_customer.delete_flag', 0);

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    function selectCustomerEmail($email)
    {
        $this->db->select('
            ma_customer.customer_id, 
            ma_customer.customer_type,
            ma_customer.customer_fb_token, 
            ma_customer.social_id,
            ma_customer.social_token,
            ma_customer.customer_password, 
            ma_customer.customer_name, 
            ma_customer.customer_description,
            ma_customer.customer_email,
            ma_customer.customer_mobile, 
            ma_customer.customer_sort, 
            ma_customer.customer_pic, 
            ma_customer.is_private_agent,
            ma_customer.notifi_auction_3days,
            ma_customer.notifi_auction_day,
            ma_customer.notifi_agent_updates,
            ma_customer.notifi_saved_search,
            ma_customer.nofifi_frequency,
            ma_customer.verify_flag,
            count(ma_customer.customer_email) as chk_email
        ');
        $this->db->from('ma_customer');
        $this->db->where('ma_customer.customer_email', $email);
        $this->db->where('ma_customer.delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0];
    }

    /**
     * CRUD - FIN LOGIN AND REGISTER
     */



    /**
     * CRUD - UPDATE
     */



    /**
     * CRUD - DELETE
     */
}
