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
<section class="content" ng-controller="contactlistController">
    <div class="row">

        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Choose Contact Group</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">

                </div>
            </div>

            <div class="box box-primary">


                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="list-group">
                        <?php
                        foreach ($contactdata as $key => $value) {
                            $mid = $value['id'];
                            $mname = $value['name'];
                            ?>

                            <li  class="list-group-item">
                                <h2 style="font-size: 17px;">
                                    <i class="fa fa-circle-o text-red"></i>
                                    <?php echo ($value['name']); ?> 
                                    <span class="small" style="font-size: 12px;float: right"><i class="fa fa-calendar"></i> <?php print_r($value['datetime']); ?></span>
                                    <br/>
                                    <span style="font-size: 12px;">
                                        Total Members:  <?php print_r($value['total_members']); ?>
                                    </span>
                                </h2>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addContact" ng-click="contactSelect('<?php echo $mid;?>', '<?php echo $mname;?>')">
                                    <i class="fa fa-plus"></i> Add Contact
                                </button>
                                <a href="<?php echo site_url("Messages/sendMailThirdParty/" . $mid . "/1"); ?>" class="btn btn-success btn-sm"><i class="fa fa-envelope"></i> Send Mail</a>
                            </li>

                            <?php
                        }
                        ?>
                    </ul>

                </div>
                <!-- /.box-body -->




                <!-- /.box-footer -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>

    <!-- /.row -->
    
<!-- Modal -->
<div class="modal fade" id="addContact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
           <form action="#" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add New Contact - {{contactDict.listname}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label >Email Addresss</label>
                            <input type="email"  required="" class="form-control" name="email_address"  aria-describedby="emailHelp" placeholder="">
                        <input type="hidden" name="listid" value="{{contactDict.listid}}">
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

    HASALE.controller('contactlistController', function ($scope, $http, $filter, $timeout) {
        $scope.contactDict = {"listid":"", "listname":""};
        $scope.contactSelect = function(listid, listname){
            console.log(listname);
            $scope.contactDict.listid = listid;
            $scope.contactDict.listname = listname;
        }


    })

</script>