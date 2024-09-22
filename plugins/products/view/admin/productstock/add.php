<?php defined('BASE') OR exit('No direct script access allowed.');
?>
<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>

    <form name="modifycontent" action="" method="POST" enctype="multipart/form-data" id="form">
        <div class="row">
            <div class="col-sm-12 contentL">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <select name="sltVendor" id="sltVendor" class="form-control">
                                        <option value="">Select Vendor</option>
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
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <select name="sltSize" id="sltSize" class="form-control">
                                        <option value="">Select Size</option>
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
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <select name="sltGSM" id="sltGSM" class="form-control">
                                        <option value="">Select GSM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <select name="sltCategory" id="sltCategory" class="form-control">
                                        <option value="">Select Category</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <button type="button" name="Search" class="btn btn-info width-auto searchStock"><i class="fa fa-search"></i></button>
                                    <button type="button" name="Reset" class="btn btn-dark width-auto m-l-10"><i class="fa fa-refresh"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="product_list"></div>
                    </div>
                </div>
            </div>
            
        </div>
            
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" name="Back" value="Back" onclick="history.back(-1);" class="btn btn-default m-r-15">Back</button>
                        
                        <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                        <input type="hidden" name="SourceForm" value="addEditHSNCode" />
                        <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                        <button type="button" name="Cancel" value="Close" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-l-15">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    var ajx_url = "./index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>";
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
                                        + item.gsmName + ' GSM'
                                        + '</option>' );
                            });
                        }
                    }
                });
            }
        });

        $(document).on("click", ".searchStock", function(){
            var sltVendor      = $("#sltVendor").val(), 
                sizeId         = $("#sltSize").val(),
                sltGSM         = $("#sltGSM").val(),
                sltCategory    = $("#sltCategory").val();
            
            if(parseInt(sltVendor) > 0 && parseInt(sizeId) > 0 && parseInt(sltGSM) > 0 && parseInt(sltCategory) > 0) {
                $.ajax({
                    type : 'POST',
                    url : ajx_url,
                    data : {'sltVendor':sltVendor, 'sizeId':sizeId, 'sltGSM':sltGSM, 'sltCategory':sltCategory, 'ajx_action':'searchStock'},
                    success: function(response) {
                        $(".product_list").html(response.htmlContent);
                        $('.numbersOnly').on("keyup", function () {
                            this.value = this.value.replace(/[^0-9\,.]/g, '');
                        });
                    }
                });
            } else {
                swal('Please select Vendor/size/GSM/Category');
                return false;
            }

        });
    });
</script>