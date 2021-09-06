<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Detail</title>
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
	<link rel="stylesheet" href="css/animate-custom.css" type='text/css' />
    
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

                if(isset($_POST["Comment"])){
                    $sql_customer_id_search = "SELECT Customer_Id FROM comment WHERE Item_Id = $Item_Id";
                    $customer_id_search_result = mysqli_query($conn, $sql_customer_id_search);
                    while ($one_customer_id_search_result = mysqli_fetch_assoc($customer_id_search_result)) {
                        if($one_customer_id_search_result['Customer_Id'] == $Customer_Id) {
                            header("Location:maintenance_Detail.php?Item_Id=$Item_Id&&Alert=Comment");
                        }
                        else {
                            $count++;
                        }
                    }
                    if($count != 0) {
                        $Comment = $_POST["Comment"];
                        $comment_sql = "INSERT INTO comment (Customer_Id, Item_Id, Comment, Created) VALUES ($Customer_Id, $Item_Id, '$Comment', '$timestamp')";
                        $comment_sql_result = mysqli_query($conn, $comment_sql);
                        echo $comment_sql;
                        header("Location:maintenance_Detail.php?Item_Id=$Item_Id");
                    }
                    echo "hahahaha";
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
    
<!--------------------------服务框----------------------------->
   <div class="main">
        <!-- packageproduct detail left start -->
        <div class="main-left" style="position:relative;">
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
                            <div style="width:28%;float:left;">Technology:
                                <label style="color:#4A99D1;font-size: 18px;font-weight: 800;">5</label>
                                <span>/5</span>
                            </div>
                            <div style="width:21%;float:left;">Service:
                                <label style="color:#4A99D1;font-size: 18px;font-weight: 800;"><?php echo $one_comment["Item_Rating"]; ?></label>
                                <span>/5</span>
                            </div>
                            <div style="width:21%;float:left;">Store:
                                <label style="color:#4A99D1;font-size: 18px;font-weight: 800;"><?php echo $one_comment["Store_Rating"]; ?></label>
                                <span>/5</span>
                            </div>
                            <div style="width:30%;float:left;text-align: right;color:#aaa;"><?php echo $one_comment["Created"]; ?></div>
                        </div>
                        <div class="reset"></div>
                        <div style="width:100%;">
                            <div style="width:25%;float:left;margin-top:10px;text-align:center;">
                                <span><?php echo $one_comment["Customer_Name"]; ?></span>
                            </div>
                            <div style="width:75%;float: right;border-left:1px solid #ccc;margin-top:10px;">
                                <div style="width:100%;color:#888;">
                                    <div style="width:25%;float:left;text-align: right;">Car Model:&nbsp;</div>
                                    <div style="width:75%;float:left;">
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
                                    <div style="width:25%;float:left;text-align: right;">Comment:&nbsp;</div>
                                    <div style="width:75%;float:left;"><?php echo $one_comment["Comment"]; ?>
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
                    <textarea name="Comment" type="text" style="margin-top:30px;width:70%;height:50px;color:grey;padding:5px;" placeholder="Write down your comment..." required></textarea>
                </form>
            </div>
        </div>
    </div>
    <div class='reset'></div>
 
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
      <li><a href="#" ><span class="icon-picture"></span><img src="images/footer/newfooter1.png" width="30px"/></a></li>
      <li><a href="#" ><span class="icon-headphones"></span><img src="images/footer/newfooter2.png" width="30px"/></a></li>
      <li><a href="#" ><span class="icon-home"></span><img src="images/footer/newfooter3.png" width="30px"/></a></li>
      <li><a href="#" ><span class="icon-facetime-video"></span><img src="images/footer/newfooter4.png" width="30px"/></a></li>
      <li><a href="#" ><span class="icon-envelope-alt"></span><img src="images/footer/newfooter5.png" width="30px"/></a></li>
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
    
/* ================ 新闻动态 ================== */
    $(document).ready(function(){
         $("#j_Focus").Focus();
    });
    
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
        window.location.href="../PC_Tablet/maintenance_Detail.php";
    }
});
</script>