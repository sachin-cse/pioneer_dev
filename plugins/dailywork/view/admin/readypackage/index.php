<?php
defined('BASE') OR exit('No direct script access allowed.');

if($data['packaging']) {
    $IdToEdit                       = $data['packaging']['packagingId'];
    $productId                      = $data['packaging']['productId'];
    $endProductId                   = $data['packaging']['endProductId'];
    $packCount                      = $data['packaging']['packCount'];
    $boxCount                       = $data['packaging']['boxCount'];
    $packagingName                  = $data['packaging']['packagingName'];
	
	$qrystrPermalink			    = 'packagingId != '.$IdToEdit;
} else {

    $IdToEdit                       = $this->_request['packagingId'];
    $productId                      = $this->_request['productId'];
    $endProductId                   = $this->_request['endProductId'];
    $packCount                      = $this->_request['packCount'];
    $boxCount                       = $this->_request['boxCount'];
    $packagingName                  = $this->_request['packagingName'];
	$qrystrPermalink			    = 1;
}
// $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
// echo "<pre>";
// print_r();
// echo "</pre>";
?>
<div class="container-fluid">
    <div class="row">

        <!-- <div class="col-sm-6">
            <form name="searchForm" action="" method="post">
                <div class="form-inline">
                    <div class="form-group">
                        <input type="text" name="searchText" value="<?php echo $this->session->read('searchText')??''?>" placeholder="Search By Packaging Name" class="form-control">
                    </div>

                    <div class="form-group">
                        <input type="text" name="searchByboxCount" value="<?php echo $this->session->read('searchByboxCount')??''?>" class="form-control" placeholder="Search by box count" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                    </div>
    
                    <div class="form-group">
                        <button type="submit" name="Search" class="btn btn-info width-auto"><i class="fa fa-search"></i></button>
                        <button type="submit" name="Reset" class="btn btn-dark width-auto m-l-10"><i class="fa fa-refresh"></i></button>
                    </div>
                </div>
    
            </form>
        </div> -->

        <div class="col-sm-8">
            <form action="" method="post">
                <div class="card p-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                            if($data['getAllReadyProduct']) {
                                ?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>E.Product</th>
                                            <th>RP.Qty</th>
                                            <th>CRP.Qty</th>
                                            <th>CRP.Pics</th>
                                            <th>R.Pack</th>
                                            <!-- <th>Unpacking Product</th> -->
                                            <th>R.Box</th>
                                            <!-- <th>Unbox Pack</th> -->

                                            <!-- <th>Status</th>-->
                                            <!-- <th colspan="2">Paper Size</th> -->
                                            <th width="500">
                                                <div class="alert alert-success font-weight-bold">Records Found: <?php echo $data['rowCount'];?></div>
                                            </th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php
                                        $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                                        foreach($data['getAllReadyProduct'] as $item) {
                                            if($item['readyProductStatus'] == 'Y')
                                                $readyProductStatus  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                            else
                                                $readyProductStatus  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                            
                                            ?>
                                            <tr>
                                                
                                                <td>
                                                    <?php echo $item['productName'];?>
                                                </td>

                                                <td>
                                                    <?php echo $item['epName'];?>
                                                </td>

                                                <td>
                                                <?php echo $item['readyProductQty'];?>
                                                </td>

                                                <td width="250" class="last_li">
                                                    <div class="action_link">
                                                        <?php echo $item['currReadyProductQty'];?>
                                                    </div>
                                                </td>
                                                
                                                <td width="250" class="last_li">
                                                    <?php echo $item['currReadyProductPics'];?>
                                                </td>

                                                <td width="250" class="last_li">
                                                    <?php echo $item['readyPack'];?>
                                                </td>

                                                <!-- <td width="250" class="last_li">
                                                    <?php echo $item['unpackingProduct'];?>
                                                </td> -->

                                                <td width="250" class="last_li">
                                                    <?php echo $item['readyBox'];?>
                                                </td>
                                                <!-- 
                                                <td width="250" class="last_li">
                                                    <?php echo $item['unboxPack'];?>
                                                </td> -->

                                                <!-- 
                                                <td width="250" class="last_li">
                                                    <div class="action_link">
                                                        <?php echo $readyProductStatus;?>
                                                    </div>
                                                </td>
                                                 -->

                                                <td width="250" class="last_li"></td>
                                            </tr>
                                            <?php
                                            $slNo++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php

                            }
                            else
                                echo '<div class="norecord text-center">No Record Present</div>';
                            ?>
                        </div>
                    </div>
                </div>

                <?php 
                /*if($data['allPackaging']) { ?>
                    <div class="card m-t-20">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-5 pull-right">
                                    <div class="last_li form-inline">
                                        <select name="multiAction" class="form-control">
                                            <option value="">Select</option>
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>  
                                        <input type="hidden" name="SourceForm" value="multiAction">
                                        <button type="submit" name="Save" value="Apply" class="btn btn-info m-l-10">Apply</button>
                                    </div>
                                </div>
                                <?php
                                if($data['pageList']){
                                    echo '<div class="col-sm-7">';
                                    echo '<div class="pagination">';
                                    echo '<p class="total">Page '.$data['page'].' of '.$data['totalPage'].'</p>';
                                    echo '<div>'.$data['pageList'].'</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php }
                */
                ?>
                
            </form>
        </div>
        
        <div class="col-sm-4">
            <div class="card" style="padding: 0px !important;">
                <div class="card-header"><i class="fa fa-eraser" aria-hidden="true"></i> <?php echo ($IdToEdit != '' ? 'Update Ready Package <span class="pull-right"><a href="./index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'">Add New Ready Package</a></span>' : 'New Ready Package');?></div>
                <div class="card-body">
                    <div class="col m-t-20 m-b-20">
                        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">

                            <div class="form-group">
                                <label>Product *</label>
                                <select class="form-control generate_unique_name selectpiker" name="productId" id="productId">
                                    <option value="">Select Product</option>
                                        <?php 
                                            foreach($data['productList'] as $key=>$val){
                                                
                                                if(count($val['childType']) > 0){
                                                    ?>
                                                        <optgroup label="<?php echo $val['categoryName']; ?>">
                                                            <?php 
                                                            foreach($val['childType'] as $productName){
                                                                ?>
                                                            <option value="<?php echo $productName['productId']; ?>" <?php if($productId == $productName['productId']) { echo "selected"; } ?> data-pname="<?php echo $productName['productName']; ?>"><?php echo $productName['productName']; ?>
                                                            </option>
        
                                                                <?php
                                                            }
                                                            ?>
                                                        </optgroup>
                                                    <?php
                                                }
                                                
                                            }
                                        
                                        
                                        ?>   
                                </select>
                            </div>

                            <div class="form-group">
                                <label>End Product *</label>
                                <select class="form-control" name="endProductId" id="endProductId">
                                    <option value="">Select End Product</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Total Ready Products Qty (KG) *</label>
                                <input type="text" name="readyProductQty" id="readyProductQty" value="<?php echo $readyProductQty; ?>" class="form-control" autocomplete="off" maxlength="255" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" readonly> 
                            </div>

                            <div class="form-group">
                                <label>Ready Products Qty (KG) *</label>
                                <input type="text" name="currReadyProductQty" id="currReadyProductQty" value="<?php echo $currReadyProductQty; ?>" class="form-control" autocomplete="off" maxlength="255" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"> 
                            </div>

                            <div class="form-group">
                                <label>Packing Type *</label>
                                <select class="form-control" name="packingType" id="packingType">
                                    <option value="">Select Packing Type</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Ready Products Qty (Pics) *</label>
                                <input type="text" name="currReadyProductPics" id="currReadyProductPics" value="<?php echo $currReadyProductPics; ?>" class="form-control" autocomplete="off" maxlength="255" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" readonly> 
                            </div>

                            <div class="form-group">
                                <label>Ready Pack *</label>
                                <input type="text" name="readyPack" id="readyPack" value="<?php echo $readyPack; ?>" class="form-control" autocomplete="off" maxlength="255" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" readonly> 
                            </div>

                            <div class="form-group">
                                <label>Unpacking Product (Qty) *</label>
                                <input type="text" name="unpackingProduct" id="unpackingProduct" value="<?php echo $unpackingProduct; ?>" class="form-control" autocomplete="off" maxlength="255" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" readonly> 
                            </div>

                            <div class="form-group">
                                <label>Ready Box *</label>
                                <input type="text" name="readyBox" id="readyBox" value="<?php echo $readyBox; ?>" class="form-control" autocomplete="off" maxlength="255" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" readonly> 
                            </div>

                            <div class="form-group">
                                <label>Unbox Pack (Qty) *</label>
                                <input type="text" name="unboxPack" id="unboxPack" value="<?php echo $unboxPack; ?>" class="form-control" autocomplete="off" maxlength="255" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" readonly> 
                            </div>

                            <div class="form-group" style="display: flex;justify-content: space-between;align-items: center;">
                                <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                                <input type="hidden" name="SourceForm" value="addEditStock" />
                                <button type="submit" name="Save" value="Save" class="btn btn-info login_btn mb-2"><?php echo ($IdToEdit != '' ? 'UPDATE' : 'ADD'); ?></button>

                                <?php 
                                if($IdToEdit != ''){
                                    ?>
                                <div class="trash_bx_ico">
                                    <input type="button" data-moduleId="<?php echo $this->_request['moduleId']; ?>" data-id="<?php echo $IdToEdit; ?>"  class="btn btn-danger login_btn mb-2 delete_packaging" ><i class="fa fa-trash trash_position" aria-hidden="true" data-moduleId="<?php echo $this->_request['moduleId']; ?>" data-id="<?php echo $IdToEdit; ?>"></i>
                                </div>
                                    <?php
                                }
                                ?>
                            </div>

                            <?php 
                                if($data['act']['message']) {
                                    echo ($data['act']['type'] == 1)? '<div class="errmsg m-t-4"><div class="alert alert-success"><i class="fa fa-check-square-o">&nbsp;</i>'.$data['act']['message'].'</div></div>':'<div class="errmsg m-t-4"><div class="alert alert-danger"><i class="fa fa-times">&nbsp;</i>'.$data['act']['message'].'</div></div>';
                                }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

var ajx_url = "./index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>";
    $(function(){
        $(document).on("change", "#productId", function() {
            var productId = $(this).val();
            var endProductId = $('#endProductId').empty();
            var packingType = $('#packingType').empty();

            $('#readyProductQty').val('');
            $('#currReadyProductQty').val('');
            $('#currReadyProductPics').val('');
            $('#readyPack').val('');
            $('#readyBox').val('');
            $('#unpackingProduct').val('');
            $('#unboxPack').val('');

            if(parseInt(productId) > 0) {
                $.ajax({
                    type : 'POST',
                    url : ajx_url,
                    data : "productId="+productId+"&ajx_action=getEndProduct",
                    success: function(response) {

                        packingType.append( '<option value="">Select Packing Type</option>' );
                        endProductId.append( '<option value="">Select End Product</option>' );

                        if(response.endProducts != null) {
                            $.each(response.endProducts, function(i,item) {
                                endProductId.append( '<option value="'
                                        + item.epId
                                        + '" data-shrtname="'
                                        + item.epName +'">'
                                        + item.epName
                                        + '</option>' );
                            });
                        }
                    }
                });
            }
        });

        $(document).on("change", "#endProductId", function() {

            var productId = $('#productId').val();
            var endProductId = $(this).val();
            var packingType = $('#packingType').empty();

            $('#readyProductQty').val('');
            $('#currReadyProductQty').val('');
            $('#currReadyProductPics').val('');
            $('#readyPack').val('');
            $('#readyBox').val('');
            $('#unpackingProduct').val('');
            $('#unboxPack').val('');

            if(parseInt(productId) > 0 && parseInt(endProductId) > 0) {
                $.ajax({
                    type : 'POST',
                    url : ajx_url,
                    data : "productId="+productId+"&endProductId="+endProductId+"&ajx_action=getUnpackingProductQty",
                    success: function(response) {
                        $('#readyProductQty').val(response.noReadyProduct); 

                        packingType.append( '<option value="">Select Packing Type</option>' );
                        if(response.packagingType != null) {
                            $.each(response.packagingType, function(i,item) {
                                packingType.append( '<option value="'
                                        + item.packagingId
                                        + '">'
                                        + item.packagingName
                                        + '</option>' );
                            });
                        }

                    }
                });
            }
        });

        $(document).on("input", "#currReadyProductQty", function() {
            var currReadyProductQty = $('#currReadyProductQty').val();
            var readyProductQty = $('#readyProductQty').val();

            if(Number(currReadyProductQty) > Number(readyProductQty))
            {
                swal("Error", "You cannot enter greater than ready product qunatity", "error");
                $('#currReadyProductQty').val(0);
            }
        });

        $(document).on("change", "#packingType", function() {
            var productId = $('#productId').val();
            var endProductId = $('#endProductId').val();
            var currReadyProductQty = $('#currReadyProductQty').val();
            var packingType = $(this).val();

            if(parseInt(productId) > 0 && parseInt(endProductId) > 0 && parseInt(currReadyProductQty) > 0 && parseInt(packingType) > 0) {
                $.ajax({
                    type : 'POST',
                    url : ajx_url,
                    data : "productId="+productId+"&endProductId="+endProductId+"&currReadyProductQty="+currReadyProductQty+"&packingType="+packingType+"&ajx_action=getProductPackingDetails",
                    success: function(response) {

                        $('#currReadyProductPics').val(response.productPics); 
                        $('#readyPack').val(response.packCount);
                        $('#readyBox').val(response.boxCount);  
                        $('#unpackingProduct').val(response.unPackProduct);  
                        $('#unboxPack').val(response.unBoxPack);  
                    }
                });
            }
            else
            {
                swal("Error", "Please enter ready product quantity", "error");
                $('#packingType').prop('selectedIndex',0);
            }

        });

    });
</script>