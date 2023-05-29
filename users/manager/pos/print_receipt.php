<?php 
    session_start();

    require_once ('../../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

?>

<!DOCTYPE html>
<html lang="en">

    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manager Panel: <?=$_SESSION['system_info']['name'];?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo base_url; ?>plugins/fontawesome-free/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url; ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?php echo base_url; ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    
    <!-- Toastr -->
    <link rel="stylesheet" href="<?php echo base_url; ?>plugins/toastr/toastr.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/adminlte.min.css">

    <!-- <style>
        .padding-max {
            padding-left: 800px;
        }
    </style> -->
    
    <script>
        var _base_url_ = '<?php echo base_url ?>';
    </script>

    </head>
<body class="hold-transition sidebar-mini layout-navbar-fixed">
    <?php 
        if(isset($_SESSION['receipt_key'])) {
            $cart_unique_key = $_SESSION['receipt_key'];
            $sql_transaction = "SELECT * FROM `sales_transactions_2` WHERE session_id='$cart_unique_key' LIMIT 1";

            $result = mysqli_query($conn, $sql_transaction);

            if(mysqli_num_rows($result) > 0)
            {
                while($row = mysqli_fetch_array($result))
                {
                    $customer_name = $row['customer_name'];
                    $customer_contact_number = $row['customer_contact_number'];
                    $customer_home_address = $row['customer_home_address'];
                    $transaction_number = $row['transaction_number'];
                    $total_amount = $row['total_amount'];
                    $amount_rendered = $row['amount_rendered'];
                    $changed = $row['changed'];
                    $discount_pct = $row['discount_pct'];
                    $vat_pct = $row['vat_pct'];
                    $delivery_fee = $row['delivery_fee'];
                    $overall_total = $row['overall_total'];
                }
            }
    ?>
            <div class="wrapper d-flex justify-content-center p-2">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <div class="container border border-dark px-5" id="print_receipt">
                                <div class="row text-center p-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <h2 class="text-bold" style="text-align: center;"><?=$_SESSION['system_info']['name'] ?></h2>
                                        <p style="text-align: center;"><?=$_SESSION['system_info']['address'] ?></p>
                                        <p style="text-align: center;"><?=$_SESSION['system_info']['email'] ?></p>
                                        <p style="text-align: center;"><?=$_SESSION['system_info']['mobile'] ?></p>
                                    </div>
                                </div>

                                <hr style="border-top: dotted 2px;"  />

                                <div class="row text-left mt-5">
                                    <div class="col-lg-12 col-md-9 col-sm-9">
                                        <div style="margin-bottom: 14px;">
                                            <span class="text-bold">Customer Name: </span> <br><?=$customer_name;?>
                                        </div>
                                        <div class="mt-2" style="margin-bottom: 14px;">
                                            <span class="text-bold">Home Address: </span> <br><?=$customer_home_address;?>
                                        </div>
                                        <div class="mt-2" style="margin-bottom: 14px;">
                                            <span class="text-bold">Contact Number: </span> <br><?=$customer_contact_number;?>
                                        </div>
                                    </div>  

                                    <div class="col-lg-12 col-md-3 col-sm-3">
                                        <div class="mt-2" style="margin-bottom: 14px;">
                                            <span class="text-bold">DATE: </span> <br><?=date('Y-m-d H:i:s');?>
                                        </div>
                                        <div class="mt-2" style="margin-bottom: 14px;">
                                            <span class="text-bold">TRANSACTION #: </span> <br><?=$transaction_number;?>
                                        </div>
                                    </div>
                                </div>

                                <hr style="border-top: dotted 2px;" />

                                <div class="row text-center mt-5 text-bold">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left" style="margin-bottom: 6px;">
                                        ITEM/S:
                                    </div>
                                    <!-- <div class="col-lg-4 col-md-4 col-sm-4 text-center">
                                        QUANTITY
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                        AMOUNT
                                    </div> -->
                                </div>

                                <?php 
                                    $sql_sti = "SELECT st2i.session_id, st2i.product_id, p.name, p.retail_price, st2i.quantity FROM sales_transactions_2_items st2i JOIN products p ON st2i.product_id = p.id WHERE st2i.session_id='$cart_unique_key'";

                                    $result_i = mysqli_query($conn, $sql_sti);
                        
                                    if(mysqli_num_rows($result_i) > 0)
                                    {
                                        while($items = mysqli_fetch_array($result_i))
                                        {
                                ?>
                                            <div class="row text-center mt-1" style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                                <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                                    <?=$items['name'];?> x <?=$items['quantity'];?> 
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                                ₱<?=number_format($items['retail_price'] * $items['quantity'],2);?>
                                                </div>
                                            </div>      
                                <?php
                                        }
                                    }
                                ?>

                                    <hr style="border-top: dotted 2px;" />

                                <div class="row text-center mt-5" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        SUBTOTAL
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?php
                                            echo number_format($total_amount, 2);
                                        ?>
                                    </div>
                                </div>
                                <div class="row text-center mt-3" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        VAT (<?=$vat_pct;?>%)
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?php
                                        if($vat_pct > 0) {
                                            $vat_amount = $total_amount * ($vat_pct / 100);
                                        } else {
                                            $vat_amount = 0;
                                        }

                                        echo number_format($vat_amount,2);
                                    ?>
                                    </div>
                                </div>
                                <div class="row text-center mt-3" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        DISCOUNT (<?=$discount_pct;?>%)
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?php
                                            if($discount_pct > 0) {
                                                $discount_amount = $total_amount * ($discount_pct / 100);
                                            } else {
                                                $discount_amount = 0;
                                            }

                                            echo number_format($discount_amount,2);
                                        ?>
                                    </div>
                                </div>
                                <div class="row text-center mt-3" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        DELIVERY FEE
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?php
                                            if($delivery_fee > 0) {
                                                $delivery_fee_amount = $delivery_fee;
                                            } else {
                                                $delivery_fee_amount = 0;
                                            }

                                            echo number_format($delivery_fee_amount,2);
                                        ?>
                                    </div>
                                </div>
                                <div class="row text-center mt-5" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        TOTAL
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?php
                                            $total_with_vat = $overall_total;
                                            echo number_format($total_with_vat, 2);
                                        ?>
                                    </div>
                                </div>
        
                                <div class="row text-center mt-3" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        CASH
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?=number_format($amount_rendered,2);?>
                                    </div>
                                </div>
                                <div class="row text-center mt-3" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        CHANGE
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?=number_format($changed,2);?>
                                    </div>
                                </div>
                                <br>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php 
        }
    ?>

<!-- jQuery -->
<script src="<?php echo base_url; ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


<!-- DataTables  & Plugins -->
<script src="<?php echo base_url; ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url; ?>plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url; ?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url; ?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url; ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- AdminLTE<?php echo base_url; ?> -->
<script src="<?php echo base_url; ?>dist/js/adminlte.js"></script>

<!-- OPTIONAL<?php echo base_url; ?> SCRIPTS -->
<script src="<?php echo base_url; ?>plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE<?php echo base_url; ?> for demo purposes -->
<!-- <script <?php echo base_url; ?>src="dist/js/demo.js"></script> -->

<!-- AdminLTE<?php echo base_url; ?> dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url; ?>dist/js/pages/dashboard3.js"></script>

<!-- SweetAlert2 -->
<script src="<?php echo base_url ?>plugins/sweetalert2/sweetalert2.min.js"></script>

<!-- Toastr -->
<script src="<?php echo base_url ?>plugins/toastr/toastr.min.js"></script>

<!-- select2 -->
<script src="<?php echo base_url; ?>plugins/select2/js/select2.min.js"></script>

<!-- TableCheckAll -->
<script src="<?php echo base_url; ?>plugins/TableCheckAll/TableCheckAll.js"></script>

 
<!-- datatables-checkboxes -->
<script src="<?php echo base_url; ?>plugins/datatables-checkboxes/js/dataTables.checkboxes.min.js"></script>

<script>
    $(document).ready(function() {
        printPageArea('print_receipt');
    })

    function printPageArea(areaID) {
        var printContent = document.getElementById(areaID).innerHTML;
        var originalContent = document.body.innerHTML;
        var originalContentWithStyles = document.documentElement.innerHTML;

        console.log(originalContent);

        window.document.write(originalContent);
        // document.body.innerHTML = originalContent;
        window.print();
        document.body.innerHTML = originalContentWithStyles;
        window.close();
    }
</script>
</body>
</html>
