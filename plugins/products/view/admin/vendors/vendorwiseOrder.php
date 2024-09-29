<?php defined('BASE') OR exit('No direct script access allowed.'); ?>

<?php
if($_SESSION["fromDate"] !="")
{
    $fromDate = date('d-m-Y', strtotime($_SESSION["fromDate"]));
}
else
{
    $fromDate = '';
}

if($_SESSION["toDate"] !="")
{
    $toDate = date('d-m-Y', strtotime($_SESSION["toDate"]));
}
else
{
    $toDate = '';
}

$monday = strtotime("last sunday");
$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;

$sunday = strtotime(date("Y-m-d",$monday)." +6 days");

echo $this_week_start = date("Y-m-d",$monday);
echo $this_week_end = date("Y-m-d",$sunday);

?>


<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9">
            <div class="card p-0 vendorDetails">
                <div class="card-body">
                    <form name="searchForm" action="" method="post" style="max-width: max-content; margin-right: auto; padding-left:20px;">
                        <div class="row">

                            <div class="form-group" style="margin-bottom:20px; margin-right:20px;">
                                <select name="choose_date" class="form-control">
                                    <option value="">Select Date</option>
                                    <option value="today" <?php echo ($_SESSION["choose_date"] ==  'today') ? 'selected="selected"' : '';?>>Today</option>
                                    <option value="this_week" <?php echo ($_SESSION["choose_date"] ==  'this_week') ? 'selected="selected"' : '';?>>This Week</option>
                                    <option value="this_month" <?php echo ($_SESSION["choose_date"] ==  'this_month') ? 'selected="selected"' : '';?>>This Month</option>
                                    <option value="last_3_month" <?php echo ($_SESSION["choose_date"] ==  'last_3_month') ? 'selected="selected"' : '';?>>Last 3 Month</option>
                                    <option value="last_6_month" <?php echo ($_SESSION["choose_date"] ==  'last_6_month') ? 'selected="selected"' : '';?>>Last 6 Month</option>
                                    <option value="custom_date">Custom Date</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-4">
                                <input type="text" name="from_date" value="<?php echo $fromDate; ?>"
                                    id="from_date" placeholder="From Date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="to_date" value="<?php echo $toDate; ?>" id="to_date"
                                    placeholder="To Date" class="form-control">
                            </div> -->
                            <div class="form-group">
                                <button type="submit" name="Search" class="btn btn-info width-auto"><i
                                        class="fa fa-search"></i></button>
                                <button type="submit" name="Reset" class="btn btn-dark width-auto m-l-10"><i
                                        class="fa fa-refresh"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="card p-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if($data['vendorTransaction']) { ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Purchase Date</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Size</th>
                                    <th>GSM</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $total = 0; foreach($data['vendorTransaction'] as $item) { ?>
                                <tr>
                                    <td><?php echo date('jS M, Y', strtotime($item['purchaseDate'])); ?></td>
                                    <td><?php echo $item['productName']; ?></td>
                                    <td><?php echo $item['categoryName']; ?></td>
                                    <td><?php echo $item['sizeName']; ?></td>
                                    <td><?php echo $item['gsmName']; ?></td>
                                    <td><?php echo $item['purchaseQty']; ?></td>
                                    <td><?php echo $item['purchasePrice']; ?></td>
                                </tr>
                                <?php $total = $total + $item['purchasePrice']; } ?>
                            </tbody>
                        </table>
                        <?php
                        }
                        else
                            echo '<div class="norecord text-center">No Record Present</div>';
                        ?>
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

            <?php if($data['vendorTransaction']) { ?>
                <div class="card p-0 vendorDetails">
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>

                            </tr>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-sm-3">
            <div class="card p-0 vendorDetails">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            Vendor : <strong><?php echo $data['vendorDetails']['vendorName']; ?></strong>
                        </div>
                        <div class="col-md-12">
                            Store : <strong><?php echo $data['vendorDetails']['storeName']; ?></strong>
                        </div>
                        <div class="col-md-12">
                            Contact No. : <strong><?php echo $data['vendorDetails']['vendorPhone']; ?></strong>
                        </div>
                        <div class="col-md-12">
                            Address : <strong><?php echo $data['vendorDetails']['vendorAddress']; ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#from_date').datepicker({
        firstDay: 1,
        showOtherMonths: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        maxDate: new Date()
    });
    $('#to_date').datepicker({
        firstDay: 1,
        showOtherMonths: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        maxDate: new Date()
    });
});

</script>