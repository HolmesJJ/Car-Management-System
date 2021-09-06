<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script src="js/jquery/jquery.advertisement.js"></script>
    
    <script src="js/jquery/jquery-1.10.2.min.js"></script>
    <script src="js/jquery/jquery-ui-1.10.4.min.js"></script>
    
    <script src="js/lhgdialog.js"></script>
    <script src="js/alertpriduct.js"></script>
    
    <!---------- 菜单 ---------->
    <script src="js/modernizr.custom.js"></script>
    <script src="js/modernizr-2.6.2.min.js"></script>
    
	<!-------选择车型、服务展示、广告位 ------->
    <link rel='stylesheet' href='css/alertproductc.css' type='text/css' />

    <link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/home.css' type='text/css' />
    
    <!-------汽车轮播------->
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
    
        $car_sql = "SELECT Car_Name, Car_Image FROM car";
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
    <nav class="nav30"></nav>
<!------------------------ 服务展示 ----------------------------->
    <div class="reset"></div>
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
                <div class="text-center">
                    <p class="area-title color333">Service Area</p>
                    <nav class="nav20"></nav>
                    <img id="area-map" src="images/article/singapore-map.jpg" alt="" width="480">
                </div> 
        </div>
        <div class="reset"></div>
    </section>
    <div class="reset"></div>

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
                    <li><a href="javascript:void(0);"><img src="images/article/News.png" alt="" title="" /></a></li>
                    <li><a href="javascript:void(0);"><img src="images/article/News.png" alt="" title="" /></a></li>
                    <li><a href="javascript:void(0);"><img src="images/article/News.png" alt="" title="" /></a></li>
                    <li><a href="javascript:void(0);"><img src="images/article/News.png" alt="" title="" /></a></li>
                    <li><a href="javascript:void(0);"><img src="images/article/News.png" alt="" title="" /></a></li>
                    <li><a href="javascript:void(0);"><img src="images/article/News.png" alt="" title="" /></a></li>
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
                <a href="discuss/list.html" class="discuss-more">more&gt;&gt;</a>
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
                </div>
            </div>
        </div>
        <div class="reset"></div>
        <nav class="nav30"></nav>
    </section>
    
<!------------------------ 合作伙伴 ----------------------------->
    <section id="partner">
    <nav class="nav20"></nav>
    <div class="main-content">
        <div>
            <div class="partner-title pull-left"></div>
            <a href="#" class="partner-more">more&gt;&gt;</a>
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
    <nav class="nav20"></nav>
    <section>
        <div>
            <div id="downapp_title"></div>
            <nav class="nav10"></nav>
            <p class="text-center" style="letter-spacing:15px;font-size:14px;color:#666;">以人性化的智能服务，悦动人车生活</p>
            <div id="downapp_2v">
                <div class="col-QRcode">
                    <img src="images/article/app.png" alt="Download APP" width="150px">
                </div>
                <div class="col-QRcode">
                    <img src="images/article/weixin.png" alt="Follow Us" width="150px" style="padding-bottom:2px;">
                </div>
            </div>
        </div>
        <nav class="nav"></nav>
        <nav class="nav10"></nav>
    </section>  
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
/* ================ PC_Tablet ================== */
$(window).resize(function(){
    if(document.body.clientWidth>768){
        window.location.href="../PC_Tablet/index.php";
    }
});
</script>