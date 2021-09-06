<!DOCTYPE html>
<html>
<head>
    <title>Servicestore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <!---------- 菜单 ---------->
    <script src="js/modernizr.custom.js"></script>
    <script src="js/modernizr-2.6.2.min.js"></script>
    
    <script type="text/javascript" src="js/packageproductc.js"></script>

    <link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/servicestore.css' type='text/css' />
    
    <!-------汽车轮播------->
    <link rel="stylesheet" href="css/edslider.css" type='text/css' />
	<link rel="stylesheet" href="css/animate-custom.css" type='text/css' />
    

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
        $search_condition = " DISTINCT(A.ServiceStore_Id), A.ServiceStore_Name, A.Address, A.Location_Id, A.Create_Time, A.Picture1, A.Description, A.Car_Model, A.Rating, A.Page_View, A.Selected_Quantities, A.Updated, A.Created FROM servicestore AS A INNER JOIN serviceitem AS B ON A.ServiceStore_Id = B.ServiceStore_Id ";
    
        if(isset($_SESSION['Car_Model'])) {
            $Car_Model = $_SESSION['Car_Model'];
            $filter = $filter." && A.Car_Model LIKE '%$Car_Model%'";
        }
        if(isset($_SESSION["Item_Id"]) && !empty($_SESSION['Item_Id'])){
            $Item_Id = $_SESSION["Item_Id"];
            $filter = $filter." && B.Item_Id = $Item_Id";
        }
        if(isset($_POST["Search_Key_Word"]) && !empty($_POST["Search_Key_Word"])){
            $Search_Key_Word = $_POST["Search_Key_Word"];
            $filter = $filter." && A.ServiceStore_Name LIKE '%$Search_Key_Word%'";
        }
        if (isset($_GET['Location_Id'])) {
            $Location_Id_selected = $_GET['Location_Id'];
            $filter = $filter." && A.Location_Id = '$Location_Id_selected'";
            $sql_servicestore = "SELECT".$search_condition.$filter;
        }
        else if (isset($_GET['Sort'])) {
            $Sort = $_GET['Sort'];
            if($Sort == "Name") {
                $sql_servicestore = "SELECT".$search_condition.$filter." ORDER BY A.ServiceStore_Name ASC";
            }
            else if($Sort == "Rating") {
                $sql_servicestore = "SELECT".$search_condition.$filter." ORDER BY A.Rating DESC";
            }
            else if($Sort == "View") {
                $sql_servicestore = "SELECT".$search_condition.$filter." ORDER BY A.Page_View DESC";
            }
            else if($Sort == "Selected") {
                $sql_servicestore = "SELECT".$search_condition.$filter." ORDER BY A.Selected_Quantities DESC";
            }
        }
        else {
            $sql_servicestore = "SELECT".$search_condition.$filter;
        }
    
        $servicestore_list = mysqli_query($conn, $sql_servicestore);
        
        $address = "";
        $search_address = mysqli_query($conn, $sql_servicestore); // search table NOW!
        while ($one_address = mysqli_fetch_assoc($search_address)) {
            $address = $address.$one_address['Address']."_split_";
        }
    
        //Location
        $sql_location = "SELECT * FROM location";
        $Location_List = mysqli_query($conn, $sql_location);
    
        //Lat, Lng
        $sql_lat_lng = "SELECT Servicestore_Id, Lat, Lng FROM servicestore";
        $Lat_Lng = mysqli_query($conn, $sql_lat_lng);
    
        if(isset($_GET['Alert'])) {
            if($_GET['Alert'] == "Difference") {
                echo "<script>alert('Please choose the correct Service or Store!')</script>";
            }
        }
    
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
        <!-- filtrate-list -->
        <!-- end 保养维修筛选区域  -->
        <div class="head-v3" id="head-v3">
            <div class="navigation-up">
                <div class="navigation-inner">
                    <div class="navigation-v3">
                        <ul>
                            <li _t_nav="categories"><h2><a href="javascript:void(0);">Location</a></h2></li>
                            <li _t_nav="sort"><h2><a href="javascript:void(0);">Sort</a></h2></li>
                            <li _t_nav="selected"><h2><a href="servicestore.php?Sort=Selected">Sort By Selected</a></h2></li>
                            <li _t_nav="Search"><h2><a href="javascript:void(0);">Search</a></h2></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="navigation-down">
                <div id="categories" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="categories">
                    <div class="navigation-down-inner">
                        <dt>Please Select:</dt>
                        <dl><dd><a class="link" href="servicestore.php?Location=All">All</a></dd></dl>
                        <?php
                            while ($one_location = mysqli_fetch_assoc($Location_List)) {
                        ?>
                            <dl><dd><a class="link" href="servicestore.php?Location_Id=<?php echo $one_location['Location_Id']; ?>"><?php echo $one_location['Location']; ?></a></dd></dl>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <div id="sort" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="sort">
                    <div class="navigation-down-inner">
                        <dt>Please Select:</dt>
                        <dl><dd><a class="link" href="servicestore.php?Sort=Name">Name</a></dd></dl>
                        <dl><dd><a class="link" href="servicestore.php?Sort=Rating">Rating</a></dd></dl>
                        <dl><dd><a class="link" href="servicestore.php?Sort=View">View</a></dd></dl>
                    </div>
                </div>
                <div id="selected" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="selected"></div>
                <div id="Search" class="nav-down-menu menu-3 menu-1" style="display: none;" _t_nav="view">
                    <div class="navigation-down-inner">
                        <form action="maintenance.php" method="post" id="Form1">
                            <dt>Please Enter (Service Item Name):</dt>
                            <input style="margin:20px;" type="text" name="Search_Key_Word">
                            <a class="semi-transparent-button with-border" onclick="Search_Key_Word()"><span>Go</span></a>
                        </form>
                        <script>
                            function Search_Key_Word() {
                                document.getElementById("Form1").submit();
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <nav>
            <?php
                while ($one_servicestore = mysqli_fetch_assoc($servicestore_list)) {
            ?>
                <div class='diyshoplist_item' id='shopidmap1081' onmouseover="show_border_map('<?php echo $one_servicestore['ServiceStore_Id'];?>')" onmouseout="show_out_all('<?php echo $one_servicestore['ServiceStore_Id'];?>')">
                    <div class="dsli_info">
                        <span class="shoplisttitle">
                            <span class="shoplisttitle_span"><?php echo $one_servicestore['ServiceStore_Id'];?></span>
                            <span style='height:25px; display:block; float:left; padding-top:5px;'>
                                <a href="#" class='orderlisttitleastyle' target="_blank"><span class='orderlisttitlestyle' ><?php echo $one_servicestore['ServiceStore_Name'];?></span></a>
                                <?php
                                    for($i=0; $i<(int)$one_servicestore['Rating']; $i++) {
                                        echo "<img style='position:relative; top:2px;' src='images/article/lan.png' width='17px'>";
                                    }
                                ?>
                            </span>
                        </span>
                        <div class="reset"></div>
                        <div style='height:90px;'>
                            <div style='float:left; margin:0px 10px; min-height:80px;'>
                                <img width="103" height="80" src="<?php echo $one_servicestore['Picture1'];?>"/> 
                                <div style='height:25px;'>
                                    <img src='images/article/wirelessnetwork.png' style="width:23px;"/>
                                    <img src='images/article/parkingspace.png' style="width:23px;"/>
                                    <img src='images/article/restarea.png' style="width:23px;"/>
                                    <img src='images/article/toilet.png' style="width:23px;"/>
                                </div>
                            </div>
                            <div class='shoptext'>
                                <div class='shopcars' >
                                    <span class='smallstyle' >Car Models:</span>
                                    <div style='width: 118px; height: 25px; overflow: hidden; float: left; white-space: nowrap; -ms-word-break: keep-all;' >
                                        <?php
                                            $Car_Model_arr = explode(",", $one_servicestore['Car_Model']);
                                            for($i=0; $i<count($Car_Model_arr); $i++) {
                                            ?>
                                                <img src='images/smallcar/<?php echo $Car_Model_arr[$i];?>.png' class='smallstyle' style='float:none;display:inline-block;'/>&nbsp;
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class='shopbettertext'>
                                    <span style='color:#4A99D2;'>Description:
                                    <span style="color:#A7A7A7;"><?php echo $one_servicestore['Description']?></span>
                                    </span>
                                </div>
                                <div  class='shopdizhi'>
                                    <span style='color:#4A99D2;'>Address:
                                    <span style="color:#A7A7A7;"><?php echo $one_servicestore['Address']?></span>
                                    </span>
                                </div>
                                <div class='shopchangebutton'>
                                    <div class='shoplistxzcar' id="<?php echo $one_servicestore['ServiceStore_Id'];?>" onclick="submit_Item_Id(<?php echo $one_servicestore['ServiceStore_Id']?>)">
                                        Select
                                    </div>
                                <script>
                                    function submit_Item_Id(id) {
                                        window.open("servicestore_Detail.php?ServiceStore_Id="+id); 
                                    }
                                </script>
                            </div>
                            </div>
                            <div class='reset'></div>
                            <div class='shopcomment' >
                                <span style='color:#4A99D2;'><?php echo $one_servicestore['Page_View'];?></span>&nbsp;Viewed&nbsp;
                                <span style='color:#4A99D2;'><?php echo $one_servicestore['Selected_Quantities'];?></span>&nbsp;Selected&nbsp;
                                <span style='color:#4A99D2;'>0</span>&nbsp;comment&nbsp;  
                            </div>
                        </div>
                        <div class='reset'></div>
                    </div>
                </div>
            <?php
                }
            ?>
            </nav>
            <!-- end 列表区 -->
        </div>
        <!-- end main_left -->
        <div class="reset"></div>
    </div>
 
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
        window.location.href="../PC_Tablet/servicestore.php";
    }
});
</script>