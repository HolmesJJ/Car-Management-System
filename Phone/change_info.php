<!DOCTYPE html>
<html>
<head>
    <title>Change Info</title>
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
            display: inline-block;
            width:130px;
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
            $conn = mysqli_connect("localhost", "root", "","db160015q_project");
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

                if (isset($_POST['Submit'])) {
                    $Submit = $_POST['Submit'];
                    if ($Submit == "Update Information") {
                        //更新
                        if (!empty($_FILES['u_Customer_Picture']['tmp_name'])) {
                            $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

                            if (in_array($_FILES["u_Customer_Picture"]["type"], $allowedType)) {    
                                if ($_FILES["u_Customer_Picture"]["size"] < 5000000) { 
                                    $extension = pathinfo($_FILES['u_Customer_Picture']['name'], PATHINFO_EXTENSION);
                                    $target = "images/source/Customer/".$Username.".".$extension;
                                    $filename = "images/source/Customer/".$Username.".".$extension;

                                    $result = move_uploaded_file($_FILES["u_Customer_Picture"]["tmp_name"], $filename);

                                    if($result) {
                                        $update = "A.Customer_Picture='$target'";
                                        $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                                        $update_result = mysqli_query($conn, $u_sql);
                                    }
                                    else {
                                        echo "<script>console.log('1');</script>";
                                    }
                                }
                                else {
                                    echo "<script>console.log('2');</script>";
                                }
                            }
                            else {
                                echo "<script>console.log('3');</script>";
                            }
                        }
                        else {
                                echo "<script>console.log('4');</script>";   
                        }
                        if (isset($_POST['u_Gender']) && !empty($_POST["u_Gender"])) {
                            echo "<script>console.log('$u_Gender');</script>";
                            $u_Gender = $_POST['u_Gender'];
                            if ($u_Gender != "nil") {
                                $update = "A.Gender='$u_Gender'";
                                $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                                $update_result = mysqli_query($conn, $u_sql);
                            }
                        }
                        if (isset($_POST['u_Phone']) && !empty($_POST["u_Phone"])) {
                            $u_Phone = $_POST['u_Phone'];
                            $update = "A.Phone=$u_Phone";
                            $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                            $update_result = mysqli_query($conn, $u_sql);
                        }
                        if (isset($_POST['u_Address']) && !empty($_POST["u_Address"])) {
                            $u_Address = $_POST['u_Address'];
                            $update = "A.Address='$u_Address'";
                            $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                            $update_result = mysqli_query($conn, $u_sql);
                        }
                        if (isset($_POST['u_Email']) && !empty($_POST["u_Email"])) {
                            $u_Email = $_POST['u_Email'];
                            $update = "A.Email='$u_Email'";
                            $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                            $update_result = mysqli_query($conn, $u_sql);
                        }
                        if (isset($_POST['u_License_No']) && !empty($_POST["u_License_No"])) {
                            $u_License_No = $_POST['u_License_No'];
                            $update = "A.License_No='$u_License_No'";
                            $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                            $update_result = mysqli_query($conn, $u_sql);
                        }
                        if (isset($_POST['u_Location']) && !empty($_POST["u_Location"])) {
                            $u_Location = $_POST['u_Location'];
                            if ($u_Status != "nil") {
                                $update = "A.Location_Id='$u_Location'";
                                $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Username = '$Username'";
                                $update_result = mysqli_query($conn, $u_sql);
                            }
                        }
                        $ut = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET A.Updated='$timestamp' WHERE A.Username = '$Username'";
                        $update_time = mysqli_query($conn, $ut);
                    }
                }
            }

            //Status
            $sql_location = "SELECT * FROM location";
            $Location_List = mysqli_query($conn, $sql_location);
            $u_Location_List = mysqli_query($conn, $sql_location);
            $i_Location_List = mysqli_query($conn, $sql_location);
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
            <div class="step-body" id="myStep">
                <div class="step-header" style="width:100%">
                    <ul>
                        <li><p>Validation</p></li>
                        <li><p>Change Your Profile</p></li>
                        <li><p>Confirm</p></li>
                    </ul>
                </div>
                <div>
                    <div class="step-list"></div>
                    <div class="step-list"></div>
                    <div class="step-list"></div>
                </div>
            </div>
            <div id="loading" style='text-align:center;display:none;'><img src="images/article/loading.gif"></div>
            <div class='mainwnd first-wnd'>
                <div class='wnd_content'>
                    <div class='infownd'>
                        <div class='infownd-box'> 
                            <form class='baction bsearchform form1' enctype="multipart/form-data" method='post' action='change_info.php' name='form1' id="form1">
                                <div id="step1" style="text-align:center;">
                                    <h3 style="text-align:left;" class='box-head'><label>Validation</label></h3>
                                    <div style="margin:20px;">
                                        <label class="labelname labelname1">Username: </label>
                                        <input class="inputstyle inputstyle1" type="text" name='Username'>
                                    </div>
                                    <div style="margin:10px;">
                                        <label class="labelname labelname1">Phone: </label>
                                        <input class="inputstyle inputstyle1" type="number" name='Phone'>
                                    </div>
                                    <div style="margin:20px;">
                                        <label class="labelname labelname1">Email: </label>
                                        <input class="inputstyle inputstyle1" type="email" name='Email'>
                                    </div>
                                </div>
                                <div id="step2" style="display:none;">
                                    <h3 class='box-head'><label>Change Your Profile</label></h3>
                                    <div>
                                        <label class="labelname labelname2" >Customer Name:</label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_Customer_Name' value="<?php echo $Customer_Name?>" disabled>
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
                                        <input id="head_icon_input1" type="file" name='u_Customer_Picture' hidden="hidden" onchange="Picture_Show1(this);">
                                    </div>
                                    <div>
                                        <label class="labelname labelname2">Gender: </label>
                                        <select class="dropdown dropdown2" type="text" name="u_Gender">
                                            <?php
                                                if($Gender == "Male") {
                                                ?>
                                                    <option value="Male" selected>Male</option>
                                                    <option value="nil">-----</option>
                                                    <option value="Female">Female</option>
                                                <?php
                                                }
                                                else if($Gender == "Female"){
                                                ?>
                                                    <option value="Female" selected>Female</option>
                                                    <option value="nil">-----</option>
                                                    <option value="Male">Male</option>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <option value="nil" selected>-----</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="labelname labelname2">Phone: </label>
                                        <input class="inputstyle inputstyle2" type="number" name='u_Phone' value="<?php echo $Phone?>">
                                    </div>
                                    <div>
                                        <label class="labelname labelname2" style="position:relative;top:-25px;">Address: </label>
                                        <textarea class="inputstyle inputstyle2" type="text" name='u_Address' style="height:70px;width:220px;margin-top:10px;"><?php echo $Address?></textarea>
                                    </div>
                                    <div>
                                        <label class="labelname labelname2">Email: </label>
                                        <input class="inputstyle inputstyle2" type="email" name='u_Email' value="<?php echo $Email?>">
                                    </div>
                                    <div>
                                        <label class="labelname labelname2">License No: </label>
                                        <input class="inputstyle inputstyle2" type="text" name='u_License_No' value="<?php echo $License_No?>">
                                    </div>
                                    <div>
                                        <label class="labelname labelname2">Location: </label>
                                        <select class="dropdown dropdown2" type="text" name="u_Location">
                                            <option value="nil" >-----</option>
                                        <?php
                                            while ($one_result = mysqli_fetch_assoc($u_Location_List)) {
                                                if ($Location_Id == $one_result['Location_Id']) {
                                                ?>
                                                    <option value="<?php echo $one_result['Location_Id'];?>" selected><?php echo $one_result['Location']; ?></option>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <option value="<?php echo $one_result['Location_Id'];?>"><?php echo $one_result['Location']; ?></option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <?php $haha = $one_result['Location_Id']; echo "<h3>$haha</h3>"; ?>
                                    </div>
                                </div>
                                <input name="Submit" type="text" value="Update Information" hidden/>
                                <div class="forms_sub">
                                    <input class="nextBtn" id="forms_sub_input" type="button" value="Update Information" style="display:none;" onclick="delay();">
                                    <input class="nextBtn" id="nextBtn" type="button" value="Next Step"/>
                                    <!--<input id="preBtn" type="button" value="Last Step"/>-->
                                    <script>
                                        $(function() {
                                            var step= $("#myStep").step();
                                            var step_number = 0;
                                            //$("#preBtn").click(function(event) {
                                            //  var yes=step.preStep();//上一步
                                            //});
                                            $(".nextBtn").click(function(event) {
                                                step_number = step_number + 1;
                                                if(step_number == 1) {
                                                    var username_value = form1.Username.value;
                                                    var phone_value = form1.Phone.value;
                                                    var email_value = form1.Email.value;
                                                    if(username_value == "<?php echo $Username?>" && phone_value == "<?php echo $Phone?>" && email_value == "<?php echo $Email?>") {
                                                        var yes=step.nextStep();
                                                        document.getElementById("step1").style.display = "none";
                                                        document.getElementById("step2").style.display = "block";
                                                        var sub_field_arr = document.getElementsByClassName("sub_field");
                                                        for(var i=0; i<sub_field_arr.length; i++) {
                                                            sub_field_arr[i].style.display = "block";
                                                        }
                                                        document.getElementById("nextBtn").style.display = "none";
                                                        document.getElementById("forms_sub_input").style.display = "inline";
                                                    }
                                                    else {
                                                        form1.Username.style.border = "red 2px solid";
                                                        form1.Phone.style.border = "red 2px solid";
                                                        form1.Email.style.border = "red 2px solid";
                                                        step_number = 0;
                                                    }
                                                }
                                                else if(step_number == 2) {
                                                    var yes=step.nextStep();
                                                    document.getElementById("step2").style.display = "none";
                                                    document.getElementById("loading").style.display = "block";
                                                }
                                                
                                            });
                                            //$("#goBtn").click(function(event) {
                                            //        var yes=step.goStep(3);//到指定步
                                            //});
                                        });
                                    </script>
                                </div>
                            </form>
                            <script>
                                function delay() {
                                    window.setTimeout(function() {
                                        document.getElementById("form1").submit();
                                    },3000);
                                    return true;
                                }
                            </script>
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
        window.location.href="../PC_Tablet/change_info.php";
    }
});
</script>