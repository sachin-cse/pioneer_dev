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

            // search datewise

            if(isset($this->_request['Reset'])){
                $_SESSION["fromDate"] = '';
                $_SESSION["toDate"] = '';
                $_SESSION["choose_date"] = '';

                $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'&vendorId='.$_GET['vendorId']);
            }

            if($_GET['vendorId']){
                $limit = 20;

                $from_date = '';
                $to_date = '';

                if($_GET['purchaseDate'] !="")
                {

                    $from_date = $_GET['purchaseDate'];
                    $_SESSION["fromDate"] = $_GET['purchaseDate'];

                    $to_date = $_GET['purchaseDate'];
                    $_SESSION["toDate"] = $_GET['purchaseDate'];

                }
                else
                {

                    //$from_date=>max date
                    //$to_date=>min date

                    if(!empty($this->_request['choose_date'])){

                        if($this->_request['choose_date'] == 'today'){
                            $from_date = date("Y-m-d"); 
                            $to_date = date("Y-m-d");
                            $_SESSION["choose_date"] = $this->_request['choose_date'];

                        }else if($this->_request['choose_date'] == 'this_week'){
                            $from_date = date("Y-m-d");
                            $to_date = date('Y-m-d', strtotime("this week"));
                            $_SESSION["choose_date"] = $this->_request['choose_date'];

                        }else if($this->_request['choose_date'] == 'this_month'){
                            $from_date = date("Y-m-d");
                            $to_date = date('Y-m-d', strtotime("first day of this month"));
                            $_SESSION["choose_date"] = $this->_request['choose_date'];

                        }else if($this->_request['choose_date'] == 'last_3_month'){
                            $from_date = date("Y-m-d");
                            $to_date = date('Y-m-d', strtotime("-3 Months"));
                            $_SESSION["choose_date"] = $this->_request['choose_date'];

                        }elseif($this->_request['choose_date'] == 'last_6_month'){
                            $from_date = date("Y-m-d");
                            $to_date = date('Y-m-d', strtotime("-6 Months"));
                            $_SESSION["choose_date"] = $this->_request['choose_date'];
                        }
                    } 
                }
                
                $this->response['vendorDetails']   = $this->model->getVendorsDetails($_GET['vendorId']);
                $this->response['pageName'] = 'vendorwiseOrder.php';

                $this->response['rowCount'] = count($this->model->getVendorwiseOrder($_GET['vendorId'],$from_date, $to_date, $start=0, $limit));

                // echo $this->response['rowCount'];

                // pagination
                if($this->response['rowCount']){
                    $this->response['limit']            = 20;
                    $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
                    $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);

                    $this->response['vendorTransaction']          = $this->model->getVendorwiseOrder($_GET['vendorId'],$from_date,$to_date,$start,$this->response['limit']);
                }
                // pagination
                // $this->response['rowCount'] =        
                // $start                              = $p->findStart($limit, $this->_request['page']);
                // $pages                              = $p->findPages($this->response['rowCount'], $limit);
                // $this->response['limit'] = $limit;
                // $this->response['vendorTransaction']   = $this->model->getVendorwiseOrder($_GET['vendorId'],  $from_date, $to_date, $limit);

            }


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
        // echo "<pre>";
        // print_r($this->response);
        // echo "</pre>";
        // exit;
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

    function getVendorwiseOrder() {
        $from_date = '';
        $to_date = '';
        $start = 0;
        $actMsg['type']             = 0;
        $actMsg['message']          = '';

        $vendorId                   = trim($this->_request['vendorId']);

        if($vendorId != '') {
            $limit = 6;
            $vendorwiseOrder  = $this->model->getVendorwiseOrderGroup($vendorId);

            //showArray($vendorwiseOrder); exit;

            if(is_array($vendorwiseOrder['orderDate']) && count($vendorwiseOrder['orderDate']) > 0) {
                    $html = '<tr class="transactionTbl'.$vendorId.'"><td colspan="8"><table class="table table-hover viewChildTable"><thead><tr><th class="text-center">Purchase Date</th><th class="text-center">Quantity</th><th class="text-center">Amount</th><th class="text-center">Action</th></tr></thead><tbody class="swap">';
                for($a = 0; $a < count($vendorwiseOrder['orderDate']); $a++){
                    $html .= '<tr><td class="text-center">' .date('jS M, Y', strtotime($vendorwiseOrder['orderDate'][$a])).'</td><td class="text-center">' .$vendorwiseOrder['totalQty'][$a]. '</td><td class="text-center">' .$vendorwiseOrder['totalAmt'][$a]. '</td><td  class="text-center"><a href="index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'&vendorId='.$vendorId.'&purchaseDate='.$vendorwiseOrder['orderDate'][$a].'" class="viewMoreBtn tooltipBtn"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a></td></tr>';
                }
                $html .= '</tbody><tr><td>
                <td colspan="3"><a class="closeVendorHistoryTbl tooltipBtn" data-vendorid="' .$vendorId.'"><i class="fa fa-times"></i><span class="tooltiptext">Close Record</span></a>
                <td><a href="index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'&vendorId='.$vendorId.'" class="viewMoreBtn tooltipBtn"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i><span class="tooltiptext">View Orders</span></a></td></td></td></tr></tbody></table></td></tr>';  
            }
            else
            {
                $html = '<tr class="transactionTbl'.$vendorId.'"><td colspan="8"><table class="table table-hover viewChildTable"><tbody class="swap"><tr><td class="text-center" colspan="3">No record found</td></tr></tbody><tr><td><td colspan="3"><a class="closeVendorHistoryTbl tooltipBtn" data-vendorid="' .$vendorId.'"><i class="fa fa-times"></i><span class="tooltiptext">Close Record</span></a></td></td></tr></tbody></table></td></tr>';   
            }
        }

        $actMsg['html'] = $html;

        return $actMsg;
    }
}