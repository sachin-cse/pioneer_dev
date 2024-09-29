<?php defined('BASE') OR exit('No direct script access allowed.');
class ReadypackageController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(ReadypackageModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new ReadypackageModel;

        $this->model = $model;
    }


    function index($act = []) {
        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);

        $ExtraQryStr                            = 1;

        if(!empty($this->_request['searchText']) || !empty($this->_request['searchByboxCount'])){

            if(!empty($this->_request['searchText']) && !empty($this->_request['searchByboxCount'])){

                $this->session->write('searchText', $this->_request['searchText']);
                $this->session->write('searchByboxCount', $this->_request['searchByboxCount']);

                $ExtraQryStr = " boxCount=".addslashes($this->_request['searchByboxCount'])." AND packagingName LIKE '%".addslashes($this->_request['searchText'])."%'";
            }else{

                $this->session->write('searchText', $this->_request['searchText']);
                $this->session->write('searchByboxCount', $this->_request['searchByboxCount']);

                $searchBytext = $this->_request['searchText']?$this->_request['searchText']:' ';
                $searchByboxCount = $this->_request['searchByboxCount']?$this->_request['searchByboxCount']:'';

                $ExtraQryStr = " boxCount = '".$searchByboxCount."' OR packagingName LIKE '%".$searchBytext."%'";
            }

        }

        
        if(isset($this->_request['Reset'])){
            $this->session->write('searchText',  '');
            $this->session->write('searchByboxCount',   '');
            $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
        }

        $this->response['rowCount']             = $this->model->readyProductCount($ExtraQryStr);

        if($this->response['rowCount']) {
            $p                                  = new Pager;
            $this->response['limit']            = 8;
            $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
            $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);

            $this->response['getAllReadyProduct']              = $this->model->getreadyProductByLimit($ExtraQryStr, $start, $this->response['limit']);

            if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                $this->response['page']         = ($this->_request['page']) ? $this->_request['page'] : 1;
                $this->response['totalPage']    = ceil($this->response['rowCount'] / $this->response['limit']);
                $this->response['pageList']     = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
            }
        }

        // Get Product List ...
        $this->response['productList']       = $this->model->getAllCategorywiseProductlist();


         // Get End Product List ...
        $this->response['endProductList']    = $this->model->getAllEndProductlist();

        // Get End Product List ...
        $this->response['packCount']         = array('15', '16', '17', '18', '20', '24');

       //showArray($this->response['endProductList']);

        // if(isset($this->_request['editid']) || isset($act['editid']) || $this->_request['dtaction'] == 'add') {
        //     $editid                         = ($this->_request['editid']) ? $this->_request['editid'] : $act['editid'];
        //     if($editid) {
        //         $this->response['packaging']  = $this->model->getPackagingById("packagingId = ".addslashes($editid));
        //     }
        // }
        return $this->response;
    }
    

    // add edit product
    function addEditStock() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';

        $productId                  = $this->_request['productId'];
        $endProductId               = $this->_request['endProductId'];
        $readyProductQty       = $this->_request['readyProductQty'];
        $currReadyProductQty        = $this->_request['currReadyProductQty'];
        $packingType                = $this->_request['packingType'];
        $currReadyProductPics       = $this->_request['currReadyProductPics'];
        $readyPack                  = $this->_request['readyPack'];
        $unpackingProduct           = $this->_request['unpackingProduct'];
        $readyBox                   = $this->_request['readyBox'];
        $unboxPack                  = $this->_request['unboxPack'];

        if($productId != '' && $endProductId != '' && $readyProductQty != '' && $currReadyProductQty != '' && $currReadyProductPics != '' && $readyPack != '' && $unpackingProduct != '' && $readyBox != '' && $unboxPack != '') {

            $params                 = [];
            $params['productId']  = $productId;
            $params['endProductId']  = $endProductId;
            $params['readyProductQty'] = $readyProductQty;
            $params['currReadyProductQty'] =  $currReadyProductQty;
            $params['packingType'] = $packingType;
            $params['currReadyProductPics'] = $currReadyProductPics;
            $params['readyPack']       = $readyPack;
            $params['unpackingProduct'] = $unpackingProduct;
            $params['readyBox'] = $readyBox;
            $params['unboxPack'] = $unboxPack;

            $stockId          = $this->model->insertStockData($params);

            $updateStock      = $this->model->updateUnpackStock($productId, $currReadyProductQty);

            $actMsg['message']  = 'Stock added successfully';

            $actMsg['type']         = 1;

        } else {
            $actMsg['message']      = 'All fields are mandatory.';
        }

		return $actMsg;
    }

    function modPage() {
        $srch = trim($this->_request['srch']);

        if ($srch) {
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
    }

    function getEndProduct()
    {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['endProducts']       = null;

        $productId                     = trim($this->_request['productId']);

        if($productId != '') {
            $selectedEndProducts  = $this->model->getAllEndProductlist();
            if(count($selectedEndProducts) > 0) {
                $actMsg['endProducts']  = $selectedEndProducts;
            }
        }

        return $actMsg;
    }

    function getUnpackingProductQty()
    {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['endProducts']       = null;

        $productId                     = trim($this->_request['productId']);
        $endProductId                  = trim($this->_request['endProductId']);

        if($productId != '' && $endProductId != '') {

            $selectedEndProducts  = $this->model->getUnpackingProductQty($productId, $endProductId);
            $packagingType  = $this->model->getPackingType($productId, $endProductId);
            
            if(count($selectedEndProducts) > 0) {
                $actMsg['noReadyProduct']  = $selectedEndProducts['totalQty'];
                $actMsg['packagingType']  = $packagingType;
            }
            else
            {
                $actMsg['noReadyProduct']  = 0;
                $actMsg['packagingType']  = $packagingType;
            }
        }

        return $actMsg; 
    }

    function getProductPackingDetails()
    {
        $actMsg['productPics']             = 0;

        $productId                     = trim($this->_request['productId']);
        $endProductId                  = trim($this->_request['endProductId']);
        $currReadyProductQty           = trim($this->_request['currReadyProductQty']);
        $packingType                   = trim($this->_request['packingType']);

        if($productId != '' && $endProductId != '' && $currReadyProductQty != '' && $packingType !="") {

            $productPackingDetails = $this->model->getProductPackingDetails($packingType);

            $noOfProducts = $this->model->getNoOfProducts($productId);

            $totalProduct = ($noOfProducts['piecesPerKg'] * $currReadyProductQty);

            $packCount = ($totalProduct / $productPackingDetails['packCount']);
            $boxCount = ($packCount / $productPackingDetails['boxCount']);

            $finalPack = intval($packCount);
            $unPackProduct = ($totalProduct - ($productPackingDetails['packCount'] * $finalPack));

            $finalBox = intval($boxCount);
            $unBoxPack = ($finalPack - ($productPackingDetails['boxCount'] * $finalBox));

            $actMsg['productPics'] = $totalProduct;
            $actMsg['packCount']   = $finalPack;
            $actMsg['boxCount'] = $finalBox;
            $actMsg['unPackProduct'] = $unPackProduct;
            $actMsg['unBoxPack'] = $unBoxPack;

        }

        return $actMsg; 
    }
}