<?php
defined('BASE') OR exit('No direct script access allowed.');

//showArray($data['product']);

if($data['product']) {
    $IdToEdit                       = $data['product']['productId'];
    $sltSize                        = $data['product']['sizeId'];
    $sltGSM                         = $data['product']['gsmId'];
    $sltType                        = $data['product']['typeId'];
    $pName                          = $data['product']['productName'];
    $piecesPerKg                    = $data['product']['piecesPerKg'];
    $stockAlertQty                  = $data['product']['stockAlertQty'];
	
	$qrystrPermalink			   = 'productId != '.$IdToEdit;
}
else {

    $IdToEdit                       = $this->_request['productId'];
    $sltSize                        = $this->_request['sltSize'];
    $sltGSM                         = $this->_request['sltGSM'];
    $sltType                        = $this->_request['sltType'];
    $pName                          = $this->_request['pName'];
    $piecesPerKg                    = $this->_request['piecesPerKg'];
    $stockAlertQty                  = $this->_request['stockAlertQty'];

	$qrystrPermalink			    = 1;
}
//showArray($data['productSize']);
?>
<div class="container-fluid">
    <?php
    // if($data['act']['message'])
    //     echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>

    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <form name="searchForm" action="" method="post">
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" name="searchText" value="<?php echo $this->session->read('searchText');?>" placeholder="Search" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <select name="searchStatus" class="form-control">
                                    <option value="">Status</option>
                                    <option value="Y" <?php if ($this->session->read('searchStatus') == 'Y') echo 'selected';?>>Active</option>
                                    <option value="N" <?php if ($this->session->read('searchStatus') == 'N') echo 'selected';?>>Inactive</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="searchSize" id="searchSize" class="form-control">
                                    <option value="">Select Size</option>
                                    <?php 
                                    if(is_array($data['productSize']) && count($data['productSize']) > 0) {
                                        foreach($data['productSize'] as $item) {
                                            echo '<option value="'.$item['sizeId'].'">'.$item['sizeName'].'"</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <select name="searchGSM" id="searchGSM" class="form-control">
                                    <option value="">Select GSM</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-right: 8px !important;">
                                <select name="searchType" id="searchType" class="form-control">
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="Search" class="btn btn-info width-auto"><i class="fa fa-search"></i></button>
                                <button type="submit" name="Reset" class="btn btn-dark width-auto m-l-10"><i class="fa fa-refresh"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <form action="" method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card p-0">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <?php
                                        if($data['products']) {
                                            //showArray($data['products']);
                                            ?>
                                            <div class="alert alert-success">Records Found: <?php echo $data['rowCount'];?></div>
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width:250px;">Category</th>
                                                        <th>Product Name</th>
                                                        <th>Size</th>
                                                        <th>GSM</th>
                                                        <th>Stock Alert Qty</th>
                                                        <th>pcs per kg</th>
                                                    </tr>
                                                </thead>
                                                <?php $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;  ?>
                                                <tbody class="swap">
                                                    <?php foreach($data['products'] as $item) { if(!empty($item['categoryProduct']) || !empty($item['subCat'])) { ?>
                                                    <tr>
                                                        <td colspan="7" style="text-align: left;"><strong><?php echo $item['categoryName']; ?></strong></td></td>
                                                    </tr>
                                                        <?php foreach($item['subCat'] as $citem) { if(!empty($citem['categoryProduct'])) { ?>     
                                                        <tr>
                                                        <td rowspan="<?php echo count($citem['categoryProduct']); ?>"><?php echo $citem['categoryName']; ?></td>
                                                            <?php
                                                            $count = 1;
                                                            foreach($citem['categoryProduct'] as $citemData) { ?>
                                                                <td><?php echo $citemData['productName']; ?></td>
                                                                <td><?php echo $citemData['sizeName']; ?></td>
                                                                <td><?php echo $citemData['gsmName']; ?></td>
                                                                <td><?php echo $citemData['stockAlertQty']; ?> kgs</td>
                                                                <td><?php echo $citemData['piecesPerKg']; ?></td>
                                                            
                                                            <?php if($count != count($citem['categoryProduct'])) echo '</tr><tr>'; else echo '</tr>'; ?>

                                                            <?php $count++; } ?>    
                                                        <?php } } ?> 

                                                        <?php if(!empty($item['categoryProduct'])) { ?>
                                                        <tr>  
                                                        <td rowspan="<?php echo count($item['categoryProduct']); ?>"></td>        
                                                        <?php foreach($item['categoryProduct'] as $citemData) { ?>
                                                            <td><?php echo $citemData['productName']; ?></td>
                                                            <td><?php echo $citemData['sizeName']; ?></td>
                                                            <td><?php echo $citemData['gsmName']; ?></td>
                                                            <td><?php echo $citemData['stockAlertQty']; ?> kgs</td>
                                                            <td><?php echo $citemData['piecesPerKg']; ?></td>
                                                            </tr><tr>
                                                    <?php } } } ?>

                                                </tbody>
                                              <?php } ?>              
                                            </table>
                                            <?php
                                        }
                                        else
                                            echo '<div class="norecord text-center">No Record Present</div>';
                                        ?>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <?php if($data['products']) { ?>
                                <div class="card m-t-20">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-5 pull-right">
                                                <div class="last_li form-inline">
                                                    <select name="multiAction" class="form-control multi_action">
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
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card" style="padding: 0px !important;">
                <div class="card-header"><i class="fa fa-product-hunt" aria-hidden="true"></i> <?php echo ($IdToEdit != '' ? 'Update Product <span class="pull-right"><a href="./index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'">Add Product</a></span>' : 'New Product');?></div>
                <div class="card-body">
                    <div class="col m-t-20 m-b-20">
                        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">

                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select name="sltSize" id="sltSizealt" class="form-control">
                                            <option value="">Select Size</option>
                                            <?php 
                                            if(is_array($data['productSize']) && count($data['productSize']) > 0) {
                                                foreach($data['productSize'] as $item) {
                                                    echo '<option value="'.$item['sizeId'].'" data-sltsize="'.$item['sizeName'].'" '.($item['sizeId'] == $sltSize ? 'selected' : '').'>'.$item['sizeName'].'"</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <select name="sltGSM" id="sltGSM" class="form-control">
                                        <option value="">Select GSM</option>
                                            <?php 
                                            if(is_array($data['productGSM']) && count($data['productGSM']) > 0) {
                                                foreach($data['productGSM'] as $item) {
                                                    echo '<option value="'.$item['gsmId'].'" data-gsm="'.$item['gsmName'].'" '.($item['gsmId'] == $sltGSM ? 'selected' : '').'>'.$item['gsmName'].'</option>';
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <select name="sltType" id="sltType" class="form-control">
                                        <option value="">Select Category</option>
                                        <?php 
                                            if(is_array($data['productType']) && count($data['productType']) > 0) {
                                                foreach($data['productType'] as $item) { 

                                                    if(!empty($item['childType'])){
                                        ?>
                                                        <optgroup label="<?php echo $item['categoryName']; ?>">
                                                            <?php foreach($item['childType'] as $citem) { ?>
                                                                <option value="<?php echo $citem['categoryId']; ?>" data-ptype="<?php echo $citem['categoryName']; ?>" <?php if($citem['categoryId'] == $sltType) { echo "selected"; } ?>><?php echo $citem['categoryName']; ?></option>
                                                            <?php } ?>    
                                                        </optgroup>
                                                   <?php } else {  ?>
                                                        <option value="<?php echo $item['categoryId']; ?>" data-ptype="<?php echo $item['categoryName']; ?>" <?php if($item['categoryId'] == $sltType ) { echo "selected"; } ?>><?php echo $item['categoryName']; ?></option>
                                                    <?php
                                                   }
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <input type="text" name="pName" id="pName" value="<?php echo $pName; ?>" class="form-control" placeholder="Product Name" autocomplete="off" maxlength="255">
                                </div>
                            </div>   

                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="piecesPerKg" id="piecesPerKg" value="<?php echo $piecesPerKg; ?>" class="form-control" autocomplete="off" maxlength="255" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"> <span>pcs per kg</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="stockAlertQty" id="stockAlertQty" value="<?php echo $stockAlertQty; ?>" class="form-control" placeholder="Stock Alert Quantity" autocomplete="off" maxlength="255" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"> <span>kg</span>
                                    </div>
                                </div>

                            </div>
                            
                            <div class="form-group">
                                <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                                <input type="hidden" name="SourceForm" value="addEditProduct" />
                                <button type="submit" name="Save" value="Save" class="btn btn-info login_btn mb-2"><?php echo ($IdToEdit != '' ? 'UPDATE' : 'ADD'); ?></button>
                                <?php 
                                if($data['act']['message']) {
                                    echo ($data['act']['type'] == 1)? '<div class="errmsg m-t-4"><div class="alert alert-success"><i class="fa fa-check-square-o">&nbsp;</i>'.$data['act']['message'].'</div></div>':'<div class="errmsg m-t-4"><div class="alert alert-danger"><i class="fa fa-times">&nbsp;</i>'.$data['act']['message'].'</div></div>';
                                }
                                ?>
                            </div>
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

        $(document).on("change", "#sltSize", function() {
            var sizeId = $(this).val();
            var sltCategory = $('#sltType').empty();
            var sltGSM = $('#sltGSM').empty();
            if(parseInt(sizeId) > 0) {
                $.ajax({
                    type : 'POST',
                    url : ajx_url,
                    data : "sizeId="+sizeId+"&ajx_action=paperCategories",
                    success: function(response) {
                        sltCategory.append( '<option value="">Select Category</option>' );
                        if(response.categories != null) {
                            $.each(response.categories, function(i,item) {
                                sltCategory.append( '<option value="'
                                        + item.typeId
                                        + '" data-shrtname="'
                                        + item.shortName +'">'
                                        + item.typeName
                                        + '</option>' );
                            });
                        }

                        sltGSM.append( '<option value="">Select GSM</option>' );
                        if(response.gsmList != null) {
                            $.each(response.gsmList, function(i,item) {
                                sltGSM.append( '<option value="'
                                        + item.gsmId
                                        + '" data-name="'
                                        +item.gsmName+'">'
                                        + item.gsmName
                                        + '</option>' );
                            });
                        }
                    }
                });
            }
        });

        $(document).on("change", "#sltSizealt", function(){

            selectedGSM = $("#sltGSM").find(':selected').attr('data-gsm'), 
            selectedSize = $("#sltSizealt").find(':selected').attr('data-sltsize'),
            selectedtype = $('#sltType').find(':selected').attr('data-ptype');

            if(selectedGSM !="" && selectedSize !="" && selectedtype !="")
            {
                $("#pName").val(selectedGSM+selectedtype.replace(/\s/g, "")+selectedSize);  
            }
            else
            {
                $("#pName").val('');  
            }
        });

        $(document).on("change", "#sltGSM", function(){

            selectedGSM = $("#sltGSM").find(':selected').attr('data-gsm'), 
            selectedSize = $("#sltSizealt").find(':selected').attr('data-sltsize'),
            selectedtype = $('#sltType').find(':selected').attr('data-ptype');

            if(selectedGSM !="" && selectedSize !="" && selectedtype !="")
            {
                $("#pName").val(selectedGSM+selectedtype.replace(/\s/g, "")+selectedSize);  
            }
            else
            {
                $("#pName").val('');  
            }

        });

        $(document).on("change", "#sltType", function(){

        selectedGSM = $("#sltGSM").find(':selected').attr('data-gsm'), 
        selectedSize = $("#sltSizealt").find(':selected').attr('data-sltsize'),
        selectedtype = $('#sltType').find(':selected').attr('data-ptype');

        if(selectedGSM !="" && selectedSize !="" && selectedtype !="")
        {
            $("#pName").val(selectedGSM+selectedtype.replace(/\s/g, "")+selectedSize);  
        }
        else
        {
            $("#pName").val('');  
        }

        });

        // $(document).on("change", "#sltType", function(){
        //     var categoryId = $(this).val(), 

        //         selectedGSM = $("#sltGSM").find(':selected').attr('data-gsm'), 
        //         selectedSize = $("#sltSizealt").find(':selected').attr('data-sltsize'),
        //         selectedtype = $('#sltType').find(':selected').attr('data-ptype');

        //     if(parseInt(categoryId) > 0) {

        //         console.log(selectedSize);

        //         if(selectedGSM !="" && selectedSize !="" && selectedtype !="")
        //         {
        //             $("#pName").val(selectedGSM+selectedtype.replace(/\s/g, "")+selectedSize);  
        //         }
        //         else
        //         {
        //             $("#pName").val('');  
        //         }


        //         $.ajax({
        //             type : 'POST',
        //             url : ajx_url,
        //             data : "categoryId="+categoryId+"&ajx_action=paperSubCategories",
        //             success: function(response) {
        //                 if(response.subCategoryHTML != null) {
        //                     $(".addOnSubCategory").html(response.subCategoryHTML);
        //                 }
        //             }
        //         });

        //     } else {
        //         $(".addOnSubCategory").html('');
        //     }
        // });

        $(document).on("change", "#sltPiecesPerBag", function(){
            var bagItems = $(this).val();
            if(parseInt(bagItems) > 0) {
                $(".weightHelpTxt").html('Weight for '+bagItems+' pcs');
            } else {
                $(".weightHelpTxt").html('');
                return false;
            }
        });

        $(document).on("change", "#searchSize", function() {
            var sizeId = $(this).val();
            var sltCategory = $('#searchType').empty();
            var sltGSM = $('#searchGSM').empty();
            if(parseInt(sizeId) > 0) {
                $.ajax({
                    type : 'POST',
                    url : ajx_url,
                    data : "sizeId="+sizeId+"&ajx_action=paperCategories",
                    success: function(response) {
                        sltCategory.append( '<option value="">Select Category</option>' );
                        if(response.categories != null) {
                            $.each(response.categories, function(i,item) {
                                sltCategory.append( '<option value="'
                                        + item.typeId
                                        + '">'
                                        + item.typeName
                                        + '</option>' );
                            });
                        }

                        sltGSM.append( '<option value="">Select GSM</option>' );
                        if(response.gsmList != null) {
                            $.each(response.gsmList, function(i,item) {
                                sltGSM.append( '<option value="'
                                        + item.gsmId
                                        + '">'
                                        + item.gsmName
                                        + '</option>' );
                            });
                        }
                    }
                });
            }
        });
    });
</script>