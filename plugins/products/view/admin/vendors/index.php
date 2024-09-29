<?php
defined('BASE') OR exit('No direct script access allowed.');
if(is_array($data['vendor']) && count($data['vendor']) > 0) {
    $IdToEdit                   = $data['vendor']['vendorId'];
    $vendorName                 = $data['vendor']['vendorName'];
    $vendorPhone                = $data['vendor']['vendorPhone'];
    $vendorEmail                = $data['vendor']['vendorEmail'];
    $gst                        = $data['vendor']['gst'];
    $storeName                  = $data['vendor']['storeName'];
    $vendorAddress              = $data['vendor']['vendorAddress'];
} else {
    $IdToEdit                   = $this->_request['vendorId'];
    $vendorName                 = $this->_request['vendorName'];
    $vendorPhone                = $this->_request['vendorPhone'];
    $vendorEmail                = $this->_request['vendorEmail'];
    $storeName                  = $this->_request['storeName'];
    $gst                        = $this->_request['gst'];
    $vendorAddress              = $this->_request['vendorAddress'];
}

?>


<?php 
if($data['pageName']){
    include('vendorwiseOrder.php');
}else{
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9">
            <form action="" method="post">
                <div class="card p-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                            if($data['vendors']) {
                                ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%"><input class="selectall" name="toggle" type="checkbox"></th>
                                        <th width="20%" colspan="2">Vendor</th>
                                        <th width="15%">Contact No.</th>
                                        <th width="20%">Address</th>
                                        <th width="15%">Created On</th>
                                        <th width="5%">Action</th>
                                        <th width="20%">
                                            <div class="alert alert-success font-weight-bold">Records Found:
                                                <?php echo $data['rowCount'];?></div>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                        $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                                        foreach($data['vendors'] as $item) {
                                            
                                            if($item['vendorStatus'] == 'Y')
                                                $sizeStatus  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                            else
                                                $sizeStatus  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                            
                                            ?>
                                    <tr id="<?php echo 'recordsArray_'.$item['vendorId'];?>">
                                        <td width="40">
                                            <input type="checkbox" name="selectMulti[]"
                                                value="<?php echo $item['vendorId'];?>" class="case" />
                                        </td>
                                        <td colspan="2">
                                            <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>&editid=<?php echo $item['vendorId'];?>"
                                                data-vendorId="<?php echo $item['vendorId']; ?>">
                                                <?php echo $item['vendorName'];?>
                                            </a>
                                        </td>
                                        <td><?php echo $item['vendorPhone'];?></td>
                                        <td><?php echo $item['vendorAddress'];?></td>
                                        <td><?php echo date('jS M, Y', strtotime($item['entryDate']));?></td>
                                        <td><a href="javascript:void(0);" class="showOrder tooltipBtn ml-3"
                                                data-vendorid="<?php echo $item['vendorId'];?>" data-moduleid="<?php echo $_GET['moduleId'];?>"><i class="fa fa-file" aria-hidden="true"></i><span class="tooltiptext">View Orders</span></a></td>
                                        <td width="240" class="last_li">
                                            <div class="action_link">
                                                <?php echo $sizeStatus;?>
                                            </div>
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

                <?php if($data['vendors']) { ?>
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
                                    <button type="submit" name="Save" value="Apply"
                                        class="btn btn-info m-l-10">Apply</button>
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

        <div class="col-sm-3">
            <div class="card" style="padding: 0px !important;">
                <div class="card-header"><i class="fa fa-user-circle" aria-hidden="true"></i>
                    <?php echo ($IdToEdit != '' ? 'Update Vendor <span class="pull-right"><a href="./index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'">Add Vendor</a></span>' : 'New Vendor');?>
                </div>
                <div class="card-body">
                    <div class="col m-t-20 m-b-20">
                        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">
                            <div class="form-group">
                                <label>Vendor Name *</label>
                                <input type="text" name="vendorName" value="<?php echo $vendorName;?>"
                                    class="form-control" placeholder="" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Contact Number *</label>
                                <input type="text" name="vendorPhone" value="<?php echo $vendorPhone;?>"
                                    class="form-control" placeholder="" autocomplete="off" maxlength="10">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" name="vendorEmail" value="<?php echo $vendorEmail;?>"
                                    class="form-control" placeholder="" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Store Name *</label>
                                <input type="text" name="storeName" value="<?php echo $storeName;?>"
                                    class="form-control" placeholder="" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>GST</label>
                                <input type="text" name="gst" value="<?php echo $gst;?>" class="form-control"
                                    placeholder="" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Address *</label>
                                <div class="limitedtext">
                                    <textarea name="vendorAddress" class="form-control"
                                        maxlength="80"><?php echo $vendorAddress;?></textarea>
                                    <div class="charcount"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                                <input type="hidden" name="SourceForm" value="addEditVendor" />
                                <button type="submit" name="Save" value="Save"
                                    class="btn btn-info login_btn"><?php echo ($IdToEdit != '' ? 'UPDATE' : 'ADD'); ?></button>
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
<?php
}
?>

<script type="text/javascript">
var ajx_url =
    "./index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>";
$(function() {
    $(document).on('click', '.showOrder', function() {
        vendorId = $(this).attr('data-vendorid');
        moduleId = $(this).attr('data-moduleid');

        $('.transactionTbl' + vendorId).remove();

        if (parseInt(vendorId) > 0) {
            $.ajax({
                type: 'POST',
                url: ajx_url,
                data: "vendorId=" + vendorId + "&moduleId=" + moduleId + "&ajx_action=getVendorwiseOrder",
                success: function(response) {
                    $('#recordsArray_' + vendorId).after(response.html);
                }
            });
        }
    });

    $(document).on('click', '.closeVendorHistoryTbl', function() {
        vendorId = $(this).attr('data-vendorid');

        $('.transactionTbl' + vendorId).remove();
    });

});
</script>