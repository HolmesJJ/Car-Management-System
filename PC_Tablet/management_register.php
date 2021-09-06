<!doctype html>
<html lang="en">
<head>
    <title>Management Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script src="js/jquery/jquery.advertisement.js"></script>
            
	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/home.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/management.css' type='text/css' />

    <!------- Register_Login ------->
    <link rel='stylesheet' href='css/register_login.css' type='text/css' />
    
    <?php
    session_start();
    
    if(isset($_SESSION["Administrator_Name"]) && !empty($_SESSION['Administrator_Name'])){
        $Username = $_SESSION['Administrator_Name'];
    }
    
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
			<li class="pull-left text-center"><a href="index.php" class="white">HomePage</a></li>
			<li class="pull-left text-center"><a href="management_customer.php" class="white">Customer</a></li>
			<li class="pull-left text-center"><a href="management_booking.php" class="white">Booking</a></li>
			<li class="pull-left text-center"><a href="management_service.php" class="white">Services</a></li>
			<li class="pull-left text-center"><a href="management_servicestore.php" class="white">ServiceStore</a></li>
            <li class="pull-left text-center"><a href="management_management.php" class="white">Management</a></li>
		</ul>
		</div>
    </nav>

<!----------------------------注册框----------------------------->
	<article style='margin-top:47px'>
		<div class='panel2_3' style='width:720px;'>
			<div class='cardwnd'>
				<div class='wnd_content'>
					<div class="log-box" style="width:1200px;border-top:1px solid #ccc;">
						<div class="log-title">
							<span>Admin Register</span>
						</div>
						<div class="nav20"></div>
						<div class="log-content">
							<div class="log-content-left" style="width:75%;">
								<form action='confirm_managment_register.php' method='post' id='registerform' onsubmit="return checkform();" enctype="multipart/form-data">
                                    <fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Name: </b></label>
										<input size="29" type=text name="Administrator_Name"  placeholder='HU JIA JUN' required="required" style="height:50px;width:345px;font-size:18px;color:grey;padding-left:15px;" maxlength="50"/>
									</fieldset>
									<fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Password: </b></label>
										<input size="29" id="password" type=password name="Password" placeholder='123456as' required="required" style="height:50px;width:345px;font-size:18px;color:grey;padding-left:15px;" maxlength="50"/>
									</fieldset>
                                    <fieldset>
										<label><span class="must" style="color:#fe5417;">*</span><b>Confirm: </b></label>
										<input size="29" id="cpassword" type=password name="cPassword" placeholder='123456as' required="required" style="height:50px;width:345px;font-size:18px;color:grey;padding-left:15px;" maxlength="50"/>
									</fieldset>
									<fieldset class='form-actions-1' style="margin-top: 20px;">
										<label style="display: block;float: left;"><span>&nbsp;</span></label>
										<input type=submit value='Sign Up Now' id="sub"/>
										<a href='login.php'><span class="turn-reg" style="position:relative;top:5px;">Had Account, Login Now</span></a>
									</fieldset>
                                    <?php 
                                    if(isset($_GET['register'])) {
                                        if($_GET['register'] == "fail") {
                                            echo "<fieldset><label style='color:red;width:450px;text-align:right;font-size:20px;'>Register Fail! Please register agian!</label></fieldset>";
                                        }
                                        else if($_GET['register'] == "duplicate_Administrator_Name"){
                                            echo "<fieldset><label style='color:red;width:450px;text-align:right;font-size:20px;'>Register Fail! Your Administrator Name has been used!</label></fieldset>";
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
                            <div class="log-content-right" style="width:240px;float:right;padding-top:0px;">
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

</script>