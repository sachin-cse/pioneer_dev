<?php defined('BASE') OR exit('No direct script access allowed.');
class ProductstockController extends REST
{
    private    $model;
    private    $pmodel, $vmodel;

    protected  $response = [];

    public function __construct(ProductstockModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new ProductstockModel;

        $pmodel         = new ProductlistModel;
        $vmodel         = new VendorsModel;
        $this->model    = $model;
        $this->pmodel   = $pmodel;
        $this->vmodel   = $vmodel;
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
            $this->response['purchaseDateList']  = $this->model->getProductPurchaseStock($ExtraQryStrPurchaseDate);
            
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
        
        $this->response['rowCount']             = $this->pmodel->productCount($ExtraQryStr);
        if($this->response['rowCount']) {
            $p                                  = new Pager;
            $this->response['limit']            = VALUE_PER_PAGE;
            $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
            $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);

            $this->response['products']         = $this->model->getProductStock($ExtraQryProStr, $ExtraQryStr, $purchaseDate, $start, $this->response['limit']);

            if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                $this->response['page']         = ($this->_request['page']) ? $this->_request['page'] : 1;
                $this->response['totalPage']    = ceil($this->response['rowCount'] / $this->response['limit']);
                $this->response['pageList']     = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
            }
        }

        // Get product size ...
        $this->response['productSize']      = $this->model->getAllProductSize();

        $this->response['productType']      = $this->model->getAllProductType();

        // Get all vendors ...
        $this->response['vendors']          = $this->vmodel->getAllVendors();

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


    function searchStock() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['htmlContent']      = null;
        $html                       = '';

        $sizeId                     = trim($this->_request['sizeId']);
        $sltGSM                     = trim($this->_request['sltGSM']);
        $sltCategory                = trim($this->_request['sltCategory']);

        $currSession                = $_SESSION['SESSIONID'] != null ? $_SESSION['SESSIONID'] : session_id();
        $currUserID                 = trim($this->session->read('UID'));

        if($sizeId != '' &&  $sltGSM != '' &&  $sltCategory != '') {
            $ExtraQryStr            = "tp.sizeId = ".addslashes($sizeId)." AND tp.gsmId = ".addslashes($sltGSM)." AND tp.typeId = ".addslashes($sltCategory)." AND tp.status = 'Y' ";
            $records                = $this->model->getStockProducts($ExtraQryStr);

            //showArray($records); exit;

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

                    // $tempParams['subTypeId']        = $item['subTypeId'];
                    // $tempParams['subCategoryName']  = $item['subCategoryName'];

                    $tempParams['vendorId']         = 0;
                    $tempParams['productId']        = $item['productId'];
                    $tempParams['productName']      = $item['productName'];
                    $tempParams['quantity']         = 0;
                    $tempParams['price']            = 0.00;
                    $tempParams['sessionId']        = $currSession;
                    $tempParams['entryDate']        = date('Y-m-d H:i:s');

                    $ExtraQryChk                    = "tts.sizeId = ".addslashes($item['sizeId'])." AND tts.gsmId = ".addslashes($item['gsmId'])." AND tts.typeId = ".addslashes($item['typeId'])." AND tts.userId = ".addslashes($currUserID)." AND tts.sessionId = '".addslashes($currSession)."'";
                    $exists                         = $this->model->checkTempRecord($ExtraQryChk);
                    if(!$exists) {
                        $this->model->insTemp($tempParams);
                        $actMsg['message']          = 'Add more item for stock update.';
                        $actMsg['type']             = 1;
                    } else {
                        $actMsg['message']          = 'This combination has already been added';
                    }
                }
            } else {
                $actMsg['message']      = 'No stock available!';
            }

            // Generate HTML...
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

    function removeStockForm() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $currSession                = $_SESSION['SESSIONID'] != null ? $_SESSION['SESSIONID'] : session_id();
        if($this->model->delTempStockBySession($currSession)) {
            $actMsg['type']         = 1;
        }

        return $actMsg;
    }

    function getPurchaseDate() {
        $actMsg['htmlContent']      = null;
        $html                       = '';

        $vendor                     = trim($this->_request['vendor']);

        $ExtraQryStr            = "pps.vendorId = ".addslashes($vendor)." GROUP BY purchaseDate Order BY purchaseDate DESC";
        $records                = $this->model->getProductPurchaseStock($ExtraQryStr);

        if(is_array($records) && count($records) > 0) {

            $html = '<option value="">Select Purchase Date</option>';

            foreach($records as $item) {

                $html .= '<option value="'.date('d-m-Y', strtotime($item['purchaseDate'])).'">'.date('d-m-Y', strtotime($item['purchaseDate'])).'</option>';
                
            }
        } else {
            $html = '<option value="">No Purchase Date Found</option>';
        }

        $actMsg['htmlContent'] = $html;

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
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                    <tbody>';

            foreach($recordsForHTML as $item) {
                $childRecords = $this->model->getChildDataFromTemp($item['userId'], $item['sizeId'], $item['gsmId'], $item['typeId'], $currSession);
                if(is_array($childRecords) && count($childRecords) > 0) {
                    $_noOfRow = count($childRecords);
                    foreach($childRecords as $childKey=>$childItem) {
                        if($childKey == 0) {
                            $html .= '<tr>
                                <td rowspan="'.$_noOfRow.'" style="vertical-align : middle;text-align:center;">'.$childItem['gsmName'].'</td>
                                <td rowspan="'.$_noOfRow.'" style="vertical-align : middle;text-align:center;">'.$childItem['size'].'</td>
                                <td rowspan="'.$_noOfRow.'" style="vertical-align : middle;text-align:center;">'.$childItem['typeName'].'</td>
                                <td>'.$childItem['productName'].'<input type ="hidden" name="stockProduct[]" value="'.$childItem['productId'].'"/></td>
                                <td><input type="text" name="stockQty_'.$childItem['productId'].'" value="" class="numbersOnly" style="width:60px;"/></td>
                                <td><input type="text" name="stockPrice_'.$childItem['productId'].'" value="" class="numbersOnly" style="width:60px;" /></td>
                            </tr>';
                        } else {
                            $html .= '<tr '.($childKey == $_noOfRow - 1 ? 'style="border-bottom: 1px solid #ddd;"' :'').'>
                                <td>'.$childItem['productName'].'<input type ="hidden" name="stockProduct[]" value="'.$childItem['productId'].'"/></td>
                                <td><input type="text" name="stockQty_'.$childItem['productId'].'" value="" class="numbersOnly" style="width:60px;"/></td>
                                <td><input type="text" name="stockPrice_'.$childItem['productId'].'" value="" class="numbersOnly" style="width:60px;"/></td>
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

        $vendorId                   = trim($this->_request['sltVendor']);
        $arrProductId               = $this->_request['stockProduct'];
        $IdToEdit                   = trim($this->_request['IdToEdit']);
        $batchNo                    = time();

        $purchaseDate          = date('Y-m-d', strtotime($this->_request['purchaseDate']));

        if($vendorId != '') {
            if(is_array($arrProductId) && count($arrProductId) > 0) {
                foreach($arrProductId as $productId) {
                    $tempStockQty               = trim($this->_request['stockQty_'.$productId]);
                    $tempStockPrice             = trim($this->_request['stockPrice_'.$productId]);
                    $params                     = [];
                    $params['productId']        = $productId;
                    $params['vendorId']         = $vendorId;
                    $params['stockBatchNo']     = $batchNo;
                    $params['purchasePrice']    = $tempStockPrice;
                    $params['purchaseQty']      = $tempStockQty;
                    $params['purchaseDate']     = $purchaseDate;

                    $this->model->insPurchaseStock($params);
                    $this->model->delTempStock($productId);

                    $availableStock = $this->model->availableStock($productId);

                    if($availableStock > 0)
                    {
                        $upparams['productId']    = $productId;
                        $upparams['inStock']      = $tempStockQty;
                        
                        $this->model->updateProductAvlStock($upparams);
                    }
                    else
                    {
                        $incparams['productId']    = $productId;
                        $incparams['inStock']      = $tempStockQty;
                        
                        $this->model->insProductAvlStock($incparams);
                    }

                }
                $actMsg['type']                 = 1;
                $actMsg['message']              = 'Stock added successful.';
            } else {
                $actMsg['message']  = 'No stock added!!';
            }
        } else {
            $actMsg['message']      = 'Select vendor!!';
        }

        return $actMsg;
    }

    function modPage() {
        $srch = trim($this->_request['srch']);

        if ($srch) {
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
    }
}