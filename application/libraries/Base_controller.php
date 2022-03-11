<?php

defined('BASEPATH') or exit('No direct script access allowed');

use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Notification;
// $path = $_SERVER['DOCUMENT_ROOT'];
require_once 'vendor/autoload.php';
require APPPATH . 'libraries/aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;


/**
 * PropertiMax
 * @author Edward An
 */
abstract class Base_controller extends CI_Controller
{

    // for Customer
    const SESS_CUSTOMER_ID = "CUSTOMER_ID";
    const SESS_CUSTOMER_TOKEN = "CUSTOMER_TOKEN";
    const SESS_CUSTOMER_TOKEN_TYPE = "CUSTOMER_TOKEN_TYPE";

    // for Agent
    const SESS_AGENT_ID = "AGENT_ID";
    const SESS_AGENT_TOKEN = "AGENT_TOKEN";
    const SESS_ADMIN_FLAG = "ADMIN_FLAG";

    // Common
    // const MAIN_EMAIL_SENDER = 'info@propertimax.com';
    const MAIN_EMAIL_SENDER = 'properti.max15@gmail.com';
    // const FACEBOOK_APP_ID = '1701693896543540';
    // const FACEBOOK_APP_SECRET = 'cd20c49fe2f5c89702f8f32666a1bc16';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Common_Model');
        $this->load->helper(array('file', 'directory'));
    }

    /**
     * Requirements:
     * Parameters: input_passwd, db_passwd
     */
    protected function comparePassword($input, $data)
    {
        $result = FALSE;
        if (password_verify($input, $data))
            return $result = TRUE;
        return $result;
    }

    protected function apiKeyGenerator()
    {
        $arr = array_values(unpack('N1a/n4b/N1c', openssl_random_pseudo_bytes(16)));
        $arr[2] = ($arr[2] & 0x0fff) | 0x4000;
        $arr[3] = ($arr[3] & 0x3fff) | 0x8000;
        return vsprintf('%08x-%04x-%04x-%04x-%04x%08x', $arr);
    }

    protected function pic_keys()
    {
        $token = bin2hex(random_bytes(20));
        return $token;
    }

    public function addImageFile($path, $fieldName, $filename)
    {
        // if (!is_dir($path)) {
        //     mkdir(".".$path);
        // }
        $config = array();
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|GIF|jpg|JPG|jpeg|JPEG|png|PNG';
        $config['max_size'] = 156310000;
        $config['overwrite'] = true;
        $config['remove_spaces'] = true;
        $config['file_name'] = $filename;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($fieldName)) { //write field name, not file name
            $error = $this->upload->display_errors();
            print_r($error);
            exit();

            return $error;
        } else {
            return true;
        }
    }

    function random_string($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    /**
     * S3 AGENT
     */
    public function saveImageInS3($tmp_file, $image_name, $file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'propertymax-images';
        $keyname = 'agentimages/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIA4MK5ICLFEZN5TYV6";
        $s3Secret = "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";

        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);
        $path = $file['userfile']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $image_name = $this->random_string(50) . '.' . $ext;

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "2006-03-01"
        ));

        try {
            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $image_name,
                'SourceFile'   => $tmp_file
            ));


            //Thumbs
            $files = $file;
            $form_name = "userfile";
            $thumb_tmp_path = "uploads/tmp/";
            $upload_path = "agentimagesthumb/"; //s3 buket folder
            $name = $image_name;
            $i_array = explode(".", $name);
            $ext = end($i_array);
            $size = $files[$form_name]['size'];
            $tmp = $files[$form_name]['tmp_name'];
            $names = time() . $name;
            //create thumbs
            $this->make_thumb($tmp, $thumb_tmp_path . "thumb/" . $names, 512, $ext);

            //$s3->putObject($thumb_tmp_path . "thumb/" . $names, $bucket, $upload_path . "thumb/" . $names, S3::ACL_PUBLIC_READ);

            $result2 = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $upload_path . $name,
                'SourceFile'   => $thumb_tmp_path . "thumb/" . $names
            ));

            unlink($thumb_tmp_path . "thumb/" . $names);
            //Fin Thumbs

            return ($image_name);
            //create thumbail
            //$this->make_thumb($tmp_file, 100);
        } catch (S3Exception $e) {
            print_r($e);
            //return false;
        }
        //print_r($s3);
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************

    }

    /**
     * S3 AGENT COVER
     */
    public function saveImageInS3AgentCover($tmp_file, $image_name)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'prope
        rtymax-images';
        $keyname = 'agentimagescover/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIA4MK5ICLFEZN5TYV6";
        $s3Secret = "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";

        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "2006-03-01"
        ));

        try {
            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $image_name,
                'SourceFile'   => $tmp_file
            ));
            return ($result['ObjectURL']);
            //create thumbail
            //$this->make_thumb($tmp_file, 100);
        } catch (S3Exception $e) {
            print_r($e);
            //return false;
        }
        //print_r($s3);
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
    }


    //
    public function saveImageInS3API($tmp_file_url, $name_file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'propertymax-images';
        $keyname = 'propertyimages/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIA4MK5ICLFEZN5TYV6";
        $s3Secret = "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";

        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);
        //$path = $file['images']['name'];
        //$ext = pathinfo($path, PATHINFO_EXTENSION);
        //$image_name = $this->random_string(50) . '.' . $ext;

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "2006-03-01"
        ));

        try {
            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $name_file,
                'SourceFile'   =>  $tmp_file_url
            ));

            //Thumbs
            $thumb_tmp_path = "uploads/tmp/";
            $upload_path = "propertyimagesthumb/"; //s3 buket folder
            $name = $name_file;
            //create thumbs
            $this->make_thumb($tmp_file_url, $thumb_tmp_path . "thumb/" . $name, 512, '');

            //$s3->putObject($thumb_tmp_path . "thumb/" . $names, $bucket, $upload_path . "thumb/" . $names, S3::ACL_PUBLIC_READ);

            $result2 = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $upload_path . $name,
                'SourceFile'   => $thumb_tmp_path . "thumb/" . $name
            ));

            unlink($thumb_tmp_path . "thumb/" . $name);
            //Fin Thumbs



            return ($name_file);
            //create thumbail
            //$this->make_thumb($tmp_file, 100);
        } catch (S3Exception $e) {
            print_r($e);
            //return false;
        }
        //print_r($s3);
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
    }

    public function saveImageInS3Property($tmp_file, $image_name, $file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'propertymax-images';
        $keyname = 'propertyimages/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIA4MK5ICLFEZN5TYV6";
        $s3Secret = "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";

        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);
        $path = $file['images']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $image_name = $this->random_string(50) . '.' . $ext;

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "2006-03-01"
        ));

        try {
            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $image_name,
                'SourceFile'   => $tmp_file
            ));

            //Thumbs
            $files = $file;
            $form_name = "images";
            $thumb_tmp_path = "uploads/tmp/";
            $upload_path = "propertyimagesthumb/"; //s3 buket folder
            $name = $image_name;
            $i_array = explode(".", $name);
            $ext = end($i_array);
            $size = $files[$form_name]['size'];
            $tmp = $files[$form_name]['tmp_name'];
            $names = time() . $name;
            //create thumbs
            $this->make_thumb($tmp, $thumb_tmp_path . "thumb/" . $names, 512, $ext);

            //$s3->putObject($thumb_tmp_path . "thumb/" . $names, $bucket, $upload_path . "thumb/" . $names, S3::ACL_PUBLIC_READ);

            $result2 = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $upload_path . $name,
                'SourceFile'   => $thumb_tmp_path . "thumb/" . $names
            ));

            unlink($thumb_tmp_path . "thumb/" . $names);
            //Fin Thumbs



            return ($image_name);
            //create thumbail
            //$this->make_thumb($tmp_file, 100);
        } catch (S3Exception $e) {
            print_r($e);
            //return false;
        }
        //print_r($s3);
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
    }

    public function imageBannerS3($tmp_file, $image_name, $file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'maxauto';
        $keyname = 'maxauto/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIARSXI5AQF3E7I3QXL";
        $s3Secret = "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";


        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);
        $path = $image_name;
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $image_name = $this->random_string(50) . '.' . $ext;

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "latest"
        ));

        try {
            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $image_name,
                'SourceFile'   => $tmp_file
            ));

            /* //Thumbs
            $files = $file;
            $form_name = "images";
            $thumb_tmp_path = "uploads/tmp/";
            $upload_path = "maxauto/"; //s3 buket folder
            $name = $image_name;
            $i_array = explode(".", $name);
            $ext = end($i_array);
            $size = $files[$form_name]['size'];
            $tmp = $files[$form_name]['tmp_name'];
            $names = time() . $name;
            //create thumbs
            $this->make_thumb($tmp, $thumb_tmp_path . "thumb/" . $names, 512, $ext); */

            //$s3->putObject($thumb_tmp_path . "thumb/" . $names, $bucket, $upload_path . "thumb/" . $names, S3::ACL_PUBLIC_READ);

            /*             $result2 = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $upload_path . $name,
                'SourceFile'   => $thumb_tmp_path . "thumb/" . $names
            ));

            unlink($thumb_tmp_path . "thumb/" . $names); */
            //Fin Thumbs



            return ($image_name);
            //create thumbail
            //$this->make_thumb($tmp_file, 100);
        } catch (S3Exception $e) {
            print_r($e);
            //return false;
        }
        //print_r($s3);
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
    }

    public function imageMaxAutoS3($tmp_file, $image_name, $file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'maxauto';
        $keyname = 'maxauto/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIARSXI5AQF3E7I3QXL";
        $s3Secret = "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";


        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);
        $path = $file['images']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $image_name = $this->random_string(50) . '.' . $ext;

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "latest"
        ));

        try {
            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $image_name,
                'SourceFile'   => $tmp_file
            ));

            /* //Thumbs
            $files = $file;
            $form_name = "images";
            $thumb_tmp_path = "uploads/tmp/";
            $upload_path = "maxauto/"; //s3 buket folder
            $name = $image_name;
            $i_array = explode(".", $name);
            $ext = end($i_array);
            $size = $files[$form_name]['size'];
            $tmp = $files[$form_name]['tmp_name'];
            $names = time() . $name;
            //create thumbs
            $this->make_thumb($tmp, $thumb_tmp_path . "thumb/" . $names, 512, $ext); */

            //$s3->putObject($thumb_tmp_path . "thumb/" . $names, $bucket, $upload_path . "thumb/" . $names, S3::ACL_PUBLIC_READ);

            /*             $result2 = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $upload_path . $name,
                'SourceFile'   => $thumb_tmp_path . "thumb/" . $names
            ));

            unlink($thumb_tmp_path . "thumb/" . $names); */
            //Fin Thumbs



            return ($image_name);
            //create thumbail
            //$this->make_thumb($tmp_file, 100);
        } catch (S3Exception $e) {
            print_r($e);
            //return false;
        }
        //print_r($s3);
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
    }

    public function listAutoS3($tmp_file, $image_name, $file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'maxauto';
        $keyname = 'maxauto/listingCar/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIARSXI5AQF3E7I3QXL";
        $s3Secret = "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";


        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);
        $path = $file['photo']['name'];

        //
        $path45 = strtolower($file['photo']['name']);

        $ext = pathinfo($path45, PATHINFO_EXTENSION);
        $image_name = $this->random_string(50) . '.png';

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "latest"
        ));

        try {
            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $image_name,
                'SourceFile'   => $tmp_file
            ));

            //Thumbs
            $files = $file;
            $form_name = "photo";
            $thumb_tmp_path = "uploads/tmp/";
            $upload_path = "maxauto/listingCarTmp/"; //s3 buket folder
            $name = $image_name;
            $i_array = explode(".", $name);
            $ext = "png";
            $size = $files[$form_name]['size'];
            $tmp = $files[$form_name]['tmp_name'];
            $names = time() . $name;
            //create thumbs

            //return $name

            //$this->make_thumb($tmp, $thumb_tmp_path . "thumb/" . $names, 512, $ext); 

            //$s3->putObject($thumb_tmp_path . "thumb/" . $names, $bucket, $upload_path . "thumb/" . $names, S3::ACL_PUBLIC_READ);

            /*             $result2 = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $upload_path . $name,
                'SourceFile'   => $thumb_tmp_path . "thumb/" . $names
            ));

            unlink($thumb_tmp_path . "thumb/" . $names); */
            //Fin Thumbs


            return ($image_name);
            //create thumbail
            //$this->make_thumb($tmp_file, 100);
        } catch (S3Exception $e) {
            //print_r($e);
            return false;
        }
        //print_r($s3);
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
    }

    public function deleteMaxAutoImage($file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'maxauto';
        $keyname = 'maxauto/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIARSXI5AQF3E7I3QXL";
        $s3Secret = "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";

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "latest"
        ));

        try {

            $result = $s3->deleteObject([
                'Bucket' => $bucket,
                'Key'    => $file
            ]);
        } catch (S3Exception $e) {
            print_r($e);
        }
        //print_r($s3);
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
    }



    function make_thumb($src, $dest, $desired_width, $extension)
    {
        $extension = strtolower($extension);
        if ($extension == 'jpeg' ||  $extension == 'jpg') {
            $source_image = imagecreatefromjpeg($src);
        }
        if ($extension == 'png') {

            $source_image = imagecreatefrompng($src);
        }
        // if ($extension == 'gif') {
        //     $source_image = imagecreatefromgif($src);
        // }
        // $width = imagesx($source_image);
        // $height = imagesy($source_image);
        // $desired_height = floor($height * ($desired_width / $width));
        // $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
        // imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
        // if ($extension == 'jpeg' ||  $extension == 'jpg') {
        //     imagejpeg($virtual_image, $dest);
        // }
        // if ($extension == 'png') {
        //     imagepng($virtual_image, $dest);
        // }
        // if ($extension == 'gif') {
        //     imagegif($virtual_image, $dest);
        // }
    }



    /**
     * Requirements:
     * Parameters: log_desc
     */
    protected function savelog($log_desc)
    {
        $data = array();
        $data['log_desc'] = $log_desc;

        $result = $this->Common_Model->insertLog($data);
    }

    /**
     * Requirements:
     * Parameters: 
     */
    protected function token_keys()
    {
        $token = bin2hex(random_bytes(32));
        // $token = bin2hex(random_bytes(64));
        return $token;
    }

    /**
     * uniqid_base36() . "\n"; // eb98xzzhq7
     * $data: 'agent' -> 'A', 'anything' -> 'P'  
     */
    protected function uniqid_base36($data)
    {
        $uid = uniqid('', false);
        $uniq_id = substr($uid, -10, -2);

        if ($data == 'admin') {
            $result = strtoupper('AN' . $uniq_id);
        } else if ($data == 'agent') {
            $result = strtoupper('AL' . $uniq_id);
        } else if ($data == 'customer') {
            $result = strtoupper('CR' . $uniq_id);
        } else if ($data == 'search') {
            $result = strtoupper('SH' . $uniq_id);
        } else if ($data == 'location') {
            $result = strtoupper('LN' . $uniq_id);
        } else if ($data == 'password') {
            $result = strtoupper($uniq_id);
        } else {
            $result = strtoupper('OR' . $uniq_id);
        }
        return $result;
    }



    protected function dateTime()
    {
        $dateTime = date_create('now')->format('Y-m-d H:i:s');
        return $dateTime;
    }

    protected function json_encode_msgs($val, $logged = 0, $msg = "success")
    {
        $msgx['errorcode'] = 0;
        $msgx['errormsg'] = $msg;
        $msgx['logincode'] = $logged;
        $msgx['data'] = $val;
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($msgx));
    }

    protected function errorMsg($msg, $logged = 0)
    {
        $msgx = array();
        $msgx['errorcode'] = 1;
        $msgx['errormsg'] = $msg;
        $msgx['logincode'] = $logged;
        $msgx['data'] = NULL;
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($msgx));
    }

    protected function saveImageToPath($path, $pic_name, $files, $attachName)
    {

        if (!is_dir($path)) {
            mkdir($path);
        }

        $config = array();

        $config['upload_path'] = $path;
        $config['allowed_types'] = '*';
        // $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = 56310000;
        $config['overwrite'] = false;
        $config['remove_spaces'] = true;

        $this->load->library('upload', $config);

        $picNames = array();

        $count = count($files[$attachName]['name']);

        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                if ($files[$attachName]['name'][$i]) {
                    $tempFileInfo = array();
                    $config['file_name'] = $pic_name . "_" . $i;
                    try {
                        $this->upload->initialize($config);
                        $_FILES[$attachName]['name'] = $files[$attachName]['name'][$i];
                        $_FILES[$attachName]['type'] = $files[$attachName]['type'][$i];
                        $_FILES[$attachName]['tmp_name'] = $files[$attachName]['tmp_name'][$i];
                        $_FILES[$attachName]['error'] = $files[$attachName]['error'][$i];
                        $_FILES[$attachName]['size'] = $files[$attachName]['size'][$i];
                        $this->upload->do_upload($attachName);
                        // if (!$this->upload->do_upload($attachName)) {
                        //     $error = array('error' => $this->upload->display_errors());
                        //     $this->errorMsg($error); exit;
                        // }

                        $picNames[$i] = $pic_name . "_" . $i;
                    } catch (Exception $e) {
                        $picNames[$i] = "";
                        continue;
                    }
                } else {
                    $picNames[$i] = "";
                }
            }
            return $picNames;
        }
        return null;
    }

    protected function sendPushAlarm($noticeType, $token, $data, $bool_only_data = false, $time_to_live = 20)
    {
        /**
          {
          "notification": {
          "title": "hi",
          "msg": "howdy?"
          },
          "data": {
          "code": "MSG_INQUIRY",
          "title": "hi",
          "msg": "howdy?",
          "sender_id": 113,
          "sender_name": "David",
          "sender_pic": "/pic/whatever/link",
          "conversation_id": 12
          }
          }


         */
        return self::fbPushNo($noticeType, $token, $data, $bool_only_data, $time_to_live);
    }

    // firebase notification
    protected function fbPushNo($noticeType, $token, $data, $bool_only_data, $time_to_live)
    {

        /**
          >> Notification Message <<
          {
          "to" : "bk3RNwTe3H0:CI2k_HHwgIpoDKCIZvvDMExUdFQ3P1...",
          "notification" : {
          "body" : "great match!",
          "title" : "Portugal vs. Denmark",
          "icon" : "myicon"
          }
          }

          >> Data Message <<
          {
          "to" : "bk3RNwTe3H0:CI2k_HHwgIpoDKCIZvvDMExUdFQ3P1...",
          "data" : {
          "Nick" : "Mario",
          "body" : "great match!",
          "Room" : "PortugalVSDenmark"
          },
          }

          >> notification & data <<
          {
          "to" : "bk3RNwTe3H0:CI2k_HHwgIpoDKCIZvvDMExUdFQ3P1...",
          "notification" : {
          "body" : "great match!",
          "title" : "Portugal vs. Denmark",
          "icon" : "myicon"
          },
          "data" : {
          "Nick" : "Mario",
          "body" : "great match!",
          "Room" : "PortugalVSDenmark"
          },
          }
         * */



        if (!$data || !$token) {
            return 0;
        }

        if ($time_to_live == null) {
            $time_to_live = 20;
        }
        /*{
        	"to": "dU62COfjnds:APA91bHaKDaT6h-6QDox0Y6c9sxP6x8j3M9cw0RpV6pE-GrOwGqweFno-xiiDc7ufNvm7kvOSmXv6jt4Bhj_1YKSFDu9LCEjZAmdpDwkm2m0eSqYLi6-LXOsnDA2_JnJbSCQUQ3_Oj1j",
        	"notification": {
                "body": "Hello",
                "title": "This is test message."
        	}
        }*/

        $resultStr = "";
        $randString = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVXYZ";
        $length = strlen($randString);

        for ($i = 0; $i < 7; $i++) {
            $randStr = rand(0, $length - 1);
            $resultStr .= substr($randString, $randStr, 1);
        }

        //$apiKey = 'AIzaSyCAqOQ73Gix1dSssHaoDGsem0kikaqoD_I';
        //$apiKey = 'AIzaSyAlitDno5OPlUlMiQSsGH-rEsv6viYyNOk';
        //$apiKey = 'AIzaSyAPdDcV5otJ1WbGnIZiaAbL2Cc3F6vrojg';
        $apiKey = 'AAAAMR3nf5M:APA91bETnEq0TUxupIYiV5CQ5UUYR-kKBIiKIa4zeV0UbnqNYmXK-voPL2oWB8wq_dP2SC6nPmwefvLzWm78bLh-o5gekSnxaXpyjaQpvQkicWTgRksnA_BGOEv568hoJHXBDB0ETKM6';
        $client = new Client();
        $client->setApiKey($apiKey);
        $client->injectHttpClient(new \GuzzleHttp\Client());

        $message = new Message();
        $message->addRecipient(new Device($token));
        $message->setData($data);
        $message->setTimeToLive($time_to_live);

        /* if (!$bool_only_data) {
            $notification = new Notification($data['title'], $data['msg']);

            //new
            $notification = new Data();

            $notification->setIcon('https://propertimax.co.nz/images/logo_notifi.png') // only Android
                    //->setColor('#00ff00')
                    ->setBadge(1)   // only ios
                    ->setSound(1)
                    ->setTag('propertymax'.$resultStr.$noticeType); // only android

            $message->setNotification($notification);
        } */
        $message->setData(array('body' => $data));

        $response = $client->send($message);

        print_r($response->getStatusCode());
        // return $response->getStatusCode();
    }

    protected function sendResetPasswordEmail($email, $name, $temp_password)
    {

        $this->load->library('email');
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.mandrillapp.com';
        $config['smtp_port'] = '587';
        $config['smtp_user'] = 'Propertimax';
        $config['smtp_pass'] = 'Zh4yjTIHtPb4vwD3GtdMMA';

        $config['mailtype'] = 'html'; // or html



        $this->email->initialize($config);
        $this->email->from(self::MAIN_EMAIL_SENDER, 'PropertiMax');
        $this->email->to($email);
        $this->email->subject("PropertiMax - Reset your password");

        $style_sets = 'font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;';

        $msgs = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
            <head>
                <meta name="viewport" content="width=device-width" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Reset Password</title>


                <style type="text/css">
                    img { max-width: 100%; }
                    body { -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; }
                    body { background-color: #f6f6f6; }
                    @media only screen and (max-width: 640px) {
                        body { padding: 0 !important; }
                        h1 { font-weight: 800 !important; margin: 20px 0 5px !important; }
                        h2 { font-weight: 800 !important; margin: 20px 0 5px !important; }
                        h3 { font-weight: 800 !important; margin: 20px 0 5px !important; }
                        h4 { font-weight: 800 !important; margin: 20px 0 5px !important; }
                        h1 { font-size: 22px !important; }
                        h2 { font-size: 18px !important; }
                        h3 { font-size: 16px !important; }
                        .container { padding: 0 !important; width: 100% !important; }
                        .content { padding: 0 !important; }
                        .content-wrap { padding: 10px !important; }
                        .invoice { width: 100% !important; }
                    }
                </style>
            </head>

            <body itemscope itemtype="http://schema.org/EmailMessage" style="' . $style_sets . ' -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
                <table class="body-wrap" style="' . $style_sets . ' width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
                    <tr style="' . $style_sets . ' margin: 0;">
                        <td style="' . $style_sets . ' vertical-align: top; margin: 0;" valign="top"></td>
                        <td class="container" width="600" style="' . $style_sets . ' vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                            <div class="content" style="' . $style_sets . ' max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                                <table class="main" width="100%" cellpadding="0" cellspacing="0" style="' . $style_sets . ' border-radius: 3px; background-color: #fff; margin-top: 20px; border: 1px solid #e9e9e9;" bgcolor="#fff">
                                    <tr style="' . $style_sets . ' margin: 0;">
                                        <td class="alert alert-warning" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: bold; text-align: center; bold; border-radius: 3px 3px 0 0; background-color: #FF9F00; margin: 0; padding: 20px;" align="center" bgcolor="#FF9F00" valign="top">Reset your password</td>
                                    </tr>
                                    <tr style="' . $style_sets . ' margin: 0;">
                                        <td class="content-wrap" style="' . $style_sets . ' vertical-align: top; margin: 0; padding: 20px;" valign="top">
                                            <table width="100%" cellpadding="0" cellspacing="0" style="' . $style_sets . ' margin: 0;">
                                                <tr style="' . $style_sets . ' margin: 0;">
                                                    <td class="content-block" style="' . $style_sets . ' vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                    <strong style="' . $style_sets . ' margin: 0;">Hi ' . $name . ',</strong>
                                                    </td>
                                                </tr>
                                                <tr style="' . $style_sets . ' margin: 0;">
                                                    <td class="content-block" style="' . $style_sets . ' vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">We have issued a temporary password for you.</td>
                                                </tr>
                                                <tr style="' . $style_sets . ' margin: 0;">
                                                    <td class="content-block" style="' . $style_sets . ' vertical-align: top; margin: 0; padding: 0 0 20px;" align="center" valign="top">
                                                    <div class="btn-primary" style="' . $style_sets . ' color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">Temporary Password: ' . $temp_password . '</div>
                                                    </td>
                                                </tr>
                                                <tr style="' . $style_sets . ' margin: 0;">
                                                    <td class="content-block" style="' . $style_sets . ' vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">To reset your password, please log in with the temporary password.  Once logged in, go to user profile and click “Change Password”.</td>
                                                </tr>
                                                
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <div class="footer" style="' . $style_sets . ' width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
                                    <table width="100%" style="' . $style_sets . ' margin: 0;">
                                        <tr style="' . $style_sets . ' margin: 0;">
                                            <td class="aligncenter content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">Email <a href="mailto:' . self::MAIN_EMAIL_SENDER . '" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; color: #999; text-decoration: underline; margin: 0;">' . self::MAIN_EMAIL_SENDER . '</a></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </td>
                        <td style="' . $style_sets . ' vertical-align: top; margin: 0;" valign="top"></td>
                    </tr>
                </table>
            </body>
        </html>
        ';

        $this->email->message($msgs);
        $this->email->send();
    }

    protected function sendFeedbackEmail($email, $name, $send_mags)
    {

        $this->load->library('email');
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.mandrillapp.com';
        $config['smtp_port'] = '587';
        $config['smtp_user'] = 'Propertimax';
        $config['smtp_pass'] = 'Zh4yjTIHtPb4vwD3GtdMMA';

        $config['mailtype'] = 'html'; // or html



        $this->email->initialize($config);
        $this->email->from($email);
        $this->email->to(self::MAIN_EMAIL_SENDER, 'PropertiMax');
        $this->email->subject("PropertiMax - Feedback");

        $style_sets = 'font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;';

        $msgs = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
            <head>
                <meta name="viewport" content="width=device-width" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Feedback Email</title>
            </head>

            <body itemscope itemtype="http://schema.org/EmailMessage" style="' . $style_sets . ' -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em;>
                <table class="body-wrap" style="' . $style_sets . ' width: 100%;>
                    <tr style="' . $style_sets . ' margin: 0;">
                        <td class="content-block" style="' . $style_sets . ' vertical-align: top; margin: 0; padding-bottom: 30px;" valign="top">
                        <strong style="' . $style_sets . ' margin: 0;">Hi ' . $name . ',</strong>
                        </td>
                    </tr>
                    <tr style="' . $style_sets . ' margin: 0;">
                        <td style="' . $style_sets . ' vertical-align: top; margin: 0;" valign="top">' . $send_mags . '</td>
                    </tr>
                </table>
            </body>
        </html>
        ';

        $this->email->message($msgs);
        $this->email->send();
    }



    public function uploadImageMaxAuto($tmp_file, $image_name, $file, $path_s3)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'maxauto';
        $keyname = $path_s3;
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIARSXI5AQF3E7I3QXL";
        $s3Secret = "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";


        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);
        $path = $file['images']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $image_name = $this->random_string(50) . '.' . $ext;

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "latest"
        ));

        try {
            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $image_name,
                'SourceFile'   => $tmp_file
            ));
            return ($image_name);
        } catch (S3Exception $e) {
            print_r($e);
        }
    }

    public function deleteImageMaxAuto($image_name, $path_s3)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'maxauto';
        $keyname = $path_s3;
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIARSXI5AQF3E7I3QXL";
        $s3Secret = "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";


        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "latest"
        ));

        try {
            // Upload data.
            $result = $s3->deleteObject(array(
                'Bucket' => $bucket,
                'Key'    => $image_name,
            ));
            return "delete";
        } catch (S3Exception $e) {
            print_r($e);
        }
    }

    public function deleteMaxAutoImageCar($file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'maxauto';
        $keyname = 'maxauto/listingCar/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIARSXI5AQF3E7I3QXL";
        $s3Secret = "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";

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    =>  $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "latest"
        ));

        try {

            $result = $s3->deleteObject([
                'Bucket' => $bucket,
                'Key'    =>  $keyname . $file
            ]);
        } catch (S3Exception $e) {
            print_r($e);
        }
        //print_r($s3);
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
    }



    public function uploadImageMaxAutoDealership($tmp_file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'maxauto';
        $keyname = 'maxauto/listingCar/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIARSXI5AQF3E7I3QXL";
        $s3Secret = "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";


        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);
        //$path = $file['images']['name'];
        //$ext = pathinfo($path, PATHINFO_EXTENSION);
        $image_name = $this->random_string(50) . '.' . 'png';

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "latest"
        ));

        try {
            if (!file_exists('/tmp/tmpfile')) {
                mkdir('/tmp/tmpfile');
            }
            // Create temp file
            $tempFilePath = '/tmp/tmpfile/' . basename($tmp_file);
            $tempFile = fopen($tempFilePath, "w") or die("Error: Unable to open file.");
            $fileContents = file_get_contents($tmp_file);
            $tempFile = file_put_contents($tempFilePath, $fileContents);

            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $image_name,
                'SourceFile'   => $tempFilePath
            ));
       
            return ($image_name);
        } catch (S3Exception $e) {
            print_r($e);
        }
    }


    public function S3Logo($tmp_file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'maxauto';
        $keyname = 'maxauto/dealership/logo/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIARSXI5AQF3E7I3QXL";
        $s3Secret = "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";


        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);
        //$path = $file['images']['name'];
        //$ext = pathinfo($path, PATHINFO_EXTENSION);
        $image_name = $this->random_string(50) . '.' . 'png';

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "latest"
        ));

        try {
            if (!file_exists('/tmp/tmpfile')) {
                mkdir('/tmp/tmpfile');
            }
            // Create temp file
            $tempFilePath = '/tmp/tmpfile/' . basename($tmp_file);
            $tempFile = fopen($tempFilePath, "w") or die("Error: Unable to open file.");
            $fileContents = file_get_contents($tmp_file);
            $tempFile = file_put_contents($tempFilePath, $fileContents);

            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $image_name,
                'SourceFile'   => $tempFilePath
            ));
       
            return ($image_name);
        } catch (S3Exception $e) {
            print_r($e);
        }
    }


    public function S3LogoRec($tmp_file)
    {
        //*****************************CODIGO PARA CARGAR IMAGENES S3**************************
        $bucket = 'maxauto';
        $keyname = 'maxauto/dealership/logo/';
        // $filepath should be absolute path to a file on disk                      
        $s3Key = "AKIARSXI5AQF3E7I3QXL";
        $s3Secret = "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";


        //$tmp_file = $_FILES['userfile']['tmp_name'];
        //print_r($_FILES);
        //$path = $file['images']['name'];
        //$ext = pathinfo($path, PATHINFO_EXTENSION);
        $image_name = $this->random_string(50) . '.' . 'png';

        // Instantiate the client.
        $s3 = S3Client::factory(array(
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
            'region' => 'ap-southeast-2',
            'version' => "latest"
        ));

        try {
            if (!file_exists('/tmp/tmpfile')) {
                mkdir('/tmp/tmpfile');
            }
            // Create temp file
            $tempFilePath = '/tmp/tmpfile/' . basename($tmp_file);
            $tempFile = fopen($tempFilePath, "w") or die("Error: Unable to open file.");
            $fileContents = file_get_contents($tmp_file);
            $tempFile = file_put_contents($tempFilePath, $fileContents);

            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname . $image_name,
                'SourceFile'   => $tempFilePath
            ));
       
            return ($image_name);
        } catch (S3Exception $e) {
            print_r($e);
        }
    }
}
