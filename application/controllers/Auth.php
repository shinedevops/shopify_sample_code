<?php

class Auth extends CI_Controller
{
    /**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/auth
	 *	- or -
	 * 		http://example.com/index.php/auth/access
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/auth/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
		$this->load->helper('file');
    }
    
    public function access(){
        $shop = $this->input->get('shop');
        $store_condition = array(
			'store' => $shop
        );
        // select row from the table if exist or not.
        $get_install_data = $this->User_model->select_row('install', $store_condition);
        if($get_install_data) {
            $data_header = array(
                'api_key' => $this->config->item('shopify_api_key'),
                'shop' => $shop
            );
            // load library
            $BASE_URL = $this->config->item('base_url');
            $SHOPIFY_APIKEY = $this->config->item('shopify_api_key');
            $SHOPIFY_SECRET = $this->config->item('shopify_secret');
            $SHOPIFY_API_VERSION = $this->config->item('shopify_api_version');
            $curlData = array(
                'API_KEY' => $SHOPIFY_APIKEY,
                'API_SECRET' => $SHOPIFY_SECRET,
                'SHOP_DOMAIN' => $shop,
                'ACCESS_TOKEN' => $get_install_data['access_token']
            );
            $this->load->library('Shopify' , $curlData);
            // Get a list of webhooks Start
            $list_webhooks_param = array(
                'METHOD'        => 'GET',
                'URL'           => '/admin/webhooks.json'
            );
            $getListWebhooks = $this->shopify->call($list_webhooks_param);
            // App Uninstalled webhooks param Start
            $app_uninstalled_param = array(
                'METHOD' => 'POST',
                'URL'   => '/admin/webhooks.json',
                'DATA'  => array
                    (
                    "webhook"=>array 
                    (
                        "topic"=> "app/uninstalled",
                        "address"=> $BASE_URL."app_uninstalled",
                        "format"=>"json"
                    )
                )
            );
            // Create app uninstall webhook
            if (empty($getListWebhooks->webhooks)){
                $this->shopify->call($app_uninstalled_param);
            }else{
                foreach ($getListWebhooks->webhooks as $webhookKey ){
                    $webhookhandles[] = $webhookKey->address;
                }
                if (! in_array($BASE_URL."app_uninstalled", $webhookhandles)){
                    $this->shopify->call($app_uninstalled_param);
                }
            }


            $productParams = array(
                'METHOD'        => 'GET',
                'URL'           => '/admin/api/'.$SHOPIFY_API_VERSION.'/products.json'
            );
            $shopDatas = $this->shopify->call($productParams);
            // welcome view data
            $data_welcome = array(
                'shopDatas' => $shopDatas
            );

            $this->load->view('header', $data_header);
            $this->load->view('welcome', $data_welcome);
            $this->load->view('footer');
        }else {
            $this->auth($shop);
        }
    }
    
    // verify auth request while installing the app in shopify.
    public function auth($shop) {
        $data = array(
            'API_KEY' => $this->config->item('shopify_api_key'),
            'API_SECRET' => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN' => $shop,
            'ACCESS_TOKEN' => ''
        );
        $this->load->library('Shopify', $data); //load shopify library and pass values in constructor
        $scopes = array('read_orders','write_orders', 'read_products','write_products'); //what app can do
        $redirect_url = $this->config->item('redirect_url'); //redirect url specified in app setting at shopify
        $paramsforInstallURL = array(
            'scopes' => $scopes,
            'redirect' => $redirect_url
        );
        $permission_url = $this->shopify->installURL($paramsforInstallURL);  
        $this->load->view('auth/escapeIframe', ['installUrl' => $permission_url]);
    }

    // auth call back function.
    public function authCallback() {
        $code = $this->input->get('code');
        $shop = $this->input->get('shop');

        if(isset($code)) {
            $data = array(
                'API_KEY' => $this->config->item('shopify_api_key'),
                'API_SECRET' => $this->config->item('shopify_secret'),
                'SHOP_DOMAIN' => $shop,
                'ACCESS_TOKEN' => ''
            );
            $this->load->library('Shopify' , $data); //load shopify library and pass values in constructor
        }
        $accessToken = $this->shopify->getAccessToken($code);
        // save access token in the database
        $store_condition = array(
			'store' => $shop
		);
        $storetokens_check=$this->User_model->store_check('install', $store_condition);
        if($storetokens_check) {
            $setvalue= array(
                'access_token' => $accessToken
            );
            $wherecondition = array(
                'store' => $shop
            );
            $this->User_model->update_db('install', $wherecondition, $setvalue);
        }else {
            $insert_store=array(
                'store' => $shop,
                'access_token' => $accessToken
            );
            $this->User_model->insert_db('install', $insert_store);
        }
        // redirect to app url
        $SHOPIFY_APIKEY = $this->config->item('shopify_api_key');
        $SHOPIFY_DIRECTORY_NAME = $this->config->item('shopify_directory_name');
        redirect('https://'.$shop.'/admin/apps/'.$SHOPIFY_APIKEY.'/'.$SHOPIFY_DIRECTORY_NAME);
    }
    
}