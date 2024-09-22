<?php defined('BASE') OR exit('No direct script access allowed.');
class VendorsController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(VendorsModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new VendorsModel;

        $this->model = $model;
    }

    function index($act = []) {
        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);

        $ExtraQryStr                            = 1;

        $this->response['rowCount']             = $this->model->vendorsCount($ExtraQryStr);
        if($this->response['rowCount']) {
            $p                                  = new Pager;
            $this->response['limit']            = VALUE_PER_PAGE;
            $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
            $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);

            $this->response['vendors']          = $this->model->getVendorsByLimit($ExtraQryStr, $start, $this->response['limit']);

            if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                $this->response['page']         = ($this->_request['page']) ? $this->_request['page'] : 1;
                $this->response['totalPage']    = ceil($this->response['rowCount'] / $this->response['limit']);
                $this->response['pageList']     = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
            }
        }


        if(isset($this->_request['editid']) || isset($act['editid'])) {
            $editid                             = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            if($editid) {
                $this->response['vendor']       = $this->model->checkExistence("vendorId = ".addslashes($editid));
            }
        }

        return $this->response;
    }

    function addEditVendor($pageData= []) {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $vendorName                 = trim($this->_request['vendorName']);
        $vendorPhone                = trim($this->_request['vendorPhone']);
        $vendorEmail                = trim($this->_request['vendorEmail']);
        $storeName                  = trim($this->_request['storeName']);
        $gst                        = trim($this->_request['gst']);
        $vendorAddress              = trim($this->_request['vendorAddress']);

        $IdToEdit                   = trim($this->_request['IdToEdit']);

        if($vendorName != '' && $vendorPhone != '' && $vendorAddress != '' && $storeName != '') {

            if($IdToEdit!= '')
                $sel_ContentDetails = $this->model->checkExistence("vendorName = '".addslashes($vendorName)."' AND vendorAddress = '".addslashes($vendorAddress)."' AND vendorPhone = '".addslashes($vendorPhone)."' AND storeName = '".addslashes($storeName)."' AND vendorId != ".$IdToEdit);
            else
                $sel_ContentDetails = $this->model->checkExistence("vendorName = '".addslashes($vendorName)."' AND vendorAddress = '".addslashes($vendorAddress)."' AND vendorPhone = '".addslashes($vendorPhone)."' AND storeName = '".addslashes($storeName)."' ");

            if(sizeof($sel_ContentDetails) < 1) {

            $params                     = [];
            $params['vendorName']       = $vendorName;
            $params['vendorAddress']    = $vendorAddress;
            $params['vendorEmail']      = $vendorEmail;
            $params['vendorPhone']      = $vendorPhone;
            $params['storeName']        = $storeName;
            $params['gst']              = $gst;
            $params['vendorStatus']     = 'Y';
            

            if($IdToEdit != '') {
                $this->model->vendorUpdateById($params, $IdToEdit);
                $actMsg['message']          = 'Vendor updated successful.';
            } else {
                $params['entryDate']    = date('Y-m-d H:i:s');
                $this->model->newVendor($params);
                $actMsg['message']          = 'Vendor added successful.';
            }
            $actMsg['type']             = 1;
        }
        else
        {
            $actMsg['message']          = 'Vendor already exists.';
        }
            
        } else {
            $actMsg['message']          = 'All * fields are required!';
        }
        return $actMsg;
    }

    function multiAction() {
        $actMsg['type']           = 0;
        $actMsg['message']        = '';

        if($this->_request['multiAction']){
            foreach($this->_request['selectMulti'] as $val) {
                $params = array();
                switch($this->_request['multiAction']) {
                    case "1":
                        $params['vendorStatus']       = 'Y';
                        break;
                    case "2":
                        $params['vendorStatus']       = 'N';
                        break;
                    default:
                        $this->response('', 406);
                }

                $this->model->vendorUpdateById($params, $val);
                $actMsg['type']           = 1;
                $actMsg['message']        = 'Operation successful.';
            }
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