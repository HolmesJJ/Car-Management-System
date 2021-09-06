<!DOCTYPE html>
<html>
<head>
    <title>Management Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/home.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/management.css' type='text/css' />
    <?php
    session_start();
    if (isset($_SESSION['Administrator_Name']) && !empty($_SESSION["Administrator_Name"])) {
        
        $conn = mysqli_connect("localhost", "root", "","db160015q_project" );
    
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());
        
        $search = "A.Administrator_Id, A.Administrator_Name, A.Description, B.Last_Region, B.Last_Region_IP, A.Last_Login_Time, A.Updated, A.Created"; 

        $filter = "";
        if (isset($_POST['Administrator_Name']) && !empty($_POST["Administrator_Name"])) {
            $Administrator_Name = $_POST['Administrator_Name'];
            if(isset($_POST["Administrator_Name_Yes_No"])){
                $Administrator_Name = " LIKE '%$Administrator_Name%'";
            }
            else {
                $Administrator_Name = " NOT LIKE '%$Administrator_Name%'";
            }
            if($filter == "") {
                $filter = "A.Administrator_Name$Administrator_Name";
            }
            else {
                $filter = $filter." && A.Administrator_Name$Administrator_Name";
            } 
        }
        if (isset($_POST['Description']) && !empty($_POST["Description"])) {
            $Description = $_POST['Description'];
            if(isset($_POST["Description_Yes_No"])){
                $Description = " LIKE '%$Description%'";
            }
            else {
                $Description = " NOT LIKE '%$Description%'";
            }
            if($filter == "") {
                $filter = "A.Description$Description";
            }
            else {
                $filter = $filter." && A.Description$Description";
            }
        }
        if (isset($_POST['Last_Region']) && !empty($_POST["Last_Region"])) {
            $Last_Region = $_POST['Last_Region'];
            if(isset($_POST["Last_Region_Yes_No"])){
                $Last_Region = " LIKE '%$Last_Region%'";
            }
            else {
                $Last_Region = " NOT LIKE '%$Last_Region%'";
            }
            if($filter == "") {
                $filter = "B.Last_Region$Last_Region";
            }
            else {
                $filter = $filter." && B.Last_Region$Last_Region";
            }
        }
        if (isset($_POST['Last_Region_IP']) && !empty($_POST["Last_Region_IP"])) {
            $Last_Region_IP = $_POST['Last_Region_IP'];
            if(isset($_POST["Last_Region_IP_Yes_No"])){
                $Last_Region_IP = " LIKE '%$Last_Region_IP%'";
            }
            else {
                $Last_Region_IP = " NOT LIKE '%$Last_Region_IP%'";
            }
            if($filter == "") {
                $filter = "B.Last_Region_IP$Last_Region_IP";
            }
            else {
                $filter = $filter." && B.Last_Region_IP$Last_Region_IP";
            }
        }
        
        //准备更新
        if (isset($_POST['Admin_Id']) && !empty($_POST["Admin_Id"])) {
            $Admin_Id = $_POST['Admin_Id'];
            $filter = "A.Administrator_Id = $Admin_Id";
            $enter_sql = "SELECT ".$search." FROM administrator AS A INNER JOIN lastregion AS B ON A.Last_Region_Id = B.Last_Region_Id WHERE ".$filter;
            $show_result = mysqli_query($conn, $enter_sql);
        }
        
        //更新
        if (isset($_POST['Confirm_Admin_Id']) && isset($_POST['u_Description']) && !empty($_POST["u_Description"])) {
            $u_Description = $_POST['u_Description'];
            $Confirm_Admin_Id = $_POST['Confirm_Admin_Id'];
            $update = "A.Description='$u_Description'";
            $u_sql = "UPDATE administrator AS A INNER JOIN lastregion AS B ON A.Last_Region_Id = B.Last_Region_Id SET ".$update.", A.Updated='$timestamp' WHERE A.Administrator_Id = $Confirm_Admin_Id";
            $update_result = mysqli_query($conn, $u_sql);
            $filter = "A.Administrator_Id = $Confirm_Admin_Id";
        }
        
        if ($filter == "") {
            $filter = "1 = 1";
            $sql = "SELECT ".$search." FROM administrator AS A INNER JOIN lastregion AS B ON A.Last_Region_Id = B.Last_Region_Id WHERE ".$filter; 
        }
        else {
            $sql = "SELECT ".$search." FROM administrator AS A INNER JOIN lastregion AS B ON A.Last_Region_Id = B.Last_Region_Id WHERE ".$filter;     
        }
    
        $search_result = mysqli_query($conn, $sql); // search table NOW!
        
        $ShowField = array("Administrator_Name", "Description", "Last_Region", "Last_Region_IP", "Last_Login_Time", "Updated", "Created");

        //显示的列
        if (isset($_POST['ShowField'])) {
            $ShowField = $_POST["ShowField"];
        }
        mysqli_close($conn);
    }
    else {
        header("Location:management.php");
    }
    ?>
</head>
<body>

<!------------------------顶部信息--------------------------->
	<header id="header_top">
		<div class="main-content">
			<div>
				<div class="pull-left postion1"></div>
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
                        if(isset($_SESSION["Administrator_Name"]) && !empty($_SESSION['Administrator_Name'])) {
                            echo
                            "<div class='col-left text-right'>
						      <span>Hi, ".$_SESSION['Administrator_Name']."</span>
					       </div>   
					       <div class='col-right text-center'>|</div>
					       <div class='pull-left text-left'>    
						      <a href='logout.php'>Logout</a>
					       </div>";
                        }
                        else 
                        {
                            echo 
                          "<div class='col-left text-right'>
						   <a href='login.php' class='green'>Login</a>
					       </div>  
					       <div class='col-right text-center'>|</div>
					       <div class='pull-left text-left'>    
						      <a href='management_register.php'>Register</a>
					       </div>";
                        }
                    ?>
				</div>
			</div>
			<div class="header-hotline" name=""></div>
		</div>
		<div class="clearfixed"></div>
	</header>
    
    <header id="header_bottom">
		<div class="main-content">
			<div class="row">
                <div class="pull-left" id="Logo_box">  
				    <a href="#"><img id="Logo_size" src="images/header/logo.png" alt=""></a>    
				</div>
				<div class="pull-left" id="Title_box">
				    <a href="#"><img id="Title_size" src="images/header/title.png" alt=""></a>  
				</div>
			</div>
		</div>
	</header>
    <div class="reset"></div>
    
<!----------------------------导航栏----------------------------->
	<nav id="menu">
		<div class="main-content">
		<ul>
			<li class="pull-left text-center"><a href="management.php" class="white">HomePage</a></li>
			<li class="pull-left text-center"><a href="management_customer.php" class="white">Customer</a></li>
			<li class="pull-left text-center"><a href="management_booking.php" class="white">Booking</a></li>
			<li class="pull-left text-center"><a href="management_service.php" class="white">Services</a></li>
			<li class="pull-left text-center"><a href="management_servicestore.php" class="white">ServiceStore</a></li>
            <li class="pull-left text-center"><a href="management_management.php" class="white">Management</a></li>
		</ul>
		</div>
    </nav>
    
<!----------------------------内容----------------------------->
    <nav class='panel' style='margin-top:40px'>
        <div class='panel1_3'>
            <div class='cardwnd'>
                <div class='wnd_content'>
                    <ul class="backendwnd-nav">
                        <li><p><a href="javascript:void(0);" style="color:white;">Administrator</a></p></li>
                    </ul>                     
                    <div class='infownd'>
                        <div class='infownd-box' style='border-radius:0 0 3px 3px;'>
                            <p class='center-left-title'>Condition</p>
                            <div class='content'>
                                <ul>
                                    <li><a onclick="appear('el1')">— Administrator Name</a></li>
                                    <li><a onclick="appear('el2')">— Description</a></li>
                                    <li><a onclick="appear('el3')">— Last Region</a></li>
                                    <li><a onclick="appear('el4')">— Last Region IP</a></li>
                                </ul>
                            </div>
                            <p id="update_aside" class='center-left-title' onclick="appear('update')">Update</p>
                            <div id="update_aside_content" class='content'>
                                <ul>
                                    <li><a onclick="appear('el5')">— Description</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='panel2_3' style='padding-left:60px;'>
            <div class='mainwnd first-wnd'>
                <div class='wnd_content'>
                    <div class='infownd'>
                        <div class='infownd-box'>   
                            <form class='baction bsearchform form1' method='post' action='management_management.php' name='form1'>
                            <?php
                                if (isset($show_result) && !empty($show_result)) {
                                    if (!$show_result || mysqli_num_rows($show_result) == 0){

                                    }
                                    else {
                                        $one_show_result = mysqli_fetch_assoc($show_result); 
                                    }
                                }   
                            ?>
                                <h3 class='box-head'><label>Condition</label></h3>
                                <input name='Confirm_Admin_Id' value="<?php 
                                                                      if (isset($show_result) && !empty($show_result)) {
                                                                          echo $one_show_result['Administrator_Id'];
                                                                      }?>" type="hidden">
                                <div id="el1">
                                    <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el1')"/>
                                    <label class="labelname">Administrator Name: </label>
                                    <input class="inputstyle" type="text" name='Administrator_Name' value="<?php 
                                                                      if (isset($show_result) && !empty($show_result)) {
                                                                          echo $one_show_result['Administrator_Name'];
                                                                      }?>">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="Administrator_Name_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch1" checked>
                                        <label class="onoffswitch-label" for="myonoffswitch1">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                                <div id="el2">
                                    <img class="redcross" src="images/article/red-cross.png" style="position:relative;top:-25px;width:12px;" onclick="disappear('el2')"/>
                                    <label class="labelname" style="position:relative;top:-25px;">Description: </label>
                                    <textarea class="inputstyle" type="text" name='Description' style="margin-top:12px;height:60px;width:200px;"><?php
                                        if (isset($show_result) && !empty($show_result)) {
                                            echo $one_show_result['Description'];
                                        }?></textarea>
                                    <div class="onoffswitch" style="position:relative;top:-20px;">
                                        <input type="checkbox" name="Description_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch2" checked>
                                        <label class="onoffswitch-label" for="myonoffswitch2">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                                <div id="el3">
                                    <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el3')"/>
                                    <label class="labelname">Last Region: </label>
                                    <input class="inputstyle" type="text" name='Last_Region' value="<?php 
                                        if (isset($show_result) && !empty($show_result)) {
                                            echo $one_show_result['Last_Region'];
                                        }?>">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="Last_Region_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch3" checked>
                                        <label class="onoffswitch-label" for="myonoffswitch3">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                                <div id="el4">
                                    <img class="redcross" src="images/article/red-cross.png" style="width:12px;" onclick="disappear('el4')"/>
                                    <label class="labelname">Last Region IP: </label>
                                    <input class="inputstyle" type="text" name='Last_Region_IP' value="<?php 
                                            if (isset($show_result) && !empty($show_result)) {
                                                echo $one_show_result['Last_Region_IP'];
                                            }?>">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="Last_Region_IP_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch4" checked>
                                        <label class="onoffswitch-label" for="myonoffswitch4">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                                <h3 id="update" class='box-head' onclick="disappear('update')"><label>Update</label></h3>
                                <div id="el5">
                                    <img class="redcross" src="images/article/red-cross.png" style="position:relative;top:-25px;width:12px;" onclick="disappear('el5')"/>
                                    <label class="labelname" style="position:relative;top:-25px;">Description: </label>
                                    <textarea class="inputstyle" type="text" name='u_Description' style="height:60px;width:200px;"></textarea>
                                </div>
                                <h3 class='box-head'><label>Show Fields</label></h3>
                                <div class="checkbox_box">
                                    <div><input type="checkbox" id="checkbox-1-1" class="regular-checkbox" name="ShowField[]" value="Administrator_Name" checked/><label for="checkbox-1-1"></label><span>Administrator Name&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-2" class="regular-checkbox" name="ShowField[]" value="Description"/ checked><label for="checkbox-1-2"></label><span>Description&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-3" class="regular-checkbox" name="ShowField[]" value="Last_Region"/ checked><label for="checkbox-1-3"></label><span>Last Region&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-4" class="regular-checkbox" name="ShowField[]" value="Last_Region_IP"/ checked><label for="checkbox-1-4"></label><span>Last Region IP&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-5" class="regular-checkbox" name="ShowField[]" value="Last_Login_Time"/ checked><label for="checkbox-1-5"></label><span>Last Login Time&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-6" class="regular-checkbox" name="ShowField[]" value="Updated" checked/><label for="checkbox-1-6"></label><span>Updated&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-7" class="regular-checkbox" name="ShowField[]" value="Created" checked/><label for="checkbox-1-7"></label><span>Created&nbsp;&nbsp;</span></div>
                                </div>
                                <div class="forms_sub">
                                    <input type="submit" value="Submit application">
                                </div>
                            </form>
                            <div class='reset'></div>
                            <?php
                            if (!$search_result || mysqli_num_rows($search_result) == 0){
                                //echo $_POST['Description'];
                            }
                            else {
                            ?>
                            <form method="post" action="management_management.php" id="Update_Form">
                                <table width='100%' class='cleanlily_table' id='orderlist_listtable'>    
                                    <tr class='tb-title'>
                                        <?php if(count($ShowField)>0){
                                            ?>
                                            <td width='65px' style='color:white;background:#24303c'>Edit</td>
                                            <?php
                                        }
                                        for ($i=0; $i<count($ShowField); $i++) {
                                        ?>
                                            <td width='65px' style='color:white;background:#24303c'><?php echo $ShowField[$i]; ?></td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                    while ($one_result = mysqli_fetch_assoc($search_result)) {
                                    ?> 
                                    <tr>
                                        <?php if(count($ShowField)>0){
                                        ?>
                                        <form method="post" action="management_management.php">
                                            <td style="text-align:center;"><input type="submit" class="myButton" id="Circle_btn" value="Update&nbsp;&gt;&gt;"><input style="width:1px;" type="text" name="Admin_Id" value="<?php echo $one_result["Administrator_Id"]; ?>" hidden/></td>
                                            <?php
                                            }
                                            for ($i=0; $i<count($ShowField); $i++) {
                                            ?>
                                            <td style="text-align:center;"><?php echo $one_result["$ShowField[$i]"]; ?></td>
                                        </form>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                <?php
                                }
                                ?>
                                </table>
                                <?php
                                }
                                ?>
                                <div class='reset'></div>
                            </form>
                        </div>
                    </div>
                </div>            
            </div> 
            <div class='reset'></div>
        </div>
    </nav>

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

/* ================ management ================== */
    function disappear(el) {
        if(el == "el1") {
            document.getElementById("el1").style.display="none";
        }
        else if(el == "el2") {
            document.getElementById("el2").style.display="none";
        }
        else if(el == "el3") {
            document.getElementById("el3").style.display="none";
        }
        else if(el == "el4") {
            document.getElementById("el4").style.display="none";
        }
        else if(el == "el5") {
            document.getElementById("update").style.display="none";
            document.getElementById("el5").style.display="none";
        }
        else if(el == "update") {
            document.getElementById("update").style.display="none";
            document.getElementById("el5").style.display="none";
        }
    }
    function appear(el) {
        if(el == "el1") {
            document.getElementById("el1").style.display="block";
        }
        else if(el == "el2") {
            document.getElementById("el2").style.display="block";
        }
        else if(el == "el3") {
            document.getElementById("el3").style.display="block";
        }
        else if(el == "el4") {
            document.getElementById("el4").style.display="block";
        }
        else if(el == "el5") {
            document.getElementById("update").style.display="block";
            document.getElementById("el5").style.display="block";
        }
        else if(el == "update") {
            document.getElementById("update").style.display="block";
            document.getElementById("el5").style.display="block";
        }
    }
</script>