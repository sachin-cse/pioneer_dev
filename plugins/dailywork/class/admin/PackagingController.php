<?php defined('BASE') OR exit('No direct script access allowed.');
class PackagingController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(PackagingModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new PackagingModel;

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

        $this->response['rowCount']             = $this->model->packagingCount($ExtraQryStr);

        if($this->response['rowCount']) {
            $p                                  = new Pager;
            $this->response['limit']            = 8;
            $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
            $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);

            $this->response['allPackaging']              = $this->model->getpackagingByLimit($ExtraQryStr, $start, $this->response['limit']);

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

        if(isset($this->_request['editid']) || isset($act['editid']) || $this->_request['dtaction'] == 'add') {
            $editid                         = ($this->_request['editid']) ? $this->_request['editid'] : $act['editid'];
            if($editid) {
                $this->response['packaging']  = $this->model->getPackagingById("packagingId = ".addslashes($editid));
            }
        }
        return $this->response;
    }

    // add edit product
    function addEditPackaging() {

        $actMsg['type']             = 0;
        $actMsg['message']          = '';

        $productId                  = trim($this->_request['productId']);
        $endProductId               = trim($this->_request['endProductId']);
        $packCount                = trim($this->_request['packCount']);

        $packagingName               = trim($this->_request['packagingName']);
        $boxCount               = trim($this->_request['boxCount']);

        $IdToEdit                   = trim($this->_request['IdToEdit']);

        if($productId != '' && $endProductId != '' && $packCount != '' && $packagingName != '' && $boxCount != '') {

            if($IdToEdit!= ''){
                $sel_ContentDetails = $this->model->checkExistence("(productId = '".addslashes($productId)."' AND endProductId = '".addslashes($endProductId)."' AND packCount = '".addslashes($packCount)."' AND boxCount = '".$boxCount."' AND packagingId != '".$IdToEdit."') AND (packagingName = '".addslashes($packagingName)."') AND packagingId != ".$IdToEdit);
            } else {
                $sel_ContentDetails = $this->model->checkExistence("(productId = '".addslashes($productId)."' AND endProductId = '".addslashes($endProductId)."' AND packCount = '".addslashes($packCount)."') AND (packagingName = '".addslashes($packagingName)."' AND boxCount = '".$boxCount."') ");
            }
        if(sizeof($sel_ContentDetails) < 1) {

            $params                 = [];
            $params['productId']  = $productId;
            $params['endProductId']        = $endProductId;
            $params['packCount'] = $packCount;
            $params['packagingName'] =  $packagingName;
            $params['boxCount'] = $boxCount;
            $params['packagingStatus']       = 'Y';
            $params['displayOrder'] = 0;
            

            if($IdToEdit != '') {
                $packagingId = $IdToEdit;
                $this->model->updatePackaging($params, $packagingId);
                $actMsg['message']  = 'Packaging update successful.';
            } else {
                //showArray($params); exit;

                $packagingId          = $this->model->insertPackaging($params);
                $actMsg['message']  = 'Packaging added successfully';
            }

            $actMsg['type']         = 1;
        }
        else
        {
            $actMsg['message']      = 'Packaging information already exists.';
        }

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

     // delete single packaging
     function singleDeletePackaging(){
        $actMsg['type'] = '';
        $actMsg['message'] = '';
        if(!empty($this->_request['id'])){
            // check Existance
            $packagingId = $this->_request['id'];
            $ExtraQryStr = " packagingId = ".addslashes($packagingId)."";
            $checkExistance = $this->model->packagingCount($ExtraQryStr);

            if($checkExistance >= 1){
                $actMsg['type'] = 1;
                $actMsg['message'] = 'Packaging Delete Successfully';
                $this->model->deletePackagingById($packagingId);
            }else{
                $actMsg['type'] = 2;
                $actMsg['message'] = 'Packaging id does not exist';
            }
        }else{
            $actMsg['type'] = 3;
            $actMsg['message'] = 'Something Went Wrong';
        }

        return $actMsg;
    }

    function multiAction() {
        $actMsg['type']           = 0;
        $actMsg['message']        = '';

        if($this->_request['multiAction'] == ''){
            $actMsg['message']        = 'Please select an option';
        }


        if($this->_request['multiAction']){
            if(!empty($this->_request['selectMulti'])){
                foreach($this->_request['selectMulti'] as $val) {
                    $params = array();
                    switch($this->_request['multiAction']) {
                        case "1":
                            $params['packagingStatus']       = 'Y';
                            break;
                        case "2":
                            $params['packagingStatus']       = 'N';
                            break;
                        default:
                            $this->response('', 406);
                    }

                    $this->model->updatePackaging($params, $val);
                    $actMsg['type']           = 1;
                    $actMsg['message']        = 'Operation successful.';
                }
            }else{
                $actMsg['message']        = 'Please check at least one checkbox';
            } 
        } 
        
        return $actMsg;
    }
}