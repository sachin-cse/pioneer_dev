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
        
		return $this->selectSingle($ENTITY, $str, $ExtraQryStr, $start, $limit); 
    }

    function getLinkedPages($parent_dir, $start, $limit){
        $ENTITY         = TBL_MENU_CATEGORY." mc JOIN ".TBL_MODULE." m ON (m.menu_id = mc.moduleId)";
        $ExtraQryStr    = "mc.status = 'Y' AND m.parent_dir = '".addslashes($parent_dir)."' AND m.child_dir = '' ORDER BY mc.displayOrder";
        
		return $this->selectMulti($ENTITY, "mc.categoryId, mc.categoryName, mc.permalink", $ExtraQryStr, $start, $limit); 
    }
    
    /*------------------------------ TBL_PRODUCT_TYPE ------------------------------*/
    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_PRODUCT_TYPE, "*", $ExtraQryStr);
    }
    
    function newCategory($params) {
        return $this->insertQuery(TBL_PRODUCT_TYPE, $params);
	}

    function updateCategoryBycategoryId($params, $categoryId){
        $CLAUSE = "categoryId = ".addslashes($categoryId);
        return $this->updateQuery(TBL_PRODUCT_TYPE, $params, $CLAUSE);
    }
    
    function deleteCategoryBycategoryId($categoryId){
        return $this->executeQuery("DELETE FROM ".TBL_PRODUCT_TYPE." WHERE categoryId = ".$categoryId);
    }

    function categoryById($categoryId){
		$ExtraQryStr = " categoryId = ".addslashes($categoryId);
		return $this->selectSingle(TBL_PRODUCT_TYPE, "*", $ExtraQryStr);
	}
    
    function categoryCount($ExtraQryStr) {
        $ENTITY       = TBL_PRODUCT_TYPE." tpc";
        return $this->rowCount($ENTITY, 'tpc.categoryId', $ExtraQryStr);
	}
    
    function getCategoryByLimit($ExtraQryStr, $start, $limit) {
        $ENTITY       = TBL_PRODUCT_TYPE." tpc LEFT JOIN ".TBL_PRODUCT_TYPE." tpcs ON (tpc.categoryId = tpcs.parentId)";
        $fieldset     = "tpc.*, COUNT(tpcs.categoryId) subCount";
		$ExtraQryStr .= " GROUP BY tpc.categoryId ORDER BY tpc.displayOrder";
        return $this->selectMulti($ENTITY, $fieldset, $ExtraQryStr, $start, $limit);
    }
    
    function buildTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            
            if ($element['parentId'] == $parentId) {
                
                $children = $this->buildTree($elements, $element['categoryId']);
                
                if ($children)
                    $element['children'] = $children;
                
                $branch[] = $element;
            }
        }

        return $branch;
    }

    function getAllCategory($userId) {
        $ENTITY         = TBL_PRODUCT_TYPE;
		$ExtraQryStr    = " userId = ".addslashes($userId)." AND status = 'Y' ORDER BY categoryId, displayOrder";
		$tree           = $this->selectAll($ENTITY, "categoryId, parentId, categoryName, permalink", $ExtraQryStr); 
        
        $tree           = $this->buildTree($tree);
        
        return $tree;
	}
    
    /*------------------------------ TBL_PRODUCT_TYPE_ATTRIBUTES ------------------------------*/
    function newAttribute($params) {
        return $this->insertQuery(TBL_PRODUCT_TYPE_ATTRIBUTES, $params);
    }
    
    function updateAttributeBycategoryIdattributeId($params, $categoryId, $attributeId){
        $CLAUSE = "attributeId = ".$attributeId." AND categoryId = ".$categoryId;
        return $this->updateQuery(TBL_PRODUCT_TYPE_ATTRIBUTES, $params, $CLAUSE);
    }
    
    function deleteAttributeBycategoryId($categoryId, $ExtraQryStr){
        return $this->executeQuery("DELETE FROM ".TBL_PRODUCT_TYPE_ATTRIBUTES." WHERE categoryId = ".$categoryId." AND ".$ExtraQryStr);
    }
    
	function getAttributeByCategoryId($ExtraQryStr, $categoryId, $start, $limit) {
		$ExtraQryStr .= " AND categoryId = ".addslashes($categoryId)." ORDER BY attributeId";
        return $this->selectMulti(TBL_PRODUCT_TYPE_ATTRIBUTES, "*", $ExtraQryStr, $start, $limit); 
	}
	
	/* ----------------------------------------- FOR_MENU ----------------------------------------- */
    function searchLinkedPages($mid, $parent_dir, $srch, $start, $limit) {
        
        if($mid == 0) {
            
            $ExtraQryStr    = "mc.categoryName like '%".addslashes($srch)."%'";
            $ENTITY         = TBL_MENU_CATEGORY." mc JOIN ".TBL_MODULE." m ON (m.menu_id = mc.moduleId)";
            $ExtraQryStr   .= " AND mc.status = 'Y' AND m.parent_dir = '".addslashes($parent_dir)."' AND m.child_dir = '' ORDER BY mc.displayOrder";
            
            $data = $this->selectMulti($ENTITY, "mc.id id, mc.categoryName page, mc.permalink", $ExtraQryStr, $start, $limit);
            
        } else {  
            
            $ExtraQryStr = " status = 'Y' AND categoryName like '".addslashes($srch)."%' ORDER BY categoryName ASC";
            $data        = $this->selectMulti(TBL_PRODUCT_TYPE, "categoryId id, categoryName page, permalink", $ExtraQryStr, $start, $limit);
            
        }

		return $data;
    }
    
    function settings($name) {
        $ExtraQryStr = "name = '".addslashes($name)."'";
		return $this->selectSingle(TBL_SETTINGS, "*", $ExtraQryStr);
    }
}