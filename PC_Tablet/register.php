<!doctype html>
<html lang="en">
<head>
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script src="js/jquery/jquery.advertisement.js"></script>
            
	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/home.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    
    <!------- Register_Login ------->
    <link rel='stylesheet' href='css/register_login.css' type='text/css' />
    
    <?php
    session_start();
    if(isset($_POST["car_model"])){
        $Car_Model = $_POST["car_model"];
        $_SESSION['Car_Model'] = $_POST["car_model"];
            
        if(isset($_POST["car_model"])){
            $Car_Model = $_POST["car_model"];
            $_SESSION['Car_Model'] = $_POST["car_model"];

            if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
                $conn = mysqli_connect("localhost", "root", "","db160015Q_project");
                $Username = $_SESSION['Username'];
                $update = "Car_Model = '$Car_Model'";
                $filter = "Username = '$Username'";
                $u_sql = "UPDATE customer SET ".$update." WHERE ".$filter;
                $update_result = mysqli_query($conn, $u_sql);
                mysqli_close($conn);
            }
        }
    
        if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
            $conn = mysqli_connect("localhost", "root", "","db160015Q_project");
            $Username = $_SESSION['Username'];
            $sql_car_model = "SELECT Car_Model FROM customer WHERE Username = '$Username'";
            $car_model_result = mysqli_query($conn, $sql_car_model);
            $one_car_model_result = mysqli_fetch_assoc($car_model_result);
            $Car_Model = $one_car_model_result['Car_Model'];
            $_SESSION['Car_Model'] = $Car_Model;
        }
    } 
    ?>
</head>
<body>
<!-----------------右侧悬浮菜单------------------->
<div class="guide">
	<div class="guide-wrap">
		<a href="appointment.php" class="edit" title="appointment" target="_blank"><span>App</span></a>
		<a href="index.php#news" class="find" title="Info" target="_blank"><span>Info</span></a>
		<a href="index.php#QRcode" class="report" title="QR" target="_blank"><span>QR</span></a>
		<a href="javascript:window.scrollTo(0,0)" class="top" title="Up"><span>Up</span></a>
	</div>
</div>

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
				    <a href="#"><img id="Logo_size" src="images/header/logo.png" alt=""></a>    
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
        <form class="car_model_content" action="register.php" method="post">
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
			<li class="pull-left text-center"><a href="maintenance.php" class="white" target="_blank">Management</a></li>
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

<!----------------------------注册框----------------------------->
	<article style='margin-top:47px'>
		<div class='panel2_3'>
			<div class='cardwnd'>
				<div class='wnd_content'>
					<div class="log-box" style="border-top:1px solid #ccc;">
						<div class="log-title">
							<span>User Register</span>
						</div>
						<div class="nav20"></div>
						<div class="log-content">
							<div class="log-content-left">
								<form action='confirm_register.php' method='post' id='registerform' onsubmit="return checkform();" enctype="multipart/form-data">
                                    <fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Cust Name: </b></label>
										<input size="29" type=text name="Customer_Name" placeholder='Your Name' required="required" maxlength="50"/>
                                        <span class='help'>Such as <b style="text-decoration:underline;">HU JIA JUN</b></span>
									</fieldset>
									<fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Username: </b></label>
										<input size="29" type=text name="Username" placeholder='Your Username' required="required" maxlength="50"/>
										<span class='help'>Such as <b style="text-decoration:underline;">hjjznb</b>, used for login</span>
									</fieldset>
                                    <fieldset style="height:80px;">
										<label><span class="must" style="color:#fe5417;">*</span><b>Picture: </b></label>
                                        <label style="position:relative;top:8px;" id="head_icon1" for="head_icon_input1">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input1" type="file" name='Customer_Picture' hidden="hidden" onchange="Picture_Show1(this);" required="required">
                                        <span class='help' style="position:relative;left:-70px;">Please upload your picture.</span>
									</fieldset>
									<fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Password: </b></label>
										<input size="29" id="password" type=password name="Password" placeholder='Your Password' required="required" maxlength="50"/>
									</fieldset>
                                    <fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Confirm: </b></label>
										<input size="29" id="cpassword" type=password name="cPassword" placeholder='Confirm Password' required="required" maxlength="50"/>
									</fieldset>
                                    <fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Gender: </b></label>
                                        <div class="opt" style="margin-top:15px;">
                                            <input class="magic-radio" type="radio" name="Gender" id="Male" value="Male" checked>
                                            <label for="Male"><span style="position:relative;top:-13px;left:10px;font-size:16px;">Male</span></label>
                                        </div>
                                        <div class="opt">
                                            <input class="magic-radio" type="radio" name="Gender" id="Female" value="Female">
                                            <label for="Female"><span style="position:relative;top:-13px;left:10px;font-size:16px;">Female</span></label>
                                        </div>
									</fieldset>
                                    <fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Phone: </b></label>
										<input size="29" id="phone" type=text name="Phone" placeholder='Your Phone Number' required="required" pattern="[6,9]{1}[0-9]{7}"/>
                                        <span class='help'>The first number has to be 6 or 9</span>
									</fieldset>
                                    <fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Address：</b></label>
										<input size="29" type=text name="Address"  placeholder="Your Address" required="required" maxlength="255"/>
									</fieldset>
									<fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Email：</b></label>
										<input size="29" type=email name="Email"  placeholder="Your Email" required="required" maxlength="200"/>
									</fieldset>
                                    <fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Car Plate：</b></label>
										<input size="29" type="text" name="License_No"  placeholder="Your Car Plate" required="required" pattern="[A-Z]{3}[0-9]{4}[A-Z]{1}"/>
                                        <span class='help'>3 U-letters + 4 No. + 1 U-letter</span>
									</fieldset>
									<fieldset class='form-actions-1' style="margin-top: 20px;">
										<label style="display: block;float: left;"><span>&nbsp;</span></label>
										<input style="color:white;" type=submit value='Sign Up Now' id="sub"/>
										<a href='login.php'><span class="turn-reg" style="position:relative;top:5px;">Had Account, Login Now</span></a>
									</fieldset>
                                    <?php 
                                    if(isset($_GET['register'])) {
                                        if($_GET['register'] == "fail") {
                                            echo "<fieldset><label style='color:red;width:450px;text-align:right;font-size:20px;'>Register Fail! Please register agian!</label></fieldset>";
                                        }
                                        else if($_GET['register'] == "duplicate_Username"){
                                            echo "<fieldset><label style='color:red;width:450px;text-align:right;font-size:20px;'>Register Fail! Your username has been used!</label></fieldset>";
                                        }
                                    }
                                    ?>
								</form>
                                <script>
	                               function checkform() {  
                                    var password = document.getElementById("password").value;  
                                        var cpassword = document.getElementById("cpassword").value;  
                                        if(password==cpassword && password!=""){
                                            return true;
                                            
                                        }
		                                  else{
			                                 document.getElementById("password").style.border = "5px solid red";
			                                 document.getElementById("cpassword").style.border = "5px solid red";
                                              alert("Please enter correct password");
                                              return false;
		                                  }
                                    }  
                                </script>
							</div>
                            <div class="log-content-right" style="width:240px;float:right;padding-top:100px;">
                                <span style="padding-left:2px;">Sweep, Discount information is here!</span>
				                <img src="images/article/app.png" style="width:200px;height:200px;"><br/>
                            </div>
                        </div>
                        <div class="reset"></div>
                    </div>
                </div>
            </div>
		</div>
	</article>

<!-------------------------底部弹框-------------------------------->
	<div id='footercar' style="display:none;">
		<div class="gzwxts">
			<a><div class='footerclose'></div></a>
		</div>
	</div>
	<div id="chakan2v">
		<img src="images/footer/tips.png" alt="" style="margin:10px">
		<img src="images/footer/chakan.png" alt="" style="margin:10px 12px;">
	</div>
	<div class='reset'></div>

<!------------------------- Footer -------------------------------->
	<Footer class='barpanel'>
        <div class='panel_footer'>
            <div id="tablet_show" class="foot_box">
                <div id="firstrow">
                <dl class="foot_guid">
                    <dt><span class="pull-left"></span>Service Guide</dt>
                    <span class="clearfix"></span>
                    <div class="footer-tips">
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Shopping Process</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Service Guarantee</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">On-site Service(glass)</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Common Problems</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Related Agreements</a></dd>
                    </div>
                </dl>
                <dl class="foot_special"> 
                    <dt><span class="pull-left"></span>After-sales service</dt>
                    <span class="clearfix"></span>
                    <div class="footer-tips">
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Return Policy</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Return Process</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Refund Instructions</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Invoice Related</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Customer Service</a></dd>
                    </div>
                </dl>
                <dl class="foot_aboutus">
                    <dt><span class="pull-left"></span>About us</dt>
                    <span class="clearfix"></span>
                    <div class="footer-tips">
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Company Profile</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Service Philosophy</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Service Features</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Cooperation Brand</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Service Station</a></dd>
                    </div>
                </dl>
                </div>
                <div id="secondrow">
                <dl class="foot_service">
                    <dt><span class="pull-left"></span>About Delivery</dt>
                    <span class="clearfix"></span>
                    <div class="footer-tips">
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Sign the Inspection</a></dd>
                        <dd>&emsp;</dd>
                        <dd>&emsp;</dd>
                    </div>
                </dl>
                <dl class="foot_pay">
                    <dt><span class="pull-left"></span>About payment</dt>
                    <span class="clearfix"></span>
                    <div class="footer-tips">
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Pay with Ali-Pay</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">UnionPay Payment</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">WeChat Payment</a></dd>
                    </div>
                </dl>
                </div>
                <p class="foot_cont">
                    <a target='_blank' rel="nofollow" href="javascript:void(0);">About Us</a>|
                    <a target='_blank' rel="nofollow" href="javascript:void(0);">Contact Us</a>|
                    <a target='_blank' rel="nofollow" href="javascript:void(0);">Service Station Settled</a>|
                    <a target='_blank' rel="nofollow" href="javascript:void(0);">Suggestion</a>
				</p>
                <p>&copy;2017 www.carservices.com All Rights Reserved</p>
                <p>MI1603 160015Q HU JIAJUN</p>
            </div>
            <div id="pc_show" class="foot_box">
                <dl class="foot_guid">
                    <dt><span class="pull-left"></span>Service Guide</dt>
                    <span class="clearfix"></span>
                    <div class="footer-tips">
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Shopping Process</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Service Guarantee</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">On-site Service(glass)</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Common Problems</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Related Agreements</a></dd>
                    </div>
                </dl>
                <dl class="foot_special"> 
                    <dt><span class="pull-left"></span>After-sales service</dt>
                    <span class="clearfix"></span>
                    <div class="footer-tips">
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Return Policy</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Return Process</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Refund Instructions</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Invoice Related</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Customer Service</a></dd>
                    </div>
                </dl>
                <dl class="foot_aboutus">
                    <dt><span class="pull-left"></span>About us</dt>
                    <span class="clearfix"></span>
                    <div class="footer-tips">
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Company Profile</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Service Philosophy</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Service Features</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Cooperation Brand</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Service Station</a></dd>
                    </div>
                </dl>
                <dl class="foot_service">
                    <dt><span class="pull-left"></span>About Delivery</dt>
                    <span class="clearfix"></span>
                    <div class="footer-tips">
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Sign the Inspection</a></dd>
                        <dd>&emsp;</dd>
                        <dd>&emsp;</dd>
                        <dd>&emsp;</dd>
                        <dd>&emsp;</dd>
                    </div>
                </dl>
                <dl class="foot_pay">
                    <dt><span class="pull-left"></span>About payment</dt>
                    <span class="clearfix"></span>
                    <div class="footer-tips">
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">Pay with Ali-Pay</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">UnionPay Payment</a></dd>
                        <dd><a target='_blank' rel="nofollow" href="javascript:void(0);">WeChat Payment</a></dd>
                        <dd>&emsp;</dd>
                        <dd>&emsp;</dd>
                    </div>
                </dl>
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
            img.style.top = "0px";
            img.style.left = "0px";
            document.getElementById('head_icon1').appendChild(img);
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

</script>

<script>
//<!--移动端页面跳转-->
//$(window).resize(function(){
//    if(document.body.clientWidth<=768){
//        window.location.href="../Phone/register.php";
//    }
//});
</script>