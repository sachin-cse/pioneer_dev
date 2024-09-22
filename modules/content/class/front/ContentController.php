<?php 
defined('BASE') OR exit('No direct script access allowed.');
class ContentController  extends REST
{
	private    $model;
	protected  $pageview ;
	protected  $response = array();
	
    public function __construct($model) {
    	parent::__construct();
        $this->model        = new $model;
    }
    
	function index($pageData) {
        $this->response['pageData']         = $pageData;
           
        if($pageData['categoryId'])
    	   $this->response['pageContent']   = $this->content($pageData['categoryId']);
    	
    	$this->pageview                     = 'index.php';
    	$this->response['body']             = $this->pageview;
        
        return $this->response; 
    }
    
    function content($categoryId) {
            
        $rsArry 				            = [];

        $rsArry['contentCount']	            = $this->model->countContentbymenucategoryId($categoryId);

        if($rsArry['contentCount']) {

            $p                              = new Pager;
            $rsArry['contentLimit']         = VALUE_PER_PAGE;
            $start                          = $p->findStart($rsArry['contentLimit'], $this->_request['page']);
            $contentPages                   = $p->findPages($rsArry['contentCount'], $rsArry['contentLimit']);

            $rsArry['content']              = $this->model->getContentbymenucategoryId($categoryId, $start, $rsArry['contentLimit']);

            if($rsArry['contentCount'] > 0 && ceil($rsArry['contentCount'] / $rsArry['contentLimit']) > 1) {
                
                $rsArry['contentPage']      = ($this->_request['page']) ? $this->_request['page'] : 1;
                $rsArry['totalContentPage'] = ceil($rsArry['contentCount'] / $rsArry['contentLimit']);

                $rsArry['contentPageList']  = $p->pageList($rsArry['contentPage'], $_SERVER['REQUEST_URI'], $contentPages);
            }
    	    return $rsArry;
        }
    }
}
?>