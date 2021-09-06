<!DOCTYPE html>
<html>
<head>
    <title>Recruitment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <!---------- 菜单 ---------->
    <script src="js/modernizr.custom.js"></script>
    <script src="js/modernizr-2.6.2.min.js"></script>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNufziXZ1bnhQglddfKhWzWjfIvM3pxrA&libraries=places"></script>
    <script type="text/javascript">
        function codeAddress() {
            geocoder = new google.maps.Geocoder();
            var address = document.getElementById("Address").value;
            geocoder.geocode( { 'address': address}, function(results, status) {
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
    
    <link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/recruitment.css' type='text/css' />
    
    <!-------汽车轮播------->
    <link rel="stylesheet" href="css/edslider.css" type='text/css' />
	<link rel="stylesheet" href="css/animate-custom.css" type='text/css' />
    
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
        var $j = jQuery.noConflict();
		$j(document).ready(function(){
			//Call plugin
			$j('.mySlideshow').edslider({
				width : '100%',
				height: 300
			});
		});
	</script>
    
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
                        <input type="text" size="34" name="ServiceStore_Name" required/>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Address:</span>
                    </div>
                    <div class="forms_right">
                        <textarea id="Address" rows="3" cols="35" style="resize:none;overflow:auto;" name="Address" required></textarea>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Phone:</span>
                    </div>
                    <div class="forms_right">
                        <input type="number" name="Phone" size="34" pattern="[6,9]{1}[0-9]{7}" required />
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Email:</span>
                    </div>
                    <div class="forms_right">
                        <input type="email" name="Email" size="34" required/>
                    </div>
                    <div class="reset"></div>
                    <div class="forms_left">
                        <label for="" class="mustinput">*</label>
                        <span>Create Time:</span>
                    </div>
                    <div class="forms_right">
                        <input type="date" size="34" name="Create_Time" style="height:25px; width:200px;"/>
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
                        <textarea rows="4" cols="35" style="resize:none;overflow:auto;" name="Description" ></textarea>
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
                        Form:&nbsp;<input type="text" style="width:50px;height:20px;" name="Time1" required>&nbsp;AM.&nbsp;&nbsp;&nbsp;
                        To:&nbsp;<input type="text" style="width:50px;height:20px;" name="Time2" required>&nbsp;PM.
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
<!------------------------- Footer -------------------------------->
	<Footer class='barpanel'>
        <div class='panel_footer'>
            <div class="foot_box">
                <p class="foot_cont">
                    <a target='_blank' rel="nofollow" href="#">About Us</a>|
                    <a target='_blank' rel="nofollow" href="#">Contact Us</a>|
                    <a target='_blank' rel="nofollow" href="#">Service Station Settled</a>|
                    <a target='_blank' rel="nofollow" href="#">Suggestion</a>
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
    
<!--PC_Tablet端页面跳转-->
$(window).resize(function(){
    if(document.body.clientWidth>768){
        window.location.href="../PC_Tablet/home.php";
    }
});
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
        window.location.href="../PC_Tablet/recruitment.php";
    }
});
</script>