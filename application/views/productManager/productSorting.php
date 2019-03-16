<?php
$this->load->view('layout/layoutTop');
?>
<style>
    .page_navigation a {
        padding: 5px 10px;
        border: 1px solid #000;
        margin: 5px;
        background: #000;
        color: white;
    }
    .page_navigation a.active_page {
        padding: 5px 10px;
        border: 1px solid #000;
        margin: 5px;
        background: #fff;
        color: black;
    }
</style>
<!-- Main content -->
<section class="content" ng-controller="category_controller">
    <div class="row">

        <!--list of category-->
        <div class='col-md-3'>
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Categories</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-12">
                        <div id="using_json_2" class="demo">
                        </div>
                    </div>

                </div>

            </div>


            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Bulk Change</h3>
                </div>
                <div class="box-body">
                    <form method="post" action="#">
                        <input type="hidden" name="category_id" value="{{selectedCategory2.selected_category}}">
                        <div class="row">
                            <div class="col-sm-6 col-md-4 productitem" style="padding-bottom: 10px;" id="{{product.id}}" atrindex ="{{$index}}" atrd_index ="{{product.display_index}}" ng-repeat="product in productdata.products| filter : {
                                    checked:'true'
                                    }">
                                <span class="badge"> {{product.title}}</span>
                                <input type="hidden" name="product_id[]" value="{{product.id}}">
                            </div>
                        </div>
                        {{selectedCategory2.category_string}}
                        <br/>

                        <div class="btn-group btn-group-sm" role="group" aria-label="...">
                            <button type="button" class="btn  btn-default" data-toggle="modal" data-target="#categorymodel" ng-click="changeCategory(product)">Change Category</button>
                            <button type="submit" name="apply_category" value="apply" class="btn btn-default">Apply</button>
                        </div>
                    </form>

                </div>
            </div>





        </div>
        <!--end of list category-->




        <div class="col-md-9">

            <div class="nav-tabs-custom">

                <ul class="nav nav-tabs">
                    <li class="pull-left header"><i class="fa fa-th"></i>{{selectedCategory.category_string}}</li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            Show Products <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Published</a></li>
                            <li role="presentation" class="divider"></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Unpublished</a></li>
                        </ul>
                    </li>
                    <li class="pull-right">

                        <div class="input-group pull-right" style="width: 300px;    margin-top: 2px;">
                            <span class="input-group-addon" id="basic-addon1">Search Product</span>
                            <input type="text" class="form-control" placeholder="Type hear..." ng-model="searchproduct">
                        </div>
                    </li>
                </ul>
                <div class="tab-content">

                    <div class="row" id="sortable">
                        <div class="col-sm-6 col-md-3 productitem" id="{{product.id}}" atrindex ="{{$index}}" atrd_index ="{{product.display_index}}" ng-repeat="product in productdata.products| filter : {
                                    title:searchproduct
                                    }">
                            <div class="thumbnail">
                                <img src="{{product.image}}" alt="..." style="height: 150px">
                                <div class="caption">
                                    <h3>{{product.title}}</h3>
                                    <p>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" ng-click="changeIndex(product)">Set Index</button>
                                        </span>
                                        <input type="text" class="form-control" ng-model="product.display_index">
                                        <span class="input-group-addon">
                                            <input type="checkbox" aria-label="..." ng-model="product.checked">
                                        </span>

                                    </div><!-- /input-group -->
                                    </p>
                                    <p>
                                   
                                    <div class="btn-group btn-group-sm" style="width: 100%">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-block" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><button class="btn  btn-default" data-toggle="modal" data-target="#categorymodel" ng-click="changeCategory(product)">Change Category</button></li>

                                            <li role="separator" class="divider"></li>
                                            <li><button class="btn btn-default" ng-if="product.status == 1" ng-click="publishing(product, 0)">Unpublish</button></li>
                                            <li><button class="btn  btn-default" ng-if="product.status == 0" ng-click="publishing(product, 1)">>Publish</button></li>

                                        </ul>
                                    </div>



                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--            <div class="col-md-12" id="paging_container12">
                                    <div class="showing-info">
                                        <p class="text-center"><span class="info_text ">Showing {0}-{1} of {2} results</span></p>
                                    </div>
                    
                                     Item 
                                    <div class="col-sm-4 animated zoomIn"  ng-repeat="k in productdata.productscounter">
                                        {{k}}
                                    </div>
                    
                                    <center>
                                        <div class="page_navigation"></div>
                                    </center>
                                    <div style="clear: both"></div>
                                </div>-->
                </div>
                <!-- /.tab-content -->
            </div>

            <div class="modal fade" id="categorymodel" tabindex="-1" role="dialog" >
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">

                        <div class="box box-danger">
                            <div class="box-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h3 class="box-title">Change Categories</h3>
                            </div>
                            <div class="box-body">
                                <div class="col-md-4">
                                    <div id="using_json_3" class="demo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>






        </div>



    </div>
</section>
<!-- end col-6 -->
</div>



<?php
$this->load->view('layout/layoutFooter');
?> 

<script src="<?php echo base_url(); ?>assets_main/dist/jquery.pajinate.min.js"></script>

<script>
                                                var jsondata;
                                                var selectedcategory;
                                                HASALE.controller('category_controller', function ($scope, $http, $filter, $timeout) {
                                                    $scope.selectedCategory = {"selected": {}, "parents": [],
                                                        "category_string": "Main Category",
                                                        "category_string2": "Main Category",
                                                        "category": {'parent_id': '0', 'category_name': '', 'description': '', 'id': ''},
                                                        "operation": "Add Category",
                                                        "selected_category": 0
                                                    };
                                                    var url = "<?php echo base_url(); ?>index.php/ProductManager/category_api";
                                                    $http.get(url).then(function (rdata) {
                                                        $scope.categorydata = rdata.data;
                                                        $('#using_json_2').jstree({'core': {
                                                                'data': $scope.categorydata.tree
                                                            }});
                                                        $('#using_json_3').jstree({'core': {
                                                                'data': $scope.categorydata.tree
                                                            }});
                                                    })

                                                    $scope.resetData = function () {
                                                        $scope.selectedCategory.operation = "Add Category";
                                                        $scope.selectedCategory.category.parent_id = '0';
                                                        $scope.selectedCategory.category.category_name = '';
                                                        $scope.selectedCategory.category.description = '';
                                                    }

                                                    $scope.cencel = function () {
                                                        $scope.resetData();
                                                    }

                                                    $(document).on("click", "#using_json_2 [selectcategory]", function (event) {
                                                        var catid = $(this).attr("selectcategory");
                                                        var objdata = $('#using_json_2').jstree('get_node', catid);
                                                        var catlist = objdata.parents;
                                                        $scope.selectedCategory.selected_category = catid;
                                                        $scope.getProducts(catid);
                                                        $scope.resetData();
                                                        $timeout(function () {
                                                            $scope.selectedCategory.selected = objdata;
                                                            var catsst = [];
                                                            for (i = catlist.length + 1; i >= 0; i--) {
                                                                var catid = catlist[i];
                                                                var catstr = $scope.categorydata.list[catid];
                                                                if (catstr) {
                                                                    catsst.push(catstr.text);
                                                                }
                                                            }
                                                            catsst.push(objdata.text);
                                                            $("#parent_id").val(objdata.id);
                                                            $scope.selectedCategory.category_string = catsst.join("->")
                                                        }, 100)
                                                    })

                                                    //edit data
                                                    $scope.editData = function () {
                                                        console.log($scope.selectedCategory.selected.id);
                                                        $scope.selectedCategory.operation = "Edit";
                                                        var cobj = $scope.categorydata.list[$scope.selectedCategory.selected.id];
                                                        $scope.selectedCategory.category.parent_id = cobj.id;
                                                        $scope.selectedCategory.category.category_name = cobj.text;
                                                        $scope.selectedCategory.category.description = cobj.description;
                                                    }
                                                    //edit data



                                                    //delete data
                                                    $scope.deleteData = function (cateid) {
                                                        var url = "<?php echo base_url(); ?>index.php/ProductManager/categorie_delete/" + cateid;
                                                        $http.get(url).then(function (rdata) {
                                                            window.location.reload();
                                                        })
                                                    }
                                                    //end of delete data
                                                    $scope.selectedCategory2 = {"selected": {}, "parents": [],
                                                        "category_string": "Main Category",
                                                        "category_string2": "Main Category",
                                                        "category": {'parent_id': '0', 'category_name': '', 'description': '', 'id': ''},
                                                        "operation": "Add Category",
                                                        "selected_category": 0
                                                    };
                                                    $(document).on("click", "#using_json_3 [selectcategory]", function (event) {
                                                        var catid = $(this).attr("selectcategory");
                                                        var objdata = $('#using_json_3').jstree('get_node', catid);
                                                        var catlist = objdata.parents;
                                                        $scope.selectedCategory2.selected_category = catid;
                                                        $("#categorymodel").modal("hide");
                                                        $timeout(function () {
                                                            $scope.selectedCategory2.selected = objdata;
                                                            var catsst = [];
                                                            for (i = catlist.length + 1; i >= 0; i--) {
                                                                var catid = catlist[i];
                                                                var catstr = $scope.categorydata.list[catid];
                                                                if (catstr) {
                                                                    catsst.push(catstr.text);
                                                                }
                                                            }
                                                            catsst.push(objdata.text);
                                                            $("#parent_id").val(objdata.id);
                                                            $scope.selectedCategory2.category_string = catsst.join("->")
                                                        }, 100)
                                                    })

                                                    $scope.productdata = {'products': [], 'product_count': 0, 'productscounter': [], 'selectedproduct': {}, };
                                                    $scope.getProducts = function (category_id) {
                                                        var url = "<?php echo base_url(); ?>index.php/ProductManager/productSortingApi/" + category_id;
                                                        $http.get(url).then(function (rdata) {
                                                            $scope.productdata.products = rdata.data.products;
                                                            $scope.productdata.product_count = rdata.data.total_products;
                                                            var totalcountdata = rdata.data.total_products;
                                                            var productscounter = [];
                                                            for (i = 1; i <= totalcountdata; i++) {
                                                                productscounter.push(i);
                                                            }

                                                            $scope.productdata.productscounter = productscounter;
                                                            $timeout(function () {
                                                                $('#paging_container12').pajinate({
                                                                    items_per_page: 12,
                                                                    num_page_links_to_display: 5,
                                                                });
                                                            }, 1500)



                                                        });
                                                    }

                                                    $scope.getProducts($scope.selectedCategory.selected_category)


                                                    $scope.changeIndex = function (product) {
                                                        var cindex = product.display_index;
                                                        var ids = product.id;
                                                        var url = "<?php echo base_url(); ?>index.php/ProductManager/productSortingChangeIndexApi/" + ids + "/" + cindex;
                                                        $http.get(url).then(function (rdata) {
                                                            $scope.getProducts($scope.selectedCategory.selected_category)
                                                        })
                                                    }

                                                    $scope.publishing = function (product, status) {
                                                        var status = status;
                                                        var ids = product.id;
                                                        var url = "<?php echo base_url(); ?>index.php/ProductManager/productSortingChangeStatusApi/" + ids + "/" + status;
                                                        $http.get(url).then(function (rdata) {
                                                            $scope.getProducts($scope.selectedCategory.selected_category)
                                                        })
                                                    }




                                                })


</script>

