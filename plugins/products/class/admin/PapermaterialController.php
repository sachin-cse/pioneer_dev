<?php defined('BASE') OR exit('No direct script access allowed.');
class PapermaterialController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(PapermaterialModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new PapersizeModel;

        $this->model = $model;
    }

    function index($act = []) {
        if(!empty($this->_request['searchText'])){
            $this->session->write('searchText', $this->_request['searchText']);
            $ExtraQryStr = " material_name LIKE '%".addslashes($this->_request['searchText'])."%'";
        }else{
            $this->session->write('searchText','');
            $ExtraQryStr = 1;
        }

        if(isset($this->_request['Reset'])){
            $this->session->write('searchText','');
            $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
        }


        $settings = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);
        $this->response['rowCount']             = $this->model->materialCount($ExtraQryStr);

        if($this->response['rowCount'] > 0){
            $p                                  = new Pager;
            $this->response['limit']            = 8;
            $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
            $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);
            $this->response['material']             = $this->model->getMaterialByLimit($ExtraQryStr, $start, $this->response['limit']);

            if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1){
                $this->response['page']         = ($this->_request['page']) ? $this->_request['page'] : 1;
                $this->response['totalPage']    = ceil($this->response['rowCount'] / $this->response['limit']);
                $this->response['pageList']     = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
            }
        }

        if($this->_request['editid'] || $act['editid']){
            $editid = !empty($this->_request['editid']) ? $this->_request['editid']:$act['editid'];

            if(!empty($editid)){
                $this->response['selectedMaterial'] = $this->model->checkMaterialExistence("mtId = ".addslashes($editid));
            }
        }
        return $this->response;
    }

    function addEditMaterial(){

        $actMsg['type']             = 0;
        $actMsg['message']          = '';

        $materialName               = trim($this->_request['material_name']);
        $status                     = trim($this->_request['material_status']);
        $IdToEdit                   = trim($this->_request['IdToEdit']);

        if($materialName != ''){

            if(!empty($IdToEdit)){
                $material_existance = $this->model->checkMaterialExistence("material_name = '".addslashes($materialName)."' AND mtId != ".addslashes($IdToEdit));
            }else{
                $material_existance = $this->model->checkMaterialExistence("material_name = '".addslashes($materialName)."'");
            }

            if($material_existance ==  0){
                $params = [];
                $params['material_name'] = $materialName;
                
                if(!empty($IdToEdit)){
                    $params['material_status'] = $status;
                    $this->model->materialUpdatedById($params,  $IdToEdit);
                    $actMsg['message'] = 'Data updated successfully';
                }else{
                    $params['material_status'] = 'Y';
                    $params['entryDate'] = date('Y-m-d H:i:s');
                    $this->model->insertData($params);
                    $actMsg['message'] = 'Data added successfully';
                }

                $actMsg['type'] = 1;
            }else{
                $actMsg['message'] = 'Material name already exists';
            }
        } else {
            $actMsg['message'] = 'Material name is required';
        }

        return $actMsg;
    }

    function delSingleMaterial(){
        $actMsg['type'] = 0;
        $actMsg['message'] = '';

        $IdToDelete = $this->_request['IdToEdit'];

        $checkExistance = $this->model->checkExistsAnother(TBL_PRODUCT_MATERIAL,'*',"mtId = ".addslashes($IdToDelete)."");

        if($checkExistance > 0){
            $this->model->materialDeletebyId($IdToDelete);
            $actMsg['message']='Data Delete successfully';
        }else{
            $actMsg['message']='Data not found';
        }
        $actMsg['type'] = 1;

        return $actMsg;
    }

    function swap(){
        // print_r($this->_request); exit;
        $actMsg['type'] = 0;
        $actMsg['message'] = '';
        $count = 1;
        foreach($this->_request['recordsArray'] as $dataId){
            $params = [];
            $params['displayOrder'] = $count;
            $this->model->materialUpdatedById($params, $dataId);
            $count+=1;
        }

        if($count > 1){
            $actMsg['message'] = 'Data reorder successfully';
        }else{
            $actMsg['message'] = 'Something went wrong';
        }

        $actMsg['type'] = 1;

        return $actMsg; exit;
    }

    function multiAction(){

        $actMsg['type'] = 0;
        $actMsg['message'] = '';

        if($this->_request['multiAction'] == ''){
            $actMsg['message'] = 'Please select an option';
        }else{
            $change_status = trim($this->_request['multiAction']) == '1' ? 'Y':'N';
            $flag = false;
            foreach($this->_request['selectMulti'] as $dataId){
                $params = [];
                $params['material_status'] = $change_status;

                if($this->_request['multiAction'] == '3'){
                    $this->model->materialDeletebyId($dataId);
                }else{
                    $this->model->materialUpdatedById($params, $dataId);
                }
                $flag = true;
            }

            if($flag){
                $actMsg['message'] = 'Record status change successfully';
            }else{
                $actMsg['message'] = 'Something went wrong';
            }

            $actMsg['type'] = 1;
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