<?php defined('BASE') OR exit('No direct script access allowed.');
class ReadypackageModel extends ContentModel
{
    function getDisplayOrder($orderBy = 'T'){
        $ENTITY         = TBL_PRODUCT_GSM;
        $ExtraQryStr    = 1;
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
        return $this->selectSingle($ENTITY, $str, $ExtraQryStr); 
    }


    function gsmCount($ExtraQryStr) {
        $ENTITY = TBL_PRODUCT_GSM." tpg ";
        $ExtraQryStr .= " GROUP BY tpg.gsmName";
        $res = $this->selectAll($ENTITY, "tpg.gsmId", $ExtraQryStr);
        
        if(is_array($res) && count($res) > 0) {
            return count($res);
        } else {
            return 0;
        }
        //return $this->rowCount($ENTITY, "tpg.gsmId", $ExtraQryStr, true);
    }

    function getreadyProductByLimit($ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " ORDER BY trp.displayOrder";

        $ENTITY = TBL_READY_PRODUCT. " trp INNER JOIN ".TBL_PRODUCT." tp ON (tp.productId = trp.productId)
        INNER JOIN ".TBL_END_PRODUCT." tep ON (tep.epId = trp.endproductId)
        ";
        return $this->selectMulti($ENTITY, "trp.*,tp.*,tep.*", $ExtraQryStr, $start, $limit);
    }

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_PACKAGING, "*", $ExtraQryStr);
    }

    function getAllCategorywiseProductlist() {
        $ExtraQryStr = "status = 'Y'";
        $categoryWiseProduct = $this->selectAll(TBL_PRODUCT_TYPE, "*", $ExtraQryStr);


        $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PACKAGING." tpg ON (tpg.productId = tp.productId)
        ";

        foreach($categoryWiseProduct as $key=>$val){
            $metaArray = array();
            $childExtraQryStr = "tp.status = 'Y' AND tp.typeId=".addslashes($val['categoryId']);

            $childProductName = $this->selectAll($ENTITY, "*", $childExtraQryStr);

            if(count($childProductName) >= 1){
                foreach($childProductName as $cpt => $crow)
                {
                    $metaArray[$cpt]['productId'] = $crow['productId'];
                    $metaArray[$cpt]['productName'] = $crow['productName'];
                }

                if(!empty($metaArray))
                {
                    $categoryWiseProduct[$key]['childType'] = $metaArray;
                }
            }
        }

        return $categoryWiseProduct;
        // return $this->selectAll(TBL_PRODUCT, "*", $ExtraQryStr);
    }

    function getAllEndProductlist() {
        $ExtraQryStr = "epStatus = 'Y'";
        return $this->selectAll(TBL_END_PRODUCT, "*", $ExtraQryStr);
    }

    function getUnpackingProductQty($productId, $endProductId) {

        $proExtraQryStr = "productId = '".$productId."' ";
        $proDataFetch = $this->selectSingle(TBL_PRODUCT, "*", $proExtraQryStr);

        $ExtraQryStr = "endproductId = '".$endProductId."' AND gsmId = '".$proDataFetch['gsmId']."' AND sizeId = '".$proDataFetch['sizeId']."' AND typeId = '".$proDataFetch['typeId']."' ";
        
        return $this->selectSingle(TBL_UNPACKING_PRODUCT, "*", $ExtraQryStr);
    }

    function getPackingType($productId, $endProductId) {
        $ExtraQryStr = "productId = ".$productId." AND endProductId = ".$endProductId." ";
        return $this->selectAll(TBL_PACKAGING, "*", $ExtraQryStr);
    }

    function insertPackaging($params) {
        return $this->insertQuery(TBL_PACKAGING, $params);
	}

    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }

    function readyProductCount($ExtraQryStr) {
        return $this->rowCount(TBL_READY_PRODUCT, 'readyProductId', $ExtraQryStr);
	}

    function getPackagingById($ExtraQryStr) {
        return $this->selectSingle(TBL_PACKAGING, "*", $ExtraQryStr); 
    }

    function deletePackagingById($id){
        return $this->executeQuery("DELETE FROM ".TBL_PACKAGING." WHERE packagingId = ".addslashes($id));
    }

    function updatePackaging($params, $id){
        $CLAUSE = "packagingId = ".addslashes($id);
        return $this->updateQuery(TBL_PACKAGING, $params, $CLAUSE);
    }

    function getProductPackingDetails($packingType)
    {
        $ExtraQryStr = "packagingId = '".$packingType."'";
        return $this->selectSingle(TBL_PACKAGING, "packCount, boxCount", $ExtraQryStr); 
    }

    function getNoOfProducts($productId)
    {
        $ExtraQryStr = "productId = '".$productId."' ";
        return $this->selectSingle(TBL_PRODUCT, "piecesPerKg", $ExtraQryStr); 
    }

    function insertStockData($params){
        return $this->insertQuery(TBL_READY_PRODUCT, $params);
    }

    function updateUnpackStock($productId, $currReadyProductQty) {
        
        $ENTITY = TBL_UNPACKING_PRODUCT ." pas";
        $ExtraQryStr = "pas.productId = ".$productId." ";

        $getStockAmnt = $this->selectSingle($ENTITY, "pas.*", $ExtraQryStr);

        $newParam['totalQty'] = $getStockAmnt['totalQty'] - $currReadyProductQty;

        $CLAUSE = "productId = ".addslashes($productId);

        return $this->updateQuery(TBL_UNPACKING_PRODUCT, $newParam, $CLAUSE);
    }
}