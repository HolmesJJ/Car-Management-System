<!DOCTYPE html>
<html>
<head>
    <title>Appointment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <script src="js/My97DatePicker/WdatePicker.js"></script>
    
    <!---------- 菜单 ---------->
    <script src="js/modernizr.custom.js"></script>
    <script src="js/modernizr-2.6.2.min.js"></script>

    <link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/appointment.css' type='text/css' />
    
    <!-------汽车轮播------->
    <link rel="stylesheet" href="css/edslider.css" type='text/css' />
	<link rel="stylesheet" href="css/animate-custom.css" type='text/css' />
    
    <?php
        session_start();
    
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());
  
        if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
            $conn = mysqli_connect("localhost", "root", "","db160015q_project");
            $Username = $_SESSION['Username'];
            $customer_sql = "SELECT Customer_Id FROM customer WHERE Username = '$Username'";
            $customer_id_result = mysqli_query($conn, $customer_sql);
            $one_customer_id = mysqli_fetch_assoc($customer_id_result);
            $Customer_Id = $one_customer_id['Customer_Id'];
            if(isset($_POST["car_model"])){
                $Car_Model = $_POST["car_model"];
                $_SESSION['Car_Model'] = $_POST["car_model"];
                $u_sql = "UPDATE customer SET Car_Model = '$Car_Model' WHERE Username = '$Username'";
                $update_result = mysqli_query($conn, $u_sql);
                $d_sql = "DELETE A FROM cart AS A INNER JOIN customer AS B ON A.Customer_Id = B.Customer_Id WHERE B.Username = '$Username'";
                $delete_result = mysqli_query($conn, $d_sql);
                unset($_SESSION['Item_Id']);
                unset($_SESSION['ServiceStore_Id']);
                mysqli_close($conn);
                header("Location:index.php");
            }
            else {
                $sql_car_model = "SELECT Car_Model FROM customer WHERE Username = '$Username'";
                $car_model_result = mysqli_query($conn, $sql_car_model);
                $one_car_model_result = mysqli_fetch_assoc($car_model_result);
                $Car_Model = $one_car_model_result['Car_Model'];
                $_SESSION['Car_Model'] = $Car_Model;
                
                $sql_Item = "SELECT A.Item_Name, B.Category_Name, A.Item_Picture, A.Description, A.Price, A.Working_Time, C.Status FROM serviceitem AS A INNER JOIN category AS B ON A.Category_Id = B.Category_Id INNER JOIN servicestatus AS C ON A.Status_Id = C.Status_Id INNER JOIN cart AS D ON A.Item_Id = D.Item_Id WHERE D.Customer_Id = $Customer_Id";
                $sql_Item_result = mysqli_query($conn, $sql_Item);
                
                $sql_ServiceStore = "SELECT A.ServiceStore_Name, A.Address, B.Location, A.Phone, A.Email, A.Picture1, A.Car_Model, A.Rating, A.Opening_Hours FROM servicestore AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN cart AS D ON A.ServiceStore_Id = D.ServiceStore_Id WHERE D.Customer_Id = $Customer_Id";
                $sql_ServiceStore_result = mysqli_query($conn, $sql_ServiceStore);
                
                $sql_cart = "SELECT Item_Id, ServiceStore_Id, Appointment_Date, Appointment_Time, Remarks, Quantity FROM cart WHERE Customer_Id = $Customer_Id";
                $sql_cart_result = mysqli_query($conn, $sql_cart);
                $one_cart_result = mysqli_fetch_assoc($sql_cart_result);
                $Quantity = $one_cart_result['Quantity'];
                $_SESSION['Quantity'] = $Quantity; //sdsfdsfdsfsdfsdfdsfdsfdsfds
                $Appointment_Date = $one_cart_result['Appointment_Date'];
                $Appointment_Time = $one_cart_result['Appointment_Time'];
                $Remarks = $one_cart_result['Remarks'];
                $Item_Id = $one_cart_result['Item_Id'];
                $ServiceStore_Id = $one_cart_result['ServiceStore_Id'];
                mysqli_close($conn);
            }  
        }
    
        if(isset($_POST["Quantity"]) && !empty($_POST['Quantity'])){
            $conn = mysqli_connect("localhost", "root", "","db160015q_project");
            $Quantity = $_POST['Quantity'];
            $_SESSION['Quantity'] = $Quantity; //sdsfdsfdsfsdfsdfdsfdsfdsfds
            $u_quantity_sql = "UPDATE cart SET Quantity = $Quantity WHERE Customer_Id = $Customer_Id";
            $u_quantity_result = mysqli_query($conn, $u_quantity_sql);
            mysqli_close($conn);
        }
    
        if(isset($_POST["Appointment_Date"]) && !empty($_POST['Appointment_Date']) && isset($_POST["Appointment_Time"]) && !empty($_POST['Appointment_Time']) && isset($_POST["Remarks"]) && !empty($_POST['Remarks'])){
            if(isset($_SESSION["Item_Id"]) && !empty($_SESSION['Item_Id']) && isset($_SESSION["ServiceStore_Id"]) && !empty($_SESSION['ServiceStore_Id'])){
                $conn = mysqli_connect("localhost", "root", "","db160015q_project");
                $Item_Id = $_SESSION["Item_Id"];
                $ServiceStore_Id = $_SESSION["ServiceStore_Id"];
                $Appointment_Date = $_POST["Appointment_Date"];
                $Appointment_Time = $_POST["Appointment_Time"];
                $Remarks = $_POST["Remarks"];
                
                $_SESSION['Appointment_Date'] = $Appointment_Date; //sdsfdsfdsfsdfsdfdsfdsfdsfds
                $_SESSION['Appointment_Time'] = $Appointment_Time; //sdsfdsfdsfsdfsdfdsfdsfdsfds
                $_SESSION['Remarks'] = $Remarks; //sdsfdsfdsfsdfsdfdsfdsfdsfds
                
                $Remarks = $_POST["Remarks"];
                $sql_date_crash = "SELECT Appointment_Time FROM cart WHERE Item_Id = $Item_Id && ServiceStore_Id = $ServiceStore_Id && Appointment_Date = '$Appointment_Date'";
                $date_crash_result = mysqli_query($conn, $sql_date_crash);
                $count = 0;
                if (!$date_crash_result || mysqli_num_rows($date_crash_result) == 0){  
                    $count = 1;
                }
                else {
                    while ($one_date_crash = mysqli_fetch_assoc($date_crash_result)) {
                        if($Appointment_Time == $one_date_crash["Appointment_Time"]) {
                            header("Location:appointment.php?Alert=DayCrash");
                        }
                        else {
                            $count++;
                        }
                    }   
                }
                if($count != 0) {
                    $u_sql = "UPDATE cart SET Appointment_Date = '$Appointment_Date', Appointment_Time = '$Appointment_Time', Remarks = '$Remarks' WHERE Customer_Id = $Customer_Id";
                    $u_result = mysqli_query($conn, $u_sql);
                    mysqli_close($conn);
                }
            }
            else {
                header("Location:appointment.php?Alert=Select");
            }
        }
    
        if(isset($_POST["Delete_Item"]) && !empty($_POST['Delete_Item'])){
            $conn = mysqli_connect("localhost", "root", "","db160015q_project");
            $u_item_sql = "UPDATE cart SET Appointment_Date = NULL, Appointment_Time = NULL, Remarks = NULL, Item_Id = NULL, Quantity = NULL, Updated = '$timestamp' WHERE Customer_Id = $Customer_Id";
            $u_item_result = mysqli_query($conn, $u_item_sql);
            mysqli_close($conn);
            unset($_SESSION['Item_Id']);
            unset($_SESSION["Item_Name"]);
            header("Location:appointment.php");
        }
        if(isset($_POST["Update_ServiceStore"]) && !empty($_POST['Update_ServiceStore'])){
            $conn = mysqli_connect("localhost", "root", "","db160015q_project");
            $u_store_sql = "UPDATE cart SET Appointment_Date = NULL, Appointment_Time = NULL, Remarks = NULL, ServiceStore_Id = NULL, Updated = '$timestamp' WHERE Customer_Id = $Customer_Id";
            $u_store_result = mysqli_query($conn, $u_store_sql);
            mysqli_close($conn);
            unset($_SESSION['ServiceStore_Id']);
            unset($_SESSION["ServiceStore_Name"]);
            header("Location:appointment.php");
        }
        if(!isset($_SESSION['Item_Id']) && !isset($_SESSION['ServiceStore_Id'])) {
            $conn = mysqli_connect("localhost", "root", "","db160015q_project");
            $sql_search = "SELECT Customer_Id FROM cart WHERE Customer_Id = $Customer_Id";
            $sql_search_result = mysqli_query($conn, $sql_search);
            if (!$sql_search_result || mysqli_num_rows($sql_search_result) == 0){  
            }
            else {
                $d_record_sql = "DELETE FROM cart WHERE Customer_Id = $Customer_Id";
                $d_record_result = mysqli_query($conn, $d_record_sql);
            }
            mysqli_close($conn);
        }
        if(isset($_GET['Alert'])) {
            if($_GET['Alert'] == "Unfill") {
                echo "<script>alert('Please fill up your order information!')</script>";
            }
            else if ($_GET['Alert'] == "Unsave") {
                echo "<script>alert('Please save your order information before submit!')</script>";
            }
            else if ($_GET['Alert'] == "DayCrash") {
                echo "<script>alert('This day has been selected! Please selected another day.')</script>";
            }
            else if ($_GET['Alert'] == "Select") {
                echo "<script>alert('Please select your service first.')</script>";
            }
            else if ($_GET['Alert'] == "FillUp") {
                echo "<script>alert('Please fill up your appointment first.')</script>";
            }
        }
    ?>
</head>
<body>

<!------------------------顶部信息--------------------------->
    <header id="nav">
        <div id="menu_logo" class="pull-left">
            <div class="pull-left postion1"></div>
            <div class="pull-left postion2"></div>
            <?php
                ini_set('default_socket_timeout',1); 
                $url="http://ip.taobao.com/service/getIpInfo.php?ip=myip";
                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_URL, $url);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
                $ip = curl_exec($ch);
                curl_close($ch);
                $ip = json_decode($ip);
                if((string)$ip->code=='1'){
                    return false;
                }
                $data = (array)$ip->data;
                if($data["country"] != "") {
                    echo "<div class='pull-left position-name'>".$data["country"]."</div>";
                }
            ?>
        </div>
        <h1>www.services.com</h1>
        <div id="menu_icon" class="pull-right">
            <a class="cd-bouncy-nav-trigger" href="#0"></a>
        </div>
        <?php
            if(empty($_SESSION['Username'])) {
            }
            else {
                echo "<div class='pull-right position-username'><span>Hi, ".$_SESSION['Username']."</span></div>";
            }
        ?>
    </header>

	<div class="cd-bouncy-nav-modal">
		<nav>
			<ul class="cd-bouncy-nav">
				<li><a href="index.php">HomePage</a></li>
				<li><a href="maintenance.php">Maintenance</a></li>
				<li><a href="servicestore.php">ServiceStore</a></li>
				<li><a href="appointment.php">Management</a></li>
				<li><a href="order_info.php">MyCart</a></li>
				<li><a href="login.php">Login</a></li>
			</ul>
		</nav>
		<a href="javascript:void(0);" class="cd-close"></a>
	</div>
    <div class="reset"></div>
    <nav class="nav50"></nav>
    
    <script src="js/jquery/jquery.menu.js"></script>
    <script src="js/menu.js"></script>


<!-------------------------封面轮播-------------------------------->
    <section class="container">
			<ul class="mySlideshow">
				<li class="first"></li>
				<li class="second"></li>
				<li class="third"></li>
				<li class="fourth"></li>
				<li class="fifth"></li>
			</ul>
            <div class="homedownapp-main">
                        <div class="banner-title"></div>
                        <div class="row">
                            <div class="downapp-icon pull-left text-center">
                                <div></div>
                                Manage
                            </div>
                            <div class="downapp-icon pull-left text-center">
                                <div></div>
                                Maintain
                            </div>
                            <div class="downapp-icon pull-left text-center">
                                <div></div>
                                Car Life
                            </div>
                            <div class="downapp-icon pull-left text-center">
                                <div></div>
                                H-Quality
                            </div>
                        </div>
                </div>
		</section>
	<script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery.edslider.js"></script>
	<script>
		$("document").ready(function(){
			//Call plugin
			$('.mySlideshow').edslider({
				width : '100%',
				height: 300
			});
		});
	</script>     

<!----------------------------订单----------------------------->
    <article class='panel' style='margin-top:24px;'>
    <h2 class="cart_detail_title" >Service appointment card</h2>
    <!--服务预约卡详情view-->
	<!--服务清单 start-->
	<div class="cart_detail_item" >
        <h3 class="cart_detail_item_title" >List of services</h3>
        <!--用户自选服务-->
        <div class='cart_detail_item_content'>
            <?php
                if (!$sql_Item_result || mysqli_num_rows($sql_Item_result) == 0){
                    echo "<div id='cart_detail_no_item_wnd' class='cart_detail_item_wnd'>
                    <div class='select_remind'>No service items selected,
                    <a class='cart-operation' href='maintenance.php' style='color:#FE5419;cursor:pointer;text-decoration:underline;' target='_blank'>click to choose&gt;&gt;
                    </a></div></div>";
                }
                else {
                    $one_item = mysqli_fetch_assoc($sql_Item_result);
                    $Item_Name = $one_item["Item_Name"];
                    $Description = $one_item["Description"];
                    $Category_Name = $one_item["Category_Name"];
                    $Item_Picture = $one_item["Item_Picture"];
                    $Price = $one_item["Price"];
                    $Working_Time = $one_item["Working_Time"];
                    $Status = $one_item["Status"];
            ?>
             <div id="cart_detail_item_wnd" class="cart_detail_item_wnd" >
                        <p style="font-size:18px;"><?php echo $Item_Name;?> ---- <?php echo $Description;?></p>
                        <div style="display:inline-block; width:100%; position:relative;">
                        <form id="Delete_Form" action="appointment.php" method="post">
                            <input name="Delete_Item" type="text" value="Delete" hidden="hidden">
                            <a class="myButton" id="Circle_btn" onclick="Delete_Item()">Delete&nbsp;&gt;&gt;</a>
                            <a class="myButton" id="Circle_btn" onclick="Update_Quantity()">Update&nbsp;&gt;&gt;</a>
                        </form>
                        <script>
                            function Delete_Item() {
                                var bool = confirm("Are you sure deleting the item?");
                                if(bool == true) {
                                    document.getElementById("Delete_Form").submit();
                                }  
                            }
                        </script>
                        <script>
                            function Update_Quantity() {
                                document.getElementById("quantity_show").style.display = "none";
                                document.getElementById("Update_box").style.display = "block";
                            }
                        </script>
                        </div>
                        <table class="cd_service_table" >
                            <tr>
                                <th style="width:15%;color:white;background:#24303c" >Category Name</th>
                                <th style="width:25%;color:white;background:#24303c" >Item Name</th>
                                <th style="width:15%;color:white;background:#24303c" >Quantity</th>
                                <th style="width:15%;color:white;background:#24303c" >Status</th>
                            </tr>
                            <tr class='all_service'>
                                <!--服务项目-->
                                <td><?php echo $Category_Name;?></td>
                                <td>
                                    <div class='pull-left' style="'margin-left:20px;width:80%" ><?php echo $Item_Name;?></div>
                                    <div class='pull-right orange' style='margin-right:20px;font-weight:bold;'>$<?php echo $Price;?></div>
                                    <div class="reset"></div>
                                </td>
                                <td><span id="quantity_show"><?php echo $Quantity;?></span>
                                    <div id="Update_box" style="display:none;">
                                        <form id="Save_Form" action="appointment.php" method="post" >
                                        <input id="quantity_input" type="number" value="1" name="Quantity" style="width:80px;" onblur="val_number(this)"/><br>
                                        <a class="myButton" id="Circle_btn" onclick="Save_Quantity()">Save</a>
                                        </form>
                                    </div>
                                    <script>
                                        function val_number(obj) {
                                            if(parseInt(obj.value) == obj.value && parseInt(obj.value)>0 && parseInt(obj.value)<=50) {

                                            }
                                            else {
                                                alert("Please enter correct number (1 - 50)");
                                                document.getElementById("quantity_input").value = "1";
                                            }
                                        }
                                        function Save_Quantity() {
                                            document.getElementById("Save_Form").submit();
                                        }
                                    </script>
                                </td>
                                <td><?php echo $Status;?></td>
                            </tr>
                            <div class="product_bottom"></div>
                            <tr id="tr_service_css" >
                                <td colspan="5" class="servicelist_price" style="padding:14px;">
                                    <p>
                                        <span class='priceitem_amount' id='time' > <?php echo $Working_Time;?>&nbsp;Minutes</span>
                                        <span class='priceitem_title'>Estimated Working Time:</span>
                                    </p>
                                    <div class='reset'></div>
                                    <p>
                                        <span class='priceitem_amount totalprice totalprice' id='allprice' >$
                                            <?php 
                                            if(isset($_POST["Quantity"]) && !empty($_POST['Quantity'])){
                                                echo $Quantity*$Price;
                                            }
                                            else {
                                                echo $Price;
                                            }
                                            ?>
                                        </span>
                                        <span class='priceitem_title'>Payment:</span>
                                    </p>
                                    <p>
                                        <span class='priceitem_amount totalprice' id='allprice' >$
                                            <?php 
                                            if(isset($_POST["Quantity"]) && !empty($_POST['Quantity'])){
                                                echo $Quantity*$Price*0.8;
                                            }
                                            else {
                                                echo $Price*0.8;
                                            }
                                            ?>
                                        </span>
                                        <span class='priceitem_title'>Payment(20% Discount):</span>
                                    </p>
                                    <div class='reset'></div>
                                </td>
                            </tr>
                        </table>
                        <!--没有服务的时候的展示-->
                    </div>
                    <?php
                    }
                ?>
		</div>
	</div>
    <!--服务清单 end-->
    <!--服务站信息 start-->
    <div class="cart_detail_item" id="cart_detail_shop" >
        <h3 class="cart_detail_item_title" >Service station information</h3>
        <div class='cart_detail_item_content'>
            <?php
                    if (!$sql_ServiceStore_result || mysqli_num_rows($sql_ServiceStore_result) == 0){
                        echo "<div id='cart_detail_no_item_wnd' class='cart_detail_item_wnd'>
                        <div class='select_remind'>No service items selected,
                        <a class='cart-operation' href='servicestore.php' style='color:#FE5419;cursor:pointer;text-decoration:underline;' target='_blank'>click to choose&gt;&gt;
                        </a></div></div>";
                    }
                    else {
                        $one_servicestore = mysqli_fetch_assoc($sql_ServiceStore_result);
                        $ServiceStore_Name = $one_servicestore["ServiceStore_Name"];
                        $Address = $one_servicestore["Address"];
                        $Location = $one_servicestore["Location"];
                        $Phone = $one_servicestore["Phone"];
                        $Email = $one_servicestore["Email"];
                        $Picture1 = $one_servicestore["Picture1"];
                        $Rating = $one_servicestore["Rating"];
                        $Opening_Hours = $one_servicestore["Opening_Hours"];
                ?>
                <div id="cart_detail_item_wnd" class="cart_detail_item_wnd" style="text-align:center;">
                    <form method="post" action="appointment.php" id="Update_ServiceStore_Form">
                    <p>
                        <label class='shopname'><?php echo $ServiceStore_Name;?></label>
                        <span class='shopfullname' style="position:relative; top:-1px;">(<?php echo $ServiceStore_Name;?>)</span>
                        <br>
                        <span style="position:relative; top:2px;">
                            <?php
                                for($i=0; $i<(int)$Rating; $i++) {
                                echo "<img src='images/article/lan.png' width='17px'>";
                                }
                            ?>
                        </span>
                        <span>
                            <span class="shopname"><?php echo $Rating;?></span>
                            <label style="position:relative; top:-1px;">/5 Marks</label>
                        </span>
                        <a class="myButton" id="Circle_btn" target="_blank" style="margin:0 5 5 5px" onclick="Update_ServiceStore()">Cancel&nbsp;&gt;&gt;</a>
                        <input name="Update_ServiceStore" id="Update_ServiceStore" type="text" value="Update_ServiceStore" hidden/>
                    </p>
                    </form>
                    <script>
                        function Update_ServiceStore() {
                            var bool = confirm("Are you sure deleting the Store?");
                            if(bool == true) {
                                document.getElementById("Update_ServiceStore_Form").submit();
                            }
                        }
                    </script>
                    <div class="cd_shop_image" >
                        <div class="cd_shop_image_content" >
                            <img src='<?php echo $Picture1;?>' />
                        </div>
                    </div>
                    <div class="cd_shop_intro" >
                        <div class="cd_shop_intro_content" >
                            <div class="csic_item" >
                                <div class="csic_item_title">Opening Hours:</div>
                                <div class="csic_item_content"><?php echo $Opening_Hours;?></div>
                                <div class="reset"></div>
                            </div>
                            <div class="csic_item" >
                                <div class="csic_item_title">Address(Location):</div>
                                <div class="csic_item_content"><?php echo $Address;?> (<?php echo $Location;?>)</div>
                                <div class="reset"></div>
                            </div>
                            <div class="csic_item" >
                                <div class="csic_item_title">Phone:</div>
                                <div class="csic_item_content"><?php echo $Phone;?></div>
                                <div class="reset"></div>
                            </div>
                            <div class="csic_item" >
                                <div class="csic_item_title">Email:</div>
                                <div class="csic_item_content"><?php echo $Email;?></div>
                                <div class="reset"></div>
                            </div>
                        </div>
                    </div>
                    <div class="reset"></div>
                </div>
                <?php
                    }
                ?>
        </div>
    </div>
    <!--服务站信息 end-->

    <!--到店时间 start-->
    <div class="edit_mode">
        <h3 class="cart_detail_item_title" >To the store installation time&nbsp;&nbsp;</h3>
        <div class='cart_detail_item_content'>
            <div class="ps_form">
                <form action='appointment.php' method='post' name="Save_App_Form" id="Save_App_Form" >
                        <fieldset>
                            <div style="color:#f60;padding:0px 0px 10px 10px;">Reminder: As the service required acessories 7 days delivary period, to the store installation time can be scheduled 2 days after the time.</div>
                        </fieldset>
                        <fieldset>
                            <div class="ps_edit_item" >
                                <label style="display:inline-block;width:150px;color:#000;font-weight:800">Appointment Date:</label>&nbsp;<input id="Appointment_Date" class="Wdate" type="text" onClick="WdatePicker({el:this,minDate:'%y-%M-{%d+2}'})" style="height:25px;width:170px;" name="Appointment_Date" value="<?php echo $Appointment_Date;?>">
                            </div>
                            <div class="ps_edit_item" >
                                <label style="display:inline-block;width:150px;color:#000;font-weight:800">Appointment Time:</label>
                                <select name='Appointment_Time' style='width:170px;color:#333;' >
                                <?php
                                    if($Appointment_Time == '9:00~10:00') {
                                    ?>
                                        <option value='9:00~10:00' selected>9:00~10:00</option>
                                    <?php
                                    }else {
                                    ?>
                                        <option value='9:00~10:00'>9:00~10:00</option>
                                    <?php
                                    }
                                    if($Appointment_Time == '10:00~11:00') {
                                    ?>
                                        <option value='10:00~11:00' selected>10:00~11:00</option>
                                    <?php
                                    }else {
                                    ?>
                                        <option value='10:00~11:00'>10:00~11:00</option>
                                    <?php
                                    }
                                    if($Appointment_Time == '11:00~12:00') {
                                    ?>
                                        <option value='11:00~12:00' selected>11:00~12:00</option>
                                    <?php
                                    }else {
                                    ?>
                                        <option value='11:00~12:00'>11:00~12:00</option>
                                    <?php
                                    }
                                    if($Appointment_Time == '12:00~13:00') {
                                    ?>
                                        <option value='12:00~13:00' selected>12:00~13:00</option>
                                    <?php
                                    }else {
                                    ?>
                                        <option value='12:00~13:00'>12:00~13:00</option>
                                    <?php
                                    }
                                    if($Appointment_Time == '13:00~14:00') {
                                    ?>
                                        <option value='13:00~14:00' selected>13:00~14:00</option>
                                    <?php
                                    }else {
                                    ?>
                                        <option value='13:00~14:00'>13:00~14:00</option>
                                    <?php
                                    }
                                    if($Appointment_Time == '14:00~15:00') {
                                    ?>
                                        <option value='14:00~15:00' selected>14:00~15:00</option>
                                    <?php
                                    }else {
                                    ?>
                                        <option value='14:00~15:00'>14:00~15:00</option>
                                    <?php
                                    }
                                    if($Appointment_Time == '15:00~16:00') {
                                    ?>
                                        <option value='15:00~16:00' selected>15:00~16:00</option>
                                    <?php
                                    }else {
                                    ?>
                                        <option value='15:00~16:00'>15:00~16:00</option>
                                    <?php
                                    }
                                    if($Appointment_Time == '16:00~17:00') {
                                    ?>
                                        <option value='16:00~17:00' selected>16:00~17:00</option>
                                    <?php
                                    }else {
                                    ?>
                                        <option value='16:00~17:00'>16:00~17:00</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="reset"></div>
                        </fieldset>
                        <fieldset>
                            <div>
                                <label style='display:inline-block;width:145px;color:#000;font-weight:800'>Reservation Note:</label>
                                <textarea rows='3' cols='200' style="resize:none;width:71%;"  name='Remarks'><?php echo $Remarks; ?></textarea>
                                <br/><span style=';position:relative;left:30px;top:5px;'>You can only enter up to 200 characters</span>
                            </div>
                        </fieldset>
                        <fieldset class='form-actions' style='border:none;margin:0;'>
                            <input type="button" value='Save reservation' style='background-color: #FE5417;' onclick="Save_App()"/>
                            <input id="Username" name="Username" type="text" value='<?php echo $Username;?>' style="visibility:hidden"/>
                            <input id="Quantity" name="Quantity" type="number" value='<?php echo $Quantity;?>' style="visibility:hidden"/>
                            <input id="Item_Id" name="Item_Id" type="text" value='<?php echo $Item_Id;?>' style="visibility:hidden"/>
                            <input id="ServiceStore_Id" name="ServiceStore_Id" type="text" value='<?php echo $ServiceStore_Id;?>' style="visibility:hidden"/>
                        </fieldset>
                        <div class="reset"></div>
                    </form>
                    <script>
                        function Save_App() {
                            document.getElementById("Save_App_Form").submit();
                        }
                    </script>
            </div>
        </div>
    </div>
    <!--到店时间 end-->

        <form action="order_info.php" method="post" id="All_Info_Form">
            <!-- 支付方式 start -->
            <div class="cart_detail_item" id="cart_detail_pay">
                <h3 class="cart_detail_item_title" >Payment Type</h3>
                <div class='cart_detail_item_content'>
                    <div class="payment">
                        <input class="magic-radio" type="radio" name="Payment_Type_Id" id="p1" value="1" checked>
                        <label for="p1"><img style="position:relative;top:-20px;" src="images/article/Credit.png" height="60px;"/></label>
                    </div>
                    <div class="payment">
                        <input class="magic-radio" type="radio" name="Payment_Type_Id" id="p2" value="2">
                        <label for="p2"><img style="position:relative;top:-20px;" src="images/article/Alipay.png" height="60px;"/></label>
                    </div>
                    <br>
                    <div class="payment">
                        <input class="magic-radio" type="radio" name="Payment_Type_Id" id="p3" value="3">
                        <label for="p3"><img style="position:relative;top:-15px;" src="images/article/WeChat.png" height="60px;"/></label>
                    </div>
                    <div class="payment">
                        <input class="magic-radio" type="radio" name="Payment_Type_Id" id="p4" value="4">
                        <label for="p4"><img style="position:relative;top:-20px;" src="images/article/PayPal.png" height="60px;"/></label>
                    </div>
                </div>
            </div>
            <!-- 支付方式 end  -->
            <!--发票信息 start-->
            <div class="cart_detail_item" id="cart_detail_invoice">
                <h3 class="cart_detail_item_title" >Invoice Information</h3>
                <div class='cart_detail_item_content' style="min-height:60px;_height:60px;height:100%;">
                    <div class="invoice">
                        <input class="magic-radio" type="radio" name="Invoice_Id" id="i1" value="1" checked>
                        <label for="i1">Have invoice</label>
                    </div>
                    <div class="invoice">
                        <input class="magic-radio" type="radio" name="Invoice_Id" id="i2" value="2">
                        <label for="i2">No invoice</label>
                    </div>
                </div>
            </div>
            <!--发票信息 end-->
            <!--订单提交操作区 start-->
            <div class="cart_detail_sub" id="cart_detail_sub" >
                <input type="text" name="Submit" value="Submit" hidden/>
                <div onclick="SubmitOrder()" class="cart_detail_sub_btn">Submit</div>
                <div class="reset"></div>
            </div>
        </form>
    <script>
        function SubmitOrder() {
            if(document.getElementById("Username").value != "" && document.getElementById("Item_Id").value != "" && document.getElementById("ServiceStore_Id").value != "" && document.getElementById("Appointment_Date").value != ""){
                
                document.getElementById("All_Info_Form").submit();
            }
            else {
                alert("Please fill up your information first!");
            }  
        }
    </script>
    </article>
    <div class="reset"></div>
    
<!------------------------- Footer -------------------------------->
	<Footer class='barpanel'>
        <div class='panel_footer'>
            <div class="foot_box">
                <p class="foot_cont">
                    <a target='_blank' rel="nofollow" href="javascript:void(0);">About Us</a>|
                    <a target='_blank' rel="nofollow" href="javascript:void(0);">Contact Us</a>|
                    <a target='_blank' rel="nofollow" href="javascript:void(0);">Service Station Settled</a>|
                    <a target='_blank' rel="nofollow" href="javascript:void(0);">Suggestion</a>
				</p>
                <p>&copy;2017 www.carservices.com All Rights Reserved</p>
                <p>MI1603 160015Q HU JIAJUN</p>
            </div>
        </div>
        <div class='reset'></div>
    </Footer>
    
<!----------------------底部弹框-------------------------->
    <button class="cn-button" id="cn-button">+</button>
    <div class="cn-wrapper" id="cn-wrapper">
    <ul>
        <li><a href="javascript:void(0);" ><span class="icon-picture"></span><img src="images/footer/newfooter1.png" width="30px"/></a></li>
        <li><a href="javascript:void(0);" ><span class="icon-headphones"></span><img src="images/footer/newfooter2.png" width="30px"/></a></li>
        <li><a href="javascript:void(0);" ><span class="icon-home"></span><img src="images/footer/newfooter3.png" width="30px"/></a></li>
        <li><a href="javascript:void(0);" ><span class="icon-facetime-video"></span><img src="images/footer/newfooter4.png" width="30px"/></a></li>
        <li><a href="javascript:void(0);" ><span class="icon-envelope-alt"></span><img src="images/footer/newfooter5.png" width="30px"/></a></li>
    </ul>
    </div>
    <div id="cn-overlay" class="cn-overlay"></div>
    <script src="js/polyfills.js"></script> 
    <script src="js/lrtk.js"></script>
    
</body>
</html>

<script>
    
/* ================ PC_Tablet ================== */
$(window).resize(function(){
    if(document.body.clientWidth>768){
        window.location.href="../PC_Tablet/appointment.php";
    }
});
</script>