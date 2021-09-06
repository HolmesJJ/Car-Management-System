<!DOCTYPE html>
<html>
<head>
    <title>Servicestore Detail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <!---------- 菜单 ---------->
    <script src="js/modernizr.custom.js"></script>
    <script src="js/modernizr-2.6.2.min.js"></script>
    
    <script type="text/javascript" src="js/packageproductc.js"></script>

    <link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/maintenance_Detail.css' type='text/css' />
    
    <!-------汽车轮播------->
    <link rel="stylesheet" href="css/edslider.css" type='text/css' />
	<link rel="stylesheet" href="css/servicestore_Detail.css" type='text/css' />
    
    <?php
    session_start();
    date_default_timezone_set("Asia/Singapore");
    $timestamp = date('y-m-d h:i:s',time());
    
    if (isset($_GET['ServiceStore_Id']) && !empty($_GET['ServiceStore_Id'])) {
        $ServiceStore_Id = $_GET['ServiceStore_Id'];
        
        $conn = mysqli_connect("localhost", "root", "","db160015Q_project" );
    
        if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
            $Username = $_SESSION['Username'];
            if(isset($_POST["car_model"])){
                $Car_Model = $_POST["car_model"];
                $_SESSION['Car_Model'] = $_POST["car_model"];
                echo $_SESSION['Car_Model'];
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
  
        $ServiceStore_filter = "A.ServiceStore_Id = $ServiceStore_Id";
        $sql = "SELECT A.ServiceStore_Name, A.Address, B.Location, A.Phone, A.Email, A.Create_Time, A.Picture1, A.Picture2, A.Picture3, A.Description, A.Opening_Hours, A.Car_Model, A.Rating AS ServiceStore_Rating, C.Rating AS Item_Rating, A.Page_View, A.Selected_Quantities, A.lat, A.lng FROM servicestore AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN serviceitem AS C ON A.ServiceStore_Id = C.ServiceStore_Id WHERE ".$ServiceStore_filter;
        $servicestore_result = mysqli_query($conn, $sql);
        $one_servicestore_result = mysqli_fetch_assoc($servicestore_result);
        $ServiceStore_Name = $one_servicestore_result['ServiceStore_Name'];
        $Address = $one_servicestore_result['Address'];
        $Location = $one_servicestore_result['Location'];
        $Phone = $one_servicestore_result['Phone'];
        $Email = $one_servicestore_result['Email'];
        $Create_Time = $one_servicestore_result['Create_Time'];
        $Picture1 = $one_servicestore_result['Picture1'];
        $Picture2 = $one_servicestore_result['Picture2'];
        $Picture3 = $one_servicestore_result['Picture3'];
        $Description = $one_servicestore_result['Description'];
        $Opening_Hours = $one_servicestore_result['Opening_Hours'];
        $Car_Model = $one_servicestore_result['Car_Model'];
        $ServiceStore_Rating = $one_servicestore_result['ServiceStore_Rating'];
        $Item_Rating = $one_servicestore_result['Item_Rating'];
        $Page_View = $one_servicestore_result['Page_View'];
        $Selected_Quantities = $one_servicestore_result['Selected_Quantities'];
        $lat = $one_servicestore_result['lat'];
        $lng = $one_servicestore_result['lng'];
        
        $ServiceStore_filter = "C.ServiceStore_Id = $ServiceStore_Id";
        $Comment_Search = "B.Customer_Name, A.Comment, C.Rating AS Item_Rating, C.Page_View, C.Selected_Quantities, D.Rating AS Store_Rating, D.Car_Model, A.Created";
        $sql_Comment = "SELECT ".$Comment_Search." FROM comment AS A INNER JOIN customer AS B ON A.Customer_Id = B.Customer_Id INNER JOIN serviceitem AS C ON A.Item_Id = C.Item_Id INNER JOIN servicestore AS D ON C.ServiceStore_Id = D.ServiceStore_Id WHERE ".$ServiceStore_filter;
        $comment_list = mysqli_query($conn, $sql_Comment);
        
        mysqli_close($conn);
        
        if(isset($_GET['Alert'])) {
            if($_GET['Alert'] == "Alert") {
                echo "<script>alert('Please select your Car Model first!')</script>";
            }
            else if($_GET['Alert'] == "Login") {
                echo "<script>alert('Please login your account first!')</script>";
            }
            else {
                echo "<script>alert('System Error! Please select again')</script>";
            }
        }
    }
    else {
        if(isset($_POST["Selected_Store_Id"])){
            $Selected_Store_Id = $_POST["Selected_Store_Id"];
            if(isset($_SESSION["Username"]) && !empty($_SESSION['Username'])){
                $Username = $_SESSION['Username'];
                if(isset($_SESSION["Car_Model"]) && !empty($_SESSION['Car_Model'])) {
                    $conn = mysqli_connect("localhost", "root", "","db160015Q_project");
                    $sql = "SELECT ServiceStore_Name FROM servicestore WHERE ServiceStore_Id = $Selected_Store_Id";
                    $customer_sql = "SELECT Customer_Id FROM customer WHERE Username = '$Username'";
                    $customer_id_result = mysqli_query($conn, $customer_sql);
                    $one_customer_id = mysqli_fetch_assoc($customer_id_result);
                    $Customer_Id = $one_customer_id['Customer_Id'];
                    if(isset($_SESSION["Item_Id"]) && !empty($_SESSION['Item_Id'])){
                        $Item_Id = $_SESSION["Item_Id"];
                        $sql_detect = "SELECT * FROM serviceitem WHERE ServiceStore_Id = $Selected_Store_Id && Item_Id = $Item_Id";
                        $sql_detect_result = mysqli_query($conn, $sql_detect);
                        if (!$sql_detect_result || mysqli_num_rows($sql_detect_result) == 0){
                            mysqli_close($conn);
                            header("Location:servicestore.php?Alert=Difference");
                        }
                        else {
                            $ServiceStore_Name_result = mysqli_query($conn, $sql);
                            $one_ServiceStore_Name_result = mysqli_fetch_assoc($ServiceStore_Name_result);
                            $sql_detect_cart = "SELECT Customer_Id FROM cart WHERE Customer_Id = $Customer_Id";
                            $sql_detect_cart_result = mysqli_query($conn, $sql_detect_cart);
                            
                            if (!$sql_detect_cart_result || mysqli_num_rows($sql_detect_cart_result) == 0){
                                $i_sql = "INSERT INTO cart (Customer_Id, ServiceStore_Id, Created, Updated) VALUES ($Customer_Id, $Selected_Store_Id, '$timestamp', '$timestamp')";
                                $i_u_result = mysqli_query($conn, $i_sql);
                            }
                            else {
                                $u_sql = "UPDATE cart SET ServiceStore_Id = $Selected_Store_Id, Updated =  '$timestamp' WHERE Customer_Id = $Customer_Id";
                                $i_u_result = mysqli_query($conn, $u_sql);
                            }
                            if($i_u_result) {
                                $_SESSION["ServiceStore_Id"] = $_POST["Selected_Store_Id"];
                                $_SESSION["ServiceStore_Name"] = $one_ServiceStore_Name_result["ServiceStore_Name"];
                                mysqli_close($conn);
                                header("Location:appointment.php");
                            }
                            else {
                                mysqli_close($conn);
                                header("Location:servicestore_Detail.php?ServiceStore_Id=$Selected_Store_Id&&Alert=Error");
                            }
                        }
                    }
                    else {
                        $ServiceStore_Name_result = mysqli_query($conn, $sql);
                        $one_ServiceStore_Name_result = mysqli_fetch_assoc($ServiceStore_Name_result);
                        $i_sql = "INSERT INTO cart (Customer_Id, ServiceStore_Id, Created, Updated) VALUES ($Customer_Id, $Selected_Store_Id, '$timestamp', '$timestamp')";
                        $i_result = mysqli_query($conn, $i_sql);
                        if($i_result) {
                            $_SESSION["ServiceStore_Id"] = $_POST["Selected_Store_Id"];
                            $_SESSION["ServiceStore_Name"] = $one_ServiceStore_Name_result["ServiceStore_Name"];
                            mysqli_close($conn);
                            header("Location:maintenance.php");
                        }
                        else {
                            mysqli_close($conn);
                            header("Location:servicestore_Detail.php?ServiceStore_Id=$Selected_Store_Id&&Alert=Error");
                        }
                    }
                    mysqli_close($conn);
                }
                else {
                    header("Location:servicestore_Detail.php?ServiceStore_Id=$Selected_Store_Id&&Alert=Alert");
                }
            }
            else {
                header("Location:servicestore_Detail.php?ServiceStore_Id=$Selected_Store_Id&&Alert=Login");
            }
        }
        else {
            header("Location:servicestore_Detail.php?ServiceStore_Id=$Selected_Store_Id&&Alert=Login");
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
    
<!--------------------------服务框----------------------------->
    <div class="main">
        <!-- packageproduct detail left start -->
        <div class="main-left">
        <?php
            if(isset($_POST["car_model"]) && !empty($_POST['car_model'])){}
            else {
                if(isset($_SESSION["Car_Model"]) && !empty($_SESSION['Car_Model'])){}
                else {
                    echo "<div class='warning_prompt'>
                        <div class='warning_prompt_box'>
                            <span>▪Please set the car model first!</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href='#' class='light-operation' onclick=\"document.getElementById('car_model').style.display='block'\">Select the car model&gt;&gt;</a>
                        </div>
                    </div>";
                } 
            }
        ?>
            <div class='mainwnd'>
                <div class='infownd-box'>
                    <div class='shop' >
                        <div class='shopnametitle'><?php echo $ServiceStore_Name;?>
                            <span style='font-weight:normal;font-size:12px;'>(<?php echo $ServiceStore_Name;?>)</span>
                            <span style='color: rgb(74, 153, 210); font-size: 18px;margin-left: 25px;'><?php echo $ServiceStore_Rating;?></span><span style='font-size:15px;color:#989697;font-weight:normal;'>/5 Marks</span>
                        </div>
                        <div class='subbox'>
                            <div style=' margin:0 auto; width:305px;height:190px;' id='small_img_1'>
                                <img id="bigImg" src="<?php echo $Picture1;?>" width=305 height=190>
                            </div>
                        </div>
                        <div class='shopcontent' style='width: 100%;'>
                            <form name="Form1" action='servicestore_Detail.php' method='post' id="Form1">
                                <div class='yuyuediv'>
                                    <input type="text" name="Selected_Store_Id" value="<?php echo $ServiceStore_Id; ?>" hidden="hidden"/>
                                    <a class='yuyue' onclick='s_Submit()' >Book Now</a>
                                </div>
                            </form>
                            <div class='item'><div ><label>Page View:</label><?php echo $Page_View;?></div></div>
                            <div class='item'><div ><label>Selected Quantities:</label><?php echo $Selected_Quantities;?></div></div>
                            <div class="reset"></div>
                            <div class='item' style='height:28px;width:655px;'>
                                <div>
                                    <span class='smallstyle' style='padding:0;'><label>Environment:</label></span>
                                    <img src='images/article/wirelessnetwork.png' class='smallstyle' />&nbsp;
                                    <img src='images/article/parkingspace.png' class='smallstyle' />&nbsp;
                                    <img src='images/article/restarea.png'  class='smallstyle' />&nbsp;
                                    <img src='images/article/toilet.png'  class='smallstyle' />
                                </div>
                            </div>
                            <div class='item'><div><label>Phone:</label><?php echo $Phone;?></div></div>
                            <div class='item'><div><label>Email:</label><?php echo $Email;?></div></div>
                            <div class='item'><div><label>Opening Hours:</label><?php echo $Opening_Hours;?></div></div>
                            <div class='item'><div><label>Create Time:</label><?php echo $Create_Time;?></div></div>
                            <div class='item'><div><label>Address(Location):</label><span id="address_box"><?php echo $Address;?>&nbsp;(<?php echo $Location;?>)</span></div></div>
                            <div class='item'>
								<div class='smallstyle' style='float:left;'><label>Car Models:</label></div>
								<div style='float:left; width:200px;'>
                                    <?php
                                        $Car_Model_arr = explode(",", $Car_Model);
                                        for($i=0; $i<count($Car_Model_arr); $i++) {
                                        ?>
                                            <img src='images/smallcar/<?php echo $Car_Model_arr[$i];?>.png' />
                                        <?php
                                        }
                                    ?>
                                </div>
                            </div>
				            <div class="reset"></div>
				            <div class='item' style='height:auto;'>
								<div style='float:left;'><label>Main business:</label></div>
								<div style='float:left;'>
                                    <table>
                                        <tr>
                                            <td id="hideparts" colspan="3">
                                                <a style="color:#FE5417;font-size: 14px;" onclick="showhide()"><img src="images/article/show.png" style="position:relative;top:8px;margin-right:5px;">Show</a>
                                            </td>
                                            <td style="display:none;" id="hideparts01" colspan="5">
                                                <a style="color:#FE5417;font-size: 14px;" onclick="putaway()"><img src="images/article/hide.png" style="position:relative;top:8px;margin-right:5px;">Hide</a>
                                            </td>
                                        </tr>
                                        <tr class="subtr">
                                            <td><a href="#">Oil filter</a></td>
                                            <td><a href="#">Air filter</a></td>
                                            <td><a href="#">Gas filter</a></td>
                                        </tr>
                                        <tr class="subtr">
                                            <td><a href="#">Antifreeze</a></td>
                                            <td><a href="#">Auto-transmission</a></td>
                                            <td><a href="#">Man-transmission</a></td>
                                        </tr>
                                        <tr class="shopservice_tr subtr" style="display:none;">
                                            <td class="shopservice_td"><a href="#">Brake Fluid</a></td>
                                            <td class="shopservice_td"><a href="#">Steering fluid</a></td>
                                            <td class="shopservice_td"><a href="#">Air Conditioning</a></td>
                                        </tr>
                                        <tr class="shopservice_tr subtr" style="display:none;">
                                            <td class="shopservice_td"><a href="#">Spark Plug</a></td>
                                            <td class="shopservice_td"><a href="#">Front Brake Pads</a></td>
                                            <td class="shopservice_td"><a href="#">Front Brake Disc</a></td>
                                        </tr>
                                        <tr class="shopservice_tr subtr" style="display:none;">
                                            <td class="shopservice_td"><a href="#">Belt</a></td>
                                            <td class="shopservice_td"><a href="#">Back Brake Pads</a></td>
                                            <td class="shopservice_td"><a href="#">Tires</a></td>
                                        </tr>
                                    </table>
                                </div>
							</div>
						</div>                        
                        <div class='reset'></div>
                    </div>
                </div>
            </div> 
            <div class='mainwnd' id='route'>
                <div class='infownd-box'>
                    <div class='h3tests'>
                        <div onclick="change_view('comment')">User Comment</div>
                        <div onclick="change_view('introduction')">Service Station</div>
                        <div onclick="change_view('route')" class='underline'>Vehicle Route</div>
                        <div clas="reset"></div>
                    </div>
                    <div>
                        <div id="googleMap"></div>
                        <div class="reset"></div>
                    </div>
                </div>
            </div>
            <div class='mainwnd' id='comment' style="display:none;">
                <div class='infownd-box'>
                    <div class='h3tests'>
                        <div onclick="change_view('comment')" class='underline'>User Comment</div>
                        <div onclick="change_view('introduction')">Service Station</div>
                        <div onclick="change_view('route')">Vehicle Route</div>
                        <div clas="reset"></div>
                    </div>
                <?php
                if (!$comment_list || mysqli_num_rows($comment_list) == 0){
                    
                }
                else {
                    while ($one_comment = mysqli_fetch_assoc($comment_list)) {
                ?>
                    <div style="width:100%;line-height: 20px;">
                        <div style="width:100%;height:30px;line-height: 30px;border-bottom:1px solid #ccc;">
                            <div style="width:30%;float:left;">
                                Technology:&nbsp;<label style="color:#4A99D1;font-size: 18px;font-weight: 800;">5</label><span>/5</span>
                            </div>
                            <div style="width:20%;float:left;">
                                Services:&nbsp;<label style="color:#4A99D1;font-size: 18px;font-weight: 800;"><?php echo $Item_Rating; ?></label><span>/5</span>
                            </div>
                            <div style="width:20%;float:left;">
                                Items:&nbsp;
                                <label style="color:#4A99D1;font-size: 18px;font-weight: 800;"><?php echo $ServiceStore_Rating; ?></label><span>/5</span>
                            </div>
                            <div style="width:30%;float:left;text-align: right;color:#aaa;"><?php echo $one_comment["Created"]; ?></div>
                        </div>
                        <div class="reset"></div>
                        <div style="width:25%;float:left;margin-top:10px;text-align:center;">
                            <img src="" alt="" style="margin-left:15px;width:50px;height:50px" /><br/>
                            <span style="position:relative;top:-20px;"><?php echo $one_comment["Customer_Name"]; ?></span>
                        </div>
                        <div style="width:75%;float: right;border-left:1px solid #ccc;margin-top:10px;">
                            <div style="width:100%;color:#888;">
                                <div style="width:30%;float:left;text-align: right;">Car Model:&nbsp;</div>
                                <div style="width:70%;float:left;">
                                    <span style="position:relative;top:-11px;">
                                        <?php
                                            $Car_Model = $one_comment["Car_Model"];
                                            $Car_Model_arr = explode(",", $Car_Model);
                                            for($i=0; $i<count($Car_Model_arr); $i++) {
                                            ?>
                                                <img src='images/smallcar/<?php echo $Car_Model_arr[$i];?>.png' style="position:relative;top:8px;border:1px solid #ccc;width:24px;"/>
                                        <?php
                                        }
                                    ?>
                                    </span>
                                </div>
                            </div>
                            <div style="width:100%;">
                                <div style="width:30%;float:left;text-align: right;">Comment:&nbsp;</div>
                                <div style="width:70%;float:left;"><?php echo $one_comment["Comment"]; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="reset"></div>
                <?php
                    }
                }
                ?>
                </div>
            </div>
            <div class='mainwnd' id='introduction' style="display:none;">
                <div class='infownd-box'>
                    <div class='h3tests'>
                        <div onclick="change_view('comment')">User Comment</div>
                        <div onclick="change_view('introduction')" class='underline'>Service Station</div>
                        <div onclick="change_view('route')">Vehicle Route</div>
                        <div clas="reset"></div>
                    </div>
                    <div> 
                        <div class='templateone' >
                            <div class='templatetonebottom' style='clear:both'>
                                <h3><?php echo $ServiceStore_Name; ?></h3>
                            </div>
                            <p><?php echo $Description; ?></p>
                            <p></p>
                            <p></p>
                        </div>
                        <div class="reset"></div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <div class="reset"></div>
    <script>
        function change_view(id) {
            if(id == "comment") {
                document.getElementById("comment").style.display = "block";
                document.getElementById("introduction").style.display = "none";
                document.getElementById("route").style.display = "none";
            }
            else if(id == "introduction"){
                document.getElementById("introduction").style.display = "block";
                document.getElementById("comment").style.display = "none";
                document.getElementById("route").style.display = "none";
            }
            else if(id == "route"){
                document.getElementById("comment").style.display = "none";
                document.getElementById("introduction").style.display = "none";
                document.getElementById("route").style.display = "block";
            }
        }
    </script>
 
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

<!----------------- 地图 --------------------->
<script>
    var map;
    function initMap() {
        map = new google.maps.Map(document.getElementById('googleMap'), {
            center: {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>},
            zoom: 17
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNufziXZ1bnhQglddfKhWzWjfIvM3pxrA&callback=initMap" async defer></script>

    <script>
        function cartypedetil()
        {
            $('.cartype').click();
        }
        function showhide(){
            $('#hideparts').hide();
            $('#hideparts01').show();
            $('.shopservice_tr').show();
            $('.shopservice_td').show();
        }
        function putaway(){
            $('#hideparts').show();
            $('#hideparts01').hide();
            $('.shopservice_tr').hide();
            $('.shopservice_td').hide();
        }
    </script>

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
        
<!--PC_Tablet端页面跳转-->
$(window).resize(function(){
    if(document.body.clientWidth>768){
        window.location.href="../PC_Tablet/servicestore_Detail.php";
    }
});
</script>