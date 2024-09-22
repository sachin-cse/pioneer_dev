<?php defined('BASE') OR exit('No direct script access allowed.');
class PapermaterialModel extends Site
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

    function materialCount($ExtraQryStr) {
        return $this->rowCount(TBL_PRODUCT_MATERIAL, "mtId", $ExtraQryStr);
    }

    function getMaterialByLimit($ExtraQryStr, $start, $limit) {
        $ExtraQryStr .= " ORDER BY displayOrder ASC";
        return $this->selectMulti(TBL_PRODUCT_MATERIAL, "*", $ExtraQryStr, $start, $limit);
    }

    function checkMaterialExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_PRODUCT_MATERIAL, "*", $ExtraQryStr);
    }

    function materialUpdatedById($data,$id){
        $query = "mtId = ".addslashes($id);
        return $this->updateQuery(TBL_PRODUCT_MATERIAL, $data, $query);
    }

    function insertData($data){
        return $this->insertQuery(TBL_PRODUCT_MATERIAL,$data);
    }

    function checkExistsAnother($Entity, $Fields, $ExtraQryStr) {
        return $this->rowCount($Entity, $Fields, $ExtraQryStr);
    }

    function materialDeletebyId($id){
        $sql = "DELETE FROM ".TBL_PRODUCT_MATERIAL." WHERE mtId = ".addslashes($id)."";
        return $this->executeQuery($sql);
    }
}