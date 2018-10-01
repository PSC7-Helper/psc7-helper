<?php

namespace PlentyApi;

class Client extends AbstractClient
{
    /* ORDERS */

    public function addOrder($data)
    {
        $result = $this->_call('orders', $data);
        return $result["data"];
    }

    public function getOrder($id)
    {
        $result = $this->_call('orders/' . $id);
        return $result["data"];
    }

    public static function getOrderByXxternalOrderId($externalOrderId){
        $result = $this->_call('orders?' . $externalOrderId);
        return $result["data"];
    }

    public function getOrders($queryString)
    {
        $result = $this->_call('orders?' . $queryString);
        return $result["data"];
    }

    public function getOrdersDocuments($orderId)
    {
        $result = $this->_call('orders/' . $orderId . '/documents');
        return $result["data"];
    }

    public function getOrdersDocumentsSums($page = 1)
    {
        $result = $this->_call('orders/documents/accounting_summary?itemsPerPage=50&page=' . (int)$page);
        return $result["data"];
    }

    public function getNewOrdersDocumentsByType($type, $from = null, $to = null)
    {
        $result = $this->_call('orders/documents/' . $type . '?with[]=references&itemsPerPage=100000&' . ($from !== null ? 'createdAtFrom=' . urlencode($from) . '&' : '') . ($to !== null ? 'createdAtTo=' . urlencode($to) . '&' : ''));
        return $result["data"];
    }

    public function updateOrder($id, $data)
    {
        $result = $this->_call('orders/' . $id, $data, [], 'PUT');
        return $result["data"];
    }

    public function addAddress($data)
    {
        $result = $this->_call('orders/addresses', $data);
        return $result["data"];
    }

    public function addShipping($data)
    {
        $result = $this->_call('orders/shipping/shipping_information', $data);
        return $result["data"];
    }

    public function getOrdersReferrers()
    {
        $result = $this->_call('orders/referrers');
        return $result["data"];
    }

    /* ITEMS AND STOCKS */
    public function getItemByBarcode($barcode)
    {
        $result = $this->_call('items/variations?barcode=' . $barcode);
        return $result["data"];
    }

    public function getVariationStocks($itemId, $variationId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/stock');
        return $result["data"];
    }

    public function getWarehouses()
    {
        $result = $this->_call('stockmanagement/warehouses', [], [], null, 5 * 86400);
        return $result["data"];
    }

    public function getStocks($warehouseId, $page = 1)
    {
        $result = $this->_call('stockmanagement/warehouses/' . $warehouseId . '/stock/storageLocations?page=' . $page . '&itemsPerPage=1000', [], [], null, 1800);
        return $result["data"];
    }

    public function getStockMovements($itemId, $variationId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/stock/movements');
        return $result["data"];
    }

    public function getStockMovementsByWarehouse($warehouseId, $from = null, $to = null)
    {

        $result = $this->_call('stockmanagement/warehouses/' . $warehouseId . '/stock/movements?itemsPerPage=100000&' . ($from !== null ? 'createdAtFrom=' . urlencode($from) . '&' : '') . ($to !== null ? 'createdAtTo=' . urlencode($to) . '&' : ''));
        return $result["data"];
    }

    public function getStocksWithPrice($warehouseId, $page = 1)
    {
        $result = $this->_call('stockmanagement/warehouses/' . $warehouseId . '/stock?page=' . $page . '&itemsPerPage=1000', [], [], null, 0);
        return $result["data"];
    }

    public function updateStocks($warehouseId, $corrections)
    {
        $result = $this->_call('stockmanagement/warehouses/' . $warehouseId . '/stock/correction', $corrections, [], 'PUT');
        return ($result["info"]["http_code"] == 200);
    }

    public function incomingStocks($warehouseId, $items)
    {
        $result = $this->_call('stockmanagement/warehouses/' . $warehouseId . '/stock/bookIncomingItems', $items, [], 'PUT');
        return ($result["info"]["http_code"] == 200);
    }

    public function getVariations($page = 1, $cacheTime = 0)
    {
        $result = $this->_call('items/variations?page=' . $page . '&itemsPerPage=50&with=variationCategories,variationBarcodes', [], [], null, $cacheTime, true);
        return $result["data"];
    }

    public function getVariation($itemId, $variationId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId);
        return $result["data"];
    }

    public function updateVariation($itemId, $variationId, $data)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId, $data, [], 'PUT');
        return $result["data"];
    }

    public function getVariationPrices($itemId, $variationId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_sales_prices');
        return $result["data"];
    }

    public function getVariationPrice($itemId, $variationId, $priceId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_sales_prices/' . $priceId);
        return $result["data"];
    }

    public function getPricesTypes()
    {
        $result = $this->_call('items/sales_prices');
        return $result["data"];
    }

    public function updatePrice($itemId, $variationId, $priceId, $data)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_sales_prices/' . $priceId, $data, [], 'PUT');
        return $result["data"];
    }

    public function addPrice($itemId, $variationId, $data)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_sales_prices', $data);
        return $result["data"];
    }


    public function getItem($id)
    {
        $result = $this->_call('items/' . $id);
        return $result["data"];
    }

    public function getItems(array $parameters)
    {
        $result = $this->_call('items?itemsPerPage=10000000&' . http_build_query($parameters));
        return $result["data"];
    }

    public function createItem($data)
    {
        $result = $this->_call('items', $data);
        return $result["data"];
    }

    public function updateItem($data)
    {
        $result = $this->_call('items/' . $data["id"], $data, [], 'PUT');
        return $result["data"];
    }

    public function updateVariationTexts($itemId, $variationId, $data, $lang = 'de')
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/descriptions/' . $lang, $data, [], 'PUT');
        return $result["data"];
    }

    public function getImages($itemId)
    {
        $result = $this->_call('items/' . $itemId . '/images');
        return $result["data"];
    }

    public function addImage($itemId, $data)
    {
        $result = $this->_call('items/' . $itemId . '/images/upload', $data);
        return $result["data"];
    }

    public function getBarcodes()
    {
        $result = $this->_call('items/barcodes');
        return $result["data"];
    }

    public function updateBarcode($itemId, $variationId, $barcode, $barcodeId = 1)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_barcodes/' . (int)$barcodeId, [
            'barcodeId' => $barcodeId,
            'variationId' => $variationId,
            'code' => $barcode
        ], [], 'PUT');
        return $result["data"];
    }

    public function linkImageToVariation($itemId, $variationId, $imageId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_images', [
            'itemId' => $itemId,
            'variationId' => $variationId,
            'imageId' => $imageId
        ]);
        return $result["data"];
    }

    public function getVariationProperties($itemId, $variationId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_properties');
        return $result["data"];
    }

    public function addVariationProperty($itemId, $variationId, $propertyId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_properties', [
            'variationId' => $variationId,
            'propertyId' => $propertyId
        ]);
        return $result["data"];
    }

    public function addVariationPropertyBulk($data)
    {
        $result = $this->_call('items/variations/variation_properties', $data);
        return $result["data"];
    }


    public function addVariationPropertyText($itemId, $variationId, $propertyId, $data)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_properties/' . $propertyId . '/texts', $data);
        return $result["data"];
    }

    public function updateVariationPropertyText($itemId, $variationId, $propertyId, $data)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_properties/' . $propertyId . '/texts/de', $data, [], 'PUT');
        return $result["data"];
    }


    public function getMarkets()
    {
        $result = $this->_call('markets/settings');
        return $result["data"];
    }


    public function getVariationMarkets($itemId, $variationId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_markets');
        return $result["data"];
    }

    public function addVariationMarket($itemId, $variationId, $marketId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_markets', [
            'variationId' => $variationId,
            'marketId' => $marketId
        ]);
        return $result["data"];
    }

    public function removeVariationMarket($itemId, $variationId, $marketId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_markets/' . $marketId, [], [], 'DELETE');
        return $result["data"];
    }

    public function getProperties()
    {
        $result = $this->_call('items/properties?itemsPerPage=10000');
        return $result["data"];
    }

    public function addProperty($data)
    {
        $result = $this->_call('items/properties', $data);
        return $result["data"];
    }

    public function addPropertyName($propertyId, $data)
    {
        $result = $this->_call('items/properties/' . $propertyId . '/names', $data);
        return $result["data"];
    }


    public function getPropertyGroups()
    {
        $result = $this->_call('items/property_groups?itemsPerPage=10000');
        return $result["data"];
    }

    public function addPropertyGroup($data)
    {
        $result = $this->_call('items/property_groups', $data);
        return $result["data"];
    }

    public function addPropertyGroupName($propertyGroupId, $data)
    {
        $result = $this->_call('items/property_groups/' . $propertyGroupId . '/names', $data);
        return $result["data"];
    }

    public function getPropertySelections($propertyId)
    {
        $result = $this->_call('items/properties/' . $propertyId . '/selections');
        return $result["data"];
    }

    public function getSuppliers($itemId, $variationId, $cacheTime = 43200)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_suppliers', [], [], null, $cacheTime);
        return $result["data"];
    }

    public function updateVariationSupplier($itemId, $variationId, $variationSupplierId, $data)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_suppliers/' . $variationSupplierId, $data, [], 'PUT');
        return $result["data"];
    }

    public function setVariationSupplier($itemId, $variationId, $data)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_suppliers', $data);
        return $result["data"];
    }

    public function setVariationCategory($itemId, $variationId, $categoryId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_categories', [
            'variationId' => $variationId,
            'categoryId' => $categoryId
        ]);
        return $result["data"];
    }

    public function getVariationCategories($itemId, $variationId)
    {
        $result = $this->_call('items/' . $itemId . '/variations/' . $variationId . '/variation_categories');
        return $result["data"];

    }

    public function getManufacturers()
    {
        $result = $this->_call('items/manufacturers?itemsPerPage=10000');
        return $result["data"];
    }

    public function getManufacturer($id)
    {
        $result = $this->_call('items/manufacturers/'.$id);
        return $result["data"];
    }

    public function createManufacturer($data)
    {
        $result = $this->_call('items/manufacturers', $data);
        return $result["data"];
    }

    public function getItemShippingProfiles($itemId)
    {
        $result = $this->_call('items/' . $itemId . '/item_shipping_profiles');
        return $result["data"];
    }

    public function addItemShippingProfile($itemId, $profileId)
    {
        $result = $this->_call('items/' . $itemId . '/item_shipping_profiles', [
            'itemId' => $itemId,
            'profileId' => $profileId
        ]);
        return $result["data"];
    }

    public function removeItemShippingProfile($itemId, $profileLinkId)
    {
        $result = $this->_call('items/' . $itemId . '/item_shipping_profiles/' . $profileLinkId, [], [], 'DELETE');
        return $result["data"];
    }

    /* CATEGORIES */

    public function getCategories($page = 1)
    {
        $result = $this->_call('categories?itemsPerPage=50&page=' . $page);
        return $result["data"];
    }

    public function getCategoriesWithClients($page = 1)
    {
        $result = $this->_call('categories?with=clients&itemsPerPage=50&page=' . $page);
        return $result["data"];
    }

    public function getSubCategoriesIds($categoryId)
    {
        $result = $this->_call('categories?parentId=' . (int)$categoryId);
        $ids = array_column($result["data"]["entries"], 'id');
        return $ids;
    }

    public function createCategory($data)
    {
        $result = $this->_call('categories', $data);
        return $result["data"];
    }

    /*LISTINGS*/
    public function createListing($data)
    {
        $result = $this->_call('listings', $data);
        return $result["data"];
    }

    public function createMarketListing($data)
    {
        $result = $this->_call('listings/markets', $data);
        return $result["data"];
    }

    /* PAYMENTS */
    public function getPaymentMethods()
    {
        $result = $this->_call('payments/methods?itemsPerPage=5000');
        return $result["data"];
    }


    public function renamePaymentMethod($data)
    {
        $result = $this->_call('payments/methods', $data, [], 'PUT');
        var_dump($result);
        return $result["data"];
    }

    /* ACCOUNTS AND CONTACTS */

    public function getContact($contactId)
    {
        $result = $this->_call('accounts/contacts/' . $contactId);
        return $result["data"];
    }

    public function getAddresses($contactId)
    {
        $result = $this->_call('accounts/contacts/' . $contactId . '/addresses');
        return $result["data"];
    }

    public function getListings($filter = [])
    {
        $result = $this->_call('listings?' . http_build_query($filter));
        return $result["data"];
    }

}
