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
                        <h3 class="box-title">Choose Template</h3>

                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="nav nav-pills">
                            <li><a href="<?php echo site_url("Messages/sendMailThirdParty/" . $list_id . "/1"); ?>"><i class="fa fa-circle-o text-red"></i> News Letter</a></li>
                            <li><a href="<?php echo site_url("Messages/sendMailThirdParty/" . $list_id . "/2"); ?>"><i class="fa fa-circle-o text-yellow"></i> Offer Template</a></li>
                            <li><a href="<?php echo site_url("Messages/sendMailThirdParty/" . $list_id . "/3"); ?>"><i class="fa fa-circle-o text-light-blue"></i> Event Template</a></li>
                        </ul>
                    </div>
                </div>

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $mailerobj->name; ?></h3>

                    </div>
                    <div class="box-body ">
                        <h2>
                            <small>Total</small><br/>
                            <?php echo count($contactlist); ?> Contact(s)                            
                        </h2>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addContact" ng-click="contactSelect('<?php echo $mid; ?>', '<?php echo $mname; ?>')">
                            <i class="fa fa-plus"></i> Add Contact
                        </button>
                    </div>
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
                                        echo email_sender_name . " Wishing You Merry Christmas and Happy New Year!";
                                        break;
                                    case "2":
                                        echo email_sender_name . " Wishing You Merry Christmas and Happy New Year!";
                                        break;
                                    case "3":
                                        echo email_sender_name . " Appointment";
                                        break;
                                    default:
                                        echo email_sender_name . " Newsletter";
                                }
                                ?>">
                            </div>
                            <div class="form-group">
                                <textarea id="compose-textarea" name="emailtemplate" class="form-control" style="height: 200px"><?php
                                    switch ($lattertype) {
                                        case "1":
                                            echo $html = $this->load->view('mailtemplate/template3', $order_details, true);

                                            break;
                                        case "2":
                                            echo $html = $this->load->view('mailtemplate/template2', $order_details, true);

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
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title">Contact List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tableDataOrder" class="table table-bordered table-striped">
                            <thead>
                                <tr>

                                    <th style="width: 100px;">Email </th>
                                    <th style="width: 100px;">Full Name </th>
                                    <th style="width: 100px;">First Name </th>
                                    <th style="width: 100px;">Last Name </th>
                                    <th style="width: 100px;">Status </th>
                                    <th style="width: 70px;"> </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($contactlist as $mkey => $mvalue) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $mvalue['email']; ?>
                                        </td>
                                        <td>
                                            <?php echo $mvalue['full_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $mvalue['first_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $mvalue['last_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $mvalue['status']; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url("Messages/removeContactFromList/$list_id/".$mvalue['id']."/".$lattertype);?>" class="btn btn-danger">
                                                <i class="fa fa-trash"></i> Remove
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addContact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="#" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add New Contact - <?php echo $mailerobj->name; ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label >Email Addresss</label>
                            <input type="email"  required="" class="form-control" name="email_address"  aria-describedby="emailHelp" placeholder="">
                            <input type="hidden" name="listid" value="<?php echo $list_id; ?>">
                        </div>
                        <div class="form-group">
                            <label >First Name</label>
                            <input type="text"  class="form-control" name="first_name"  aria-describedby="emailHelp" placeholder="">
                        </div>
                        <div class="form-group">
                            <label >Last Name</label>
                            <input type="text" class="form-control" name="last_name"  aria-describedby="emailHelp" placeholder="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" name="addcontact" class="btn btn-primary">Save Contact</button>
                    </div>
                </form>
            </div>

        </div>
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