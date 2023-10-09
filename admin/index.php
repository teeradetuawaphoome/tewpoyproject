<?php
session_start();
include 'condb.php';
if(!isset($_SESSION["id"]))
{
$row=header("location:login.php");
}

//รายการสั่งซื้อสินค้าที่ยังไม่ชำระเงิน
$sql1="select COUNT(orderID) AS order_no from tb_order where order_status='1' ";
$hand=mysqli_query($conn,$sql1);
$row1=mysqli_fetch_array($hand);


//รายการสั่งซื้อสินค้าที่ชำระเงินแล้ว
$sql2="select COUNT(orderID) AS order_yes from tb_order where order_status='2' ";
$hand2=mysqli_query($conn,$sql2);
$row2=mysqli_fetch_array($hand2);


//รายการสั่งซื้อสินค้าที่ยกเลิกใบสั่งซื้อ
$sql3="select COUNT(orderID) AS order_cancle from tb_order where order_status='0' ";
$hand3=mysqli_query($conn,$sql3);
$row3=mysqli_fetch_array($hand3);


//รายการสินค้าที่เหลือน้อยกว่า 10 ชิ้น
$sql4="select COUNT(pro_id) AS pro_num from product where amount < 10 ";
$hand4=mysqli_query($conn,$sql4);
$row4=mysqli_fetch_array($hand4);


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - SB Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
    <?php include 'menu1.php'; ?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">รายการสั่งซื้อสินค้า (ชำระเงินแล้ว) <h4> [<?=$row2['order_yes']?>] </h4> </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="report_order_yes.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning  text-white mb-4">
                                    <div class="card-body">รายการสั่งซื้อสินค้า (ยังไม่ชำระเงิน)  <h4> [<?=$row1['order_no']?>] </h4> </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="report_order.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">รายการสั่งซื้อสินค้า (ยกเลิกใบสั่งซื้อ) <h4> [<?=$row3['order_cancle']?>] </h4> </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="report_order_no.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-secondary text-white mb-4">
                                    <div class="card-body">รายการสินค้าที่เหลือน้อยกว่า 10 ชิ้น <h4> [<?=$row4['pro_num']?>] </h4></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="pro-stock.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        รายงานจำนวนสินค้าคงเหลือ
                                    </div>
                                    <div class="card-body"><canvas id="graphCanvas" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Bar Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                ข้อมูลสินค้า
                            </div>
                            
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped table-hover">
                                    
                                    <thead>
                                        <tr>
                                            <th>รูปภาพ</th>
                                            <th>รหัสสินค้า</th>
                                            <th>ชื่อสินค้า</th>
                                            <th>รายละเอียดสินค้า</th>
                                            <th>ประเภทสินค้า</th>
                                            <th>ราคาสินค้า</th>
                                            <th>จำนวนสินค้า</th>
                                            <th>เพิ่มสต๊อกสินค้า</th>
                                            
                                            
                                        </tr>
                                    </thead>

                                    <tfoot>
                                        <tr>
                                            <th>pro_id</th>
                                            <th>pro_name</th>
                                            <th>detail</th>
                                            <th>type_name</th>
                                        </tr>
                                    </tfoot>

                                    <tbody>

                                <?php
                                $sql="SELECT * FROM product p,type t WHERE p.type_id=t.type_id";
                                $hand=mysqli_query($conn,$sql);
                                while($row=mysqli_fetch_array($hand)){

                                ?>


                                        <tr>
                                            <td><img src="image/<?=$row['image']?>" width="100" height="100"></td>
                                            <td><?=$row['pro_id']?></td>
                                            <td><?=$row['pro_name']?></td>
                                            <td><?=$row['detail']?></td>
                                            <td><?=$row['type_name']?></td>
                                            <td><?=$row['price']?></td>
                                            <td><?=$row['amount']?></td>
                                            <td><a href="add_Stock.php?id=<?=$row['pro_id']?>" class="btn btn-success" >เพิ่ม</td>
                                        </tr>

                                        <?php
                                        }
                                        mysqli_close($conn);
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <?php include 'footer.php'; ?>

                


            </div>
        </div>
        
    </body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>

<script type="text/javascript" src="chart/js/jquery.min.js"></script>
<script type="text/javascript" src="chart/js/Chart.min.js"></script>


    <script>
        $(document).ready(function () {
            showGraph();
        });


        function showGraph()
        {
            {
                $.post("data_product.php",
                function (data)
                {
                    console.log(data);
                     var name = [];
                    var marks = [];

                    for (var i in data) {
                        name.push(data[i].pro_name);
                        marks.push(data[i].amount);
                    }

                    var chartdata = {
                        labels: name,
                        datasets: [
                            {
                                label: 'จำนวนคงเหลือ',
                                backgroundColor: '#ff751a',
                                borderColor: '#993d00',
                                hoverBackgroundColor: '#CCCCCC',
                                hoverBorderColor: '#666666',
                                data: marks
                            }
                        ]
                    };

                    var graphTarget = $("#graphCanvas");

                    var barGraph = new Chart(graphTarget, {
                        type: 'bar',
                        data: chartdata
                    });
                });
            }
        }
        </script>
        
