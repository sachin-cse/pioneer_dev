<?php
defined('BASE') OR exit('No direct script access allowed.');

//showArray($data['cropImage']);
if($data['cropImage']) {

    $IdToEdit           = $data['cropImage']['categoryId'];
    $categoryId         = $data['cropImage']['categoryId'];
    $serviceImage       = $data['cropImage']['categoryImage'];
    $width              = $data['settings']['categoryWidth'];
    $height             = $data['settings']['categoryHeight'];
    
} else {
    $IdToEdit           = $this->_request['categoryId'];
    $categoryId         = $this->_request['categoryId'];
    $serviceImage       = $this->_request['categoryImage'];
    $width              = $data['settings']['categoryWidth'];
    $height             = $data['settings']['categoryHeight'];
}

$mediaPath  = '..'.DS.$this->modPath.DS.$this->_request['pageType'].DS.'media';
?>
<link rel="stylesheet" href="<?php echo $mediaPath;?>/css/cropper.css">
<link rel="stylesheet" href="<?php echo $mediaPath;?>/css/main.css">
<div class="container-fluid">
    <?php
    if(isset($data['act']['message']))
        echo (isset($data['act']['type']) && $data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>

    <div>
        <form name="modifycontent" action="" method="post" enctype="multipart/form-data" id="form">
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    if($this->_request['type'] == 'gallery'){
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group clearfix">
                                    <label>Change Photo (Recommended Size: <?php echo $width.'px * '.$height.'px';?>)</label>
                                    <input type="file" id="inputImage" name="serviceImage" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    //echo MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/thumb/'.$serviceImage.'?t='.time();
                    if($serviceImage && file_exists(MEDIA_FILES_ROOT.'/'.$this->_request['pageType'].'/thumb/'.$serviceImage)) { ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group clearfix">
                                    
                                <div class="col-md-12 preview_container">
                                    <div class="img-container">
                                        <img class="previewImg" data-normal="<?php echo MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/normal/'.$serviceImage.'?t='.time();?>" src="<?php echo MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/thumb/'.$serviceImage.'?t='.time();?>" alt="Picture">
                                    </div>        
                                </div>

                                <?php /*
                                <div class="table_img m-t-10"><img  id="cropbox" src="'.MEDIA_FILES_SRC.'/'.$this->_request['pageType'].'/thumb/'.$serviceImage.'" alt="'.$serviceImage.'"></div>';*/?>

                                <hr class="m-t-30">

                                <div class="col-md-9 docs-buttons">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary" data-method="crop" title="Crop">
                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;crop&quot;)">
                                                <span class="fa fa-check"></span> Crop
                                            </span>
                                        </button>
                                        <button type="button" class="btn btn-primary" data-method="clear" title="Clear">
                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;clear&quot;)">
                                                <span class="fa fa-remove"></span> Clear
                                            </span>
                                        </button>

                                        <button type="button" class="btn btn-primary" data-method="reset" title="Reset">
                                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;reset&quot;)">
                                          <span class="fa fa-refresh"></span> Reset
                                        </span>
                                      </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                
                <div class="col-sm-4 contentS">
                   
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="history.back(-1);" class="btn btn-default m-r-15">Back</button>
                            
                            <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                            <input type="hidden" name="categoryId" value="<?php echo $categoryId;?>" />
                            <input type="hidden" name="SourceForm" value="cropImage" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>
                            
                            <input type="hidden" name="x" id="dataX">
                            <input type="hidden" name="y" id="dataY">
                            <input type="hidden" name="w" id="dataWidth">
                            <input type="hidden" name="h" id="dataHeight">

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
    var ratio = <?php echo ($width/$height);?>
</script> 

<script src="<?php echo $mediaPath;?>/js/cropper.js"></script>
<script src="<?php echo $mediaPath;?>/js/jquery-cropper.js"></script>

<script src="<?php echo $mediaPath;?>/js/main.js"></script>