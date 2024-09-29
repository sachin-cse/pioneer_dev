<?php defined('BASE') OR exit('No direct script access allowed.');
class PapersizeController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(PapersizeModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new PapersizeModel;

        $this->model = $model;
    }

    function index($act = []) {
        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);

        $ExtraQryStr                            = 1;
        $this->response['rowCount']             = $this->model->sizeCount($ExtraQryStr);
        if($this->response['rowCount']) {
            $p                                  = new Pager;
            $this->response['limit']            = 8;
            $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
            $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);

            $this->response['size']             = $this->model->getSizeByLimit($ExtraQryStr, $start, $this->response['limit']);

            if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                $this->response['page']         = ($this->_request['page']) ? $this->_request['page'] : 1;
                $this->response['totalPage']    = ceil($this->response['rowCount'] / $this->response['limit']);
                $this->response['pageList']     = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
            }
        }

        if(isset($this->_request['editid']) || isset($act['editid'])) {
            $editid                             = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            if($editid) {
                $this->response['selectedSize'] = $this->model->checkExistence("sizeId = ".addslashes($editid));
            }
        } else {
            

        }

        return $this->response;
    }

    function addEditSize() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $sizeName                   = trim($this->_request['sizeName']);
        $status                     = trim($this->_request['status']);
        $IdToEdit                   = trim($this->_request['IdToEdit']);

        if($sizeName != '') {
            if($IdToEdit!= '')
                $sel_ContentDetails = $this->model->checkExistence("sizeName = '".addslashes($sizeName)."' AND sizeId != ".$IdToEdit);
            else
                $sel_ContentDetails = $this->model->checkExistence("sizeName = '".addslashes($sizeName)."'");

            if(sizeof($sel_ContentDetails) < 1) {
                $params                     = array();
                $params['sizeName']         = $sizeName;
                $params['displayOrder']     = 0;

                if($IdToEdit != '') {
                    $params['sizeStatus']   = $status;
                    $this->model->sizeUpdateById($params, $IdToEdit);
                    $actMsg['message']      = 'Data updated successfully.';
                } else {
                    $params['sizeStatus']   = 'Y';
                    $params['displayOrder'] = date('Y-m-d H:i:s');
                    $this->model->newSize($params);
                    $actMsg['message']      = 'Data inserted successfully.';
                }
                $actMsg['type']             = 1;
            }
            else
                $actMsg['message']        = 'Size already exists.';  
        }
        else
            $actMsg['message']        = 'Size required!';
            
		return $actMsg;
    }

    function delSize() {
        $actMsg['type']             = 0;
        $actMsg['message']          = 'deleted';

        $IdToEdit                   = trim($this->_request['IdToEdit']);
        if($IdToEdit != '') {
            $rowCountForGSM         = $this->model->checkExistsAnother(TBL_PRODUCT_GSM." tpg", "tpg.gsmId", " tpg.sizeId='".addslashes($IdToEdit)."'");
            $rowCountForType        = $this->model->checkExistsAnother(TBL_PRODUCT_TYPE." tpt", "tpt.typeId", " tpt.sizeId='".addslashes($IdToEdit)."'");
            $rowCountForProduct     = $this->model->checkExistsAnother(TBL_PRODUCT." tp", "tp.productId", " tp.sizeId='".addslashes($IdToEdit)."'");
            if($rowCountForGSM > 0 || $rowCountForType > 0 || $rowCountForProduct > 0) {
                $actMsg['message']  = 'Size already exists!';
            } else {
                if($this->model->deleteSize($IdToEdit)) {
                    $actMsg['message']  = 'Delete successful.';
                    $actMsg['type']     = 1;
                } else {
                    $actMsg['message']  = 'DB error! Please try again.';
                }
            }
        } else {
            $actMsg['message']      = 'Data not found!';
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
                            $params['sizeStatus']       = 'Y';
                            break;
                        case "2":
                            $params['sizeStatus']       = 'N';
                            break;
                        default:
                            $this->response('', 406);
                    }
    
                    $this->model->sizeUpdateById($params, $val);
                    $actMsg['type']           = 1;
                    $actMsg['message']        = 'Operation successful.';
                }
            }else{
                $actMsg['message']        = 'Please check at least one checkbox';
            }
        }   
        
        return $actMsg;
    }

    function addEditSizefrm() {
        $actMsg['type']             = 1;
        $actMsg['message']          = '';

        $html                       = '';
        $IdToEdit                   = trim($this->_request['IdToEdit']);
    

        $html .= '<div class="row mb-2">
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="text" name="sizeName" value="" class="form-control" placeholder="Type Size Name">
                </div>
            </div>
        </div>';

        
        $html .= '<input type="hidden" name="IdToEdit" value="'.$IdToEdit.'" />';
        
        $html .= '</div>';

        $actMsg['htmlContent'] = $html;

        return $actMsg;
    }
}