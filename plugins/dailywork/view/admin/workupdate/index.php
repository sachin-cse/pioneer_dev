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

$tomorrow_date = date("Y-m-d", strtotime($data['workingDate']."+ 1 day"));
$yesterday_date = date("Y-m-d", strtotime($data['workingDate']."- 1 day"));
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
                            <!-- <div class="form-group">
                                <input type="text" name="searchText" value="<?php echo $this->session->read('searchText');?>" placeholder="Search" class="form-control">
                            </div> -->

                            <!-- <div class="form-group">
                                <select name="searchStatus" class="form-control">
                                    <option value="">Status</option>
                                    <option value="Y" <?php if ($this->session->read('searchStatus') == 'Y') echo 'selected';?>>Active</option>
                                    <option value="N" <?php if ($this->session->read('searchStatus') == 'N') echo 'selected';?>>Inactive</option>
                                </select>
                            </div> -->

                            <div class="form-group">
                                <select name="searchSize" id="searchSize" class="form-control">
                                    <option value="">Size</option>
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
                                    <option value="">GSM</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-right: 8px !important;">
                                <select name="searchType" id="searchType" class="form-control">
                                    <option value="">Category</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="button" name="Search" class="btn btn-info width-auto search"><i
                                        class="fa fa-search"></i></button>
                                <button type="submit" name="Reset" class="btn btn-dark width-auto m-l-10"><i
                                        class="fa fa-refresh"></i></button>
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
  
                                    <ul class="button_wrap">
                                        <li><a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>&workdate=<?php echo $yesterday_date; ?>"
                                                class="dateChangeBtn">Previous</a></li>
                                        <li><?php echo date("jS M, Y", strtotime($this->_request['workdate']??date('Y-m-d',strtotime($data['getLastWorkingDate']['entryDate'])))); ?></li>

                                        <?php if($_GET['workdate'] !="") { ?>
                                        <li><a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>&workdate=<?php echo $tomorrow_date; ?>" class="dateChangeBtn">Next</a></li>
                                        <?php } ?>
                                    </ul>
                                                                
                                    <?php if($data['products']) { ?>
                                    <div class="table-responsive">
                                        <?php if($data['products']) { $totalQty = 0; ?>
                                        <table class="table table-hover">
                                            <thead>

                                                <tr>
                                                    <th style="width:250px;">Date</th>
                                                    <th>End Product</th>
                                                    <th>Category</th>
                                                    <th>Size</th>
                                                    <th>GSM</th>
                                                    <th>Qty (KG)</th>
                                                </tr>
                                            </thead>
                                            <?php $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;  ?>
                                            <tbody id="search_append">
                                                <?php
                                                $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                                                foreach($data['products'] as $item) {
                                                    
                                                    ?>
                                                <tr id="<?php echo 'recordsArray_'.$item['id'];?>">
                                                    <td class="edit_data"><?php echo date('jS M, Y', strtotime($item['workUpdateDate'])); ?>
                                                    <input type="hidden" value="<?php echo $item['id']; ?>" name="hidden_id">
                                                    </td>
                                                    <td><?php echo $item['epName'];?></td>
                                                    <td><?php echo $item['categoryName'];?></td>
                                                    <td><?php echo $item['sizeName'];?></td>
                                                    <td><?php echo $item['gsmName'];?></td>
                                                    <td><?php echo $item['addedQty'];?></td>

                                                </tr>
                                                <?php
                                                $totalQty = $totalQty + $item['addedQty'];
                                            $slNo++;
                                        }
                                        ?>
                                                <tr>
                                                    <td colspan="6">
                                                        <?php echo 'Total Quantity (KG) : <strong>'. $totalQty.'</strong>'; ?>
                                                    </td>
                                                </tr>
                                            </tbody>

                                        </table>
                                        <?php
                                        }
                                        else
                                            echo '<div class="norecord text-center">No Record Present</div>';
                                        ?>
                                    </div>

                                    <?php

                                        }
                                        else
                                            echo '<div class="norecord text-center">No Record Present</div>';
                                        ?>

                                </div>
                            </div>

                            <!-- <?php if($data['products']) { ?>
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
                            <?php }?> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-4">
            <span class="alert alert-success show_message d-none">
            </span>
            <div class="card" style="padding: 0px !important;">
                <div class="card-header"><i class="fa fa-archive" aria-hidden="true"></i>
                    <?php echo ($IdToEdit != '' ? 'Work Update<span class="pull-right"><a href="./index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'">ADD WORK UPDATE</a></span>' : 'Work Update');?><?php echo ($data['htmlStockForm'] != '' ? '<span class="pull-right"><a href="javascript: void(0);" class="text-danger resetStockForm">Reset</a></span>' :'' )?>
                </div>
                <div class="card-body">
                    <div class="col m-t-20 m-b-20">
                        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">

                            <input type="text" id="datepicker" value="<?php echo !empty($data['htmlStockForm']) ? date('d-m-Y') : '' ?>" readonly="readonly" name="workUpdateDate" class="date" placeholder="Select Date">


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
                                    <a href="javascript: void(0);" class="more-filter pull-right"><i
                                            class="fa fa-plus-circle">&nbsp;</i> Add more</a>
                                </div>
                            </div>

                            <div class="row mb-2 filter_block">
                                <div class="col-sm-6">
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
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select name="sltGSM" id="sltGSM" class="form-control">
                                            <option value="">GSM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <div class="form-group">
                                        <select name="sltType" id="sltCategory" class="form-control">
                                            <option value="">Category</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 mt-3">
                                    <div class="form-group">
                                        <select name="endProduct" id="endProduct" class="form-control">
                                            <option value="">Product Type</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2 stockAction">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                                        <input type="hidden" name="SourceForm" value="addEditStock" />
                                        <button type="submit" name="Save" value="Save"
                                            class="btn btn-info login_btn"><?php echo ($IdToEdit != '' ? 'UPDATE WORK' : 'ADD WORK UPDATE'); ?></button>
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
var ajx_url =
    "./index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>";

$(window).on('load', function() {
    console.log("Length: ", $('.product_list').children().length);
    if ($('.product_list').children().length > 0) {
        $(".addMore").show();
        $(".stockAction").show();
        $(".filter_block").hide();
    } else {
        $(".addMore").hide();
        $(".stockAction").hide();
        $(".filter_block").show();
    }
});

$(function() {
    $('#sltSize').prop('disabled', true);
    $('#sltGSM').prop('disabled', true);
    $('#sltCategory').prop('disabled', true);
    $('#endProduct').prop('disabled', true);

    $(document).on("change", "#sltSize", function() {
        var sizeId = $(this).val();
        var sltCategory = $('#sltCategory').empty();
        var sltGSM = $('#sltGSM').empty();
        if (parseInt(sizeId) > 0) {
            $.ajax({
                type: 'POST',
                url: ajx_url,
                data: "sizeId=" + sizeId + "&ajx_action=getattr",
                success: function(response) {

                    sltCategory.append('<option value="">Select Category</option>');
                    sltGSM.append('<option value="">Select GSM</option>');

                    if (response.gsmList != null) {
                        $.each(response.gsmList, function(i, item) {
                            sltGSM.append('<option value="' +
                                item.gsmId +
                                '">' +
                                item.gsmName + ' GSM' +
                                '</option>');
                        });
                    }
                }
            });
        }
    });

    $(document).on("change", "#sltGSM", function() {

        var sltGSM = $(this).val();
        var sizeId = $("#sltSize").val();
        var sltCategory = $('#sltCategory').empty();

        if (parseInt(sizeId) > 0) {
            $.ajax({
                type: 'POST',
                url: ajx_url,
                data: "sizeId=" + sizeId + "&sltGSM=" + sltGSM + "&ajx_action=getGmsattr",
                success: function(response) {
                    //console.log(response.categories);

                    var catCount = response.categories;
                    sltCategory.append('<option value="">Select Category</option>');

                    var html = '';

                    for (var cat = 0; cat < catCount.length; cat++) {
                        //var subCat = catCount[cat]['count'];

                        if (catCount[cat]['count'] > 0) {

                            html += '<optgroup label="' + catCount[cat]['categoryName'] +
                                '">';

                            for (var subcat = 0; subcat < catCount[cat][
                                'count']; subcat++) {

                                html += '<option value="' + catCount[cat]['childType'][
                                    subcat
                                ]['categoryId'] + '">' + catCount[cat]['childType'][
                                    subcat
                                ]['categoryName'] + '</option>';

                            }

                            html += '</optgroup>';
                        } else {
                            html += '<option value="' + catCount[cat]['categoryId'] + '">' +
                                catCount[cat]['categoryName'] + '</option>';
                        }
                    }

                    sltCategory.append(html);

                }
            });
        }
    });

    $(document).on("change", "#sltCategory", function() {

        var endProduct = $('#endProduct').empty();

        $.ajax({
            type: 'POST',
            url: ajx_url,
            data: "ajx_action=getEndType",
            success: function(response) {

                endProduct.append('<option value="">Product Type</option>');

                if (response.endTypeLists != null) {
                    $.each(response.endTypeLists, function(i, item) {
                        endProduct.append('<option value="' + item.epId +
                            '" data-endproduct="' + item.epName + '">' + item
                            .epName + '</option>');
                    });
                }
            }
        });
    });

    $(document).on("change", "#endProduct", function() {

        var sizeId = $("#sltSize").val(),
            sltGSM = $("#sltGSM").val(),
            sltCategory = $("#sltCategory").val(),
            endProductId = $("#endProduct").val(),
            chooseDate = $("#datepicker").val(),
            endProduct = $(this).find(':selected').data("endproduct");

        if (parseInt(sizeId) > 0 && parseInt(sltGSM) > 0 && parseInt(sltCategory) > 0 && parseInt(
                endProductId) > 0 && endProduct != "" && chooseDate != "") {
            $.ajax({
                type: 'POST',
                url: ajx_url,
                data: {
                    'sizeId': sizeId,
                    'sltGSM': sltGSM,
                    'sltCategory': sltCategory,
                    'endProductId': endProductId,
                    'endProduct': endProduct,
                    'choose_date': chooseDate,
                    'ajx_action': 'addTemWork'
                },
                success: function(response) {
                    $(".product_list").html(response.htmlContent);
                    $(".addMore").show();
                    $(".stockAction").show();
                    $(".errMsg").show();
                    $(".filter_block").hide();
                    $('.numbersOnly').on("keyup", function() {
                        this.value = this.value.replace(/[^0-9\,.]/g, '');
                    });
                    if (response.type == 1) {
                        $(".errMsg").html(
                            '<div class="errmsg m-t-4"><div class="alert alert-success"><i class="fa fa-check-square-o">&nbsp;</i>' +
                            response.message + '</div></div>');
                    } else {
                        $(".errMsg").html(
                            '<div class="errmsg m-t-4"><div class="alert alert-danger"><i class="fa fa-times">&nbsp;</i>' +
                            response.message + '</div></div>');
                    }
                    $('#sltSize').prop('selectedIndex', 0);
                    $('#sltCategory').empty().append(
                        '<option value="">Select Category</option>');
                    $('#sltGSM').empty().append('<option value="">Select GSM</option>');
                }
            });
        } else {
            swal('Please choose Date/Size/GSM/Category');
            return false;
        }
    });
    /*$(document).on('input', '#datepicker', function(){
        alert($('#datepicker').val());
    }); */
    // if($('#datepicker').val() != ''){
    // }else{
    //     $('#sltSize').prop('disabled', true);
    //     $('#sltGSM').prop('disabled', true);
    //     $('#sltCategory').prop('disabled', true);
    //     $('#endProduct').prop('disabled', true);
    // }

    $(document).on("click", ".more-filter", function() {
        $(".addMore").hide();
        $(".errMsg").hide();
        $(".filter_block").show();
    });

    // $(document).on("click", ".resetStockForm", function() {
    //     $.ajax({
    //         type: 'POST',
    //         url: ajx_url,
    //         data: {
    //             'ajx_action': 'removeStockForm'
    //         },
    //         success: function(response) {
    //             if (response.type == 1) {
    //                 location.reload();
    //                 $(".filter_block").show();
    //             }
    //         }
    //     });
    // })

    $(document).on("change", "#sltVendor", function() {

        var vendor = $(this).val();

        $.ajax({
            type: 'POST',
            url: ajx_url,
            data: {
                'ajx_action': 'getPurchaseDate',
                'vendor': vendor
            },
            success: function(response) {
                $('#propurchaseDate').html('');
                $('#propurchaseDate').append(response.htmlContent);
            }
        });
    })

    // set maximum date
    // var today = new Date();
    // var dd = today.getDate();
    // var mm = today.getMonth()+1;
    // var yyyy = today.getFullYear();

    // console.log(dd-mm-yyyy);

    // date picker
    $('#datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        maxDate: new Date(),
        onSelect: function(dateText, inst) {
            var date = $(this).val();
            if (date != '') {
                $('#sltSize').prop('disabled', false);
                $('#sltGSM').prop('disabled', false);
                $('#sltCategory').prop('disabled', false);
                $('#endProduct').prop('disabled', false);
                
            }

        }
    });

    $(document).on("blur", ".getStockAmountCheck", function() {

        var gsmId = $(this).data("gsmid");
        var sizeId = $(this).data("sizeid");
        var typeId = $(this).data("typeid");

        var qty = $(this).val();

        if (parseInt(gsmId) > 0 && parseInt(sizeId) > 0 && parseInt(typeId) > 0 && parseInt(qty) > 0) {

            $.ajax({
                type: 'POST',
                url: ajx_url,
                data: "gsmId=" + gsmId + "&sizeId= " + sizeId + "&typeId= " + typeId + " &qty= " + qty + " &ajx_action=getStockAmountCheck",
                success: function(response) {
                    if(response.type == 1)
                    {
                        swal("Error", response.message, "error");
                        $('.getStockAmountCheck').val(0);
                    }

                }
            });

        }

    });

    // for search purpose sachin - 2024-09-20
    $(document).on("change", "#searchSize", function() {
        var sizeId = $(this).val();
        var sltCategory = $('#searchCategory').empty();
        var sltGSM = $('#searchGSM').empty();
        if (parseInt(sizeId) > 0) {
            $.ajax({
                type: 'POST',
                url: ajx_url,
                data: "sizeId=" + sizeId + "&ajx_action=getattr",
                success: function(response) {

                    sltCategory.append('<option value="">Select Category</option>');
                    sltGSM.append('<option value="">Select GSM</option>');

                    if (response.gsmList != null) {
                        $.each(response.gsmList, function(i, item) {
                            sltGSM.append('<option value="' +
                                item.gsmId +
                                '">' +
                                item.gsmName + ' GSM' +
                                '</option>');
                        });
                    }
                }
            });
        }
    });

    $(document).on("change", "#searchGSM", function() {

        var sltGSM = $(this).val();
        var sizeId = $("#searchSize").val();
        var sltCategory = $('#searchType').empty();

        if (parseInt(sizeId) > 0) {
            $.ajax({
                type: 'POST',
                url: ajx_url,
                data: "sizeId=" + sizeId + "&sltGSM=" + sltGSM + "&ajx_action=getGmsattr",
                success: function(response) {
                    //console.log(response.categories);

                    var catCount = response.categories;
                    sltCategory.append('<option value="">Select Category</option>');

                    var html = '';

                    for (var cat = 0; cat < catCount.length; cat++) {
                        //var subCat = catCount[cat]['count'];

                        if (catCount[cat]['count'] > 0) {

                            html += '<optgroup label="' + catCount[cat]['categoryName'] +
                                '">';

                            for (var subcat = 0; subcat < catCount[cat][
                                'count']; subcat++) {

                                html += '<option value="' + catCount[cat]['childType'][
                                    subcat
                                ]['categoryId'] + '">' + catCount[cat]['childType'][
                                    subcat
                                ]['categoryName'] + '</option>';

                            }

                            html += '</optgroup>';
                        } else {
                            html += '<option value="' + catCount[cat]['categoryId'] + '">' +
                                catCount[cat]['categoryName'] + '</option>';
                        }
                    }

                    sltCategory.append(html);

                }
            });
        }
    });


    // search by thsese value
    $(document).on('click', '.search', function(){
        var searchSize = $('#searchSize').find(':selected').val();
        var searchGSM = $('#searchGSM').find(':selected').val();
        var searchCategory = $('#searchType').find(':selected').val();
        
        if(searchSize === '' && searchGSM === '' && searchCategory === ''){
            sAlert('warning', 'Please Select at least one option', false);
        }else{
            var html = $('#search_append').html('');
            $.ajax({
                url : ajx_url,
                type : 'POST',
                data : {'ajx_action':'liveSearch',
                    'searchSize':searchSize,
                    'searchGSM':searchGSM,
                    'searchCategory':searchCategory
                },
                success: function (response) {
                    var qty = 0;
                    console.log(response);
                    if(response.length != 0){
                        $.each(response, function (key, val) {
                        $('#search_append').append(`<tr>
                            <td>${val.workUpdateDate}</td>
                            <td>${val.epName}</td>
                            <td>${val.categoryName}</td>
                            <td>${val.sizeName}</td>
                            <td>${val.gsmName}</td>
                            <td>${val.addedQty}</td>
                        </tr>`);
                        qty = parseInt(qty) + parseInt(val.addedQty);
                    });

                    $('#search_append').append(`<tr>
                            <td colspan="6">
                            Total Quantity (KG) : <strong>${qty}</strong>    
                            </td>
                    </tr>`);
                    }else{
                        $('#search_append').append(`<tr><td colspan="3"><div class="norecord">No Records Present</td></tr>`)
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    swal("Error deleting!", "Please try again", thrownError);
                }
            });
        }
    });

    $(document).on("click", ".resetStockForm", function() {
            // $.ajax({
            //     type : 'POST',
            //     url : ajx_url,
            //     data : {'ajx_action':'removeStockForm'},
            //     success: function(response) {
            //         if(response.type == 1) {
            //             location.reload();
            //             $(".filter_block").show();
            //         }
            //     }
            // });
        $.ajax({
            url : ajx_url,
            type : 'POST',
            data : {'ajx_action':'removeStockForm'},
            success: function (response) {
                if(response.type == 1){
                    $('.show_message').removeClass('d-none').text(response.message);
                    setTimeout(function(){
                        location.reload();
                        $(".filter_block").show();
                    },1500)
                    // location.reload();
                }else{
                    toster(2, response.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                swal("Error deleting!", "Please try again", thrownError);
            }
        });
           
    });

    // edit data by ajax
    $(document).on('click', '.edit_data', function(){
        var dataId = $(this).children('input').val();
        if(typeof dataId != 'undefined'){
            $.ajax({
                type: 'POST',
                url: ajx_url,
                data: {id:dataId,'ajx_action':'editWorkUpdate'},
                success:function(response){
                    console.log(response.workUpdate.id);
                }
            });
        }
    });

});
</script>