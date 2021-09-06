<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Detail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script src="js/jquery/jquery.advertisement.js"></script>
    <script type="text/javascript" src="js/packageproductc.js"></script>
    
    <!------- 图片放大镜 ------->
    <script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery/jquery.imagezoom.min.js"></script>

    <!-------选择车型、服务展示、广告位 ------->
    <link rel='stylesheet' href='css/alertproductc.css' type='text/css' />
            
	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/maintenance_Detail.css' type='text/css' />
    
    <?php
    session_start();
    date_default_timezone_set("Asia/Singapore");
    $timestamp = date('y-m-d h:i:s',time());
    
    if (isset($_GET['Item_Id']) && !empty($_GET['Item_Id'])) {
        $Item_Id = $_GET['Item_Id'];
        
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
                
                $count = 0;
                if(isset($_POST["Comment"])){
                    $sql_customer_id_search = "SELECT Customer_Id FROM comment WHERE Item_Id = $Item_Id";
                    $customer_id_search_result = mysqli_query($conn, $sql_customer_id_search);
                    if (!$customer_id_search_result || mysqli_num_rows($customer_id_search_result) == 0){
                        $count = 1;
                    }
                    else {
                        while ($one_customer_id_search_result = mysqli_fetch_assoc($customer_id_search_result)) {
                            if($one_customer_id_search_result['Customer_Id'] == $Customer_Id) {
                                header("Location:maintenance_Detail.php?Item_Id=$Item_Id&&Alert=Comment");
                            }
                            else {
                                $count++;
                            }
                        }
                    }
                    if($count != 0) {
                        $Comment = $_POST["Comment"];
                        $comment_sql = "INSERT INTO comment (Customer_Id, Item_Id, Comment, Created) VALUES ($Customer_Id, $Item_Id, '$Comment', '$timestamp')";
                        $comment_sql_result = mysqli_query($conn, $comment_sql);
                        echo $comment_sql;
                        header("Location:maintenance_Detail.php?Item_Id=$Item_Id&hahaha=1");
                    }
                }
            }
        }
        
        if(isset($_POST["car_model"]) || isset($_POST["Comment"])){
            if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
            }
            else {
                header("Location:login.php?Alert=Login");
            }
        }
        
        $Item_filter = "A.Item_Id = $Item_Id";
        $sql = "SELECT A.Item_Name, A.Item_Picture, A.Item_Detail_Picture, A.Description, A.Price, B.Status, A.Category_Id, C.Category_Name FROM serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id WHERE ".$Item_filter;
        $item_result = mysqli_query($conn, $sql);
        $one_item_result = mysqli_fetch_assoc($item_result);
        $Item_Name = $one_item_result['Item_Name'];
        $Item_Picture = $one_item_result['Item_Picture'];
        $Item_Detail_Picture = $one_item_result['Item_Detail_Picture'];
        $Description = $one_item_result['Description'];
        $Price = $one_item_result['Price'];
        $Status = $one_item_result['Status'];
        $Category_Id = $one_item_result['Category_Id'];
        $Category_Name = $one_item_result['Category_Name'];

        $Item_filter = "A.Item_Id = $Item_Id";
        $Comment_Search = "B.Customer_Name, A.Comment, C.Rating AS Item_Rating, C.Page_View, C.Selected_Quantities, D.Rating AS Store_Rating, D.Car_Model, A.Created";
        $sql_Comment = "SELECT ".$Comment_Search." FROM comment AS A INNER JOIN customer AS B ON A.Customer_Id = B.Customer_Id INNER JOIN serviceitem AS C ON A.Item_Id = C.Item_Id INNER JOIN servicestore AS D ON C.ServiceStore_Id = D.ServiceStore_Id WHERE ".$Item_filter;
        $comment_list = mysqli_query($conn, $sql_Comment);
        
        mysqli_close($conn);
        
        if(isset($_GET['Alert'])) {
            if($_GET['Alert'] == "Alert") {
                echo "<script>alert('Please select your Car Model first!')</script>";
            }
            else if($_GET['Alert'] == "Login") {
                echo "<script>alert('Please login your account first!')</script>";
            }
            else if($_GET['Alert'] == "Comment") {
                echo "<script>alert('You have already commented on it. Thanks.')</script>";
            }
            else {
                echo "<script>alert('System Error! Please select again')</script>";
            }
        }
    }
    else {
        if(isset($_POST["Selected_Item_Id"])){
            $Selected_Item_Id = $_POST["Selected_Item_Id"];
            if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
                $Username = $_SESSION['Username'];
                if(isset($_SESSION["Car_Model"]) && !empty($_SESSION['Car_Model'])) {
                    $conn = mysqli_connect("localhost", "root", "","db160015Q_project");
                    $sql = "SELECT Item_Name FROM serviceitem WHERE Item_Id = $Selected_Item_Id";
                    $customer_sql = "SELECT Customer_Id FROM customer WHERE Username = '$Username'";
                    $customer_id_result = mysqli_query($conn, $customer_sql);
                    $one_customer_id = mysqli_fetch_assoc($customer_id_result);
                    $Customer_Id = $one_customer_id['Customer_Id'];
                    if(isset($_SESSION["ServiceStore_Id"]) && !empty($_SESSION['ServiceStore_Id'])){
                        $ServiceStore_Id = $_SESSION["ServiceStore_Id"];
                        $sql_detect = "SELECT * FROM serviceitem WHERE ServiceStore_Id = $ServiceStore_Id && Item_Id = $Selected_Item_Id";
                        $sql_detect_result = mysqli_query($conn, $sql_detect);
                        if (!$sql_detect_result || mysqli_num_rows($sql_detect_result) == 0){
                            mysqli_close($conn);
                            header("Location:maintenance.php?Alert=Difference");
                        }
                        else {
                            $Item_Name_result = mysqli_query($conn, $sql);
                            $one_Item_Name_result = mysqli_fetch_assoc($Item_Name_result);
                            $sql_detect_cart = "SELECT Customer_Id FROM cart WHERE Customer_Id = $Customer_Id";
                            $sql_detect_cart_result = mysqli_query($conn, $sql_detect_cart);
                            if (!$sql_detect_cart_result || mysqli_num_rows($sql_detect_cart_result) == 0){
                                $i_sql = "INSERT INTO cart (Customer_Id, Item_Id, Quantity, Created, Updated) VALUES ($Customer_Id, $Selected_Item_Id, 1, '$timestamp', '$timestamp')";
                                $i_u_result = mysqli_query($conn, $i_sql);
                            }
                            else {
                                $u_sql = "UPDATE cart SET Item_Id = $Selected_Item_Id, Quantity = 1, Updated =  '$timestamp' WHERE Customer_Id = Customer_Id";
                                $i_u_result = mysqli_query($conn, $u_sql);
                            }
                            if($i_u_result) {
                                $_SESSION["Item_Id"] = $_POST["Selected_Item_Id"];
                                $_SESSION["Item_Name"] = $one_Item_Name_result["Item_Name"];
                                mysqli_close($conn);
                                header("Location:appointment.php");
                            }
                            else {
                                mysqli_close($conn);
                                header("Location:maintenance_Detail.php?Item_Id=$Selected_Item_Id&&Alert=Error");
                            }
                        }
                    }
                    else {
                        $Item_Name_result = mysqli_query($conn, $sql);
                        $one_Item_Name_result = mysqli_fetch_assoc($Item_Name_result);
                        $i_sql = "INSERT INTO cart (Customer_Id, Item_Id, Quantity, Created, Updated) VALUES ($Customer_Id, $Selected_Item_Id, 1, '$timestamp', '$timestamp')";
                        $i_result = mysqli_query($conn, $i_sql);
                        if($i_result) {
                            $_SESSION["Item_Id"] = $_POST["Selected_Item_Id"];
                            $_SESSION["Item_Name"] = $one_Item_Name_result["Item_Name"];
                            mysqli_close($conn);
                            header("Location:servicestore.php");
                        }
                        else {
                            mysqli_close($conn);
                            header("Location:maintenance_Detail.php?Item_Id=$Selected_Item_Id&&Alert=Error");
                        }
                    }
                    mysqli_close($conn);
                }
                else {
                    header("Location:maintenance_Detail.php?Item_Id=$Selected_Item_Id&&Alert=Alert");
                }
            }
            else {
                header("Location:maintenance_Detail.php?Item_Id=$Selected_Item_Id&&Alert=Login");
            }
        }
        else {
            header("Location:maintenance_Detail.php?Item_Id=$Selected_Item_Id&&Alert=Login");
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
        <form class="car_model_content" action="maintenance_Detail.php?Item_Id=<?php echo $Item_Id?>" method="post">
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

<!----------------------------详情页----------------------------->
    <div class="main">
        <div class="main-left" style="position:relative;">
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
            <div>
                <div class="product_show">
                    <div class="product_title">
                        <span><?php echo $Item_Name; ?></span>
                    </div>
                    <div class="product_detail" >
                        <!-- 服务配件图片展示 start -->
                        <div class="product_img">
                            <div class="tb-booth tb-pic tb-s310">
                                <img src="<?php echo $Item_Detail_Picture; ?>" alt="" rel="<?php echo $Item_Detail_Picture; ?>" class="jqzoom" />
                            </div>
                        </div>
                        <!-- 服务配件图片展示 end -->
                        <script type="text/javascript">
                            $(document).ready(function(){
                                $(".jqzoom").imagezoom();
                            });
                        </script>
                        <!-- 服务摘要信息显示 start -->
                        <div class="product_describe">
                            <p><?php echo $Description; ?></p>
                            <p class="price-right">$<?php echo $Price; ?></p>
                            <div class="reset"></div>
                            <form id="Form1" method="post" action="maintenance_Detail.php">
                                <input name="Selected_Item_Id" type="text" value="<?php echo $Item_Id; ?>" hidden="hidden"/>
                                <button class='main_action' onclick='Submit_Item()'>Select</button>
                            </form>
                            <script>
                                function Submit_Item() {
                                    document.getElementById("Form1").submit();
                                }
                            </script>
                        </div>
                        <!-- 服务摘要信息显示 end -->
                        <div class="reset"></div>
                    </div>
                </div>
                <!-- 服务详情，名称/图片/描述/价格/操作 展示 end-->
                <!-- 服务详情，服务详细列表 展示 start-->
                <div class="product_show">
                    <table class="pp_details_table" >
                        <tr style="background-color:#344157">
                            <th>Category</th>
                            <th>Sevice Item</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                        <tr>
                            <td rowspan='1' class='2' style='padding:8px;text-align:center;'><?php echo $Category_Name; ?></td>
                            <td style="text-align:center;" class="fd_ becolor"><?php echo $Item_Name; ?></td>
                            <td style='text-align:center;' class="fd_ becolor">1</td>
                            <td style='text-align:center;' class="fd_ becolor"><?php echo $Status; ?></td>
                        </tr>
                    </table>
                </div>
                <!-- 服务详情，服务详细列表 展示 end-->
            </div>
            <!-- packageproduct detail images/desc/price/select-action/detaillist end -->

            <!-- packageproduct detail standard service desc start -->
            <div class="serve-intro">
                <div class="serve-intro-title"><span>Introduction</span></div>
                <div class="template-content">
                    <div class='templateone'>
                        <div class='templatetonebottom' style='clear:both'>
                            <h3><?php echo $Item_Name ?></h3>
                        </div>
                        <div class='templatetonetop' >
                            <img src='<?php echo $Item_Picture; ?>' style="float:left;max-width:100px;">
                        </div>
                        <p><?php echo $Description; ?></p>
                    </div>
                    <div class="reset"></div>
                </div>
                <div class="reset"></div>
            </div>
            <!-- packageproduct detail standard service desc end -->

            <!-- packageproduct detail comments start -->
            <div class="comment">
                <div class="serve-intro-title">
                    <span id="fwpl" class="underline">Comment</span>
                </div> 
            <?php
                if (!$comment_list || mysqli_num_rows($comment_list) == 0){
                    
                }
                else {
                    while ($one_comment = mysqli_fetch_assoc($comment_list)) {
                ?>
                    <div class="reset"></div>
                    <div style="width:100%;line-height: 20px;">
                        <div style="width:100%;height:30px;line-height: 30px;border-bottom:1px solid #ccc;">
                            <div style="width:25%;float:left;">Technology:
                                <label style="color:#4A99D1;font-size: 18px;font-weight: 800;">5</label>
                                <span>/5Marks</span>
                            </div>
                            <div style="width:25%;float:left;">Service:
                                <label style="color:#4A99D1;font-size: 18px;font-weight: 800;"><?php echo $one_comment["Item_Rating"]; ?></label>
                                <span>/5Marks</span>
                            </div>
                            <div style="width:25%;float:left;">Store:
                                <label style="color:#4A99D1;font-size: 18px;font-weight: 800;"><?php echo $one_comment["Store_Rating"]; ?></label>
                                <span>/5Marks</span>
                            </div>
                            <div style="width:25%;float:left;text-align: right;color:#aaa;"><?php echo $one_comment["Created"]; ?></div>
                        </div>
                        <div class="reset"></div>
                        <div style="width:100%;">
                            <div style="width:12%;float:left;margin-top:10px;text-align:center;">
                                <span><?php echo $one_comment["Customer_Name"]; ?></span>
                            </div>
                            <div style="width:87%;float: right;border-left:1px solid #ccc;margin-top:10px;">
                                <div style="width:100%;color:#888;">
                                    <div style="width:15%;float:left;text-align: right;">Car Model:&nbsp;</div>
                                    <div style="width:85%;float:left;">
                                        <span style="position:relative;top:-1px;">
                                        <?php
                                            $Car_Model = $one_comment["Car_Model"];
                                            $Car_Model_arr = explode(",", $Car_Model);
                                            for($i=0; $i<count($Car_Model_arr); $i++) {
                                            ?>
                                                <img src='images/smallcar/<?php echo $Car_Model_arr[$i];?>.png' style="position:relative;top:-3px;border:1px solid #ccc;width:24px;"/>
                                            <?php
                                            }
                                        ?>
                                        </span>
                                    </div>
                                </div>
                                <div style="width:100%;">
                                    <div style="width:15%;float:left;text-align: right;">Comment:&nbsp;</div>
                                    <div style="width:85%;float:left;"><?php echo $one_comment["Comment"]; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                }
                ?>
                <form method="post" action="maintenance_Detail.php?Item_Id=<?php echo $Item_Id?>">
                    <input class="main_action comment_btn" type="submit" value="Submit"/>
                    <textarea name="Comment" type="text" style="margin-top:30px;width:85%;height:50px;color:grey;padding:5px;" placeholder="Write down your comment..." required></textarea>
                </form>
            </div>
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
                                        <a href='javascript:void(0);' class="cart-operation cartype" >
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
                                        <div class="sub_mc_o"  >
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
    </div>
    <div class="reset"></div>

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
        window.location.href="../Phone/maintenance_Detail.php";
    }
});
</script>