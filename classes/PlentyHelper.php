<?php

use PlentyApi\Client;

class PlentyHelper
{
    /**
     * @var \PlentyApi\Client $client
     */
    public static $client;
    /**
     * @var \PlentyApi\Client $client2
     */
    public static $client2;
    public static $items;
    public static $itemsByArtNo;
    public static $paymentMethods;
    private static $warehouses;
    private static $variationPriceCache;
    private static $supplierAddressCache;

    public static function getClient($id = 1)
    {
        $plenty = ShopwareHelper::getPlentyApiLoginData();

        if ($id <= 1) {
            if (empty(self::$client)) {
                PlentyHelper::$client = new Client($plenty["url"], $plenty["user"], $plenty["pw"]);
            }
            return self::$client;
        } else {
            if (empty(self::$client2)) {
                PlentyHelper::$client2 = new Client(Config::$plentyUrl, 'API_teleropa', 'D!G!nova10HD');
            }
            return self::${'client' . $id};
        }
    }

    public static function getAllMappedOrders(){

        $r = array();
        $allOrdersShopware = ShopwareHelper::getAllOrders();

        foreach ($allOrdersShopware as $s_order){

            $r[] = self::getClient()->getOrderByXxternalOrderId($s_order["ordernumber"]);

        }
    }

}