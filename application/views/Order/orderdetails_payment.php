<?php
$this->load->view('layout/layoutTop');
?>
<style>
    .vendororder{
        background: #fff;
        border-bottom: 2px solid #c5c5c5;
        border-top: 4px solid #000;
    }
    .vendor-text{
        float: left;
        height: 39px;
        /* vertical-align: middle; */
        line-height: 37px;
        font-size: 21px;
        padding-right: 15px;
        border-right: 1px solid #c5c5c5;
        margin-right: 12px;
    }

</style>
<style>
    .measurement_right_text{
        float: right;
    }
    .measurement_text{
        float: left;
    }
    .fr_value{
        font-size: 15px;
        margin-top: -7px;
        float: left;
    }
    .productStatusBlock{
        padding:10px;
        border: 1px solid #000;
        float: left;
        margin: 5px;
    }

    .payment_block{
        padding: 10px;
        padding-top: 30px;
        margin: 0px;
        margin-top: 30px;
        background: #ddd;
        border: 6px solid #ff3b3b;
    }
</style>

<section class="content" style="min-height: auto;">

    <div class="row">
        <!--title row--> 
        <div class="col-md-12">



            <div class="col-md-9">


                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="width: 100%"><i class=" fa fa-money"></i> Payment Confirmation
                            <span style="float: right"> Order No.: <?php echo $ordersdetails['order_data']->order_no; ?></span>
                        </h3>
                    </div>


                    <form role="form" action="#" method="post">
                        <div class="box-body">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Date</label>
                                    <input class="form-control" type="date" name="c_date" value="<?php echo date('Y-m-d'); ?>" required="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Time</label>
                                    <input class="form-control" type="time" name="c_time" value="<?php echo date('H:m:s'); ?>" required="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Mode</label>
                                    <input class="form-control"  name="payment_mode" value="<?php echo $ordersdetails['order_data']->payment_mode; ?>" required="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Transection ID</label>
                                    <input type="text" class="form-control" placeholder="" name="txn_no" required="">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Remark <small>(It will be subject of email.)</small></label>
                                    <input type="text" class="form-control" placeholder="Remark for order status"  name="remark" required="" value="Your payment has been received. Thanks.">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description <small>(It will be message body of email.)</small></label>
                                    <textarea class="form-control" placeholder="Enter Message"  name="description"></textarea>
                                </div>
                            </div>

                        </div>
                        <!--/.box-body--> 

                        <div class="box-footer ">
                            <div class="col-md-12 form-group">
                                <div class="col-md-4" style="    background: #e1e1e1;">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="sendmail" checked="true">
                                            Notify to customer by mail.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-primary btn-lg" style="    font-size: 13px;" name="submit" value="submit">Submit</button>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3">
                <?php
                $this->load->view('Order/orderstatusside');
                ?>
            </div>
        </div>
</section>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-12">

            <?php
            foreach ($user_order_status as $key => $value) {
                ?>

                <ul class="timeline">
                    <!--timeline time label--> 
                    <li class="time-label">
                        <span class="bg-red">
                            <?php echo $value->c_date; ?>
                        </span>
                    </li>
                    <!--/.timeline-label--> 

                    <!--timeline item--> 
                    <li>
                        <!--timeline icon--> 
                        <i class="fa fa-envelope bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> <?php echo $value->c_time; ?></span>

                            <h3 class="timeline-header"><a href="#"><?php echo $value->status ?></a></h3>

                            <div class="timeline-body">
                                <?php echo $value->remark; ?><br/>
                                <?php echo $value->description; ?>
                            </div>

                            <div class="timeline-footer">
                                <a class="btn btn-danger btn-xs" href="<?php echo site_url('Order/remove_order_status/' . $value->id . "/" . $order_key); ?>"><i class="fa fa-trash"></i> Remove</a>
                            </div>
                        </div>
                    </li>
                    <!--END timeline item--> 

                </ul>

                <?php
            }
            ?>

        </div>
    </div>
</div>

<!-- Main content -->
<section class="content "  style="min-height: auto;">

    <div class="col-md-12">
        <!-- Table row -->
        <div class="col-md-12" style=" margin-top: 10px;">
            <article class="" style="padding: 10px;">
                <div class="row">
                    <div class="col-md-12" style="padding:5px 20px;">
                        <a class="btn btn-success pull-right" href="<?php echo site_url("order/order_pdf/" . $ordersdetails['order_data']->id) ?>"><i class="fa fa-download"></i> Download</a>
                    </div>
                </div>

                <table class="table table-bordered"  align="center" border="0" cellpadding="0" cellspacing="0"  style="background: #fff">
                    <tr>
                        <td style="font-size: 15px;width: 50%" >
                            <b style="color:#c0c0c0">Shipping Address</b><br/>
                            <span style="text-transform: capitalize;margin-top: 10px;"> 
                                <?php echo $ordersdetails['order_data']->name; ?>
                            </span> <br/>
                            <div style="    padding: 5px 0px;">
                                <?php echo $ordersdetails['order_data']->address1; ?><br/>
                                <?php echo $ordersdetails['order_data']->address2; ?><br/>
                                <?php echo $ordersdetails['order_data']->state; ?>
                                <?php echo $ordersdetails['order_data']->city; ?>

                                <?php echo $ordersdetails['order_data']->country; ?> <?php echo $ordersdetails['order_data']->zipcode; ?>

                            </div>
                            <table class="gn_table">
                                <tr>
                                    <th>Email</th>
                                    <td>: <?php echo $ordersdetails['order_data']->email; ?> </td>
                                </tr>
                                <tr>
                                    <th>Contact No.</th>
                                    <td>: <?php echo $ordersdetails['order_data']->contact_no; ?> </td>
                                </tr>
                            </table>


                        </td>
                        <td style="font-size: 15px;width: 50%" >
                            <b  style="color:#c0c0c0">Order Information</b><br/>
                            <table class="gn_table">
                                <tr>
                                    <th>Order No.</th>
                                    <td>: <?php echo $ordersdetails['order_data']->order_no; ?> </td>
                                </tr>
                                <tr>
                                    <th>Date Time</th>
                                    <td>: <?php echo $ordersdetails['order_data']->order_date; ?> <?php echo $ordersdetails['order_data']->order_time; ?>  </td>
                                </tr>
                                <tr>
                                    <th>Payment Mode</th>
                                    <td>: <?php echo $ordersdetails['order_data']->payment_mode; ?> </td>
                                </tr>
                                <tr>
                                    <th>Txn No.</th>
                                    <td>: <?php echo $payment_details['txn_id'] ? $payment_details['txn_id'] : '---'; ?> </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>: <?php
                                        if ($order_status) {
                                            echo end($order_status)->status;
                                        } else {
                                            echo "Pending";
                                        }
                                        ?> </td>
                                </tr>
                            </table>


                        </td>
                    </tr>
                </table>


                <table class="table table-bordered"  border-color= "#9E9E9E" align="center" border="1" cellpadding="0" cellspacing="0" style="background: #fff;">
                    <tr>
                        <td colspan="6">
                            <b  style="color:#c0c0c0">Order Description</b><br/>
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="width: 20px;text-align: right">S.No.</td>
                        <td colspan="2"  style="text-align: center">Product</td>

                        <td style="text-align: right;width: 100px"">Price (In <?php echo globle_currency; ?>)</td>
                        <td style="text-align: right;width: 20px"">Qnty.</td>
                        <td style="text-align: right;width: 100px">Total (In <?php echo globle_currency; ?>)</td>
                    </tr>
                    <!--cart details-->
                    <?php
                    foreach ($ordersdetails['cart_data'] as $key => $product) {
                        ?>
                        <tr>
                            <td style="text-align: right">
                                <?php echo $key + 1; ?>
                            </td>

                            <td style="width: 80px">
                        <center>   
                            <img src=" <?php echo $product->file_name; ?>" style="height: 70px;"/>
                        </center>
                        </td>

                        <td style="width: 200px;">

                            <?php echo $product->title; ?> - <?php echo $product->item_name; ?>
                            <br/>
                            <small style="font-size: 15px;">(<?php echo $product->sku; ?>)</small>

                            <h4 class="panel-title">
                                <a role="button" class="btn btn-xs btn-default" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $product->id; ?>" aria-expanded="true" aria-controls="collapseOne">
                                    View Summary
                                </a>
                            </h4>
                            </div>
                            <div id="collapse<?php echo $product->id; ?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body" style="padding:10px 0px;">
                                    <?php
                                    echo "<ul class='list-group'>";
                                    foreach ($product->custom_dict as $key => $value) {
                                        echo "<li class='list-group-item'>$key <span class='badge'>$value</span></li>";
                                    }
                                    echo "</ul>";
                                    ?>                                            </div>
                            </div>


                        </td>

                        <td style="text-align: right">
                            <?php echo $product->price; ?>
                        </td>

                        <td style="text-align: right">
                            <?php echo $product->quantity; ?>
                        </td>

                        <td style="text-align: right;">
                            <?php echo $product->total_price; ?>
                        </td>
                        </tr>

                        <?php
                    }
                    ?>



                    <td colspan="7">
                        Measurement Type:
                        <?php
                        echo $ordersdetails['order_data']->measurement_style;
                        if (count($ordersdetails['measurements_items'])) {
                            ?>
                            <a role="button" class="btn btn-xs btn-default" data-toggle="collapse" data-parent="#accordion" href="#collapsemeasurements" aria-expanded="true" aria-controls="collapseOne">
                                View Measurement
                            </a>
                            <div id="collapsemeasurements" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="panel-body" style="padding:10px 0px;">
                                            <?php
                                            echo "<ul class='list-group'>";
                                            foreach ($ordersdetails['measurements_items'] as $keym => $valuem) {
                                                $mvalues = explode(" ", $valuem['measurement_value']);
                                                echo "<li class='list-group-item'>" . $valuem['measurement_key'] . " <span class='measurement_right_text'><span class='measurement_text'>" . $mvalues[0] . "</span><span class='fr_value'>" . $mvalues[1] . '"' . "</span></span></li>";
                                            }
                                            echo "</ul>";
                                            ?>                             
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <?php
                        }
                        ?>
                    </td>


                    <!--end of cart details-->
                    <tr>
                        <td colspan="7">
                            <?php
                            $order_status = $ordersdetails['order_status'];
                            $laststatus = "";
                            $laststatus_cdate = "";
                            $laststatus_ctime = "";
                            $laststatusremark = "";
                            foreach ($order_status as $key => $value) {
                                $laststatus = $value->status;
                                $laststatus_cdate = $value->c_date;
                                $laststatus_ctime = $value->c_time;
                                $laststatusremark = $value->remark;
                            }
                            ?>



<!--                                        <button class="btn btn-button pull-right" type="button" data-toggle="collapse" data-target="#collapseProduct<?php echo $product->id; ?>" aria-expanded="false" aria-controls="collapseProduct<?php echo $product->id; ?>">
                                            Show More  <i class="fa fa-arrow-down"></i>
                                        </button>-->

                            <div class="statusdiv">
                                Current Status: <?php echo $laststatus; ?>
                                <p style="font-size: 10px;    margin: 0;">
                                    <i class="fa fa-calendar"></i> 
                                    <?php echo $laststatus_cdate; ?>
                                    <?php echo $laststatus_ctime; ?>
                                </p>

                                <p style="font-size: 15px;    margin: 0;">
                                    <?php echo $laststatusremark; ?>
                                </p>
                            </div>






                            <div class="collapse" id="collapseProduct<?php echo $product->id; ?>">
                                <div class="">
                                    <?php
                                    foreach ($product->product_status as $key => $value) {
                                        ?>
                                        <div class="productStatusBlock">
                                            <p style="font-size: 10px;margin: 0;"><i class="fa fa-calendar"></i> <?php echo $value->c_date ?> <?php echo $value->c_time ?></p>
                                            <h3><?php echo $value->status; ?></h3>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>



                        </td>
                    </tr>

                    <tr>
                        <td colspan="3"  rowspan="4" style="font-size: 12px">
                            <b>Total Amount in Words:</b><br/>
                            <span style="text-transform: capitalize">
                                <span style="text-transform: capitalize"> <?php echo $ordersdetails['order_data']->amount_in_word; ?></span>

                            </span>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right">Sub Total</td>
                        <td style="text-align: right;width: 60px">{{"<?php echo $ordersdetails['order_data']->sub_total_price; ?>"|currency:"<?php echo globle_currency; ?> "}} </td>
                    </tr>
<!--                                <tr>
                        <td colspan="2" style="text-align: right">Credit Used</td>
                        <td style="text-align: right;width: 60px"><?php echo $ordersdetails['order_data']->credit_price; ?> </td>
                    </tr>-->
                    <tr>
                        <td colspan="2" style="text-align: right">Total Amount</td>
                        <td style="text-align: right;width: 60px">{{"<?php echo $ordersdetails['order_data']->total_price; ?>"|currency:"<?php echo globle_currency; ?> "}} </td>
                    </tr>




                </table>
            </article>
        </div>
        <!-- /.row -->


        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <!--        <div class="row no-print">
                    <div class="col-xs-12">
                        <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                        <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
                        </button>
                                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                        <i class="fa fa-download"></i> Generate PDF
                                    </button>
                    </div>
                </div>-->

    </div>

</section>
<!-- /.content -->
<div class="clearfix"></div>








<?php
$this->load->view('layout/layoutFooter');
?> 