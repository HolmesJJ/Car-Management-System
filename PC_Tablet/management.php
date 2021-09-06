<!DOCTYPE html>
<html>
<head>
    <title>Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/home.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/management.css' type='text/css' />
    <?php
        session_start();
        $conn = mysqli_connect("localhost", "root", "","db160015q_project" );
    
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());
    
        $Search = "A.Item_Name, A.Price, A.Rating, A.Page_View, A.Selected_Quantities, B.Status, C.Category_Name, A.Created";
        $filter="WHERE";
    
        $sql = "SELECT ".$Search." FROM serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id"; 
        $search_result = mysqli_query($conn, $sql);    // search table NOW!

        mysqli_close($conn);
    ?>
</head>
<body>
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
    <section class="container">
        <img src="images/article/management.jpg" alt="" style="width: 100%;">
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
        <div class="button_content">
            <button class="button button_glass clear-a" type="button" ><a href="logout.php" style="color:white;">Logout</a></button><br/>
        </div>
    </section>
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