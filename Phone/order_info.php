<!DOCTYPE html>
<html>
<head>
    <title>Order Info</title>
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
        .form1 .labelname1 {
            font-size: 20px;
            font-weight: 600;
        }
        .form1 .inputstyle1 {
            height:30px; 
            width:300px;
        }
        .form1 .inputstyle1:focus {
            width:350px;
            height:35px; 
        }
        .form1 .labelname2 {
            margin:10px;
            font-size: 15px;
            font-weight: 550;
            width: 150px;
        }
        .form1 .inputstyle2 {
            height:25px; 
            width:220px;
        }
        .form1 .inputstyle2:focus {
            width:250px;
            height:25px; 
        }
        select.dropdown2 {
            width: 150px;
            height: 26px;
        }
        .sub_field {
            display: none;
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

                if(isset($_POST['Submit']) && !empty($_POST['Submit']) && $_POST['Submit'] == "Submit") {
                    if(isset($_SESSION['Item_Id']) && !empty($_SESSION['Item_Id']) && isset($_SESSION['ServiceStore_Id']) && !empty($_SESSION['ServiceStore_Id']) && isset($_SESSION['Quantity']) && !empty($_SESSION['Quantity']) && isset($_SESSION['Quantity']) && !empty($_SESSION['Quantity']) && isset($_POST['Payment_Type_Id']) && !empty($_POST['Payment_Type_Id']) && isset($_POST['Invoice_Id']) && !empty($_POST['Invoice_Id'])) {
                        $Booking_No = uniqid();
                        $Item_Id = $_SESSION['Item_Id'];
                        $ServiceStore_Id = $_SESSION['ServiceStore_Id'];
                        $Quantity = $_SESSION['Quantity'];
                        $Payment_Type_Id = $_POST['Payment_Type_Id'];
                        $Invoice_Id = $_POST['Invoice_Id'];

                        if(isset($_SESSION['Appointment_Date']) && !empty($_SESSION['Appointment_Date']) && isset($_SESSION['Appointment_Time']) && !empty($_SESSION['Appointment_Time']) && isset($_SESSION['Remarks']) && !empty($_SESSION['Remarks'])){
                            $Appointment_Date = $_SESSION['Appointment_Date'];
                            $Appointment_Time = $_SESSION['Appointment_Time'];
                            $Remarks = $_SESSION['Remarks'];
                            
                            $d_sql = "DELETE A FROM cart AS A INNER JOIN customer AS B ON A.Customer_Id = B.Customer_Id WHERE B.Username = '$Username'";
                            $delete_result = mysqli_query($conn, $d_sql);

                            $item_sql = "SELECT Price FROM serviceitem WHERE Item_Id = $Item_Id";
                            $price_result = mysqli_query($conn, $item_sql);
                            $one_price = mysqli_fetch_assoc($price_result);
                            $Price = $one_price['Price'];
                            $Payment = $Price*$Quantity*0.8;

                            $i_sql = "INSERT INTO booking (Booking_No, Customer_Id, Item_Id, ServiceStore_Id, Status_Id, Appointment_Date, Appointment_Time, Quantity, Payment, Payment_Type_Id, Invoice_Id, Remarks, Created, Updated) VALUES ('$Booking_No', '$Customer_Id', '$Item_Id', '$ServiceStore_Id', 1, '$Appointment_Date', '$Appointment_Time', '$Quantity', '$Payment', '$Payment_Type_Id', '$Invoice_Id', '$Remarks', '$timestamp', '$timestamp')";

                            $result = mysqli_query($conn, $i_sql);

                            unset($_SESSION['Item_Id']);
                            unset($_SESSION['ServiceStore_Id']);
                            unset($_SESSION['Quantity']);
                            unset($_SESSION['Appointment_Date']);
                            unset($_SESSION['Appointment_Time']);
                            unset($_SESSION['Remarks']);
                        }
                        else {
                            header("Location:appointment.php?Alert=Unsave");
                        }
                    }
                    else {
                        header("Location:appointment.php?Alert=Unfill");
                    }
                }
                
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
                if(isset($_GET['Status'])) {
                    if($_GET['Status'] == "Booked") {
                        $filter = $filter."&& A.Status_Id = 1";
                        echo "<script>console.log('$filter');</script>";
                    }
                    else if($_GET['Status'] == "Cancelled_by_customer") {
                        $filter = $filter."&& A.Status_Id = 2";
                    }
                    else if($_GET['Status'] == "Cancelled_by_Admin") {
                        $filter = $filter."&& A.Status_Id = 3";
                    }
                    else if($_GET['Status'] == "Attended") {
                        $filter = $filter."&& A.Status_Id = 4";
                    }
                    else if($_GET['Status'] == "History") {
                        $filter = $filter."&& A.Status_Id != 1";
                    }
                }

                $sql = "SELECT ".$Search." FROM booking AS A INNER JOIN serviceitem AS B ON A.Item_Id = B.Item_Id INNER JOIN servicestore AS C ON A.ServiceStore_Id = C.ServiceStore_Id INNER JOIN paymenttype AS D ON A.Payment_Type_Id = D.Payment_Type_Id INNER JOIN invoice AS E ON A.Invoice_Id = E.Invoice_Id INNER JOIN orderstatus AS F ON A.Status_Id = F.Status_Id".$filter;

                $search_result = mysqli_query($conn, $sql);
                
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
    
<!----------------------------内容----------------------------->
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
                            <?php
                                if(isset($_GET['Status'])) {
                                    if($_GET['Status'] == "Waiting_for_payment") {
                                        echo "<h3><label>Waiting for payment</label></h3>";
                                    }
                                    else if($_GET['Status'] == "Waiting_for_treatment") {
                                        echo "<h3><label>Waiting for treatment</label></h3>";
                                    }
                                    else if($_GET['Status'] == "Completion") {
                                        echo "<h3><label>Completion</label></h3>";
                                    }
                                    else if($_GET['Status'] == "Cancellation") {
                                        echo "<h3><label>Cancellation</label></h3>";
                                    }
                                    else {
                                        echo "<h3><label>All Orders</label></h3>";
                                    }
                                }
                                else {
                                    echo "<h3><label>All Orders</label></h3>";
                                }
                            ?>
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
                                                for (var i = firstNo; i < firstNo+2; i++) {
                                                    document.getElementById("field" + i).style.display = "table-cell";
                                                    var allclass = document.getElementsByClassName("field1" + i);
                                                    for(var j = 0; j < allclass.length; j++) {
                                                        allclass[j].style.display = "table-cell";
                                                    }
                                                }
                                            }
                                            else {
                                                firstNo = 1;
                                                for (var i = firstNo; i < firstNo+2; i++) {
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
                                            if(firstNo<=10) {
                                                for (var i = firstNo; i < firstNo+2; i++) {
                                                    document.getElementById("field" + i).style.display = "table-cell";
                                                    var allclass = document.getElementsByClassName("field1" + i);
                                                    for(var j = 0; j < allclass.length; j++) {
                                                        allclass[j].style.display = "table-cell";
                                                    }   
                                                }
                                            }
                                            else {
                                                firstNo = 10;
                                                for (var i = firstNo; i < firstNo+2; i++) {
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
                                        <td style='color:white;background:#24303c'>Booking No</td>
                                        <td style='color:white;background:#24303c'>Item Name</td>
                                        <td id="field1" style='color:white;background:#24303c'>ServiceStore Name</td>
                                        <td id="field2" style='color:white;background:#24303c'>Appointment Date</td>
                                        <td id="field3" style='color:white;background:#24303c;display:none;'>Appointment Time</td>
                                        <td id="field4" style='color:white;background:#24303c;display:none;'>Quantity</td>
                                        <td id="field5" style='color:white;background:#24303c;display:none;'>Payment</td>
                                        <td id="field6" style='color:white;background:#24303c;display:none;'>Payment Type</td>
                                        <td id="field7" style='color:white;background:#24303c;display:none;'>Invoice</td>
                                        <td id="field8" style='color:white;background:#24303c;display:none;'>Remarks</td>
                                        <td id="field9" style='color:white;background:#24303c;display:none;'>Status</td>
                                        <td id="field10" style='color:white;background:#24303c;display:none;'>Created</td>
                                        <td id="field11" style='color:white;background:#24303c;display:none;'>Updated</td>
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
                                            <form method="post" action="order_info.php" id="Cancel_Booking_Form<?php echo $one_result["Booking_Id"];?>">
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
                                            <td class="field13" style='display:none;'><?php echo $one_result["Appointment_Time"]; ?></td>
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
    <script src="js/jquery/jquery.step.js"></script>

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
    function Picture_Show1(obj){
        var len = obj.files.length;
        for (var i =0 ; i < len ; i++){
            showimg1(obj.files[i]);
        }
    }
    function showimg1(img){
        var a = new FileReader();
        a.readAsDataURL(img);
        a.onload=function(){
            var img = new Image();
            img.src=a.result;
            img.style.width = "50px";
            img.style.height = "50px";
            img.style.position = "absolute";
            img.style.top = "-37px";
            img.style.left = "0px";
            document.getElementById('head_icon1').appendChild(img);
        }
    }
</script>
<script type="text/javascript">
    jQuery(document).ready(function(){
    var record = "";
    var clicked = 0;
	var qcloud={};
	$('[_t_nav]').click(function(){
        if(clicked === 0){
            document.getElementById("head-v3").style.height = "200px";
            clicked = 1;
            var _nav = $(this).attr('_t_nav');
            record = $(this).attr('_t_nav');
            if(_nav == "selected") {
               document.getElementById("head-v3").style.height = "80px";
            }
            clearTimeout( qcloud[ _nav + '_timer' ] );
            qcloud[ _nav + '_timer' ] = setTimeout(function(){
                $('[_t_nav]').each(function(){
                    $(this)[ _nav == $(this).attr('_t_nav') ? 'addClass':'removeClass' ]('nav-up-selected');
                });
                $('#'+_nav).stop(true,true).slideDown(200);
            }, 150);
            console.log(record);
        }
        else if(clicked === 1) {
            var _nav = $(this).attr('_t_nav');
            if(_nav == record || _nav == "selected") {
                document.getElementById("head-v3").style.height = "80px";
                clicked = 0;
                clearTimeout( qcloud[ record + '_timer' ] );
                qcloud[ record + '_timer' ] = setTimeout(function(){
                    $('[_t_nav]').removeClass('nav-up-selected');
                    $('#'+record).stop(true,true).slideUp(200);
                }, 150);
                console.log(_nav);
            }
            else {
                clicked = 1;
                clearTimeout( qcloud[ record + '_timer' ] );
                qcloud[ record + '_timer' ] = setTimeout(function(){
                    $('[_t_nav]').removeClass('nav-up-selected');
                    $('#'+record).stop(true,true).slideUp(200);
                    console.log(record);
                }, 150);
                qcloud[ record + '_timer' ] = setTimeout(function(){
                    $('[_t_nav]').each(function(){
                        $(this)[ _nav == $(this).attr('_t_nav') ? 'addClass':'removeClass' ]('nav-up-selected');
                    });
                    $('#'+_nav).stop(true,true).slideDown(200);
                    record = _nav;
                    console.log(record);
                }, 500);
            }
        }
    });
});
        
/* ================ PC_Tablet ================== */
$(window).resize(function(){
    if(document.body.clientWidth>768){
        window.location.href="../PC_Tablet/order_info.php";
    }
});
</script>