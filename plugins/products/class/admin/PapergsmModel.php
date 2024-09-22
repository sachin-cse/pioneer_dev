<?php defined('BASE') OR exit('No direct script access allowed.');
class PapergsmModel extends Site
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

    function getGSMByLimit($ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " GROUP BY gsmName ORDER BY gsmId";
        return $this->selectMulti(TBL_PRODUCT_GSM, "*", $ExtraQryStr, $start, $limit);
    }

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_PRODUCT_GSM, "*", $ExtraQryStr);
    }

    function gsmUpdateById($params, $id){
        $CLAUSE = "gsmId = ".$id;
        return $this->updateQuery(TBL_PRODUCT_GSM, $params, $CLAUSE);
    }

    function newGSM($params) {
        return $this->insertQuery(TBL_PRODUCT_GSM, $params);
	}

    function getSizeByGSM() {
        $ENTITY      = TBL_PRODUCT_SIZE." tps JOIN ".TBL_PRODUCT_GSM." tpg ON (tps.sizeId = tpg.sizeId)";
        $ExtraQryStr = " 1";
		return $this->selectAll($ENTITY, "tpg.gsmName, tps.sizeName", $ExtraQryStr);
	}

    function getSizes($gsmName) {
        $ENTITY      = TBL_PRODUCT_SIZE." tps JOIN ".TBL_PRODUCT_GSM." tpg ON (tps.sizeId = tpg.sizeId)";
        $ExtraQryStr = " tpg.gsmName = '".addslashes($gsmName)."'";
		return $this->selectAll($ENTITY, "tps.sizeName, tps.sizeId", $ExtraQryStr);
    }

    function getGSMByID($ExtraQryStr) {
        return $this->selectSingle(TBL_PRODUCT_GSM, "*", $ExtraQryStr);
    }

    function deleteGSM($gsmName){
        return $this->executeQuery("DELETE FROM ".TBL_PRODUCT_GSM." WHERE gsmName = ".$gsmName);
    }

    function chkGSM($gsmName) {
        $ExtraQryStr = " gsmName = ".addslashes($gsmName);
        return $this->selectSingle(TBL_PRODUCT_GSM, "*", $ExtraQryStr);
    }
}