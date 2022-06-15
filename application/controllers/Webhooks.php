<?php
Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
//defined('BASEPATH') OR exit('No direct script access allowed');

class Webhooks extends CI_Controller
{
    public function __construct(){
        parent::__construct();
		$this->load->helper('url');
		$this->load->helper('file');
    }

    public function appUninstalled(){
        $shop = $this->input->get_request_header('X-Shopify-Shop-Domain');
        function isJSON($json_str){
            return is_string($json_str) && is_array(json_decode($json_str, true)) ? true : false;
        }
        $json_str = file_get_contents('php://input');
        $json_str = str_replace("'","",$json_str);
        file_put_contents(FCPATH.'assets/txt_file/webhooks/app_uninstalled.txt', $json_str);
        if(!isJSON($json_str)){
            return false;
            exit;
        }
        $newJson = str_replace("'","",$json_str);
        $json_obj = json_decode($newJson, true);

        $wherecondition = array(
            'store' => $shop
        );
        $install_exist = $this->User_model->store_check("install",$wherecondition);
        if($install_exist){
            $this->User_model->delete_db('install',$wherecondition);
        }
    }

}