<?php
$this->load->view('layout/layoutTop');
?>

<style>
    .item_headers{
        background: teal;
        color:white;
    }
    .items_row{
        border-left: 1px solid #008080;
        border-right: 1px solid #008080;
    }
    tr.items_row:last-child {
        border-bottom: 1px solid #008080;
    }

</style>

<!-- Main content -->
<section class="content" ng-controller="category_controller">
    <div class="row">
<div class='col-md-12'>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-warning"></i> Alert!</h4>
            Defining category items price will effect to products price, need attention to operate this page. 
        </div>

</div>
        <!--add category-->
        <div class='col-md-4'>
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Create Price Category</h3>
                </div>
                <div class="box-body">


                    <form action="#" method="post" >

                        <div class="form-group">
                            <label for="">Category Name</label>
                            <input  type="text" class="form-control" name="category_name" id="category_name"  placeholder="Category Name" ng-model="selectedCategory.category.category_name">
                        </div>


                        <p style='font-weight: 400'>Select items thats can be made with this category</p>
                        <?php
                        foreach ($custome_items as $cikey => $civalue) {
                            ?>
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="form-group">
                                    <div class="checkbox col-sm-4">
                                        <label style="    font-size: 19px;line-height: 23px;">
                                            <input value='<?php echo $civalue->id; ?>' type="checkbox" name='item_id[]' ng-model="item_model_<?php echo $civalue->id; ?>" ng-init="item_model_<?php echo $civalue->id; ?> = false"> <?php echo $civalue->item_name; ?>
                                        </label>
                                    </div>

                                    <div class="col-sm-8" style="    padding-top: 5px;">

                                        <input class="form-control" name="item_price[]" ng-if='item_model_<?php echo $civalue->id; ?> == true' >
                                        <input class="form-control" disabled="" ng-if='item_model_<?php echo $civalue->id; ?> == false' >
                                    </div>
                                </div>
                            </div>


                            <!--                            <div class="form-group">
                                                            <label for=""><?php echo $civalue->item_name; ?></label>
                                                            <input  type="text" class="form-control" name="category_name" id="category_name"  placeholder="Category Name" ng-model="selectedCategory.category.category_name">
                                                        </div>-->
                            <?php
                        }
                        ?>


                        <button id='submit_button' type="submit" name="submit" class="btn btn-primary" value="{{selectedCategory.operation}}"><i class='fa fa-edit'></i>  {{selectedCategory.operation}}</button>
                        <button id='submit_button' type="button"  class="btn btn-warning" ng-click="cencel()">Cancel</button>

                    </form>
                </div>
            </div>
        </div>
        <!--end of add category-->



        <!--add category-->
        <div class='col-md-8'>
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Price Category</h3>
                </div>
                <div class="box-body">
                    <table class='table' style='    font-size: 17px;'>
                        <?php
                        foreach ($category_items as $key => $value) {
                            ?>
                            <tr class="item_headers items_row">
                                <th colspan="2">
                                    <?php echo $value->category_name; ?>
                                </th>
                                <td>
                                    <form action='#' method="post" class='pull-right'>
                                        <input style='    width: 200px;
                                               color: #000;' name='category_name' value='<?php echo $value->category_name; ?>' style='width: 200px;'>
                                        <input type='hidden' name='category_id' value='<?php echo $value->id; ?>'>
                                        <button type='submit' class='btn btn-warning btn-sm' name='update_category' value='<?php echo $cvalue->id; ?>' style='    margin-top: -4px;'><i class='fa fa-edit'></i> Update</button>
                                        <button type='submit' class='btn btn-danger btn-sm' name='delete_category' value='<?php echo $cvalue->id; ?>' style='    margin-top: -4px;'><i class='fa fa-trash'></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                            $category_prices = $value->prices;
                            if ($category_prices) {
                                foreach ($category_prices as $ckey => $cvalue) {
                                    ?>
                                    <tr class='items_row'>
                                        <td> <?php echo $cvalue->item_name; ?></td>
                                        <td><?php echo $cvalue->price; ?></td>
                                        <td>
                                            <form action='#' method="post">
                                                <input  name='item_price' value='<?php echo $cvalue->price; ?>' style='width: 100px;'>
                                                <input type='hidden' name='item_id' value='<?php echo $cvalue->id; ?>'>
                                                <button type='submit' class='btn btn-warning btn-sm' name='update_price' value='<?php echo $cvalue->id; ?>' style='    margin-top: -4px;'><i class='fa fa-edit'></i> Update</button>
                                                <button type='submit' class='btn btn-danger btn-sm' name='delete_price' value='<?php echo $cvalue->id; ?>' style='    margin-top: -4px;'><i class='fa fa-trash'></i> Delete</button>
                                            </form>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td class='colspan4'>----</td></tr>";
                            }
                            ?>

                            <?php
                        }
                        ?>
                    </table>

                </div>
            </div>
        </div>
        <!--end of add category-->

    </div>
</section>
<!-- end col-6 -->
</div>


<?php
$this->load->view('layout/layoutFooter');
?> 

<script>
    var jsondata;
    var selectedcategory;

    HASALE.controller('category_controller', function ($scope, $http, $filter, $timeout) {
        $scope.selectedCategory = {
            "selected": {}, "parents": [],
            "category_string": "Main Category",
            "category": {'parent_id': '0', 'category_name': '', 'description': '', 'id': ''},
            "operation": "Add Category"
        };



    })


</script>