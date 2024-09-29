<?php defined('BASE') OR exit('No direct script access allowed.');
class EndproducttypeController extends REST
{
    private    $model;
    private    $pmodel;
    protected  $response = [];

    public function __construct(EndproducttypeModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new EndproducttypeModel;

        $pmodel = new ProductlistModel;

        $this->model = $model;
        $this->pmodel = $pmodel;
    }

    function index($act = []) {
        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);

        $ExtraQryStr                            = 1;

        $this->response['rowCount']             = $this->model->epCount($ExtraQryStr);
        //echo $this->response['rowCount']; exit;
        if($this->response['rowCount']) {
            $p                                  = new Pager;
            $this->response['limit']            = 8;
            $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
            $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);

            $this->response['ep']              = $this->model->getEPByLimit($ExtraQryStr, $start, $this->response['limit']);

            if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                $this->response['page']         = ($this->_request['page']) ? $this->_request['page'] : 1;
                $this->response['totalPage']    = ceil($this->response['rowCount'] / $this->response['limit']);
                $this->response['pageList']     = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
            }
        }

        if(isset($this->_request['editid']) || isset($act['editid'])) {
            $editid                                 = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            if($editid) {
                $this->response['selectedEP']      = $this->model->getEPByID("epId = ".addslashes($editid));
            } 
        }
        
        return $this->response;
    }

    function addEditEP() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $epName                    = trim($this->_request['epName']);
        $IdToEdit                   = trim($this->_request['IdToEdit']);

        if($epName != '') {

            if($IdToEdit!= '')
                $sel_ContentDetails = $this->model->checkExistence("epName = '".addslashes($epName)."' AND epId != ".$IdToEdit);
            else
                $sel_ContentDetails = $this->model->checkExistence("epName = '".addslashes($epName)."'");

            if(sizeof($sel_ContentDetails) < 1) {
                
            if($IdToEdit != '') {
                    $exist = $this->model->chkEP($epName);
                    $params                             = array();
                    $params['epName']                  = $epName;
                    $this->model->epUpdateById($params, $IdToEdit);

                    $actMsg['message']          = 'End product updated successful.';
                    $actMsg['type']             = 1;
            } else {
                        $params                             = array();
                        $params['epName']                  = $epName;
                        $params['epStatus']                = 'Y';
                        $this->model->newEP($params);
                    $actMsg['message']          = 'End product added successful.';
                    $actMsg['type']             = 1;
            }
        }
        else
        {
            $actMsg['message']        = $epName.' already exists!';
        }
        }
        else
            $actMsg['message']        = 'End product required!';
            
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
                            $params['epStatus']       = 'Y';
                            break;
                        case "2":
                            $params['epStatus']       = 'N';
                            break;
                        default:
                            $this->response('', 406);
                    }
    
                    $this->model->epUpdateById($params, $val);
                    $actMsg['type']           = 1;
                    $actMsg['message']        = 'Operation successful.';
                }
            }else{
                $actMsg['message']        = 'Please check at least one checkbox';
            }
        }   
        
        return $actMsg;
    }

    function addEditFrm() {
        $actMsg['type']             = 1;
        $actMsg['message']          = '';

        $html                       = '';
        $IdToEdit                   = trim($this->_request['IdToEdit']);
        $productSize                = $this->pmodel->getAllProductSize();

        $html .= '<div class="row mb-2">
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="text" name="epName" value="" class="form-control" placeholder="Type End product typeName">
                </div>
            </div>
        </div>';

        $html .= '<div class="mb-2">Paper Size</div>';

        $html .= '<div class="row mb-2">';
        if(is_array($productSize) && count($productSize) > 0) {
            foreach($productSize as $item) {
                $html .= '<div class="col-sm-3">
                    <div class="form-group">
                        <input type="checkbox" id="'.$item['sizeId'].'" name="sizeId[]" value="'.$item['sizeId'].'">
                        <label for="'.$item['sizeName'].'">'.$item['sizeName'].'"</label>
                    </div>
                </div>';
            }
        }

        $html .= '<input type="hidden" name="IdToEdit" value="'.$IdToEdit.'" />';
        
        $html .= '</div>';

        $actMsg['htmlContent'] = $html;

        return $actMsg;
    }

    function sizeChart($opt = []) {
        $epName        = trim($opt['epName']);
        $sizeNames = '';
        if($epName != '') {
            $records = $this->model->getSizes($epName);
            if(is_array($records) && count($records) > 0) {
                foreach($records as $item) {
                    $sizeNames .= $item['sizeName'].'", ';
                }
            }
        }
        return rtrim($sizeNames, ", ");
    }
}