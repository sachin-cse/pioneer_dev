<?php defined('BASE') OR exit('No direct script access allowed.');
class ProductstockModel extends Site
{
    function getStockProducts($ExtraQryStr) {
        $ENTITY = TBL_PRODUCT . " tp LEFT JOIN " . TBL_PRODUCT_GSM . " tpg ON (tp.gsmId = tpg.gsmId) 
        LEFT JOIN " . TBL_PRODUCT_TYPE . " tpt ON (tp.typeId = tpt.categoryId) LEFT JOIN " . TBL_PRODUCT_SIZE . " tps ON (tp.sizeId = tps.sizeId)";
        return $this->selectAll($ENTITY, "tp.*, tpg.gsmName, tpt.categoryName, tps.sizeName", $ExtraQryStr);
    }

    function getStockProductsByLimit($ExtraQryStr, $start, $limit) {
        $ENTITY = TBL_PRODUCT . " tp LEFT JOIN " . TBL_PRODUCT_GSM . " tpg ON (tp.gsmId = tpg.gsmId) 
        LEFT JOIN " . TBL_PRODUCT_TYPE . " tpt ON (tp.typeId = tpt.typeId) 
        LEFT JOIN " . TBL_PRODUCT_TYPE . " tpst ON (tp.subTypeId = tpst.typeId)";
        $FIELDS = "tp.*, tpg.gsmName, tpt.typeName, tpst.typeName as subCategoryName, (SELECT SUM(tpps.purchaseQty) FROM ".TBL_PRODUCT_PURCHASE_STOCK." tpps WHERE tpps.productId=tp.productId) as stockQty ";
        return $this->selectMulti($ENTITY, $FIELDS, $ExtraQryStr, $start, $limit);
    }

    function insTemp($params) {
        return $this->insertQuery(TBL_TEMP_STOCK, $params);
    }

    function checkTempRecord($ExtraQryStr) {
        $ENTITY = TBL_TEMP_STOCK ." tts";
        return $this->rowCount($ENTITY, "tts.tempId", $ExtraQryStr);
    }

    function getDataFromTemp($sessionId) {
        $ENTITY = TBL_TEMP_STOCK ." tts";
        $ExtraQryStr = " tts.sessionId = '".addslashes($sessionId)."' GROUP BY tts.userId, tts.sizeId, tts.gsmId, tts.typeId";
        return $this->selectAll($ENTITY, "tts.userId, tts.sizeId, tts.gsmId, tts.typeId", $ExtraQryStr);
    }

    function getChildDataFromTemp($userId, $sizeId, $gsmId, $typeId, $currSession) {
        $ENTITY = TBL_TEMP_STOCK ." tts";
        $ExtraQryStr            = "tts.sizeId = ".addslashes($sizeId)." AND tts.gsmId = ".addslashes($gsmId)." AND tts.typeId = ".addslashes($typeId)." AND tts.userId = ".addslashes($userId)." AND tts.sessionId = '".addslashes($currSession)."'";
        return $this->selectAll($ENTITY, "tts.*", $ExtraQryStr);
    }

    function insPurchaseStock($params) {
        return $this->insertQuery(TBL_PRODUCT_PURCHASE_STOCK, $params);
    }

    function delTempStock($productId) {
        return $this->executeQuery("DELETE FROM ".TBL_TEMP_STOCK." WHERE productId = ".addslashes($productId));
    }

    function delTempStockBySession($sessionId) {
        return $this->executeQuery("DELETE FROM ".TBL_TEMP_STOCK." WHERE sessionId = '".addslashes($sessionId)."'");
    }

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

    function getProductPurchaseStock($ExtraQryStr) {
        $ENTITY = TBL_PRODUCT_PURCHASE_STOCK ." pps";
        return $this->selectAll($ENTITY, "pps.purchaseStockId, pps.purchaseDate", $ExtraQryStr);
    }

    // function getPaperTypeByGsm2($sizeId, $gsmId) {
    //     $ENTITY = TBL_PRODUCT. " tp INNER JOIN ".TBL_PRODUCT_TYPE." tpy ON (tp.typeId = tpy.categoryId)";

    //     $ExtraQryStr = "tp.sizeId = '".$sizeId."' AND tp.gsmId = '".$gsmId."' AND tp.status = 'Y' GROUP BY tpy.categoryId ORDER BY tpy.categoryName";

    //     return $this->selectAll($ENTITY, "tpy.categoryId, tpy.categoryName ", $ExtraQryStr);
    // }

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

    function getProductStock($ExtraQryProStr = '', $ExtraQryStrS = '', $purchaseDate = '',  $start, $limit) {

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
                    INNER JOIN ".TBL_PRODUCT_TYPE." tpt ON (tp.typeId = tpt.categoryId)";

                    $ExtraQryStr1 = " tp.typeId = ".$row['categoryId'];
                    $ExtraQryStr1 .= $ExtraQryProStr;
                    $ExtraQryStr1 .= " ORDER BY tp.displayOrder, tpg.gsmId";

                    $getChildCatProduct = $this->selectMulti($ENTITY, "tp.productId, tp.productName, tps.sizeName, tp.piecesPerKg, tp.stockAlertQty, tp.status,tp.displayOrder, tpg.gsmName, tpt.categoryName", $ExtraQryStr1, $start, $limit);

                    if(!empty($getChildCatProduct))
                    {
                        $metaArray[$pt]['categoryProduct'] = $getChildCatProduct;

                        foreach($getChildCatProduct as $pp => $key_new)
                        {
                            $STOCKENTITY = "productId = ".$key_new['productId'];
                           // $STOCKENTITY .= $ExtraQryStrS;
                            
                            $stockQty = $this->selectSingle(TBL_PRODUCT_AVAILABLR_STOCK, "inStock", $STOCKENTITY);

                            $PURENTITY = "productId = ".$key_new['productId'];
                            $PURENTITY .= $ExtraQryStrS;
                            $PURENTITY .= " ORDER BY purchaseStockId DESC";

                            $latestPrice = $this->selectSingle(TBL_PRODUCT_PURCHASE_STOCK, "purchasePrice", $PURENTITY);

                            if($stockQty['inStock'] !="" && $purchaseDate =="")
                            {
                                $metaArray[$pt]['categoryProduct'][$pp]['stockQty'] = $stockQty['inStock'];
                                $metaArray[$pt]['categoryProduct'][$pp]['latestPrice'] = $latestPrice['purchasePrice'];
                            }
                            else if($stockQty['inStock'] !="" && $purchaseDate !="")
                            {
                                $metaArray[$pt]['categoryProduct'][$pp]['stockQty'] = $stockQty['inStock'];
                                $metaArray[$pt]['categoryProduct'][$pp]['latestPrice'] = $stockQty['purchasePrice'];
                            }
                            else
                            {
                                $metaArray[$pt]['categoryProduct'][$pp]['stockQty'] = 0;
                                $metaArray[$pt]['categoryProduct'][$pp]['latestPrice'] = '0.00';
                            }

                           
                            
                        }
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

                    $ExtraQryStr1 = " tp.typeId = ".$crow['categoryId'];
                    $ExtraQryStr1 .= $ExtraQryProStr;
                    $ExtraQryStr1 .= " ORDER BY tpg.gsmId";

                    $getChildCatProduct = $this->selectMulti($ENTITY, "tp.productId, tp.productName, tps.sizeName, tp.stockAlertQty, tp.piecesPerKg, tp.status,tp.displayOrder, tpg.gsmName, tpt.categoryName", $ExtraQryStr1, $start, $limit);

                    if(!empty($getChildCatProduct))
                    {
                        $metaArray[$pt]['subCat'][$cpt]['categoryProduct'] = $getChildCatProduct;

                        foreach($getChildCatProduct as $pp => $key_new)
                        {
                            $STOCKENTITY = "productId = ".$key_new['productId'];
                            //$STOCKENTITY .= $ExtraQryStrS;

                            $stockQty = $this->selectSingle(TBL_PRODUCT_AVAILABLR_STOCK, "inStock", $STOCKENTITY);

                            $PURENTITY = "productId = ".$key_new['productId'];
                            $PURENTITY .= $ExtraQryStrS;
                            $PURENTITY .= " ORDER BY purchaseStockId DESC";

                            $latestPrice = $this->selectSingle(TBL_PRODUCT_PURCHASE_STOCK, "purchasePrice", $PURENTITY);

                            if($stockQty['inStock'] !="" && $purchaseDate =="")
                            {
                                $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['stockQty'] = $stockQty['inStock'];
                                $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['latestPrice'] = $latestPrice['purchasePrice'];
                            }
                            else if($stockQty['inStock'] !="" && $purchaseDate !="")
                            {
                                $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['stockQty'] = $stockQty['inStock'];
                                $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['latestPrice'] = $stockQty['purchasePrice'];
                            }
                            else
                            {
                                $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['stockQty'] = 0;
                                $metaArray[$pt]['subCat'][$cpt]['categoryProduct'][$pp]['latestPrice'] = '0.00';
                            }

                            
                        }
                    }
                }
            }    

        }

        //showArray($metaArray); exit;

        return $metaArray;
    }

    function availableStock($productId)
    {
        $ExtraQryStr = "pas.productId = ".$productId;

        $ENTITY = TBL_PRODUCT_AVAILABLR_STOCK ." pas";

        return $this->rowCount($ENTITY, "pas.productStockId", $ExtraQryStr);
    }

    function insProductAvlStock($incparams) {
        return $this->insertQuery(TBL_PRODUCT_AVAILABLR_STOCK, $incparams);
    }

    function updateProductAvlStock($upparams) {
        
        $ENTITY = TBL_PRODUCT_AVAILABLR_STOCK ." pas";
        $ExtraQryStr = "pas.productId = ".$upparams['productId']." ";

        $getStockAmnt = $this->selectAll($ENTITY, "pas.*", $ExtraQryStr);

        $total = 0;

        foreach($getStockAmnt as $amnt)
        {
            $total = $total + $amnt['inStock'];
        }

        $newParam['inStock'] = ($total + $upparams['inStock']);

        $CLAUSE = "productId = ".addslashes($upparams['productId']);

        return $this->updateQuery(TBL_PRODUCT_AVAILABLR_STOCK, $newParam, $CLAUSE);
    }

}