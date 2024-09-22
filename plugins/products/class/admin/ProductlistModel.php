<?php defined('BASE') OR exit('No direct script access allowed.');
class ProductlistModel extends Site
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
        $ENTITY         = TBL_PRODUCT;
        $ExtraQryStr    = 1;
        
        if($orderBy == 'T')
            $str = 'MIN(displayOrder) displayOrder';
        elseif($orderBy == 'B')
            $str = 'MAX(displayOrder) displayOrder';
        else
            return;
        
        return $this->selectSingle($ENTITY, $str, $ExtraQryStr); 
    }

    function getAllProductGSM() {
        $ExtraQryStr = "gsmStatus = 'Y'";
        return $this->selectAll(TBL_PRODUCT_GSM, "*", $ExtraQryStr);
    }

    function getAllProductType() {
        $ExtraQryStr = "status = 'Y' AND parentId= 0 ORDER BY displayOrder";
        $productType = $this->selectAll(TBL_PRODUCT_TYPE, "categoryId, categoryName", $ExtraQryStr);

        foreach($productType as $pt => $row)
        {
            $metaArray = array();

            $childExtraQryStr = "status = 'Y' AND parentId=".addslashes($row['categoryId']);
            $childProductType = $this->selectAll(TBL_PRODUCT_TYPE, "*", $childExtraQryStr);
            
            foreach($childProductType as $cpt => $crow)
            {
                $metaArray[$cpt]['categoryId'] = $crow['categoryId'];
                $metaArray[$cpt]['categoryName'] = $crow['categoryName'];
            }

            if(!empty($metaArray))
            {
                $productType[$pt]['childType'] = $metaArray;
            }
        }
        return $productType;
    }

    function getProductTypeByParentId($parentId) {
        $ExtraQryStr = "status = 'Y' AND parentId=".addslashes($parentId);
        return $this->selectAll(TBL_PRODUCT_TYPE, "*", $ExtraQryStr);
    }

    function getAllProductSize() {
        $ExtraQryStr = "sizeStatus = 'Y'";
        return $this->selectAll(TBL_PRODUCT_SIZE, "*", $ExtraQryStr);
    }

    function getProductGSMById($id) {
        $ExtraQryStr = "gsmId = ".addslashes($id)." AND gsmStatus = 'Y'";
		return $this->selectSingle(TBL_PRODUCT_GSM, "*", $ExtraQryStr);
    }

    function getProductTypeById($id) {
        $ExtraQryStr = "typeId = ".addslashes($id)." AND typeStatus = 'Y'";
		return $this->selectSingle(TBL_PRODUCT_TYPE, "*", $ExtraQryStr);
    }

    function getProductSizeById($id) {
        $ExtraQryStr = "sizeId = ".addslashes($id)." AND sizeStatus = 'Y'";
		return $this->selectSingle(TBL_PRODUCT_SIZE, "*", $ExtraQryStr);
    }

    function insProduct($params) {
        return $this->insertQuery(TBL_PRODUCT, $params);
    }

    function updProduct($params, $id){
        $CLAUSE = "productId = ".addslashes($id);
        return $this->updateQuery(TBL_PRODUCT, $params, $CLAUSE);
    }

    function insProductAttr($params) {
        return $this->insertQuery(TBL_PRODUCT_PAPER_ATTR, $params);
    }

    function insProductPurchaseStock($params) {
        return $this->insertQuery(TBL_PRODUCT_PURCHASE_STOCK, $params);
    }

    function insProductCostEstimation($params) {
        return $this->insertQuery(TBL_PRODUCT_COST_ESTIMATION, $params);
    }

    function productCount($ExtraQryStr) {
        return $this->rowCount(TBL_PRODUCT, "productId", $ExtraQryStr);
    }

    function getProductByLimit($ExtraQryStr, $start, $limit) {
        $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PRODUCT_SIZE." tps ON (tp.sizeId = tps.sizeId) 
                INNER JOIN ".TBL_PRODUCT_GSM." tpg ON (tp.gsmId = tpg.gsmId) 
                INNER JOIN ".TBL_PRODUCT_TYPE." tpt ON (tp.typeId = tpt.categoryId) ";
        $ExtraQryStr .= " ORDER BY tp.displayOrder";
        return $this->selectMulti($ENTITY, "tp.*, tps.sizeName, tpg.gsmName, tpt.categoryName", $ExtraQryStr, $start, $limit); 
    }

    function getProductCategoriwiseByLimit($ExtraQryStr1 = '', $start, $limit) {

        $ExtraQryStr = "status = 'Y' AND parentId= 0 ORDER BY displayOrder";
        $productType = $this->selectAll(TBL_PRODUCT_TYPE, "categoryId, categoryName", $ExtraQryStr);

        $metaArray = array();

        foreach($productType as $pt => $row)
        {
            $metaArray[$pt]['categoryId'] = $row['categoryId'];
            $metaArray[$pt]['categoryName'] = $row['categoryName'];

            $childExtraQryStr = "status = 'Y' AND parentId=".addslashes($row['categoryId']);
            $childProductType = $this->selectAll(TBL_PRODUCT_TYPE, "*", $childExtraQryStr);

            if(empty($childProductType))
            {
                $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PRODUCT_SIZE." tps ON (tp.sizeId = tps.sizeId) 
                    INNER JOIN ".TBL_PRODUCT_GSM." tpg ON (tp.gsmId = tpg.gsmId) 
                    INNER JOIN ".TBL_PRODUCT_TYPE." tpt ON (tp.typeId = tpt.categoryId) ";

                    $ExtraQryStr1 = " tp.typeId = ".$row['categoryId']." ORDER BY tp.displayOrder, tpg.gsmId";

                    $getChildCatProduct = $this->selectMulti($ENTITY, "tp.productId, tp.productName, tps.sizeName, tp.piecesPerKg, tp.stockAlertQty, tp.status,tp.displayOrder, tpg.gsmName, tpt.categoryName", $ExtraQryStr1, $start, $limit);

                    if(!empty($getChildCatProduct))
                    {
                        $metaArray[$pt]['categoryProduct'] = $getChildCatProduct;
                    }
            }
            else
            {
                foreach($childProductType as $cpt => $crow)
                {
                $metaArray[$pt]['subCat'][$cpt]['categoryId'] = $crow['categoryId'];
                $metaArray[$pt]['subCat'][$cpt]['categoryName'] = $crow['categoryName'];

                $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PRODUCT_SIZE." tps ON (tp.sizeId = tps.sizeId) 
                    INNER JOIN ".TBL_PRODUCT_GSM." tpg ON (tp.gsmId = tpg.gsmId) 
                    INNER JOIN ".TBL_PRODUCT_TYPE." tpt ON (tp.typeId = tpt.categoryId) ";

                    $ExtraQryStr1 = " tp.typeId = ".$crow['categoryId']." ORDER BY tpg.gsmId";

                    $getChildCatProduct = $this->selectMulti($ENTITY, "tp.productId, tp.productName, tps.sizeName, tp.stockAlertQty, tp.piecesPerKg, tp.status,tp.displayOrder, tpg.gsmName, tpt.categoryName", $ExtraQryStr1, $start, $limit);

                    if(!empty($getChildCatProduct))
                    {
                        $metaArray[$pt]['subCat'][$cpt]['categoryProduct'] = $getChildCatProduct;
                    }
                }
            }    

        }

        //showArray($metaArray); exit;

        return $metaArray;
    }

    // function getProductCategoriwiseByLimit($ExtraQryStr, $start, $limit) {
    //     $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PRODUCT_SIZE." tps ON (tp.sizeId = tps.sizeId) 
    //             INNER JOIN ".TBL_PRODUCT_GSM." tpg ON (tp.gsmId = tpg.gsmId) 
    //             INNER JOIN ".TBL_PRODUCT_TYPE." tpt ON (tp.typeId = tpt.categoryId) ";

    //     $ExtraQryStr .= " ORDER BY tp.displayOrder";
        
    //     return $this->selectMulti($ENTITY, "tp.*, tps.sizeName, tpg.gsmName, tpt.categoryName", $ExtraQryStr, $start, $limit); 
    // }

    function getAllProducts($ExtraQryStr) {
        $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PRODUCT_SIZE." tps ON (tp.sizeId = tps.sizeId) 
                INNER JOIN ".TBL_PRODUCT_GSM." tpg ON (tp.gsmId = tpg.gsmId) 
                INNER JOIN ".TBL_PRODUCT_TYPE." tpt ON (tp.typeId = tpt.categoryId)";
        $ExtraQryStr .= " ORDER BY tp.displayOrder";
        return $this->selectAll($ENTITY, "tp.*, tps.sizeName, tpg.gsmName, tpt.categoryName", $ExtraQryStr); 
    }

    function getPaperCategoryById($sizeId) {
        $ExtraQryStr = "sizeId = ".addslashes($sizeId)." AND typeStatus = 'Y' AND parentTypeId= 0";
		return $this->selectAll(TBL_PRODUCT_TYPE, "*", $ExtraQryStr);
    }

    function getPaperGSMById($sizeId) {
        $ExtraQryStr = "sizeId = ".addslashes($sizeId)." AND gsmStatus = 'Y' ";
		return $this->selectAll(TBL_PRODUCT_GSM, "*", $ExtraQryStr);
    }

    function getProductById($ExtraQryStr) {
        $ENTITY = TBL_PRODUCT. " tp 
                    INNER JOIN ".TBL_PRODUCT_SIZE." tps ON (tp.sizeId = tps.sizeId) 
                    INNER JOIN ".TBL_PRODUCT_GSM." tpg ON (tp.gsmId = tpg.gsmId) 
                    INNER JOIN ".TBL_PRODUCT_TYPE." tpt ON (tp.typeId = tpt.categoryId)";
        
        return $this->selectSingle($ENTITY, "tp.*, tps.sizeName, tpg.gsmName, tpt.categoryName", $ExtraQryStr); 
    }

    function checkExistence($ExtraQryStr) {
        return $this->selectSingle(TBL_PRODUCT, "*", $ExtraQryStr);
    }
}