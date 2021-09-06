<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script src="js/jquery/jquery.advertisement.js"></script>
    
    <script src="js/jquery/jquery-1.10.2.min.js"></script>
    <script src="js/jquery/jquery-ui-1.10.4.min.js" ></script>
    
    <script src="js/lhgdialog.js"></script>
    <script src="js/alertpriduct.js"></script>

    <!------- 选择车型、服务展示、广告位 ------->
    <link rel='stylesheet' href='css/alertproductc.css' type='text/css' />
            
	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/home.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    
    <!------- 汽车轮播 ------->
    <link rel="stylesheet" href="css/edslider.css" type='text/css' />
	<link rel="stylesheet" href="css/animate-custom.css" type='text/css' />
    
    <?php
        session_start();
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());     
        $conn = mysqli_connect("localhost", "root", "","db160015Q_project");
    
        $Search = "B.Customer_Name, A.Comment, C.Rating AS Item_Rating, C.Page_View, C.Selected_Quantities, D.Car_Model, A.Created";
    
        $comment_sql = "SELECT ".$Search." FROM comment AS A INNER JOIN customer AS B ON A.Customer_Id = B.Customer_Id INNER JOIN serviceitem AS C ON A.Item_Id = C.Item_Id INNER JOIN servicestore AS D ON C.ServiceStore_Id = D.ServiceStore_Id";
        $comment_list = mysqli_query($conn, $comment_sql);
        $one_comment = mysqli_fetch_assoc($comment_list);
    
        $car_sql = "SELECT * FROM car";
        $car_list = mysqli_query($conn, $car_sql);
  
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
    
<!-----------------右侧悬浮菜单------------------->
<div class="guide">
	<div class="guide-wrap">
		<a href="appointment.php" class="edit" title="appointment" target="_blank"><span>App</span></a>
		<a href="javascript:window.scrollTo(0,1750)" class="find" title="Info"><span>Info</span></a>
		<a href="javascript:window.scrollTo(0,2600)" class="report" title="QR"><span>QR</span></a>
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
                <?php
                    while($one_car = mysqli_fetch_assoc($car_list)) {
                    ?>
                    <div class="opt">
                        <img src="<?php echo $one_car['Car_Image'];?>" alt="" width="25px"/>
                        <input class="magic-radio" type="radio" name="car_model" id="r<?php echo $one_car['Car_Id'];?>" value="<?php echo $one_car['Car_Name'];?>">
                        <label for="r<?php echo $one_car['Car_Id'];?>"><?php echo $one_car['Car_Name'];?></label>
                    </div>
                <?php
                    }
                ?>
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

<!-------------------------封面轮播-------------------------------->
    <section class="jq22-container">
		<div class="container">
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
		</div>
	</section>
	<script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery.edslider.js"></script>
	<script>
        var $j = jQuery.noConflict();
		$j(document).ready(function(){
			//Call plugin
			$j('.mySlideshow').edslider({
				width : '100%',
				height: 500
			});
		});
	</script>
    
<!---------------------- 选择车型、服务展示--------------------->
    <nav class="nav30"></nav>
    <section id="home_service_ad">
	<div class="main-content">
		<div class="home-main-enter">   
			<div class="home_service_choosecar">
				<div id="home_service_content">                 
					<div class="home-setcar">
						<div id="home_title_carset">
							<div id="home_setcars_icon"></div>
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
						<div class="mainentrance"  style="width:552px;">
							<div id='leftfuwumenu' >
								<div id='baoyangfuwut' class='yxz' >
									<div id="jianyi1" class="pull-left"></div>
									<div id="jianyi2" class="pull-left" style='display:none'></div>
									<span>Advice</span>
									<div id="jianyi_1" class="pull-right right-icon"></div>
									<div id="jianyi_2" class="pull-right right-icon" style='display:none'></div>
									<div class="reset"></div>
								</div> 

								<div id='baoyangdaohangt' class='wxz'>
									<div id='baoyang1'  class="pull-left" style='display:none'></div>
									<div id='baoyang2'  class="pull-left"  ></div>
									<span>Maintain</span>
									<div id="baoyang_1" class="pull-right right-icon" style='display:none'></div>
									<div id="baoyang_2" class="pull-right right-icon" ></div>
									<div class="reset"></div>
								</div>

								<div id='weixiufuwut' class='wxz' style="border:0px;">
									<div id='weixiu1'  class="pull-left" style='display:none'></div>
									<div id='weixiu2'  class="pull-left"></div>
									<span>Repair</span>
									<div id="weixiu_1" class="pull-right right-icon" style='display:none'></div>
									<div id="weixiu_2" class="pull-right right-icon" ></div>
									<div class="reset"></div>
								</div>
							</div>

							<div id='baoyangfuwu'  class='rightfuwu' style='display:block;border:none;height:100%;width:380px;'  >
								<div class="banner_box_title" style="margin-top:25px;">
								    <div style='height:30px;width:100%;'>
								        <div style='float:left;margin-right: -16px; margin-top: -10px; position: relative;' id='leftimg' onclick='prevoption()' onmouseover='leftchange($(this))'  onmouseout='leftchangeout($(this))'>
								            <img src='images/article/index/left1.png' alt=""/>
								        </div>
								        <div style='float:left;'>
								            <div id='zhizheng' style="float:left;left:72px;top:-4px;position: relative;z-index:100;"><img src='images/article/index/select.png' alt=""/></div>
								            <div  class='contentoption' >
								                <div class='option0' style='border:medium none;'><a href="javascript:void(0);" onclick="findrealmileage($(this))">5000</a></div>
								                <div ><a href="javascript:void(0);" onclick="findrealmileage($(this))">10000</a></div>
								                <div ><a href="javascript:void(0);" onclick="findrealmileage($(this))">15000</a></div>
								                <div class='lastoption '><a href="javascript:void(0);" onclick="findrealmileage($(this))">20000</a></div>
				                                <div id='movediv' class='contentoption' >
				                                    <div class='option0' style='border:medium none;display:block;'><a href="javascript:void(0);" onclick="findrealmileage($(this))">5000</a></div>
				                                    <div ><a href="javascript:void(0);" onclick="findrealmileage($(this))">10000</a></div>
				                                    <div ><a href="javascript:void(0);" onclick="findrealmileage($(this))">15000</a></div>
				                                    <div class='lastoption '><a href="javascript:void(0);" onclick="findrealmileage($(this))">20000</a></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style='float:left;margin-top: -10px;margin-left:6px;' onclick='lastoption()' id='rightimg' onmouseover='rightchange($(this))' onmouseout='rightchangeout($(this))' ><img src='images/article/index/right1.png' alt="" /></div>
                                    </div>
                                    <form method='post' action='maintenance.php' target="_blank" name='form1' id="form1">
                                        <div class='indexfindproduct' >
                                            <input type="text" size="8" id="gongli" name="Kilometer">
                                            <span  class='gl' >KM (The current mileage)</span>
                                        </div>
                                        <fieldset style="margin-right:20px;text-align:center;width:auto;border:none;margin-top: 55px;">
                                            <span id="bydhsubmit" onclick='findproduct()' style="cursor:pointer;border:none;font:16px arial;">To see how to maintain</span>
                                        </fieldset>
                                    </form>
                                </div>
                                    <div id='hideoptioncg' style='display:none;'>
                                        <div id="option0"><a href="javascript:void(0);" onclick="findrealmileage($(this))">5000</a></div>
                                        <div id="option1"><a href="javascript:void(0);" onclick="findrealmileage($(this))">10000</a></div>
                                        <div id="option2"><a href="javascript:void(0);" onclick="findrealmileage($(this))">15000</a></div>
                                        <div id="option3"><a href="javascript:void(0);" onclick="findrealmileage($(this))">20000</a></div>
                                        <div id="option4"><a href="javascript:void(0);" onclick="findrealmileage($(this))">25000</a></div>
                                        <div id="option5"><a href="javascript:void(0);" onclick="findrealmileage($(this))">30000</a></div>
                                        <div id="option6"><a href="javascript:void(0);" onclick="findrealmileage($(this))">35000</a></div>
                                        <div id="option7"><a href="javascript:void(0);" onclick="findrealmileage($(this))">40000</a></div>
                                        <div id="option8"><a href="javascript:void(0);" onclick="findrealmileage($(this))">45000</a></div>
                                        <div id="option9"><a href="javascript:void(0);" onclick="findrealmileage($(this))">50000</a></div>
                                        <div id="option10"><a href="javascript:void(0);" onclick="findrealmileage($(this))">55000</a></div>
                                        <div id="option11"><a href="javascript:void(0);" onclick="findrealmileage($(this))">60000</a></div>
                                        <div id="option12"><a href="javascript:void(0);" onclick="findrealmileage($(this))">65000</a></div>
                                        <div id="option13"><a href="javascript:void(0);" onclick="findrealmileage($(this))">70000</a></div>
                                        <div id="option14"><a href="javascript:void(0);" onclick="findrealmileage($(this))">75000</a></div>
                                        <div id="option15"><a href="javascript:void(0);" onclick="findrealmileage($(this))">80000</a></div>
                                        <div id="option16"><a href="javascript:void(0);" onclick="findrealmileage($(this))">85000</a></div>
                                        <div id="option17"><a href="javascript:void(0);" onclick="findrealmileage($(this))">90000</a></div>
                                        <div id="option18"><a href="javascript:void(0);" onclick="findrealmileage($(this))">95000</a></div>
                                        <div id="option19"><a href="javascript:void(0);" onclick="findrealmileage($(this))">100000</a></div>
                                        <div id="option20"><a href="javascript:void(0);" onclick="findrealmileage($(this))">105000</a></div>
                                        <div id="option21"><a href="javascript:void(0);" onclick="findrealmileage($(this))">110000</a></div>
                                        <div id="option22"><a href="javascript:void(0);" onclick="findrealmileage($(this))">115000</a></div>
                                        <div id="option23"><a href="javascript:void(0);" onclick="findrealmileage($(this))">120000</a></div>
                                        <div id="option24"><a href="javascript:void(0);" onclick="findrealmileage($(this))">125000</a></div>
                                        <div id="option25"><a href="javascript:void(0);" onclick="findrealmileage($(this))">130000</a></div>
                                        <div id="option26"><a href="javascript:void(0);" onclick="findrealmileage($(this))">135000</a></div>
                                        <div id="option27"><a href="javascript:void(0);" onclick="findrealmileage($(this))">140000</a></div>
                                        <div id="option28"><a href="javascript:void(0);" onclick="findrealmileage($(this))">145000</a></div>
                                        <div id="option29"><a href="javascript:void(0);" onclick="findrealmileage($(this))">150000</a></div>
                                        <div id="option30"><a href="javascript:void(0);" onclick="findrealmileage($(this))">155000</a></div>
                                        <div id="option31"><a href="javascript:void(0);" onclick="findrealmileage($(this))">160000</a></div>
                                        <div id="option32"><a href="javascript:void(0);" onclick="findrealmileage($(this))">165000</a></div>
                                        <div id="option33"><a href="javascript:void(0);" onclick="findrealmileage($(this))">170000</a></div>
                                        <div id="option34"><a href="javascript:void(0);" onclick="findrealmileage($(this))">175000</a></div>
                                        <div id="option35"><a href="javascript:void(0);" onclick="findrealmileage($(this))">180000</a></div>
                                        <div id="option36"><a href="javascript:void(0);" onclick="findrealmileage($(this))">185000</a></div>
                                        <div id="option37"><a href="javascript:void(0);" onclick="findrealmileage($(this))">190000</a></div>
                                        <div id="option38"><a href="javascript:void(0);" onclick="findrealmileage($(this))">195000</a></div>
                                        <div id="option39"><a href="javascript:void(0);" onclick="findrealmileage($(this))">200000</a></div>		
                                    </div>
                                </div>
                                <div id='weixiufuwu'  class='rightfuwu' >
                                    <div class="banner_box_content">
                                        <span style='line-height: 115px;'>Some products for this model have not yet shelves</span>
                                    </div>
                                </div>
                                <div id='baoyangdaohang' class='rightfuwu' >
                                    <div class="banner_box_content">
                                        <span style='line-height: 115px;'>Some products for this model have not yet shelves</span>	<div  class="banner_box_text">
                                            <a href="maintenance.php?Category_Id=4" target="_blank">
                                                <img src="images/article/ts.png" alt="">Feature
                                            </a>
                                        </div>
                                        <div class="banner_box_text">
                                            <a href="maintenance.php?Category_Id=1" target="_blank">
                                                <img src="images/article/yh.png" alt="">Repair
                                            </a>
                                        </div>
                                        <div class="reset"></div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="reset" class="reset"></div>
        <nav id="nav30" class="nav30"></nav>
        <div id="video">
        <video autoplay loop>
            <source src="CarRepair.mp4" type="video/mp4">
        </video>
        </div>
    </section>
   
<script>
 $(function() {
	 	 $('#gongli').val($('.option0 > a').html());
	$( "#zhizheng" ).draggable({ axis: 'x',containment: 'parent',cursor: 'pointer',zIndex: 1000,
	 drag: function(event, ui) { 
		var leftnum = $( "#zhizheng" ).css('left'); 
		leftnum = parseInt(leftnum);
		$('#movediv').css('width',leftnum-3+'px');
		var n = leftnum/67;
		n = parseInt(n);
		for(var i=5;i>n-1;i--)
		{
			$('#movediv > div:eq('+i+')').hide();
		}
		for(var i=0;i<n;i++)
		{//alert(i+'---'+n);
			$('#movediv > div:eq('+i+')').show();
		}
		var one = $('#movediv > div:eq(0) a').html();
		var two = $('#movediv > div:eq(1) a').html();
		//var five = $('#movediv > div:eq(4) a').html();
		var four = $('#movediv > div:eq(3) a').html();
		var valnum = two - one;

		 var startn = one - valnum;
		//var startn = one - valnum;


		var zong = four - startn;

		var v = (zong/260)*(leftnum-5);
		v = parseInt(v);
		$('#gongli').val(v+startn);
	 },
	stop: function(event, ui) { 
		var leftnum = $( "#zhizheng" ).css('left'); 
		leftnum = parseInt(leftnum);
		var one = $('#movediv > div:eq(0) a').html();
		var two = $('#movediv > div:eq(1) a').html();
		var valnum = two - one;
		if( leftnum  < 17 )
		{
			$( "#zhizheng" ).css('left','17px');
			if(one>valnum)
			{
				$("#gongli").val(one);
			}else
			{
				$("#gongli").val(0);
			}

		}
	}
	});
});
$("#gongli").blur(function(){
	var one = $('#movediv > div:eq(0) a').html();//当前的第一个div的值
	var two = $('#movediv > div:eq(1) a').html();//当前的第二个div的值
	var valnum = two - one;//保养间隔
	var gongli = $("#gongli").val();//获取输入框中的公里数
	//判断输入框的公里数是否合法，不合法则不能继续接下来的计算
	var regex="^(0|[1-9][0-9]*)$";
	var re = new RegExp(regex);
	if( !re.test(gongli) ){
		$("#gongli").val( $('.option0 > a').html() );
		//校验失败时，将滚动条返回到第一格
		gongli = $("#gongli").val();
		var ww = 67;
		$('#movediv').css('width',ww-3+'px');
		$('#zhizheng').css('left',ww+5+'px');
		for(j=0;j<5;j++){
			$('#movediv > div:eq('+j+')').css({"display":"none"});
		}
			$('#movediv > div:eq(0)').css({"display":"block"});
		return false;
	}
	//计算滚动条数值和对应的位置
	var optionlast = $('#hideoptioncg div:last > a').html();//行驶里程数的最大值
	var optionlast01 = optionlast-valnum;
	var optionlast02 = optionlast-valnum*2;
	var optionlast03 = optionlast-valnum*3;
	//var optionlast04 = optionlast-valnum*4;
	var n = gongli/valnum;//所输入公里数所占的div的个数
	var nn = Math.ceil(n/4);	//所输入的公里数所在的段数
	var numn = n-(nn-1)*4;
	if(n==0) numn=0;       //如果输入的是0，则计算的是第0个div，起始公里数为0
	// 该段所在的起始公里数
	if(parseInt(n)<4){
		var startnn = 0;
	}else{
		var startnn =  (nn-1)*4*valnum;
	}
	if(parseInt(gongli)>parseInt(optionlast)){
		//如果公里数超过了最大选项
//		$("#leftimg").click(prevoption());
		$('#movediv').css('width',275+'px');  //原始数据是335
		$('#zhizheng').css('left',275+'px');
		$("#gongli").val(optionlast);
		$('#movediv > div:eq(0) a').html(optionlast03);
		$('#movediv > div:eq(1) a').html(optionlast02);
		$('#movediv > div:eq(2) a').html(optionlast01);
		$('#movediv > div:eq(3) a').html(optionlast);

		//更改底层的公里数
		$('.contentoption > div:eq(0) a').html(optionlast03);
		$('.contentoption > div:eq(1) a').html(optionlast02);
		$('.contentoption > div:eq(2) a').html(optionlast01);
		$('.contentoption > div:eq(3) a').html(optionlast);

		//$('#movediv > div:eq(4) a').html(optionlast);
		for(i=0;i<5;i++){
			j = i+1;
			$('#movediv > div:eq('+j+')').css({"display":"block"});
		}
	}else{
		//输入的公里数未超过最大公里数
		one01 = startnn + valnum;
		two01 = startnn + 2*valnum;
		three01 = startnn + 3*valnum;
		four01 = startnn + 4*valnum;
		// five01 = startnn + 5*valnum;
		var ww = numn*67;
		var numn01 = Math.floor(numn);
		$('#movediv').css('width',ww-3+'px');
		$('#zhizheng').css('left',ww+5+'px');
		$('#movediv > div:eq(0) a').html(one01);
		$('#movediv > div:eq(1) a').html(two01);
		$('#movediv > div:eq(2) a').html(three01);
		$('#movediv > div:eq(3) a').html(four01);
		//$('#movediv > div:eq(4) a').html(five01);
		$('.contentoption > div:eq(0) a').html(one01);
		$('.contentoption > div:eq(1) a').html(two01);
		$('.contentoption > div:eq(2) a').html(three01);
		$('.contentoption > div:eq(3) a').html(four01);
		//$('.contentoption > div:eq(4) a').html(five01);
		for(j=0;j<5;j++){
			$('#movediv > div:eq('+j+')').css({"display":"none"});
		}
		for(i=0;i<numn01;i++){
			$('#movediv > div:eq('+i+')').css({"display":"block"});
		}
	}
	//将当前的搜索的公里数保存到隐藏隐藏
});

function prevoption()
{
	var option0 = $('.contentoption').find('.option0').length;

	if( option0 == 0 )
	{
		var firstdiv = $('.contentoption > div:first').attr('class');
		$('.'+firstdiv).html($('#'+firstdiv).prev().html());
		$('.'+firstdiv).next().html($('#'+firstdiv).prev().next().html());
		$('.'+firstdiv).next().next().html($('#'+firstdiv).prev().next().next().html());
		$('.'+firstdiv).next().next().next().html($('#'+firstdiv).prev().next().next().next().html());
		// $('.'+firstdiv).next().next().next().next().html($('#'+firstdiv).prev().next().next().next().next().html());
		$('.'+firstdiv).attr('class',$('#'+firstdiv).prev().attr('id'));
		var one = $('#'+firstdiv).prev().find('a').html();
		var two = $('#'+firstdiv).prev().next().find('a').html();
		var v = two - one;
		var gongli = $('#gongli').val();
		gongli = gongli-v;
		$('#gongli').val(gongli);
	}else{

		var one = $('#movediv > div:eq(0) a').html();//当前的第一个div的值
		var two = $('#movediv > div:eq(1) a').html();//当前的第二个div的值
		var three = $('#movediv > div:eq(2) a').html();//当前的第三个div的值
		var four = $('#movediv > div:eq(3) a').html();//当前的第四个div的值
		//var five = $('#movediv > div:eq(4) a').html();//当前的第五个div的值
		var valnum = two - one;//保养间隔
		var gongli = $('#gongli').val();
		gongli = gongli-valnum;
		one = one - valnum;
		two = two - valnum;
		three = three - valnum;
		four = four -valnum;
		//five = five -valnum;
		if(parseInt(one)>0){
			$('#gongli').val(gongli);
			$('#movediv > div:eq(0) a').html(one);
			$('#movediv > div:eq(1) a').html(two);
			$('#movediv > div:eq(2) a').html(three);
			$('#movediv > div:eq(3) a').html(four);
			//$('#movediv > div:eq(4) a').html(five);
			$('.contentoption > div:eq(0) a').html(one);
			$('.contentoption > div:eq(1) a').html(two);
			$('.contentoption > div:eq(2) a').html(three);
			$('.contentoption > div:eq(3) a').html(four);
			//$('.contentoption > div:eq(4) a').html(five);
			return false;
		}
	}
}
function lastoption()
{
	var lastoption = $('.lastoption > a').html();
	var optionlast = $('#hideoptioncg div:last > a').html();
	if( optionlast != lastoption)
	{
		var one = parseInt($('#movediv > div:eq(0) a').html());//当前的第一个div的值
		var two = parseInt($('#movediv > div:eq(1) a').html());//当前的第二个div的值
		var three = parseInt($('#movediv > div:eq(2) a').html());//当前的第三个div的值
		var four = parseInt($('#movediv > div:eq(3) a').html());//当前的第四个div的值
		//var five = $('#movediv > div:eq(4) a').html();//当前的第五个div的值
		var valnum = parseInt(two - one);//保养间隔
		var gongli = $('#gongli').val();
		gongli = parseInt(gongli) + valnum;
		one = one + valnum;
		two = two + valnum;
		three = three + valnum;
		// four = four + valnum;
		four =parseInt(four)+parseInt(valnum);
		// five =parseInt(five)+parseInt(valnum);
		if(parseInt(four)< optionlast){
			$('#gongli').val(gongli);
			$('#movediv > div:eq(0) a').html(one);
			$('#movediv > div:eq(1) a').html(two);
			$('#movediv > div:eq(2) a').html(three);
			$('#movediv > div:eq(3) a').html(four);
			//$('#movediv > div:eq(4) a').html(five);
			$('.contentoption > div:eq(0) a').html(one);
			$('.contentoption > div:eq(1) a').html(two);
			$('.contentoption > div:eq(2) a').html(three);
			$('.contentoption > div:eq(3) a').html(four);
			//$('.contentoption > div:eq(4) a').html(five);
			return false;
		}
	}
}

function leftchange(obj)
{
	$(obj).find('img').attr('src','images/article/index/left.png');
}

function rightchange(obj)
{
	$(obj).find('img').attr('src','images/article/index/right.png');
}
function leftchangeout(obj)
{
	$(obj).find('img').attr('src','images/article/index/left1.png');
}

function rightchangeout(obj)
{
	$(obj).find('img').attr('src','images/article/index/right1.png');
}
function findproduct()
{
    document.getElementById("form1").submit();
}
function findrealmileage(obj)
{
	var carmodelnum ="0";
	if( carmodelnum== 0 ) {
		return check_carmodel(0);
	}
	var carid = '0';
	var gongli = $(obj).html();	
}
</script>                

<!------------------------ 服务展示 ----------------------------->
    <div class="reset"></div>
    <nav class="nav30"></nav>
    <section id="service_show">
        <div class="main-content">
            <div class="row">
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-hyfu"></div>
                        <div class="pull-left">Gasoline</div>
                    </div>
                </div>
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-fdj"></div>
                        <div class="pull-left">Engine</div>
                    </div>
                </div>
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-zdxt"></div>
                        <div class="pull-left">Braking</div>
                    </div>
                </div>
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-dpfw"></div>
                        <div class="pull-left">Battery</div>
                    </div>
                </div>
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-blfw"></div>
                        <div class="pull-left">Glass</div>
                    </div>
                </div>
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-cljc"></div>
                        <div class="pull-left">Inspection</div>
                    </div>
                </div>
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-zmxt"></div>
                        <div class="pull-left">Illumination</div>
                    </div>
                </div>
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-lufw"></div>
                        <div class="pull-left">Tires</div>
                    </div>
                </div>
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-bjpq"></div>
                        <div class="pull-left">Painting</div>
                    </div>
                </div>
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-mryh"></div>
                        <div class="pull-left">Conservation</div>
                    </div>
                </div>
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-ktxt"></div>
                        <div class="pull-left">Air condition</div>
                    </div>
                </div>
                <div class="col-service">
                    <div class="serviceicon">
                        <div class="pull-left service-pfxt"></div>
                        <div class="pull-left">Emission</div>
                    </div>
                </div>
                <div class="clearfixed"></div>
            </div>
            <div class="reset"></div>
        </div>
    </section>
    <div class="reset"></div>
    <nav class="nav30"></nav>
    
<!-----------------------服务站信息----------------------------->
    <section id="service_area" style="background: #f7f7f7;">
        <nav class="nav20"></nav>
        <div class="main-content">
            <div id="bigbox">
            <div id="shop">
                <p class="area-title color333">Join Us</p>
                <nav class="nav20"></nav>
                <a href="servicestore.php" target="_blank"><img src="images/article/shopenter.png" alt="" width="300" height="340"></a>
            </div>
            <div id="map" class="text-center">
                <p class="area-title color333">Service Area</p>
                <nav class="nav20"></nav>
                <img id="area-macp" src="images/article/singapore-map.jpg" alt="" >
            </div>
            <div id="dynamic" >
                <div>
                    <p class="area-title color333">Dynamic</p>
                    <nav class="nav20"></nav>
                    <img src="images/article/building.png" alt="" width="300" height="160">
                    <nav class="nav10"></nav>
                    <p class="color333">Singapore carservices go online!</p>
                    <nav class="nav10"></nav>
                    <p class="color666">Singapore carservices Services Ltd. is located diagonally opposite ABC Road. Main business: professional maintenance Audi A4, A6, A8, Q5, Q7; professional maintenance of various types of automatic transmission.</p>
                </div>
                <div class="reset"></div>
            </div>
            </div>
        </div>
        <div class="reset"></div>
    </section>

<!-------------------------- 资讯动态 --------------------------->
    <script src="js/jquery/jquery-1.7.2.min.js" type="text/javascript" charset="utf-8"></script>
    <!--企业动态-->
    <script src="js/jquery/jquery-ui-1.8.6.core.widget.js"></script>
    <script src="js/jquery/jqueryui.bannerize.js"></script>
    <script type="text/javascript">
        var $k = jQuery.noConflict();
        $k(document).ready(function(){
            $k('#banners').bannerize({
                shuffle: 1,
                interval: "5"
            });
            $k(".ui-line").hover(function(){
                $(this).addClass("ui-line-hover");
                $(this).find(".ui-bnnerp").addClass("ui-bnnerp-hover");
            },function(){
                $(this).removeClass("ui-line-hover");
                $(this).find(".ui-bnnerp").removeClass("ui-bnnerp-hover");
            });
        });
    </script>
    <section id="news">
    <!--企业动态-->
        <nav class="nav30"></nav>
        <div class="main-content">
            <div id="banners" class="ui-banner">
                <ul class="ui-banner-slides">
                    <li><a href="advertisement.php" target="_blank"><img src="images/article/News.png" alt="" title="" /></a></li>
                    <li><a href="advertisement.php" target="_blank"><img src="images/article/News.png" alt="" title="" /></a></li>
                    <li><a href="advertisement.php" target="_blank"><img src="images/article/News.png" alt="" title="" /></a></li>
                    <li><a href="advertisement.php" target="_blank"><img src="images/article/News.png" alt="" title="" /></a></li>
                    <li><a href="advertisement.php" target="_blank"><img src="images/article/News.png" alt="" title="" /></a></li>
                    <li><a href="advertisement.php" target="_blank"><img src="images/article/News.png" alt="" title="" /></a></li>
                </ul>
                <ul class="ui-banner-slogans">
                    <li class="ui-line">
                        <div class="ullinehover">
                            <div class="ui-bnnerimg pull-left">
                                <img src="images/article/news/news1.jpg" alt="" />
                            </div>
                            <div class="ui-bnnerp pull-right">
                                <h3 style="margin-top: 8px;">Cure-All for Cars</h3>
                                <p>Reliability and quality service comes standard. Where every detail counts. We are driven to serve you.</p>
                            </div>
                        </div>
                    </li>
                    <li class="ui-line">
                        <div class="ullinehover">
                            <div class="ui-bnnerimg pull-left">
                                <img src="images/article/news/news2.jpg" alt="" />
                            </div>
                            <div class="ui-bnnerp pull-right">
                                <h3 style="margin-top: 8px;">Cure-All for Cars</h3>
                                <p>Reliability and quality service comes standard. Where every detail counts. We are driven to serve you.</p>
                            </div>
                        </div>
                    </li>
                    <li class="ui-line">
                        <div class="ullinehover">
                            <div class="ui-bnnerimg pull-left">
                                <img src="images/article/news/news3.jpg" alt="" />
                            </div>
                            <div class="ui-bnnerp pull-right">
                                <h3 style="margin-top: 8px;">Cure-All for Cars</h3>
                                <p>Reliability and quality service comes standard. Where every detail counts. We are driven to serve you.</p>
                            </div>
                        </div>
                    </li>
                    <li class="ui-line">
                        <div class="ullinehover">
                            <div class="ui-bnnerimg pull-left">
                                <img src="images/article/news/news4.jpg" alt="" />
                            </div>
                            <div class="ui-bnnerp pull-right">
                                <h3 style="margin-top: 8px;">Cure-All for Cars</h3>
                                <p>Reliability and quality service comes standard. Where every detail counts. We are driven to serve you.</p>
                            </div>
                        </div>
                    </li>
                    <li class="ui-line">
                        <div class="ullinehover">
                            <div class="ui-bnnerimg pull-left">
                                <img src="images/article/news/news5.jpg" alt="" />
                            </div>
                            <div class="ui-bnnerp pull-right">
                                <h3 style="margin-top: 8px;">Cure-All for Cars</h3>
                                <p>Reliability and quality service comes standard. Where every detail counts. We are driven to serve you.</p>
                            </div>
                        </div>
                    </li>
                    <li class="ui-line">
                        <div class="ullinehover">
                            <div class="ui-bnnerimg pull-left">
                                <img src="images/article/news/news6.jpg" alt="" />
                            </div>
                            <div class="ui-bnnerp pull-right">
                                <h3 style="margin-top: 8px;">Cure-All for Cars</h3>
                                <p>Reliability and quality service comes standard. Where every detail counts. We are driven to serve you.</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!--企业动态 end-->
    </section>
    <nav class="nav30"></nav>

<!------------------------ 车主评论 ----------------------------->
    <section id="order_discuss">
        <div class="main-content">
            <div>
                <div id="discuss-title" class="pull-left"></div>
                <a href="javascript:void(0);" class="discuss-more">more&gt;&gt;</a>
            </div>
            <div class="reset"></div>
            <nav class="nav20"></nav>
            <div id="discuss-content">
                <div class="row">
                    <div id="box1" class="col-width">
                        <?php
                        $one_comment = mysqli_fetch_assoc($comment_list)
                        ?>
                            <div class="color999">
                                <span style="margin-right:10px;"class="pull-left"><?php echo $one_comment["Customer_Name"]; ?></span>
                                <?php
                                    for($i=0; $i<(int)$one_comment['Item_Rating']; $i++) {
                                        echo "<div class='orangestar'></div>";
                                    }
                                ?>
                            </div>
                            <div class="reset"></div>
                            <p class="color999">Car Model:&nbsp;
                                    <?php
                                        $Car_Model = $one_comment["Car_Model"];
                                        $Car_Model_arr = explode(",", $Car_Model);
                                        for($i=0; $i<count($Car_Model_arr); $i++) {
                                        ?>
                                            <img src='images/smallcar/<?php echo $Car_Model_arr[$i];?>.png' style="position:relative;top:7px;border:1px solid #ccc;width:24px;"/>
                                        <?php
                                        }
                                    ?>
                            </p>
                            <p class="color333"><?php echo $one_comment["Comment"]; ?></p>
                    </div>
                    <div id="box2" class="col-width">
                        <?php
                        $one_comment = mysqli_fetch_assoc($comment_list)
                        ?>
                            <div class="color999">
                                <span style="margin-right:10px;"class="pull-left"><?php echo $one_comment["Customer_Name"]; ?></span>
                                <?php
                                    for($i=0; $i<(int)$one_comment['Item_Rating']; $i++) {
                                        echo "<div class='orangestar'></div>";
                                    }
                                ?>
                            </div>
                            <div class="reset"></div>
                            <p class="color999">Car Model:&nbsp;
                                    <?php
                                        $Car_Model = $one_comment["Car_Model"];
                                        $Car_Model_arr = explode(",", $Car_Model);
                                        for($i=0; $i<count($Car_Model_arr); $i++) {
                                        ?>
                                            <img src='images/smallcar/<?php echo $Car_Model_arr[$i];?>.png' style="position:relative;top:7px;border:1px solid #ccc;width:24px;"/>
                                        <?php
                                        }
                                    ?>
                            </p>
                            <p class="color333"><?php echo $one_comment["Comment"]; ?></p>
                    </div>
                    <div id="box3" class="col-width">
                        <?php
                        $one_comment = mysqli_fetch_assoc($comment_list)
                        ?>
                            <div class="color999">
                                <span style="margin-right:10px;"class="pull-left"><?php echo $one_comment["Customer_Name"]; ?></span>
                                <?php
                                    for($i=0; $i<(int)$one_comment['Item_Rating']; $i++) {
                                        echo "<div class='orangestar'></div>";
                                    }
                                ?>
                            </div>
                            <div class="reset"></div>
                            <p class="color999">Car Model:&nbsp;
                                    <?php
                                        $Car_Model = $one_comment["Car_Model"];
                                        $Car_Model_arr = explode(",", $Car_Model);
                                        for($i=0; $i<count($Car_Model_arr); $i++) {
                                        ?>
                                            <img src='images/smallcar/<?php echo $Car_Model_arr[$i];?>.png' style="position:relative;top:7px;border:1px solid #ccc;width:24px;"/>
                                        <?php
                                        }
                                    ?>
                            </p>
                            <p class="color333"><?php echo $one_comment["Comment"]; ?></p>
                    </div>
                    <div id="box4" class="col-width">
                        <?php
                        $one_comment = mysqli_fetch_assoc($comment_list)
                        ?>
                            <div class="color999">
                                <span style="margin-right:10px;"class="pull-left"><?php echo $one_comment["Customer_Name"]; ?></span>
                                <?php
                                    for($i=0; $i<(int)$one_comment['Item_Rating']; $i++) {
                                        echo "<div class='orangestar'></div>";
                                    }
                                ?>
                            </div>
                            <div class="reset"></div>
                            <p class="color999">Car Model:&nbsp;
                                    <?php
                                        $Car_Model = $one_comment["Car_Model"];
                                        $Car_Model_arr = explode(",", $Car_Model);
                                        for($i=0; $i<count($Car_Model_arr); $i++) {
                                        ?>
                                            <img src='images/smallcar/<?php echo $Car_Model_arr[$i];?>.png' style="position:relative;top:7px;border:1px solid #ccc;width:24px;"/>
                                        <?php
                                        }
                                    ?>
                            </p>
                            <p class="color333"><?php echo $one_comment["Comment"]; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="reset"></div>
        <nav class="nav30"></nav>
    </section>
    
<!------------------------ 合作伙伴 ----------------------------->
    <section id="partner">
    <nav class="nav30"></nav>
    <div class="main-content">
        <div>
            <div class="partner-title pull-left"></div>
            <a href="javascript:void(0);" class="partner-more">more&gt;&gt;</a>
            <div class="reset"></div>
        </div>
        <nav class="nav20"></nav>
        <div class="partner-content">
            <div class="row">
                <div class="col-partner text-center">
                    <img src="images/article/partner/partner1.png" alt="">
                </div>
                <div class="col-partner text-center">
                    <img src="images/article/partner/partner2.png" alt="">
                </div>
                <div class="col-partner text-center">
                    <img src="images/article/partner/partner3.png" alt="">
                </div>
                <div class="col-partner text-center">
                    <img src="images/article/partner/partner4.png" alt="">
                </div>
                <div class="col-partner text-center">
                    <img src="images/article/partner/partner5.png" alt="">
                </div>
                <div class="col-partner text-center">
                    <img src="images/article/partner/partner6.png" alt="">
                </div>
                </div>
        </div>
        <nav class="nav30"></nav>
    </div>
    </section>

<!------------------------- 二维码 ------------------------------>
    <nav class="nav30"></nav>
    <section id="QRcode">
        <div>
            <div id="downapp_title"></div>
            <nav class="nav10"></nav>
            <p class="text-center">以人性化的智能服务，悦动人车生活</p>
            <div id="downapp_2v">
                <div class="col-QRcode">
                    <img src="images/article/app.png" alt="Download APP">
                </div>
                <div class="col-QRcode">
                    <img src="images/article/weixin.png" alt="Follow Us" style="padding-bottom:3px;">
                </div>
            </div>
        </div>
        <nav class="nav"></nav>
        <nav class="nav10"></nav>
    </section>

<!-------------------------底部弹框-------------------------------->
	<Footer id='footercar' style="display:none;">
		<div class="gzwxts">
			<a><div class='footerclose'></div></a>
		</div>
	</Footer>
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
        window.location.href="../Phone/index.php";
    }
});
</script>