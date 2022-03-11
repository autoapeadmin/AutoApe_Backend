<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * MaxAuto
 * @author Alvaro Pavez
 */
class Maxauto_Model extends CI_Model
{

    function __construct()
    {
        $this->db = $this->load->database('maxauto', TRUE);
    }

    /**
     * CRUD - Select
     */
    function selectAgentById($id)
    {
        $this->db->select('*');
        $this->db->from('ma_sales_consultant');
        $this->db->where('id_consultant', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function getBannersApp($idLocation)
    {
        $this->db->select('*');
        $this->db->from('ma_banner');
        $this->db->join('ma_region', 'ma_banner.fk_region = ma_region.region_id');
        $this->db->where('delete_flag', 0);
        $this->db->order_by('number', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


    function getBanners()
    {
        $this->db->select('*');
        $this->db->from('ma_banner');
        $this->db->join('ma_region', 'ma_banner.fk_region = ma_region.region_id');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
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


    function selectPrices()
    {
        $this->db->select('*');
        $this->db->from('ma_app_conf');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectMakeId($make)
    {
        $this->db->select('*');
        $this->db->from('ma_make');
        $this->db->like('make_description', $make, 'both');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectModelId($model, $fk)
    {
        $this->db->select('*');
        $this->db->from('ma_model');
        $this->db->where('fk_make_id', $fk);
        $this->db->like('model_desc', $model, 'both');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function selectOtherModelID($fk)
    {
        $this->db->select('*');
        $this->db->from('ma_model');
        $this->db->where('fk_make_id', $fk);
        $this->db->like('model_desc', 'Other', 'both');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


    function selectAgentByDealershipNearby($id, $distance, $dealerLogo, $dealerName)
    {
        $dist = str_replace('.', '000', $distance);
        $this->db->select('ma_sales_consultant.id_consultant,"' . $dealerLogo .  '" as rec_img_base64, "' . $dealerName . '" as dealername ,ma_sales_consultant.consultant_first_name, ma_sales_consultant.consultant_last_name,ma_sales_consultant.base64_img,ma_sales_consultant.sales_consultant_title, ' . $dist .  ' as distance');
        $this->db->from('ma_sales_consultant');
        $this->db->where('delete_flag', "0");
        $this->db->where('is_visible', "0");
        $this->db->where('fk_dealership_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function selectAgentByDealership2($id, $dealerLogo, $dealerName)
    {
        $this->db->select('ma_sales_consultant.id_consultant,"' . $dealerLogo .  '" as rec_img_base64, "' . $dealerName . '" as dealername ,ma_sales_consultant.consultant_first_name, ma_sales_consultant.consultant_last_name,ma_sales_consultant.base64_img,ma_sales_consultant.sales_consultant_title');
        $this->db->from('ma_sales_consultant');
        $this->db->where('delete_flag', "0");
        $this->db->where('is_visible', "0");
        $this->db->where('fk_dealership_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function selectAgentByDealership($id)
    {
        $this->db->select('ma_sales_consultant.consultant_first_name, ma_sales_consultant.consultant_last_name,ma_sales_consultant.base64_img,ma_sales_consultant.sales_consultant_title ');
        $this->db->from('ma_sales_consultant');
        $this->db->where('fk_dealership_id', $id);
        $this->db->order_by('indate', 'desc');
        $query = $this->db->get();
        return $query->result();
    }


    public function getWantedList($page, $region)
    {
        $this->db->select('ma_wanted_vehicule.*,
        ma_wanted_vehicule.wanted_indate as post_at,
        ma_make.*,ma_model.*,
        ma_wanted_picture.*');
        $this->db->from('ma_wanted_vehicule');
        $this->db->join('ma_make', 'ma_wanted_vehicule.fk_make_id = ma_make.make_id');
        $this->db->join('ma_model', 'ma_wanted_vehicule.fk_model_id = ma_model.model_id');
        $this->db->join('ma_wanted_picture', 'ma_wanted_vehicule.wanted_id = ma_wanted_picture.fk_wanted_id');
        //$this->db->where('fk_dealership_id', $id);
        //$this->db->where('fk_listing_type', 1);
        $this->db->where('ma_wanted_vehicule.delete_flag', 0);

        if ($region != 0) {
            $this->db->where('ma_wanted_vehicule.fk_region_id', $region);
        }
        //page 1
        if ($page == "1") {
            $this->db->limit(10);
        } else {
            //page2
            $pageStart = (intval($page) - 1) * 10;  //20
            $pageTo = intval($page) * 10; //30
            $this->db->limit(10, $pageStart); //30,20
        }

        $this->db->order_by('post_at', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getVehicleNearbyCustomer($lat, $lng, $radius, $logged, $page)
    {
        if ($logged == 0) {
            $this->db->select('ma_vehicle.*,ma_vehicle.indate as post_at,ma_make.*,ma_model.*,0 as is_added,ma_dealership.rec_img_base64,
            ROUND(6371 * ACOS(LEAST(COS(RADIANS(ma_vehicle.listing_customer_lat)) * COS(RADIANS(' . $lat . ')) * COS(RADIANS(ma_vehicle.listing_customer_long -  ' . $lng . ')) + SIN(RADIANS(ma_vehicle.listing_customer_lat)) * SIN(RADIANS(' . $lat . ')),1.0)),1) as distance
            ');
            $this->db->from('ma_vehicle');
            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
            $this->db->join('ma_dealership', 'ma_vehicle.fk_dealership_id = ma_dealership.dealership_id', 'left outer');
            //$this->db->where('fk_dealership_id', $id);
            //$this->db->where('fk_listing_type', 1);
            $this->db->where('ma_vehicle.delete_flag', 0);
            $this->db->where("6371 * ACOS(LEAST(COS(RADIANS(ma_vehicle.listing_customer_lat)) * COS(RADIANS(" . $lat . ")) * COS(RADIANS(ma_vehicle.listing_customer_long  -  " . $lng . ")) + SIN(RADIANS(ma_vehicle.listing_customer_lat)) * SIN(RADIANS(" . $lat . ")),1.0)) <= " . $radius . "");
            //page 1
            if ($page == "1") {
                $this->db->limit(10);
            } else {
                //page2
                $pageStart = (intval($page) - 1) * 10;  //20
                $pageTo = intval($page) * 10; //30
                $this->db->limit(10, $pageStart); //30,20
            }

            $this->db->order_by('distance', 'asc');
            $query = $this->db->get();
            return $query->result();
        } else {
            $this->db->select(
                'ma_vehicle.*,ma_make.*,ma_model.*,ma_vehicle.indate as post_at,
                IF(ma_watchlist.fk_customer_id IS NULL,0,1) as is_added,ma_dealership.rec_img_base64,
                ROUND(6371 * ACOS(LEAST(COS(RADIANS(ma_vehicle.listing_customer_lat)) * COS(RADIANS(' . $lat . ')) * COS(RADIANS(ma_vehicle.listing_customer_long -  ' . $lng . ')) + SIN(RADIANS(ma_vehicle.listing_customer_lat)) * SIN(RADIANS(' . $lat . ')),1.0)),1) as distance'
            );
            $this->db->from('ma_vehicle');
            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
            $this->db->join('ma_dealership', 'ma_vehicle.fk_dealership_id = ma_dealership.dealership_id', 'left outer');
            $this->db->join('ma_watchlist', 'ma_vehicle.vehicule_id = ma_watchlist.fk_vehicule_id and ma_watchlist.fk_customer_id = ' . $logged, 'left outer');
            //$this->db->where('fk_listing_type', 1);
            $this->db->where("6371 * ACOS(LEAST(COS(RADIANS(ma_vehicle.listing_customer_lat)) * COS(RADIANS(" . $lat . ")) * COS(RADIANS(ma_vehicle.listing_customer_long  -  " . $lng . ")) + SIN(RADIANS(ma_vehicle.listing_customer_lat)) * SIN(RADIANS(" . $lat . ")),1.0)) <= " . $radius . "");
            $this->db->where('ma_vehicle.delete_flag', 0);
            //page 1
            if ($page == "1") {
                $this->db->limit(10);
            } else {
                //page2
                $pageStart = (intval($page) - 1) * 10;  //20
                $pageTo = intval($page) * 10; //30
                $this->db->limit(10, $pageStart); //30,20
            }
            $this->db->order_by('distance', 'asc');
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function getDealershipNearby($lat, $lng, $radius, $logged)
    {
        $this->db->select('
        ma_dealership.dealership_id,
        ma_dealership.dealership_name,
        ma_dealership.dealership_email,
        ma_dealership.img_base64,
        ma_dealership.rec_img_base64,
        ma_location.*,
        ROUND(6371 * ACOS(LEAST(COS(RADIANS(ma_location.lat)) * COS(RADIANS(' . $lat . ')) * COS(RADIANS(ma_location.long -  ' . $lng . ')) + SIN(RADIANS(ma_location.lat)) * SIN(RADIANS(' . $lat . ')),1.0)),1) as distance
        ');
        $this->db->from('ma_dealership');
        $this->db->join('ma_location', 'ma_dealership.fk_location = ma_location.location_id');
        $this->db->where("6371 * ACOS(LEAST(COS(RADIANS(ma_location.lat)) * COS(RADIANS(" . $lat . ")) * COS(RADIANS(ma_location.long -  " . $lng . ")) + SIN(RADIANS(ma_location.lat)) * SIN(RADIANS(" . $lat . ")),1.0)) <= " . $radius . "");
        $this->db->where('ma_dealership.delete_flag', 0);


        $this->db->order_by('distance', 'asc');

        //Pagination
        if (isset($page)) {
            $this->db->limit(8, $page);
        } else {
            //$this->db->limit(8);
        }

        //$query = $this->db->query("select pm_agent.* from pm_location , pm_agent left join `en_application` c using(jobid) where a.is_deleted=0 and a.`userid`=b.`userid` and a.`status` in (\"open\", \"processing\") and a.jobid<? and (((acos(sin((?*pi()/180)) * sin((a.`latitude`*pi()/180))+cos((?*pi()/180)) * cos((a.`latitude`*pi()/180)) * cos(((?-a.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) < ? group by a.jobid order by publish_time desc limit ?;", array($last_index, $lat, $lat, $lng, $radius, self::item_per_page));
        $query = $this->db->get();
        $result = $query->result();

        //print_r($this->db->last_query());

        return $result;
    }

    function insertDocument($data)
    {
        $this->db->insert('ma_documents', $data);
        return $this->db->insert_id();
    }

    function insertLocation($data)
    {
        $this->db->insert('ma_location', $data);
        return $this->db->insert_id();
    }

    function insertPhoto($data)
    {
        return $this->db->insert('ma_vehicle_picture', $data);
    }

    function insertPhotoMy($data)
    {
        return $this->db->insert('ma_my_picture', $data);
    }


    function insertPhotoWantedList($data)
    {
        return $this->db->insert('ma_wanted_picture', $data);
    }

    function insertPhotoTradeIn($data)
    {
        return $this->db->insert('ma_tradein_picture', $data);
    }


    function insertCharging($data)
    {
        return $this->db->insert('ma_charging_station', $data);
    }

    function deleteCharging($id)
    {
        $this->db->delete('ma_charging_station', array('id' => $id));
    }

    function deleteDocument($id)
    {
        $this->db->delete('ma_documents', array('document_id' => $id));
    }



    function deleteDealership($id)
    {
        $this->db->delete('ma_dealership', array('dealership_id' => $id));
    }

    function deleteNewCar($id)
    {
        $this->db->delete('ma_new_car', array('id' => $id));
    }

    function updateVehicle($data, $vehicle_id)
    {
        $this->db->where('vehicule_id', $vehicle_id);
        return $this->db->update('ma_vehicle', $data);
    }


    function updateVehicleWantedList($data, $vehicle_id)
    {
        $this->db->where('wanted_id', $vehicle_id);
        return $this->db->update('ma_wanted_vehicule', $data);
    }



    function updateNewCar($data, $car_id)
    {
        $this->db->where('id', $car_id);
        return $this->db->update('ma_new_car', $data);
    }

    function addWashList($data)
    {
        return $this->db->insert('ma_watchlist', $data);
    }

    function addLike($data)
    {
        return $this->db->insert('ma_thumbs', $data);
    }


    function addWashListDealer($data)
    {
        return $this->db->insert('ma_watchlist_dealer', $data);
    }

    function sendAboutUs($data)
    {
        return $this->db->insert('ma_contact_us', $data);
    }

    function updateConfig($data)
    {
        $this->db->where('id_config', "1");
        return $this->db->update('ma_app_conf', $data);
    }

    function addadmin($data)
    {
        return $this->db->insert('ma_admin_account', $data);
    }



    function deleteWashList($customer_id, $vehicle_id)
    {
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->where('fk_vehicule_id', $vehicle_id);
        return $this->db->delete('ma_watchlist');
    }

    function deleteLike($customer_id, $vehicle_id)
    {
        $this->db->where('fk_customer', $customer_id);
        $this->db->where('fk_vehicle', $vehicle_id);
        return $this->db->delete('ma_thumbs');
    }

    function deleteWashListDealer($customer_id, $dealer_id)
    {
        $this->db->where('fk_customer', $customer_id);
        $this->db->where('fk_dealership', $dealer_id);
        return $this->db->delete('ma_watchlist_dealer');
    }

    function insertNewCar($data)
    {
        return $this->db->insert('ma_new_car', $data);
    }

    function insertVehicule($data)
    {
        return $this->db->insert('ma_vehicle', $data);
    }
    function creatSubs($data)
    {
        return $this->db->insert('ma_subscription', $data);
    }

    function createBusinessGroup($data)
    {
        return $this->db->insert('ma_business_group', $data);
    }

    function createBanner($data)
    {
        return $this->db->insert('ma_banner', $data);
    }

    function deleteBanner($id)
    {
        $this->db->delete('ma_banner', array('banner_id' => $id));
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




    function selectDashCustomer()
    {
        return $this->db->count_all_results('ma_customer');
    }



    function selectContactUs()
    {
        $this->db->select('*');
        $this->db->from('ma_contact_us');
        $this->db->order_by('id', 'desc');
        $this->db->group_by('indate');
        $query = $this->db->get();
        return $query->result();
    }

    function selectWantedList()
    {;
    }

    function selectConfig()
    {
        $this->db->select('*');
        $this->db->from('ma_app_conf');
        $query = $this->db->get();
        return $query->result();
    }
    function selectDashVehicle()
    {
        return $this->db->count_all_results('ma_vehicle');
    }


    function selectCountMyDocuments($id)
    {
        $this->db->select('*');
        $this->db->from('ma_documents');
        $this->db->where('fk_customer', $id);
        $this->db->where('delete_flag', 0);
        $this->db->order_by('document_id', 'desc');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }


    function selectDashSubsc()
    {
        return $this->db->count_all_results('ma_subscription');
    }

    function selectDashDealership()
    {
        $this->db->where('delete_flag', "0");
        return $this->db->count_all_results('ma_dealership');
    }

    function selectLikeVehicle($id)
    {
        $this->db->where('fk_vehicle', $id);
        return $this->db->count_all_results('ma_thumbs');
    }


    function isLikeVehicle($idCustomer, $idVehicle)
    {
        $this->db->where('fk_vehicle', $idVehicle);
        $this->db->where('fk_customer', $idCustomer);
        return $this->db->count_all_results('ma_thumbs');
    }


    function selectUserAdmin($user, $pass)
    {
        $this->db->select('*');
        $this->db->from('ma_admin_account');
        $this->db->where('admin_user', $user);
        $query = $this->db->get();
        return $query->result();
    }

    function selectLoginDealership($user, $pass)
    {
        $this->db->select('*');
        $this->db->from('ma_dealership');
        $this->db->where('dealership_email', $user);
        $query = $this->db->get();
        return $query->result();
    }


    function selectProducts($status = 0)
    {
        $this->db->select('*');
        $this->db->from('ma_products_subs');
        $this->db->where('delete_flag', $status);
        $query = $this->db->get();
        return $query->result();
    }

    function updateBanner($data, $id)
    {
        $this->db->where('banner_id', $id);
        return $this->db->update('ma_banner', $data);
    }

    function updateProduct($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update('ma_products_subs', $data);
    }

    function selectDealership($status = 0)
    {
        $this->db->select('*');
        $this->db->from('ma_dealership');
        $this->db->where('delete_flag', $status);
        $this->db->order_by('dealership_id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectAllDealership()
    {
        $this->db->select('*');
        $this->db->from('ma_dealership');
        $this->db->order_by('dealership_id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }



    function businessgrouplist($status = 0)
    {
        $this->db->select('*');
        $this->db->from('ma_business_group');
        $this->db->where('delete_flag', $status);
        $this->db->order_by('business_id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectSubscription($status = 0)
    {
        $this->db->select('*');
        $this->db->from('ma_subscription');
        $this->db->join('`ma_products_subs`', 'ma_subscription.fk_product = `ma_products_subs`.id');
        $this->db->join('ma_dealership', 'ma_subscription.fk_branch_id = ma_dealership.dealership_id');
        $this->db->where('ma_subscription.delete_flag', $status);
        $this->db->order_by('subscription_id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function selectVehiclesCustomer($id)
    {
        $this->db->select('*');
        $this->db->from('ma_vehicle');
        $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
        $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
        $this->db->where('delete_flag', 0);
        $this->db->where('fk_customer', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function selectWantedCustomer($id)
    {
        $this->db->select('*,ma_wanted_vehicule.delete_flag as wanted_deleted');
        $this->db->from('ma_wanted_vehicule');
        $this->db->join('ma_make', 'ma_wanted_vehicule.fk_make_id = ma_make.make_id');
        $this->db->join('ma_model', 'ma_wanted_vehicule.fk_model_id = ma_model.model_id');
        $this->db->join('ma_wanted_picture', 'ma_wanted_vehicule.wanted_id = ma_wanted_picture.fk_wanted_id');
        $this->db->where('fk_customer_id', $id);
        $query = $this->db->get();
        return $query->result();
    }


    function selectBusinessById($id)
    {
        $this->db->select('*');
        $this->db->from('ma_business_group');
        $this->db->where('business_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function selectDealershipById($id, $logged = 0, $lat, $lon)
    {
        if ($logged == 0) {
            $this->db->select('ma_dealership.*,ma_location.*,0 as is_added,
            ROUND(6371 * ACOS(LEAST(COS(RADIANS(ma_location.lat)) * COS(RADIANS(' . $lat . ')) * COS(RADIANS(ma_location.long -  ' . $lon . ')) + SIN(RADIANS(ma_location.lat)) * SIN(RADIANS(' . $lat . ')),1.0)),1) as distance
            ');
            $this->db->from('ma_dealership');
            $this->db->where('dealership_id', $id);
            $this->db->join('ma_location', 'ma_location.location_id = ma_dealership.fk_location');
            $this->db->order_by('dealership_id', 'desc');
            $query = $this->db->get();
        } else {
            $this->db->select('ma_dealership.*,ma_location.*,IF(ma_watchlist_dealer.fk_customer IS NULL,0,1) as is_added,
            ROUND(6371 * ACOS(LEAST(COS(RADIANS(ma_location.lat)) * COS(RADIANS(' . $lat . ')) * COS(RADIANS(ma_location.long -  ' . $lon . ')) + SIN(RADIANS(ma_location.lat)) * SIN(RADIANS(' . $lat . ')),1.0)),1) as distance
        ');
            $this->db->from('ma_dealership');
            $this->db->where('dealership_id', $id);
            $this->db->join('ma_location', 'ma_location.location_id = ma_dealership.fk_location');
            $this->db->join('ma_watchlist_dealer', 'ma_dealership.dealership_id = ma_watchlist_dealer.fk_dealership and ma_watchlist_dealer.fk_customer = ' . $logged, 'left outer');
            $this->db->order_by('dealership_id', 'desc');
            $query = $this->db->get();
        }

        return $query->result();
    }

    function selectSubById($id)
    {
        $this->db->select('*');
        $this->db->from('ma_subscription');
        $this->db->where('subscription_id', $id);
        $this->db->join('`ma_products_subs`', 'ma_subscription.fk_product = `ma_products_subs`.id');
        $this->db->join('ma_dealership', 'ma_subscription.fk_branch_id = ma_dealership.dealership_id');
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


    function selectContactList($customer_id)
    {
        $this->db->select('*');
        $this->db->from('ma_watchlist_dealer');
        $this->db->join('ma_dealership', 'ma_watchlist_dealer.fk_dealership = ma_dealership.dealership_id');
        $this->db->join('ma_location', 'ma_dealership.fk_location = ma_location.location_id');
        $this->db->join('ma_region', 'ma_location.fk_region_id = ma_region.region_id');
        $this->db->where('fk_customer', $customer_id);
        $this->db->order_by('dealership_name', 'asc');
        $query = $this->db->get();
        return $query->result();
    }


    function selectWashList($customer_id)
    {
        $this->db->select('ma_watchlist.*,
        ma_vehicle.vehicule_id,
        ma_vehicle.vehicule_price,
        ma_vehicle.vehicule_year,
        ma_vehicle.fk_listing_type as is_customer,
        ma_vehicle.indate as post_at,
        ma_make.*,ma_model.*,1 as is_added,ma_dealership.rec_img_base64');
        $this->db->from('ma_watchlist');
        $this->db->join('ma_vehicle', 'ma_vehicle.vehicule_id = ma_watchlist.fk_vehicule_id');
        $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
        $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
        $this->db->join('ma_dealership', 'ma_vehicle.fk_dealership_id = ma_dealership.dealership_id', 'left outer');
        $this->db->where('fk_customer_id', $customer_id);
        $this->db->order_by('ma_vehicle.vehicule_id', 'desc');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result();
    }

    function getPositionDealership($idDealer)
    {
        $this->db->select('ma_location.lat,ma_location.long');
        $this->db->from('ma_dealership');
        $this->db->join('ma_location', 'ma_dealership.fk_location = ma_location.location_id');
        $this->db->where('dealership_id', $idDealer);
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



    function selectVehicleNews($logged, $page)
    {
        if ($logged == 0) {
            $this->db->select('ma_fuel_type.*,ma_dealership.*,ma_watchlist_dealer.*,ma_vehicle.*,ma_make.*,ma_model.*,0 as is_addedma_vehicle.indate as post_at,ma_region.*');
            $this->db->from('ma_watchlist_dealer');
            $this->db->join('ma_vehicle', 'ma_watchlist_dealer.fk_dealership = ma_vehicle.fk_dealership_id');
            $this->db->join('ma_dealership', 'ma_vehicle.fk_dealership_id = ma_dealership.dealership_id');
            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
            $this->db->join('ma_fuel_type', 'ma_fuel_type.fuel_id = ma_vehicle.fk_vehicule_fuel');
            $this->db->join('ma_region', 'ma_vehicle.fk_region = ma_region.region_id');
            $this->db->where('ma_watchlist_dealer.fk_customer', $logged);
            $this->db->where('fk_listing_type', 2);
            $this->db->where('ma_vehicle.delete_flag', 0);
            //page 1
            if ($page == "1") {
                $this->db->limit(10);
            } else {
                //page2
                $pageStart = (intval($page) - 1) * 10;  //20
                $pageTo = intval($page) * 10; //30
                $this->db->limit(10, $pageStart); //30,20
            }

            $this->db->order_by('ma_vehicle.indate', 'desc');
            $query = $this->db->get();
            return $query->result();
        } else {
            $this->db->select(
                'ma_fuel_type.*,ma_dealership.*,ma_watchlist_dealer.*,ma_vehicle.*,ma_make.*,ma_model.*,ma_vehicle.indate as post_at,
                IF(ma_watchlist.fk_customer_id IS NULL,0,1) as is_added,ma_region.*'
            );
            $this->db->from('ma_watchlist_dealer');
            $this->db->join('ma_vehicle', 'ma_watchlist_dealer.fk_dealership = ma_vehicle.fk_dealership_id');
            $this->db->join('ma_dealership', 'ma_vehicle.fk_dealership_id = ma_dealership.dealership_id');
            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
            $this->db->join('ma_fuel_type', 'ma_fuel_type.fuel_id = ma_vehicle.fk_vehicule_fuel');
            $this->db->join('ma_region', 'ma_vehicle.fk_region = ma_region.region_id');
            $this->db->join('ma_watchlist', 'ma_vehicle.vehicule_id = ma_watchlist.fk_vehicule_id and ma_watchlist.fk_customer_id = ' . $logged, 'left outer');
            $this->db->where('ma_watchlist_dealer.fk_customer', $logged);
            $this->db->where('fk_listing_type', 2);
            $this->db->where('ma_vehicle.delete_flag', 0);
            //page 1
            if ($page == "1") {
                $this->db->limit(10);
            } else {
                //page2
                $pageStart = (intval($page) - 1) * 10;  //20
                $pageTo = intval($page) * 10; //30
                $this->db->limit(10, $pageStart); //30,20
            }
            $this->db->order_by('ma_vehicle.indate', 'desc');
            $query = $this->db->get();
            return $query->result();
        }
    }

    function selectVehicleByDealershipByStatusWashlist($id, $idflag, $logged, $page)
    {
        if ($logged == 0) {
            $this->db->select('ma_vehicle.*,ma_make.*,ma_model.*,0 as is_added');
            $this->db->from('ma_vehicle');
            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
            $this->db->where('fk_dealership_id', $id);
            $this->db->where('fk_listing_type', 2);
            $this->db->where('delete_flag', $idflag);

            //page 1
            if ($page == "1") {
                $this->db->limit(10);
            } else {
                //page2
                $pageStart = (intval($page) - 1) * 10;  //20
                $pageTo = intval($page) * 10; //30
                $this->db->limit(10, $pageStart); //30,20
            }

            $this->db->order_by('indate', 'desc');
            $query = $this->db->get();
            return $query->result();
        } else {
            $this->db->select(
                'ma_vehicle.*,ma_make.*,ma_model.*,
                IF(ma_watchlist.fk_customer_id IS NULL,0,1) as is_added'
            );
            $this->db->from('ma_vehicle');
            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
            $this->db->join('ma_watchlist', 'ma_vehicle.vehicule_id = ma_watchlist.fk_vehicule_id and ma_watchlist.fk_customer_id = ' . $logged, 'left outer');
            $this->db->where('fk_dealership_id', $id);
            $this->db->where('fk_listing_type', 2);
            $this->db->where('delete_flag', $idflag);
            //page 1
            if ($page == "1") {
                $this->db->limit(10);
            } else {
                //page2
                $pageStart = (intval($page) - 1) * 10;  //20
                $pageTo = intval($page) * 10; //30
                $this->db->limit(10, $pageStart); //30,20
            }
            $this->db->order_by('indate', 'desc');
            $query = $this->db->get();
            return $query->result();
        }
    }

    function selectVehicleByDealershipByStatusWashlist2($id, $idflag, $logged, $page)
    {
        if ($logged == 0) {
            $this->db->select('ma_vehicle.*,ma_vehicle.indate as post_at,ma_make.*,ma_model.*,0 as is_added,ma_dealership.rec_img_base64');
            $this->db->from('ma_vehicle');
            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
            $this->db->join('ma_dealership', 'ma_vehicle.fk_dealership_id = ma_dealership.dealership_id', 'left outer');
            //$this->db->where('fk_dealership_id', $id);
            $this->db->where_in('fk_dealership_id', $id);
            $this->db->where('fk_listing_type', 2);
            $this->db->where('ma_vehicle.delete_flag', $idflag);

            //page 1
            if ($page == "1") {
                $this->db->limit(10);
            } else {
                //page2
                $pageStart = (intval($page) - 1) * 10;  //20
                $pageTo = intval($page) * 10; //30
                $this->db->limit(10, $pageStart); //30,20
            }

            $this->db->order_by('indate', 'desc');
            $query = $this->db->get();
            return $query->result();
        } else {
            $this->db->select(
                'ma_vehicle.*,ma_make.*,ma_model.*,ma_vehicle.indate as post_at,
                IF(ma_watchlist.fk_customer_id IS NULL,0,1) as is_added'
            );
            $this->db->from('ma_vehicle');
            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
            $this->db->join('ma_watchlist', 'ma_vehicle.vehicule_id = ma_watchlist.fk_vehicule_id and ma_watchlist.fk_customer_id = ' . $logged, 'left outer');
            //$this->db->where('fk_dealership_id', $id);
            $this->db->where_in('fk_dealership_id', $id);
            $this->db->where('fk_listing_type', 2);
            $this->db->where('delete_flag', $idflag);
            //page 1
            if ($page == "1") {
                $this->db->limit(10);
            } else {
                //page2
                $pageStart = (intval($page) - 1) * 10;  //20
                $pageTo = intval($page) * 10; //30
                $this->db->limit(10, $pageStart); //30,20
            }
            $this->db->order_by('indate', 'desc');
            $query = $this->db->get();
            return $query->result();
        }
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

    function getFirstImageMy($id)
    {
        $this->db->select('pic_url');
        $this->db->from('ma_my_picture');
        //$this->db->join('ma_vehicle_picture', 'ma_vehicle_picture.fk_vehicule_id = ma_vehicle.vehicule_id');
        $this->db->where('fk_my_id', $id);
        $this->db->order_by('pic_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();
    }


    function getTradeInImage($id)
    {
        $this->db->select('pic_url');
        $this->db->from('ma_tradein_picture');
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
        $this->db->select('*,ma_vehicle.indate as post_at');
        $this->db->from('ma_vehicle');
        $this->db->join('ma_customer', 'ma_vehicle.fk_customer = ma_customer.customer_id');
        $this->db->join('ma_body_type', 'ma_vehicle.fk_vehicule_body_id = ma_body_type.body_type_id');
        $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
        $this->db->join('ma_fuel_type', 'ma_fuel_type.fuel_id = ma_vehicle.fk_vehicule_fuel');
        $this->db->join('ma_dealership', 'ma_vehicle.fk_dealership_id = ma_dealership.dealership_id', 'left outer');
        //ma_body_type.body_type_id
        $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
        $this->db->join('ma_region', 'ma_vehicle.fk_region = ma_region.region_id');
        $this->db->where('vehicule_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function findTimeByDealership($id, $day)
    {
        $this->db->select('*');
        $this->db->from('ma_dealerships_hours');
        $this->db->where('fk_dealership_id', $id);
        $this->db->where('day', $day);
        $query = $this->db->get();
        return $query->result();
    }



    function findDealershipById($id)
    {
        $this->db->select('*');
        $this->db->from('ma_dealership');
        $this->db->where('dealership_id', $id);
        $query = $this->db->get();
        return $query->result();
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

    function findVehiculeCount($flag, $fk_region, $priceFrom, $priceTo, $bodyType, $makeId, $modelId, $odoFrom, $odoTo, $yearFrom, $yearTo, $page, $sort, $logged)
    {

        $this->db->select('COUNT(*) as total');
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
        if ($flag == "0") {
            if (in_array("20", $bodyType)) {
            } else {
                $this->db->where_in('ma_vehicle.fk_vehicule_body_id', $bodyType);
            }
        } else {
            if (in_array("21", $bodyType)) {
            } else {
                $this->db->where_in('ma_vehicle.fk_vehicule_body_id', $bodyType);
            }
        }


        //Flag
        $this->db->where('ma_vehicle.fk_vehicule_type', $flag);

        $this->db->where('ma_vehicle.delete_flag', "0");

        $query = $this->db->get();

        //print_r($this->db->last_query());
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
            $this->db->select('ma_vehicle.*,ma_customer.customer_name,ma_customer.customer_pic, ma_vehicle.fk_listing_type as is_customer,ma_vehicle.indate as post_at,ma_make.*,ma_model.*,0 as is_added,ma_region.*,ma_dealership.rec_img_base64,ma_dealership.img_base64,ma_dealership.dealership_name,ma_fuel_type.*');
            $this->db->from('ma_vehicle');

            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
            $this->db->join('ma_region', 'ma_vehicle.fk_region = ma_region.region_id');
            $this->db->join('ma_dealership', 'ma_vehicle.fk_dealership_id = ma_dealership.dealership_id', 'left outer');
            $this->db->join('ma_fuel_type', 'ma_fuel_type.fuel_id = ma_vehicle.fk_vehicule_fuel');
            $this->db->join('ma_customer', 'ma_vehicle.fk_customer = ma_customer.customer_id', 'left outer');
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
            if (in_array("13", $bodyType)) {
            } else {
                $this->db->where_in('ma_vehicle.fk_vehicule_body_id', $bodyType);
            }

            //Flag
            $this->db->where('ma_vehicle.fk_vehicule_type', $flag);

            $this->db->where('ma_vehicle.delete_flag', "0");

            //page 1
            if ($page == "1") {
                $this->db->limit(10);
            } else {
                //page2
                $pageStart = (intval($page) - 1) * 10;  //20
                $pageTo = intval($page) * 10; //30
                $this->db->limit(10, $pageStart); //30,20
            }



            if ($sort == "0") {
                $this->db->order_by('ma_vehicle.vehicule_id', 'desc');
            } else if ($sort == "1") {
                $this->db->order_by('ma_vehicle.vehicule_price', 'asc');
            } else if ($sort == "2") {
                $this->db->order_by('ma_make.make_description', 'asc');
            }
        } else {
            $this->db->select('ma_vehicle.*,ma_customer.customer_name,ma_customer.customer_pic,ma_vehicle.fk_listing_type as is_customer,ma_vehicle.indate as post_at,ma_make.*,ma_model.*,ma_fuel_type.*,
            IF(ma_watchlist.fk_customer_id IS NULL,0,1) as is_added,ma_region.*,ma_dealership.rec_img_base64,ma_dealership.img_base64,ma_dealership.dealership_name');
            $this->db->from('ma_vehicle');
            $this->db->join('ma_watchlist', "ma_watchlist.fk_vehicule_id = ma_vehicle.vehicule_id and ma_watchlist.fk_customer_id = '$logged'", 'left outer');
            $this->db->join('ma_make', 'ma_vehicle.fk_vehicule_make = ma_make.make_id');
            $this->db->join('ma_model', 'ma_vehicle.fk_vehicule_model = ma_model.model_id');
            $this->db->join('ma_region', 'ma_vehicle.fk_region = ma_region.region_id');
            $this->db->join('ma_dealership', 'ma_vehicle.fk_dealership_id = ma_dealership.dealership_id', 'left outer');
            $this->db->join('ma_fuel_type', 'ma_fuel_type.fuel_id = ma_vehicle.fk_vehicule_fuel');
            $this->db->join('ma_customer', 'ma_vehicle.fk_customer = ma_customer.customer_id', 'left outer');
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
            if (in_array("13", $bodyType)) {
            } else {
                $this->db->where_in('ma_vehicle.fk_vehicule_body_id', $bodyType);
            }

            //Flag
            $this->db->where('ma_vehicle.fk_vehicule_type', $flag);
            $this->db->where('ma_vehicle.delete_flag', "0");

            if ($sort == "0") {
                $this->db->order_by('ma_vehicle.vehicule_id', 'desc');
            } else if ($sort == "1") {
                $this->db->order_by('ma_vehicle.vehicule_price', 'asc');
            } else if ($sort == "2") {
                $this->db->order_by('ma_make.make_description', 'asc');
            }

            //page 1
            if ($page == "1") {
                $this->db->limit(10);
            } else {
                //page2
                $pageStart = (intval($page) - 1) * 10;  //20
                $pageTo = intval($page) * 10; //30
                $this->db->limit(10, $pageStart); //30,20
            }
        }



        $query = $this->db->get();

        //print_r($this->db->last_query());
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

    function selectRegionListCamera()
    {
        $this->db->select('*');
        $this->db->from('ma_region_camera');
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


    function insertWantedList($data)
    {
        $this->db->insert('ma_wanted_vehicule', $data);
        return $this->db->insert_id();
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


    function insertMyCar($data)
    {
        $this->db->insert('ma_my_vehicle', $data);
        return $this->db->insert_id();
    }

    function selectMyDocuments($id)
    {
        $this->db->select('*');
        $this->db->from('ma_documents');
        $this->db->where('fk_customer', $id);
        $this->db->where('delete_flag', 0);
        $this->db->order_by('document_id', 'desc');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }


    function selectMyVehicles($social_id)
    {
        $this->db->select('*');
        $this->db->from('ma_my_vehicle');
        $this->db->where('fk_customer', $social_id);
        $this->db->where('delete_flag', 0);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }


    function insertTradein($data)
    {
        $this->db->insert('ma_tradein', $data);
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
     * Dealership Admin
     */

    function createDealersip($data)
    {
        $this->db->insert('ma_dealership', $data);
        return $this->db->insert_id();
    }




    function updateMyVehicle($data, $id)
    {
        $this->db->where('vehicle_id', $id);
        return $this->db->update('ma_my_vehicle', $data);
    }

    function deleteMyVehicle($id)
    {
        $this->db->delete('ma_my_vehicle', array('vehicle_id' => $id));
    }


    function updateMyVehicleImg($data, $id)
    {
        $this->db->where('fk_my_id', $id);
        return $this->db->update('ma_my_picture', $data);
    }


    function updateDealership($data, $id)
    {
        $this->db->where('dealership_id', $id);
        return $this->db->update('ma_dealership', $data);
    }

    function updateBusinessGroup($data, $id)
    {
        $this->db->where('business_id', $id);
        return $this->db->update('ma_business_group', $data);
    }


    function updateSubs($data, $id)
    {
        $this->db->where('subscription_id', $id);
        return $this->db->update('ma_subscription', $data);
    }


    function updateLocation($data, $id)
    {
        $this->db->where('location_id', $id);
        return $this->db->update('ma_location', $data);
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
