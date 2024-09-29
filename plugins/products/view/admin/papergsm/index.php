<?php
defined('BASE') OR exit('No direct script access allowed.');
//showArray($data['selectedGSMSize']); 
if($data['selectedGSM']) {
    $IdToEdit                       = $data['selectedGSM']['gsmId'];
    $gsmName                        = $data['selectedGSM']['gsmName'];
	
	$qrystrPermalink			    = 'gsmId != '.$IdToEdit;
} else {

    $IdToEdit                       = $this->_request['gsmId'];
    $gsmName                        = $this->_request['gsmName'];

	$qrystrPermalink			    = 1;
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
            <form action="" method="post">
                <div class="card p-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                            if($data['gsm']) {
                                ?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="40"><input class="selectall" name="toggle" type="checkbox"></th>
                                            <th>GSM</th>
                                            <!-- <th colspan="2">Paper Size</th> -->
                                            <th width="360">
                                                <div class="alert alert-success font-weight-bold">Records Found: <?php echo $data['rowCount'];?></div>
                                            </th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php
                                        $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                                        foreach($data['gsm'] as $item) {
                                            $sizeChart = $this->hook('papergsm', 'sizeChart', array('sizeId' => $item['sizeId'], 'gsmName'=> $item['gsmName']));
                                            
                                            if($item['gsmStatus'] == 'Y')
                                                $gsmStatus  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                            else
                                                $gsmStatus  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                            
                                            ?>
                                            <tr id="<?php echo 'recordsArray_'.$item['gsmId'];?>">
                                                <td width="40">
                                                    <input type="checkbox" name="selectMulti[]" value="<?php echo $item['gsmId'];?>" class="case" />
                                                </td>
                                                <td>
                                                    <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&editid=<?php echo $item['gsmId'];?>&moduleId=<?php echo $this->_request['moduleId'];?>" data-gsmid="<?php echo $item['gsmId']; ?>">
                                                        <?php echo $item['gsmName'].' GSM';?>
                                                    </a>
                                                </td>
                                                <!-- <td colspan="2">
                                                    <a href="javascript: void(0);">
                                                        <?php 
                                                        echo $sizeChart;
                                                        ?>
                                                    </a>
                                                </td> -->
                                                <td width="250" class="last_li">
                                                    <div class="action_link">
                                                        <?php echo $gsmStatus;?>
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

                <?php if($data['gsm']) { ?>
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
        
        <div class="col-sm-4">
            <div class="card" style="padding: 0px !important;">
                <div class="card-header"><i class="fa fa-eraser" aria-hidden="true"></i> <?php echo ($IdToEdit != '' ? 'Update GSM <span class="pull-right"><a href="./index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'">Add GSM</a></span>' : 'New GSM');?></div>
                <div class="card-body">
                    <div class="col m-t-20 m-b-20">
                        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">
                            <div class="form-group">
                                <label>GSM *</label>
                                <input type="text" name="gsmName" value="<?php echo $gsmName; ?>" class="form-control" placeholder="" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                            </div>
                            
                            <div class="form-group">
                                <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                                <input type="hidden" name="SourceForm" value="addEditGSM" />
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