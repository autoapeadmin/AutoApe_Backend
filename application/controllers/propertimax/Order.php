<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/Base_controller.php';
require_once('vendor/stripe-php/init.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Order
 *
 * @author mac
 */
class Order extends Base_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Order_Model');
        $this->load->model('WebadminModel');
    }

    public function charge() {
        if ($_SESSION["orderid"] == "new") {
            $this->createOrder();
        }

        $token = $this->input->post_get('token');
        $amount = $_SESSION["SESS_PRICE"];
        $currency = "NZD";
        $orderid = $_SESSION["orderid"];
        $customer = $_SESSION["SESS_CUSTOMER_ID"];
        $propid = $_SESSION["pripertyid"];

        \Stripe\Stripe::setApiKey("sk_test_BWe7m4QNoFghgOGIWPJH8zKq");
        $result = \Stripe\Charge::create(array(
                    "amount" => $amount * 100,
                    "currency" => $currency,
                    "source" => $token,
                    "metadata" => array("order_id" => $orderid,
                        "customerID" => $customer,
                        "propertyID" => $propid)
        ));

        $current_date = date('Y-m-d H:i:s');
        if ($result->status == "succeeded") {
            $data = array(
                "price" => $amount,
                "status" => "success",
                "payment_id" => $result->id,
                "pay_date" => $current_date,
                "start_date" => $current_date,
                "payment_state" => "1"
            );
            $this->Order_Model->updateOrder($_SESSION["orderid"], $data);
            $pdata = array(
                "delete_flag" => "0"
            );
            $this->WebadminModel->modproperty($propid, $pdata);
            $this->json_encode_msgs(array("id" => $result->id,
                "status" => $result->status));
            $this->sendinvoice();
        } else {
            $data = array(
                "price" => $amount,
                "status" => "pay failed",
                "payment_id" => $result->id,
                "pay_date" => $current_date,
                "payment_state" => "0"
            );
            $this->Order_Model->updateOrder($_SESSION["orderid"], $data);
            $this->errorMsg("Payment result failed");
        }
    }

    public function byPassCharge($co_code) {
        $current_date = date('Y-m-d H:i:s');

        $data = array();
        $data['price'] = 0;
        $data['status'] = "success";
        $data['payment_id'] = $co_code;
        $data['pay_date'] = $current_date;
        $data['start_date'] = $current_date;
        $data['payment_state'] = 1;
        $this->Order_Model->updateOrder($_SESSION["orderid"], $data);

        $this->sendinvoice();

        $pdata = array();
        $pdata['delete_flag'] = 0;
        $this->WebadminModel->modproperty($_SESSION["pripertyid"], $pdata);
    }

    public function createOrder() {
        $data = array(
            "status" => "open",
            "customer_id" => $_SESSION["SESS_CUSTOMER_ID"],
            "propertyid" => $_SESSION["pripertyid"],
            "price" => $_SESSION["SESS_PRICE"],
            "currency" => "NZD",
        );
        $orderid = $this->Order_Model->saveOrder($data);
        $_SESSION["orderid"] = $orderid;
        $this->json_encode_msgs(array("orid" => $orderid));
    }

    public function chkCode() {
        $co_code = $this->input->post_get('co_code');
        $pay_code = $this->input->post_get('pay_code');
        $data = $this->Order_Model->chkCode($co_code)->result()[0];
        $configs = $this->WebadminModel->getConfig()->row(0);

        if ($pay_code == 'rent') {
            $_SESSION["SESS_MAIN_PRICE"] = $configs->rental_listing_cost;
            if ($data->co_category == 2) {
                $data->current_price = $configs->rental_listing_cost - round((($configs->rental_listing_cost * $data->co_discount) / 100), 2);
                $_SESSION["SESS_DISCOUNT_PRICE"] = round((($configs->rental_listing_cost * $data->co_discount) / 100), 2);
            } else {
                $data->current_price = $configs->rental_listing_cost - $data->co_discount;
                $_SESSION["SESS_DISCOUNT_PRICE"] = $data->co_discount;
            }
        } else {
            $_SESSION["SESS_MAIN_PRICE"] = $configs->sales_listing_cost;
            if ($data->co_category == 2) {
                $data->current_price = $configs->sales_listing_cost - round((($configs->sales_listing_cost * $data->co_discount) / 100), 2);
                $_SESSION["SESS_DISCOUNT_PRICE"] = round((($configs->sales_listing_cost * $data->co_discount) / 100), 2);
            } else {
                $data->current_price = $configs->sales_listing_cost - $data->co_discount;
                $_SESSION["SESS_DISCOUNT_PRICE"] = $data->co_discount;
            }
        }

        if ($data->current_price <= 0) {
            $_SESSION["SESS_DISCOUNT_CODE"] = $co_code;
            $_SESSION["SESS_DISCOUNT"] = $data->co_discount;
            $data->current_price = 0;
            $_SESSION["SESS_PRICE"] = $data->current_price;

            $this->byPassCharge($co_code);
        }

        $_SESSION["SESS_DISCOUNT_CODE"] = $co_code;
        $_SESSION["SESS_DISCOUNT"] = $data->co_discount;
        $_SESSION["SESS_PRICE"] = $data->current_price;
        $this->json_encode_msgs($data);
    }

    public function carddetails() {
        $this->load->view('payment');
    }

    public function salePayment() {
        $configs = $this->WebadminModel->getConfig()->row(0);

        $ar = array();
        $ar["ipayment"] = 'sale';
        $ar["current_price"] = $configs->sales_listing_cost;
        $this->load->view('payment', $ar);
    }

    public function rentPayment() {
        $configs = $this->WebadminModel->getConfig()->row(0);

        $ar = array();
        $ar["ipayment"] = 'rent';
        $ar["current_price"] = $configs->rental_listing_cost;
        $this->load->view('payment', $ar);
    }

    public function sendinvoice() {
        $this->load->library('email');
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.mandrillapp.com';
        $config['smtp_port'] = '587';
        $config['smtp_user'] = 'Propertimax';
        $config['smtp_pass'] = 'Zh4yjTIHtPb4vwD3GtdMMA';

        $config['mailtype'] = 'html'; // or html



        $this->email->initialize($config);

        $name = $_SESSION["SESS_CUSTOMER_NAME"];
        $email = $_SESSION["SESS_CUSTOMER_EMAIL"];
        $orderid = $_SESSION["orderid"];
        $saledec = $_SESSION["SESS_SERVCAT"];
        $price = $_SESSION["SESS_PRICE"];
        $discont = "";
        $mainprice = $_SESSION["SESS_MAIN_PRICE"];
        if (isset($_SESSION["SESS_DISCOUNT"])) {
            // 
            //
            //
            $discont = '<tr class="item">

                    <td style="padding:5px;
                        vertical-align:top;border-bottom:1px solid #eee;">
                        Discount (' . $_SESSION["SESS_DISCOUNT_CODE"] . ')
                    </td>

                    <td style="padding:5px;
                        vertical-align:top; text-align:right;border-bottom:1px solid #eee;">
                        $' . $_SESSION["SESS_DISCOUNT_PRICE"] . '
                    </td>
                </tr>';
        }

        // testingg
//        $name="Rogin";
//        $email="rogin@imperialdigital.co.nz";
//        $orderid = "123";
//        $saledec = "Property Rental Listing (5 Week(s))";
//        $price = "29.50";

        $gst = ($price * 0.15);
        $price_ex_gst = ($price - $gst);

        $this->email->from('noreply@propertimax.co.nz', 'Propertimax');
        $this->email->to($email);
        $this->email->cc('invoice@propertimax.co.nz');
        $this->email->subject('Propertimax Tax Invoice');



        $msg = '<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Propertimax | Invoice</title>
    </head>

    <body>
        <div class="invoice-box" style="max-width:800px;
             margin:auto;
             padding:30px;
             border:1px solid #eee;
             box-shadow:0 0 10px rgba(0, 0, 0, .15);
             font-size:16px;
             line-height:24px;
             font-family:Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif;
             color:white;background: rgba(0, 0, 0, 0.8);" >
            <table style="width:100%;
                   line-height:inherit;
                   text-align:left;" cellpadding="0" cellspacing="0">
                <tr class="top">

                    <td colspan="2" style="padding:5px;
                        vertical-align:top;">
                        <table>
                            <tr>
                                <td style="padding-bottom:20px;font-size:45px;
                                    line-height:45px;padding:5px;
                                    vertical-align:top; width: 300px;
                                    color:#333;" class="title">
                                    <img src="https://propertimax.co.nz/assets/css/imgs/logo-top.png" style="width:100%; max-width:300px;">
                                </td>

                                <td style="padding-bottom:20px; text-align:right;padding:5px; color:white;
                                    vertical-align:top; width:500px; ">
                                    <h2 style="margin-bottom: 0;">Tax Invoice</h2>
                                    GST No: 125-733-379<br>
                                    Invoice #: PMI-' . $orderid . '<br>
                                    <b>' . date("F j, Y") . '</b><br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="information">

                    <td colspan="2" style="padding:5px;
                        vertical-align:top;">
                        <table>
                            <tr>
                                <td style="padding-bottom:40px;padding:5px;
                                    vertical-align:top; width: 300px;">

                                </td>

                                <td style="padding-bottom:40px; text-align:right;padding:5px;
                                    vertical-align:top;width:500px;">

                                    ' . $name . '<br>
                                    ' . $email . '
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>



                <tr class="heading">
                    <td style="padding:5px;
                        vertical-align:top;    background: #616161;
                        border-bottom: 1px solid #ddd;
                        font-weight: bold;">
                        Description of Goods and services
                    </td>

                    <td style="padding:5px;
                        vertical-align:top; text-align:right;    background: #616161;
                        border-bottom: 1px solid #ddd;
                        font-weight: bold;">
                        Price
                    </td>
                </tr>

                <tr class="item">

                    <td style="padding:5px;
                        vertical-align:top;border-bottom:1px solid #eee;">
                        ' . $saledec . '
                    </td>

                    <td style="padding:5px;
                        vertical-align:top; text-align:right;border-bottom:1px solid #eee;">
                        $' . $mainprice . '
                    </td>
                </tr>
                
                ' . $discont . '

               <tr class="item">

                    <td style="padding:5px;
                        vertical-align:top;border-bottom:1px solid #eee;border-bottom:none;">
                        <b>Net Price</b>
                    </td>

                    <td style="padding:5px;
                        vertical-align:top; text-align:right;border-bottom:1px solid #eee;border-bottom:none;">
                        $' . $price . '
                    </td>
                </tr>

                <tr class="item last">

                    <td style="padding:5px;
                        vertical-align:top;border-bottom:1px solid #eee;border-bottom:none;">
                        Goods and Services Tax (15%)
                    </td>

                    <td style="padding:5px;
                        vertical-align:top; text-align:right;border-bottom:1px solid #eee;border-bottom:none;">
                        $' . round($gst, 2) . '
                    </td>
                </tr>

            </table>
        </div>
    </body>
</html>
';

//        old one
//        <tr class="total">
//
//                    <td style="padding:5px;
//                        vertical-align:top;"></td>
//
//                    <td style="padding:5px;    width: 200px;
//                        vertical-align:top; text-align:right;border-top:2px solid #eee;
//                        font-weight:bold;">
//                        Total: $' . $price . '
//                    </td>
//                </tr>   $2y$10$rKTr5KWY7Gt21dTvG1HgY.nx9rEbAULc.c5nTO4kCG5ptbsPZ02ha


        $this->email->message($msg);

        $this->email->send();

        unset(
                $_SESSION['SESS_DISCOUNT'], $_SESSION['SESS_DISCOUNT_PRICE'], $_SESSION['SESS_MAIN_PRICE']
        );
    }

}
