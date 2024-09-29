<?php defined('BASE') OR exit('No direct script access allowed.'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-7">
            <div class="card">
                <div class="card-body">
                    <form name="searchForm" action="" method="post">
                        <div class="form-inline">

                            <div class="form-group">
                                <input type="text" name="searchText" value="<?php echo $this->session->read('searchText');?>" placeholder="Product Name" class="form-control">
                            </div>

                            <div class="form-group">
                                <select name="sltVendor" id="sltVendor" class="form-control">
                                    <option value="">Vendor</option>
                                    <?php 
                                    if(is_array($data['vendors']) && count($data['vendors']) > 0) {
                                        foreach($data['vendors'] as $item) {
                                            echo '<option value="'.$item['vendorId'].'" data-vid="'.$item['vendorId'].'" '.($item['vendorId'] == $this->session->read('sltVendor') ? 'selected' : '').'>'.$item['vendorName'].'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="propurchaseDate" id="propurchaseDate" class="form-control">
                                   <option value="">Select Purchase Date</option>
                                   <?php if(!empty($data['purchaseDateList'])) { foreach($data['purchaseDateList'] as $purchaseDateList) { $cnvtDate = date('d-m-Y', strtotime($purchaseDateList['purchaseDate']));
                                    echo '<option value="'.$cnvtDate.'" '.($cnvtDate == $this->session->read('propurchaseDate') ? 'selected' : '').'>'.$cnvtDate.'</option>';
                                    } } ?> 
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
            <div class="item-display">
                <form action="" method="post">
                    <div class="card p-0">
                        <div class="card-body">
                        <div class="table-responsive">
                                        <?php
                                        if($data['products']) {
                                            //showArray($data['products']);
                                            ?>
                                            <!-- <div class="alert alert-success">Records Found: <?php echo $data['rowCount'];?></div> -->
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width:250px;">Category</th>
                                                        <th>Product Name</th>
                                                        <th>Size</th>
                                                        <th>GSM</th>
                                                        <th>Stock</th>
                                                        <th>Latest Price</th>
                                                    </tr>
                                                </thead>
                                                <?php $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;  ?>
                                                <tbody>
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
                                                                <td><?php echo $citemData['stockQty']; ?></td>
                                                                <td><?php echo $citemData['latestPrice']; ?></td>
                                                            
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
                                                            <td><?php echo $citemData['stockQty']; ?></td>
                                                            <td><?php echo $citemData['latestPrice']; ?></td>
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
                </form>
            </div>

        </div>
        <div class="col-sm-5">
        <div class="card" style="padding: 0px !important;">
            <div class="card-header"><i class="fa fa-archive" aria-hidden="true"></i> <?php echo ($IdToEdit != '' ? 'Update Stock <span class="pull-right"><a href="./index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'">Add Stock</a></span>' : 'New Stock');?><?php echo ($data['htmlStockForm'] != '' ? '<span class="pull-right"><a href="javascript: void(0);" class="text-danger resetStockForm">Reset</a></span>' :'' )?></div>
                <div class="card-body">
                    <div class="col m-t-20 m-b-20">
                        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select name="sltVendor" id="sltVendor" class="form-control">
                                            <option value="">Vendor</option>
                                            <?php 
                                            if(is_array($data['vendors']) && count($data['vendors']) > 0) {
                                                foreach($data['vendors'] as $item) {
                                                    echo '<option value="'.$item['vendorId'].'" data-vid="'.$item['vendorId'].'" '.($item['vendorId'] == $vendorId ? 'selected' : '').'>'.$item['vendorName'].'</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" id="datepicker" value="<?php echo date('d-m-Y'); ?>" readonly="readonly" name="purchaseDate" class="date" placeholder="Select Date">
                                    </div>
                                </div>
                            </div>

                            <div class="product_list">
                                <?php if($data['htmlStockForm']) { echo $data['htmlStockForm']; } ?>
                            </div>

                            <div class="row mt-2">
                                <div class="col-sm-12 errMsg">
                                <?php 
                                if($data['act']['message']) {
                                    echo ($data['act']['type'] == 1)? '<div class="errmsg m-t-4"><div class="alert alert-success"><i class="fa fa-check-square-o">&nbsp;</i>'.$data['act']['message'].'</div></div>':'<div class="errmsg m-t-4"><div class="alert alert-danger"><i class="fa fa-times">&nbsp;</i>'.$data['act']['message'].'</div></div>';
                                }
                                ?>
                                </div>
                            </div>

                            <div class="row mb-2 addMore">
                                <div class="col-sm-12">
                                    <a href="javascript: void(0);" class="more-filter pull-right"><i class="fa fa-plus-circle">&nbsp;</i> Add more</a>
                                </div>
                            </div>

                            <div class="row mb-2 filter_block">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <select name="sltSize" id="sltSize" class="form-control">
                                            <option value="">Size</option>
                                            <?php 
                                                if(is_array($data['productSize']) && count($data['productSize']) > 0) {
                                                    foreach($data['productSize'] as $item) {
                                                        echo '<option value="'.$item['sizeId'].'" data-size="'.$item['sizeName'].'" '.($item['sizeId'] == $sltSize ? 'selected' : '').'>'.$item['sizeName'].'"</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <select name="sltGSM" id="sltGSM" class="form-control">
                                            <option value="">GSM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                    <select name="sltType" id="sltCategory" class="form-control">
                                        <option value="">Category</option>
                                    </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2 stockAction">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                                        <input type="hidden" name="SourceForm" value="addEditStock" />
                                        <button type="submit" name="Save" value="Save" class="btn btn-info login_btn"><?php echo ($IdToEdit != '' ? 'UPDATE STOCK' : 'ADD STOCK'); ?></button>
                                    </div>
                                </div>
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

    $(window).on('load', function(){
        console.log("Length: ", $('.product_list').children().length);
        if ($('.product_list').children().length > 0 ) {
            $(".addMore").show();
            $(".stockAction").show();
            $(".filter_block").hide();
        } else {
            $(".addMore").hide();
            $(".stockAction").hide();
            $(".filter_block").show();
        }
    });

    $(function(){

        $(document).on("change", "#sltSize", function() {
            var sizeId      = $(this).val();
            var sltCategory = $('#sltCategory').empty();
            var sltGSM      = $('#sltGSM').empty();
            if(parseInt(sizeId) > 0) {
                $.ajax({
                    type : 'POST',
                    url : ajx_url,
                    data : "sizeId="+sizeId+"&ajx_action=getattr",
                    success: function(response) {

                        sltCategory.append( '<option value="">Select Category</option>' );
                        sltGSM.append( '<option value="">Select GSM</option>' );

                        if(response.gsmList != null) {
                            $.each(response.gsmList, function(i,item) {
                                sltGSM.append( '<option value="'
                                        + item.gsmId
                                        + '">'
                                        + item.gsmName + ' GSM'
                                        + '</option>' );
                            });
                        }
                    }
                });
            }
        });

        $(document).on("change", "#sltGSM", function() {

            var sltGSM      = $(this).val();
            var sizeId      =  $("#sltSize").val();
            var sltCategory = $('#sltCategory').empty();

            if(parseInt(sizeId) > 0) {
                $.ajax({
                    type : 'POST',
                    url : ajx_url,
                    data : "sizeId="+sizeId+"&sltGSM="+sltGSM+"&ajx_action=getGmsattr",
                    success: function(response) {
                        //console.log(response.categories);

                        var catCount = response.categories;
                        sltCategory.append( '<option value="">Select Category</option>' );

                        var html = '';

                        for(var cat = 0; cat < catCount.length; cat++)
                        {
                            //var subCat = catCount[cat]['count'];

                            if(catCount[cat]['count'] > 0)
                            {

                                html += '<optgroup label="'+catCount[cat]['categoryName']+'">';

                                    for(var subcat = 0; subcat < catCount[cat]['count']; subcat++){

                                        html += '<option value="'+catCount[cat]['childType'][subcat]['categoryId']+'">'+catCount[cat]['childType'][subcat]['categoryName']+'</option>';
                                        
                                    }

                                html += '</optgroup>';
                            }
                            else
                            {
                                html += '<option value="'+catCount[cat]['categoryId']+'">'+catCount[cat]['categoryName']+'</option>';
                            }
                        }

                        sltCategory.append(html);

                        // if(response.categories != null) {
                        //     // $.each(response.categories, function(i,item) {

                        //     //     // sltCategory.append( '<option value="'
                        //     //     //         + item.categoryId
                        //     //     //         + '">'
                        //     //     //         + item.categoryName
                        //     //     //         + '</option>' );

                        //     //     console.log(1);
                        //     // });

                        //     console.log(1);
                        // }

                    }
                });
            }
        });

        $(document).on("change", "#sltCategory", function(){

            var sizeId         = $("#sltSize").val(),
                sltGSM         = $("#sltGSM").val(),
                sltCategory    = $("#sltCategory").val();
            
            if(parseInt(sizeId) > 0 && parseInt(sltGSM) > 0 && parseInt(sltCategory) > 0) {
                $.ajax({
                    type : 'POST',
                    url : ajx_url,
                    data : {'sizeId':sizeId, 'sltGSM':sltGSM, 'sltCategory':sltCategory, 'ajx_action':'searchStock'},
                    success: function(response) {
                        $(".product_list").html(response.htmlContent);
                        $(".addMore").show();
                        $(".stockAction").show();
                        $(".errMsg").show();
                        $(".filter_block").hide();
                        $('.numbersOnly').on("keyup", function () {
                            this.value = this.value.replace(/[^0-9\,.]/g, '');
                        });
                        if(response.type == 1) {
                            $(".errMsg").html('<div class="errmsg m-t-4"><div class="alert alert-success"><i class="fa fa-check-square-o">&nbsp;</i>'+response.message+'</div></div>');
                        } else {
                            $(".errMsg").html('<div class="errmsg m-t-4"><div class="alert alert-danger"><i class="fa fa-times">&nbsp;</i>'+response.message+'</div></div>');
                        }
                        $('#sltSize').prop('selectedIndex', 0);
                        $('#sltCategory').empty().append( '<option value="">Select Category</option>' );
                        $('#sltGSM').empty().append( '<option value="">Select GSM</option>' );
                    }
                });
            } else {
                swal('Please select Size/GSM/Category');
                return false;
            }
        });

        $(document).on("click", ".more-filter", function() {
            $(".addMore").hide();
            $(".errMsg").hide();
            $(".filter_block").show();
        });

        $(document).on("click", ".resetStockForm", function() {
            $.ajax({
                type : 'POST',
                url : ajx_url,
                data : {'ajx_action':'removeStockForm'},
                success: function(response) {
                    if(response.type == 1) {
                        location.reload();
                        $(".filter_block").show();
                    }
                }
            });
        })

        $(document).on("change", "#sltVendor", function() {

            var vendor = $(this).val();

            $.ajax({
                type : 'POST',
                url : ajx_url,
                data : {'ajx_action':'getPurchaseDate', 'vendor' : vendor},
                success: function(response) {
                    $('#propurchaseDate').html('');
                    $('#propurchaseDate').append(response.htmlContent);
                }
            });
        })

    });
</script>