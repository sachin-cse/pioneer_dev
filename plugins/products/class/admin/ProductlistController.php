<?php defined('BASE') OR exit('No direct script access allowed.');
class ProductlistController extends REST
{
    private    $model;
    protected  $response = [];

    public function __construct(ProductlistModel $model = null) {
        parent::__construct();

        if ($model == null)
            $model  = new ProductlistModel;

        $this->model = $model;
    }

    function index($act = []) {
        $settings                               = $this->model->settings($this->_request['pageType']);
        $this->response['settings']             = unserialize($settings['value']);

        $ExtraQryStr                        = 1;

        // SEARCH START --------------------------------------------------------------
        if(isset($this->_request['searchText']))
            $this->session->write('searchText', $this->_request['searchText']);

        if($this->session->read('searchText'))
            $ExtraQryStr        .= " AND projectName LIKE '%".addslashes($this->session->read('searchText'))."%'";

        if(isset($this->_request['searchStatus']))
            $this->session->write('searchStatus', $this->_request['searchStatus']);

        if($this->session->read('searchStatus'))
            $ExtraQryStr        .= " AND status = '".addslashes($this->session->read('searchStatus'))."'";

        if(isset($this->_request['searchGSM']))
            $this->session->write('searchGSM', $this->_request['searchGSM']);

        if($this->session->read('searchGSM'))
            $ExtraQryStr        .= " AND isShowcase = '".addslashes($this->session->read('searchShowcase'))."'";

        if(isset($this->_request['searchType']))
            $this->session->write('searchType', $this->_request['searchType']);

        if($this->session->read('searchType'))
            $ExtraQryStr        .= " AND menucategoryId = ".addslashes($this->session->read('searchPage'));

        if(isset($this->_request['searchSize']))
            $this->session->write('searchSize', $this->_request['searchSize']);

        if($this->session->read('searchSize'))
            $ExtraQryStr        .= " AND menucategoryId = ".addslashes($this->session->read('searchPage'));

        if(isset($this->_request['Reset']) || isset($this->_request['Search'])) {

            if(isset($this->_request['Reset'])){

                $this->session->write('searchText',     '');
                $this->session->write('searchStatus',   '');
                $this->session->write('searchGSM',      '');
                $this->session->write('searchType',     '');
                $this->session->write('searchSize',     '');
            }

            $this->model->redirectToUrl(SITE_ADMIN_PATH.'/index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId']);
        }
        // SEARCH END ----------------------------------------------------------------
        $this->response['rowCount']             = $this->model->productCount($ExtraQryStr);
        if($this->response['rowCount']) {
            $p                                  = new Pager;
            $this->response['limit']            = VALUE_PER_PAGE;
            $start                              = $p->findStart($this->response['limit'], $this->_request['page']);
            $pages                              = $p->findPages($this->response['rowCount'], $this->response['limit']);

            $this->response['products']         = $this->model->getProductCategoriwiseByLimit($ExtraQryStr, $start, $this->response['limit']);

            if($this->response['rowCount'] > 0 && ceil($this->response['rowCount'] / $this->response['limit']) > 1) {
                $this->response['page']         = ($this->_request['page']) ? $this->_request['page'] : 1;
                $this->response['totalPage']    = ceil($this->response['rowCount'] / $this->response['limit']);
                $this->response['pageList']     = $p->pageList($this->response['page'], $_SERVER['REQUEST_URI'], $pages);
            }
        }

        // Get product GSM ...
        $this->response['productGSM']       = $this->model->getAllProductGSM();

        // Get product type ...
        $this->response['productType']      = $this->model->getAllProductType();

        // Get product size ...
        $this->response['productSize']      = $this->model->getAllProductSize();
 
        // Rate of Interest ...
        $arr                                = array('rateOfInterest');
        $gvars                              = new GlobalArray($arr);
        $this->response['rateOfInterest']   = $gvars->_array['rateOfInterest'];

        // Piece Per Bag ...
        $arr                                = array('piecePerBag');
        $gvars                              = new GlobalArray($arr);
        $this->response['piecePerBag']      = $gvars->_array['piecePerBag'];

        // Packet Per Bag ...
        $arr                                = array('packetPerBag');
        $gvars                              = new GlobalArray($arr);
        $this->response['packetPerBag']     = $gvars->_array['packetPerBag'];

        if(isset($this->_request['editid']) || isset($act['editid'])) {
            $editid                         = ($this->_request['editid']) ? $this->_request['editid'] : $act['editid'];
            if($editid) {
                $this->response['product']  = $this->model->getProductById("tp.productId = ".addslashes($editid));
            }
        }
        return $this->response;
    }

    function modPage() {
        $srch = trim($this->_request['srch']);

        if ($srch) {
            return $this->model->searchLinkedPages($this->_request['mid'], $this->_request['pageType'], $srch, 0, 10);
        }
    }

    function addEditProduct() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $currUserID                 = trim($this->session->read('UID'));

        $GSMID                      = trim($this->_request['sltGSM']);
        $TypeID                     = trim($this->_request['sltType']);
        $SizeID                     = trim($this->_request['sltSize']);
        $productName                = trim($this->_request['pName']);

        $piecesPerBag               = trim($this->_request['piecesPerKg']);
        $stockAlertQty               = trim($this->_request['stockAlertQty']);

        $IdToEdit                   = trim($this->_request['IdToEdit']);

        if($GSMID != '' && $TypeID != '' && $SizeID != '' && $productName != '' && $piecesPerBag != '' && $stockAlertQty != '') {

            if($IdToEdit!= '')
                $sel_ContentDetails = $this->model->checkExistence("(gsmId = '".addslashes($GSMID)."' AND sizeId = '".addslashes($SizeID)."' AND typeId = '".addslashes($TypeID)."') OR (productName = '".addslashes($productName)."') AND productId != ".$IdToEdit);
            else
                $sel_ContentDetails = $this->model->checkExistence("(gsmId = '".addslashes($GSMID)."' AND sizeId = '".addslashes($SizeID)."' AND typeId = '".addslashes($TypeID)."') OR (productName = '".addslashes($productName)."') ");

            if(sizeof($sel_ContentDetails) < 1) {

            $params                 = [];
            $params['productName']  = $productName;
            $params['gsmId']        = $GSMID;
            $params['sizeId']       = $SizeID;
            $params['typeId']       = $TypeID;
            $params['piecesPerKg']    = $piecesPerBag;
            $params['stockAlertQty']    = $stockAlertQty;
            $params['userId']       = $currUserID;
            $params['status']       = 'Y';
            $params['displayOrder'] = 0;
            
            $params['modifiedDate'] = date('Y-m-d H:i:s');

            if($IdToEdit != '') {
                $productId = $IdToEdit;
                $this->model->updProduct($params, $productId);
                $actMsg['message']  = 'Product update successful.';
            } else {
                $params['entryDate']= date('Y-m-d H:i:s');

                //showArray($params); exit;

                $productId          = $this->model->insProduct($params);
                $actMsg['message']  = 'Product added successfully';
            }

            $actMsg['type']         = 1;
        }
        else
        {
            $actMsg['message']      = 'Product information already exists.';
        }

        } else {
            $actMsg['message']      = 'All fields are mandatory.';
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
                        $params['status']       = 'Y';
                        break;
                    case "2":
                        $params['status']       = 'N';
                        break;
                    default:
                        $this->response('', 406);
                }

                $this->model->updProduct($params, $val);
                $actMsg['type']           = 1;
                $actMsg['message']        = 'Operation successful.';
            }
        }   
        
        return $actMsg;
    }

    function productNameByAttr() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['productName']      = '';
        $actMsg['subcategories']    = null;

        $selectedGSM                = trim($this->_request['selectedGSM']);
        $selectedType               = trim($this->_request['selectedType']);
        $selectedSize               = trim($this->_request['selectedSize']);
        if($selectedGSM != '') {
            $selectedGSMData = $this->model->getProductGSMById($selectedGSM);
            if(is_array($selectedGSMData) && count($selectedGSMData) > 0) {
                $actMsg['productName']      = $selectedGSMData['gsmName'];
            }
        }

        if($selectedType != '') {
            $selectedProductTypeData        = $this->model->getProductTypeById($selectedType);
            if(is_array($selectedProductTypeData) && count($selectedProductTypeData) > 0) {
                $actMsg['productName']      .= $selectedProductTypeData['shortName'];
            }
            $selectedCategories             = $this->model->getProductTypeByParentId($selectedType);
            if(is_array($selectedCategories) && count($selectedCategories) > 0) {
                $actMsg['subcategories']    = $selectedCategories;
            }

        }

        if($selectedSize != '') {
            $selectedProductSizeData = $this->model->getProductSizeById($selectedSize);
            if(is_array($selectedProductSizeData) && count($selectedProductSizeData) > 0) {
                $actMsg['productName']      .= $selectedProductSizeData['sizeName'];
            }
        }

        return $actMsg;
    }

    function productPricePerUnit() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['piecePerUnit']     = 0;
        $actMsg['perPieceWeight']   = 0;

        $piecesPerBag               = trim($this->_request['piecesPerBag']);
        $currWeight                 = trim($this->_request['currWeight']);
        if(floatval($currWeight) > 0 && floatval($currWeight) > 0 ) {
            $temp                       = round( (1000 / ($currWeight / $piecesPerBag)), 0);
            $tempPerPieceWeight         = round( (1000 / $temp), 3);
            $actMsg['piecePerUnit']     = $temp;
            $actMsg['perPieceWeight']   = $tempPerPieceWeight;
        }
        return $actMsg;
    }

    function productPerBagWeight() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['perBagWeight']     = 0;


        $packetPerBag               = trim($this->_request['packetPerBag']);
        $piecesPerBag               = trim($this->_request['piecesPerBag']);
        $perPieceWeight             = trim($this->_request['perPieceWeight']);
        if(floatval($packetPerBag) > 0 && floatval($piecesPerBag) > 0 && floatval($perPieceWeight) > 0 ) {
            $temp                       = round( ( ($piecesPerBag * $packetPerBag * $perPieceWeight) / 1000), 2);
            $actMsg['perBagWeight']     = $temp;
        }
        return $actMsg;
    }

    function productBagPerSacks() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['bagPerSacks']      = 0;


        $sacksWeight                = trim($this->_request['sacksWeight']);
        $pricePerUnit               = trim($this->_request['pricePerUnit']);
        $piecesPerBag               = trim($this->_request['piecesPerBag']);
        $packetPerBag               = trim($this->_request['packetPerBag']);
        if(floatval($sacksWeight) > 0 && floatval($pricePerUnit) > 0 && floatval($piecesPerBag) > 0 && floatval($packetPerBag) > 0 ) {
            $temp                   = round( ($sacksWeight * $pricePerUnit)/($piecesPerBag * $packetPerBag), 2);
            $actMsg['bagPerSacks']  = $temp;
        }
        return $actMsg;
    }

    function paperCategories() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['categories']       = null;
        $actMsg['gsmList']          = null;

        $sizeId                     = trim($this->_request['sizeId']);

        if($sizeId != '') {
            $selectedPaperCategory  = $this->model->getPaperCategoryById($sizeId);
            $selectedPaperGSM       = $this->model->getPaperGSMById($sizeId);
            if(is_array($selectedPaperCategory) && count($selectedPaperCategory) > 0) {
                $actMsg['categories'] = $selectedPaperCategory;
            }
            if(is_array($selectedPaperGSM) && count($selectedPaperGSM) > 0) {
                $actMsg['gsmList']  = $selectedPaperGSM;
            }
        }

        return $actMsg;
    }

    function paperSubCategories() {
        $actMsg['type']             = 0;
        $actMsg['message']          = '';
        $actMsg['subCategoryHTML']  = '';

        $categoryId                 = trim($this->_request['categoryId']);

        if($categoryId != '') {
            $selectedCategories  = $this->model->getProductTypeByParentId($categoryId);
            $html = '';
            if(is_array($selectedCategories) && count($selectedCategories) > 0) {
                $html .= '<div class="row mb-2">';
                $html .= '<div class="col-sm-12">';
                $html .= '<div class="form-group">';
                $html .= '<select name="sltSubType" id="sltSubType" class="form-control">';
                $html .= '<option value="">Select Sub-category</option>';
                foreach($selectedCategories as $item) {
                    $html .= '<option value="'.$item['typeId'].'">'.$item['typeName'].'</option>';
                }
                $html .= '</select>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $actMsg['subCategoryHTML']  = $html;
        }

        return $actMsg;
    }

    function addEditFrm() {
        $actMsg['type']             = 1;
        $actMsg['message']          = '';

        $html                       = '';
        $IdToEdit                   = trim($this->_request['IdToEdit']);
        $productSize                = $this->model->getAllProductSize();

        // Piece Per Bag ...
        $arr                        = array('piecePerBag');
        $gvars                      = new GlobalArray($arr);
        $piecePerBag                = $gvars->_array['piecePerBag'];

        // Packet Per Bag ...
        $arr                        = array('packetPerBag');
        $gvars                      = new GlobalArray($arr);
        $packetPerBag               = $gvars->_array['packetPerBag'];
        
        $html .= '<div class="row mb-2">
            <div class="col-sm-6">
                <div class="form-group">
                    <select name="sltSize" id="sltSize" class="form-control">
                        <option value="">Select Size</option>';
                        if(is_array($productSize) && count($productSize) > 0) {
                            foreach($productSize as $item) {
                                $html .= '<option value="'.$item['sizeId'].'">'.$item['sizeName'].'"</option>';
                            }
                        }
        $html .='</select>
                </div>
            </div>
            <div class="col-sm-6">
                <select name="sltGSM" id="sltGSM" class="form-control">
                    <option value="">Select GSM</option>
                </select>
            </div>
        </div>';

        $html .= '<div class="row mb-2">
            <div class="col-sm-12">
                <select name="sltType" id="sltType" class="form-control">
                    <option value="">Select Category</option>
                </select>
            </div>
        </div>';

        $html .= '<div class="addOnSubCategory"></div>';

        $html .= '<div class="row mb-2">
            <div class="col-sm-12">
                <input type="text" name="pName" id="pName" value="" class="form-control" placeholder="Product Name" autocomplete="off" maxlength="255">
            </div>
        </div>';

        $html .= '<div class="row mb-2">';
            $html .= '<div class="col-sm-4">
                            <div class="form-group">
                                <select name="sltPiecesPerBag" id="sltPiecesPerBag" class="form-control">
                                    <option value="">Pieces Per Bag</option>';
                                    if(is_array($piecePerBag) && count($piecePerBag) > 0) {
                                        foreach($piecePerBag as $item) {
                                            $html .= '<option value="'.$item.'">'.$item.' Pieces</option>';
                                        }
                                    }
                        $html .='</select>
                            </div>
                        </div>';
            $html .= '<div class="col-sm-4">
                        <div class="form-group">
                            <select name="sltPacketPerBag" id="sltPacketPerBag" class="form-control">
                                <option value="">Packet per Bag</option>';
                                if(is_array($packetPerBag) && count($packetPerBag) > 0) {
                                    foreach($packetPerBag as $item) {
                                        $html .= '<option value="'.$item.'">'.$item.' Pieces</option>';
                                    }
                                }
                    $html .='</select>
                        </div>
                    </div>';
            $html .= '<div class="col-sm-4">
                        <input type="text" name="pWeight" id="pWeight" value="" class="form-control numbersOnly" placeholder="Weight" autocomplete="off" maxlength="255">
                        <small class="form-text text-muted weightHelpTxt"></small>
                    </div>';

        $html .= '</div>';

        $html .= '<input type="hidden" name="IdToEdit" value="'.$IdToEdit.'" />';

        $actMsg['htmlContent'] = $html;

        return $actMsg;
    }
}