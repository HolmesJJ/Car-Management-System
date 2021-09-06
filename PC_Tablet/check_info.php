<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/management.css' type='text/css' />
    <style>
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
    </style>
    <?php
        session_start();
    
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());
    
        if(isset($_SESSION['Username']) && !empty($_SESSION['Username'])) {
            $conn = mysqli_connect("localhost", "root", "","db160015Q_project" );
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
                $Last_Region_Id = $one_result["Last_Region_Id"];
                $Last_Login_Time = $one_result["Last_Login_Time"];
                $Updated = $one_result["Updated"];
                $Created = $one_result["Created"];
            }

            //Status
            $sql_location = "SELECT * FROM location";
            $Location_List = mysqli_query($conn, $sql_location);

            $sql_last_region = "SELECT * FROM lastregion";
            $Last_Region_List = mysqli_query($conn, $sql_last_region);
            $Last_Region_List2 = mysqli_query($conn, $sql_last_region);
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
        <form class="car_model_content" action="check_info.php" method="post">
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
            <div id="loading" style='text-align:center;display:none;'><img src="images/article/loading.gif"></div>
            <div class='mainwnd first-wnd'>
                <div class='wnd_content'>
                    <div class='infownd'>
                        <div class='infownd-box'> 
                            <form class='baction bsearchform form1' enctype="multipart/form-data" method='post' action='change_info.php' name='form1' id="form1">
                                <div>
                                    <h3 class='box-head'><label>My Profile</label></h3>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;"/>
                                        <label class="labelname labelname2" >Customer Name:</label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Customer_Name' value="<?php echo $Customer_Name ?>" disabled>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;"/>
                                        <label class="labelname labelname2" >Userame:</label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Username' value="<?php echo $Username?>" disabled>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;top:-10px;" />
                                        <label style="position:relative;top:-10px;" class="labelname labelname2">Customer Picture: </label>
                                        <label style="position:relative;top:8px;" id="head_icon1" for="head_icon_input1">
                                            <img src="<?php echo $Customer_Picture; ?>" width="50px" height="50px"/>
                                        </label>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;" />
                                        <label class="labelname labelname2">Gender: </label>
                                        <select class="dropdown dropdown2" type="text" name="u_Gender">
                                            <option value="<?php echo $Gender?>" selected><?php echo $Gender?></option>
                                        </select>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;" />
                                        <label class="labelname labelname2">Phone: </label>
                                        <input class="inputstyle inputstyle2" type="number" name='u_Phone' value="<?php echo $Phone?>" disabled>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;top:-24px;width:12px;" />
                                        <label class="labelname labelname2" style="position:relative;top:-25px;">Address: </label>
                                        <textarea class="inputstyle inputstyle2" type="text" name='u_Address' style="height:70px;width:300px;margin-top:10px;" disabled><?php echo $Address?></textarea>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;" />
                                        <label class="labelname labelname2">Email: </label>
                                        <input class="inputstyle inputstyle2" type="email" name='u_Email' value="<?php echo $Email?>" disabled>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;" />
                                        <label class="labelname labelname2">License No: </label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_License_No' value="<?php echo $License_No?>" disabled>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;" />
                                        <label class="labelname labelname2">Location: </label>
                                        <select class="dropdown dropdown2" type="text" name="Location">
                                        <?php
                                            while ($one_result = mysqli_fetch_assoc($Location_List)) {
                                                if ($Location_Id == $one_result['Location_Id']) {
                                                ?>
                                                    <option value="<?php echo $one_result['Location_Id'];?>" selected><?php echo $one_result['Location']; ?></option>
                                                <?php
                                                }
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;" />
                                        <label class="labelname labelname2">Last Region IP: </label>
                                        <?php
                                            while ($one_result = mysqli_fetch_assoc($Last_Region_List)) {
                                                if ($Last_Region_Id == $one_result['Last_Region_Id']) {
                                                ?>
                                                    <input class="inputstyle inputstyle2" type="text" name='Last_Region_IP' value="<?php echo $one_result['Last_Region_IP']; ?>" disabled>
                                                <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;top:-24px;width:12px;" />
                                        <label class="labelname labelname2" style="position:relative;top:-25px;">Last Region: </label>
                                        <?php
                                            while ($one_result = mysqli_fetch_assoc($Last_Region_List2)) {
                                                if ($Last_Region_Id == $one_result['Last_Region_Id']) {
                                                ?>
                                                    <textarea class="inputstyle inputstyle2" type="text" name='Last_Region' style="height:70px;width:300px;margin-top:10px;" disabled><?php echo $one_result['Last_Region']; ?></textarea>
                                                <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;" />
                                        <label class="labelname labelname2">Last_Login_Time: </label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Last_Login_Time' value="<?php echo $Last_Login_Time?>" disabled>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;" />
                                        <label class="labelname labelname2">Updated: </label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Updated' value="<?php echo $Updated?>" disabled>
                                    </div>
                                    <div>
                                        <img class="redcross" style="position:relative;width:12px;" />
                                        <label class="labelname labelname2">Created: </label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Created' value="<?php echo $Created?>" disabled>
                                    </div>
                                </div>
                            </form>
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
</script>

<script>
    
<!--移动端页面跳转-->
$(window).resize(function(){
    if(document.body.clientWidth<=768){
        window.location.href="../Phone/check_info.php";
    }
});
</script>