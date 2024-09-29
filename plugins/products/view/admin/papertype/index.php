<?php
if(is_array($data['record']) && count($data['record']) > 0) {
    $IdToEdit                   = $data['record']['categoryId'];
    $categoryName               = $data['record']['categoryName'];
    $categoryDescription        = $data['record']['categoryDescription'];
    $status                     = $data['record']['status'];
} else {
    $IdToEdit                   = $this->_request['categoryId'];
    $categoryName               = $this->_request['categoryName'];
    $categoryDescription        = $this->_request['categoryDescription'];
    $status                     = $this->_request['status'];
}

if(isset($_GET['parentId'])){
    $categoryNameByPid = '<span class="category_name">'.$data['getCategoryName']['categoryName'].'</span>';
}

?>

<div class="container-fluid">
    

    <div>
        <div class="row">
            <div class="col-sm-6">
                <h2 class="text-primary text-bold"><?php echo $categoryNameByPid ?? ''; ?></h2>
            </div>
            <div class="col-sm-6">
                <form name="searchForm" action="" method="post" style="max-width: max-content;  margin-left: auto;">
                    <div class="form-inline">
                        <div class="form-group">
                            <input type="text" name="searchText" value="<?=$this->session->read('searchText')??''?>" placeholder="Search By Category Name" class="form-control">
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
                                if($data['records']) {
                                    ?>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th width="40"><input class="selectall" name="toggle" type="checkbox"></th>
                                                <th width="40">Sl.</th>
                                                <th colspan="2">Category</th>
                                                <th width="250"><div class="alert alert-success">Records Found: <?php echo $data['rowCount'];?></div></th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                                            foreach($data['records'] as $record) {
                                                
                                                if($record['status'] == 'Y')
                                                    $statusCheck  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                                else
                                                    $statusCheck  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                                ?>
                                                <tr id="<?php echo 'recordsArray_'.$record['categoryId'];?>">
                                                    <td width="40">
                                                        <input type="checkbox" name="selectMulti[]" value="<?php echo $record['categoryId'];?>" class="case" />
                                                    </td>
                                                    
                                                    <td width="40" scope="row"><?php echo $slNo;?></td>
                        
                                                    <td>
                                                        <?php if(!$this->_request['parentId']) { ?>
                                                        <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&editid=<?php echo $record['categoryId'];?>&moduleId=<?php echo $this->_request['moduleId'];?>">
                                                            <?php echo $record['categoryName'];?>
                                                        </a>
                                                        <?php } else{ ?>
                                                            <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&editid=<?php echo $record['categoryId'];?>&moduleId=<?php echo $this->_request['moduleId'];?>&parentId=<?php echo $this->_request['categoryId']; ?>">
                                                            <?php echo $record['categoryName'];?>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                    
                                                    <td>
                                                        <a  href="<?php echo 'index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'&parentId='.$record['categoryId'];?>">
                                                            Sub Category (<?php echo $record['subCount'];?>)
                                                        </a>
                                                    </td>
                                                    
                                                    <td width="250" class="last_li">
                                                        <div class="action_link">
                                                            <?php echo $statusCheck;?>
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
                    
                    <?php if($data['records']) { ?>
                        <div class="card m-t-20">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-5 pull-right">
                                        <div class="last_li form-inline">
                                            <select name="multiAction" class="form-control multi_action">
                                                <option value="">Select</option>
                                                <option value="1">Active</option>
                                                <option value="2">Inactive</option>
                                                <option value="3">Delete</option>
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
                <div class="row">
                    <div class="col-sm-12 contentL">
                        <div class="card" style="padding: 0px !important;">

                        <div class="card-header"><i class="fa fa-eraser" aria-hidden="true"></i> <?php echo ($IdToEdit != '' ? 'Update GSM <span class="pull-right"><a href="./index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'">Add Paper Type</a></span>' : 'New Paper Type');?></div>

                            <div class="card-body">
                            <div class="col m-t-20 m-b-20">
                            <form name="modifycontent" action="" method="POST" id="form">
                                <?php if($this->_request['parentId']) { ?>
                                <div class="form-group">
                                    <label>Parent Category </label>
                                    <select class="form-control" name="parentCategory">
                                        <option value="">Select Parent Category</option>
                                        <?php foreach($data['records'] as $pr) { ?>
                                            <option value="<?php echo $pr['categoryId']; ?>"><?php echo $pr['categoryName']; ?></option>
                                        <?php } ?>    
                                    </select>
                                </div>
                                <?php } ?>
                                    
                                <!-- <div class="form-group">
                                    <label>Category Name *</label>
                                    <input type="text" name="categoryName" value="<?php echo $categoryName;?>" class="form-control permalink copyToTitle" placeholder="" autocomplete="off" data-entity="<?php echo TBL_PRODUCT_TYPE;?>" data-qrystr="<?php echo $qrystrPermalink;?>" maxlength="255">
                                </div> -->

                                 <div class="form-group">
                                    <label>Category Name *</label>
                                    <input type="text" name="categoryName" value="<?php echo $categoryName;?>" class="form-control" placeholder="" autocomplete="off" maxlength="255">
                                </div>

                                <div class="form-group">
                                    <label>Category Description</label>
                                    <textarea name="categoryDescription" class="form-control"><?php echo $categoryDescription;?></textarea>
                                </div>

                                
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="Y" <?php if($status == 'Y') {echo "selected";} ?>>Active</option>
                                        <option value="N" <?php if($status == 'N') {echo "selected";} ?>>Inactive</option>
                                    </select>
                                </div>

                        
                                <button type="submit" name="Save" value="Save" class="btn btn-info login_btn mb-2"><?php echo ($IdToEdit != '' ? 'UPDATE' : 'ADD'); ?></button>
                                
                                <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                                <input type="hidden" name="SourceForm" value="addEditCategory" />

                                <?php
                                    if($data['act']['message'])
                                        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
                                ?>

                                </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            
            </div>
            
        </div>
    </div>
</div>