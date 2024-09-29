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

?>

<div class="container-fluid">
    <div class="row">

        <div class="col-sm-6">
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
        </div>

        <div class="col-sm-8">
            <form action="" method="post">
                <div class="card p-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                            if($data['allPackaging']) {
                                ?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="40"><input class="selectall" name="toggle" type="checkbox"></th>
                                            <th>Product</th>
                                            <th style="width:400px;">Packaging</th>
                                            <th>Pack</th>
                                            <th>Box</th>

                                            <th>Status</th>
                                            <!-- <th colspan="2">Paper Size</th> -->
                                            <th width="500">
                                                <div class="alert alert-success font-weight-bold">Records Found: <?php echo $data['rowCount'];?></div>
                                            </th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php
                                        $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                                        foreach($data['allPackaging'] as $item) {
                                            if($item['packagingStatus'] == 'Y')
                                                $packagingStatus  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                            else
                                                $packagingStatus  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                            
                                            ?>
                                            <tr id="<?php echo 'recordsArray_'.$item['packagingId'];?>">
                                                <td width="40">
                                                    <input type="checkbox" name="selectMulti[]" value="<?php echo $item['packagingId'];?>" class="case" />
                                                </td>

                                                <td width="250" class="last_li">
                                                    <?php echo $item['productName'];?>
                                                </td>

                                                <td>
                                                    <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&editid=<?php echo $item['packagingId'];?>&moduleId=<?php echo $this->_request['moduleId'];?>">
                                                        <?php echo $item['packagingName']; ?>
                                                    </a>
                                                </td>

                                                <td width="250" class="last_li">
                                                    <div class="action_link">
                                                        <?php echo $item['packCount'];?>
                                                    </div>
                                                </td>
                                                
                                                <td width="250" class="last_li">
                                                    <?php echo $item['boxCount'];?>
                                                </td>

                                                <td width="250" class="last_li">
                                                    <div class="action_link">
                                                        <?php echo $packagingStatus;?>
                                                    </div>
                                                </td>

                                                <td width="250" class="last_li">
                                                </td>
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

                <?php if($data['allPackaging']) { ?>
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
                <?php }?>
                
            </form>
        </div>
        
        <div class="col-sm-4">
            <div class="card" style="padding: 0px !important;">
                <div class="card-header"><i class="fa fa-eraser" aria-hidden="true"></i> <?php echo ($IdToEdit != '' ? 'Update Packaging <span class="pull-right"><a href="./index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'">Add Packaging</a></span>' : 'New Packaging');?></div>
                <div class="card-body">
                    <div class="col m-t-20 m-b-20">
                        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">

                            <div class="form-group">
                                <label>Product *</label>
                                <select class="form-control generate_unique_name selectpiker" name="productId" id="productId">
                                    <option value="">Select Product</option>
                                        <?php 
                                        foreach($data['productList'] as $key=>$val){
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
                                        ?>   
                                </select>
                            </div>

                            <div class="form-group">
                                <label>End Product *</label>
                                <select class="form-control generate_unique_name" name="endProductId" id="endProductId">
                                    <option value="">Select End Product</option>
                                    <?php foreach($data['endProductList'] as $epl) { ?>
                                        <option value="<?php echo $epl['epId']; ?>" <?php if($endProductId == $epl['epId']) { echo "selected"; } ?> data-epname = "<?php echo $epl['epName']; ?>"><?php echo $epl['epName']; ?></option>
                                    <?php } ?>    
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Pack Count *</label>
                                <select class="form-control generate_unique_name" name="packCount" id="packCount">
                                    <option value="">Select Pack Count</option>
                                    <?php foreach($data['packCount'] as $epl) { ?>
                                        <option value="<?php echo $epl; ?>" <?php if($epl == 20 || $epl == $packCount) { echo "selected"; } ?>><?php echo $epl; ?></option>
                                    <?php } ?>    
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Packaging Name*</label>
                                <input type="text" name="packagingName" value="<?php echo $packagingName; ?>" class="form-control" placeholder="Packaging Name" id="packaging_name">
                            </div>

                            <div class="form-group">
                                <label>Box Count *</label>
                                <input type="number" name="boxCount" value="<?php echo $boxCount; ?>" class="form-control" placeholder="" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                            </div>

                            <div class="form-group" style="display: flex;justify-content: space-between;align-items: center;">
                                <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                                <input type="hidden" name="SourceForm" value="addEditPackaging" />
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

$(document).ready(function(){

    // single product delete
    $(document).on('click','.delete_packaging, .trash_position', function(){
        var dataId = $(this).data('id');
        var moduleid = $(this).attr('data-moduleId');
        if(dataId != 0){
            swal({
                title: "Are You sure",
                text: "want to delete it!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it !!",
                closeOnConfirm: true
            }, function(isConfirm){
                if(!isConfirm) return;
                $.ajax({
                type: 'POST',
                url: ajx_url,
                data: "id=" + dataId + "&moduleId=" + moduleid + "&ajx_action=singleDeletePackaging",
                success: function(response) {
                    if(response.type == 1){
                        toster(response.type, response.message);
                        setTimeout(() => {
                            location.reload(true);
                        }, 2000);
                    }else{
                        toster(response.type, response.message);
                    }
                }
            });
        });
        }else{
            toster(4, "Id is empty");
        }
    });

    // generate unique name
    $(document).on('change', '.generate_unique_name', function(){
        var productName = $('#productId option:selected').attr('data-pname');
        var endproductName = $('#endProductId option:selected').attr('data-epname');
        var packCount = $('#packCount').val();
        // $('#packaging_name').val('');
        if(typeof productName != 'undefined' && typeof endproductName != 'undefined' && packCount != ''){
            var packagingVal = productName + "-" + endproductName.toLowerCase() + "-" + packCount;
            $('#packaging_name').val(packagingVal);
        }else{
            $('#packaging_name').val('');
        }
    });
});
</script>
