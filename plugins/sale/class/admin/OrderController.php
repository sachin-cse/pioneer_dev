<?php defined('BASE') OR exit('No direct script access allowed.');
class OrderController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(OrderModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new OrderModel;

        $this->model = $model;
    }

    function index($act = []) {

    }

    function modPage() {
        $srch = trim($this->_request['srch']);

        if ($srch) {
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
    }
}