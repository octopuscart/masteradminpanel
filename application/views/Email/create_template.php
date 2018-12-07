<?php
$this->load->view('layout/layoutTop');
?>
<!-- Main content -->
<section class="content">
    <div class="row">

        <!-- /.col -->


        <div class="col-md-12">
            <?php
            if ($mailstatus) {
                ?>
                <div class="alert alert-success" role="alert"> <strong>Well done!</strong> <?php echo $mailstatus; ?> </div>
                <?php
            }
            ?>
            <div class="col-md-3">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $contactlist->name; ?> - <?php echo $contactlist->member_count; ?> Contact(s)</h3>

                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="nav nav-pills">
                            <li><a href="<?php echo site_url("Messages/createTemplate/".$listid."/1"); ?>"><i class="fa fa-circle-o text-red"></i> News Letter</a></li>
                            <li><a href="<?php echo site_url("Messages/createTemplate/".$listid."/2"); ?>"><i class="fa fa-circle-o text-yellow"></i> Offer Template</a></li>
                            <li><a href="<?php echo site_url("Messages/createTemplate/".$listid."/3"); ?>"><i class="fa fa-circle-o text-light-blue"></i> Event Template</a></li>
                        </ul>
                    </div>

                    <table id="tableDataOrder1" class="table table-bordered table-striped">
                        <thead>
                            <tr>

                                <th style="width: 100px;">Email </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($contactdata as $mkey => $mvalue) {
                                foreach ($mvalue as $mk => $mv) {
                                    $emailadd = $mv['email_address'];
                                    if (strpos($emailadd, "@") > -1) {
                                        echo "<tr><td>";
                                        echo $emailadd;
                                        echo "</td></tr>";
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>


                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <form action="" method="post">
                        <div class="box-header with-border">
                            <h3 class="box-title">Compose New Message</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <div class="form-group">
                                <input class="form-control" placeholder="Subject:" name="subject" required="" value="<?php
                            switch ($lattertype) {
                                case "1":
                                    echo email_sender_name. " Newsletter";
                                    break;
                                case "2":
                                    echo email_sender_name." Offers";
                                    break;
                                case "3":
                                    echo email_sender_name." Appointment";
                                    break;
                                default:
                                    echo email_sender_name." Newsletter";
                            }
                            ?>">
                            </div>
                            <div class="form-group">
                                <textarea id="compose-textarea" name="emailtemplate" class="form-control" style="height: 200px">
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

                        </div>
                        <!-- /.box-body -->



                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="button" class="btn btn-default btn-lg"><i class="fa fa-pencil"></i> Draft</button>
                                <button type="submit" name="sendmail" value="sendmail" class="btn btn-primary btn-lg"><i class="fa fa-envelope-o"></i> Send</button>
                            </div>
                            <button type="reset" class="btn btn-default  btn-lg"><i class="fa fa-times"></i> Discard</button>
                        </div>
                    </form>
                    <!-- /.box-footer -->
                </div>
                <!-- /. box -->
            </div>
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