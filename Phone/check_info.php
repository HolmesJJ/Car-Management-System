<!DOCTYPE html>
<html>
<head>
    <title>Check Info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <!---------- 菜单 ---------->
    <script src="js/modernizr.custom.js"></script>
    <script src="js/modernizr-2.6.2.min.js"></script>

    <link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/management.css' type='text/css' />
    
    <!-------汽车轮播------->
    <link rel="stylesheet" href="css/edslider.css" type='text/css' />
	<link rel="stylesheet" href="css/animate-custom.css" type='text/css' />
    
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
            font-size: 15px;
            font-weight: 550;
            width: 150px;
        }
        .form1 .inputstyle2 {
            height:25px; 
            width:220px;
        }
        .form1 .inputstyle2:focus {
            width:250px;
            height:25px; 
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
    
<!----------------------------内容----------------------------->
    <nav class='panel' style='margin-top:10px'>
        <div class="head-v3" id="head-v3">
            <div class="navigation-up">
                <div class="navigation-inner">
                    <div class="navigation-v3">
                        <ul>
                            <li _t_nav="myorder"><h2><a href="javascript:void(0);">My Order</a></h2></li>
                            <li _t_nav="search"><h2><a href="javascript:void(0);">Search</a></h2></li>
                            <li _t_nav="comment"><h2><a href="order_comment.php">Comment</a></h2></li>
                            <li _t_nav="myaccount"><h2><a href="javascript:void(0);">My Account</a></h2></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="navigation-down">
                <div id="myorder" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="myorder">
                    <div class="navigation-down-inner">— 
                        <dt>Please Select:</dt>
                        <dl><dd><a class="link" href="order_info.php?Status=Booked">Booked</a></dd></dl>
                        <dl><dd><a class="link" href="order_info.php?Status=Cancelled_by_customer">Cancelled(C)</a></dd></dl>
                        <dl><dd><a class="link" href="order_info.php?Status=Cancelled_by_Admin">Cancelled(A)</a></dd></dl>
                        <dl><dd><a class="link" href="order_info.php?Status=Attended">Attended</a></dd></dl>
                        <dl><dd><a class="link" href="order_info.php?Status=All_Orders">All</a></dd></dl>
                    </div>
                </div>
                <div id="search" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="search">
                    <div class="navigation-down-inner">— 
                        <dt>Please Enter(Search Service):</dt>
                        <dl><dd><input type="text" name="Search_Key_Word"></dd></dl>
                    </div>
                </div>
                <div id="comment" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="comment">
                </div>
                <div id="myaccount" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="account">
                    <div class="navigation-down-inner">
                        <dt>Please Select:</dt>
                        <dl><dd><a class="link" href="order_info.php?Status=History">History</a></dd></dl>
                        <dl><dd><a class="link" href="check_info.php">My Profile</a></dd></dl>
                        <dl><dd><a class="link" href="change_info.php">Change Profile</a></dd></dl>
                    </div>
                </div>
            </div>
        </div>
        <div class='panel2_3'>
            <div id="loading" style='text-align:center;display:none;'><img src="images/article/loading.gif"></div>
            <div class='mainwnd first-wnd'>
                <div class='wnd_content'>
                    <div class='infownd'>
                        <div class='infownd-box'> 
                            <form class='baction bsearchform form1' enctype="multipart/form-data" method='post' action='change_info.php' name='form1' id="form1">
                                <div>
                                    <h3 class='box-head'><label>My Profile</label></h3>
                                    <div>
                                        <label class="labelname labelname2" >Customer Name:</label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Customer_Name' value="<?php echo $Customer_Name ?>" disabled>
                                    </div>
                                    <div>
                                        <label class="labelname labelname2" >Userame:</label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Username' value="<?php echo $Username?>" disabled>
                                    </div>
                                    <div>
                                        <label style="position:relative;top:-10px;" class="labelname labelname2">Customer Picture: </label>
                                        <label style="position:relative;top:8px;" id="head_icon1" for="head_icon_input1">
                                            <img src="<?php echo $Customer_Picture?>" width="50px" height="50px"/>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="labelname labelname2">Gender: </label>
                                        <select class="dropdown dropdown2" type="text" name="u_Gender">
                                            <option value="<?php echo $Gender?>" selected><?php echo $Gender?></option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="labelname labelname2">Phone: </label>
                                        <input class="inputstyle inputstyle2" type="number" name='u_Phone' value="<?php echo $Phone?>" disabled>
                                    </div>
                                    <div>
                                        <label class="labelname labelname2" style="position:relative;top:-25px;">Address: </label>
                                        <textarea class="inputstyle inputstyle2" type="text" name='u_Address' style="height:70px;width:250px;margin-top:10px;" disabled><?php echo $Address?></textarea>
                                    </div>
                                    <div>
                                        <label class="labelname labelname2">Email: </label>
                                        <input class="inputstyle inputstyle2" type="email" name='u_Email' value="<?php echo $Email?>" disabled>
                                    </div>
                                    <div>
                                        <label class="labelname labelname2">License No: </label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_License_No' value="<?php echo $License_No?>" disabled>
                                    </div>
                                    <div>
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
                                        <label class="labelname labelname2" style="position:relative;top:-25px;">Last Region: </label>
                                        <?php
                                            while ($one_result = mysqli_fetch_assoc($Last_Region_List2)) {
                                                if ($Last_Region_Id == $one_result['Last_Region_Id']) {
                                                ?>
                                                    <textarea class="inputstyle inputstyle2" type="text" name='Last_Region' style="height:70px;width:250px;margin-top:10px;" disabled><?php echo $one_result['Last_Region']; ?></textarea>
                                                <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div>
                                        <label class="labelname labelname2">Last_Login_Time: </label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Last_Login_Time' value="<?php echo $Last_Login_Time?>" disabled>
                                    </div>
                                    <div>
                                        <label class="labelname labelname2">Updated: </label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Updated' value="<?php echo $Updated?>" disabled>
                                    </div>
                                    <div>
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
    <script src="js/jquery/jquery.step.js"></script>

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
        
/* ================ PC_Tablet ================== */
$(window).resize(function(){
    if(document.body.clientWidth>768){
        window.location.href="../PC_Tablet/check_info.php";
    }
});
</script>