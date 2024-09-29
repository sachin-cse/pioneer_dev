<?php defined('BASE') OR exit('No direct script access allowed.');
class PackagingModel extends ContentModel
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


    function getpackagingByLimit($ExtraQryStr, $start, $limit) {

        $ENTITY = TBL_PACKAGING. " tpg INNER JOIN ".TBL_PRODUCT." tp ON (tpg.productId = tp.productId)
                                    ";
        $ExtraQryStr .= " ORDER BY tpg.displayOrder";
        return $this->selectMulti($ENTITY, "tpg.*,tp.productName", $ExtraQryStr, $start, $limit);
    }

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_PACKAGING, "*", $ExtraQryStr);
    }

    function getAllCategorywiseProductlist() {
        $ExtraQryStr = "status = 'Y'";
        $categoryWiseProduct = $this->selectAll(TBL_PRODUCT_TYPE, "*", $ExtraQryStr);

        foreach($categoryWiseProduct as $key=>$val){
            $metaArray = array();
            $childExtraQryStr = "status = 'Y' AND typeId=".addslashes($val['categoryId']);

            $childProductName = $this->selectAll(TBL_PRODUCT, "*", $childExtraQryStr);

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

    function insertPackaging($params) {
        return $this->insertQuery(TBL_PACKAGING, $params);
	}

    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }

    function packagingCount($ExtraQryStr) {
        return $this->rowCount(TBL_PACKAGING, 'packagingId', $ExtraQryStr);
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
}