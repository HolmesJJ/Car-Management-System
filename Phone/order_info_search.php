<!DOCTYPE html>
<html>
<head>
    <title>Order Info Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <!---------- 菜单 ---------->
    <script src="js/modernizr.custom.js"></script>
    <script src="js/modernizr-2.6.2.min.js"></script>

    <link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/management.css' type='text/css' />
    
    <!-------汽车轮播------->
    <link rel="stylesheet" href="css/edslider.css" type='text/css' />
	<link rel="stylesheet" href="css/animate-custom.css" type='text/css' />
    
    <style>
        .dropdown {
            max-width:100px;
        }
    </style>
    
    <?php
        session_start();
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());

        $conn = mysqli_connect("localhost", "root", "","db160015Q_project");
  
        if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
            $Username = $_SESSION['Username'];
            if(isset($_POST["car_model"])) {
                $Car_Model = $_POST["car_model"];
                $_SESSION['Car_Model'] = $_POST["car_model"];
                $u_sql = "UPDATE customer SET Car_Model = '$Car_Model' WHERE Username = '$Username'";
                $update_result = mysqli_query($conn, $u_sql);
                $d_sql = "DELETE A FROM cart AS A INNER JOIN customer AS B ON A.Customer_Id = B.Customer_Id WHERE B.Username = '$Username'";
                $delete_result = mysqli_query($conn, $d_sql);
                unset($_SESSION['Item_Id']);
                unset($_SESSION['Item_Name']);
                unset($_SESSION['ServiceStore_Id']);
                unset($_SESSION['ServiceStore_Name']);
                mysqli_close($conn);
                header("Location:index.php");
            }
            else {
                $sql_car_model = "SELECT Car_Model FROM customer WHERE Username = '$Username'";
                $car_model_result = mysqli_query($conn, $sql_car_model);
                $one_car_model_result = mysqli_fetch_assoc($car_model_result);
                $Car_Model = $one_car_model_result['Car_Model'];
                $_SESSION['Car_Model'] = $Car_Model;
                
                $customer_sql = "SELECT Customer_Id FROM customer WHERE Username = '$Username'";
                $customer_id_result = mysqli_query($conn, $customer_sql);
                $one_customer_id = mysqli_fetch_assoc($customer_id_result);
                $Customer_Id = $one_customer_id['Customer_Id'];

                if(isset($_POST['Cancel_Booking']) && !empty($_POST['Cancel_Booking'])) {
                    $u_Booking_Id = $_POST['Cancel_Booking'];

                    $date_sql = "SELECT Appointment_Date FROM booking WHERE Booking_Id = $u_Booking_Id";
                    $date_result = mysqli_query($conn, $date_sql);
                    $one_date = mysqli_fetch_assoc($date_result);
                    $old_Appointment_Date = $one_date['Appointment_Date'];
                    $day = ceil((strtotime($old_Appointment_Date) - strtotime($timestamp))/86400);
                    if((int)$day > 7) {
                        $u_sql = "UPDATE booking SET Status_Id = 2, Updated = '$timestamp' WHERE Booking_Id = $u_Booking_Id";
                        $u_result = mysqli_query($conn, $u_sql);
                    }
                    else {
                        echo "<script>alert('Sorry! Cancellation can only be done before 7 days.')</script>";
                    }

                }

                $Search = "A.Booking_Id, A.Booking_No, B.Item_Picture, B.Item_Name, C.ServiceStore_Name, A.Appointment_Date, A.Appointment_Time, A.Quantity, A.Payment, D.Payment_Type, E.Invoice, A.Remarks, F.Status, A.Created, A.Updated";

                $filter = " WHERE 1 = 1 && A.Customer_Id = $Customer_Id ";

                if (isset($_POST['Item_Name']) && !empty($_POST["Item_Name"])) {
                    $Item_Name = $_POST['Item_Name'];
                    if(isset($_POST["Item_Name_Yes_No"])){
                        $Item_Name = " LIKE '%$Item_Name%'";
                    }
                    else {
                        $Item_Name = " NOT LIKE '%$Item_Name%'";
                    }
                    $filter = $filter." && B.Item_Name$Item_Name";
                }
                if (isset($_POST['ServiceStore_Name']) && !empty($_POST["ServiceStore_Name"])) {
                    $ServiceStore_Name = $_POST['ServiceStore_Name'];
                    if(isset($_POST["ServiceStore_Name_Yes_No"])){
                        $ServiceStore_Name = " LIKE '%$ServiceStore_Name%'";
                    }
                    else {
                        $ServiceStore_Name = " NOT LIKE '%$ServiceStore_Name%'";
                    }
                    $filter = $filter." && C.ServiceStore_Name$ServiceStore_Name";
                }
                if (isset($_POST['Appointment_Date']) && !empty($_POST["Appointment_Date"])) {
                    $Appointment_Date = $_POST['Appointment_Date'];
                    if(isset($_POST["Appointment_Date_Yes_No"])){
                        $Appointment_Date = "='$Appointment_Date%'";
                    }
                    else {
                        $Appointment_Date = "!='$Appointment_Date'";
                    }
                    $filter = $filter." && A.Appointment_Date$Appointment_Date";
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
                        echo "<script>console.log('$filter');</script>";
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
                        echo "<script>console.log('$filter');</script>";
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
                        $filter = $filter." && A.Payment_Type_Id=$Payment_Type";             
                    }
                }
                if (isset($_POST['Invoice']) && !empty($_POST["Invoice"])) {
                    $Invoice = $_POST['Invoice'];
                    if($Invoice == "all") {

                    }
                    else {
                        $filter = $filter." && A.Invoice_Id=$Invoice";              
                    }
                }

                $sql = "SELECT ".$Search." FROM booking AS A INNER JOIN serviceitem AS B ON A.Item_Id = B.Item_Id INNER JOIN servicestore AS C ON A.ServiceStore_Id = C.ServiceStore_Id INNER JOIN paymenttype AS D ON A.Payment_Type_Id = D.Payment_Type_Id INNER JOIN invoice AS E ON A.Invoice_Id = E.Invoice_Id INNER JOIN orderstatus AS F ON A.Status_Id = F.Status_Id".$filter;

                $search_result = mysqli_query($conn, $sql);

                //Payment_Type
                $sql_Payment_Type = "SELECT * FROM paymenttype";
                $Payment_Type_List = mysqli_query($conn, $sql_Payment_Type);

                //Invoice
                $sql_Invoice = "SELECT * FROM invoice";
                $Invoice_List = mysqli_query($conn, $sql_Invoice);
                
                mysqli_close($conn);
            }
        }
        
        if(isset($_POST["car_model"])){
            if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
            }
            else {
                header("Location:login.php?Alert=Login");
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

<!----------------------------广告框----------------------------->
    <nav class='panel' style='margin-top:10px'>
        <div class="head-v3" id="head-v3">
            <div class="navigation-up">
                <div class="navigation-inner">
                    <div class="navigation-v3">
                        <ul>
                            <li _t_nav="myorder"><h2><a href="javascript:void(0);">My Order</a></h2></li>
                            <li _t_nav="search"><h2><a href="javascript:void(0);">Search</a></h2></li>
                            <li _t_nav="comment"><h2><a href="order_comment.php">Comment</a></h2></li>
                            <li _t_nav="myaccount"><h2><a href="javascript:void(0);">My Account</a></h2></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="navigation-down">
                <div id="myorder" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="myorder">
                    <div class="navigation-down-inner">— 
                        <dt>Please Select:</dt>
                        <dl><dd><a class="link" href="order_info.php?Status=Booked">Booked</a></dd></dl>
                        <dl><dd><a class="link" href="order_info.php?Status=Cancelled_by_customer">Cancelled(C)</a></dd></dl>
                        <dl><dd><a class="link" href="order_info.php?Status=Cancelled_by_Admin">Cancelled(A)</a></dd></dl>
                        <dl><dd><a class="link" href="order_info.php?Status=Attended">Attended</a></dd></dl>
                        <dl><dd><a class="link" href="order_info.php?Status=All_Orders">All</a></dd></dl>
                    </div>
                </div>
                <div id="search" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="search">
                    <div class="navigation-down-inner">— 
                        <dt>Please Enter(Search Service):</dt>
                        <dl><dd><input type="text" name="Search_Key_Word"></dd></dl>
                    </div>
                </div>
                <div id="comment" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="comment">
                </div>
                <div id="myaccount" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="account">
                    <div class="navigation-down-inner">
                        <dt>Please Select:</dt>
                        <dl><dd><a class="link" href="order_info.php?Status=History">History</a></dd></dl>
                        <dl><dd><a class="link" href="check_info.php">My Profile</a></dd></dl>
                        <dl><dd><a class="link" href="change_info.php">Change Profile</a></dd></dl>
                    </div>
                </div>
            </div>
        </div>
        <div class='panel2_3'>
            <div class='mainwnd first-wnd'>
                <div class='wnd_content'>
                    <div class='infownd'>
                        <div class='infownd-box'>
                            <form class='baction bsearchform form1' enctype="multipart/form-data" method='post' action='order_info_search.php' name='form1'>
                                <div id="Sreach_Update_By_Field">
                                    <h3 class='box-head'><label>Sreach / Update By</label></h3>
                                    <div>
                                        <label class="labelname">Item Name: </label>
                                        <input class="inputstyle" type="text" name='Item_Name'>
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Item_Name_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch1" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch1">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="labelname">ServiceStore Name: </label>
                                        <input class="inputstyle" type="text" name='ServiceStore_Name'>
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="ServiceStore_Name_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch2" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch2">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="labelname">Appointment Date: </label>
                                        <input id="Appointment_Date" class="inputstyle Wdate" type="text" onClick="WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd'})" style="height:25px;" name="Appointment_Date">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Appointment_Date_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch3" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch3">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="labelname">Quantity: </label>
                                        <input id="Quantity_Range_Dropdown_Input1" class="inputstyle" type="number" name='Quantity'>
                                        <input id="Quantity_Range_Dropdown_Input2" style="display:none;" class="inputstyle" type="number" name='Quantity2'>
                                        <select class="dropdown" name="Quantity_Range" id="Quantity_Range_Dropdown" onclick="getSelectValue(this);">
                                            <option value="equal">equal</option>
                                            <option value="larger">larger</option>
                                            <option value="smaller">smaller</option>
                                            <option value="between">between</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="labelname">Payment: </label>
                                        <input id="Payment_Dropdown_Input1" class="inputstyle" type="text" name='Payment'>
                                        <input id="Payment_Dropdown_Input2" style="display:none;" class="inputstyle" type="number" name='Payment2'>
                                        <select class="dropdown" name="Payment_Range" id="Payment_Dropdown" onclick="getSelectValue(this);">
                                            <option value="equal">equal</option>
                                            <option value="larger">larger</option>
                                            <option value="smaller">smaller</option>
                                            <option value="between">between</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="labelname">Payment Type: </label>
                                        <select style="width:200px;" class="dropdown" type="text" name="Payment_Type">
                                            <option value="all">All</option>
                                            <?php
                                            WHILE ($one_result = mysqli_fetch_assoc($Payment_Type_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['Payment_Type_Id']; ?>"><?php echo $one_result['Payment_Type'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="labelname">Invoice: </label>
                                        <select class="dropdown" type="text" name="Invoice">
                                            <option value="all">All</option>
                                            <?php
                                            WHILE ($one_result = mysqli_fetch_assoc($Invoice_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['Invoice_Id']; ?>"><?php echo $one_result['Invoice'];?></option>
                                            <?php       
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="forms_sub">
                                        <input id="forms_sub_input" type="submit" value="Search Information" name="Submit">
                                </div>
                                </div>
                            </form>
                                <h3 class='box-head'><label>Search Orders</label></h3>
                                <a href='javascript:void(0);' name='orderlist'></a>                
                                <a class="myButton" id="Left_btn" onclick="rollLeft()">&lt;&lt;&nbsp;Left</a>
                                <a class="myButton" id="Right_btn" onclick="rollRight()">Right&nbsp;&gt;&gt;</a>
                                <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="text-align:center;">
                                    <script>
                                        var firstNo = 1;
                                        function rollLeft() {
                                            firstNo = firstNo - 1;
                                            for (var i = 1; i < 12; i++) {
                                                document.getElementById("field" + i).style.display = "none";
                                                var allclass = document.getElementsByClassName("field1" + i);
                                                for(var j = 0; j < allclass.length; j++) {
                                                    allclass[j].style.display = "none";
                                                    console.log("field1" + i);
                                                }
                                            }
                                            if(firstNo>=1) {
                                                for (var i = firstNo; i < firstNo+3; i++) {
                                                    document.getElementById("field" + i).style.display = "table-cell";
                                                    var allclass = document.getElementsByClassName("field1" + i);
                                                    for(var j = 0; j < allclass.length; j++) {
                                                        allclass[j].style.display = "table-cell";
                                                    }
                                                }
                                            }
                                            else {
                                                firstNo = 1;
                                                for (var i = firstNo; i < firstNo+3; i++) {
                                                    document.getElementById("field" + i).style.display = "table-cell";
                                                    var allclass = document.getElementsByClassName("field1" + i);
                                                    for(var j = 0; j < allclass.length; j++) {
                                                        allclass[j].style.display = "table-cell";
                                                    }
                                                    console.log("field" + i);
                                                }
                                            }
                                        }
                                        function rollRight() {
                                            firstNo = firstNo + 1;
                                            for (var i = 1; i < 12; i++) {
                                                document.getElementById("field" + i).style.display = "none";
                                                var allclass = document.getElementsByClassName("field1" + i);
                                                for(var j = 0; j < allclass.length; j++) {
                                                    allclass[j].style.display = "none";
                                                    console.log("field1" + i);
                                                }
                                            }
                                            if(firstNo<=9) {
                                                for (var i = firstNo; i < firstNo+3; i++) {
                                                    document.getElementById("field" + i).style.display = "table-cell";
                                                    var allclass = document.getElementsByClassName("field1" + i);
                                                    for(var j = 0; j < allclass.length; j++) {
                                                        allclass[j].style.display = "table-cell";
                                                    }   
                                                }
                                            }
                                            else {
                                                firstNo = 9;
                                                for (var i = firstNo; i < firstNo+3; i++) {
                                                    document.getElementById("field" + i).style.display = "table-cell";
                                                    var allclass = document.getElementsByClassName("field1" + i);
                                                    for(var j = 0; j < allclass.length; j++) {
                                                        allclass[j].style.display = "table-cell";
                                                    }
                                                    console.log("field" + i);
                                                }
                                            }
                                        }
                                    </script>
                                    <tr class='tb-title' style='color:white;'>
                                        <td style='background:#24303c;color:white;'>Booking No</td>
                                        <td style='background:#24303c;color:white;'>Item Name</td>
                                        <td id="field1" style='background:#24303c;color:white;'>ServiceStore Name</td>
                                        <td id="field2" style='background:#24303c;color:white;'>Appointment Date</td>
                                        <td id="field3" style='background:#24303c;color:white;'>Appointment Time</td>
                                        <td id="field4" style='background:#24303c;color:white;display:none;'>Quantity</td>
                                        <td id="field5" style='background:#24303c;color:white;display:none;'>Payment</td>
                                        <td id="field6" style='background:#24303c;color:white;;display:none;'>Payment Type</td>
                                        <td id="field7" style='background:#24303c;color:white;display:none;'>Invoice</td>
                                        <td id="field8" style='background:#24303c;color:white;;display:none;'>Remarks</td>
                                        <td id="field9" style='background:#24303c;color:white;display:none;'>Status</td>
                                        <td id="field10" style='background:#24303c;color:white;display:none;'>Created</td>
                                        <td id="field11" style='background:#24303c;color:white;display:none;'>Updated</td>
                                    </tr>
                                    <div class='reset'></div>
                                    <?php
                                    if (!$search_result || mysqli_num_rows($search_result) == 0){
                                        echo "<td colspan='8' style='text-align: center;color:#fe5417;'>Cannot find your orders~~</td>";
                                    }
                                    else {
                                        $conn = mysqli_connect("localhost", "root", "","db160015Q_project" );
                                        while ($one_result = mysqli_fetch_assoc($search_result)) {
                                        ?>
                                        <tr>
                                            <td>
                                            <form method="post" action="order_info_search.php" id="Cancel_Booking_Form<?php echo $one_result["Booking_Id"];?>">
                                            <input name="Cancel_Booking" type="text" value="<?php echo $one_result["Booking_Id"];?>" style="width:1px;" hidden/>
                                            <?php 
                                                echo $one_result["Booking_No"];
                                                if($one_result["Status"] == "Booked") {
                                                ?>  
                                                    <a class="myButton" id="Circle_btn" onclick="Cancel_Booking('<?php echo $one_result["Booking_Id"];?>')">Cancel&nbsp;&gt;&gt;</a>
                                                <?php
                                                }
                                            ?>
                                            </form>
                                            </td>
                                            <script>
                                                function Cancel_Booking(id) {
                                                    var bool = confirm("Are you sure you want to cancel this item?");
                                                    if(bool == true){
                                                        document.getElementById("Cancel_Booking_Form"+id).submit();
                                                    }
                                                }
                                            </script>
                                            <td><?php echo $one_result["Item_Name"]; ?></td>
                                            <td class="field11"><?php echo $one_result["ServiceStore_Name"];?></td>
                                            <td class="field12"><?php echo $one_result["Appointment_Date"]; ?></td>
                                            <td class="field13"><?php echo $one_result["Appointment_Time"]; ?></td>
                                            <td class="field14" style='display:none;'><?php echo $one_result["Quantity"]; ?></td>
                                            <td class="field15" style='display:none;'><?php echo $one_result["Payment"]; ?></td>
                                            <td class="field16" style='display:none;'><?php echo $one_result["Payment_Type"]; ?></td>
                                            <td class="field17" style='display:none;'><?php echo $one_result["Invoice"]; ?></td>
                                            <td class="field18" style='display:none;'><?php echo $one_result["Remarks"]; ?></td>
                                            <td class="field19" style='display:none;'><?php echo $one_result["Status"]; ?></td>
                                            <td class="field110" style='display:none;'><?php echo $one_result["Created"]; ?></td>
                                            <td class="field111" style='display:none;'><?php echo $one_result["Updated"]; ?></td>
                                        </tr>
                                        <?php
                                        }
                                    }
                                    ?>
                                </table>
                            <div class='reset'></div>
                        </div>
                    </div>
                </div>            
            </div> 
            <div class='reset'></div>
        </div>
        <div class='reset'></div>
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
/* ================ 位置选择 ================== */
	function show_postion(){
		$("#postion_btn").click();
	}

	function showHide(){
		$("#menu_mycarservice").removeClass('hidden');
	}

	function hideShow(){
		$("#menu_mycarservice").addClass('hidden');
	}

/* ================ PC_Tablet ================== */
$(window).resize(function(){
    if(document.body.clientWidth>768){
        window.location.href="../PC_Tablet/order_info_search.php";
    }
});
</script>