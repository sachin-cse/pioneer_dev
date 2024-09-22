<?php defined('BASE') OR exit('No direct script access allowed.');
class PapersizeModel extends Site
{
    function getDisplayOrder($orderBy = 'T'){
        $ENTITY         = TBL_PRODUCT_SIZE;
        $ExtraQryStr    = 1;
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
        return $this->selectSingle($ENTITY, $str, $ExtraQryStr); 
    }

    function sizeCount($ExtraQryStr) {
        return $this->rowCount(TBL_PRODUCT_SIZE, "sizeId", $ExtraQryStr);
    }

    function getSizeByLimit($ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " ORDER BY sizeId DESC";
        return $this->selectMulti(TBL_PRODUCT_SIZE, "*", $ExtraQryStr, $start, $limit);
    }

    function sizeUpdateById($params, $id){
        $CLAUSE = "sizeId = ".addslashes($id);
        return $this->updateQuery(TBL_PRODUCT_SIZE, $params, $CLAUSE);
    }

    function newSize($params) {
        return $this->insertQuery(TBL_PRODUCT_SIZE, $params);
	}

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_PRODUCT_SIZE, "*", $ExtraQryStr);
    }

    function checkExistsAnother($Entity, $Fields, $ExtraQryStr) {
        return $this->rowCount($Entity, $Fields, $ExtraQryStr);
    }

    function deleteSize($id) {
        return $this->executeQuery("DELETE FROM ".TBL_PRODUCT_SIZE." WHERE sizeId = ".addslashes($id));
    }
}