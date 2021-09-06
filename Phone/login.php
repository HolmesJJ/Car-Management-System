<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <!---------- 菜单 ---------->
    <script src="js/modernizr.custom.js"></script>
    <script src="js/modernizr-2.6.2.min.js"></script>

    <link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/advertisement.css' type='text/css' />
    
    <!-------汽车轮播------->
    <link rel="stylesheet" href="css/edslider.css" type='text/css' />
	<link rel="stylesheet" href="css/animate-custom.css" type='text/css' />
    
    <!------- Register_Login ------->
    <link rel='stylesheet' href='css/register_login.css' type='text/css' />
    
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
    
        if(isset($_GET['Alert'])) {
            if($_GET['Alert'] == "Login") {
                echo "<script>alert('Please login your account first!')</script>";
            }
        }
        if(isset($_GET['Login'])) {
            if($_GET['Login'] == "fail") {
                echo "<script>alert('Login failed! Please check your information')</script>";
            }
            else if($_GET['Login'] == "admin") {
                echo "<script>alert('Admin Login failed! Please use your PC to login.')</script>";
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

<!----------------------------登陆框----------------------------->
	<article style='margin-top:47px'>
        <div class='panel2_3'>
            <div class='cardwnd'>
                <div class='wnd_content'>
                    <div id="userlogin">
                        <div class="log-title">
                            <a href="#" id='log-title-select' onclick="javascript:$('#log-1').hide();$('#reg-1').show();$(this).attr('id','log-title-select');$(this).next().attr('id','');">Normal Login</a>
                            <a href="#" onclick="javascript:$('#reg-1').hide();$('#log-1').show();$(this).attr('id','log-title-select');$(this).prev().attr('id','');">Mobile Login</a>
                        </div>
                        <div class="log-box">
                            <div class="nav20"></div>
                            <div class="log-content" id="reg-1">
                                <div class="log-content-left">
                                    <form name="LoginForm" action='check_login.php' method='post' onsubmit="return checkform();">
                                        <fieldset>
				                            <div class="pull-left">
				                                <span class="pull-left username"></span>
				                            </div>
				                            <input size="29" type=text name="Username" id="client_username" placeholder="Username / Phone / Email" required="required" maxlength="50"/>
				                        </fieldset>
				                        <div class="reset"></div>
				                        <fieldset>
				                            <div class="pull-left">
				                                <span class="pull-left password"></span>
				                            </div>
				                            <input size="29" type='password' name="Password" value='' id="client_password" placeholder="Password" required="required" maxlength="50"/>
				                        </fieldset>
				                        <div class="reset"></div>
				                        <fieldset>
				                            <div class="pull-left">
				                                <span class="pull-left captcha"></span>
				                            </div>
				                            <input size="29" type=text id="code_input" value="" placeholder="Please enter verification code" required="required"/>
                                            <div id="v_container" style="position:relative;left:360px;top:-45px;width:150px;height:40px;"></div>
                                            <script src="js/gVerify.js"></script>  
                                        </fieldset>
				                        <div class="reset"></div>
				                        <fieldset class='form-actions-1' style="border:0;">
					                       <input type=submit value='Login' class="pull-left" style="color:white;"/>
				                        </fieldset>
                                        <a href='register.php'><span class="turn-reg" >No account, register now</span></a>
				                        <div class="reset"></div>
                                        <?php 
                                            if(isset($_GET['login'])) {
                                            echo "<fieldset style='border:0;text-align:center;font-size:20px;color:red;'>Login Failed! Please login again!</fieldset>";
                                        }
                                        ?>
                                        
                                        <div class="reset"></div>
			                         </form>
		                          </div>
                                <div class="log-content-right">
                                    <img src="images/article/carrepairposter.jpg" alt="">
                                </div>
                                <div class="reset"></div>
                            </div>
                            
	                       <!------- 手机登录 ------->
	                       <div class="log-content"  id='log-1' style='display: none;'>
                               <div class="log-content-left"  >
                                   <form action='#' method='post'>
                                        <fieldset>
                                            <div class="pull-left">
                                                <span class="pull-left mobile"></span>
                                            </div>
					                        <input size="29" type=text placeholder="Phone Number"/>
				                        </fieldset>
				                        <div class="reset"></div>
				                        <fieldset style="border: 0;">
					                       <a id='butt'>&nbsp;Get verification code</a>
					                       <div id='loginform_mobile_result'></div>
				                        </fieldset>
				                        <div class="reset"></div>
				                        <fieldset>
					                       <div class="pull-left">
						                      <span class="pull-left passcode"></span>
					                       </div>
					                       <input size="29" type=text id='client_passcode' placeholder="Verification code"/> 
                                       </fieldset>
				                        <div class="reset"></div>
				                        <fieldset class='form-actions-1' style="border:0;color:white;">
					                       <input type=submit value='Login' class="pull-left" style="color:white;"/>
				                        </fieldset>
                                        <a href='register.php'><span class="turn-reg">No account, register now</span></a>
				                        <div class="reset"></div>
			                         </form>
		                          </div>
		                      <div class="log-content-right">
			                     <img src="images/article/carrepairposter.jpg" alt="">
		                      </div>
		                      <div class="reset"></div>
	                       </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
    <div class="reset"></div>
   
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

/* ================ PC_Tablet ================== */
$(window).resize(function(){
    if(document.body.clientWidth>768){
        window.location.href="../PC_Tablet/login.php";
    }
});
</script>