<!DOCTYPE html>
<html>
<head>
    <title>Maintenance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script src="js/jquery/jquery.advertisement.js"></script>
    
    <script src="js/packageproductc.js"></script>

    <!-------选择车型、服务展示、广告位 ------->
    <link rel='stylesheet' href='css/alertproductc.css' type='text/css' />
            
	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/maintenance.css' type='text/css' />
    
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
            }
        }
        
        if(isset($_POST["car_model"])){
            if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
            }
            else {
                header("Location:login.php?Alert=Login");
            }
        }
    
        $filter = "WHERE 1 = 1";
        $search_condition = " A.Item_Id, A.Item_Name, A.Item_Picture, A.Item_Detail_Picture, A.Description, A.Price, A.Rating, A.Page_View, A.Selected_Quantities, A.Status_Id, A.Category_Id, A.ServiceStore_Id, A.Updated, A.Created FROM serviceitem AS A INNER JOIN servicestore AS B ON A.ServiceStore_Id = B.ServiceStore_Id ";
        if(isset($_POST['Kilometer'])) {
            $Kilometer = $_POST['Kilometer'];
            if((int)$Kilometer>=0 && (int)$Kilometer<75000) {
                $filter = $filter." && A.Item_Name LIKE '%5000Km%'";
            }
            else if((int)$Kilometer>=7500 && (int)$Kilometer<125000) {
                $filter = $filter." && A.Item_Name LIKE '%10000Km%'";
                echo "<script>console.log('2');</script>";
                
            }
            else if((int)$Kilometer>=125000 && (int)$Kilometer<175000) {
                $filter = $filter." && A.Item_Name LIKE '%15000Km%'";
                echo "<script>console.log('3');</script>";
            }
            else {
                $filter = $filter." && A.Item_Name LIKE '%20000Km%'";
                echo "<script>console.log('4');</script>";
            }
        }
        if(isset($_SESSION['Car_Model'])) {
            $Car_Model = $_SESSION['Car_Model'];
            $filter = $filter." && B.Car_Model LIKE '%$Car_Model%'";
        }
        if(isset($_SESSION["ServiceStore_Id"]) && !empty($_SESSION['ServiceStore_Id'])){
            $ServiceStore_Id = $_SESSION["ServiceStore_Id"];
            $filter = $filter." && A.ServiceStore_Id = $ServiceStore_Id";
        }
        if(isset($_POST["Search_Key_Word"]) && !empty($_POST["Search_Key_Word"])){
            $Search_Key_Word = $_POST["Search_Key_Word"];
            $filter = $filter." && A.Item_Name LIKE '%$Search_Key_Word%'";
        }
        if (isset($_GET['Category_Id'])) {
            $Category_Id_selected = $_GET['Category_Id'];
            $filter = $filter." && A.Category_Id = '$Category_Id_selected'";
            $sql_serviceitem = "SELECT".$search_condition.$filter; 
        }
        else if (isset($_GET['Sort'])) {
            $Sort = $_GET['Sort'];
            if($Sort == "Name") {
                $sql_serviceitem = "SELECT".$search_condition.$filter." ORDER BY A.Item_Name ASC";
            }
            else if($Sort == "Price_DESC") {
                $sql_serviceitem = "SELECT".$search_condition.$filter." ORDER BY A.Price DESC";
            }
            else if($Sort == "Price_ASC") {
                $sql_serviceitem = "SELECT".$search_condition.$filter." ORDER BY A.Price ASC";
            }
            else if($Sort == "Rating") {
                $sql_serviceitem = "SELECT".$search_condition.$filter." ORDER BY A.Rating DESC";
            }
            else if($Sort == "View") {
                $sql_serviceitem = "SELECT".$search_condition.$filter." ORDER BY A.Page_View DESC";
            }
            else if($Sort == "Selected") {
                $sql_serviceitem = "SELECT".$search_condition.$filter." ORDER BY A.Selected_Quantities DESC";
            }
        }
        else {
            $sql_serviceitem = "SELECT".$search_condition.$filter;
        }

        $serviceitem_list = mysqli_query($conn, $sql_serviceitem);
    
        //Category
        $sql_category = "SELECT * FROM category";
        $Category_List = mysqli_query($conn, $sql_category);
             
        if(isset($_GET['Alert'])) {
            if($_GET['Alert'] == "Difference") {
                echo "<script>alert('Please choose the correct Service or Store!')</script>";
            }
        }
    
        mysqli_close($conn);
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
    
<!--------------------------服务框----------------------------->
    <div class="panel">
    <div class="panel2_3" style="margin-right:12px;">
        <?php
            if(isset($_POST["car_model"]) && !empty($_POST['car_model'])){}
            else {
                if(isset($_SESSION["Car_Model"]) && !empty($_SESSION['Car_Model'])){}
                else {
                    echo "<div class='warning_prompt'>
                        <div class='warning_prompt_box'>
                            <span>▪To help you quickly find the right maintenance service, set the car model first!</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href='#' class='light-operation' onclick=\"document.getElementById('car_model').style.display='block'\">Select the car model&gt;&gt;</a>
                        </div>
                    </div>";
                } 
            }
        ?>
        <div class="head-v3" id="head-v3">
            <div class="navigation-up">
                <div class="navigation-inner">
                    <form action="maintenance.php" method="post" id="Form1">
                    <div class="navigation-v3">
                        <ul>
                            <li _t_nav="categories"><h2><a id="color_change1" href="javascript:void(0);">Categories</a></h2></li>
                            <li _t_nav="sort"><h2><a id="color_change2" href="javascript:void(0);">Sort By Others</a></h2></li>
                            <li _t_nav="selected"><h2><a id="color_change3" href="maintenance.php?Sort=Selected">Sort By Selected</a></h2></li>
                            <li>
                                <h2>
                                    <a id="color_change4" href="javascript:void(0);">
                                        Search Service:&nbsp;&nbsp;
                                        <input type="text" name="Search_Key_Word">
                                    </a>
                                </h2>
                            </li>
                        </ul>
                    </div>
                    <a class="semi-transparent-button with-border" onclick="Search_Key_Word()"><span>Go</span></a>
                    </form>
                    <script>
                        function Search_Key_Word() {
                            document.getElementById("Form1").submit();
                        }
                    </script>
                </div>
            </div>
            <div class="navigation-down">
                <div id="categories" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="categories">
                    <div class="navigation-down-inner">
                        <dt>Please Select:</dt>
                        <dl><dd><a class="link" href="maintenance.php?category=all">All</a></dd></dl>
                        <?php
                            while ($one_category = mysqli_fetch_assoc($Category_List)) {
                        ?>
                                <dl><dd><a class="link" href="maintenance.php?Category_Id=<?php echo $one_category['Category_Id']; ?>"><?php echo $one_category['Category_Name']; ?></a></dd></dl>
                        <?php
                            }
                        ?>                
                    </div>
                </div>
                <div id="sort" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="sort">
                    <div class="navigation-down-inner">
                        <dt>Please Select:</dt>
                        <dl><dd><a class="link" href="maintenance.php?Sort=Name">Name</a></dd></dl>
                        <dl><dd><a class="link" href="maintenance.php?Sort=Price_DESC">Price&nbsp;(h&nbsp;&rarr;&nbsp;l)</a></dd></dl>
                        <dl><dd><a class="link" href="maintenance.php?Sort=Price_ASC">Price&nbsp;(l&nbsp;&rarr;&nbsp;h)</a></dd></dl>
                        <dl><dd><a class="link" href="maintenance.php?Sort=Rating">Rating</a></dd></dl>
                        <dl><dd><a class="link" href="maintenance.php?Sort=view">View</a></dd></dl>
                    </div>
                </div>
                <div id="selected" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="selected"></div>
            </div>
        </div>
        <!-- 列表区  -->
        <form method='' action='' name=''>
            <div class="product-content">
                <div class="reset" ></div>
            <?php
                while ($one_serviceitem = mysqli_fetch_assoc($serviceitem_list))  {
            ?>
                <div class="product-intro" onmouseover="show_border('<?php echo $one_serviceitem['Item_Id']?>')" onmouseout="show_out('<?php echo $one_serviceitem['Item_Id']?>')" >
				    <div class='product-intro-content' >
                        <div class="product-theme">
                            <a href=""><?php echo $one_serviceitem['Item_Name']; ?></a>
				        </div>
				        <div class="product-main">
				            <div class="product-bg">
                                <a href="" >
                                    <img border="0" src="<?php echo $one_serviceitem['Item_Picture']; ?>" style="width: 107px;" >
                                </a>
                            </div>
                            <div class="product-detail">
                                <a href=""><?php echo $one_serviceitem['Description']; ?></a>
                            </div>
                            <div class='pd_split' ></div>
                            <div class="product-situ">
                                <span><label class="spe-label" ><?php echo $one_serviceitem['Rating']; ?></label> Stars</span><br>
                                <span><label class="spe-label" ><?php echo $one_serviceitem['Selected_Quantities']; ?></label> has been selected</span><br>
                                <span><label class="spe-label" ><?php echo $one_serviceitem['Page_View']; ?></label> has been viewed</span>
                            </div>
                            <div class='pd_split'></div>
                            <div class="product-price">
                                <span>$<?php echo $one_serviceitem['Price']; ?></span><br><br>
                                <a id="<?php echo $one_serviceitem['Item_Id']?>" href="maintenance_Detail.php?Item_Id=<?php echo $one_serviceitem['Item_Id']?>" target="_blank">Check</a>
                            </div>
                            <div class='reset' ></div>
                        </div>
                    </div>
                </div>
            <?php
                }	
            ?>          
            </div>
        </form> 
        <!-- end 列表区 -->
    </div>

    <div class='panel1_3' id='menuservice'>
        <div class='wnd_content'>
            <div class='infownd'>
                <div class='infownd-box' id="infownd-box">
                    <div class="micro_cart" id="micro_cart" >
                        <div class="mc_top"></div>
                        <div class="mc_main" >
                            <!-- 设置车型 start-->
                            <div class="mc_car">
                                <!-- 设置车型 状态图 -->
                                <div id="mc_car_logo" class="mc_timeline" >
                                    <img src='images/aside/cur_ope_car.png' />
                                </div>
                                <!-- 设置车型 内容 -->
                                <div id="mc_car_content" class="mc_content" >
                                    <div class="sub_mc_o" >
                                        <a href='javascript:void(0);' class="cart-operation cartype" onclick="document.getElementById('car_model').style.display='block'" >
                                        <?php
                                            if(isset($_POST["car_model"]) && !empty($_POST['car_model'])){
                                                echo "Car model is: ".$_POST["car_model"];
                                            }
                                            else {
                                                if(isset($_SESSION["Car_Model"]) && !empty($_SESSION['Car_Model'])){
                                                    echo "Car model is: ".$_SESSION["Car_Model"];
                                                }
                                                else {
                                                    echo "Select car model&gt;&gt;";
                                                } 
                                            }
                                        ?>
                                        </a>
                                        
                                    </div>
                                </div>
                                <!-- 设置车型 end-->
                                <div class="reset"></div>
                                <!-- 选择服务 -->
                                <div class="mc_service">
                                    <div id="mc_service_logo" class="mc_timeline" >
                                        <img src='images/aside/cur_ope_service.png' />
                                    </div>
                                    <div id="mc_service_content" class="mc_content" >
                                        <div class="sub_mc_o" >
                                            <a class='cart-operation' href='javascript:void(0);'>
                                            <?php
                                            if(isset($_SESSION["Item_Id"]) && !empty($_SESSION['Item_Id'])){
                                                $Selected_Item_Id = $_SESSION['Item_Id'];
                                            ?>
                                                <a class='cart-operation' href='maintenance_Detail.php?Item_Id=<?php echo $Selected_Item_Id?>'>
                                                <?php
                                                    echo "Service Item: ".$_SESSION["Item_Name"];
                                                ?>
                                                </a>
                                            <?php
                                            }
                                            else {
                                            ?>
                                                <a class='cart-operation' href="maintenance.php">
                                                <?php
                                                    echo "Select service&gt;&gt;";
                                                ?>
                                                </a>
                                            <?php
                                            }
                                            ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="reset"></div>
                                <!-- 选择服务站 -->
                                <div class="mc_shop">
                                    <div id="mc_shop_logo" class="mc_timeline" >
                                        <img src='images/aside/not_ope_location.png' />
                                    </div>
                                    <div id="mc_shop_content" class="mc_content" >
                                        <div class="sub_mc_o" >
                                            <?php
                                            if(isset($_SESSION["ServiceStore_Id"]) && !empty($_SESSION['ServiceStore_Id'])){
                                                $Selected_Store_Id = $_SESSION['ServiceStore_Id'];
                                            ?>
                                                <a class='cart-operation' href='servicestore_Detail.php?ServiceStore_Id=<?php echo $Selected_Store_Id?>'>
                                                <?php
                                                    echo "Service store: ".$_SESSION["ServiceStore_Name"];
                                                ?>
                                                </a>
                                            <?php
                                            }
                                            else {
                                            ?>
                                                <a class='cart-operation' href="servicestore.php">
                                                <?php
                                                    echo "Select store&gt;&gt;";
                                                ?>
                                                </a>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="reset"></div>
                                <!-- 结算 -->
                                <div class="mc_account">
                                    <div id="mc_account_logo" class="mc_timeline" >
                                        <img src='images/aside/not_ope_account.png' />
                                    </div>
                                    <div id="mc_account_content" class="mc_content" style="">
                                        <div class="sub_mc_o" >
                                            <a class='cart-operation' href="appointment.php">Settle accounts&gt;&gt;</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="reset"></div>
                            </div>
                        </div>
                    </div>
                    <div class="ss_shortcutbtn" id="ss_shortcutbtn" onclick='menuservice()'>
                        <span>服务单</span><br/><img src='images/aside/zuo.png' id="foldmark">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="reset"></div>
    </div>
    
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
    
<!----------导航栏---------->
<script type="text/javascript">
    jQuery(document).ready(function(){
    var record = "";
    var clicked = 0;
	var qcloud={};
	$('[_t_nav]').click(function(){
        if(clicked === 0){
            document.getElementById("head-v3").style.height = "150px";
            clicked = 1;
            var _nav = $(this).attr('_t_nav');
            
            record = $(this).attr('_t_nav');
            if(_nav == "categories") {
                document.getElementById("color_change1").style.color = "white";
            }
            else if(_nav == "sort") {
                document.getElementById("color_change2").style.color = "white";
            }
            else if(_nav == "selected") {
                document.getElementById("head-v3").style.height = "60px";
                document.getElementById("color_change3").style.color = "white";
            }
            else if(_nav == "view") {
                document.getElementById("color_change4").style.color = "white";
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
            if(_nav == record) {
                document.getElementById("head-v3").style.height = "60px";
                if(_nav == "categories") {
                    document.getElementById("color_change1").style.color = "black";
                }
                else if(_nav == "sort") {
                    document.getElementById("color_change2").style.color = "black";
                }
                else if(_nav == "selected") {
                    document.getElementById("color_change3").style.color = "black";
                }
                else if(_nav == "view") {
                    document.getElementById("color_change4").style.color = "black";
                }
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
                    if(record == "categories") {
                        document.getElementById("color_change1").style.color = "black";
                    }
                    else if(record == "sort") {
                        document.getElementById("color_change2").style.color = "black";
                    }
                    else if(record == "selected") {
                        document.getElementById("head-v3").style.height = "150px";
                        document.getElementById("color_change3").style.color = "black";
                    }
                    else if(record == "view") {
                        document.getElementById("color_change4").style.color = "black";
                    }
                    $('[_t_nav]').removeClass('nav-up-selected');
                    $('#'+record).stop(true,true).slideUp(200);
                    console.log(record);
                }, 150);
                qcloud[ record + '_timer' ] = setTimeout(function(){
                    if(_nav == "categories") {
                        document.getElementById("color_change1").style.color = "white";
                    }
                    else if(_nav == "sort") {
                        document.getElementById("color_change2").style.color = "white";
                    }
                    else if(_nav == "selected") {
                        document.getElementById("head-v3").style.height = "60px";
                        document.getElementById("color_change3").style.color = "white";
                    }
                    else if(_nav == "view") {
                        document.getElementById("color_change4").style.color = "white";
                    }
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
</script>

<script type="text/javascript">
    //动态计算微预约卡，保持左时间轴标记与右侧内容高度一致 -- start
    var mc_car_logo = document.getElementById("mc_car_logo")
    var mc_car_content = document.getElementById("mc_car_content")
    if (mc_car_logo.scrollHeight < mc_car_content.scrollHeight)
    {
        mc_car_logo.style.height = mc_car_content.scrollHeight+"px";
    }
    var mc_service_logo = document.getElementById("mc_service_logo")
    var mc_service_content = document.getElementById("mc_service_content")
    if (mc_service_logo.scrollHeight < mc_service_content.scrollHeight)
    {
        mc_service_logo.style.height = mc_service_content.scrollHeight+"px";
    }
    var mc_shop_logo = document.getElementById("mc_shop_logo")
    var mc_shop_content = document.getElementById("mc_shop_content")
    if (mc_shop_logo.scrollHeight < mc_shop_content.scrollHeight)
    {
        mc_shop_logo.style.height = mc_shop_content.scrollHeight+"px";
    }
    var mc_account_logo = document.getElementById("mc_account_logo")
    var mc_account_content = document.getElementById("mc_account_content")
    if (mc_account_logo.scrollHeight < mc_account_content.scrollHeight)
    {
        mc_account_logo.style.height = mc_account_content.scrollHeight+"px";
    }
    //动态计算微预约卡，保持左时间轴标记与右侧内容高度一致 -- end

    /**
     * 取消车型操作成功后，将清空服务预约卡数据
     */
    function cancelcarmodel()
    {
        jQuery.ajax({
            url:"/ac/client.html?default[action]=cart_resetcar&ajax=ajax&wndname=default",
            type:"post",
            success:function(){
                //取消成功后，页面跳转至首页
                window.location.href="../../index.html";
            }
        });
    }

</script>
<!-- 浮动层随页面滚动而滚动 -->
<script type="text/javascript">

    //维修保养服务列表，右侧边栏
    var shoplist_sidebar = document.getElementById('shoplist_sidebar');
    //维修保养服务详情，右侧边栏
    var shopdetail_sidebar = document.getElementById('shopdetail_sidebar');
    //微预约服务卡
    var micro_cart = document.getElementById('infownd-box');
    //服务单
    var ss_shortcutbtn = document.getElementById('ss_shortcutbtn');
    
    //网页正文全文高
    var h = document.documentElement.scrollHeight || document.body.scrollHeight;

    //维修保养服务列表右侧边栏如存在，则微预约卡无需跟随定位
    if (micro_cart != null && shoplist_sidebar == null && shopdetail_sidebar == null) {
        //页头高度
        var headerh = 126;
        //页脚高度
        var footerh = 380;
        //默认不进行浮动
        micro_cart.style.position = "static";
        micro_cart.style.top = "0px";
        //服务单同上
        ss_shortcutbtn.style.position = "static";
        ss_shortcutbtn.style.top = "0px";
        //micro_cart对象内容高度
        var ch = micro_cart.offsetHeight;
        if (micro_cart.offsetHeight < 600) {//微服务预约卡总高度大于500时，页面不进行自动滚动
            window.onscroll = function () {
                //网页被卷去的高
                var t = document.documentElement.scrollTop || document.body.scrollTop;
                //micro_cart对象距离网页底部高度
                var b = h - (t + ch + headerh);
                if (t >= 141) {//初始状态下微服务预约卡距离页面顶部141
                    if (b < 0) {
                        micro_cart.style.top = (h - (footerh + ch + headerh)) + "px";
                        micro_cart.style.position = "absolute";
                    }
                    else {
                        micro_cart.style.position = "fixed";
                        micro_cart.style.top = "0px";
                    }
                    micro_cart.style.height = "auto";
                    if (document.getElementById('menuservice').style.width == "260px") {
                        micro_cart.style.width = "260px";
                        //alert("haha");
                    }
                    else {
                        micro_cart.style.width = "25px";
                        //alert("lala");
                    }
                    
                }
                else {
                    micro_cart.style.position = "static";
                    micro_cart.style.top = "0px";
                    micro_cart.style.height = "auto";
                    if (document.getElementById('menuservice').style.width == "260px") {
                        micro_cart.style.width = "260px";
                        //alert("haha");
                    }
                    else {
                        micro_cart.style.width = "25px";
                        //alert("lala");
                    }
                }
            }
        }
    }
    
    function menuservice() {
        if($('#micro_cart').css('display') == 'none') {
            document.getElementById('menuservice').style.width = "260px";
            document.getElementById('infownd-box').style.width = "260px";
            $('#micro_cart').css('display','block');
            $('#foldmark').attr('src','images/aside/you.png');
        }
        else{
            document.getElementById('menuservice').style.width = "25px";
            document.getElementById('infownd-box').style.width = "25px";
            $('#micro_cart').css('display','none');
            $('#foldmark').attr('src','images/aside/zuo.png');
        }
    }
    
<!--移动端页面跳转-->
$(window).resize(function(){
    if(document.body.clientWidth<=768){
        window.location.href="../Phone/maintenance.php";
    }
});
</script>
    
