<?php defined('BASE') OR exit('No direct script access allowed.');
class WorkupdateController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(WorkupdateModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new WorkupdateModel;

        $this->model = $model;
    }

    function index($act = []) {

        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);
        $currSession                            = $_SESSION['SESSIONID'] != null ? $_SESSION['SESSIONID'] : session_id();

        //$ExtraQryStr                            = 1;

        $ExtraQryStr = '';
        $ExtraQryProStr = '';

        // SEARCH START --------------------------------------------------------------
        if(isset($this->_request['searchText']))
            $this->session->write('searchText', $this->_request['searchText']);

        if(isset($this->_request['sltVendor']))
            $this->session->write('sltVendor', $this->_request['sltVendor']);

        if(isset($this->_request['propurchaseDate']))    
        $this->session->write('propurchaseDate', $this->_request['propurchaseDate']);

        if($this->session->read('searchText'))
        {
            $searchText = $this->session->read('searchText');
            $ExtraQryProStr        .= " AND productName LIKE '%".addslashes($this->session->read('searchText'))."%'";
        }
        else
        {
            $searchText = '';
        }

        if($this->session->read('sltVendor'))
        {
            $vendorId = $this->session->read('sltVendor');

            $ExtraQryStrPurchaseDate          = "pps.vendorId = ".addslashes($vendorId)." GROUP BY purchaseDate Order BY purchaseDate DESC";
            // $this->response['purchaseDateList']  = $this->model->getProductPurchaseStock($ExtraQryStrPurchaseDate);
            
            $ExtraQryStr        .= " AND vendorId = '".addslashes($this->session->read('sltVendor'))."'";
        }
        else
        {
            $vendorId = '';
        }
            
        if($this->session->read('propurchaseDate'))
        {
            $purchaseDate = $this->session->read('propurchaseDate');

            $ExtraQryStr        .= " AND purchaseDate = '".date('Y-m-d', strtotime($this->session->read('propurchaseDate')))."'";
        }
        else
        {
            $purchaseDate = '';
        }

        if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {

            if(isset($this->_request['Reset'])){

                $this->session->write('searchText',     '');
                $this->session->write('sltVendor',   '');
                $this->session->write('propurchaseDate',     '');

            }

            $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
        }

        // SEARCH END ----------------------------------------------------------------
        
        // $this->response['rowCount']             = $this->pmodel->productCount($ExtraQryStr);
        // if($this->response['rowCount']) {
            $p                                  = new Pager;
            $this->response['limit']            = VALUE_PER_PAGE;
            $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
            $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);

            if($_GET['workdate'] !="")
            {
                $workDate = $_GET['workdate'];
            }
            else
            {
                $lastWorkingDate = $this->model->getLastWorkingDate();

                $workDate = $lastWorkingDate['workUpdateDate'];
            }

            $this->response['workingDate'] = $workDate;

            $this->response['products']         = $this->model->getWorkUpdate($ExtraQryProStr, $ExtraQryStr, $purchaseDate, $start, $this->response['limit'], $workDate);

            // $query = " workUpdateDate = '".date("Y-m-d", strtotime($workDate."+ 1 day"))."'";

            // $currentDate = date('Y-m-d', strtotime("+ 1 day"));
            $this->response['getLastWorkingDate'] = $this->model->getLastWrokingDate();

            if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                $this->response['page']         = ($this->_request['page']) ? $this->_request['page'] : 1;
                $this->response['totalPage']    = ceil($this->response['rowCount'] / $this->response['limit']);
                $this->response['pageList']     = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
            }
        // }

        // Get product size ...
        $this->response['productSize']      = $this->model->getAllProductSize();


        if(isset($this->_request['editid']) || isset($act['editid'])) {
            $editid                             = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            if($editid) {

            }
        }

        $htmlForm       = $this->generateStockFormBySession($currSession);
        if($htmlForm != '') {
            $this->response['htmlStockForm'] = $htmlForm;
        }

        return $this->response;
    }

    function modPage() {
        $srch = trim($this->_request['srch']);

        if ($srch) {
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
    }

    function getStockAmountCheck() {

        $actMsg['type']             = 0;
        $actMsg['message']          = '';

        $gsmId                     = trim($this->_request['gsmId']);
        $sizeId                    = trim($this->_request['sizeId']);
        $typeId                    = trim($this->_request['typeId']);
        $qty                       = trim($this->_request['qty']);

        if($gsmId != '' && $sizeId != '' && $typeId != '' && $qty != '') {

            $selectedStockAmount   = $this->model->getStockAmountCheck($gsmId, $sizeId, $typeId);

            if($qty > $selectedStockAmount['inStock'])
            {
                $actMsg['type']             = 1;
                $actMsg['message']          = 'You cannot more than amount from product stock';   
            }
            else
            {
                $actMsg['type']             = 0;
            }
        }

        return $actMsg;

    }

    function getattr() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['categories']       = null;
        $actMsg['gsmList']          = null;

        $sizeId                     = trim($this->_request['sizeId']);

        if($sizeId != '') {

            $selectedPaperGSM           = $this->model->getPaperGSMById($sizeId);

            if(!empty($selectedPaperGSM) && count($selectedPaperGSM) > 0) {
                $actMsg['gsmList']      = $selectedPaperGSM;
            }
        }

        return $actMsg;
    }

    function getGmsattr() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['categories']       = null;
        $actMsg['gsmList']          = null;

        $sizeId                     = trim($this->_request['sizeId']);
        $sltGSM                     = trim($this->_request['sltGSM']);

        if($sizeId != '' && $sltGSM !='') {

            $selectedPaperType           = $this->model->getPaperTypeByGsm($sizeId, $sltGSM);

            if(!empty($selectedPaperType) && count($selectedPaperType) > 0) {
                $actMsg['categories']      = $selectedPaperType;
            }
        }

        //showArray($actMsg);

        return $actMsg;
    }

    function getEndType() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['categories']       = null;
        $actMsg['endTypeList']          = null;

        $selectedEndPaperType           = $this->model->getAllEndProductType();

        $actMsg['endTypeLists']      = $selectedEndPaperType;

        return $actMsg;
    }

    function addTemWork() {

        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['htmlContent']      = null;
        $html                       = '';

        $sizeId                     = trim($this->_request['sizeId']);
        $sltGSM                     = trim($this->_request['sltGSM']);
        $sltCategory                = trim($this->_request['sltCategory']);
        $endProductId               = trim($this->_request['endProductId']);
        $endProduct                 = trim($this->_request['endProduct']);
        $chooseDate                 = trim(date("Y-m-d h:i:s a", strtotime($this->_request['choose_date'])));

        $currSession                = $_SESSION['SESSIONID'] != null ? $_SESSION['SESSIONID'] : session_id();
        $currUserID                 = trim($this->session->read('UID'));

        if($sizeId != '' &&  $sltGSM != '' &&  $sltCategory != '' &&  $endProductId != '' &&  $endProduct != '') {

            $ExtraQryStr            = "tp.sizeId = ".addslashes($sizeId)." AND tp.gsmId = ".addslashes($sltGSM)." AND tp.typeId = ".addslashes($sltCategory)." AND tp.status = 'Y' ";
            $records                = $this->model->getStockProducts($ExtraQryStr);

            if(is_array($records) && count($records) > 0) {
                foreach($records as $item) {
                    $tempParams                     = array();
                    $tempParams['userId']           = $currUserID;
                    $tempParams['sizeId']           = $item['sizeId'];
                    $tempParams['size']             = $item['sizeName'];
                    $tempParams['gsmId']            = $item['gsmId'];
                    $tempParams['gsmName']          = $item['gsmName'];
                    $tempParams['typeId']           = $item['typeId'];
                    $tempParams['typeName']         = $item['categoryName'];

                    $tempParams['endProductId']     = $endProductId;
                    $tempParams['endProductName']   = $endProduct;

                    $tempParams['sessionId']        = $currSession;
                    $tempParams['entryDate']        = $chooseDate;

                    $ExtraQryChk                    = "tts.sizeId = ".addslashes($item['sizeId'])." AND tts.gsmId = ".addslashes($item['gsmId'])." AND tts.typeId = ".addslashes($item['typeId'])." AND tts.endProductId = ".addslashes($endProductId)." AND tts.userId = ".addslashes($currUserID)." AND tts.sessionId = '".addslashes($currSession)."'";

                    $stockExistsExtraQryChk         = "tp.sizeId = ".addslashes($item['sizeId'])." AND tp.gsmId = ".addslashes($item['gsmId'])." AND tp.typeId = ".addslashes($item['typeId'])." AND tp.status = 'Y' ";

                    $stockExists                    = $this->model->checkAvailableStockData($stockExistsExtraQryChk);

                    //showArray($stockExists); exit;

                    if(count($stockExists) > 0 && $stockExists['inStock'] > 0) 
                    {
                        $exists                         = $this->model->checkTempRecord($ExtraQryChk);

                        if(!$exists) {
                            $this->model->insTemp($tempParams);
                            $actMsg['message']          = 'Add more item for daily work update.';
                            $actMsg['type']             = 1;
                        } else {
                            $actMsg['message']          = 'This combination has already been added';
                        }
                    }
                    else
                    {
                        $actMsg['message']          = 'Product stock is not avaialable';
                    }
                   
                }
            } else {
                $actMsg['message']      = 'No stock available!';
            }

            $htmlForm       = $this->generateStockFormBySession($currSession);
            if($htmlForm != '') {
                $actMsg['htmlContent']  = $htmlForm;
            } else {
                $actMsg['message']      = 'No product found!';
            }
        } else {
            $actMsg['message']          = 'Select all required fields!';
        }

        return $actMsg;
    }
    
    function generateStockFormBySession($currSession) {
        $html           = '';
        $recordsForHTML = $this->model->getDataFromTemp($currSession);

        if(is_array($recordsForHTML) && count($recordsForHTML) > 0) {
            $html .= '<div class="row m-t-20">';
            $html .= '<div class="col-sm-12">';
            $html .= '<div class="table-responsive">';
            $html .= '<table class="table">
                        <thead>
                            <tr>
                                <th>GSM</th>
                                <th>Size</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Quantity (KG)</th>
                            </tr>
                        </thead>
                    <tbody>';

            foreach($recordsForHTML as $item) {
                $childRecords = $this->model->getChildDataFromTemp($item['userId'], $item['sizeId'], $item['gsmId'], $item['typeId'], $item['endProductId'], $currSession);
                if(is_array($childRecords) && count($childRecords) > 0) {
                    $_noOfRow = count($childRecords);
                    foreach($childRecords as $childKey=>$childItem) {
                        if($childKey == 0) {
                            $html .= '<tr>
                                <td rowspan="'.$_noOfRow.'" style="vertical-align : middle;text-align:center;">'.$childItem['gsmName'].'<input type ="hidden" name="gsmId[]" value="'.$childItem['gsmId'].'"/></td>
                                <td rowspan="'.$_noOfRow.'" style="vertical-align : middle;text-align:center;">'.$childItem['size'].'<input type ="hidden" name="sizeId[]" value="'.$childItem['sizeId'].'"/></td>
                                <td rowspan="'.$_noOfRow.'" style="vertical-align : middle;text-align:center;">'.$childItem['typeName'].'<input type ="hidden" name="typeId[]" value="'.$childItem['typeId'].'"/></td>
                                <td rowspan="'.$_noOfRow.'" style="vertical-align : middle;text-align:center;">'.$childItem['endProductName'].'<input type ="hidden" name="endPro[]" value="'.$childItem['endProductId'].'"/></td>
                                <td><input type="text" name="workQty[]" value="" class="numbersOnly getStockAmountCheck" data-gsmid="'.$childItem['gsmId'].'" data-sizeid="'.$childItem['sizeId'].'" data-typeid="'.$childItem['typeId'].'" style="width:60px;"/ required></td>
                            </tr>';
                        } else {
                            $html .= '<tr '.($childKey == $_noOfRow - 1 ? 'style="border-bottom: 1px solid #ddd;"' :'').'>
                                <td>'.$childItem['productName'].'<input type ="hidden" name="endPro[]" value="'.$childItem['endProductId'].'"/></td>
                                <td><input type="text" name="workQty[]" value="" class="numbersOnly" style="width:60px;"/></td>
                            </tr>';
                        }
                    }
                }
            }
            $html .= '</tbody>
                </table>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }

    
    function addEditStock($pageData= []) {

        $actMsg['type']             = 0;
        $actMsg['message']          = '';

        //showArray($this->_request); exit;

        $arrProductId               = $this->_request['endPro'];
        $IdToEdit                   = trim($this->_request['IdToEdit']);

        $workUpdateDate        = date('Y-m-d', strtotime($this->_request['workUpdateDate']));


        if(is_array($arrProductId) && count($arrProductId) > 0) {
            foreach($arrProductId as $row => $productId) {
        
                $params                     = [];
                $newParams                  = [];
                $params['endproductId']     = $this->_request['endPro'][$row];
                $params['gsmId']            = $this->_request['gsmId'][$row];
                $params['sizeId']           = $this->_request['sizeId'][$row];
                $params['typeId']           = $this->_request['typeId'][$row];
                $params['addedQty']         = $this->_request['workQty'][$row];
                $params['workUpdateDate']   = $workUpdateDate;

                $query = " endproductId = '".$this->_request['endPro'][$row]."' AND  gsmId = '".$this->_request['gsmId'][$row]."' AND sizeId = '".$this->_request['sizeId'][$row]."' AND typeId = '".$this->_request['typeId'][$row]."'";

                $rowCount = $this->model->checkInUnpackingTable($query);

                $checkDailyWorkExist = $this->model->checkExistancedailyWork($query);


                if($rowCount > 0){
                    $workQuantity = $this->_request['workQty'][$row];
                    $this->model->updateUnpackingData($workQuantity, $query);
                }else{

                    $productId = $this->model->getProductId($this->_request['gsmId'][$row], $this->_request['sizeId'][$row], $this->_request['typeId'][$row]);

                    $newParams['endproductId'] = $this->_request['endPro'][$row];
                    $newParams['productId'] = $productId['productId'];
                    $newParams['gsmId']  = $this->_request['gsmId'][$row];
                    $newParams['sizeId'] = $this->_request['sizeId'][$row];
                    $newParams['typeId'] = $this->_request['typeId'][$row];
                    $newParams['totalQty'] = $this->_request['workQty'][$row];
                    $newParams['modifiedDate'] = date('Y-m-d h:i:s a');

                    // print_r($newParams); exit;
                    $this->model->insPackingData($newParams);
                }

                $upparams['gsmId']     = $this->_request['gsmId'][$row];
                $upparams['sizeId']    = $this->_request['sizeId'][$row];
                $upparams['typeId']    = $this->_request['typeId'][$row];
                $upparams['totalQty']  = $this->_request['workQty'][$row];
                
                $this->model->updateProductAvlStock($upparams);

                if($checkDailyWorkExist > 0){
                    $this->model->insPurchaseStockUpdate($params);
                }else{
                    $this->model->insPurchaseStock($params);
                }

                $this->model->delTempStock($productId);

            }
            $actMsg['type']                 = 1;
            $actMsg['message']              = 'Work update added successfully';
        } else {
            $actMsg['message']  = 'No data added!!';
        }

        return $actMsg;
    }

    // function liveSearch(){

    //     $data['searchVal'] = '';
    //     $searchData = $this->_request;
        
    //     if(is_array($searchData) && count($searchData) > 0){
    //         if(!empty($searchData['searchSize']) && !empty($searchData['searchGSM']) && !empty($searchData['searchType'])){
    //             $ExtraQryStr = "sizeId = '".$searchData['searchSize']."' AND gsmName = '".explode(' ',$searchData['searchGSM'])[0]."' AND typeName LIKE '%".$searchData['searchType']."%'";
    //             $data['searchVal'] = $this->model->livesearchData($ExtraQryStr);
    //         }

    //         if(!empty($searchData['searchSize']) && !empty($searchData['searchGSM']) && empty($searchData['searchType'])){
    //             $ExtraQryStr = "sizeId = '".$searchData['searchSize']."' AND gsmName = '".explode(' ',$searchData['searchGSM'])[0]."'";
    //             $data['searchVal'] = $this->model->livesearchData($ExtraQryStr);
    //         }

            
    //         if(!empty($searchData['searchSize']) && !empty($searchData['searchGSM']) && empty($searchData['searchType'])){
    //             $ExtraQryStr = "sizeId = '".$searchData['searchSize']."' AND gsmName = '".explode(' ',$searchData['searchGSM'])[0]."'";
    //             $data['searchVal'] = $this->model->livesearchData($ExtraQryStr);
    //         }


    //     }

    //     print_r($data); exit;
    // }

    // live search
    function liveSearch(){
        $searchSize = $this->_request['searchSize'];
        $searchGSM = $this->_request['searchGSM'];
        $searchCategory = $this->_request['searchCategory'];

        // Initialize an empty array for conditions
        $conditions = [];

        // Check each condition and add to array if present
        if ($searchSize != '') {
            $conditions[] = "tp.sizeId = '$searchSize'";
        }

        if ($searchGSM != '') {
            $conditions[] = "tp.gsmId = '$searchGSM'";
        }

        if ($searchCategory != '') {
            $conditions[] = "tp.typeId = '$searchCategory'";
        }

        $ExtraQryStr = !empty($conditions) ? implode(' AND ', $conditions) : "";
        $searchval = $this->model->search($ExtraQryStr);
        
        return $searchval;
    }

    function removeStockForm(){

        $actMsg['type'] = 0;
        $actMsg['message'] = '';
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

            $removeStoke = $this->model->removeStoke();

            if($removeStoke == 0){
                $actMsg['type'] = 1;
                $actMsg['message'] = 'Work update reset successfully';
            }else{
                $actMsg['message'] = 'Something went wrong';
            }
        }

        return $actMsg;
    }

    // edit work update data
    function editWorkUpdate(){
        $getData['workUpdate'] = array();
        if($this->_request['id'] > 0){
            $getData['workUpdate'] = $this->model->getWorkupdateEditById($this->_request['id']);
        }

        return $getData;
    }
}