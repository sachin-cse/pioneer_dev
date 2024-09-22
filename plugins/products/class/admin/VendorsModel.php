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
}