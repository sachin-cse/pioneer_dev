<?php defined('BASE') OR exit('No direct script access allowed.');
class PapergsmController extends REST
{
    private    $model;
    private    $pmodel;
    protected  $response = [];

    public function __construct(PapergsmModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new PapergsmModel;

        $pmodel = new ProductlistModel;

        $this->model = $model;
        $this->pmodel = $pmodel;
    }

    function index($act = []) {
        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);

        $ExtraQryStr                            = 1;

        $this->response['rowCount']             = $this->model->gsmCount($ExtraQryStr);
        if($this->response['rowCount']) {
            $p                                  = new Pager;
            $this->response['limit']            = 8;
            $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
            $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);

            $this->response['gsm']              = $this->model->getGSMByLimit($ExtraQryStr, $start, $this->response['limit']);

            if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                $this->response['page']         = ($this->_request['page']) ? $this->_request['page'] : 1;
                $this->response['totalPage']    = ceil($this->response['rowCount'] / $this->response['limit']);
                $this->response['pageList']     = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
            }
        }

        $this->response['productSize']          = $this->pmodel->getAllProductSize();

        if(isset($this->_request['editid']) || isset($act['editid'])) {
            $editid                                 = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            if($editid) {
                $this->response['selectedGSM']      = $this->model->getGSMByID("gsmId = ".addslashes($editid));
                if(is_array($this->response['selectedGSM']) && count($this->response['selectedGSM']) > 0) {
                    $selectedSizeIds                = $this->model->getSizes($this->response['selectedGSM']['gsmName']);
                    $sortedIds = array();
                    if(is_array($selectedSizeIds) && count($selectedSizeIds) > 0) {
                        foreach($selectedSizeIds as $item) {
                            array_push($sortedIds, $item['sizeId']);
                        }
                    }
                    $this->response['selectedGSMSize']  = $sortedIds;
                }
            } 
        }
        
        return $this->response;
    }

    function addEditGSM() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $gsmName                    = trim($this->_request['gsmName']);
        $IdToEdit                   = trim($this->_request['IdToEdit']);

        if($gsmName != '') {

            if($IdToEdit!= '')
                $sel_ContentDetails = $this->model->checkExistence("gsmName = '".addslashes($gsmName)."' AND gsmId != ".$IdToEdit);
            else
                $sel_ContentDetails = $this->model->checkExistence("gsmName = '".addslashes($gsmName)."'");

            if(sizeof($sel_ContentDetails) < 1) {
                
            if($IdToEdit != '') {
                    $exist = $this->model->chkGSM($gsmName);
                    $params                             = array();
                    $params['gsmName']                  = $gsmName;
                    $this->model->gsmUpdateById($params, $IdToEdit);

                    $actMsg['message']          = 'GSM updated successful.';
                    $actMsg['type']             = 1;
            } else {
                        $params                             = array();
                        $params['gsmName']                  = $gsmName;
                        $params['gsmStatus']                = 'Y';
                        $this->model->newGSM($params);
                    $actMsg['message']          = 'GSM added successful.';
                    $actMsg['type']             = 1;
            }
        }
        else
        {
            $actMsg['message']        = 'GSM already exists!';
        }
        }
        else
            $actMsg['message']        = 'GSM required!';
            
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
                            $params['gsmStatus']       = 'Y';
                            break;
                        case "2":
                            $params['gsmStatus']       = 'N';
                            break;
                        default:
                            $this->response('', 406);
                    }
    
                    $this->model->gsmUpdateById($params, $val);
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
                    <input type="text" name="gsmName" value="" class="form-control" placeholder="Type GSM Name">
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
        $gsmName        = trim($opt['gsmName']);
        $sizeNames = '';
        if($gsmName != '') {
            $records = $this->model->getSizes($gsmName);
            if(is_array($records) && count($records) > 0) {
                foreach($records as $item) {
                    $sizeNames .= $item['sizeName'].'", ';
                }
            }
        }
        return rtrim($sizeNames, ", ");
    }
}