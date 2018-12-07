<?php
$this->load->view('layout/layoutTop');

function userReportFunction($users) {
    ?>
    <table id="tableDataOrder" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="width: 20px;">Select</th>
                <th style="width: 20px;">S.N.</th>
                <th style="width:50px;">Image</th>
                <th style="width: 75px;">Name</th>
                <th style="width: 100px;">Email </th>
                <th style="width: 100px;">Contact No.</th>
                <th style="width: 100px;">Reg. Date/Time</th>
                <th style="width: 75px;">Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($users)) {

                $count = 1;
                foreach ($users as $key => $value) {
                    ?>
                    <tr>
                        <td><input type="checkbox" name="receivers[]"></td>
                        <td><?php echo $count; ?></td>

                        <td>
                            <?php
                            if ($value->image) {
                                ?>
                                <img src="<?php echo base_url(); ?>assets_main/userimages/<?php echo $value->image; ?>" style="height:51px;">
                                <?php
                            } else {

                                $avatar = $value->gender == 'Female' ? "avatar3" : "avatar5";
                                ?>
                                <img src="<?php echo base_url(); ?>assets_main/dist/img/<?php echo $avatar; ?>.png" style="height:51px;">

                            <?php }
                            ?>

                        </td>

                        <td>
                            <span class="">
                                <b><span class="seller_tag"><?php echo $value->first_name; ?> <?php echo $value->last_name; ?></span></b>
                                <br/>
                                <i class="fa fa-<?php echo strtolower($value->gender); ?>"></i>  <?php echo $value->gender; ?>
                                <br/>(<?php echo $value->profession ? $value->profession : '----'; ?>)
                            </span>
                        </td>

                        <td>
                            <span class="">
                                <span class="seller_tag">
                                    <?php echo $value->email; ?>
                                </span>

                            </span>
                        </td>
                        <td>
                            <span class="">

                                <?php echo $value->contact_no; ?>
                            </span>
                        </td>



                        <td>
                            <span class="">
                                <?php echo $value->registration_datetime; ?>
                            </span>
                        </td>

                        <td>
                            <a href="<?php echo '../userManager/user_details/' . $value->id; ?>" class="btn btn-danger"><i class="fa fa-eye "></i> View</a>
                        </td>
                    </tr>
                    <?php
                    $count++;
                }
            }
            ?>
        </tbody>
    </table>
    <?php
}
?>
<!-- Main content -->
<section class="content">
    <div class="row">

        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Choose Template</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills">
                        <li><a href="<?php echo site_url("Services/newslatter/1"); ?>"><i class="fa fa-circle-o text-red"></i> News Letter</a></li>
                        <li><a href="<?php echo site_url("Services/newslatter/2"); ?>"><i class="fa fa-circle-o text-yellow"></i> Offer Template</a></li>
                        <li><a href="<?php echo site_url("Services/newslatter/3"); ?>"><i class="fa fa-circle-o text-light-blue"></i> Event Template</a></li>
                    </ul>
                </div>
            </div>

            <div class="box box-primary">
                <form action="" method="post">
                    <div class="box-header with-border">
                        <h3 class="box-title">Compose New Message</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="form-group">
                            <input class="form-control" placeholder="Subject:" required="" value="<?php
                            switch ($lattertype) {
                                case "1":
                                    echo "Costcokart Newsletter";
                                    break;
                                case "2":
                                    echo "Costcokart Offers";
                                    break;
                                case "3":
                                    echo "Costcokart Appointment";
                                    break;
                                default:
                                    echo "Costcokart Newsletter";
                            }
                            ?>">
                        </div>
                        <div class="form-group">
                            <textarea id="compose-textarea" class="form-control" style="height: 200px">
                                <?php
                                switch ($lattertype) {
                                    case "1":
                                        echo $html = $this->load->view('Email/order_pdf_1', $order_details, true);

                                        break;
                                    case "2":
                                        echo $html = $this->load->view('Email/order_pdf_2', $order_details, true);

                                        break;
                                    case "3":
                                        echo $html = $this->load->view('Email/order_pdf_3', $order_details, true);

                                        break;
                                    default:
                                        echo $html = $this->load->view('Email/order_pdf_1', $order_details, true);
                                }
                                ?>
                            </textarea>
                        </div>
                        <!--                    <div class="form-group">
                                                <div class="btn btn-default btn-file">
                                                    <i class="fa fa-paperclip"></i> Attachment
                                                    <input type="file" name="attachment">
                                                </div>
                                                <p class="help-block">Max. 32MB</p>
                                            </div>-->
                        <?php userReportFunction($users_all); ?>
                    </div>
                    <!-- /.box-body -->



                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
                        </div>
                        <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
                    </div>
                </form>
                <!-- /.box-footer -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>

    <!-- /.row -->
</section>
<!-- /.content -->

<?php
$this->load->view('layout/layoutFooter');
?> 
<script src="<?php echo base_url(); ?>assets_main/tinymce/js/tinymce/tinymce.min.js"></script>


<script>
    $(function () {
        tinymce.init({selector: 'textarea', plugins: 'advlist autolink link image lists charmap print preview'});


    })


    $(function () {
        $("#daterangepicker").daterangepicker({
            format: 'YYYY-MM-DD',
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                "Today's": [moment(), moment()],
                "Yesterday's": [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'right',
            drops: 'down',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-default',
            separator: ' to ',
            locale: {
                applyLabel: 'Submit',
                cancelLabel: 'Cancel',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        }, function (start, end, label) {
            $('input[name=daterange]').val(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
        $('#tableDataOrder').DataTable({
            "language": {
                "search": "Search Order By Email, First Name, Last Name Etc."
            }
        })
    })
</script>