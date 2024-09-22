<?php
defined('BASE') OR exit('No direct script access allowed.');
//showArray($data['selectedSize']);
if(is_array($data['selectedMaterial']) && count($data['selectedMaterial']) > 0) {
    $IdToEdit                   = $data['selectedMaterial']['mtId'];
    $materialName                   = $data['selectedMaterial']['material_name'];
    $material_status                     = $data['selectedMaterial']['material_status'];
} else {
    $IdToEdit                   = $this->_request['mtId'];
    $materialName                   = $this->_request['material_name'];
    $material_status                     = $this->_request['material_status'];
}

?>
<div class="container-fluid">
    <div class="row">
        <form name="searchForm" action="" method="post">
            <div class="form-inline">
                <div class="form-group">
                    <input type="text" name="searchText" value="<?=$this->session->read('searchText')??''?>" placeholder="Search" class="form-control">
                </div>

                <div class="form-group">
                    <button type="submit" name="Search" class="btn btn-info width-auto"><i class="fa fa-search"></i></button>
                    <button type="submit" name="Reset" class="btn btn-dark width-auto m-l-10"><i class="fa fa-refresh"></i></button>
                </div>
            </div>

        </form>
        
        <div class="col-sm-8">
            <form action="" method="post">
                <div class="card p-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                            if($data['material']) {
                                ?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="40"><input class="selectall" name="toggle" type="checkbox"></th>
                                            <th colspan="2">Paper Material</th>
                                            <th width="320">
                                                <div class="alert alert-success font-weight-bold">Records Found: <?php echo $data['rowCount'];?></div>
                                            </th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody class="swap">
                                        <?php
                                        $slNo = ($this->_request['page'] > 1) ? (($this->_request['page'] - 1) * $data['limit']) + 1 : 1;
                                        foreach($data['material'] as $item) {
                                            
                                            if($item['material_status'] == 'Y')
                                                $materialStatus  = '<span class="status"><i class="fa fa-check" title="Active"></i> Active</span>';
                                            else
                                                $materialStatus  = '<span class="status inactive"><i class="fa fa-times" title="Inactive"></i> Inactive</span>';
                                            
                                            ?>
                                            <tr id="<?php echo 'recordsArray_'.$item['mtId'];?>">
                                                <td width="40">
                                                    <input type="checkbox" name="selectMulti[]" value="<?php echo $item['mtId'];?>" class="case" />
                                                </td>
                                                                                               

                                                <td colspan="2">
                                                    <a href="index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>&editid=<?php echo $item['mtId'];?>" data-mtId="<?php echo $item['mtId']; ?>">
                                                        <?php echo $item['material_name'];?>
                                                    </a>
                                                </td>
                                                
                                                <td width="250" class="last_li">
                                                    <div class="action_link">
                                                        <?php echo $materialStatus;?>
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

                <?php if($data['material']) { ?>
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
            <div class="card" style="padding: 0px !important;">
                <div class="card-header"><i class="fa fa-sort-numeric-asc" aria-hidden="true"></i> <?php echo ($IdToEdit != '' ? 'Update Material <span class="pull-right"><a href="./index.php?pageType='.$this->_request['pageType'].'&dtls='.$this->_request['dtls'].'&moduleId='.$this->_request['moduleId'].'">Add Material</a></span>' : 'New Material');?></div>
                <div class="card-body">
                    <div class="col m-t-20 m-b-20">
                        <form class="form-valide-size" name="modifycontent" action="" method="post" id="frmSize">
                            <div class="form-group">
                                <label for="material_name">Material Name <span class="text-danger">*</span></label>
                                <input type="text" name="material_name" id="material_name" value="<?php echo $materialName; ?>" class="form-control" placeholder="" autocomplete="off" maxlength="255">
                                
                            </div>

                            <?php 
                            if($IdToEdit != '') {
                                ?>
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="material_status" class="form-control">
                                    <option value="Y" <?php echo ($material_status == 'Y' ? 'selected="selected"': '');?>>Active</option>
                                    <option value="N" <?php echo ($material_status == 'N' ? 'selected="selected"': '');?>>Inactive</option>
                                </select>
                            </div>
                                <?php 
                            }
                            ?>
                            
                            <div class="form-group">
                                <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                                <input type="hidden" name="SourceForm" value="addEditMaterial" />
                                <button type="submit" name="Save" value="Save" class="btn btn-info login_btn mb-2"><?php echo ($IdToEdit != '' ? 'UPDATE' : 'ADD'); ?></button>

                                <?php 
                                if($IdToEdit != '') {
                                    ?>
                                <span><button type="button" name="Save" value="Save" class="btn btn-danger float-right" onclick="deleteConfirm(this, event, 'warning','Are you sure to delete?');">DELETE</button></span>
                                    <?php 
                                }
                                ?>

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

<script type="text/javascript" defer>
    var ajx_url = "./index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>";

    $(function(){
        $(document).ready(function(){
            //toster(0,'Menu added successfully.','Success!');
            form_validation.init();
        });
    });

    function deleteConfirm(obj, event, msgtype, title){
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
            var btn      = $(obj), 
                form     = btn.parents('form'), 
                formData = form.serialize() + '&isDeleted=Y&ajx_action=delSingleMaterial';
            $.ajax({
                type : 'POST',
                url : ajx_url,
                data : formData,
                success: function(response) {
                    swal.close();
                    if(response.type == 1) {
                        toster(1, response.message,'Success');
                        setTimeout(function(){
                            location.reload();
                        }, 5000);
                    } else {
                        toster(0, response.message, 'Error!');
                    }
                }
            });
        });
    }

    var form_validation = function(){
        var e= function(){
            jQuery(".form-valide-size").validate({
                ignore: [],
                errorClass: "invalid-feedback animated fadeInDown",
                errorElement: "div",
                errorPlacement: function(e, a) {
                    jQuery(a).parents(".form-group > div").append(e)
                },
                highlight: function(e) {
                    jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")
                },
                success: function(e) {
                    jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()
                },
                rules: {
                    "material_name": {
                        required: !0
                    }
                },
                messages: {
                    "material_name": {
                        required: "Please enter a material_name"
                    }
                }
            })
        }
        return {
            init: function() {
                e()
            }
        }
    }();
    
    function isNumberKey(txt, evt) {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode == 46) {
        //Check if the text already contains the . character
        if (txt.value.indexOf('.') === -1) {
          return true;
        } else {
          return false;
        }
      } else {
        if (charCode > 31 &&
          (charCode < 48 || charCode > 57))
          return false;
      }
      return true;
    }
  </script>