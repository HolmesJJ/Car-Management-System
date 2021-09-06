<!DOCTYPE html>
<html>
<head>
    <title>Recruitment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script src="js/jquery/jquery.advertisement.js"></script>
    
    <script src="js/jquery/jquery-1.10.2.min.js"></script>
    <script src="js/jquery/jquery-ui-1.10.4.min.js" ></script>
    
    <script src="js/lhgdialog.js"></script>
    <script src="js/alertpriduct.js"></script>
    
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNufziXZ1bnhQglddfKhWzWjfIvM3pxrA&libraries=places"></script>
    <script type="text/javascript">
        function codeAddress() {
            geocoder = new google.maps.Geocoder();
            var address = document.getElementById("Address").value;
            geocoder.geocode( { 'Address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    document.getElementById("Latitude").value = results[0].geometry.location.lat();
                    document.getElementById("Longitude").value = results[0].geometry.location.lng();
                    //alert("Latitude: "+results[0].geometry.location.lat());
                    //alert("Longitude: "+results[0].geometry.location.lng());
                } 
                else {
                    alert("Please enter the correct address.");
                }
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>

    <!-------选择车型、服务展示、广告位 ------->
    <link rel='stylesheet' href='css/alertproductc.css' type='text/css' />
            
	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/home.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/recruitment.css' type='text/css' />
    
    <?php
        session_start();
    
        $conn = mysqli_connect("localhost", "root", "","db160015Q_project");
        
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());
    
        if(isset($_POST["car_model"])){
            $Car_Model = $_POST["car_model"];
            $_SESSION['Car_Model'] = $_POST["car_model"];

            if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
                $Username = $_SESSION['Username'];
                $update = "Car_Model = '$Car_Model'";
                $filter = "Username = '$Username'";
                $u_sql = "UPDATE customer SET ".$update." WHERE ".$filter;
                $update_result = mysqli_query($conn, $u_sql);
            }
        }
    
        if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
            $Username = $_SESSION['Username'];
            $sql_car_model = "SELECT Car_Model FROM customer WHERE Username = '$Username'";
            $car_model_result = mysqli_query($conn, $sql_car_model);
            $one_car_model_result = mysqli_fetch_assoc($car_model_result);
            $Car_Model = $one_car_model_result['Car_Model'];
            $_SESSION['Car_Model'] = $Car_Model;
        }
    
        if(isset($_GET["register"])){
            if($_GET["register"] == "fail") {
                echo "<script>alert('Register failed!')</script>";
            }
            else if($_GET["register"] == "success"){
                echo "<script>alert('Register successfully!')</script>";
            }
        }
    
        //Location
        $sql_location = "SELECT * FROM location";
        $Location_List = mysqli_query($conn, $sql_location);
        $Location_List2 = mysqli_query($conn, $sql_location);
    
        mysqli_close($conn);
    ?>
</head>
<body>
    
<!-----------------右侧悬浮菜单------------------->
<div class="guide">
	<div class="guide-wrap">
		<a href="#" class="edit" title="发新帖"><span>新评论</span></a>
		<a href="#" class="find" title="找论坛"><span>找信息</span></a>
		<a href="#" class="report" title="反馈"><span>反馈</span></a>
		<a href="javascript:window.scrollTo(0,0)" class="top" title="回顶部"><span>Up</span></a>
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
        <form class="car_model_content" action="index.php" method="post">
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

<!-------------------------封面-------------------------------->
    <section class="container">
        <img src="images/article/Recruitment.png" alt="" style="width: 100%;">
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
    
<!----------------------------商店注册框----------------------------->
	<article class="main-content">
        <div class="forms">
            <div class="forms_title"><div >Service station settled application</div></div>
            <form action="check_recruitment.php" name="shopenterapply" id="shopenterapply" method="post" enctype="multipart/form-data" >
                <div class="forms_content">
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>ServiceStore Name:</span>
                    </div>
                    <div class="forms_right">
                        <input type="text" size="58" name="ServiceStore_Name" required/>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Address:</span>
                    </div>
                    <div class="forms_right">
                        <textarea id="Address" rows="3" cols="60" style="resize:none;overflow:auto;" name="Address" required></textarea>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Phone:</span>
                    </div>
                    <div class="forms_right">
                        <input type="number" name="Phone" size="28" pattern="[6,9]{1}[0-9]{7}" required />
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Email:</span>
                    </div>
                    <div class="forms_right">
                        <input type="email" name="Email" size="40" required/>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Create Time:</span>
                    </div>
                    <div class="forms_right">
                        <input type="date" size="28" name="Create_Time" style="height:25px; width:200px;"/>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Location:</span>
                    </div>
                    <div class="forms_right">
                        <?php
                        while ($one_location = mysqli_fetch_assoc($Location_List)) {
                        ?>
                            <input name="Location" type="radio" value="<?php echo $one_location['Location_Id']; ?>" /><?php echo $one_location['Location']; ?>&nbsp;&nbsp;
                        <?php
                        }
                        ?>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Description:</span>
                    </div>
                    <div class="forms_right">
                        <textarea rows="4" cols="60" style="resize:none;overflow:auto;" name="Description" ></textarea>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Car Model:</span>
                    </div>
                    <div class="forms_right" >
                        <input name="f_Car_Model[]" type="checkbox" value="Audi" />Audi&nbsp;&nbsp;
                        <input name="f_Car_Model[]" type="checkbox" value="Benz" />Benz&nbsp;&nbsp;
                        <input name="f_Car_Model[]" type="checkbox" value="Buick" />Buick&nbsp;&nbsp;
                        <input name="f_Car_Model[]" type="checkbox" value="BMW" />BMW&nbsp;&nbsp;
                    </div>

                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Opening_Hours:</span>
                    </div>
                    <div class="forms_right">
                        Form:&nbsp;<input type="text" site=10 name="Time1" required>&nbsp;AM.&nbsp;&nbsp;&nbsp;
                        To:&nbsp;<input type="text" size=10 name="Time2" required>&nbsp;PM.
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Latitude:</span>
                    </div>
                    <div class="forms_right">
                        <input id="Latitude" type="text" size=28 name="Lat" required>
                        <button type="button" style="height:24px;" onClick="codeAddress();">Get</button>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Longitude:</span>
                    </div>
                    <div class="forms_right">
                        <input id="Longitude" type="text" size=28 name="Lng" required>
                        <button type="button" style="height:24px;" onClick="codeAddress();">Get</button>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Picture 1 upload:</span>
                    </div>
                    <div class="forms_right">
                        <input type="file" name="Picture1" size="1" required>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Picture 2 upload:</span>
                    </div>
                    <div class="forms_right">
                        <input type="file" name="Picture2" size="1" required>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Picture 3 upload:</span>
                    </div>
                    <div class="forms_right">
                        <input type="file" name="Picture3" size="1" required>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_sub">
                        <input type="submit" value="Submit application">
                    </div>
                </div>
            </form>
        </div>

        <div class="row" id="shopenter_message">
            <article class="col pull-left">
                <div class="shopenter-intro-content">
                    <p class="shopenter-title"><span>|</span>&nbsp;About Car Services</p>
                    <div>
                        <p>Car Services is a professional vehicle maintenance service platform, through cooperation with parts manufacturers and car repair shop, the establishment of a professional service system for the automotive market, the Internet as a carrier, around the "life of the car, the wisdom of car maintenance" Service concept for the owners  to provide technical expertise, price transparency, quality and reliable all-round car car service.</p>
                    </div>
                </div>
            </article>
            <article class="col pull-left">
                <div class="shopenter-intro-content">
                    <p class="shopenter-title"><span>|</span>&nbsp;Entry instructions</p>
                    <div>
                        <ol>
                            <li>1. Settled in business need to have a certain service place, in the local have a good reputation and visibility;</li>
                            <li>2. Need to have a standard service standards, with strict quality control system;</li>
                            <li>3. Have a certain technical ability to complete the mainstream models of conventional maintenance and repair services;</li>
                            <li>4. Settled businesses will be able to directly accept the owner's appointment to the store service;</li>
                            <li>5. Merchants will be able to become a first-tier brand cooperation agencies to enhance customer recognition and trust of the store;</li>
                            <li>6. settled merchants will be able to participate in various markets for easy car repair, promotion and promotion activities;</li>
                            <li>7. Each city limits 10-20, 3-5 km exclusive regional protection;</li>
                            <li>8. If the merchants have settled in with other questions may dial merchants Tel: 400-0330-006</li>
                        </ol>
                    </div>
                </div>
            </article>
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
        window.location.href="../Phone/recruitment.php";
    }
});
</script>