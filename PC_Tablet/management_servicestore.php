<!DOCTYPE html>
<html>
<head>
    <title>Management Servicestore</title>
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
        
        $Search = "ServiceStore_Id, ServiceStore_Name, Address, Phone, Email, Create_Time, Picture1, Picture2, Picture3, Description, Rating, Page_View, Selected_Quantities, Updated, Created";

        $filter = "";
    
        //Search
        if (isset($_POST['ServiceStore_Name']) && !empty($_POST["ServiceStore_Name"])) {
            $ServiceStore_Name = $_POST['ServiceStore_Name'];
            if(isset($_POST["ServiceStore_Name_Yes_No"])){
                $ServiceStore_Name = " LIKE '%$ServiceStore_Name%'";
            }
            else {
                $ServiceStore_Name = " NOT LIKE '%$ServiceStore_Name%'";
            }
            if($filter == "") {
                $filter = "ServiceStore_Name$ServiceStore_Name";
            }
            else {
                $filter = $filter." && ServiceStore_Name$ServiceStore_Name";
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
                $filter = "Address$Address";
            }
            else {
                $filter = $filter." && Address$Address";
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
                $filter = "Phone$Phone";
            }
            else {
                $filter = $filter." && Phone$Phone";
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
                $filter = "Email$Email";
            }
            else {
                $filter = $filter." && Email$Email";
            }  
        }
        if (isset($_POST['Create_Time']) && !empty($_POST["Create_Time"])) {
            $Create_Time = $_POST['Create_Time'];
            if(isset($_POST["Create_Time_Yes_No"])){
                $Create_Time = "='$Create_Time'";
            }
            else {
                $Create_Time = "!='$Create_Time'";
            }
            if($filter == "") {
                $filter = "Create_Time$Create_Time";
            }
            else {
                $filter = $filter." && Create_Time$Create_Time";
            }  
        }
        if (isset($_POST['Description']) && !empty($_POST["Description"])) {
            $Description = $_POST['Description'];
            if(isset($_POST["Description_Yes_No"])){
                $Description = "='$Description%'";
            }
            else {
                $Description = "!='$Description%'";
            }
            if($filter == "") {
                $filter = "Description$Description";
            }
            else {
                $filter = $filter." && Description$Description";
            }  
        }
        if (isset($_POST['Rating'])) {
            if($_POST["Rating"] != "") {
                $Rating = $_POST['Rating'];
                if(isset($_POST["Rating_Range"])) {
                    $Rating_Range = $_POST["Rating_Range"];
                    if($Rating_Range == "equal") {
                        $Rating = "=$Rating";
                    }
                    else if($Rating_Range == "larger") {
                        $Rating = ">$Rating";
                    }
                    else if($Rating_Range == "smaller") {
                        $Rating = "<$Rating";
                    }
                    else if($Rating_Range == "between") {
                        if (isset($_POST['Rating2']) && !empty($_POST["Rating2"])) {
                            $Rating2 = $_POST['Rating2'];
                            if((float)$_POST['Rating'] < (float)$_POST['Rating2']) {
                                $Rating = ">$Rating AND Rating<$Rating2";
                            }
                            else if((float)$_POST['Rating'] > (float)$_POST['Rating2']) {
                                $Rating = ">$Rating2 AND Rating<$Rating";
                            }
                        }
                    }
                }
                if($filter == "") {
                    $filter = "A.Rating$Rating";
                }
                else {
                    $filter = $filter." && A.Rating$Rating";
                }
            }
        }
        if (isset($_POST['Page_View'])) {
            if ($_POST['Page_View'] != "") {
                $Page_View = $_POST['Page_View'];
                if(isset($_POST["Page_View_Range"])) {
                    $Page_View_Range = $_POST["Page_View_Range"];
                    if($Page_View_Range == "equal") {
                        $Page_View = "=$Page_View";
                    }
                    else if($Page_View_Range == "larger") {
                        $Page_View = ">$Page_View";
                    }
                    else if($Page_View_Range == "smaller") {
                        $Page_View = "<$Page_View";
                    }
                    else if($Page_View_Range == "between") {
                        if (isset($_POST['Page_View2']) && !empty($_POST["Page_View2"])) {
                            $Page_View2 = $_POST['Page_View2'];
                            if((float)$_POST['Page_View'] < (float)$_POST['Page_View2']) {
                                $Page_View = ">$Page_View AND Page_View<$Page_View2";
                            }
                            else if((float)$_POST['Page_View'] > (float)$_POST['Page_View2']) {
                                $Page_View = ">$Page_View2 AND Page_View<$Page_View";
                            }
                        }
                    }
                }
                if($filter == "") {
                    $filter = "A.Page_View$Page_View";
                }
                else {
                    $filter = $filter." && A.Page_View$Page_View";
                }
            }
        }
        if (isset($_POST['Selected_Quantities'])) {
            if ($_POST['Selected_Quantities'] != "") {
                $Selected_Quantities = $_POST['Selected_Quantities'];
                if(isset($_POST["Selected_Quantities_Range"])) {
                    $Selected_Quantities_Range = $_POST["Selected_Quantities_Range"];
                    if($Selected_Quantities_Range == "equal") {
                        $Selected_Quantities = "=$Selected_Quantities";
                    }
                    else if($Selected_Quantities_Range == "larger") {
                        $Selected_Quantities = ">$Selected_Quantities";
                    }
                    else if($Selected_Quantities_Range == "smaller") {
                        $Selected_Quantities = "<$Selected_Quantities";
                    }
                    else if($Selected_Quantities_Range == "between") {
                        if (isset($_POST['Selected_Quantities2']) && !empty($_POST["Selected_Quantities2"])) {
                            $Selected_Quantities2 = $_POST['Selected_Quantities2'];
                            if((float)$_POST['Selected_Quantities'] < (float)$_POST['Selected_Quantities2']) {
                                $Selected_Quantities = ">$Selected_Quantities AND Selected_Quantities<$Selected_Quantities2";
                            }
                            else if((float)$_POST['Selected_Quantities'] > (float)$_POST['Selected_Quantities2']) {
                                $Selected_Quantities = ">$Selected_Quantities2 AND Selected_Quantities<$Selected_Quantities";
                            }
                        }
                    }         
                }
                if($filter == "") {
                    $filter = "A.Selected_Quantities$Selected_Quantities";
                }
                else {
                    $filter = $filter." && A.Selected_Quantities$Selected_Quantities";
                }
            }   
        }
        
        //准备更新
        if (isset($_POST['u_Store_Id']) && !empty($_POST["u_Store_Id"])) {
            $u_Store_Id = $_POST['u_Store_Id'];
            $filter = "ServiceStore_Id = $u_Store_Id";
            $enter_sql = "SELECT ".$Search." FROM servicestore WHERE ".$filter;    
            $show_result = mysqli_query($conn, $enter_sql);
        }
        
        //直接删除
        if (isset($_POST['d_Store_Id']) && !empty($_POST["d_Store_Id"])) {
            $d_Store_Id = $_POST['d_Store_Id'];
            $d_sql = "DELETE FROM servicestore WHERE ServiceStore_Id = $d_Store_Id";
            $delete_result = mysqli_query($conn, $d_sql);
        }
            
        if (isset($_POST['Submit'])) {
            $Submit = $_POST['Submit'];
            //Search
            if (isset($_POST['Confirm_Store_Id']) && $Submit == "Update Information") {
                //更新
                $Confirm_Store_Id = $_POST['Confirm_Store_Id'];
                if (isset($_POST['u_ServiceStore_Name']) && !empty($_POST["u_ServiceStore_Name"])) {
                    $u_ServiceStore_Name = $_POST['u_ServiceStore_Name'];
                    $update = "ServiceStore_Name='$u_ServiceStore_Name'";
                    $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_Address']) && !empty($_POST["u_Address"])) {
                    $u_Address = $_POST['u_Address'];
                    $update = "Address='$u_Address'";
                    $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_Phone']) && !empty($_POST["u_Phone"])) {
                    $u_Phone = $_POST['u_Phone'];
                    $update = "Phone=$u_Phone";
                    $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_Email']) && !empty($_POST["u_Email"])) {
                    $u_Email = $_POST['u_Email'];
                    $update = "Email='$u_Email'";
                    $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['Create_Time']) && !empty($_POST["Create_Time"])) {
                    $u_Addresse = $_POST['u_Create_Time'];
                    $update = "Create_Time='$u_Create_Time'";
                    $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['ServiceStore_Name']) && !empty($_POST["ServiceStore_Name"]) && !empty($_FILES['u_Picture1']['tmp_name'])) {
                    $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

                    if (in_array($_FILES["u_Picture1"]["type"], $allowedType)) {    
                        if ($_FILES["u_Picture1"]["size"] < 5000000) { 
                            $extension = pathinfo($_FILES['u_Picture1']['name'], PATHINFO_EXTENSION);
                            $target = "images/source/Store/".$_POST['ServiceStore_Name']."1.".$extension;
                            $filename = "images/source/Store/".$_POST['ServiceStore_Name']."1.".$extension;

                            $result = move_uploaded_file($_FILES["u_Picture1"]["tmp_name"], $filename);

                            if($result) {
                                $update = "ServiceStore_Picture='$target'";
                                $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
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
                if (isset($_POST['ServiceStore_Name']) && !empty($_POST["ServiceStore_Name"]) && !empty($_FILES['u_Picture2']['tmp_name'])) {
                    $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

                    if (in_array($_FILES["u_Picture2"]["type"], $allowedType)) {    
                        if ($_FILES["u_Picture2"]["size"] < 5000000) { 
                            $extension = pathinfo($_FILES['u_Picture2']['name'], PATHINFO_EXTENSION);
                            $target = "images/source/Store/".$_POST['ServiceStore_Name']."2.".$extension;
                            $filename = "images/source/Store/".$_POST['ServiceStore_Name']."2.".$extension;

                            $result = move_uploaded_file($_FILES["u_Picture2"]["tmp_name"], $filename);

                            if($result) {
                                $update = "ServiceStore_Picture='$target'";
                                $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
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
                if (isset($_POST['ServiceStore_Name']) && !empty($_POST["ServiceStore_Name"]) && !empty($_FILES['u_Picture3']['tmp_name'])) {
                    $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

                    if (in_array($_FILES["u_Picture3"]["type"], $allowedType)) {    
                        if ($_FILES["u_Picture3"]["size"] < 5000000) { 
                            $extension = pathinfo($_FILES['u_Picture3']['name'], PATHINFO_EXTENSION);
                            $target = "images/source/Store/".$_POST['ServiceStore_Name']."3.".$extension;
                            $filename = "images/source/Store/".$_POST['ServiceStore_Name']."3.".$extension;

                            $result = move_uploaded_file($_FILES["u_Picture3"]["tmp_name"], $filename);

                            if($result) {
                                $update = "ServiceStore_Picture='$target'";
                                $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
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
                }
                if (isset($_POST['u_Description']) && !empty($_POST["u_Description"])) {
                    $u_Description = $_POST['u_Description'];
                    $update = "Description='$u_Description'";
                    $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_Rating'])) {
                    if ($_POST['u_Rating'] != "") {
                        $u_Rating = $_POST['u_Rating'];
                        $update = "Rating=$u_Rating";
                        $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                }
                if (isset($_POST['u_Page_View'])) {
                    if ($_POST['u_Page_View'] != "") {
                        $u_Page_View = $_POST['u_Page_View'];
                        $update = "Page_View=$u_Page_View";
                        $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                }
                if (isset($_POST['u_Selected_Quantities'])) {
                    if ($_POST['u_Selected_Quantities'] != "") {
                        $u_Selected_Quantities = $_POST['u_Selected_Quantities'];
                        $update = "Selected_Quantities=$u_Selected_Quantities";
                        $u_sql = "UPDATE servicestore SET ".$update." WHERE ServiceStore_Id = $Confirm_Store_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                }
                $ut = "UPDATE servicestore SET A.Updated='$timestamp' WHERE ServiceStore_Id = $Confirm_Store_Id";
                $update_time = mysqli_query($conn, $ut);
                $filter = "ServiceStore_Id = $Confirm_Store_Id";
            }
            else if ($Submit == "Insert Information") {
                //插入
                if (isset($_POST['i_ServiceStore_Name']) && !empty($_POST["i_ServiceStore_Name"]) && isset($_POST['i_Address']) && !empty($_POST["i_Address"]) && isset($_POST['i_Phone']) && !empty($_POST["i_Phone"]) && isset($_POST['i_Email']) && !empty($_POST["i_Email"]) && isset($_POST['i_Create_Time']) && !empty($_POST["i_Create_Time"]) && !empty($_FILES['i_Picture1']['tmp_name']) && !empty($_FILES['i_Picture2']['tmp_name']) && !empty($_FILES['i_Picture3']['tmp_name']) && isset($_POST['i_Description']) && !empty($_POST["i_Description"]) && isset($_POST['i_Rating']) && !empty($_POST["i_Rating"]) && isset($_POST['i_Page_View']) && isset($_POST['i_Selected_Quantities'])) {
                    $i_ServiceStore_Name = $_POST['i_ServiceStore_Name'];
                    $i_Address = $_POST['i_Address'];
                    $i_Phone = $_POST['i_Phone'];
                    $i_Email = $_POST['i_Email'];
                    $i_Create_Time = $_POST['i_Create_Time'];
                    $i_Description = $_POST['i_Description'];
                    $i_Rating = $_POST['i_Rating'];
                    $i_Page_View = $_POST['i_Page_View'];
                    $i_Selected_Quantities = $_POST['i_Selected_Quantities'];
                    
                    $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

                    if (in_array($_FILES["i_Picture1"]["type"], $allowedType) && in_array($_FILES["i_Picture2"]["type"], $allowedType) && in_array($_FILES["i_Picture3"]["type"], $allowedType)) { 
                        if ($_FILES["i_Picture1"]["size"] < 5000000 && $_FILES["i_Picture1"]["size"] < 5000000 && $_FILES["i_Picture3"]["size"] < 5000000) {
                            $extension1 = pathinfo($_FILES['i_Picture1']['name'], PATHINFO_EXTENSION);
                            $extension2 = pathinfo($_FILES['i_Picture2']['name'], PATHINFO_EXTENSION);
                            $extension3 = pathinfo($_FILES['i_Picture3']['name'], PATHINFO_EXTENSION);
                            $target1 = "images/source/Store/".$_POST['i_ServiceStore_Name']."1.".$extension1;
                            $target2 = "images/source/Store/".$_POST['i_ServiceStore_Name']."2.".$extension2;
                            $target3 = "images/source/Store/".$_POST['i_ServiceStore_Name']."3.".$extension3;
                            $filename1 = "images/source/Store/".$_POST['i_ServiceStore_Name']."1.".$extension1;
                            $filename2 = "images/source/Store/".$_POST['i_ServiceStore_Name']."2.".$extension2;
                            $filename3 = "images/source/Store/".$_POST['i_ServiceStore_Name']."3.".$extension3;

                            $result1 = move_uploaded_file($_FILES["i_Picture1"]["tmp_name"], $filename1);
                            $result2 = move_uploaded_file($_FILES["i_Picture2"]["tmp_name"], $filename2);
                            $result3 = move_uploaded_file($_FILES["i_Picture3"]["tmp_name"], $filename3);

                            if($result1 && $result2 && $result3) {
                                $i_sql = "INSERT INTO servicestore (ServiceStore_Name, Address, Phone, Email, Create_Time, Picture1, Picture2, Picture3, Description, Rating, Page_View, Selected_Quantities, Updated, Created) VALUES ('$i_ServiceStore_Name', '$i_Address', '$i_Phone', '$i_Email', '$i_Create_Time', '$target1', '$target2', '$target3', '$i_Description', $i_Rating, $i_Page_View, $i_Selected_Quantities, '$timestamp', '$timestamp')";

                                $result = mysqli_query($conn, $i_sql);
                                echo "<script>console.log('0');</script>";
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
            else {
                header("Location:management_servicestore.php?Alert=Unselected");
            }
        }

        if ($filter == "") {
            $filter = "1 = 1";
        }
        
        $sql = "SELECT ".$Search." FROM servicestore WHERE ".$filter;
        $Search_result = mysqli_query($conn, $sql);
        
        $ShowField = array("ServiceStore_Name", "Address", "Phone", "Email", "Create_Time", "Description");
    
        //显示的列
        if (isset($_POST['ShowField'])) {
            $ShowField = $_POST["ShowField"];
        }
        
        if (isset($_GET['Alert'])) {
            if($_GET['Alert'] == "Unselected") {
                echo "<script>alert('Please select one item store.')</script>";
            }
        }
        mysqli_close($conn);
    }
    else {
        header("Location:management.php");
    }
    ?>
</head>
<body <?php 
      if(isset($_POST['u_Store_Id']) && !empty($_POST["u_Store_Id"])) { 
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
                                    <li><a onclick="appear('el1')">— ServiceStore Name</a></li>
                                    <li><a onclick="appear('el2')">— Address</a></li>
                                    <li><a onclick="appear('el3')">— Phone</a></li>
                                    <li><a onclick="appear('el4')">— Email</a></li>
                                    <li><a onclick="appear('el5')">— Create Time</a></li>
                                    <li><a onclick="appear('el6')">— Description</a></li>
                                    <li><a onclick="appear('el7')">— Rating</a></li>
                                    <li><a onclick="appear('el8')">— Page View</a></li>
                                    <li><a onclick="appear('el9')">— Selected Quantities</a></li>
                                </ul>
                            </div>
                            <p class='center-left-title' onclick="appear('update')">Update</p>
                            <div class='content'>
                                <ul>
                                    <li><a onclick="appear('el10')">— ServiceStore Name</a></li>
                                    <li><a onclick="appear('el11')">— Address</a></li>
                                    <li><a onclick="appear('el12')">— Phone</a></li>
                                    <li><a onclick="appear('el13')">— Email</a></li>
                                    <li><a onclick="appear('el14')">— Create Time</a></li>
                                    <li><a onclick="appear('el15')">— Picture</a></li>
                                    <li><a onclick="appear('el16')">— Description</a></li>
                                    <li><a onclick="appear('el17')">— Rating</a></li>
                                    <li><a onclick="appear('el18')">— Page View</a></li>
                                    <li><a onclick="appear('el19')">— Selected Quantities</a></li>
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
                            <form class='baction bsearchform form1' enctype="multipart/form-data" method='post' action='management_servicestore.php' name='form1'>
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
                                    <input name='Confirm_ServiceStore_Id' value="<?php 
                                                                                 if (isset($show_result) && !empty($show_result)) {
                                                                                     echo $one_show_result['ServiceStore_Id'];
                                                                                 }?>" type="hidden">
                                    <div id="el1">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el1')"/>
                                        <label class="labelname">ServiceStore Name: </label>
                                        <input class="inputstyle" type="text" name='ServiceStore_Name' value="<?php 
                                                                                                              if (isset($show_result) && !empty($show_result)) {
                                                                                                                  echo $one_show_result['ServiceStore_Name'];
                                                                                                              }?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="ServiceStore_Name_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch1" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch1">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el2">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;top:-25px;width:12px;" onclick="disappear('el2')"/>
                                        <label class="labelname" style="position:relative;top:-25px;">Address: </label>
                                        <textarea class="inputstyle" type="text" name='Address' style="margin-top:12px;height:60px;width:200px;"><?php 
                                            if (isset($show_result) && !empty($show_result)) {
                                                echo $one_show_result['Address'];
                                            }?></textarea>
                                        <div class="onoffswitch" style="position:relative;top:-20px;">
                                            <input type="checkbox" name="Address_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch2" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch2">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el3">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el3')"/>
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
                                    <div id="el4">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el4')"/>
                                        <label class="labelname">Email: </label>
                                        <input class="inputstyle" type="text" name='Email' value="<?php 
                                                                                                  if (isset($show_result) && !empty($show_result)) {
                                                                                                      echo $one_show_result['Email']; 
                                                                                                  }?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Email_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch4" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch4">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el5">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el5')"/>
                                        <label class="labelname">Create Time: </label>
                                        <input class="inputstyle" type="text" name='Create_Time' value="<?php 
                                                                                                        if (isset($show_result) && !empty($show_result)) {
                                                                                                            echo $one_show_result['Create_Time']; 
                                                                                                        }?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Create_Time_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch5" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch5">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el6">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;top:-39px;width:12px;" onclick="disappear('el6')"/>
                                        <label class="labelname" style="position:relative;top:-40px;">Description: </label>
                                        <textarea class="inputstyle" type="text" name='Description' style="margin-top:12px;height:100px;width:200px;"><?php 
                                            if (isset($show_result) && !empty($show_result)) {
                                                echo $one_show_result['Description']; 
                                            }?></textarea>
                                        <div class="onoffswitch" style="position:relative;top:-20px;">
                                            <input type="checkbox" name="Description_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch6" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch6">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el7">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el7')"/>
                                        <label class="labelname">Rating: </label>
                                        <input id="Rating_Range_Dropdown_Input1" class="inputstyle" type="number" name='Rating' value="<?php 
                                                                                                            if (isset($show_result) && !empty($show_result)) {
                                                                                                                echo $one_show_result['Rating'];
                                                                                                            }?>">
                                        <input id="Rating_Range_Dropdown_Input2" style="display:none;" class="inputstyle" type="number" name='Rating2'>
                                        <select class="dropdown" name="Rating_Range" id="Rating_Range_Dropdown" onclick="getSelectValue(this);">
                                            <option value="equal">equal</option>
                                            <option value="larger">larger</option>
                                            <option value="smaller">smaller</option>
                                            <option value="between">between</option>
                                        </select>
                                    </div>
                                    <div id="el8">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el8')"/>
                                        <label class="labelname">Page View: </label>
                                        <input id="Page_View_Range_Dropdown_Input1" class="inputstyle" type="number" name='Page_View' value="<?php 
                                                                                                            if (isset($show_result) && !empty($show_result)) {
                                                                                                                echo $one_show_result['Page_View'];
                                                                                                            }?>">
                                        <input id="Page_View_Range_Dropdown_Input2" style="display:none;" class="inputstyle" type="number" name='Page_View2'>
                                        <select class="dropdown" name="Page_View_Range" id="Page_View_Range_Dropdown" onclick="getSelectValue(this);">
                                            <option value="equal">equal</option>
                                            <option value="larger">larger</option>
                                            <option value="smaller">smaller</option>
                                            <option value="between">between</option>
                                        </select>
                                    </div>
                                    <div id="el9">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el9')"/>
                                        <label class="labelname">Selected Quantities: </label>
                                        <input id="Selected_Quantities_Range_Dropdown_Input1" class="inputstyle" type="number" name='Selected_Quantities' value="<?php
                                        if (isset($show_result) && !empty($show_result)) {
                                            echo $one_show_result['Selected_Quantities'];
                                        }?>">
                                        <input id="Selected_Quantities_Range_Dropdown_Input2" style="display:none;" class="inputstyle" type="number" name='Selected_Quantities2'>
                                        <select class="dropdown" name="Selected_Quantities_Range" id="Selected_Quantities_Range_Dropdown" onclick="getSelectValue(this);">
                                            <option value="equal">equal</option>
                                            <option value="larger">larger</option>
                                            <option value="smaller">smaller</option>
                                            <option value="between">between</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="Update_Field" style="display:none;">
                                    <h3 class='box-head'><label>Update</label></h3>
                                    <div id="el10">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el10')"/>
                                        <label class="labelname">ServiceStore Name:</label>
                                        <input class="inputstyle" type="text" name='u_ServiceStore_Name'>
                                    </div>
                                    <div id="el11">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;top:-24px;width:12px;" onclick="disappear('el11')"/>
                                        <label class="labelname" style="position:relative;top:-25px;">Address:</label>
                                        <textarea class="inputstyle" type="text" name='u_Address' style="height:60px;width:200px;margin-top:10px;"></textarea>
                                    </div>
                                    <div id="el12">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el12')"/>
                                        <label class="labelname">Phone:</label>
                                        <input class="inputstyle" type="text" name='u_Phone'>
                                    </div>
                                    <div id="el13">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el13')"/>
                                        <label class="labelname">Email:</label>
                                        <input class="inputstyle" type="text" name='u_Email'>
                                    </div>
                                    <div id="el14">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el14')"/>
                                        <label class="labelname">Create Time:</label>
                                        <input class="inputstyle" type="text" name='u_Create_Time'>
                                    </div>
                                    <div id="el15">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;top:-10px;" onclick="disappear('el15')"/>
                                        <label style="position:relative;top:-10px;" class="labelname">Picture: </label>
                                        <label style="position:relative;top:8px;" id="head_icon1" for="head_icon_input1">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input1" type="file" name='u_Picture1' hidden="hidden" onchange="Picture_Show1(this);">
                                        <label style="position:relative;top:8px;" id="head_icon2" for="head_icon_input2">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input2" type="file" name='u_Picture2' hidden="hidden" onchange="Picture_Show2(this);">
                                        <label style="position:relative;top:8px;" id="head_icon3" for="head_icon_input3">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input3" type="file" name='u_Picture3' hidden="hidden" onchange="Picture_Show3(this);">
                                        <span style="position:relative;top:-10px;left:10px;">(Please enter the Customer Name for updating)</span>
                                    </div>
                                    <div id="el16">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;top:-39px;width:12px;" onclick="disappear('el16')"/>
                                        <label class="labelname" style="position:relative;top:-40px;">Description:</label>
                                        <textarea class="inputstyle" type="text" name='u_Description' style="height:100px;width:200px;margin-top:10px;"></textarea>
                                    </div>
                                    <div id="el17">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el17')"/>
                                        <label class="labelname">Rating: </label>
                                        <input class="inputstyle" type="number" name='u_Rating'>
                                    </div>
                                    <div id="el18">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el18')"/>
                                        <label class="labelname">Page View:</label>
                                        <input class="inputstyle" type="number" name='u_Page_View'>
                                    </div>
                                    <div id="el19">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el19')"/>
                                        <label class="labelname">Selected Quantities: </label>
                                        <input class="inputstyle" type="number" name='u_Selected_Quantities'>
                                    </div>
                                </div>
                                <div id="Insert_Field" style="display:none;">
                                    <h3 class='box-head'><label>Insert</label></h3>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">ServiceStore Name:</label>
                                        <input class="inputstyle" type="text" name='i_ServiceStore_Name'>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:-24px;width:12px;"/>
                                        <label class="labelname" style="position:relative;top:-25px;">Address: </label>
                                        <textarea class="inputstyle" type="text" name='i_Address' style="height:60px;width:200px;margin-top:10px;"></textarea>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Phone:</label>
                                        <input class="inputstyle" type="text" name='i_Phone'>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Email:</label>
                                        <input class="inputstyle" type="text" name='i_Email'>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Create Time: </label>
                                        <input class="inputstyle" type="date" name='i_Create_Time'>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:-9px;width:12px;"/>
                                        <label style="position:relative;top:-10px;" class="labelname">Picture: </label>
                                        <label style="position:relative;top:8px;" id="head_icon4" for="head_icon_input4">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input4" type="file" name='i_Picture1' hidden="hidden" onchange="Picture_Show4(this);">
                                        <label style="position:relative;top:8px;" id="head_icon5" for="head_icon_input5">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input5" type="file" name='i_Picture2' hidden="hidden" onchange="Picture_Show5(this);">
                                        <label style="position:relative;top:8px;" id="head_icon6" for="head_icon_input6">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input6" type="file" name='i_Picture3' hidden="hidden" onchange="Picture_Show6(this);">
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:-39px;width:12px;"/>
                                        <label class="labelname" style="position:relative;top:-40px;">Description: </label>
                                        <textarea class="inputstyle" type="text" name='i_Description' style="height:100px;width:200px;margin-top:10px;"></textarea>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Rating: </label>
                                        <input class="inputstyle" type="number" name='i_Rating' value="5">
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Page View:</label>
                                        <input class="inputstyle" type="number" name='i_Page_View' value="0">
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Selected Quantities: </label>
                                        <input class="inputstyle" type="number" name='i_Selected_Quantities' value="0">
                                    </div>
                                </div>
                                <h3 class='box-head'><label>Show Fields</label></h3>
                                <div class="checkbox_box">
                                    <div><input type="checkbox" id="checkbox-1-1" class="regular-checkbox" name="ShowField[]" value="ServiceStore_Name" checked/><label for="checkbox-1-1"></label><span>ServiceStore Name&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-2" class="regular-checkbox" name="ShowField[]" value="Address" checked/><label for="checkbox-1-2"></label><span>Address&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-3" class="regular-checkbox" name="ShowField[]" value="Phone" checked/><label for="checkbox-1-3"></label><span>Phone&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-4" class="regular-checkbox" name="ShowField[]" value="Email" checked/><label for="checkbox-1-4"></label><span>	Email&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-5" class="regular-checkbox" name="ShowField[]" value="Create_Time" checked/><label for="checkbox-1-5"></label><span>Create Time&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-6" class="regular-checkbox" name="ShowField[]" value="Picture" /><label for="checkbox-1-6"></label><span>Picture&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-7" class="regular-checkbox" name="ShowField[]" value="Description" checked/><label for="checkbox-1-7"></label><span>Description&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-8" class="regular-checkbox" name="ShowField[]" value="Rating" /><label for="checkbox-1-8"></label><span>Rating&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-9" class="regular-checkbox" name="ShowField[]" value="Page_View" /><label for="checkbox-1-9"></label><span>Page View&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-10" class="regular-checkbox" name="ShowField[]" value="Selected_Quantities" /><label for="checkbox-1-10"></label><span>Selected Quantities&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-11" class="regular-checkbox" name="ShowField[]" value="Created"/><label for="checkbox-1-11"></label><span>Created&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-12" class="regular-checkbox" name="ShowField[]" value="Updated"/><label for="checkbox-1-12"></label><span>Updated&nbsp;&nbsp;</span></div>
                                </div>
                                <div class="forms_sub">
                                    <input id="forms_sub_input" type="submit" value="Search Information" name="Submit">
                                </div>
                            </form>
                                <div class='reset'></div>
                                    <?php
                                    if (!$Search_result || mysqli_num_rows($Search_result) == 0){
                                    }
                                    else {
                                    ?>
                                        <?php
                                        if(count($ShowField)>11){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-300px;z-index:100;">
                                        <?php
                                        }
                                        else if(count($ShowField)>10){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-250px;z-index:100;">
                                        <?php
                                        }
                                        else if(count($ShowField)>9){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-200px;z-index:100;">
                                        <?php
                                        }
                                        else if(count($ShowField)>8){
                                        ?>
                                            <table width='100%' class='cleanlily_table' id='orderlist_listtable' style="position:relative;left:-150px;z-index:100;">
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
                                        while ($one_result = mysqli_fetch_assoc($Search_result)) {
                                        ?> 
                                        <tr>
                                        <?php if(count($ShowField)>0){
                                        ?>
                                            <td>
                                                <form method="post" action="management_servicestore.php">
                                                <input type="submit" class="myButton" id="Circle_btn" value="Update&nbsp;&gt;&gt;" />
                                                <input style="width:15px;" type="text" name="u_Store_Id" value="<?php echo $one_result["ServiceStore_Id"]; ?>" hidden/>
                                                </form>
                                                <form method="post" action="management_servicestore.php" onsubmit="return confirm('Are you sure delete this item?')">
                                                <input type="submit" class="myButton" id="Circle_btn" value="Delete&nbsp;&gt;&gt;" />
                                                <input style="width:15px;" type="text" name="d_Store_Id" value="<?php echo $one_result["ServiceStore_Id"]; ?>" hidden/>
                                                </form>
                                            </td>
                                            <?php
                                            for ($i=0; $i<count($ShowField); $i++) {
                                                if($ShowField[$i] == "Picture") {
                                            ?>
                                                    <td><img src="<?php echo $one_result["$ShowField[$i]1"];?>" width="100px" />
                                                        <img src="<?php echo $one_result["$ShowField[$i]2"];?>" width="100px" />
                                                        <img src="<?php echo $one_result["$ShowField[$i]3"];?>" width="100px" />
                                                    </td>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <td><?php echo $one_result["$ShowField[$i]"]; ?></td>
                                                <?php
                                                }
                                            }
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
                            <br />         
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
            document.getElementById("Sreach_Update_By_Field").style.display = "block";
            document.getElementById("Update_Field").style.display = "none";
            document.getElementById("Insert_Field").style.display = "none";
            document.getElementById("forms_sub_input").style.backgroundColor = "#4eb233";
            document.getElementById("forms_sub_input").value = "Search Information";
        }
        else if(el == "update") {
            document.getElementById("Sreach_Update_By_Field").style.display = "block";
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
    function Picture_Show3(obj){
        var len = obj.files.length;
        for (var i =0 ; i < len ; i++){
            showimg3(obj.files[i]);
        }
    }
    function showimg3(img){
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
            document.getElementById('head_icon3').appendChild(img);
        }
    }
    function Picture_Show4(obj){
        var len = obj.files.length;
        for (var i =0 ; i < len ; i++){
            showimg4(obj.files[i]);
        }
    }
    function showimg4(img){
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
            document.getElementById('head_icon4').appendChild(img);
        }
    }
    function Picture_Show5(obj){
        var len = obj.files.length;
        for (var i =0 ; i < len ; i++){
            showimg5(obj.files[i]);
        }
    }
    function showimg5(img){
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
            document.getElementById('head_icon5').appendChild(img);
        }
    }
    function Picture_Show6(obj){
        var len = obj.files.length;
        for (var i =0 ; i < len ; i++){
            showimg6(obj.files[i]);
        }
    }
    function showimg6(img){
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
            document.getElementById('head_icon6').appendChild(img);
        }
    }
</script>