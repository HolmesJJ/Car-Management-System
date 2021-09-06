<!DOCTYPE html>
<html>
<head>
    <title>Management Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <script src="js/jquery/jquery.advertisement.js"></script>
    
    <script src="js/My97DatePicker/WdatePicker.js"></script>
    
	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/management.css' type='text/css' />
    
    <?php  
    session_start();
        if (isset($_SESSION['Administrator_Name']) && !empty($_SESSION["Administrator_Name"])) {

            $conn = mysqli_connect("localhost", "root", "","db160015Q_project" );

            date_default_timezone_set("Asia/Singapore");
            $timestamp = date('y-m-d h:i:s',time());

            $Search = "A.Booking_Id, A.Booking_No, G.Username, B.Item_Name, C.ServiceStore_Name, A.Appointment_Date, A.Appointment_Time, A.Quantity, A.Payment, D.Payment_Type, E.Invoice, A.Remarks, F.Status, A.Created, A.Updated";

            $filter = "";
            
            if (isset($_POST['Username']) && !empty($_POST["Username"])) {
                $Username = $_POST['Username'];
                if(isset($_POST["Username_Yes_No"])){
                    $Username = " LIKE '%$Username%'";
                }
                else {
                    $Username = " NOT LIKE '%$Username%'";
                }
                if($filter == "") {
                    $filter = "G.Username$Username";
                }
                else {
                    $filter = $filter." && G.Username$Username";
                }
            }
            if (isset($_POST['Item_Name']) && !empty($_POST["Item_Name"])) {
                $Item_Name = $_POST['Item_Name'];
                if(isset($_POST["Item_Name_Yes_No"])){
                    $Item_Name = " LIKE '%$Item_Name%'";
                }
                else {
                    $Item_Name = " NOT LIKE '%$Item_Name%'";
                }
                if($filter == "") {
                    $filter = "B.Item_Name$Item_Name";
                }
                else {
                    $filter = $filter." && B.Item_Name$Item_Name";
                }
            }
            if (isset($_POST['ServiceStore_Name']) && !empty($_POST["ServiceStore_Name"])) {
                $ServiceStore_Name = $_POST['ServiceStore_Name'];
                if(isset($_POST["ServiceStore_Name_Yes_No"])){
                    $ServiceStore_Name = " LIKE '%$ServiceStore_Name%'";
                }
                else {
                    $ServiceStore_Name = " NOT LIKE '%$ServiceStore_Name%'";
                }
                if($filter == "") {
                    $filter = "C.ServiceStore_Name$ServiceStore_Name";
                }
                else {
                    $filter = $filter." && C.ServiceStore_Name$ServiceStore_Name";
                }
            }
            if (isset($_POST['Appointment_Date']) && !empty($_POST["Appointment_Date"])) {
                $Appointment_Date = $_POST['Appointment_Date'];
                if(isset($_POST["Appointment_Date_Yes_No"])){
                    $Appointment_Date = "='$Appointment_Date'";
                }
                else {
                    $Appointment_Date = "!='$Appointment_Date'";
                }
                if($filter == "") {
                    $filter = "A.Appointment_Date$Appointment_Date";
                }
                else {
                    $filter = $filter." && A.Appointment_Date$Appointment_Date";
                }
            }
            if (isset($_POST['Appointment_Time']) && !empty($_POST["Appointment_Time"])) {
                $Appointment_Time = $_POST['Appointment_Time'];
                if($Appointment_Time == "all") {

                }
                else {
                    if($filter == "") {
                        $filter = "A.Appointment_Time='$Appointment_Time'";
                    }
                    else {
                        $filter = $filter." && A.Appointment_Time='$Appointment_Time'";
                    }
                }
            }
            if (isset($_POST['Quantity']) && !empty($_POST["Quantity"])) {
                $Quantity = $_POST['Quantity'];
                if(isset($_POST["Quantity_Range"])) {
                    $Quantity_Range = $_POST["Quantity_Range"];
                    if($Quantity_Range == "equal") {
                        $Quantity = "=$Quantity";
                    }
                    else if($Quantity_Range == "larger") {
                        $Quantity = ">$Quantity";
                    }
                    else if($Quantity_Range == "smaller") {
                        $Quantity = "<$Quantity";
                    }
                    else if($Quantity_Range == "between") {
                        if (isset($_POST['Quantity2']) && !empty($_POST["Quantity2"])) {
                            $Quantity2 = $_POST['Quantity2'];
                            if((float)$_POST['Quantity'] < (float)$_POST['Quantity2']) {
                                $Quantity = ">$Quantity AND A.Quantity<$Quantity2";
                            }
                            else if((float)$_POST['Quantity'] > (float)$_POST['Quantity2']) {
                                $Quantity = ">$Quantity2 AND A.Quantity<$Quantity";
                            }
                        }
                    }
                }
                if($filter == "") {
                    $filter = "A.Quantity$Quantity";
                }
                else {
                    $filter = $filter." && A.Quantity$Quantity";
                }
            }
            if (isset($_POST['Payment']) && !empty($_POST["Payment"])) {
                $Payment = $_POST['Payment'];
                if(isset($_POST["Payment_Range"])) {
                    $Payment_Range = $_POST["Payment_Range"];
                    if($Payment_Range == "equal") {
                        $Payment = "=$Payment";
                    }
                    else if($Payment_Range == "larger") {
                        $Payment = ">$Payment";
                    }
                    else if($Payment_Range == "smaller") {
                        $Payment = "<$Payment";
                    }
                    else if($Payment_Range == "between") {
                        if (isset($_POST['Payment2']) && !empty($_POST["Payment2"])) {
                            $Payment2 = $_POST['Payment2'];
                            if((float)$_POST['Payment'] < (float)$_POST['Payment2']) {
                                $Payment = ">$Payment AND A.Payment<$Payment2";
                            }
                            else if((float)$_POST['Payment'] > (float)$_POST['Payment2']) {
                                $Payment = ">$Payment2 AND A.Payment<$Payment";
                            }
                        }
                    }
                }
                if($filter == "") {
                    $filter = "A.Payment$Payment";
                }
                else {
                    $filter = $filter." && A.Payment$Payment";
                }
            }
            if (isset($_POST['Payment_Type']) && !empty($_POST["Payment_Type"])) {
                $Payment_Type = $_POST['Payment_Type'];
                if($Payment_Type == "all") {

                }
                else {
                    if($filter == "") {
                        $filter = "A.Payment_Type_Id=$Payment_Type";
                    }
                    else {
                        $filter = $filter." && A.Payment_Type_Id=$Payment_Type";
                    }
                }
            }
            if (isset($_POST['Invoice']) && !empty($_POST["Invoice"])) {
                $Invoice = $_POST['Invoice'];
                if($Invoice == "all") {

                }
                else {
                    if($filter == "") {
                        $filter = "A.Invoice_Id=$Invoice";
                    }
                    else {
                        $filter = $filter." && A.Invoice_Id=$Invoice";
                    }
                }
            }
            
            //准备更新
            if (isset($_POST['u_Booking_Id']) && !empty($_POST["u_Booking_Id"])) {
                $u_Booking_Id = $_POST['u_Booking_Id'];
                $enter_sql = "SELECT ".$Search." FROM booking AS A INNER JOIN serviceitem AS B ON A.Item_Id = B.Item_Id INNER JOIN servicestore AS C ON A.ServiceStore_Id = C.ServiceStore_Id INNER JOIN paymenttype AS D ON A.Payment_Type_Id = D.Payment_Type_Id INNER JOIN invoice AS E ON A.Invoice_Id = E.Invoice_Id INNER JOIN orderstatus AS F ON A.Status_Id = F.Status_Id INNER JOIN Customer AS G ON A.Customer_Id = G.Customer_Id WHERE Booking_Id = $u_Booking_Id";    
                $show_result = mysqli_query($conn, $enter_sql);
            }

            //直接取消
            if (isset($_POST['c_Booking_Id']) && !empty($_POST["c_Booking_Id"])) {
                $c_Booking_Id = $_POST['c_Booking_Id'];
                $c_sql = "UPDATE booking SET Status_Id = 3 WHERE Booking_Id = $c_Booking_Id";
                $cancel_result = mysqli_query($conn, $c_sql);
            }
        
            if (isset($_POST['Submit'])) {
                $Submit = $_POST['Submit'];
                //Search
                if (isset($_POST['Confirm_Booking_Id']) && $Submit == "Update Information") {
                //更新
                $Confirm_Booking_Id = $_POST['Confirm_Booking_Id'];
                if (isset($_POST['u_Appointment_Date']) && !empty($_POST["u_Appointment_Date"])) {
                    $u_Appointment_Date = $_POST['u_Appointment_Date'];
                    if(isset($_POST['Username']) && !empty($_POST["Username"]) && isset($_POST['Item_Name']) && !empty($_POST["Item_Name"]) && isset($_POST['ServiceStore_Name']) && !empty($_POST["ServiceStore_Name"])){
                        $update = "A.Appointment_Date='$u_Appointment_Date'";
                        $u_sql = "UPDATE booking AS A INNER JOIN serviceitem AS B ON A.Item_Id = B.Item_Id INNER JOIN servicestore AS C ON A.ServiceStore_Id = C.ServiceStore_Id INNER JOIN paymenttype AS D ON A.Payment_Type_Id = D.Payment_Type_Id INNER JOIN invoice AS E ON A.Invoice_Id = E.Invoice_Id INNER JOIN orderstatus AS F ON A.Status_Id = F.Status_Id INNER JOIN Customer AS G ON A.Customer_Id = G.Customer_Id SET ".$update." WHERE A.Booking_Id = $Confirm_Booking_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                    else {
                        echo "<script>alert('Please enter the User Name, Item Name, ServiceStore Name')</script>";
                    }
                }
                if (isset($_POST['u_Appointment_Time']) && !empty($_POST["u_Appointment_Time"])) {
                    $u_Appointment_Time = $_POST['u_Appointment_Time'];
                    if(isset($_POST['Username']) && !empty($_POST["Username"]) && isset($_POST['Item_Name']) && !empty($_POST["Item_Name"]) && isset($_POST['ServiceStore_Name']) && !empty($_POST["ServiceStore_Name"])){
                        if ($u_Appointment_Time != "nil") {
                            $update = "A.Appointment_Time='$u_Appointment_Time'";
                            $u_sql = "UPDATE booking AS A INNER JOIN serviceitem AS B ON A.Item_Id = B.Item_Id INNER JOIN servicestore AS C ON A.ServiceStore_Id = C.ServiceStore_Id INNER JOIN paymenttype AS D ON A.Payment_Type_Id = D.Payment_Type_Id INNER JOIN invoice AS E ON A.Invoice_Id = E.Invoice_Id INNER JOIN orderstatus AS F ON A.Status_Id = F.Status_Id INNER JOIN Customer AS G ON A.Customer_Id = G.Customer_Id SET ".$update." WHERE A.Booking_Id = $Confirm_Booking_Id";
                            $update_result = mysqli_query($conn, $u_sql);
                        }
                    }
                    else {
                        echo "<script>alert('Please enter the User Name, Item Name, ServiceStore Name')</script>";
                    }
                }
                if (isset($_POST['u_Invoice']) && !empty($_POST["u_Invoice"])) {
                    $u_Invoice = $_POST['u_Invoice'];
                    if ($u_Invoice != "nil") {
                        $update = "A.Invoice_Id=$u_Invoice";
                        $u_sql = "UPDATE booking AS A INNER JOIN serviceitem AS B ON A.Item_Id = B.Item_Id INNER JOIN servicestore AS C ON A.ServiceStore_Id = C.ServiceStore_Id INNER JOIN paymenttype AS D ON A.Payment_Type_Id = D.Payment_Type_Id INNER JOIN invoice AS E ON A.Invoice_Id = E.Invoice_Id INNER JOIN orderstatus AS F ON A.Status_Id = F.Status_Id INNER JOIN Customer AS G ON A.Customer_Id = G.Customer_Id SET ".$update." WHERE A.Booking_Id = $Confirm_Booking_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                }
                if (isset($_POST['u_Remarks']) && !empty($_POST["u_Remarks"])) {
                    $u_Remarks = $_POST['u_Remarks'];
                    $update = "A.Remarks='$u_Remarks'";
                    $u_sql = "UPDATE booking AS A INNER JOIN serviceitem AS B ON A.Item_Id = B.Item_Id INNER JOIN servicestore AS C ON A.ServiceStore_Id = C.ServiceStore_Id INNER JOIN paymenttype AS D ON A.Payment_Type_Id = D.Payment_Type_Id INNER JOIN invoice AS E ON A.Invoice_Id = E.Invoice_Id INNER JOIN orderstatus AS F ON A.Status_Id = F.Status_Id INNER JOIN Customer AS G ON A.Customer_Id = G.Customer_Id SET ".$update." WHERE A.Booking_Id = $Confirm_Booking_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_Status']) && !empty($_POST["u_Status"])) {
                    $u_Status = $_POST['u_Status'];
                    if ($u_Status != "nil") {
                        $update = "A.Status_Id='$u_Status'";
                        $u_sql = "UPDATE booking AS A INNER JOIN serviceitem AS B ON A.Item_Id = B.Item_Id INNER JOIN servicestore AS C ON A.ServiceStore_Id = C.ServiceStore_Id INNER JOIN paymenttype AS D ON A.Payment_Type_Id = D.Payment_Type_Id INNER JOIN invoice AS E ON A.Invoice_Id = E.Invoice_Id INNER JOIN orderstatus AS F ON A.Status_Id = F.Status_Id INNER JOIN Customer AS G ON A.Customer_Id = G.Customer_Id SET ".$update." WHERE A.Booking_Id = $Confirm_Booking_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                }
                $ut = "UPDATE booking SET Updated = '$timestamp' WHERE Booking_Id = $Confirm_Booking_Id";
                $update_time = mysqli_query($conn, $ut);
                $filter = "A.Booking_Id = $Confirm_Booking_Id";
                }

            }
        
            if ($filter == "") {
                $filter = "1 = 1";
            }
            
            $sql = "SELECT ".$Search." FROM booking AS A INNER JOIN serviceitem AS B ON A.Item_Id = B.Item_Id INNER JOIN servicestore AS C ON A.ServiceStore_Id = C.ServiceStore_Id INNER JOIN paymenttype AS D ON A.Payment_Type_Id = D.Payment_Type_Id INNER JOIN invoice AS E ON A.Invoice_Id = E.Invoice_Id INNER JOIN orderstatus AS F ON A.Status_Id = F.Status_Id INNER JOIN Customer AS G ON A.Customer_Id = G.Customer_Id WHERE ".$filter;

            $Search_result = mysqli_query($conn, $sql);

            //Payment_Type
            $sql_Payment_Type = "SELECT * FROM paymenttype";
            $Payment_Type_List = mysqli_query($conn, $sql_Payment_Type);

            //Invoice
            $sql_Invoice = "SELECT * FROM invoice";
            $Invoice_List = mysqli_query($conn, $sql_Invoice);
            $u_Invoice_List = mysqli_query($conn, $sql_Invoice);

            //Invoice
            $sql_Status = "SELECT * FROM orderstatus";
            $Status_List = mysqli_query($conn, $sql_Status);
            $u_Status_List = mysqli_query($conn, $sql_Status);

            $ShowField = array("Username", "Item_Name", "ServiceStore_Name", "Status", "Appointment_Date", "Appointment_Time", "Quantity", "Payment");

            //显示的列
            if (isset($_POST['ShowField'])) {
                $ShowField = $_POST["ShowField"];
            }
            mysqli_close($conn);
        }
        else {
            header("Location:management.php");
        }
    ?>
</head>
<body <?php 
      if(isset($_POST['u_Booking_Id']) && !empty($_POST["u_Booking_Id"])) { 
          echo "onload=\"appear('update')\"";
      }?>>
<!------------------------顶部信息--------------------------->
	<header id="header_top">
		<div class="main-content">
			<div>
				<div class="pull-left postion1"></div>
			</div>
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
                    echo "<div class='pull-left position-name'>Your current location: ".$data["ip"]."(".$data["country"].")</div>";
                }
                else {
                    echo "<div class='pull-left position-name'>Your location cannot be confirmed.</div>";
                }
            ?>
			<div class="pull-left header-login">
				<div class="row"> 
					<?php 
                        if(isset($_SESSION["Administrator_Name"]) && !empty($_SESSION['Administrator_Name'])) {
                            echo
                            "<div class='col-left text-right'>
						      <span>Hi, ".$_SESSION['Administrator_Name']."</span>
					       </div>   
					       <div class='col-right text-center'>|</div>
					       <div class='pull-left text-left'>    
						      <a href='logout.php'>Logout</a>
					       </div>";
                        }
                        else 
                        {
                            echo 
                          "<div class='col-left text-right'>
						   <a href='login.php' class='green'>Login</a>
					       </div>  
					       <div class='col-right text-center'>|</div>
					       <div class='pull-left text-left'>    
						      <a href='management_register.php'>Register</a>
					       </div>";
                        }
                    ?>
				</div>
			</div>
			<div class="header-hotline" name=""></div>
		</div>
		<div class="clearfixed"></div>
	</header>
    
    <header id="header_bottom">
		<div class="main-content">
			<div class="row">
                <div class="pull-left" id="Logo_box">  
				    <a href="#"><img id="Logo_size" src="images/header/logo.png" alt=""></a>    
				</div>
				<div class="pull-left" id="Title_box">
				    <a href="#"><img id="Title_size" src="images/header/title.png" alt=""></a>  
				</div>
			</div>
		</div>
	</header>
    <div class="reset"></div>

<!----------------------------导航栏----------------------------->
	<nav id="menu">
		<div class="main-content">
		<ul>
			<li class="pull-left text-center"><a href="management.php" class="white">HomePage</a></li>
			<li class="pull-left text-center"><a href="management_customer.php" class="white">Customer</a></li>
			<li class="pull-left text-center"><a href="management_booking.php" class="white">Booking</a></li>
			<li class="pull-left text-center"><a href="management_service.php" class="white">Services</a></li>
			<li class="pull-left text-center"><a href="management_servicestore.php" class="white">ServiceStore</a></li>
            <li class="pull-left text-center"><a href="management_management.php" class="white">Management</a></li>
		</ul>
		</div>
    </nav>
    
<!----------------------------内容----------------------------->
    <nav class='panel' style='margin-top:40px'>
        <div class='panel1_3'>
            <div class='cardwnd'>
                <div class='wnd_content'>
                    <ul class="backendwnd-nav">
                        <li><p><a style="color:white" href="javascript:void(0);">Service Items</a></p></li>
                    </ul>                     
                    <div class='infownd'>
                        <div class='infownd-box' style='border-radius:0 0 3px 3px;'>
                            <p class='center-left-title' onclick="appear('search')">Condition</p>
                            <div class='content'>
                                <ul>
                                    <li><a onclick="appear('el1')">— User Name</a></li>
                                    <li><a onclick="appear('el2')">— Item Name</a></li>
                                    <li><a onclick="appear('el3')">— ServiceStore Name</a></li>
                                    <li><a onclick="appear('el4')">— Appointment Date</a></li>
                                    <li><a onclick="appear('el5')">— Appointment Time</a></li>
                                    <li><a onclick="appear('el6')">— Quantity</a></li>
                                    <li><a onclick="appear('el7')">— Payment</a></li>
                                    <li><a onclick="appear('el8')">— Payment Type</a></li>
                                    <li><a onclick="appear('el9')">— Invoice</a></li>
                                    <li><a onclick="appear('el10')">— Remarks</a></li>
                                    <li><a onclick="appear('el11')">— Status</a></li>
                                </ul>
                            </div>
                            <p class='center-left-title' onclick="appear('update')">Update</p>
                            <div class='content'>
                                <ul>
                                    <li><a onclick="appear('el12')">— Appointment Date</a></li>
                                    <li><a onclick="appear('el13')">— Appointment Time</a></li>
                                    <li><a onclick="appear('el14')">— Invoice</a></li>
                                    <li><a onclick="appear('el15')">— Remarks</a></li>
                                    <li><a onclick="appear('el16')">— Status</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='panel2_3' style='padding-left:60px;'>
            <div class='mainwnd first-wnd'>
                <div class='wnd_content'>
                    <div class='infownd'>
                        <div class='infownd-box'>
                            <form class='baction bsearchform form1' enctype="multipart/form-data" method='post' action='management_booking.php' name='form1'>
                            <?php 
                                if (isset($show_result) && !empty($show_result)) {
                                    if (!$show_result || mysqli_num_rows($show_result) == 0){

                                    }
                                    else {
                                        $one_show_result = mysqli_fetch_assoc($show_result); 
                                    }
                                }
                            ?>
                                <div id="Sreach_Update_By_Field">
                                    <h3 class='box-head'><label>Condition</label></h3>
                                    <input name='Confirm_Booking_Id' value="<?php 
                                                                            if (isset($show_result) && !empty($show_result)) {
                                                                                echo $one_show_result['Booking_Id'];
                                                                            }
                                                                            ?>" type="hidden">
                                    <div id="el1">
                                        <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el1')"/>
                                        <label class="labelname">User Name: </label>
                                        <input class="inputstyle" type="text" name='Username' value="<?php 
                                                                                                    if (isset($show_result) && !empty($show_result)) {
                                                                                                        echo $one_show_result['Username'];
                                                                                                    }
                                                                                                    ?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Username_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch1" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch1">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el2">
                                        <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el2')"/>
                                        <label class="labelname">Item Name: </label>
                                        <input class="inputstyle" type="text" name='Item_Name' value="<?php
                                                                                                    if (isset($show_result) && !empty($show_result)) {
                                                                                                        echo $one_show_result['Item_Name']; 
                                                                                                    }
                                                                                                    ?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Item_Name_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch1" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch1">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el3">
                                        <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el3')"/>
                                        <label class="labelname">ServiceStore Name: </label>
                                        <input class="inputstyle" type="text" name='ServiceStore_Name' value="<?php 
                                                                                                            if (isset($show_result) && !empty($show_result)) {
                                                                                                                echo $one_show_result['ServiceStore_Name']; 
                                                                                                            }
                                                                                                            ?>" />
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="ServiceStore_Name_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch2" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch2">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el4">
                                        <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el4')"/>
                                        <label class="labelname">Appointment Date: </label>
                                        <input id="Appointment_Date" class="inputstyle Wdate" type="text" onClick="WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd'})" style="height:25px;" name="Appointment_Date" value="<?php 
                                                                                            if (isset($show_result) && !empty($show_result)) {
                                                                                                echo $one_show_result['Appointment_Date'];
                                                                                            } ?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Appointment_Date_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch3" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch3">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el5">
                                        <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el5')"/>
                                        <label class="labelname">Appointment Time: </label>
                                        <select class="dropdown" type="text" name="Appointment_Time">
                                        <option value="all">All</option>
                                        <?php
                                            if (isset($show_result) && !empty($show_result)) {
                                                if($one_show_result['Appointment_Time'] == '9:00~10:00') {
                                                ?>
                                                    <option value='9:00~10:00' selected>9:00~10:00</option>
                                                <?php
                                                }else {
                                                ?>
                                                    <option value='9:00~10:00'>9:00~10:00</option>
                                                <?php
                                                }
                                                if($one_show_result['Appointment_Time'] == '10:00~11:00') {
                                                ?>
                                                    <option value='10:00~11:00' selected>10:00~11:00</option>
                                                <?php
                                                }else {
                                                ?>
                                                    <option value='10:00~11:00'>10:00~11:00</option>
                                                <?php
                                                }
                                                if($one_show_result['Appointment_Time'] == '11:00~12:00') {
                                                ?>
                                                    <option value='11:00~12:00' selected>11:00~12:00</option>
                                                <?php
                                                }else {
                                                ?>
                                                    <option value='11:00~12:00'>11:00~12:00</option>
                                                <?php
                                                }
                                                if($one_show_result['Appointment_Time'] == '12:00~13:00') {
                                                ?>
                                                    <option value='12:00~13:00' selected>12:00~13:00</option>
                                                <?php
                                                }else {
                                                ?>
                                                    <option value='12:00~13:00'>12:00~13:00</option>
                                                <?php
                                                }
                                                if($one_show_result['Appointment_Time'] == '13:00~14:00') {
                                                ?>
                                                    <option value='13:00~14:00' selected>13:00~14:00</option>
                                                <?php
                                                }else {
                                                ?>
                                                    <option value='13:00~14:00'>13:00~14:00</option>
                                                <?php
                                                }
                                                if($one_show_result['Appointment_Time'] == '14:00~15:00') {
                                                ?>
                                                    <option value='14:00~15:00' selected>14:00~15:00</option>
                                                <?php
                                                }else {
                                                ?>
                                                    <option value='14:00~15:00'>14:00~15:00</option>
                                                <?php
                                                }
                                                if($one_show_result['Appointment_Time'] == '15:00~16:00') {
                                                ?>
                                                    <option value='15:00~16:00' selected>15:00~16:00</option>
                                                <?php
                                                }else {
                                                ?>
                                                    <option value='15:00~16:00'>15:00~16:00</option>
                                                <?php
                                                }
                                                if($one_show_result['Appointment_Time'] == '16:00~17:00') {
                                                ?>
                                                    <option value='16:00~17:00' selected>16:00~17:00</option>
                                                <?php
                                                }else {
                                                ?>
                                                    <option value='16:00~17:00'>16:00~17:00</option>
                                                <?php
                                                }
                                            } 
                                            else {
                                            ?>
                                                <option value='9:00~10:00'>9:00~10:00</option>
                                                <option value='10:00~11:00'>10:00~11:00</option>
                                                <option value='11:00~12:00'>11:00~12:00</option>
                                                <option value='12:00~13:00'>12:00~13:00</option>
                                                <option value='13:00~14:00'>13:00~14:00</option>
                                                <option value='14:00~15:00'>14:00~15:00</option>
                                                <option value='15:00~16:00'>15:00~16:00</option>
                                                <option value='16:00~17:00'>16:00~17:00</option>
                                            <?php
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <div id="el6">
                                        <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el6')"/>
                                        <label class="labelname" >Quantity: </label>
                                        <input id="Quantity_Range_Dropdown_Input1" class="inputstyle" type="number" name='Quantity' value="<?php 
                                                                                                        if (isset($show_result) && !empty($show_result)) {
                                                                                                            echo $one_show_result['Quantity'];
                                                                                                        }?>">
                                        <input id="Quantity_Range_Dropdown_Input2" style="display:none;" class="inputstyle" type="number" name='Quantity2'>
                                        <select class="dropdown" name="Quantity_Range" id="Quantity_Range_Dropdown" onclick="getSelectValue(this);">
                                            <option value="equal">equal</option>
                                            <option value="larger">larger</option>
                                            <option value="smaller">smaller</option>
                                            <option value="between">between</option>
                                        </select>
                                    </div>
                                    <div id="el7">
                                        <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el7')"/>
                                        <label class="labelname">Payment: </label>
                                        <input id="Payment_Dropdown_Input1" class="inputstyle" type="number" name='Payment' value="<?php 
                                                                                                        if (isset($show_result) && !empty($show_result)) {
                                                                                                            echo $one_show_result['Payment'];
                                                                                                        }?>">
                                        <input id="Payment_Dropdown_Input2" style="display:none;" class="inputstyle" type="number" name='Payment2'>
                                        <select class="dropdown" name="Payment_Range" id="Payment_Dropdown" onclick="getSelectValue(this);">
                                            <option value="equal">equal</option>
                                            <option value="larger">larger</option>
                                            <option value="smaller">smaller</option>
                                            <option value="between">between</option>
                                        </select>
                                    </div>
                                    <div id="el8">
                                        <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el8')"/>
                                        <label class="labelname">Payment Type: </label>
                                        <select style="width:200px;" class="dropdown" type="text" name="Payment_Type">
                                            <option value="all">All</option>
                                            <?php
                                            while ($one_result = mysqli_fetch_assoc($Payment_Type_List)) {
                                                if($one_result['Payment_Type'] == $one_show_result['Payment_Type']) {
                                                ?>
                                                    <option value="<?php echo $one_result['Payment_Type_Id']; ?>" selected><?php echo $one_result['Payment_Type'];?></option>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <option value="<?php echo $one_result['Payment_Type_Id']; ?>"><?php echo $one_result['Payment_Type'];?></option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="el9">
                                        <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el9')"/>
                                        <label class="labelname">Invoice: </label>
                                        <select class="dropdown" type="text" name="Invoice">
                                            <option value="all">All</option>
                                            <?php
                                            while ($one_result = mysqli_fetch_assoc($Invoice_List)) {
                                                if($one_result['Invoice'] == $one_show_result['Invoice']) {
                                                ?>
                                                    <option value="<?php echo $one_result['Invoice_Id']; ?>" selected><?php echo $one_result['Invoice'];?></option>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <option value="<?php echo $one_result['Invoice_Id']; ?>"><?php echo $one_result['Invoice'];?></option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="el10">
                                        <img class="redcross" src="images/article/red-cross.png" style="width:12px;position:relative;top:-24px;" onclick="disappear('el10')"/>
                                        <label class="labelname" style="position:relative;top:-25px;">Remarks: </label>
                                        <textarea class="inputstyle" type="text" name='Remarks' style="margin-top:12px;height:60px;width:200px;"><?php 
                                            if (isset($show_result) && !empty($show_result)) {
                                                echo $one_show_result['Remarks']; 
                                            }?></textarea>
                                        <div class="onoffswitch" style="position:relative;top:-20px;">
                                            <input type="checkbox" name="Remarks_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch2" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch2">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el11">
                                        <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el11')"/>
                                        <label class="labelname">Status: </label>
                                        <select style="width:200px;" class="dropdown" type="text" name="Status">
                                            <option value="all">All</option>
                                            <?php
                                            while ($one_result = mysqli_fetch_assoc($Status_List)) {
                                                if (isset($show_result) && !empty($show_result)) {
                                                    if($one_result['Status'] == $one_show_result['Status']) {
                                                    ?>
                                                        <option value="<?php echo $one_result['Status_Id']; ?>" selected><?php echo $one_result['Status'];?></option>
                                                    <?php
                                                    }
                                                    else {
                                                    ?>
                                                        <option value="<?php echo $one_result['Status_Id']; ?>"><?php echo $one_result['Status'];?></option>
                                                    <?php
                                                    }
                                                }
                                                else {
                                                ?>
                                                    <option value="<?php echo $one_result['Status_Id']; ?>" ><?php echo $one_result['Status'];?></option>
                                                <?php
                                                }  
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="Update_Field" style="display:none;">
                                    <h3 class='box-head'><label>Update</label></h3>
                                    <div id="el12">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el12')"/>
                                        <label class="labelname">Appointment Date: </label>
                                        <input id="u_Appointment_Date" class="inputstyle Wdate" type="text" onClick="WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd'})" style="height:25px;" name="u_Appointment_Date">
                                    </div>
                                    <div id="el13">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el13')"/>
                                        <label class="labelname">Appointment Time: </label>
                                        <select class="dropdown" type="text" name="u_Appointment_Time">
                                            <option value="nil">-----</option>
                                            <option value='9:00~10:00'>9:00~10:00</option>
                                            <option value='10:00~11:00'>10:00~11:00</option>
                                            <option value='11:00~12:00'>11:00~12:00</option>
                                            <option value='12:00~13:00'>12:00~13:00</option>
                                            <option value='13:00~14:00'>13:00~14:00</option>
                                            <option value='14:00~15:00'>14:00~15:00</option>
                                            <option value='15:00~16:00'>15:00~16:00</option>
                                            <option value='16:00~17:00'>16:00~17:00</option>
                                        </select>
                                    </div>
                                    <div id="el14">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el14')"/>
                                        <label class="labelname">Invoice: </label>
                                        <select class="dropdown" type="text" name="u_Invoice">
                                            <option value="nil">-----</option>
                                        <?php
                                            WHILE ($one_result = mysqli_fetch_assoc($u_Invoice_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['Invoice_Id']; ?>"><?php echo $one_result['Invoice'];?></option>
                                            <?php
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <div id="el15">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;top:-25px;width:12px;" onclick="disappear('el15')"/>
                                        <label class="labelname" style="position:relative;top:-25px;">Remarks: </label>
                                        <textarea class="inputstyle" type="text" name='u_Remarks' style="height:60px;width:200px;position:relative;top:4px;"></textarea>
                                    </div>
                                    <div id="el16">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el16')"/>
                                        <label class="labelname">Status: </label>
                                        <select style="width:200px;" class="dropdown" type="text" name="u_Status">
                                            <option value="nil">-----</option>
                                        <?php
                                            WHILE ($one_result = mysqli_fetch_assoc($u_Status_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['Status_Id']; ?>"><?php echo $one_result['Status'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <h3 class='box-head'><label>Show Fields</label></h3>
                                <div class="checkbox_box">
                                    <div><input type="checkbox" id="checkbox-1-1" class="regular-checkbox" name="ShowField[]" value="Booking_No"/><label for="checkbox-1-1"></label><span>Booking No&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-2" class="regular-checkbox" name="ShowField[]" value="Username" checked/><label for="checkbox-1-2"></label><span>Username&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-3" class="regular-checkbox" name="ShowField[]" value="Item_Name" checked/><label for="checkbox-1-3"></label><span>Item Name&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-4" class="regular-checkbox" name="ShowField[]" value="ServiceStore_Name" checked/><label for="checkbox-1-4"></label><span>ServiceStore Name&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-5" class="regular-checkbox" name="ShowField[]" value="Status" checked/><label for="checkbox-1-5"></label><span>Status&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-6" class="regular-checkbox" name="ShowField[]" value="Appointment_Date" checked/><label for="checkbox-1-6"></label><span>Appointment_Date&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-7" class="regular-checkbox" name="ShowField[]" value="Appointment_Time" checked/><label for="checkbox-1-7"></label><span>Appointment_Time&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-8" class="regular-checkbox" name="ShowField[]" value="Quantity" checked/><label for="checkbox-1-8"></label><span>Quantity&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-9" class="regular-checkbox" name="ShowField[]" value="Payment" checked/><label for="checkbox-1-9"></label><span>Payment&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-10" class="regular-checkbox" name="ShowField[]" value="Payment_Type" /><label for="checkbox-1-10"></label><span>Payment Type&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-11" class="regular-checkbox" name="ShowField[]" value="Invoice"/><label for="checkbox-1-11"></label><span>Invoice&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-12" class="regular-checkbox" name="ShowField[]" value="Remarks"/><label for="checkbox-1-12"></label><span>Remarks&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-13" class="regular-checkbox" name="ShowField[]" value="Created"/><label for="checkbox-1-13"></label><span>Created&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-14" class="regular-checkbox" name="ShowField[]" value="Updated"/><label for="checkbox-1-14"></label><span>Updated&nbsp;&nbsp;</span></div>
                                </div>
                                <div class="forms_sub">
                                    <input id="forms_sub_input" type="submit" value="Search Information" name="Submit">
                                </div>
                            </form>
                                <div class='reset'></div>
                                <?php
                                if (!$Search_result || mysqli_num_rows($Search_result) == 0){
                                }
                                else {
                                ?>
                                    <?php
                                        if(count($ShowField)>13){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-380px;z-index:100;">
                                        <?php
                                        }
                                        else if(count($ShowField)>12){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-350px;z-index:100;">
                                        <?php
                                        }
                                        else if(count($ShowField)>11){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-300px;z-index:100;">
                                        <?php
                                        }
                                        else if(count($ShowField)>10){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-220px;z-index:100;">
                                        <?php
                                        }
                                        else if(count($ShowField)>9){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-170px;z-index:100;">
                                        <?php
                                        }
                                        else if(count($ShowField)>8){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-80px;z-index:100;">
                                        <?php
                                        }
                                        else {
                                        ?>
                                        <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:0px;z-index:100;">
                                        <?php
                                        }
                                        ?>
                                        <tr class='tb-title'>
                                        <?php if(count($ShowField)>0){
                                        ?>
                                            <td width='65px' style='color:white;background:#24303c'>Edit</td>
                                        <?php
                                        }
                                        for ($i=0; $i<count($ShowField); $i++) {
                                        ?>
                                            <td width='65px' style='color:white;background:#24303c'><?php echo $ShowField[$i]; ?></td>
                                        <?php
                                        }
                                        ?>
                                        </tr>
                                        <?php
                                        while ($one_result = mysqli_fetch_assoc($Search_result)) {
                                        ?>
                                        <tr>
                                            <?php
                                            if(count($ShowField)>0){
                                            ?>
                                            <td>
                                                <form method="post" action="management_booking.php">
                                                <input type="submit" class="myButton" id="Circle_btn" value="Update&nbsp;&gt;&gt;" />
                                                <input style="width:15px;" type="text" name="u_Booking_Id" value="<?php echo $one_result["Booking_Id"]; ?>" hidden/>
                                                </form>
                                                <form method="post" action="management_booking.php" onsubmit="return confirm('Are you sure cannel this booking?')">
                                                <?php
                                                    if($one_result['Status'] == 'Cancelled by Admin') {
                                                        
                                                    }
                                                    else {
                                                        echo "<input type='submit' class='myButton' id='Circle_btn' value='Cancel&nbsp;&gt;&gt;' />";
                                                    }
                                                ?>   
                                                <input style="width:15px;" type="text" name="c_Booking_Id" value="<?php echo $one_result["Booking_Id"]; ?>" hidden/>
                                                </form>
                                            </td>
                                            <?php
                                                for ($i=0; $i<count($ShowField); $i++) {
                                                ?>
                                                    <td><?php echo $one_result["$ShowField[$i]"]; ?></td>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                <?php
                                }
                                ?>
                            <div class='reset'></div>
                        </div>
                    </div>
                </div>            
            </div> 
            <div class='reset'></div>
        </div>
    </nav>

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
</body>
</html>

<script>
    function getSelectValue(e) {
    var mySelect = document.getElementById(e.id);
        if(mySelect.options[3].selected == true){
            document.getElementById(e.id+"_Input2").style.display = "inline";
        }
        else {
            document.getElementById(e.id+"_Input2").style.display = "none";
        }
    }

/* ================ 位置选择 ================== */
    var clicktimes = 0;
	function show_postion(){
		$("#postion_btn").click();
	}

	function showHide(){
		$("#menu_mycarservice").removeClass('hidden');
	}

	function hideShow(){
		$("#menu_mycarservice").addClass('hidden');
	}
    
    function show_and_Hide(){
        if (clicktimes == 0){
            $("#menu_mycarservice").removeClass('hidden');
            clicktimes = 1;
        }
        else {
            $("#menu_mycarservice").addClass('hidden');
            clicktimes = 0;
        }
        
    }
    
/* ================ 新闻动态 ================== */
    $(document).ready(function(){
         $("#j_Focus").Focus();
    });
    
/* ================ 底部弹框 ================== */
	$(document).ready(function () {
        $('#showshop').attr('src', '#');
    });

    $("#chakan2v").click(function () {
        var w = $(window).width();
        $("#chakan2v").hide();
        $("#footercar > div").show();
        $("#footercar").show();
        $("#footercar").animate({"width": w + "px"}, "slow");
    });

    var myDate = new Date();
    myDate.setTime(myDate.getTime() + 360 * 24 * 60 * 60 * 1000);
    $('.footerclose').click(function () {
        if (getCookie('showfooter') == null) {
            setCookie('showfooter', 'showfooter', myDate, '../index.html');
        }

        $("#footercar").animate({width: 0}, "slow");
        $("#footercar > div").hide();
        $('#chakan2v').show();
    });

    $('.gzwxts').click(function () {
        if (getCookie('showfooter') == null) {
            setCookie('showfooter', 'showfooter', myDate, '../index.html');
        }

        $("#footercar").animate({width: 0}, "slow");
        $("#footercar > div").hide();
        $('#chakan2v').show();
    });

    if (getCookie('showfooter') != null) {
        $('#footercar').hide();
        $('#footercar > div').hide();
        $('#chakan2v').show();
    }
    else {
        var w = $(window).width();
        $('#footercar').show();
        $('#footercar > div').show();
        $("#footercar").animate({"width": w + "px"}, "slow");
        $('#chakan2v').hide();
    }

	function setCookie(sName,sValue,oExpires,sPath,sDmomain,bSecure){
		var sCookie = sName + "="+encodeURIComponent(sValue);
		if(oExpires){
			sCookie += "; expires="+ oExpires.toGMTString();
		}
		if(sPath){
			sCookie += "; path="+ sPath;
		}
		if(sDmomain){
			sCookie += "; domain="+sDmomain;
		}
	
		if(bSecure){
			sCookie += "; secure=";
		}
		document.cookie = sCookie;
}

	function  getCookie(sName){
		var sRE = "(?:; )?"+ sName + "=([^;]*);?"
		var oRE = new RegExp(sRE);
		if(oRE.test(document.cookie)){
			return decodeURIComponent(RegExp["$1"]);
		}else {
			return null;
		}
	}

/* ================= 边栏 =================== */
$(function(){
	$('.slide .icon li').not('.up,.down').mouseenter(function(){
		$('.slide .info').addClass('hover');
		$('.slide .info li').hide();
		$('.slide .info li.'+$(this).attr('class')).show();//.slide .info li.qq
	});
	$('.slide').mouseleave(function(){
		$('.slide .info').removeClass('hover');
	});
	
	$('#btn').click(function(){
		$('.slide').toggle();
		if($(this).hasClass('index_cy')){
			$(this).removeClass('index_cy');
			$(this).addClass('index_cy2');
		}else{
			$(this).removeClass('index_cy2');
			$(this).addClass('index_cy');
		}
	});
});
    
/* ================ management ================== */
    function disappear(el) {
        if(el == "el1") {
            document.getElementById("el1").style.display="none";
        }
        else if(el == "el2") {
            document.getElementById("el2").style.display="none";
        }
        else if(el == "el3") {
            document.getElementById("el3").style.display="none";
        }
        else if(el == "el4") {
            document.getElementById("el4").style.display="none";
        }
        else if(el == "el5") {
            document.getElementById("el5").style.display="none";
        }
        else if(el == "el6") {
            document.getElementById("el6").style.display="none";
        }
        else if(el == "el7") {
            document.getElementById("el7").style.display="none";
        }
        else if(el == "el8") {
            document.getElementById("el8").style.display="none";
        }
        else if(el == "el9") {
            document.getElementById("el9").style.display="none";
        }
        else if(el == "el10") {
            document.getElementById("el10").style.display="none";
        }
        else if(el == "el11") {
            document.getElementById("el11").style.display="none";
        }
        else if(el == "el12") {
            document.getElementById("el12").style.display="none";
        }
        else if(el == "el13") {
            document.getElementById("el13").style.display="none";
        }
        else if(el == "el14") {
            document.getElementById("el14").style.display="none";
        }
        else if(el == "el15") {
            document.getElementById("el15").style.display="none";
        }
        else if(el == "el16") {
            document.getElementById("el16").style.display="none";
        }
    }
    function appear(el) {
        if(el == "el1") {
            document.getElementById("el1").style.display="block";
        }
        else if(el == "el2") {
            document.getElementById("el2").style.display="block";
        }
        else if(el == "el3") {
            document.getElementById("el3").style.display="block";
        }
        else if(el == "el4") {
            document.getElementById("el4").style.display="block";
        }
        else if(el == "el5") {
            document.getElementById("el5").style.display="block";
        }
        else if(el == "el6") {
            document.getElementById("el6").style.display="block";
        }
        else if(el == "el7") {
            document.getElementById("el7").style.display="block";
        }
        else if(el == "el8") {
            document.getElementById("el8").style.display="block";
        }
        else if(el == "el9") {
            document.getElementById("el9").style.display="block";
        }
        else if(el == "el10") {
            document.getElementById("el10").style.display="block";
        }
        else if(el == "el11") {
            document.getElementById("el11").style.display="block";
        }
        else if(el == "el12") {
            document.getElementById("el12").style.display="block";
        }
        else if(el == "el13") {
            document.getElementById("el13").style.display="block";
        }
        else if(el == "el14") {
            document.getElementById("el14").style.display="block";
        }
        else if(el == "el15") {
            document.getElementById("el15").style.display="block";
        }
        else if(el == "el16") {
            document.getElementById("el16").style.display="block";
        }
        else if(el == "search") {
            document.getElementById("Update_Field").style.display = "none";
            document.getElementById("forms_sub_input").style.backgroundColor = "#4eb233";
            document.getElementById("forms_sub_input").value = "Search Information";
        }
        else if(el == "update") {
            document.getElementById("Update_Field").style.display = "block";
            document.getElementById("forms_sub_input").style.backgroundColor = "#4eb233";
            document.getElementById("forms_sub_input").value = "Update Information";
        }
    }

<!--移动端页面跳转-->
$(window).resize(function(){
    if(document.body.clientWidth<=768){
        window.location.href="../Phone/order_info_Phone.html";
    }
});
</script>