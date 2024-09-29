<?php defined('BASE') OR exit('No direct script access allowed.');
class VendorsModel extends SITE
{
    function searchLinkedPages($mid, $parent_dir, $srch, $start, $limit)
    {
        if ($mid == 0) {

            $ENTITY      = TBL_MENU_CATEGORY . " mc JOIN " . TBL_MODULE . " m ON (m.menu_id = mc.moduleId)";

            $ExtraQryStr = "mc.categoryName like '%" . addslashes($srch) . "%' AND mc.status = 'Y' AND m.parent_dir = '" . addslashes($parent_dir) . "' AND m.child_dir = '' ORDER BY mc.displayOrder";

            $data = $this -> selectMulti($ENTITY, "mc.categoryId id, mc.categoryName page, mc.permalink", $ExtraQryStr, $start, $limit);

        } else {

            $ENTITY      = TBL_CONTENT . " c JOIN " . TBL_MENU_CATEGORY . " mc ON (c.menucategoryId = mc.categoryId) JOIN " . TBL_MODULE . " m ON (m.menu_id = mc.moduleId)";
            $ExtraQryStr = "c.contentHeading like '%" . addslashes($srch) . "%' AND mc.status = 'Y' AND m.parent_dir = '" . addslashes($parent_dir) . "' AND m.child_dir = '' ORDER BY mc.displayOrder";


            $data = $this -> selectMulti($ENTITY, "c.contentID id, c.contentHeading page, c.permalink", $ExtraQryStr, $start, $limit);
        }

        return $data;
    }


    function getDisplayOrder($orderBy = 'T'){
        $ENTITY         = TBL_VENDORS;
        $ExtraQryStr    = 1;
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
        return $this->selectSingle($ENTITY, $str, $ExtraQryStr); 
    }

    function vendorsCount($ExtraQryStr) {
        return $this->rowCount(TBL_VENDORS, "vendorId", $ExtraQryStr);
    }

    // product count
    function productCount($ExtraQryStr){
        return $this->rowCount(TBL_PRODUCT, "productId", $ExtraQryStr);
    }

    function getVendorsByLimit($ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " ORDER BY displayOrder";
        return $this->selectMulti(TBL_VENDORS, "*", $ExtraQryStr, $start, $limit);
    }

    function vendorUpdateById($params, $id){
        $CLAUSE = "vendorId = ".addslashes($id);
        return $this->updateQuery(TBL_VENDORS, $params, $CLAUSE);
    }

    function newVendor($params) {
        return $this->insertQuery(TBL_VENDORS, $params);
	}

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_VENDORS, "*", $ExtraQryStr);
    }

    function getAllVendors() {
        $ExtraQryStr = " vendorStatus = 'Y' ORDER BY displayOrder";
        return $this->selectAll(TBL_VENDORS, "*", $ExtraQryStr);
    }

    function getVendorwiseOrder($vendorId, $from_date, $to_date, $start, $limit) {
        $sql = $start.", ".$limit;
        $ENTITY = TBL_PRODUCT_PURCHASE_STOCK. " tps INNER JOIN ".TBL_PRODUCT." tp ON (tps.productId = tp.productId) INNER JOIN ".TBL_PRODUCT_SIZE." ps ON (tp.sizeId = ps.sizeId) 
                    INNER JOIN ".TBL_PRODUCT_GSM." pg ON (tp.gsmId = pg.gsmId) 
                    INNER JOIN ".TBL_PRODUCT_TYPE." pt ON (tp.typeId = pt.categoryId)";
        
        if(!empty($from_date) && !empty($to_date)){
            $ExtraQryStr = " vendorId = $vendorId AND tps.purchaseDate BETWEEN '".$to_date."' AND '".$from_date."' ORDER BY purchaseStockId ";
        }else{
            $ExtraQryStr = " vendorId = $vendorId ORDER BY purchaseStockId LIMIT $sql ";
        }

        // if($limit != 0)
        // {
        //     $ExtraQryStr .= 'DESC LIMIT '.$limit;
        // }

        return $this->selectAll($ENTITY, "tps.*, tp.productName, ps.sizeName, pg.gsmName, pt.categoryName", $ExtraQryStr);
    }

    function getVendorwiseOrderGroup($vendorId) {

        $orderData = array();

        $ENTITYONE = TBL_PRODUCT_PURCHASE_STOCK. " tps";
        
        $ExtraOneQryStr = " vendorId = $vendorId GROUP BY purchaseDate ORDER BY purchaseStockId LIMIT 3 ";

        $recordOne = $this->selectAll($ENTITYONE, "tps.purchaseStockId, tps.purchaseDate", $ExtraOneQryStr);

            foreach($recordOne as $ro)
            {
                $ENTITY = TBL_PRODUCT_PURCHASE_STOCK. " tps";
                $ExtraQryStr = " vendorId = $vendorId AND purchaseDate = '".$ro['purchaseDate']."' ";

                $record = $this->selectAll($ENTITY, "tps.*", $ExtraQryStr);

                $orderData['orderDate'][] = $ro['purchaseDate'];

                $totalQty = 0;
                $totalAmt = 0;

                foreach($record as $r)
                {
                    $totalQty = $totalQty + $r['purchaseQty'];
                    $totalAmt = $totalAmt + $r['purchasePrice'];
                }

                $orderData['totalQty'][] = $totalQty;
                $orderData['totalAmt'][] = $totalAmt;
            }
        
        return $orderData;
    }

    function getVendorsDetails($vendorId) {
        $ExtraQryStr = " vendorId = ".$vendorId." ";
        return $this->selectSingle(TBL_VENDORS, "*", $ExtraQryStr);
    }

}