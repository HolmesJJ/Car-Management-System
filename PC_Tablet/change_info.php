<!DOCTYPE html>
<html>
<head>
    <title>Change Info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/management.css' type='text/css' />
    
    <script src="js/jquery/jquery.step.min.js"></script>
    <script src="js/jquery/jquery.step.js"></script>
    
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
            font-size: 18px;
            font-weight: 550;
            width: 180px;
        }
        .form1 .inputstyle2 {
            height:30px; 
            width:250px;
        }
        .form1 .inputstyle2:focus {
            width:300px;
            height:30px; 
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
    
        if(isset($_SESSION['Username']) && !empty($_SESSION['Username'])) {
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
            }

            $sql = "SELECT * FROM customer WHERE Username = '$Username'";
            $search_result = mysqli_query($conn, $sql);
            $userfound = mysqli_num_rows($search_result);

            if($userfound >= 1) {
                $one_result = mysqli_fetch_assoc($search_result);
                $Customer_Name = $one_result["Customer_Name"];
                $Username = $one_result["Username"];
                $Customer_Picture = $one_result["Customer_Picture"];
                $Gender = $one_result["Gender"];
                $Phone = $one_result["Phone"];
                $Address = $one_result["Address"];
                $Email = $one_result["Email"];
                $License_No = $one_result["License_No"];
                $Location_Id = $one_result["Location_Id"];

                if (isset($_POST['Submit'])) {
                    $Submit = $_POST['Submit'];
                    if ($Submit == "Update Information") {
                        //更新
                        if (!empty($_FILES['u_Customer_Picture']['tmp_name'])) {
                            $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

                            if (in_array($_FILES["u_Customer_Picture"]["type"], $allowedType)) {    
                                if ($_FILES["u_Customer_Picture"]["size"] < 5000000) { 
                                    $extension = pathinfo($_FILES['u_Customer_Picture']['name'], PATHINFO_EXTENSION);
                                    $target = "images/source/Customer/".$Username.".".$extension;
                                    $filename = "images/source/Customer/".$Username.".".$extension;

                                    $result = move_uploaded_file($_FILES["u_Customer_Picture"]["tmp_name"], $filename);

                                    if($result) {
                                        $update = "A.Customer_Picture='$target'";
                                        $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                                        $update_result = mysqli_query($conn, $u_sql);
                                    }
                                    else {
                                        echo "<script>console.log('1');</script>";
                                    }
                                }
                                else {
                                    echo "<script>console.log('2');</script>";
                                }
                            }
                            else {
                                echo "<script>console.log('3');</script>";
                            }
                        }
                        else {
                                echo "<script>console.log('4');</script>";   
                        }
                        if (isset($_POST['u_Gender']) && !empty($_POST["u_Gender"])) {
                            echo "<script>console.log('$u_Gender');</script>";
                            $u_Gender = $_POST['u_Gender'];
                            if ($u_Gender != "nil") {
                                $update = "A.Gender='$u_Gender'";
                                $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                                $update_result = mysqli_query($conn, $u_sql);
                            }
                        }
                        if (isset($_POST['u_Phone']) && !empty($_POST["u_Phone"])) {
                            $u_Phone = $_POST['u_Phone'];
                            $update = "A.Phone=$u_Phone";
                            $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                            $update_result = mysqli_query($conn, $u_sql);
                        }
                        if (isset($_POST['u_Address']) && !empty($_POST["u_Address"])) {
                            $u_Address = $_POST['u_Address'];
                            $update = "A.Address='$u_Address'";
                            $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                            $update_result = mysqli_query($conn, $u_sql);
                        }
                        if (isset($_POST['u_Email']) && !empty($_POST["u_Email"])) {
                            $u_Email = $_POST['u_Email'];
                            $update = "A.Email='$u_Email'";
                            $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                            $update_result = mysqli_query($conn, $u_sql);
                        }
                        if (isset($_POST['u_License_No']) && !empty($_POST["u_License_No"])) {
                            $u_License_No = $_POST['u_License_No'];
                            $update = "A.License_No='$u_License_No'";
                            $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                            $update_result = mysqli_query($conn, $u_sql);
                        }
                        if (isset($_POST['u_Location']) && !empty($_POST["u_Location"])) {
                            $u_Location = $_POST['u_Location'];
                            if ($u_Status != "nil") {
                                $update = "A.Location_Id='$u_Location'";
                                $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                                $update_result = mysqli_query($conn, $u_sql);
                            }
                        }
                        $ut = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET A.Updated='$timestamp' WHERE A.Username = '$Username'";
                        $update_time = mysqli_query($conn, $ut);
                    }
                }
            }

            //Status
            $sql_location = "SELECT * FROM location";
            $Location_List = mysqli_query($conn, $sql_location);
            $u_Location_List = mysqli_query($conn, $sql_location);
            $i_Location_List = mysqli_query($conn, $sql_location);
            mysqli_close($conn);
        }
        else {
            header("Location:view_info.php");
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
        <form class="car_model_content" action="change_info.php" method="post">
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
                <input id="forms_sub_input2" type="submit" value="Submit" name="Submit">
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
                        <li><p><a style="color:white" href="#" target="_self" >Service Items</a></p></li>
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
                            <p class='center-left-title'>Comment</p>
                            <div class='content'>
                                <ul>
                                    <li><a href='order_comment.php'>— My Comment</a></li>
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
            <div class="step-body" id="myStep">
                <div class="step-header" style="width:90%">
                    <ul>
                        <li><p>Validation</p></li>
                        <li><p>Change Your Profile</p></li>
                        <li><p>Confirm</p></li>
                    </ul>
                </div>
                <div>
                    <div class="step-list"></div>
                    <div class="step-list"></div>
                    <div class="step-list"></div>
                </div>
            </div>
            <div id="loading" style='text-align:center;display:none;'><img src="images/article/loading.gif"></div>
            <div class='mainwnd first-wnd'>
                <div class='wnd_content'>
                    <div class='infownd'>
                        <div class='infownd-box'> 
                            <form class='baction bsearchform form1' enctype="multipart/form-data" method='post' action='change_info.php' name='form1' id="form1">
                                <div id="step1" style="text-align:center;">
                                    <h3 style="text-align:left;" class='box-head'><label>Validation</label></h3>
                                    <div style="margin:20px;">
                                        <label class="labelname labelname1">Username: </label>
                                        <input class="inputstyle inputstyle1" type="text" name='Username'>
                                    </div>
                                    <div style="margin:10px;">
                                        <label class="labelname labelname1">Phone: </label>
                                        <input class="inputstyle inputstyle1" type="number" name='Phone'>
                                    </div>
                                    <div style="margin:20px;">
                                        <label class="labelname labelname1">Email: </label>
                                        <input class="inputstyle inputstyle1" type="email" name='Email'>
                                    </div>
                                </div>
                                <div id="step2" style="display:none;">
                                    <h3 class='box-head'><label>Change Your Profile</label></h3>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;"/>
                                        <label class="labelname labelname2" >Customer Name:</label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Customer_Name' value="<?php echo $Customer_Name?>" disabled>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;"/>
                                        <label class="labelname labelname2" >Userame:</label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Username' value="<?php echo $Username?>" disabled>
                                    </div>
                                    <div id="el1">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;top:-10px;" onclick="disappear('el1')"/>
                                        <label style="position:relative;top:-10px;" class="labelname labelname2">Customer Picture: </label>
                                        <label style="position:relative;top:8px;" id="head_icon1" for="head_icon_input1">
                                            <img src="<?php echo $Customer_Picture?>" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input1" type="file" name='u_Customer_Picture' hidden="hidden" onchange="Picture_Show1(this);">
                                    </div>
                                    <div id="el2">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el2')"/>
                                        <label class="labelname labelname2">Gender: </label>
                                        <select class="dropdown dropdown2" type="text" name="u_Gender">
                                            <?php
                                                if($Gender == "Male") {
                                                ?>
                                                    <option value="Male" selected>Male</option>
                                                    <option value="nil">-----</option>
                                                    <option value="Female">Female</option>
                                                <?php
                                                }
                                                else if($Gender == "Female"){
                                                ?>
                                                    <option value="Female" selected>Female</option>
                                                    <option value="nil">-----</option>
                                                    <option value="Male">Male</option>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <option value="nil" selected>-----</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="el3">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el3')"/>
                                        <label class="labelname labelname2">Phone: </label>
                                        <input class="inputstyle inputstyle2" type="number" name='u_Phone' value="<?php echo $Phone?>">
                                    </div>
                                    <div id="el4">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;top:-24px;width:12px;" onclick="disappear('el4')"/>
                                        <label class="labelname labelname2" style="position:relative;top:-25px;">Address: </label>
                                        <textarea class="inputstyle inputstyle2" type="text" name='u_Address' style="height:70px;width:300px;margin-top:10px;"><?php echo $Address?></textarea>
                                    </div>
                                    <div id="el5">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el5')"/>
                                        <label class="labelname labelname2">Email: </label>
                                        <input class="inputstyle inputstyle2" type="email" name='u_Email' value="<?php echo $Email?>">
                                    </div>
                                    <div id="el6">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el6')"/>
                                        <label class="labelname labelname2">License No: </label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_License_No' value="<?php echo $License_No?>">
                                    </div>
                                    <div id="el7">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el7')"/>
                                        <label class="labelname labelname2">Location: </label>
                                        <select class="dropdown dropdown2" type="text" name="u_Location">
                                            <option value="nil" >-----</option>
                                        <?php
                                            while ($one_result = mysqli_fetch_assoc($u_Location_List)) {
                                                if ($Location_Id == $one_result['Location_Id']) {
                                                ?>
                                                    <option value="<?php echo $one_result['Location_Id'];?>" selected><?php echo $one_result['Location']; ?></option>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <option value="<?php echo $one_result['Location_Id'];?>"><?php echo $one_result['Location']; ?></option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <?php $haha = $one_result['Location_Id']; echo "<h3>$haha</h3>"; ?>
                                    </div>
                                </div>
                                <input name="Submit" type="text" value="Update Information" hidden/>
                                <div class="forms_sub">
                                    <input class="nextBtn" id="forms_sub_input" type="button" value="Update Information" style="display:none;" onclick="delay();">
                                    <input class="nextBtn" id="nextBtn" type="button" value="Next Step"/>
                                    <!--<input id="preBtn" type="button" value="Last Step"/>-->
                                    <script>
                                        $(function() {
                                            var step= $("#myStep").step();
                                            var step_number = 0;
                                            //$("#preBtn").click(function(event) {
                                            //  var yes=step.preStep();//上一步
                                            //});
                                            $(".nextBtn").click(function(event) {
                                                step_number = step_number + 1;
                                                if(step_number == 1) {
                                                    var username_value = form1.Username.value;
                                                    var phone_value = form1.Phone.value;
                                                    var email_value = form1.Email.value;
                                                    if(username_value == "<?php echo $Username?>" && phone_value == "<?php echo $Phone?>" && email_value == "<?php echo $Email?>") {
                                                        var yes=step.nextStep();
                                                        document.getElementById("step1").style.display = "none";
                                                        document.getElementById("step2").style.display = "block";
                                                        var sub_field_arr = document.getElementsByClassName("sub_field");
                                                        for(var i=0; i<sub_field_arr.length; i++) {
                                                            sub_field_arr[i].style.display = "block";
                                                        }
                                                        document.getElementById("nextBtn").style.display = "none";
                                                        document.getElementById("forms_sub_input").style.display = "inline";
                                                    }
                                                    else {
                                                        form1.Username.style.border = "red 2px solid";
                                                        form1.Phone.style.border = "red 2px solid";
                                                        form1.Email.style.border = "red 2px solid";
                                                        step_number = 0;
                                                    }
                                                }
                                                else if(step_number == 2) {
                                                    var yes=step.nextStep();
                                                    document.getElementById("step2").style.display = "none";
                                                    document.getElementById("loading").style.display = "block";
                                                }
                                                
                                            });
                                            //$("#goBtn").click(function(event) {
                                            //        var yes=step.goStep(3);//到指定步
                                            //});
                                        });
                                    </script>
                                </div>
                            </form>
                            <script>
                                function delay() {
                                    window.setTimeout(function() {
                                        document.getElementById("form1").submit();
                                    },3000);
                                    return true;
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
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
    }
</script>
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

<script>
<!--移动端页面跳转-->
$(window).resize(function(){
    if(document.body.clientWidth<=768){
        window.location.href="../Phone/change_info.php";
    }
});
</script>