<?php defined('BASE') OR exit('No direct script access allowed.');
class PapertypeModel extends Site
{
    
    function getDisplayOrder($orderBy = 'T'){
        $ENTITY         = TBL_PRODUCT_TYPE;
        $ExtraQryStr    = 1;
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
        return $this->selectSingle($ENTITY, $str, $ExtraQryStr); 
    }

    function typeCount($ExtraQryStr) {
        $ExtraQryStr .= " AND parentTypeId = 0 GROUP BY typeName";

        //return $this->rowCount(TBL_PRODUCT_TYPE, "typeId", $ExtraQryStr);
        $res = $this->selectAll(TBL_PRODUCT_TYPE, "typeId", $ExtraQryStr);
        if(is_array($res) && count($res) > 0) {
            return count($res);
        } else {
            return 0;
        }
    }

    function getTypeByLimit($ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " AND parentTypeId = 0 GROUP BY typeName ORDER BY typeId";
        return $this->selectMulti(TBL_PRODUCT_TYPE, "*", $ExtraQryStr, $start, $limit);
    }

    function typeUpdateById($params, $id){
        $CLAUSE = "typeId = ".addslashes($id);
        return $this->updateQuery(TBL_PRODUCT_TYPE, $params, $CLAUSE);
    }

    function newType($params) {
        return $this->insertQuery(TBL_PRODUCT_TYPE, $params);
	}

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_PRODUCT_TYPE, "*", $ExtraQryStr);
    }

    function getAllParentType($ExtraQryStr) {
        $ExtraQryStr .= " AND typeStatus = 'Y' GROUP BY typeName ";
        return $this->selectAll(TBL_PRODUCT_TYPE, "*", $ExtraQryStr);
    }

    function getSelectedSizeByName($typeName) {
        $ENTITY      = TBL_PRODUCT_SIZE." tps JOIN ".TBL_PRODUCT_TYPE." tpt ON (tps.sizeId = tpt.sizeId)";
        $ExtraQryStr = " tpt.typeName = '".addslashes($typeName)."'";
		return $this->selectAll($ENTITY, "tps.sizeName, tps.sizeId", $ExtraQryStr);
    }

    function getSelectedSubCategoryByName($typeName, $typeId) {
        $ExtraQryStr = " parentTypeId = '".addslashes($typeId)."' ";
        return $this->selectAll(TBL_PRODUCT_TYPE, "typeId, typeName", $ExtraQryStr);
    }

    function getCategoryByID($ExtraQryStr) {
        return $this->selectSingle(TBL_PRODUCT_TYPE, "*", $ExtraQryStr);
    }
}