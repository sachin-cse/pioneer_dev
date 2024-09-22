<?php defined('BASE') OR exit('No direct script access allowed.');
class WorkupdateModel extends ContentModel
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

    function getWorkUpdate($ExtraQryProStr = '', $ExtraQryStrS = '', $purchaseDate = '',  $start, $limit) {

        $ExtraQryStr = " 1 ORDER BY tp.entryDate";

        $ENTITY = TBL_DAILY_WORK. " tp INNER JOIN ".TBL_PRODUCT_SIZE." tps ON (tp.sizeId = tps.sizeId) 
                        INNER JOIN ".TBL_PRODUCT_GSM." tpg ON (tp.gsmId = tpg.gsmId) 
                        INNER JOIN ".TBL_PRODUCT_TYPE." tpt ON (tp.typeId = tpt.categoryId)
                        INNER JOIN ".TBL_END_PRODUCT." tep ON (tp.endproductId = tep.epId)
                        ";

        $productType = $this->selectMulti($ENTITY, "*", $ExtraQryStr, $start, $limit);

        return $productType;

    }

    // function getWorkUpdate1($ExtraQryProStr = '', $ExtraQryStrS = '', $purchaseDate = '',  $start, $limit) {

    //     $ExtraQryStr = "status = 'Y' AND parentId= 0 ORDER BY displayOrder";
    //     $productType = $this->selectAll(TBL_PRODUCT_TYPE, "categoryId, categoryName", $ExtraQryStr);

    //     $metaArray = array();

    //     foreach($productType as $pt => $row)
    //     {
    //         $metaArray[$pt]['categoryId'] = $row['categoryId'];
    //         $metaArray[$pt]['categoryName'] = $row['categoryName'];

    //         $childExtraQryStr = "status = 'Y' AND parentId=".addslashes($row['categoryId']);
    //         $childProductType = $this->selectAll(TBL_PRODUCT_TYPE, "*", $childExtraQryStr);


    //         if(empty($childProductType))
    //         {
    //                 $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PRODUCT_SIZE." tps ON (tp.sizeId = tps.sizeId) 
    //                 INNER JOIN ".TBL_PRODUCT_GSM." tpg ON (tp.gsmId = tpg.gsmId) 
    //                 INNER JOIN ".TBL_PRODUCT_TYPE." tpt ON (tp.typeId = tpt.categoryId)
    //                 ";

    //                 $ExtraQryStr1 = " tp.typeId = ".$row['categoryId'];
    //                 $ExtraQryStr1 .= $ExtraQryProStr;
    //                 $ExtraQryStr1 .= " ORDER BY tp.displayOrder, tpg.gsmId";

    //                 $getChildCatProduct = $this->selectMulti($ENTITY, "tp.productId, tp.productName, tps.sizeName, tp.piecesPerKg, tp.stockAlertQty, tp.status,tp.displayOrder, tpg.gsmName, tpt.categoryName", $ExtraQryStr1, $start, $limit);

    //                 if(!empty($getChildCatProduct))
    //                 {
    //                     $metaArray[$pt]['categoryProduct'] = $getChildCatProduct;

    //                     foreach($getChildCatProduct as $pp => $key_new)
    //                     {
    //                         $STOCKENTITY = "productId = ".$key_new['productId'];
    //                         $STOCKENTITY .= $ExtraQryStrS;
                            
    //                         $stockQty = $this->selectSingle(TBL_PRODUCT_PURCHASE_STOCK, "SUM(purchaseQty) as qtyStock, purchasePrice", $STOCKENTITY);

    //                         $PURENTITY = "productId = ".$key_new['productId'];
    //                         $PURENTITY .= $ExtraQryStrS;
    //                         $PURENTITY .= " ORDER BY purchaseDate DESC";

    //                         $latestPrice = $this->selectSingle(TBL_PRODUCT_PURCHASE_STOCK, "purchasePrice", $PURENTITY);

    //                         if($stockQty['qtyStock'] !="" && $purchaseDate =="")
    //                         {
    //                             $metaArray[$pt]['categoryProduct'][$pp]['stockQty'] = $stockQty['qtyStock'];
    //                             $metaArray[$pt]['categoryProduct'][$pp]['latestPrice'] = $latestPrice['purchasePrice'];
    //                         }
    //                         else if($stockQty['qtyStock'] !="" && $purchaseDate !="")
    //                         {
    //                             $metaArray[$pt]['categoryProduct'][$pp]['stockQty'] = $stockQty['qtyStock'];
    //                             $metaArray[$pt]['categoryProduct'][$pp]['latestPrice'] = $stockQty['purchasePrice'];
    //                         }
    //                         else
    //                         {
    //                             $metaArray[$pt]['categoryProduct'][$pp]['stockQty'] = 0;
    //                             $metaArray[$pt]['categoryProduct'][$pp]['latestPrice'] = '0.00';
    //                         }

                           
                            
    //                     }
    //                 }
    //         }
    //         else
    //         {
    //             foreach($childProductType as $cpt => $crow)
    //             {
    //             $metaArray[$pt]['subCat'][$cpt]['categoryId'] = $crow['categoryId'];
    //             $metaArray[$pt]['subCat'][$cpt]['categoryName'] = $crow['categoryName'];

    //             $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PRODUCT_SIZE." tps ON (tp.sizeId = tps.sizeId) 
    //                 INNER JOIN ".TBL_PRODUCT_GSM." tpg ON (tp.gsmId = tpg.gsmId) 
    //                 INNER JOIN ".TBL_PRODUCT_TYPE." tpt ON (tp.typeId = tpt.categoryId) ";

    //                 $ExtraQryStr1 = " tp.typeId = ".$crow['categoryId'];
    //                 $ExtraQryStr1 .= $ExtraQryProStr;
    //                 $ExtraQryStr1 .= " ORDER BY tpg.gsmId";

    //                 $getChildCatProduct = $this->selectMulti($ENTITY, "tp.productId, tp.productName, tps.sizeName, tp.stockAlertQty, tp.piecesPerKg, tp.status,tp.displayOrder, tpg.gsmName, tpt.categoryName", $ExtraQryStr1, $start, $limit);

    //                 if(!empty($getChildCatProduct))
    //                 {
    //                     $metaArray[$pt]['subCat'][$cpt]['categoryProduct'] = $getChildCatProduct;

    //                     foreach($getChildCatProduct as $pp => $key_new)
    //                     {
    //                         $STOCKENTITY = "productId = ".$key_new['productId'];
    //                         $STOCKENTITY .= $ExtraQryStrS;

    //                         $stockQty = $this->selectSingle(TBL_PRODUCT_PURCHASE_STOCK, "SUM(purchaseQty) as qtyStock, purchasePrice", $STOCKENTITY);

    //                         $PURENTITY = "productId = ".$key_new['productId'];
    //                         $PURENTITY .= $ExtraQryStrS;
    //                         $PURENTITY .= " ORDER BY purchaseDate DESC";

    //                         $latestPrice = $this->selectSingle(TBL_PRODUCT_PURCHASE_STOCK, "purchasePrice", $PURENTITY);

    //                         if($stockQty['qtyStock'] !="" && $purchaseDate =="")
    //                         {
    //                             $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['stockQty'] = $stockQty['qtyStock'];
    //                             $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['latestPrice'] = $latestPrice['purchasePrice'];
    //                         }
    //                         else if($stockQty['qtyStock'] !="" && $purchaseDate !="")
    //                         {
    //                             $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['stockQty'] = $stockQty['qtyStock'];
    //                             $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['latestPrice'] = $stockQty['purchasePrice'];
    //                         }
    //                         else
    //                         {
    //                             $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['stockQty'] = 0;
    //                             $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['latestPrice'] = '0.00';
    //                         }

                            
    //                     }
    //                 }
    //             }
    //         }    

    //     }

    //     //showArray($metaArray); exit;

    //     return $metaArray;
    // }

    function getAllProductSize() {

        $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PRODUCT_SIZE." tps ON (tp.sizeId = tps.sizeId)";

        $ExtraQryStr = "tp.status = 'Y' GROUP BY tps.sizeId ORDER BY tps.sizeName";

        return $this->selectAll($ENTITY, "tp.*, tps.sizeName, tps.sizeId ", $ExtraQryStr);
    }

    function getAllProductType() {
        $ExtraQryStr = "status = 'Y' AND parentId= 0";
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

    function getPaperGSMById($sizeId) {
        $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PRODUCT_GSM." tpg ON (tp.gsmId = tpg.gsmId)";

        $ExtraQryStr = "tp.sizeId = '".$sizeId."'  AND tp.status = 'Y' GROUP BY tpg.gsmId ORDER BY tpg.gsmName";

        return $this->selectAll($ENTITY, "tpg.gsmName, tpg.gsmId ", $ExtraQryStr);
    }

    function getPaperTypeByGsm($sizeId, $gsmId) {

        $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PRODUCT_TYPE." tpy ON (tp.typeId = tpy.categoryId)";

        $ExtraQryStr = "tp.sizeId = '".$sizeId."' AND tp.gsmId = '".$gsmId."' AND tp.status = 'Y' GROUP BY tpy.categoryId ORDER BY tpy.categoryName";

        $productType = $this->selectAll($ENTITY, "tpy.categoryId, tpy.categoryName ", $ExtraQryStr);

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
            $productType[$pt]['count'] = count($childProductType);;
        }

        //showArray( $productType);
        return $productType;
    }

    function getAllEndProductType() {

        $ENTITY = TBL_END_PRODUCT. " ep ";

        $ExtraQryStr = "ep.epStatus = 'Y' ORDER BY epName";

        return $this->selectAll($ENTITY, "ep.*", $ExtraQryStr);
    }

    function getStockProducts($ExtraQryStr) {
        $ENTITY = TBL_PRODUCT . " tp LEFT JOIN " . TBL_PRODUCT_GSM . " tpg ON (tp.gsmId = tpg.gsmId) 
        LEFT JOIN " . TBL_PRODUCT_TYPE . " tpt ON (tp.typeId = tpt.categoryId) LEFT JOIN " . TBL_PRODUCT_SIZE . " tps ON (tp.sizeId = tps.sizeId)";
        return $this->selectAll($ENTITY, "tp.*, tpg.gsmName, tpt.categoryName, tps.sizeName", $ExtraQryStr);
    }

    function checkTempRecord($ExtraQryStr) {
        $ENTITY = TBL_TEMP_DAILY_WORK ." tts";
        return $this->rowCount($ENTITY, "tts.tempId", $ExtraQryStr);
    }

    function insTemp($params) {
        return $this->insertQuery(TBL_TEMP_DAILY_WORK, $params);
    }

    function getDataFromTemp($sessionId) {
        $ENTITY = TBL_TEMP_DAILY_WORK ." tts";
        $ExtraQryStr = " tts.sessionId = '".addslashes($sessionId)."' GROUP BY tts.userId, tts.sizeId, tts.gsmId, tts.typeId, tts.endProductId";
        return $this->selectAll($ENTITY, "tts.userId, tts.sizeId, tts.gsmId, tts.typeId, tts.endProductId", $ExtraQryStr);
    }

    function getChildDataFromTemp($userId, $sizeId, $gsmId, $typeId, $endProductId, $currSession) {
        $ENTITY = TBL_TEMP_DAILY_WORK ." tts";
        $ExtraQryStr            = "tts.sizeId = ".addslashes($sizeId)." AND tts.gsmId = ".addslashes($gsmId)." AND tts.typeId = ".addslashes($typeId)." AND tts.endProductId = ".addslashes($endProductId)." AND tts.userId = ".addslashes($userId)." AND tts.sessionId = '".addslashes($currSession)."'";
        return $this->selectAll($ENTITY, "tts.*", $ExtraQryStr);
    }

    function insPurchaseStock($params) {
        return $this->insertQuery(TBL_DAILY_WORK, $params);
    }

    function delTempStock($endProductId) {
        return $this->executeQuery("DELETE FROM ".TBL_TEMP_DAILY_WORK." WHERE endProductId = ".addslashes($endProductId));
    }

    function removeStoke(){
        return $this->executeQuery("TRUNCATE TABLE ".TBL_TEMP_DAILY_WORK);
    }

    function search($ExtraQryStr){
        return $this->selectAll(TBL_DAILY_WORK,'*',$ExtraQryStr);
    }
}