<?php defined('BASE') OR exit('No direct script access allowed.');
class PapertypeController extends REST
{
    private    $model;
    private    $pmodel;
    protected  $response = [];

    public function __construct(PapertypeModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new PapertypeModel;

        $pmodel = new ProductlistModel;
        $this->model = $model;
        $this->pmodel = $pmodel;
    }

    function index($act = []) {
        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);
        $ExtraQryStr                            = 1;

        $this->response['rowCount']             = $this->model->typeCount($ExtraQryStr);
        if($this->response['rowCount']) {
            $p                                  = new Pager;
            $this->response['limit']            = VALUE_PER_PAGE;
            $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
            $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);

            $this->response['type']             = $this->model->getTypeByLimit($ExtraQryStr, $start, $this->response['limit']);

            if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                $this->response['page']         = ($this->_request['page']) ? $this->_request['page'] : 1;
                $this->response['totalPage']    = ceil($this->response['rowCount'] / $this->response['limit']);
                $this->response['pageList']     = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
            }
        }

        if(isset($this->_request['editid']) || isset($act['editid'])) {
            $editid                             = ($this->_request['editid'])? $this->_request['editid']:$act['editid'];
            if($editid) {

                $this->response['selectedCategory']      = $this->model->getCategoryByID("typeId = ".addslashes($editid));
                $this->response['selectedSubCategory']      = $this->model->getCategoryByID("typeId = ".addslashes($editid));

                showArray($this->response['selectedCategory']); exit;

            }
        }

        $this->response['productSize']          = $this->pmodel->getAllProductSize();
        $this->response['parentType']           = $this->model->getAllParentType($ExtraQryStr= "parentTypeId=0");

        return $this->response;
    }

    function addEditType() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $shortName                  = '';
        
        $typeName                   = trim($this->_request['typeName']);
        $parentTypeId               = trim($this->_request['parentTypeId']);
        $IdToEdit                   = trim($this->_request['IdToEdit']);

        if(strlen($parentTypeId) < 1) {
            $parentTypeId           = 0;
        }
        
        if($typeName != '') {
            if($IdToEdit!= '')
                $sel_ContentDetails = $this->model->checkExistence("typeName = '".addslashes($typeName)."' AND typeId != ".$IdToEdit);

            if(strlen($typeName) > 2) {
                $shortName = strtoupper(substr($typeName, 0, 3));
            } else {
                $shortName = strtoupper($typeName);
            }

            if(sizeof($sel_ContentDetails) < 1) {
                    $params                            = array();
                    $params['typeName']                = $typeName;
                    $params['shortName']               = $shortName;
                    $params['parentTypeId']            = $parentTypeId;
                    $params['typeStatus']              = 'Y';
                    $this->model->newType($params);

                    $actMsg['message']          = 'Data inserted successfully.';
                    $actMsg['type']             = 1;
            }
            else
                $actMsg['message']        = 'Category already exists.';
        }
        else
            $actMsg['message']        = 'Category name required!';
            
		return $actMsg;
    }

    function sizeChart($opt = []) {
        $typeName           = trim($opt['typeName']);
        $sizeNames          = '';
        if($typeName != '') {
            $records        = $this->model->getSelectedSizeByName($typeName);
            if(is_array($records) && count($records) > 0) {
                foreach($records as $item) {
                    $sizeNames .= $item['sizeName'].'", ';
                }
            }
        }
        return rtrim($sizeNames, ", ");
    }

    function subCategoryList($opt = []) {
        $typeName           = trim($opt['typeName']);
        $typeId             = trim($opt['typeId']);
        $typeName           = '';
        if($typeId != '') {
            $records        = $this->model->getSelectedSubCategoryByName($typeName, $typeId);
            if(is_array($records) && count($records) > 0) {
                foreach($records as $item) {
                    $typeName .= $item['typeName'].', ';
                }
            }
        }
        return rtrim($typeName, ", ");
    }

    function multiAction() {
        $actMsg['type']           = 0;
        $actMsg['message']        = '';

        if($this->_request['multiAction']){
            foreach($this->_request['selectMulti'] as $val) {
                $params = array();
                switch($this->_request['multiAction']) {
                    case "1":
                        $params['typeStatus']       = 'Y';
                        break;
                    case "2":
                        $params['typeStatus']       = 'N';
                        break;
                    default:
                        $this->response('', 406);
                }

                $this->model->typeUpdateById($params, $val);
                $actMsg['type']           = 1;
                $actMsg['message']        = 'Operation successful.';
            }
        }   
        
        return $actMsg;
    }

    function addEditTypefrm() {
        $actMsg['type']             = 1;
        $actMsg['message']          = '';

        $html                       = '';
        $IdToEdit                   = trim($this->_request['IdToEdit']);
    

        $html .= '<div class="row mb-2">
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="text" name="typeName" value="" class="form-control" placeholder="Add Type Name">
                </div>
            </div>
        </div>';

        
        $html .= '<input type="hidden" name="IdToEdit" value="'.$IdToEdit.'" />';
        
        $html .= '</div>';

        $actMsg['htmlContent'] = $html;

        return $actMsg;
    }
}