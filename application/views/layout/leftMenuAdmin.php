<!-- begin #sidebar -->
<?php
$session_data = $this->session->userdata('logged_in');

function checkPermission($session_data) {
    if ($session_data['user_type'] == 'Admin') {
        return "system";
    }
    if ($session_data['user_type'] == 'Manager') {
        return "system";
    }

    if ($session_data['user_type'] == 'Developer') {
        return "system";
    }

    if ($session_data['user_type'] == 'Vendor') {
        return "vendor";
    }
}
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">



                <?php if ($session_data['image']) { ?>
                    <img src="<?php echo base_url(); ?>assets_main/userimages/<?php echo $session_data['image']; ?>" class="img-circle" alt="User Image">

                    <?php
                } else {
                    ?>
                    <img src="<?php echo base_url(); ?>assets_main/dist/img/avatar5.png" class="img-circle" alt="User Image">
                <?php } ?> 

            </div>
            <div class="pull-left info">
                <p style="line-height: 20px;">
                    <?php echo $session_data['first_name'] . " " . $session_data['last_name']; ?>
                    <br/>
                    <span style="color:green;"><?php echo $session_data['user_type']; ?></span>
                </p>



            </div>
        </div>

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>




            <li class="treeview">
                <a href="#">
                    <i class="fa fa-list"></i>
                    <span>Order Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <!--Admin Access-->
                    <li>
                        <a href="<?php echo site_url('Order/orderslist'); ?>">
                            <i class="active fa fa-plus "></i> <span>Orders Report</span>
                        </a>
                    </li>   

                    <?php if (checkPermission($session_data) == 'system') { ?>
                        <!--                        <li>
                                                    <a href="<?php echo site_url('Order/orderslistvendor'); ?>">
                                                        <i class="active fa fa-plus "></i> <span>Vendor Orders Report</span>
                                                    </a>
                                                </li>  -->

                        <li>       
                            <a href="<?php echo site_url('Order/orderAnalysis') ?>">
                                <i class="active fa fa-plus "></i> <span>Order Analytics</span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php if (checkPermission($session_data) == 'vendor') { ?>
                        <li>      
                            <a href="<?php echo site_url('Order/orderAnalysisVendor') ?>">
                                <i class="active fa fa-plus "></i> <span>Order Analytics</span>
                            </a> 
                        </li> 
                        <?php
                    }
                    ?>

                    <!--end of admin access-->

                </ul>
            </li>




            <li class="treeview">
                <a href="#">
                    <i class="fa fa-archive"></i>
                    <span>Product Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li>
                        <a href="<?php echo base_url(); ?>index.php/ProductManager/add_product">
                            <i class="active fa fa-plus "></i> <span>Add Product</span>
                        </a>
                    </li>   
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/ProductManager/productReport">
                            <i class="active fa fa-plus "></i> <span>Product Reports</span>
                        </a>
                    </li>    


                    <?php if (checkPermission($session_data) == 'system') { ?>
                        <!--Admin Access-->
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/ProductManager/categories">
                                <i class="active fa fa-plus "></i> <span>Categories</span>
                            </a>
                        </li>    


                        <li>
                            <a href="<?php echo base_url(); ?>index.php/ProductManager/categoryItems">
                                <i class="active fa fa-plus "></i> <span>Items Prices</span>
                            </a>
                        </li>  
                        
                        
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/ProductManager/productSorting">
                                <i class="active fa fa-plus "></i> <span>Product Sorting</span>
                            </a>
                        </li>  

                        <?php
                        if ($session_data['user_type'] == 'Developer') {
                            ?>
                            <li>
                                <a href="<?php echo base_url(); ?>index.php/ProductManager/createAttribute">
                                    <i class="active fa fa-plus "></i> <span>Attributes</span>
                                </a>
                            </li>   
                            <?php
                        }
                        ?>
                        <!--end of admin access-->

                        <?php
                    }
                    ?>
                </ul>
            </li>



            <?php if (checkPermission($session_data) == 'system') { ?>


                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-user-circle-o"></i>
                        <span>Client Management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!--Admin Access-->

                        <li>
                            <a href="<?php echo base_url(); ?>index.php/UserManager/usersReport">
                                <i class="active fa fa-plus "></i> <span>Clients Reports</span>
                            </a>
                        </li>   
                        <!--end of admin access-->
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-envelope"></i>
                        <span>Message Book</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!--Admin Access-->

                        <li>
                            <a href="<?php echo site_url("Messages/getContactList") ?>">
                                <i class="active fa fa-plus "></i> <span>Send Mail/Newsletter (Prm.)</span>
                            </a>
                        </li>
                         <li>
                            <a href="<?php echo site_url("Messages/getContactListTxn") ?>">
                                <i class="active fa fa-plus "></i> <span>Send Mail/Newsletter (Txn.)</span>
                            </a>
                        </li>


                        <!--end of admin access-->
                    </ul>
                </li>


                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i>
                        <span>Schedule Management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!--Admin Access-->

                        <li>
                            <a href="<?php echo base_url(); ?>index.php/Services/appointment">
                                <i class="active fa fa-plus "></i> <span>Set Schedule</span>
                            </a>
                        </li>  
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/Services/appointment_report">
                                <i class="active fa fa-plus "></i> <span>Schedule Report</span>
                            </a>
                        </li>  
                        <!--end of admin access-->
                    </ul>
                </li>



                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-user"></i>
                        <span>User Management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!--Admin Access-->
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/UserManager/addManager">
                                <i class="active fa fa-plus "></i> <span>Add User</span>
                            </a>
                        </li>   
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/UserManager/usersReportManager">
                                <i class="active fa fa-plus "></i> <span>Users Reports</span>
                            </a>
                        </li>   
                        <!--end of admin access-->
                    </ul>
                </li>


                <!--                <li class="treeview">
                                    <a href="#">
                                        <i class="fa fa-money"></i>
                                        <span>Credit Management</span>
                                         <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu">
                                        credit Access
                                        <li>
                                            <a href="<?php echo base_url(); ?>index.php/UserManager/usersCreditDebit">
                                                <i class="active fa fa-plus "></i> <span>Allot Credits</span>
                                            </a>
                                        </li>    
                                        <li>
                                            <a href="<?php echo base_url(); ?>index.php/UserManager/adminDebit">
                                                <i class="active fa fa-plus "></i> <span>Debit</span>
                                            </a>
                                        </li>    
                                        end of admin access
                                    </ul>
                                </li>-->




                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cogs"></i>
                        <span>Settings</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!--Admin Access-->
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/Configuration/add_sliders">
                                <i class="active fa fa-plus "></i> <span>Add Sliders</span>
                            </a>
                        </li> 
                        <!--                        <li>
                                                    <a href="<?php echo base_url(); ?>index.php/Configuration/add_barcode">
                                                        <i class="active fa fa-plus "></i> <span>Add Barcodes</span>
                                                    </a>
                                                </li> -->
                        <!--                        <li>
                                                    <a href="<?php echo base_url(); ?>index.php/UserManager/usersReport">
                                                        <i class="active fa fa-plus "></i> <span>Users Reports</span>
                                                    </a>
                                                </li>   -->
                        <!--end of admin access-->
                    </ul>
                </li>


                <?php
            }
            ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
