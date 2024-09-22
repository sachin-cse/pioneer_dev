<?php defined('BASE') OR exit('No direct script access allowed.');
class PapertypeController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(PapertypeModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new PapertypeModel;

        $this->model = $model;
    }

    function index($act = []) {


        //var_dump($this->session->read('UID'));
            
        $this->response['linkedPages']          = $this->model->getLinkedPages($this->_request['pageType'], 0, 100);
            
        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);
        
        if(isset($this->_request['editid']) || isset($act['editid']) || $this->_request['dtaction'] == 'add') {
            
            $editid = ($this->_request['editid'])? $this->_request['editid'] : $act['editid'];
            
            if($editid) {
                $this->response['record']       = $this->model->categoryById($editid);
            }

            $ExtraQryStr  = (!$this->_request['parentId']) ? "tpc.parentId = 0" : "tpc.parentId = ".addslashes($this->_request['parentId']);

            $this->response['rowCount']     = $this->model->categoryCount($ExtraQryStr);
            

            if($this->response['rowCount']) {

                $p                          = new Pager;
                $this->response['limit']    = VALUE_PER_PAGE;
                $start                      = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                      = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['records']      = $this->model->getCategoryByLimit($ExtraQryStr, $start, $this->response['limit']);

                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
            
        }
        else {

            //echo $this->session->read('UID'); exit;
            
            $ExtraQryStr  = (!$this->_request['parentId']) ? "tpc.parentId = 0" : "tpc.parentId = ".addslashes($this->_request['parentId']);

            $this->response['rowCount']     = $this->model->categoryCount($ExtraQryStr);
            //echo $this->response['rowCount']; exit;

            if($this->response['rowCount']) {

                $p                          = new Pager;
                $this->response['limit']    = VALUE_PER_PAGE;
                $start                      = $p->findStart($this->response['limit'], $this->_request['page']);
                $pages                      = $p->findPages($this->response['rowCount'], $this->response['limit']);

                $this->response['records']      = $this->model->getCategoryByLimit($ExtraQryStr, $start, $this->response['limit']);

                if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                    $this->response['page']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                    $this->response['totalPage'] = ceil($this->response['rowCount'] / $this->response['limit']);

                    $this->response['pageList']  = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
                }
            }
        }
        
        return $this->response;
    }

    function nested2category($data, $selected, $nbsp='') {
        $result = array();

        if (sizeof($data) > 0) {
            foreach ($data as $entry) {
                $active = ($entry['categoryId'] == $selected) ? 'selected' : '';
                $result[] = sprintf(
                    '<option value="%s" %s>%s%s</option>%s',
                    $entry['categoryId'], $active, $nbsp, $entry['categoryName'],
                    $this->nested2category($entry['children'], $selected, $nbsp.'&nbsp;&nbsp;')
                );
            }
        }

        return implode($result);
    }
    
    function addEditCategory(){

        //echo 1; exit;
        
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $parentId                   = ($this->_request['parentId']) ? trim($this->_request['parentId']) : 0;

        $categoryName           	= trim($this->_request['categoryName']);
        $categoryDescription        = trim($this->_request['categoryDescription']);
        $status                	    = trim($this->_request['status']);
             
        if($categoryName != '') {
            
            if($this->_request['IdToEdit']!= '')
                $sel_ContentDetails = $this->model->checkExistence("categoryName = '".addslashes($categoryName)."' AND parentId = ".$parentId." AND categoryId != ".$this->_request['IdToEdit']);
            else
                $sel_ContentDetails = $this->model->checkExistence("categoryName = '".addslashes($categoryName)."' AND parentId = ".$parentId);

            if(sizeof($sel_ContentDetails) < 1) {

                //permalink--------------
                $ENTITY          = TBL_PRODUCT_CATEGORY;

                if($parentId) {
                    $cparentId       = $parentId;
                    while($cparentId) {
                        $cData       = $this->model->categoryById($cparentId);
                        $cparentId   = $cData['parentId'];
                        $categoryUrl = $categoryUrl.'/'.$cData['permalink'];
                    }
                }
                $categoryUrl         = '/'.$categoryUrl.'/';
                //categoryUrl-------------

                $params                         = array();
                $params['parentId']             = $parentId;
                $params['categoryName']         = $categoryName;
                $params['categoryDescription']  = $categoryDescription;
                $params['status']               = $status;
                $params['userId']               = $this->session->read('UID');

                if($this->_request['IdToEdit'] != '') {
                    //$dataBeforeUpdate           = $this->model-> categoryById($this->_request['IdToEdit']);

                    $this->model->updateCategoryBycategoryId($params, $this->_request['IdToEdit']);

                    $actMsg['editid']           = $this->_request['IdToEdit'];
                    $actMsg['message']          = 'Data updated successfully.';
                }
                else {
                    $params['entryDate']        = date('Y-m-d H:i:s');
                    $actMsg['editid']           = $this->model->newCategory($params);

                    $actMsg['message']          = 'Data inserted successfully.';
                }
                $actMsg['type']                 = 1;

                $targetLocation = MEDIA_FILES_ROOT.DS.$this->_request['pageType'];
                // $targetFile     = MEDIA_FILES_SRC.DS.$this->_request['pageType'];
                // $ogUrl          = DS.$this->_request['pageType'];

                // if (!file_exists($targetLocation) && !is_dir($targetLocation)) 
                //     $this->createMedia($targetLocation);

                // $settings = $this->model->settings($this->_request['pageType']);
                // $settings = unserialize($settings['value']);

                // $selData        = $this->model->categoryById($actMsg['editid']);
 
            }
            else
                $actMsg['message']        = 'Category already exists.';   
        }
        else
           $actMsg['message']        = 'Fields marked with (*) are mandatory.';
        
		return $actMsg;
    }

    
    function cropImage(){

        $settings = $this->model->settings($this->_request['pageType']);
        $settings = unserialize($settings['value']);
        
        if($this->_request['IdToEdit']){
            $dataBeforeUpdate   = $this->model->categoryById($this->_request['IdToEdit']);
            $fileName           = $dataBeforeUpdate['categoryImage'];
            $image              = explode('.',$fileName);
            $image              = $image[0];
        }

        $targetLocation = MEDIA_FILES_ROOT.DS.$this->_request['pageType'];
        
        if (!file_exists($targetLocation) && !is_dir($targetLocation)) 
            $this->createMedia($targetLocation);

        $fObj       = new FileUpload;

        if($this->_request['w'] && $this->_request['h']) {
            $src        = $targetLocation.DS.'normal'.DS.$fileName;
            $dst        = $targetLocation.DS.'thumb'.DS.$fileName;
            $cropped    = $fObj->resizeThumbnailImage($dst, $src, $this->_request['w'] , $this->_request['h'],$this->_request['x'], $this->_request['y'], 1);
        }
    }
    
    function createMedia($targetLocation) {
        $indexingSource = MEDIA_FILES_ROOT.DS.'index.php';
        @mkdir($targetLocation, 0755); 
        copy($indexingSource, $targetLocation.DS.'index.php');

        @mkdir($targetLocation.DS.'large',      0755); 
        copy($indexingSource, $targetLocation.DS.'large'.DS.'index.php');

        @mkdir($targetLocation.DS.'normal',     0755); 
        copy($indexingSource, $targetLocation.DS.'normal'.DS.'index.php');

        @mkdir($targetLocation.DS.'small',      0755);   
        copy($indexingSource, $targetLocation.DS.'small'.DS.'index.php');

        @mkdir($targetLocation.DS.'thumb',      0755); 
        copy($indexingSource, $targetLocation.DS.'thumb'.DS.'index.php');
    }
    
    function multiAction() {
        $actMsg['type']           = 0;
        $actMsg['message']        = '';
        
        if($this->_request['multiAction']){
            foreach($this->_request['selectMulti'] as $val) {
                
                $params = array();  
                
                switch($this->_request['multiAction']) {
                    case "1":
                        $params['status'] = 'Y';
                        break;
                    case "2":
                        $params['status'] = 'N';
                        break;
                    case "3":
                        $params['delete'] = 'Y';
                        break;
                    case "4":
                        $params['isShowcase']   = 'Y';
                        break;
                    case "5":
                        $params['isShowcase']   = 'N';
                        break;
                    default:
                        $this->response('', 406);
                } 
                
                if($params['delete'] == 'Y') {
                    $selData = $this->model->categoryById($val);
                    if($selData['categoryImage']) {
                        @unlink(MEDIA_FILES_ROOT.DS.$this->_request['pageType'].DS.'normal'.DS.$selData['categoryImage']);
                        @unlink(MEDIA_FILES_ROOT.DS.$this->_request['pageType'].DS.'thumb'.DS.$selData['categoryImage']);
                        @unlink(MEDIA_FILES_ROOT.DS.$this->_request['pageType'].DS.'large'.DS.$selData['categoryImage']);
                    }
                                    
                    $this->model->deleteCategoryBycategoryId($val);
                }
                else
                    $this->model->updateCategoryBycategoryId($params, $val);
                
                $actMsg['type']           = 1;
                $actMsg['message']        = 'Operation successful.';
            }
        }
        
        return $actMsg;
    }

    function deleteFile(){
        $actMsg['type']           = 0;
        $actMsg['message']        = '';

        if($this->_request['DeleteFile'] == 'categoryImage'){
            $selData = $this->model->categoryById($this->_request['IdToEdit']);
            if($selData['categoryImage']){
                @unlink(MEDIA_FILES_ROOT.DS.$this->_request['pageType'].DS.'normal'.DS.$selData['categoryImage']);
                @unlink(MEDIA_FILES_ROOT.DS.$this->_request['pageType'].DS.'thumb'.DS.$selData['categoryImage']);
                @unlink(MEDIA_FILES_ROOT.DS.$this->_request['pageType'].DS.'large'.DS.$selData['categoryImage']);

                // update image field to blank
                $params                     = array();
                $params['categoryImage']    = '';
                $this->model->updateCategoryBycategoryId($params, $this->_request['IdToEdit']);
            }

            $actMsg['type']           = 1;
            $actMsg['message']        = 'Image deleted successfully.';
        }

        return $actMsg;  
    }
    
    function swap() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        
        $listingCounter = 1;
        
        foreach ($this->_request['recordsArray'] as $recordID) {
            $params = array();
            $params['displayOrder'] = $listingCounter;
            $this->model->updateCategoryBycategoryId($params, $recordID);
            $listingCounter = $listingCounter + 1;
        }
        
        if($listingCounter > 1){
            $actMsg['type']             = 1;
            $actMsg['message']          = 'Operation successful.';
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