<!DOCTYPE html>
<html>
<head>
    <title>Order Info Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <script src="js/jquery/jquery.advertisement.js"></script>
    
    <script src="js/My97DatePicker/WdatePicker.js"></script>
    
	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/management.css' type='text/css' />
    

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
	<header id="header_top">
		<div class="main-content">
			<div id="postion_click">
				<div class="pull-left postion1"></div>
				<div class="pull-left postion2"></div>
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
                        if(empty($_SESSION['Username'])) {
                            echo 
                          "<div class='col-left text-right'>
						   <a href='login.php' class='green'>Login</a>
					       </div>  
					       <div class='col-right text-center'>|</div>
					       <div class='pull-left text-left'>    
						      <a href='register.php'>Register</a>
					       </div>";
                        }
                        else 
                        {
                            echo
                            "<div class='col-left text-right'>
						      <span>Hi, ".$_SESSION['Username']."</span>
					       </div>   
					       <div class='col-right text-center'>|</div>
					       <div class='pull-left text-left'>    
						      <a href='logout.php'>Logout</a>
					       </div>";
                        }
                    ?>
				</div>
			</div>
			<div class="header-hotline"></div>
		</div>
		<div class="clearfixed"></div>
	</header>
    
    <header id="header_bottom">
		<div class="main-content">
			<div class="row">
                <div class="pull-left" id="Logo_box">  
				    <a href="index.php"><img id="Logo_size" src="images/header/logo.png" alt=""></a>    
				</div>
				<div class="pull-left" id="header_ensure">
				    <div class="row">
				        <div class="col-icon text-center">
				            <div class="ensure menu-bz"></div><b>All-round</b>
				        </div>
				        <div class="col-icon text-center">
				            <div class="ensure menu-jp"></div><b>Well-chosen</b>
				        </div>     
				        <div class="col-icon text-center">
				            <div class="ensure menu-yz"></div><b>Knight-service</b>
				        </div>   
				        <div class="col-icon text-center">                
				            <div class="ensure menu-js"></div><b>Professional</b>
				        </div>
				    </div>
				</div>
				<div class="col-right text-right">
					<div id="header_car">
						<div id="title_carset1">
							<div id="setcars_icon"></div>
							<span onclick="document.getElementById('car_model').style.display='block'"><?php
                                    if(isset($_POST["car_model"]) && !empty($_POST['car_model'])){
                                        echo $_POST["car_model"];
                                    }
                                    else {
                                        if(isset($_SESSION["Car_Model"]) && !empty($_SESSION['Car_Model'])){
                                            echo $_SESSION["Car_Model"];
                                        }
                                        else {
                                            echo "Car model";
                                        } 
                                    }
                                ?>
                            </span>
						</div>
                        <div id="title_carset2">
							<div id="setcars_icon"></div>
							<span onclick="document.getElementById('car_model').style.display='block'">  
                                <?php
                                    if(isset($_POST["car_model"]) && !empty($_POST['car_model'])){
                                        $_SESSION["Car_Model"] = $_POST["car_model"];
                                        echo "Car model is ".$_POST["car_model"];
                                    }
                                    else {
                                        if(isset($_SESSION["Car_Model"]) && !empty($_SESSION['Car_Model'])){
                                            echo "Car model is ".$_SESSION["Car_Model"];
                                        }
                                        else {
                                            echo "Select your car model";
                                        } 
                                    }
                                ?>
                            </span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
    <div class="reset"></div>
    <div id="car_model" class="car_model_form">
        <span onclick="document.getElementById('car_model').style.display='none'" class="close" title="Close It">×</span>
        <form class="car_model_content" action="maintenance.php" method="post">
            <div class="select_car_model">
                <div class="opt">
                    <img src="images/smallcar/Audi.png" alt="" width="25px"/>
                    <input class="magic-radio" type="radio" name="car_model" id="r1" value="Audi" checked>
                    <label for="r1">Audi</label>
                </div>
                <div class="opt">
                    <img src="images/smallcar/Benz.png" alt="" width="25px"/>
                    <input class="magic-radio" type="radio" name="car_model" id="r2" value="Benz">
                    <label for="r2">Benz</label>
                </div>
                <div class="opt">
                    <img src="images/smallcar/Buick.png" alt="" width="25px"/>
                    <input class="magic-radio" type="radio" name="car_model" id="r3" value="Buick">
                    <label for="r3">Buick</label>
                </div>
                <div class="opt">
                    <img src="images/smallcar/BMW.png" alt="" width="25px"/>
                    <input class="magic-radio" type="radio" name="car_model" id="r4" value="BMW">
                    <label for="r4">BMW</label>
                </div>
            </div>
            <div class="forms_sub">
                <input id="forms_sub_input" type="submit" value="Submit" name="Submit">
            </div>
        </form>
    </div>

<!----------------------------导航栏----------------------------->
	<nav id="menu">
		<div class="main-content">
		<ul>
			<li class="pull-left text-center"><a href="index.php" class="white" target="_blank">HomePage</a></li>
			<li class="pull-left text-center"><a href="maintenance.php" class="white" target="_blank">Maintenance</a></li>
			<li class="pull-left text-center"><a href="servicestore.php" class="white" target="_blank">ServiceStore</a></li>
			<li class="pull-left text-center"><a href="recruitment.php" class="white" target="_blank">Recruitment</a></li>
			<li id="cart" class="pull-right text-center" onclick="show_and_Hide()">
				<a style="cursor: pointer;" class="white">My Car Service</a>
			</li>
		</ul>
		<div class="reset"></div>
 			<ul id="menu_mycarservice" class="hidden">
				<li id="li1" class="text-center"><a href="appointment.php" target="_blank">My appointment</a></li>
				<li id="li2" class="text-center"><a href="order_info.php" target="_blank">My order</a></li>
			</ul>
		</div>
    </nav>
    
<!----------------------------内容----------------------------->
    <nav class='panel' style='margin-top:10px'>
        <div class='panel1_3'>
            <div class='cardwnd'>
                <div class='wnd_content'>
                    <ul class="backendwnd-nav">
                        <li><p><a href="#" target="_self" >Service Items</a></p></li>
                    </ul>                     
                    <div class='infownd'>
                        <div class='infownd-box' style='border-radius:0 0 3px 3px;'>
                            <p class='center-left-title'>My Order</p>
                            <div class='content'>
                                <ul>
                                    <li><a href='order_info.php?Status=Booked'>— Booked</a></li>
                                    <li><a href='order_info.php?Status=Cancelled_by_customer'>— Cancelled by customer</a></li>
                                    <li><a href='order_info.php?Status=Cancelled_by_Admin'>— Cancelled by Admin</a></li>
                                    <li><a href='order_info.php?Status=Attended'>— Attended</a></li>
                                    <li><a href='order_info.php?Status=All_Orders'>— All Orders</a></li>
                                    <li><a href='order_info.php?Status=Search'>— Search</a></li>
                                </ul>
                            </div>
                            <p class='center-left-title'>Maintenance File</p>
                            <div class='content'>
                                <ul>
                                    <li><a href='#'>— Maintenance File</a></li>
                                </ul>
                            </div>
                            <p class='center-left-title'>Comment/Inquiry</p>
                            <div class='content'>
                                <ul>
                                    <li><a href='#'>— My Comment</a></li>
                                    <li><a href='#'>— My Inquiry</a></li>
                                </ul>
                            </div>
                            <p class='center-left-title'>My Account</p>
                            <div class='content'>
                                <ul>
                                    <li><a href='order_info.php?Status=History'>— History</a></li>
                                    <li><a href='check_info.php'>— My Profile</a></li>
                                    <li><a href='change_info.php'>— Change Profile</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='panel2_3' style='padding-left:10px;'>
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
                                                for (var i = firstNo; i < firstNo+5; i++) {
                                                    document.getElementById("field" + i).style.display = "table-cell";
                                                    var allclass = document.getElementsByClassName("field1" + i);
                                                    for(var j = 0; j < allclass.length; j++) {
                                                        allclass[j].style.display = "table-cell";
                                                    }
                                                }
                                            }
                                            else {
                                                firstNo = 1;
                                                for (var i = firstNo; i < firstNo+5; i++) {
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
                                            if(firstNo<=7) {
                                                for (var i = firstNo; i < firstNo+5; i++) {
                                                    document.getElementById("field" + i).style.display = "table-cell";
                                                    var allclass = document.getElementsByClassName("field1" + i);
                                                    for(var j = 0; j < allclass.length; j++) {
                                                        allclass[j].style.display = "table-cell";
                                                    }   
                                                }
                                            }
                                            else {
                                                firstNo = 7;
                                                for (var i = firstNo; i < firstNo+5; i++) {
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
                                    <tr class='tb-title'>
                                        <td style='background:#24303c'>Booking No</td>
                                        <td style='background:#24303c'>Item Picture</td>
                                        <td style='background:#24303c'>Item Name</td>
                                        <td id="field1" style='background:#24303c;'>ServiceStore Name</td>
                                        <td id="field2" style='background:#24303c;'>Appointment Date</td>
                                        <td id="field3" style='background:#24303c;'>Appointment Time</td>
                                        <td id="field4" style='background:#24303c;'>Quantity</td>
                                        <td id="field5" style='background:#24303c;'>Payment</td>
                                        <td id="field6" style='background:#24303c;display:none;'>Payment Type</td>
                                        <td id="field7" style='background:#24303c;display:none;'>Invoice</td>
                                        <td id="field8" style='background:#24303c;display:none;'>Remarks</td>
                                        <td id="field9" style='background:#24303c;;display:none;'>Status</td>
                                        <td id="field10" style='background:#24303c;display:none;'>Created</td>
                                        <td id="field11" style='background:#24303c;display:none;'>Updated</td>
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
                                            <td>
                                                <img src="<?php echo $one_result["Item_Picture"];?>" width="100px" /><br />
                                            </td>
                                            <td><?php echo $one_result["Item_Name"]; ?></td>
                                            <td class="field11"><?php echo $one_result["ServiceStore_Name"];?></td>
                                            <td class="field12"><?php echo $one_result["Appointment_Date"]; ?></td>
                                            <td class="field13"><?php echo $one_result["Appointment_Time"]; ?></td>
                                            <td class="field14"><?php echo $one_result["Quantity"]; ?></td>
                                            <td class="field15"><?php echo $one_result["Payment"]; ?></td>
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

<!--移动端页面跳转-->
$(window).resize(function(){
    if(document.body.clientWidth<=768){
        window.location.href="../Phone/order_info_search.php";
    }
});
</script>