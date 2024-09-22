<?php defined('BASE') OR exit('No direct script access allowed.');?>

<div class="card">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table" id="dataSizeTable">
                <thead>
                    <tr>
                        <th width="25"></th>
                        <th width="120">Attribute</th>
                        <th width="120">Type</th>
                        <th align="left"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $blankRow = '<tr class="rowcount"><td><input type="checkbox" name="chk"/></td><td><input type="text" name="attributeNameArray[]" value="" maxlength="100" class="form-control" /></td><td><select name="attributeType[]" class="form-control attributetype"><option value="">Select</option><option value="text">Textfield</option><option value="radio">Radio Button</option><option value="checkbox">Checkbox</option><option value="image">Image</option></select></td><td align="left"><div class="options" style="display:none;"><span class="default"><input type="text" name="attributeOptions[0][]" placeholder="Options" class="form-control default"/></span><div class="option_controls"><a href="#" class="addoption">add new</a></div></div></td></tr>';

                    if($data['attributes']) {
                        
                        foreach($data['attributes'] as $key=>$attribute) {
                            ?>
                            <tr class="rowcount">
                                <td><input type="checkbox" name="chk"/></td>
                                <td>
                                    <input type="text" name="attributeNameArray[]" value="<?php echo $attribute['attributeName'];?>" maxlength="100" class="form-control" />
                                    <input type="hidden" name="attributeIdArray[]" value="<?php echo $attribute['attributeId'];?>"/>
                                </td>
                                <td>
                                    <select name="attributeType[]" class="form-control attributetype">
                                        <option value="">Select</option>
                                        <option value="text" <?php echo ($attribute['attributeType'] == 'text') ? 'selected' : '';?>>Textfield</option>
                                        <option value="radio" <?php echo ($attribute['attributeType'] == 'radio') ? 'selected' : '';?>>Radio Button</option>
                                        <option value="checkbox" <?php echo ($attribute['attributeType'] == 'checkbox') ? 'selected' : '';?>>Checkbox</option>
                                        <option value="image" <?php echo ($attribute['attributeType'] == 'image') ? 'selected' : '';?>>Image</option>
                                    </select>
                                </td>
                                <?php
                                if($attribute['attributeOptions'] != '') {
                                    $options    = explode('@#@', $attribute['attributeOptions']);
                                    $style      = 'style="display:block"';
                                }
                                else
                                    $style = 'style="display:none"';
                                ?>
                                <td align="left">
                                    <div class="options" <?php echo $style;?>>
                                        <?php
                                        if($attribute['attributeOptions'] != ''){
                                            foreach($options as $val) {
                                                if($val) {
                                                    echo '<span class="default">
                                                            <input type="text" name="attributeOptions['.$key.'][]" value="'.$val.'" placeholder="Options" class="form-control default"/>
                                                            <a href="#" class="removeoption">&times;</a>
                                                        </span>';
                                                }
                                            }
                                        }
                                        else {
                                            echo '<span class="default"><input type="text" name="attributeOptions['.$key.'][]" placeholder="Options" class="form-control default"/></span>';	
                                        }
                                        ?>
                                        <div class="option_controls"><a href="#" class="addoption">add new</a></div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    else
                        echo $blankRow;
                    ?>
                </tbody>
            </table>
            <div class="m-t-20">
                <a href="javascript:void(0)" class="AddRow">Add Value</a> |
                <a href="javascript:void(0)" onclick="deleteRow('dataSizeTable')">Delete Value (Tick checkbox to delete)</a>
            </div>
        </div>

    </div>
</div>

<script>
    $(function(){
        $(document).on('click', '.AddRow', function(){
            $(this).parent().prev('table').append('<?php echo $blankRow;?>');
        });

        $(document).on('change', '.attributetype', function(){
            var attributeType = $(this).val();
            if(attributeType=='radio' || attributeType=='checkbox' || attributeType=='image') {
                var rowNum = ($(this).closest('tr').index());

                $(this).parent().next().children('.options').css('display','block');
                $(this).parent().next().children('.options').children(".default").remove();

                $(this).parent().next().children('.options').html('<span class="default"><input type="text" name="attributeOptions['+rowNum+'][]" placeholder="Options" class="form-control default"/></span><div class="option_controls"><a href="#" class="addoption">add new</a></div>');
            }
            else
                $(this).parent().next().children('.options').css('display','none');
        });
        $(document).on('click', '.addoption', function(e){
            e.preventDefault();
            var addoption = $(this),
                rowNum = (addoption.closest('tr').index());
            
            addoption.parent().before('<span class="default"><input type="text" name="attributeOptions['+rowNum+'][]" placeholder="Options" class="form-control default"/> <a href="#" class="removeoption">&times;</a></span>');
            $('.default input[name="attributeOptions['+rowNum+'][]"]').focus();
            
        });
        $(document).on('click', '.removeoption', function(e){
            e.preventDefault();
            $(this).parent().remove();
        });	
    });

    /* function deleteRow(tableID) {
        try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length; 
            for(var i=0; i<rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    if(rowCount <= 2) {
                        alert("Cannot delete all the rows.");
                        break;
                    }
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }
            }
        } catch(e) {
            alert(e);
        }
    } */

    function deleteRow(tableID) {
        var table = $('#'+tableID);
        var tbody = table.children('tbody');
        var rowCount = tbody.children('tr').length;

        for(var i=0; i<rowCount; i++) {
            var row = tbody.children('tr').eq(i);
            var chkbox = row.find('[name="chk"]');
            if(chkbox.prop('checked') == true) {
                if(rowCount <= 1) {
                    tbody.append('<?php echo $blankRow;?>');
                }
                row.remove();
                rowCount--;
                i--;
            }
        }
    }
</script>