<div class="row page-titles">
    <div class="col-sm-5 align-self-center"><h3 class="text-primary">Dashboard</h3></div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card hover p-0 unitCount">
                <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType=sale&dtls=order&moduleId=544';?>" class="p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-files-o f-s-40 color-warning"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2><?php echo $data['body']['pageCount'];?></h2>
                            <p class="m-b-0"><?php echo ($data['body']['pageCount'] > 1)? 'Total Invoices' : 'Total Invoice';?></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card hover p-0 unitCount">
                <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType=products&dtls=vendors&moduleId=542'?>" class="p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-user f-s-40 color-info"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2><?php echo $data['body']['vendorCount'];?></h2>
                            <p class="m-b-0"><?php echo ($data['body']['vendorCount'] > 1)? 'Vendors' : 'Vendor';?></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <?php 
        $moduleCount  = count($data['navigation']);
        ?>
        <div class="col-md-3">
            <div class="card hover p-0 unitCount">
                <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType=products&dtls=productlist&moduleId=539'?>" class="p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-cubes f-s-40 color-primary"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2><?php echo $data['body']['productCount'];?></h2>
                            <p class="m-b-0"><?php echo ($data['body']['productCount'] > 1)? 'Products' : 'Product';?></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 lastlogin">
            <div class="card p-30 unitCount">
                <div class="media">
                    <div class="media-left meida media-middle">
                        <span><i class="fa fa-clock-o f-s-40 color-danger"></i></span>
                    </div>
                    <div class="media-body media-text-right">
                        <h2><?php
                            if(date('jS M, Y', strtotime($this->session->read('LASTLOGIN'))) == date('jS M, Y'))
                                echo 'Today';
                            elseif(date('jS M, Y', strtotime($this->session->read('LASTLOGIN'))) == date('jS M, Y', strtotime('-1 day')))
                                echo 'Yesterday';
                            else
                                echo date('jS M, Y', strtotime($this->session->read('LASTLOGIN')));
                            echo '<br>'.date('h:i A', strtotime($this->session->read('LASTLOGIN')));
                            ?></h2>
                        <p class="m-b-0">Last Login</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-title">
                    <h4>Sales</h4>
                    <span class="pull-right" style="font-size: 13px;"><a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType=sale&dtls=report&moduleId=545';?>">View Details</a></span>
                </div>
                <div class="card-body">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-title">
                    <h4>Recent Order</h4>
                    <span class="pull-right" style="font-size: 13px;"><a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType=sale&dtls=order&moduleId=544';?>">View Details</a></span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover ">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer Name</th>
                                    <th>Amount (<?php echo SITE_CURRENCY_SYMBOL; ?>)</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>123123123123</td>
                                    <td>Test User</td>
                                    <td>251106.00</td>
                                    <td>01-12-2022</td>
                                </tr>
                                <tr>
                                    <td>123123123123</td>
                                    <td>Test User</td>
                                    <td>251106.00</td>
                                    <td>01-12-2022</td>
                                </tr>
                                <tr>
                                    <td>123123123123</td>
                                    <td>Test User</td>
                                    <td>251106.00</td>
                                    <td>01-12-2022</td>
                                </tr>
                                <tr>
                                    <td>123123123123</td>
                                    <td>Test User</td>
                                    <td>251106.00</td>
                                    <td>01-12-2022</td>
                                </tr>
                                <tr>
                                    <td>123123123123</td>
                                    <td>Test User</td>
                                    <td>251106.00</td>
                                    <td>01-12-2022</td>
                                </tr>
                                <tr>
                                    <td>123123123123</td>
                                    <td>Test User</td>
                                    <td>251106.00</td>
                                    <td>01-12-2022</td>
                                </tr>
                                <tr>
                                    <td>123123123123</td>
                                    <td>Test User</td>
                                    <td>251106.00</td>
                                    <td>01-12-2022</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-4">
            <div class="card">
                <div class="card-title">
                    <h4>Stock </h4>
                    <span class="pull-right" style="font-size: 13px;"><a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType=products&dtls=productstock&moduleId=541'?>">View Details</a></span>
                </div>
                <div class="card-body">
                    <div class="current-progress">
                        <?php 
                        if(is_array($data['body']['productStock']) && count($data['body']['productStock']) > 0) {
                            //showArray($data['body']['productStock']);
                            foreach($data['body']['productStock'] as $item) {
                                $purchaseQty    = $item['stockQty'];
                                $saleQty        = 0;
                                $stockInHand    = ( ( ($purchaseQty - $saleQty) / $purchaseQty) * 100 );
                                ?>
                        <div class="progress-content">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="progress-text"><?php echo $item['productName']; ?></div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="current-progressbar">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-primary <?php echo 'w-'.$stockInHand; ?>" role="progressbar" aria-valuenow="<?php echo $stockInHand; ?>" aria-valuemin="0" aria-valuemax="100"> <?php echo $stockInHand.'%'?> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-title">
                    <h4>Vendors</h4>
                    <span class="pull-right" style="font-size: 13px;"><a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType=products&dtls=vendors&moduleId=542'?>">View Details</a></span>
                </div>
                <div class="card-body">
                    <div class="recent-meaasge">
                        <?php 
                        if(is_array($data['body']['vendors']) && count($data['body']['vendors']) > 0) {
                            foreach($data['body']['vendors'] as $item) {
                                echo '
                                <div class="media">
                                    <div class="media-body">
                                        <h4 class="media-heading">'.$item['vendorName'].'</h4>
                                        <div class="meaasge-date">'.time_elapsed_string($item['entryDate']).'</div>
                                    </div>
                                </div>
                                ';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-title">
                    <h4>Recent Products</h4>
                    <span class="pull-right" style="font-size: 13px;"><a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType=products&dtls=productlist&moduleId=539'?>">View Details</a></span>
                </div>
                <div class="card-body">
                    <div class="recent-meaasge">
                        <?php 
                        if(is_array($data['body']['products']) && count($data['body']['products']) > 0) {
                            foreach($data['body']['products'] as $item) {
                                echo '
                                <div class="media">
                                    <div class="media-body">
                                        <h4 class="media-heading">'.$item['productName'].'</h4>
                                        <div class="meaasge-date">'.$item['gsmName'].' GSM | '.$item['typeName'].'</div>
                                    </div>
                                </div>
                                ';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <?php /*
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <iframe src="<?php echo SITE_LOC_PATH;?>" style="width:100%; border:none; height:350px;"></iframe>
                </div>
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center"><?php echo SITE_NAME;?></h2> 
                    <p class="text-center"><a href="<?php echo SITE_LOC_PATH;?>" target="_blank"><?php echo SITE_LOC_PATH;?></a></p>
                    <hr>
                    <p><?php echo nl2br(SITE_ADDRESS);?></p>
                    <a href="<?php echo SITE_ADMIN_PATH.'/index.php?pageType=modules&dtls=settings&dtaction=configuration';?>" class="btn btn-info btn-xs" style="color:#fff;">edit</a>
                </div>
            </div>
        </div>
    </div>
    */ ?>
    
</div>

<script>
    $(function(){
        //bar chart
	    var ctx = document.getElementById( "barChart" );
        var myChart = new Chart( ctx, {
            type: 'bar',
            data: {
                labels: [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ],
                datasets: [
                    {
                        label: "Current Year",
                        data: [ 65, 59, 80, 81, 56, 55, 40, 55, 43, 12, 44 ],
                        borderColor: "rgba(0, 123, 255, 0.9)",
                        borderWidth: "0",
                        backgroundColor: "rgba(0, 123, 255, 0.5)"
                    },
                    {
                        label: "Previous Year",
                        data: [ 28, 48, 40, 19, 86, 27, 100, 48, 40, 19, 86, 27, 100 ],
                        borderColor: "rgba(0,0,0,0.09)",
                        borderWidth: "0",
                        backgroundColor: "rgba(0,0,0,0.07)"
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [ {
                        ticks: {
                            beginAtZero: true
                        }
                    } ]
                }
            }
        } );
    });
</script>