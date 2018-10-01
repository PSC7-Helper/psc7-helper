<?php
/**
 * Created by PhpStorm.
 * User: f.wehrhausen
 * Date: 13.07.2018
 * Time: 13:55
 */

class ShopwareHelper{

    public static function getAllOrdernummberOfArticleByObjectIdentifier($uuid){
        $q = "Select ordernumber from s_articles_details a where a.articleID = (
                  select adapterIdentifier from plenty_identity p 
                    where p.adapterName = 'ShopwareAdapter' 
                          and p.objectType = 'Product' 
                          and p.objectIdentifier = ?)";
        $variationNumbers = Db::fetchAll($q, [$uuid]);
        return self::getAllOrdernummbersAsString($variationNumbers);
    }

    private static function getAllOrdernummbersAsString(array $ordernumbers){

        $numbers = "";
        $count = 0;
        foreach ($ordernumbers as $ordernumber){
            if ($count == 0){
                $numbers .= " ".$ordernumber["ordernumber"];
            }else{
                $numbers .= ", ".$ordernumber["ordernumber"];
            }
            $count++;
        }
        return $numbers;
    }

    public static function getObjectIdentifierByVariationId($variationId)
    {
        $q = "select * from plenty_identity p where p.objectType = 'product' and p.adapterIdentifier = ?";
        return Db::fetch($q, [$variationId]);
    }

    public static function getAllProductObjectIdentifier()
    {
        $q = "select * from plenty_identity p where p.objectType = 'product' AND p.adapterName = 'PlentymarketsAdapter'";
        return Db::fetchAll($q);
    }

    public static function getAllProductObjectIdentifierByCategoreyID($s_categoryID)
    {
        $q = "select objectIdentifier from 
                    (select p.objectIdentifier, a.id from plenty_identity p 
                    inner join 
                        (select a.id, d.ordernumber as articleNR, a.name from s_articles a inner join s_articles_details d on d.articleID = a.id) as a
                    on p.adapterIdentifier = a.id
                    where p.objectType = 'Product' and p.adapterName = 'ShopwareAdapter') as o 
                inner join s_articles_categories c
                on o.id = c.articleID
                where categoryID = ?
                group by articleID";
        return Db::fetchAll($q, [$s_categoryID]);
    }

    public static function getDBDataShopware(){
        $r = include("../config.php");

        return $r['db'];
    }

    public static function getAllCategoriesShopware(){
        $q = "Select c.id, c.description, c.parent From s_categories as c";
        return Db::fetchAll($q);
    }

    public static function getCatorieSelectionHTML(){
        $allShopwareCatgories = self::getAllCategoriesShopware();
        $html = "<div class=\"form-group\">
                  <label for=\"sel1\">Artikel dieser Kategorie abgleichen:</label>
                  <select class=\"form-control\" id=\"sel1\" name=\"s_categoryID\">";


        foreach ($allShopwareCatgories as $catgory){

            $html.= "<option value=".$catgory['id'].">".$catgory['description']."</option>";
        }

        $html .= "</select></div>";

        return $html;

    }

    public static function getArticleSelectionHTML(){
        $allProducts = self::getAllProductObjectIdentifier();
        $html = "<div class=\"form-group\">
                  <label for=\"sel1\">Plenty ArtikelID und alle VariantenNr.</label>
                  <select class=\"form-control selectpicker\" id=\"sel1\" name=\"uuid\" data-show-subtext=\"true\" data-live-search=\"true\">";


        foreach ($allProducts as $product){
            $uuid = $product['objectIdentifier'];
            $html.= "<option data-subtext=\"". self::getAllOrdernummberOfArticleByObjectIdentifier($uuid) ."\" value=".$uuid.">".$product['adapterIdentifier']."</option>";
        }

        $html .= "</select></div>";

        return $html;

    }


    public static function getOrderNumberByOrderID($orderID){

        $q = "select d.ordernumber from s_order_details d where d.orderID = ?";
        return Db::fetch($q,[$orderID]);
    }

    public static function getMappedOrdersHTML(){

        $allOrders = self::getAllOrders();
        $html = "<table class='table'><th>Shopware OrderID:</th><th>Plenty OrderID:</th><th>Aktion</th>";
        foreach ($allOrders as $orders){

            $plentyOrderID = (!empty($orders['p_OrderID'])) ? $orders['p_OrderID'] : 'keine Plenty Auftrag gefunden';
            $html .=
            "<tr><form action=\"\" method='post' class='form-inline'>
             
                
                <td><label>".self::getOrderNumberByOrderID($orders['s_OrderID'])["ordernumber"]."</label></td>
                
                <td><label>".$plentyOrderID."</label></td>
             
                <td><input type=\"hidden\" name=\"uuid\" value=\"".$orders['objectIdentifier']."\">
                <button type=\"submit\" class=\"btn btn-primary\">Erneut abgleichen</button>
                </td>
            </form></tr>";
        }
        $html .= "</table>";

        return $html;

    }

    public static function getNewPlentyOrderID($uuid){
        $q = "Select * from plenty_identity p where p.objectIdentifier = ? and p.adapterName = 'PlentymarketsAdapter'";
        return Db::fetch($q,[$uuid]);
    }

    public static function getBacklogCount(){
        $q = "select count(id) as count from plenty_backlog";
        return Db::fetch($q);

    }
    public static function getCronjobsInfo($name){
        $q = "SELECT a.id, a.name, a.action, a.elementID, a.data, a.next, a.start, a.interval, a.active, a.disable_on_error, a.end, a.inform_template, a.inform_mail, a.pluginID
                                           FROM s_crontab a
                                          WHERE a.name = ?
                                          LIMIT 1 ";
        return Db::fetch($q,[$name]);

    }

    public static function getPlentyApiLoginData(){
        $q = "select * from plenty_config c Where c.name like 'rest%'";
        $r = Db::fetchAll($q);

        return ['url'=>$r[0]["value"],
                'user'=>$r[1]["value"],
                'pw' =>$r[2]["value"]
        ];

    }

    public static function getUUID($type, $id, $adapterName){
        $q = "Select * from plenty_identity p where p.adapterIdentifier = ? and p.objectType = ? and p.adapterName= ?";
        return Db::fetch($q,[$id, $type, $adapterName])["objectIdentifier"];
    }

    public static function getSupplierHTML(){

        $allSuppliers = self::getAllSuplierInfos();
        $html = "<table class='table'><th>HerstellerID</th><th>Herstellername</th><th>Logo?</th><th>Anzahl der Artikel</th><th>Aktion</th>";
        foreach ($allSuppliers as $supplier){

            $supplierLogo = (!empty($supplier['img'])) ? 'vorhanden' : 'nicht vorhanden';
            $supplierID = (int)$supplier['id'];
            $countArticles = $supplier['count(a.id)'];
            $supplierName = $supplier['name'];

            if ($countArticles <= 0){
                $rowHTMLClass = "table-danger";
            } elseif ($supplierLogo == "nicht vorhanden"){
                $rowHTMLClass = "table-warning";
            } else {
                $rowHTMLClass = "table-success";
            }

            $html .=
                "<tr class='".$rowHTMLClass."'><form action=\"\" method='post' class='form-inline'>
             
                
                <td><label>".$supplierID."</label></td>
                <td><label>".$supplierName."</label></td>
                <td><label>".$supplierLogo."</label></td>
                <td><label>".$countArticles."</label></td>
             
                <td><input type=\"hidden\" name=\"uuid\" value=\"".self::getUUID('Manufacturer', $supplierID, 'ShopwareAdapter')."\">
                <button type=\"submit\" class=\"btn btn-primary\">Erneut abgleichen</button>
                </td>
            </form></tr>";
        }
        $html .= "</table>";

        return $html;

    }

    public static function getAllSuplierInfos(){

        $q = "select count(a.id), s.* from s_articles_supplier s
                left join
                s_articles a
                on s.id = a.supplierID
                group by a.supplierID
                Order by count(a.id) DESC";
        $r = Db::fetchAll($q);

        return $r;

    }

    public static function getAllOrders(){
        $q = "Select s.objectIdentifier, s.s_OrderID, p.p_OrderID from
                (Select p.adapterIdentifier as s_OrderID, p.objectIdentifier from plenty_identity p where p.objectType = \"order\" and p.adapterName = \"ShopwareAdapter\") as s
                left join
                (Select p.adapterIdentifier as p_OrderID, p.objectIdentifier from plenty_identity p where p.objectType = \"order\" and p.adapterName = \"PlentymarketsAdapter\") as p
                on s.objectIdentifier = p.objectIdentifier
                Order By s.s_OrderID DESC";
        return Db::fetchAll($q);
    }

}
