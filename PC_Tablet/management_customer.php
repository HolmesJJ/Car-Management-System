<!DOCTYPE html>
<html>
<head>
    <title>Management Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel='stylesheet' href='css/gobal.css' type='text/css' />
    <link rel='stylesheet' href='css/main.css' type='text/css' />
    <link rel='stylesheet' href='css/management.css' type='text/css' />
    
    <?php
    session_start();
    if (isset($_SESSION['Administrator_Name']) && !empty($_SESSION["Administrator_Name"])) {
        $conn = mysqli_connect("localhost", "root", "","db160015q_project" );
    
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());
        
        $Search = "A.Customer_Id, A.Customer_Name, A.Username, A.Customer_Picture, A.Password, A.Gender, A.Phone, A.Address, A.Email, A.License_No, B.Location, C.Last_Region, C.Last_Region_IP, A.Last_Login_Time, A.Updated, A.Created";

        $filter = "";

        //Search
        if (isset($_POST['Customer_Name']) && !empty($_POST["Customer_Name"])) {
            $Customer_Name = $_POST['Customer_Name'];
            if(isset($_POST["Customer_Name_Yes_No"])){
                $Customer_Name = " LIKE '%$Customer_Name%'";
            }
            else {
                $Customer_Name = " NOT LIKE '%$Customer_Name%'";
            }
            if($filter == "") {
                $filter = "A.Customer_Name$Customer_Name";
            }
            else {
                $filter = $filter." && A.Customer_Name$Customer_Name";
            }  
        }
        if (isset($_POST['Username']) && !empty($_POST["Username"])) {
            $Username = $_POST['Username'];
            if(isset($_POST["Username_Yes_No"])){
                $Username = " LIKE '%$Username%'";
            }
            else {
                $Username = " NOT LIKE '%$Username%'";
            }
            if($filter == "") {
                $filter = "A.Username$Username";
            }
            else {
                $filter = $filter." && A.Username$Username";
            }  
        }
        if (isset($_POST['Gender']) && !empty($_POST["Gender"])) {
            $Gender = $_POST['Gender'];
            if($Gender == "all") {

            }
            else {
                if($filter == "") {
                    $filter = "A.Gender='$Gender'";
                }
                else {
                    $filter = $filter." && A.Gender='$Gender'";
                }               
            }
        }
        if (isset($_POST['Phone']) && !empty($_POST["Phone"])) {
            $Phone = $_POST['Phone'];
            if(isset($_POST["Phone_Yes_No"])){
                $Phone = " LIKE '%$Phone%'";
            }
            else {
                $Phone = " NOT LIKE '%$Phone%'";
            }
            if($filter == "") {
                $filter = "A.Phone$Phone";
            }
            else {
                $filter = $filter." && A.Phone$Phone";
            }  
        }
        if (isset($_POST['Address']) && !empty($_POST["Address"])) {
            $Address = $_POST['Address'];
            if(isset($_POST["Address_Yes_No"])){
                $Address = " LIKE '%$Address%'";
            }
            else {
                $Address = " NOT LIKE '%$Address%'";
            }
            if($filter == "") {
                $filter = "A.Address$Address";
            }
            else {
                $filter = $filter." && A.Address$AddressAddress";
            }  
        }
        if (isset($_POST['Email']) && !empty($_POST["Email"])) {
            $Email = $_POST['Email'];
            if(isset($_POST["Email_Yes_No"])){
                $Email = " LIKE '%$Email%'";
            }
            else {
                $Email = " NOT LIKE '%$Email%'";
            }
            if($filter == "") {
                $filter = "A.Email$Email";
            }
            else {
                $filter = $filter." && A.Email$Email";
            }  
        }
        if (isset($_POST['License_No']) && !empty($_POST["License_No"])) {
            $License_No = $_POST['License_No'];
            if(isset($_POST["License_No_Yes_No"])){
                $License_No = " LIKE '%$License_No%'";
            }
            else {
                $License_No = " NOT LIKE '%$License_No%'";
            }
            if($filter == "") {
                $filter = "A.License_No$License_No";
            }
            else {
                $filter = $filter." && A.License_No$License_No";
            }  
        }
        if (isset($_POST['Location']) && !empty($_POST["Location"])) {
            $Location = $_POST['Location'];
            if($Location == "all") {

            }
            else {
                if($filter == "") {
                    $filter = "A.Location_Id=$Location";
                }
                else {
                    $filter = $filter." && A.Location_Id=$Location";
                }               
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
                $filter = "A.Last_Region$Last_Region";
            }
            else {
                $filter = $filter." && A.Last_Region$Last_Region";
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
                $filter = "A.Last_Region_IP$Last_Region_IP";
            }
            else {
                $filter = $filter." && A.Last_Region_IP$Last_Region_IP";
            }  
        }
        
        //准备更新
        if (isset($_POST['u_Customer_Id']) && !empty($_POST["u_Customer_Id"])) {
            $u_Customer_Id = $_POST['u_Customer_Id'];
            $enter_sql = "SELECT ".$Search." FROM customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id WHERE A.Customer_Id = $u_Customer_Id";
            $show_result = mysqli_query($conn, $enter_sql);
        }
        
        //直接删除
        if (isset($_POST['d_Customer_Id']) && !empty($_POST["d_Customer_Id"])) {
            $d_Customer_Id = $_POST['d_Customer_Id'];
            $d_sql = "DELETE A FROM customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id WHERE A.Customer_Id = $d_Customer_Id";
            $delete_result = mysqli_query($conn, $d_sql); 
        }
            
        if (isset($_POST['Submit'])) {
            $Submit = $_POST['Submit'];
            //Search
            if (isset($_POST['Confirm_Customer_Id']) && $Submit == "Update Information") {
                //更新
                $Confirm_Customer_Id = $_POST['Confirm_Customer_Id'];
                if (isset($_POST['u_Customer_Name']) && !empty($_POST["u_Customer_Name"])) {
                    $u_Customer_Name = $_POST['u_Customer_Name'];
                    $update = "A.Customer_Name='$u_Customer_Name'";
                    $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Customer_Id = $Confirm_Customer_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_Username']) && !empty($_POST["u_Username"])) {
                    $u_Username = $_POST['u_Username'];
                    $update = "A.Username=$u_Username";
                    $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Customer_Id = $Confirm_Customer_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['Customer_Name']) && !empty($_POST["Customer_Name"]) && !empty($_FILES['u_Customer_Picture']['tmp_name'])) {
                    $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

                    if (in_array($_FILES["u_Customer_Picture"]["type"], $allowedType)) {    
                        if ($_FILES["u_Customer_Picture"]["size"] < 5000000) { 
                            $extension = pathinfo($_FILES['u_Customer_Picture']['name'], PATHINFO_EXTENSION);
                            $target = "images/source/Customer/".$_POST['Customer_Name'].".".$extension;
                            $filename = "images/source/Customer/".$_POST['Customer_Name'].".".$extension;

                            $result = move_uploaded_file($_FILES["u_Customer_Picture"]["tmp_name"], $filename);

                            if($result) {
                                $update = "A.Customer_Picture=$target";
                                $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Customer_Id = $Confirm_Customer_Id";
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
                if (isset($_POST['u_Gender']) && !empty($_POST["u_Gender"])) {
                    $u_Gender = $_POST['u_Gender'];
                    if ($u_Gender != "nil") {
                        $update = "A.Gender='$u_Gender'";
                        $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Customer_Id = $Confirm_Customer_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                }
                if (isset($_POST['u_Phone']) && !empty($_POST["u_Phone"])) {
                    $u_Phone = $_POST['u_Phone'];
                    $update = "A.Phone=$u_Phone";
                    $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Customer_Id = $Confirm_Customer_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_Address']) && !empty($_POST["u_Address"])) {
                    $u_Address = $_POST['u_Address'];
                    $update = "A.Address=$u_Address";
                    $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Customer_Id = $Confirm_Customer_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_Email']) && !empty($_POST["u_Email"])) {
                    $u_Email = $_POST['u_Email'];
                    $update = "A.Email=$u_Email";
                    $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Customer_Id = $Confirm_Customer_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_License_No']) && !empty($_POST["u_License_No"])) {
                    $u_License_No = $_POST['u_License_No'];
                    $update = "A.License_No=$u_License_No";
                    $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Customer_Id = $Confirm_Customer_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_Location']) && !empty($_POST["u_Location"])) {
                    $u_Location = $_POST['u_Location'];
                    if ($u_Status != "nil") {
                        $update = "A.Location_Id='$u_Location'";
                        $u_sql = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET ".$update." WHERE A.Customer_Id = $Confirm_Customer_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                }
                $ut = "UPDATE customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id SET A.Updated='$timestamp' WHERE A.Customer_Id = $Confirm_Customer_Id";
                $update_time = mysqli_query($conn, $ut);
                $filter = "A.Customer_Id = $Confirm_Customer_Id";
            }
            else if ($Submit == "Insert Information") {
                //插入
                if (isset($_POST['i_Customer_Name']) && !empty($_POST["i_Customer_Name"]) && isset($_POST['i_Username']) && !empty($_POST["i_Username"]) && !empty($_FILES['i_Customer_Picture']['tmp_name']) && isset($_POST['i_Gender']) && !empty($_POST["i_Gender"]) && isset($_POST['i_Phone']) && !empty($_POST["i_Phone"]) && isset($_POST['i_Address']) && !empty($_POST["i_Address"]) && isset($_POST['i_Email']) && !empty($_POST["i_Email"]) && isset($_POST['i_License_No']) && !empty($_POST["i_License_No"]) && isset($_POST['i_Location']) && !empty($_POST["i_Location"])) {
                    $i_Customer_Name = $_POST['i_Customer_Name'];
                    $i_Username = $_POST['i_Username'];
                    $i_Gender = $_POST['i_Gender'];
                    $i_Phone = $_POST['i_Phone'];
                    $i_Address = $_POST['i_Address'];
                    $i_Email = $_POST['i_Email'];
                    $i_License_No = $_POST['i_License_No'];
                    $i_Location = $_POST['i_Location'];
                    
                    $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

                    if (in_array($_FILES["i_Customer_Picture"]["type"], $allowedType)) {    
                        if ($_FILES["i_Customer_Picture"]["size"] < 5000000) { 
                            $extension = pathinfo($_FILES['i_Customer_Picture']['name'], PATHINFO_EXTENSION);
                            $target = "images/source/Customer/".$i_Customer_Name.".".$extension;
                            $filename = "images/source/Customer/".$i_Customer_Name.".".$extension;

                            $result = move_uploaded_file($_FILES["i_Customer_Picture"]["tmp_name"], $filename);
                            if($result) {
                                $i_sql = "INSERT INTO customer (Customer_Name, Username, Customer_Picture, Password, Gender, Phone, Address, Email, License_No, Location_Id, Last_Region_Id, Last_Login_Time, Updated, Created) VALUES ('$i_Customer_Name', '$i_Username', '$target', 123456, '$i_Gender', $i_Phone, '$i_Address', '$i_Email', '$i_License_No', '$i_Location', 1, '$timestamp', '$timestamp', '$timestamp')";

                                $result = mysqli_query($conn, $i_sql);
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
            }
        }

        if ($filter == "") {
            $filter = "1 = 1";
        } 
        $sql = "SELECT ".$Search." FROM customer AS A INNER JOIN location AS B ON A.Location_Id = B.Location_Id INNER JOIN lastregion AS C ON A.Last_Region_Id = C.Last_Region_Id WHERE ".$filter;
        $search_result = mysqli_query($conn, $sql);
    
        //Status
        $sql_location = "SELECT * FROM location";
        $Location_List = mysqli_query($conn, $sql_location);
        $u_Location_List = mysqli_query($conn, $sql_location);
        $i_Location_List = mysqli_query($conn, $sql_location);
    
        //Category
        $sql_lastregion = "SELECT * FROM lastregion";
        $Last_Region_List = mysqli_query($conn, $sql_lastregion);
        $u_Last_Region_List = mysqli_query($conn, $sql_lastregion);
        $i_Last_Region_List = mysqli_query($conn, $sql_lastregion);

        $ShowField = array("Customer_Name", "Username", "Customer_Picture", "Gender", "Phone", "Address", "Email", "License_No");
        
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
<body <?php 
      if(isset($_POST['u_Customer_Id']) && !empty($_POST["u_Customer_Id"])) { 
          echo "onload=\"appear('update')\"";
      }?>>
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
			<li class="pull-left text-center"><a href="index.php" class="white">HomePage</a></li>
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
                        <li><p><a style="color:white" href="javascript:void(0);">Service Items</a></p></li>
                    </ul>                     
                    <div class='infownd'>
                        <div class='infownd-box' style='border-radius:0 0 3px 3px;'>
                            <p class='center-left-title' onclick="appear('search')">Condition</p>
                            <div class='content'>
                                <ul>
                                    <li><a onclick="appear('el1')">— Customer Name</a></li>
                                    <li><a onclick="appear('el2')">— Username</a></li>
                                    <li><a onclick="appear('el3')">— Gender</a></li>
                                    <li><a onclick="appear('el4')">— Phone</a></li>
                                    <li><a onclick="appear('el5')">— Address</a></li>
                                    <li><a onclick="appear('el6')">— Email</a></li>
                                    <li><a onclick="appear('el7')">— License No</a></li>
                                    <li><a onclick="appear('el8')">— Location</a></li>
                                    <li><a onclick="appear('el9')">— Last Region</a></li>
                                    <li><a onclick="appear('el10')">— Last Region IP</a></li>
                                </ul>
                            </div>
                            <p class='center-left-title' onclick="appear('update')">Update</p>
                            <div class='content'>
                                <ul>
                                    <li><a onclick="appear('el11')">— Customer Name</a></li>
                                    <li><a onclick="appear('el12')">— Username</a></li>
                                    <li><a onclick="appear('el13')">— Gender</a></li>
                                    <li><a onclick="appear('el14')">— Phone</a></li>
                                    <li><a onclick="appear('el15')">— Address</a></li>
                                    <li><a onclick="appear('el16')">— Email</a></li>
                                    <li><a onclick="appear('el17')">— License No</a></li>
                                </ul>
                            </div>
                            <p class='center-left-title' onclick="appear('insert')">Insert</p>
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
                            <form class='baction bsearchform form1' enctype="multipart/form-data" method='post' action='management_customer.php' name='form1'>
                            <?php 
                                if (isset($show_result) && !empty($show_result)) {
                                    if (!$show_result || mysqli_num_rows($show_result) == 0){

                                    }
                                    else {
                                        $one_show_result = mysqli_fetch_assoc($show_result); 
                                    }
                                }
                            ?>
                                <div id="Sreach_Update_By_Field">
                                    <h3 class='box-head'><label>Condition</label></h3>
                                    <input name='Confirm_Customer_Id' value="<?php 
                                                                             if (isset($show_result) && !empty($show_result)) {
                                                                                 echo $one_show_result['Customer_Id'];
                                                                             }?>" type="hidden">
                                    <div id="el1">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el1')"/>
                                        <label class="labelname">Customer Name: </label>
                                        <input class="inputstyle" type="text" name='Customer_Name' value="<?php
                                                                                                        if (isset($show_result) && !empty($show_result)) {
                                                                                                            echo $one_show_result['Customer_Name'];
                                                                                                        }?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Customer_Name_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch1" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch1">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el2">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el2')"/>
                                        <label class="labelname">Username: </label>
                                        <input class="inputstyle" type="text" name='Username' value="<?php
                                                                                                    if (isset($show_result) && !empty($show_result)) {
                                                                                                            echo $one_show_result['Username'];
                                                                                                    }?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Username_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch2" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch2">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el3">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el3')"/>
                                        <label class="labelname">Gender: </label>
                                        <select class="dropdown" type="text" name="Gender">
                                            <option value="all">All</option>
                                            <?php
                                            if (isset($show_result) && !empty($show_result)) {
                                                if($one_show_result['Gender'] == "Male") {
                                                ?>
                                                    <option value="Male" selected>Male</option>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <option value="Male">Male</option>
                                                <?php
                                                }
                                                if($one_show_result['Gender'] == "Female") {
                                                ?>
                                                    <option value="Female" selected>Female</option>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <option value="Female">Female</option>
                                                <?php
                                                }
                                            }
                                            else {
                                            ?> 
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            <?php       
                                            } 
                                            ?>
                                        </select>
                                    </div>
                                    <div id="el4">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el4')"/>
                                        <label class="labelname">Phone: </label>
                                        <input class="inputstyle" type="text" name='Phone' value="<?php 
                                                                                                    if (isset($show_result) && !empty($show_result)) {
                                                                                                        echo $one_show_result['Phone'];
                                                                                                    }?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Phone_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch3" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch3">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el5">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;top:-25px;width:12px;" onclick="disappear('el5')"/>
                                        <label class="labelname" style="position:relative;top:-25px;">Address: </label>
                                        <textarea class="inputstyle" type="text" name='Address' style="margin-top:12px;height:60px;width:200px;"><?php 
                                            if (isset($show_result) && !empty($show_result)) {
                                                echo $one_show_result['Address'];
                                            }?>
                                        </textarea>
                                        <div class="onoffswitch" style="position:relative;top:-20px;">
                                            <input type="checkbox" name="Address_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch4" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch4">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el6">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el6')"/>
                                        <label class="labelname">Email: </label>
                                        <input class="inputstyle" type="text" name='Email' value="<?php 
                                                                                                if (isset($show_result) && !empty($show_result)) {
                                                                                                    echo $one_show_result['Email'];
                                                                                                }?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Email_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch5" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch5">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el7">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el7')"/>
                                        <label class="labelname">License No: </label>
                                        <input class="inputstyle" type="text" name='License_No' value="<?php 
                                                                                                       if (isset($show_result) && !empty($show_result)) {
                                                                                                            echo $one_show_result['License_No'];
                                                                                                       }?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="License_No_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch6" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch6">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el8">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el8')"/>
                                        <label class="labelname">Location: </label>
                                        <select class="dropdown" type="text" name="Location">
                                            <option value="all">All</option>
                                            <?php
                                            
                                            while ($one_result = mysqli_fetch_assoc($Location_List)) {
                                                if (isset($show_result) && !empty($show_result)) {
                                                    if($one_result['Location'] == $one_show_result['Location']) {
                                                    ?>
                                                        <option value="<?php echo $one_result['Location_Id']; ?>" selected><?php echo $one_result['Location'];?></option>
                                                    <?php
                                                    }
                                                    else {
                                                    ?>
                                                        <option value="<?php echo $one_result['Location_Id']; ?>"><?php echo $one_result['Location'];?></option>
                                                    <?php
                                                    }
                                                }
                                                else {
                                                ?>
                                                    <option value="<?php echo $one_result['Location_Id']; ?>"><?php echo $one_result['Location'];?></option>
                                                <?php
                                                }
                                            }  
                                            ?>
                                        </select>
                                    </div>
                                    <div id="el9">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;top:-25px;width:12px;" onclick="disappear('el9')"/>
                                        <label class="labelname" style="position:relative;top:-25px;">Last Region: </label>
                                        <textarea class="inputstyle" type="text" name='Last_Region' style="margin-top:12px;height:60px;width:200px;"><?php 
                                            if (isset($show_result) && !empty($show_result)) {
                                                echo $one_show_result['Last_Region'];
                                            }?></textarea>
                                        <div class="onoffswitch" style="position:relative;top:-20px;">
                                            <input type="checkbox" name="Last_Region_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch7" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch7">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el10">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el10')"/>
                                        <label class="labelname">Last Region IP: </label>
                                        <input class="inputstyle" type="text" name='Last_Region_IP' value="<?php 
                                                                                                        if (isset($show_result) && !empty($show_result)) {
                                                                                                            echo $one_show_result['Last_Region_IP'];
                                                                                                        }?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Last_Region_IP_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch8" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch8">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="Update_Field" style="display:none;">
                                    <h3 class='box-head'><label>Update</label></h3>
                                    <div id="el11">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el11')"/>
                                        <label class="labelname">Customer Name: </label>
                                        <input class="inputstyle" type="text" name='u_Customer_Name'>
                                    </div>
                                    <div id="el12">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el12')"/>
                                        <label class="labelname">Username: </label>
                                        <input class="inputstyle" type="text" name='u_Username'>
                                    </div>
                                    <div id="el13">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;top:-10px;" onclick="disappear('el13')"/>
                                        <label style="position:relative;top:-10px;" class="labelname">Customer Picture: </label>
                                        <label style="position:relative;top:8px;" id="head_icon1" for="head_icon_input1">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input1" type="file" name='u_Customer_Picture' hidden="hidden" onchange="Picture_Show1(this);">
                                        <span style="position:relative;top:-10px;left:10px;">(Please enter the Customer Name for updating)</span>
                                    </div>
                                    <div id="el14">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el14')"/>
                                        <label class="labelname">Gender: </label>
                                        <select class="dropdown" type="text" name="u_Gender">
                                            <option value="nil">-----</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div id="el15">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el15')"/>
                                        <label class="labelname">Phone: </label>
                                        <input class="inputstyle" type="number" name='u_Phone'>
                                    </div>
                                    <div id="el16">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;top:-24px;width:12px;" onclick="disappear('el16')"/>
                                        <label class="labelname" style="position:relative;top:-25px;">Address: </label>
                                        <textarea class="inputstyle" type="text" name='u_Address' style="height:60px;width:200px;margin-top:10px;"></textarea>
                                    </div>
                                    <div id="el17">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el17')"/>
                                        <label class="labelname">Email: </label>
                                        <input class="inputstyle" type="email" name='u_Email'>
                                    </div>
                                    <div id="el18">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el18')"/>
                                        <label class="labelname">License No: </label>
                                        <input class="inputstyle" type="text" name='u_License_No'>
                                    </div>
                                    <div id="el19">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el19')"/>
                                        <label class="labelname">Location: </label>
                                        <select class="dropdown" type="text" name="u_Location">
                                            <option value="nil">-----</option>
                                        <?php
                                            WHILE ($one_result = mysqli_fetch_assoc($u_Location_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['Location_Id']; ?>"><?php echo $one_result['Location'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="Insert_Field" style="display:none;">
                                    <h3 class='box-head'><label>Insert</label></h3>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Customer Name: </label>
                                        <input class="inputstyle" type="text" name='i_Customer_Name'>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Username: </label>
                                        <input class="inputstyle" type="text" name='i_Username'>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:-9px;width:12px;"/>
                                        <label style="position:relative;top:-10px;" class="labelname">Customer Picture: </label>
                                        <label style="position:relative;top:8px;" id="head_icon2" for="head_icon_input2">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input2" type="file" name='i_Customer_Picture' hidden="hidden" onchange="Picture_Show2(this);">
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Gender: </label>
                                        <select class="dropdown" type="text" name="i_Gender">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Phone: </label>
                                        <input class="inputstyle" type="text" name='i_Phone'>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:-24px;width:12px;"/>
                                        <label class="labelname" style="position:relative;top:-25px;">Address: </label>
                                        <textarea class="inputstyle" type="text" name='i_Address' style="height:60px;width:200px;margin-top:10px;"></textarea>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Email: </label>
                                        <input class="inputstyle" type="Email" name='i_Email'>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">License No: </label>
                                        <input class="inputstyle" type="text" name='i_License_No'>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Location:</label>
                                        <select class="dropdown" type="text" name="i_Location">
                                        <?php
                                            while ($one_result = mysqli_fetch_assoc($i_Location_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['Location_Id']; ?>"><?php echo $one_result['Location'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <h3 class='box-head'><label>Show Fields</label></h3>
                                <div class="checkbox_box">
                                    <div><input type="checkbox" id="checkbox-1-1" class="regular-checkbox" name="ShowField[]" value="Customer_Name" checked/><label for="checkbox-1-1"></label><span>Customer Name&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-2" class="regular-checkbox" name="ShowField[]" value="Username" checked/><label for="checkbox-1-2"></label><span>Username&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-3" class="regular-checkbox" name="ShowField[]" value="Customer_Picture" checked/><label for="checkbox-1-3"></label><span>Customer Picture&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-4" class="regular-checkbox" name="ShowField[]" value="Gender" checked/><label for="checkbox-1-4"></label><span>Gender&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-5" class="regular-checkbox" name="ShowField[]" value="Phone" checked/><label for="checkbox-1-5"></label><span>Phone&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-6" class="regular-checkbox" name="ShowField[]" value="Address" checked/><label for="checkbox-1-6"></label><span>Address&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-7" class="regular-checkbox" name="ShowField[]" value="Email" checked/><label for="checkbox-1-7"></label><span>Email&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-8" class="regular-checkbox" name="ShowField[]" value="License_No" checked/><label for="checkbox-1-8"></label><span>License No&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-9" class="regular-checkbox" name="ShowField[]" value="Location"/><label for="checkbox-1-9"></label><span>Location&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-10" class="regular-checkbox" name="ShowField[]" value="Last_Region"/><label for="checkbox-1-10"></label><span>Last Region&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-11" class="regular-checkbox" name="ShowField[]" value="Last_Region_IP"/><label for="checkbox-1-11"></label><span>Last Region IP&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-12" class="regular-checkbox" name="ShowField[]" value="Updated"/><label for="checkbox-1-12"></label><span>Updated&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-13" class="regular-checkbox" name="ShowField[]" value="Created"/><label for="checkbox-1-13"></label><span>Created&nbsp;&nbsp;</span></div>
                                </div>
                                <div class="forms_sub">
                                    <input id="forms_sub_input" type="submit" value="Search Information" name="Submit">
                                </div>
                            </form>
                                <div class='reset'></div>
                                    <?php
                                    if (!$search_result || mysqli_num_rows($search_result) == 0){
                                    }
                                    else {
                                        if(count($ShowField)>12){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-370px;z-index:100;">
                                        <?php
                                        }   
                                        else if(count($ShowField)>11){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-320px;z-index:100;">
                                        <?php
                                        }
                                        else if(count($ShowField)>10){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-260px;z-index:100;">
                                        <?php
                                        }
                                        else if(count($ShowField)>9){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-150px;z-index:100;">
                                        <?php
                                        }
                                        else if(count($ShowField)>8){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-80px;z-index:100;">
                                        <?php
                                        }
                                        else {
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:0px;z-index:100;">
                                        <?php
                                        }
                                        ?>
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
                                        <?php if(count($ShowField)>0){
                                        ?>
                                            <tr>
                                            <td>
                                                <form method="post" action="management_customer.php">
                                                <input type="submit" class="myButton" id="Circle_btn" value="Update&nbsp;&gt;&gt;" />
                                                <input style="width:15px;" type="text" name="u_Customer_Id" value="<?php echo $one_result["Customer_Id"]; ?>" hidden/>
                                                </form>
                                                <form method="post" action="management_customer.php" onsubmit="return confirm('Are you sure delete this record?')">
                                                <input type="submit" class="myButton" id="Circle_btn" value="Delete&nbsp;&gt;&gt;" />
                                                <input style="width:15px;" type="text" name="d_Customer_Id" value="<?php echo $one_result["Customer_Id"]; ?>" hidden/>
                                                </form>
                                            </td>
                                                <?php
                                                for ($i=0; $i<count($ShowField); $i++) {
                                                    if($ShowField[$i] == "Customer_Picture") {
                                                ?>
                                                        <td><img src="<?php echo $one_result["$ShowField[$i]"];?>" width="100px" /> </td>
                                                <?php
                                                    }
                                                    else {
                                                    ?>
                                                        <td><?php echo $one_result["$ShowField[$i]"]; ?></td>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    ?>
                                    </table>
                                <?php
                                }
                                ?>
                            <div class='reset'></div>            
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
            document.getElementById("el5").style.display="none";
        }
        else if(el == "el6") {
            document.getElementById("el6").style.display="none";
        }
        else if(el == "el7") {
            document.getElementById("el7").style.display="none";
        }
        else if(el == "el8") {
            document.getElementById("el8").style.display="none";
        }
        else if(el == "el9") {
            document.getElementById("el9").style.display="none";
        }
        else if(el == "el10") {
            document.getElementById("el10").style.display="none";
        }
        else if(el == "el11") {
            document.getElementById("el11").style.display="none";
        }
        else if(el == "el12") {
            document.getElementById("el12").style.display="none";
        }
        else if(el == "el13") {
            document.getElementById("el13").style.display="none";
        }
        else if(el == "el14") {
            document.getElementById("el14").style.display="none";
        }
        else if(el == "el15") {
            document.getElementById("el15").style.display="none";
        }
        else if(el == "el16") {
            document.getElementById("el16").style.display="none";
        }
        else if(el == "el17") {
            document.getElementById("el17").style.display="none";
        }
        else if(el == "el18") {
            document.getElementById("el18").style.display="none";
        }
        else if(el == "el19") {
            document.getElementById("el19").style.display="none";
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
            document.getElementById("el5").style.display="block";
        }
        else if(el == "el6") {
            document.getElementById("el6").style.display="block";
        }
        else if(el == "el7") {
            document.getElementById("el7").style.display="block";
        }
        else if(el == "el8") {
            document.getElementById("el8").style.display="block";
        }
        else if(el == "el9") {
            document.getElementById("el9").style.display="block";
        }
        else if(el == "el10") {
            document.getElementById("el10").style.display="block";
        }
        else if(el == "el11") {
            document.getElementById("el11").style.display="block";
        }
        else if(el == "el12") {
            document.getElementById("el12").style.display="block";
        }
        else if(el == "el13") {
            document.getElementById("el13").style.display="block";
        }
        else if(el == "el14") {
            document.getElementById("el14").style.display="block";
        }
        else if(el == "el15") {
            document.getElementById("el15").style.display="block";
        }
        else if(el == "el16") {
            document.getElementById("el16").style.display="block";
        }
        else if(el == "el17") {
            document.getElementById("el17").style.display="block";
        }
        else if(el == "el18") {
            document.getElementById("el18").style.display="block";
        }
        else if(el == "el19") {
            document.getElementById("el19").style.display="block";
        }
        else if(el == "search") {
            document.getElementById("Update_Field").style.display = "none";
            document.getElementById("Insert_Field").style.display = "none";
            document.getElementById("forms_sub_input").style.backgroundColor = "#4eb233";
            document.getElementById("forms_sub_input").value = "Search Information";
        }
        else if(el == "update") {
            document.getElementById("Update_Field").style.display = "block";
            document.getElementById("Insert_Field").style.display = "none";
            document.getElementById("forms_sub_input").style.backgroundColor = "#4eb233";
            document.getElementById("forms_sub_input").value = "Update Information";
        }
        else if(el == "insert") {
            document.getElementById("Sreach_Update_By_Field").style.display = "none";
            document.getElementById("Update_Field").style.display = "none";
            document.getElementById("Insert_Field").style.display = "block";
            document.getElementById("forms_sub_input").style.backgroundColor = "#4eb233";
            document.getElementById("forms_sub_input").value = "Insert Information";
        }
    }
</script>

<script>
    function getSelectValue(e) {
    var mySelect = document.getElementById(e.id);
        if(mySelect.options[3].selected == true){
            document.getElementById(e.id+"_Input2").style.display = "inline";
        }
        else {
            document.getElementById(e.id+"_Input2").style.display = "none";
        }
    }
</script>

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
    function Picture_Show2(obj){
        var len = obj.files.length;
        for (var i =0 ; i < len ; i++){
            showimg2(obj.files[i]);
        }
    }
    function showimg2(img){
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
            document.getElementById('head_icon2').appendChild(img);
        }
    }
</script>