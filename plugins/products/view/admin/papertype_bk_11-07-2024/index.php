<?php
defined('BASE') OR exit('No direct script access allowed.');
?>
<div class="container-fluid">
    <?php
    // if($data['act']['message'])
    //     echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>

    <div class="row">
        <div class="col-sm-8">
            <form action="" method="post">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card p-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <?php
                                    if($data['type']) {
                                        ?>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="40"><input class="selectall" name="toggle" type="checkbox"></th>
                                                    <th colspan="2">Category Name</th>
                                                    <th colspan="2">Sub Category</th>
                                                    <th width="180">
                                                        <div class="alert alert-success font-weight-bold">Records Found: <?php echo $data['rowCount'];?></div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody class="swap">
                                                <?php
                                                $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                                                foreach($data['type'] as $item) {

                                                    $sizeChart = $this->hook('papertype', 'sizeChart', array('typeId' => $item['typeId'], 'typeName'=> $item['typeName']));

                                                    $subCategoryName = $this->hook('papertype', 'subCategoryList', array('typeId' => $item['typeId'], 'typeName'=> $item['typeName']));

                                                    
                                                    if($item['typeStatus'] == 'Y')
                                                        $typeStatus  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                                    else
                                                        $typeStatus  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                                    
                                                    ?>
                                                    <tr id="<?php echo 'recordsArray_'.$item['typeId'];?>">
                                                        <td width="40">
                                                            <input type="checkbox" name="selectMulti[]" value="<?php echo $item['typeId'];?>" class="case" />
                                                        </td>
                                                        
                                                        <td colspan="2">
                                                            <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&editid=<?php echo $item['typeId'];?>&moduleId=<?php echo $this->_request['moduleId'];?>" data-typeid="<?php echo $item['typeId']; ?>">
                                                                <?php echo $item['typeName'];?>
                                                            </a>
                                                        </td>

                                                        <td colspan="2"><?php echo $subCategoryName; ?></td>
                                                        
                                                        <td width="180" class="last_li">
                                                            <div class="action_link">
                                                                <?php echo $typeStatus;?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
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
                        
                        <?php if($data['type']) { ?>
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
        
        <div class="col-sm-4">
            <div class="card" style="padding: 0px !important;">
                <div class="card-header"><i class="fa fa-superpowers" aria-hidden="true"></i> <?php echo ($IdToEdit != '' ? 'Update Category <span class="pull-right"><a href="./index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'">Add Category</a></span>' : 'New Category');?></div>
                <div class="card-body">
                    <div class="col m-t-20 m-b-20">
                        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">
                            <div class="form-group">
                                <select name="parentTypeId" id="parentTypeId" class="form-control">
                                    <option value="">Select Parent Category</option>
                                    <?php 
                                    if(is_array($data['parentType']) && count($data['parentType']) > 0) {
                                        foreach($data['parentType'] as $item) {
                                            if($item['typeStatus'] == 'Y') {
                                                echo '<option value="'.$item['typeId'].'" '.($item['typeId'] == $parentTypeId ? 'selected' : '').'>'.$item['typeName'].'</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Category Name *</label>
                                <input type="text" name="typeName" value="<?php echo $typeName; ?>" class="form-control" placeholder="" maxlength="255">
                            </div>

                            <div class="form-group">
                                <input type="text" id="text-tags" class="tags" />
                            </div>
                            
                            <div class="form-group">
                                <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                                <input type="hidden" name="SourceForm" value="addEditType" />
                                <button type="submit" name="Save" value="Save" class="btn btn-info login_btn"><?php echo ($IdToEdit != '' ? 'UPDATE' : 'ADD'); ?></button>
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