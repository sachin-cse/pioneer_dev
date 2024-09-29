<?php
defined('BASE') OR exit('No direct script access allowed.');
if($data['record']) {

    $IdToEdit                      	= $data['record']['categoryId'];
    $parentId                       = $data['record']['parentId'];
    $categoryName                   = $data['record']['categoryName'];
    $categoryUrl                   	= $data['record']['categoryUrl'];
    $categoryDescription           	= $data['record']['categoryDescription'];
    $displayOrder                  	= $data['record']['displayOrder'];
    $status                        	= $data['record']['status'];
	
	$qrystrPermalink			   	= 'categoryId != '.$IdToEdit;
}
else {

    $IdToEdit                      	= $this->_request['categoryId'];
    $parentId                       = $this->_request['parentId'];
    $categoryName                   = $this->_request['categoryName'];
    $categoryUrl                   	= $this->_request['categoryUrl'];
    $categoryDescription           	= $this->_request['categoryDescription'];
    $displayOrder                  	= $this->_request['displayOrder'];
    $categoryImage                  = $this->_request['categoryImage'];
    $status                        	= $this->_request['status'];
	
	$qrystrPermalink			   	= 1;
}

?>
<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
 
    <div>
        <form name="modifycontent" action="" method="POST" enctype="multipart/form-data" id="form">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">

                            <?php
                            if($data['parentCategory']) {
                                echo '<div class="form-group">
                                    <label>Parent Category</label>
                                    <select name="parentId" class="form-control">
                                        <option value="">Select</option>
                                        '.$data['parentCategory'].'
                                    </select>
                                    </div>';
                            }
                            ?>
                    
                            <div class="form-group">
                                <label>Category Name *</label>
                                <input type="text" name="categoryName" value="<?php echo $categoryName;?>" class="form-control permalink copyToTitle" placeholder="" autocomplete="off" data-entity="<?php echo TBL_PRODUCT_CATEGORY;?>" data-qrystr="<?php echo $qrystrPermalink;?>" maxlength="255">
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Y" <?php if($status=='Y') echo 'selected'?>>Active</option>
                                    <option value="N" <?php if($status=='N') echo 'selected'?>>Inactive</option>
                                </select>
                            </div>
                            
                            <!-- <div class="form-group">
                                <label>Permalink</label>
                                <input type="text" name="permalink" value="<?php echo  $permalink;?>" class="form-control gen_permalink" placeholder="" autocomplete="off" maxlength="255">
                            </div> -->
                        </div> 
                    </div>

                    <?php //include("attributes.php");?>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Category Description</label>
                                <textarea name="categoryDescription" class="form-control"><?php echo $categoryDescription;?></textarea>
                            </div>
                        </div> 
                    </div>
                </div>
                
                <div class="col-sm-4 contentS">
                    
                    <?php if($IdToEdit) {?>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="m-b-0 w-100">

                                        <a href="<?php echo 'index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&dtaction='.$this->_request['dtaction'].'&moduleId='.$this->_request['moduleId'].(($parentId) ? '&parentId='.$parentId : '');?>" class="btn btn-default pull-right">Add New</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php }?>

                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="history.back(-1);" class="btn btn-default m-r-15">Back</button>
                            
                            <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                            <input type="hidden" name="SourceForm" value="addEditCategory" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    function deleteConfirm(msgtype,title){
        swal({
            title: title,
            text: "",
            type: msgtype,
            showCancelButton: true,
            confirmButtonColor: "#ef5350",
            confirmButtonText: "Yes, delete it!!",
            closeOnConfirm: false
        },
        function(){
            $('#form').submit();
        });
    }
</script>