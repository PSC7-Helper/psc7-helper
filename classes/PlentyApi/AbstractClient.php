<?php
namespace PlentyApi;

class AbstractClient
{
    const ORDER_PROPERTY_WAREHOUSE = 1;
    const ORDER_PROPERTY_SHIPPING_PROFILE = 2;
    const ORDER_PROPERTY_PAYMENT_METHOD = 3;
    const ORDER_PROPERTY_PAYMENT_STATUS = 4;
    const ORDER_PROPERTY_EXTERNAL_SHIPPING_PROFILE = 5;
    const ORDER_PROPERTY_DOCUMENT_LANGUAGE = 6;
    const ORDER_PROPERTY_EXTERNAL_ORDER_ID = 7;
    const ORDER_PROPERTY_CUSTOMER_SIGN = 8;
    const ORDER_PROPERTY_DUNNING_LEVEL = 9;
    const ORDER_PROPERTY_SELLER_ACCOUNT = 10;
    const ORDER_PROPERTY_WEIGHT = 11;
    const ORDER_PROPERTY_WIDTH = 12;
    const ORDER_PROPERTY_LENGTH = 13;
    const ORDER_PROPERTY_HEIGHT = 14;
    const ORDER_PROPERTY_FLAG = 15;
    const ORDER_PROPERTY_EXTERNAL_TOKEN_ID = 16;
    const ORDER_PROPERTY_EXTERNAL_ITEM_ID = 17;
    const ORDER_PROPERTY_COUPON_CODE = 18;
    const ORDER_PROPERTY_COUPON_TYPE = 19;

    const ORDER_DATE_DELETED = 1;
    const ORDER_DATE_CREATED = 2;
    const ORDER_DATE_PAID = 3;
    const ORDER_DATE_UPDATE = 4;
    const ORDER_DATE_COMPLETED = 5;
    const ORDER_DATE_RETURN = 6;
    const ORDER_DATE_PAYMENT_DUE = 7;
    const ORDER_DATE_ESTIMATED_SHIPPING = 8;

    const ORDER_ADDRESS_BILLING = 1;
    const ORDER_ADDRESS_DELIVERY = 2;
    const ORDER_ADDRESS_SENDER = 3;
    const ORDER_ADDRESS_RETURN = 4;
    const ORDER_ADDRESS_CLIENT = 5;
    const ORDER_ADDRESS_CONTRACTOR = 6;
    const ORDER_ADDRESS_WAREHOUSE = 7;

    const ORDER_ITEM_VARIATION = 1;
    const ORDER_ITEM_ITEM_BUNDLE = 2;
    const ORDER_ITEM_BUNDLE_COMPONENT = 3;
    const ORDER_ITEM_PROMOTIONAL_COUPON = 4;
    const ORDER_ITEM_GIFT_CARD = 5;
    const ORDER_ITEM_SHIPPING_COSTS = 6;
    const ORDER_ITEM_PAYMENT_SURCHARGE = 7;
    const ORDER_ITEM_GIFT_WRAP = 8;
    const ORDER_ITEM_UNASSIGNED_VARIATION = 9;
    const ORDER_ITEM_DEPOSIT = 10;
    const ORDER_ITEM_ORDER = 11;

    protected $urlBase;
    protected $user;
    protected $password;
    protected $accessToken;
    protected $tokenType;
    protected $expires;
    protected $refreshToken;

    public function __construct($urlBase, $user, $password){
        $this->urlBase = $urlBase;
        $this->user = $user;
        $this->password = $password;
        $this->doLoginAction();
    }

    protected function doLoginAction($try = 1){
        $result = $this->_call('login', ['username'=>$this->user, 'password'=>$this->password]);
        if(empty($result["data"])){
            if($try < 5){
                sleep(1);
                $this->doLoginAction($try+1);
            }else{
                throw new \Exception('Login failed');
            }
        }else{
            $this->accessToken = $result["data"]["accessToken"];
            $this->tokenType = $result["data"]["tokenType"];
            $this->expires = time()+$result["data"]["expiresIn"];
            $this->refreshToken = $result["data"]["refreshToken"];
        }
    }

    protected function _call($action, $post=[], $header = [], $requestType = null, $cacheTime = 0, $writeCache = false, $count = 0) {
        $cacheFile = 'cache/'.preg_replace('/[^a-z0-9]+/i', '_', $action).'_'.md5(serialize($post)).'.data';
        if($cacheTime > 0){
            if(file_exists(($cacheFile)) && filemtime($cacheFile) > (time()-$cacheTime)){
                $data = unserialize(file_get_contents($cacheFile));
                if($data != null && $data["data"] != null && empty($data["data"]["error"])) {
                    return $data;
                }
            }
        }

        if(!empty($this->tokenType) && !empty($this->accessToken)){
            $header[] = 'Authorization: '.$this->tokenType.' '.$this->accessToken;
        }



        $ch = curl_init($this->urlBase . $action);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if(!empty($post)){
            curl_setopt($ch, CURLOPT_POST, 1);
            if(is_array($post)){
                $post = json_encode($post);
                $header[] = 'Content-Type: application/json';
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if($requestType !== null){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);

        $outputArray = json_decode($output, true);
        //var_dump($output, $info);
        if($info["http_code"] != 200){
            if(in_array($info["http_code"], [503,400,500,0]) && $count < 3){
                usleep(pow($count, 2)*100000);
                return static::_call($action, $post, $header, $requestType, $cacheTime, $writeCache, ++$count);
            }else{
                //throw new \Exception ('Plenty Api Error: '.$action.' '.print_r($outputArray, true)."\n".print_r($info, true)."\naction:".$action."\ndata: ".print_r($post, true));
            }
        }
        
        $return = ['info'=>curl_getinfo($ch), 'data'=>$outputArray];
        if($cacheTime > 0 || $writeCache){
            file_put_contents($cacheFile, serialize($return));
        }

        return $return;
    }

}
