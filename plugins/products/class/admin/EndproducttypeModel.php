<?php defined('BASE') OR exit('No direct script access allowed.');
class EndproducttypeModel extends Site
{
    function getDisplayOrder($orderBy = 'T'){
        $ENTITY         = TBL_END_PRODUCT;
        $ExtraQryStr    = 1;
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
        return $this->selectSingle($ENTITY, $str, $ExtraQryStr); 
    }

    function epCount($ExtraQryStr) {
        $ENTITY = TBL_END_PRODUCT." ep ";
        $ExtraQryStr .= " GROUP BY ep.epName";
        $res = $this->selectAll($ENTITY, "ep.epId", $ExtraQryStr);
        
        if(is_array($res) && count($res) > 0) {
            return count($res);
        } else {
            return 0;
        }
        //return $this->rowCount($ENTITY, "tpg.epId", $ExtraQryStr, true);
    }

    function getEPByLimit($ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " GROUP BY epName ORDER BY epId";
        return $this->selectMulti(TBL_END_PRODUCT, "*", $ExtraQryStr, $start, $limit);
    }

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_END_PRODUCT, "*", $ExtraQryStr);
    }

    function epUpdateById($params, $id){
        $CLAUSE = "epId = ".$id;
        return $this->updateQuery(TBL_END_PRODUCT, $params, $CLAUSE);
    }

    function newEP($params) {
        return $this->insertQuery(TBL_END_PRODUCT, $params);

    }

    function getEPByID($ExtraQryStr) {
        return $this->selectSingle(TBL_END_PRODUCT, "*", $ExtraQryStr);
    }

    function deleteEP($epName){
        return $this->executeQuery("DELETE FROM ".TBL_END_PRODUCT." WHERE epName = ".$epName);
    }

    function chkEP($epName) {
        $ExtraQryStr = " epName = ".addslashes($epName);
        return $this->selectSingle(TBL_END_PRODUCT, "*", $ExtraQryStr);
    }
}