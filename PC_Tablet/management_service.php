<!DOCTYPE html>
<html>
<head>
    <title>Management Service</title>
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
        
        $Search = "A.Item_Id, A.Item_Name, A.Item_Picture, A.Item_Detail_Picture, A.Price, A.Working_Time, A.Rating, A.Page_View, A.Selected_Quantities, B.Status, C.Category_Name, D.ServiceStore_Name, A.Updated, A.Created";

        $filter = "";
    
        //Search
        if (isset($_POST['Item_Name']) && !empty($_POST["Item_Name"])) {
            $Item_Name = $_POST['Item_Name'];
            if(isset($_POST["Item_Name_Yes_No"])){
                $Item_Name = " LIKE '%$Item_Name%'";
            }
            else {
                $Item_Name = " NOT LIKE '%$Item_Name%'";
            }
            if($filter == "") {
                $filter = "A.Item_Name$Item_Name";
            }
            else {
                $filter = $filter." && A.Item_Name$Item_Name";
            }  
        }
        if (isset($_POST['Price']) && !empty($_POST["Price"])) {
            $Price = $_POST['Price'];
            if(isset($_POST["Price_Range"])) {
                $Price_Range = $_POST["Price_Range"];
                if($Price_Range == "equal") {
                    $Price = "=$Price";
                }
                else if($Price_Range == "larger") {
                    $Price = ">$Price";
                }
                else if($Price_Range == "smaller") {
                    $Price = "<$Price";
                }
                else if($Price_Range == "between") {
                    if (isset($_POST['Price2']) && !empty($_POST["Price2"])) {
                        $Price2 = $_POST['Price2'];
                        if((float)$_POST['Price'] < (float)$_POST['Price2']) {
                            $Price = ">$Price AND A.Price<$Price2";
                        }
                        else if((float)$_POST['Price'] > (float)$_POST['Price2']) {
                            $Price = ">$Price2 AND A.Price<$Price";
                        }
                    }
                }
            }
                    if($filter == "") {
                        $filter = "A.Price$Price";
                        echo "<script>console.log('$filter');</script>";
                    }
                    else {
                        $filter = $filter." && A.Price$Price";
                    }
                }
        if (isset($_POST['Working_Time']) && !empty($_POST["Working_Time"])) {
            $Working_Time = $_POST['Working_Time'];
            if(isset($_POST["Working_Time_Range"])) {
                $Working_Time_Range = $_POST["Working_Time_Range"];
                if($Working_Time_Range == "equal") {
                    $Working_Time = "=$Working_Time";
                }
                else if($Working_Time_Range == "larger") {
                    $Working_Time = ">$Working_Time";
                }
                else if($Working_Time_Range == "smaller") {
                    $Working_Time = "<$Working_Time";
                }
                else if($Working_Time_Range == "between") {
                    if (isset($_POST['Working_Time2']) && !empty($_POST["Working_Time2"])) {
                        $Working_Time2 = $_POST['Working_Time2'];
                        if((float)$_POST['Working_Time'] < (float)$_POST['Working_Time2']) {
                            $Working_Time = ">$Working_Time AND A.Working_Time<$Working_Time2";
                        }
                        else if((float)$_POST['Working_Time'] > (float)$_POST['Working_Time2']) {
                            $Working_Time = ">$Working_Time2 AND A.Working_Time<$Working_Time";
                        }
                    }
                }
            }
                    if($filter == "") {
                        $filter = "A.Working_Time$Working_Time";
                        echo "<script>console.log('$filter');</script>";
                    }
                    else {
                        $filter = $filter." && A.Working_Time$Working_Time";
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
                                $Rating = ">$Rating AND A.Rating<$Rating2";
                            }
                            else if((float)$_POST['Rating'] > (float)$_POST['Rating2']) {
                                $Rating = ">$Rating2 AND A.Rating<$Rating";
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
                                $Page_View = ">$Page_View AND A.Page_View<$Page_View2";
                            }
                            else if((float)$_POST['Page_View'] > (float)$_POST['Page_View2']) {
                                $Page_View = ">$Page_View2 AND A.Page_View<$Page_View";
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
                                $Selected_Quantities = ">$Selected_Quantities AND A.Selected_Quantities<$Selected_Quantities2";
                            }
                            else if((float)$_POST['Selected_Quantities'] > (float)$_POST['Selected_Quantities2']) {
                                $Selected_Quantities = ">$Selected_Quantities2 AND A.Selected_Quantities<$Selected_Quantities";
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
        if (isset($_POST['Status']) && !empty($_POST["Status"])) {
            $Status = $_POST['Status'];
            if($Status == "all") {

            }
            else {
                if($filter == "") {
                    $filter = "A.Status_Id=$Status";
                }
                else {
                    $filter = $filter." && A.Status_Id=$Status";
                }               
            }
        }
        if (isset($_POST['Category']) && !empty($_POST["Category"])) {
            $Category = $_POST['Category'];
            if($Category == "all") {

            }
            else {
                if($filter == "") {
                    $filter = "A.Category_Id=$Category";
                }
                else {
                    $filter = $filter." && A.Category_Id=$Category";
                }               
            }
        }
        if (isset($_POST['ServiceStore']) && !empty($_POST["ServiceStore"])) {
            $ServiceStore = $_POST['ServiceStore'];
            if($ServiceStore == "all") {

            }
            else {
                if($filter == "") {
                    $filter = "A.ServiceStore_Id=$ServiceStore";
                }
                else {
                    $filter = $filter." && A.ServiceStore_Id=$ServiceStore";
                }               
            }
        }
        
        //准备更新
        if (isset($_POST['u_Item_Id']) && !empty($_POST["u_Item_Id"])) {
            $u_Item_Id = $_POST['u_Item_Id'];
            $enter_sql = "SELECT ".$Search." FROM serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id INNER JOIN servicestore AS D ON A.ServiceStore_Id = D.ServiceStore_Id WHERE A.Item_Id = $u_Item_Id";
            $show_result = mysqli_query($conn, $enter_sql);
        }
        
        //直接删除
        if (isset($_POST['d_Item_Id']) && !empty($_POST["d_Item_Id"])) {
            $d_Item_Id = $_POST['d_Item_Id'];
            echo $d_Item_Id;
            $d_sql = "DELETE A FROM serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id INNER JOIN servicestore AS D ON A.ServiceStore_Id = D.ServiceStore_Id WHERE A.Item_Id = $d_Item_Id";
            $delete_result = mysqli_query($conn, $d_sql);
        }
            
        if (isset($_POST['Submit'])) {
            $Submit = $_POST['Submit'];
            //Search
            if (isset($_POST['Confirm_Item_Id']) && $Submit == "Update Information") {
                //更新
                $Confirm_Item_Id = isset($_POST['Confirm_Item_Id']);
                if (isset($_POST['u_Item_Name']) && !empty($_POST["u_Item_Name"])) {
                    $u_Item_Name = $_POST['u_Item_Name'];
                    $update = "A.Item_Name='$u_Item_Name'";
                    $u_sql = "UPDATE serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id SET ".$update." WHERE A.Item_Id = $Confirm_Item_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                } 
                if (isset($_POST['Item_Name']) && !empty($_POST["Item_Name"]) && !empty($_FILES['u_Item_Picture']['tmp_name'])) {
                    $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

                    if (in_array($_FILES["u_Item_Picture"]["type"], $allowedType)) {    
                        if ($_FILES["u_Item_Picture"]["size"] < 5000000) { 
                            $extension = pathinfo($_FILES['u_Item_Picture']['name'], PATHINFO_EXTENSION);
                            $target = "images/source/Item/".$_POST['Item_Name'].".".$extension;
                            $filename = "images/source/Item/".$_POST['Item_Name'].".".$extension;

                            $result = move_uploaded_file($_FILES["u_Item_Picture"]["tmp_name"], $filename);

                            if($result) {
                                $update = "Item_Picture='$target'";
                                $u_sql = "UPDATE serviceitem SET ".$update." WHERE A.Item_Id = $Confirm_Item_Id";
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
                if (isset($_POST['Item_Name']) && !empty($_POST["Item_Name"]) && !empty($_FILES['u_Item_Detail_Picture']['tmp_name'])) {
                    $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

                    if (in_array($_FILES["u_Item_Detail_Picture"]["type"], $allowedType)) {    
                        if ($_FILES["u_Item_Detail_Picture"]["size"] < 5000000) { 
                            $extension = pathinfo($_FILES['u_Item_Detail_Picture']['name'], PATHINFO_EXTENSION);
                            $target = "images/source/Item/".$_POST['Item_Name']."_detail.".$extension;
                            $filename = "images/source/Item/".$_POST['Item_Name']."_detail.".$extension;

                            $result = move_uploaded_file($_FILES["u_Item_Detail_Picture"]["tmp_name"], $filename);

                            if($result) {
                                $update = "Item_Detail_Picture='$target'";
                                $u_sql = "UPDATE serviceitem SET ".$update." WHERE A.Item_Id = $Confirm_Item_Id";
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
                if (isset($_POST['u_Price']) && !empty($_POST["u_Price"])) {
                    $u_Price = $_POST['u_Price'];
                    $update = "A.Price=$u_Price";
                    $u_sql = "UPDATE serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id SET ".$update." WHERE A.Item_Id = $Confirm_Item_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_Working_Time']) && !empty($_POST["u_Working_Time"])) {
                    $u_Working_Time = $_POST['u_Working_Time'];
                    $update = "A.Working_Time=$u_Working_Time";
                    $u_sql = "UPDATE serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id SET ".$update." WHERE A.Item_Id = $Confirm_Item_Id";
                    $update_result = mysqli_query($conn, $u_sql);
                }
                if (isset($_POST['u_Rating'])) {
                    if ($_POST['u_Rating'] != "") {
                        $u_Rating = $_POST['u_Rating'];
                        $update = "A.Rating=$u_Rating";
                        $u_sql = "UPDATE serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id SET ".$update." WHERE A.Item_Id = $Confirm_Item_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                }
                if (isset($_POST['u_Page_View'])) {
                    if ($_POST['u_Page_View'] != "") {
                        $u_Page_View = $_POST['u_Page_View'];
                        $update = "A.Page_View=$u_Page_View";
                        $u_sql = "UPDATE serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id SET ".$update." WHERE A.Item_Id = $Confirm_Item_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                }
                if (isset($_POST['u_Selected_Quantities'])) {
                    if ($_POST['u_Selected_Quantities'] != "") {
                        $u_Selected_Quantities = $_POST['u_Selected_Quantities'];
                        $update = "A.Selected_Quantities=$u_Selected_Quantities";
                        $u_sql = "UPDATE serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id SET ".$update." WHERE A.Item_Id = $Confirm_Item_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                }
                if (isset($_POST['u_Status']) && !empty($_POST["u_Status"])) {
                    $u_Status = $_POST['u_Status'];
                    if ($u_Status != "nil") {
                        $update = "A.Status_Id=$u_Status";
                        $u_sql = "UPDATE serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id SET ".$update." WHERE A.Item_Id = $Confirm_Item_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }
                }
                if (isset($_POST['u_Category']) && !empty($_POST["u_Category"])) {
                    $u_Category = $_POST['u_Category'];
                    if ($u_Category != "nil") {
                        $update = "A.Category_Id=$u_Category";
                        $u_sql = "UPDATE serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id SET ".$update." WHERE A.Item_Id = $Confirm_Item_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }   
                }
                if (isset($_POST['u_Service_Store']) && !empty($_POST["u_Service_Store"])) {
                    $u_Service_Store = $_POST['u_Service_Store'];
                    if ($u_Service_Store != "nil") {
                        $update = "A.ServiceStore_Id=$u_Service_Store";
                        $u_sql = "UPDATE serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id SET ".$update." WHERE A.Item_Id = $Confirm_Item_Id";
                        $update_result = mysqli_query($conn, $u_sql);
                    }   
                }
                $ut = "UPDATE serviceitem AS A INNER JOIN serviceStatus AS B ON A.Status_Id = B.Status_Id INNER JOIN Category AS C ON A.Category_Id = C.Category_Id SET A.Updated='$timestamp' WHERE A.Item_Id = $Confirm_Item_Id";
                $update_item = mysqli_query($conn, $ut);
                $filter = "A.Item_Id = $Confirm_Item_Id";
            }
            else if ($Submit == "Insert Information") {
                //插入
                if (isset($_POST['i_Item_Name']) && !empty($_POST["i_Item_Name"]) && !empty($_FILES['i_Item_Picture']['tmp_name']) && !empty($_FILES['i_Item_Detail_Picture']['tmp_name']) && isset($_POST['i_Description']) && !empty($_POST["i_Description"]) && isset($_POST['i_Price']) && !empty($_POST["i_Price"]) && isset($_POST['i_Working_Time']) && !empty($_POST["i_Working_Time"]) && isset($_POST['i_Rating']) && !empty($_POST["i_Rating"]) && isset($_POST['i_Page_View']) && isset($_POST['i_Selected_Quantities']) && isset($_POST['i_Status']) && !empty($_POST["i_Status"]) && isset($_POST['i_Category']) && !empty($_POST["i_Category"]) && isset($_POST['i_Service_Store']) && !empty($_POST["i_Service_Store"])) {
                    $i_Item_Name = $_POST['i_Item_Name'];
                    $i_Description = $_POST['i_Description'];
                    $i_Price = $_POST['i_Price'];
                    $i_Working_Time = $_POST['i_Working_Time']." Minutes";
                    $i_Rating = $_POST['i_Rating'];
                    $i_Page_View = $_POST['i_Page_View'];
                    $i_Selected_Quantities = $_POST['i_Selected_Quantities'];
                    $i_Status = $_POST['i_Status'];
                    $i_Category = $_POST['i_Category'];
                    $i_Service_Store = $_POST['i_Service_Store'];

                    $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

                    if (in_array($_FILES["i_Item_Picture"]["type"], $allowedType) && in_array($_FILES["i_Item_Detail_Picture"]["type"], $allowedType)) {    
                        if ($_FILES["i_Item_Picture"]["size"] < 5000000 && $_FILES["i_Item_Detail_Picture"]["size"] < 5000000) { 
                            $extension = pathinfo($_FILES['i_Item_Picture']['name'], PATHINFO_EXTENSION);
                            $target = "images/source/Item/".$i_Item_Name.".".$extension;
                            $filename = "images/source/Item/".$i_Item_Name.".".$extension;
                            
                            $extension1 = pathinfo($_FILES['i_Item_Detail_Picture']['name'], PATHINFO_EXTENSION);
                            $target1 = "images/source/Item/".$i_Item_Name."_detail.".$extension1;
                            $filename1 = "images/source/Item/".$i_Item_Name."_detail.".$extension1;

                            $result = move_uploaded_file($_FILES["i_Item_Picture"]["tmp_name"], $filename);
                            $result1 = move_uploaded_file($_FILES["i_Item_Detail_Picture"]["tmp_name"], $filename1);

                            if($result && $result1) {
                                $i_sql = "INSERT INTO serviceitem (Item_Name, Item_Picture, Item_Detail_Picture, Description, Price, Working_Time, Rating, Page_View, Selected_Quantities, Status_Id, Category_Id, ServiceStore_Id, Updated, Created) VALUES ('$i_Item_Name', '$target', '$target1', '$i_Description', $i_Price, '$i_Working_Time', $i_Rating, $i_Page_View, $i_Selected_Quantities, $i_Status, $i_Category, $i_Service_Store, '$timestamp', '$timestamp')";

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
            else {
                header("Location:management_service.php?Alert=Unselected");
            }
        }
 
        if ($filter == "") {
            $filter = "1 = 1";
        }
        
        $sql = "SELECT ".$Search." FROM serviceitem AS A INNER JOIN servicestatus AS B ON A.Status_Id = B.Status_Id INNER JOIN category AS C ON A.Category_Id = C.Category_Id INNER JOIN servicestore AS D ON A.ServiceStore_Id = D.ServiceStore_Id WHERE ".$filter;
        $search_result = mysqli_query($conn, $sql); // search table NOW!  
    
        //Status
        $sql_Status = "SELECT * FROM servicestatus";
        $Status_List = mysqli_query($conn, $sql_Status);
        $u_Status_List = mysqli_query($conn, $sql_Status);
        $i_Status_List = mysqli_query($conn, $sql_Status);
    
        //Category
        $sql_Category = "SELECT * FROM category";
        $Category_List = mysqli_query($conn, $sql_Category);
        $u_Category_List = mysqli_query($conn, $sql_Category);
        $i_Category_List = mysqli_query($conn, $sql_Category);
        
        //Service_Store
        $sql_Service_Store = "SELECT * FROM servicestore";
        $Service_Store_List = mysqli_query($conn, $sql_Service_Store);
        $u_Service_Store_List = mysqli_query($conn, $sql_Service_Store);
        $i_Service_Store_List = mysqli_query($conn, $sql_Service_Store);

        $ShowField = array("Item_Name", "Price", "Status", "Category_Name", "ServiceStore_Name");
    
        //显示的列
        if (isset($_POST['ShowField'])) {
            $ShowField = $_POST["ShowField"];
        }
        
        if (isset($_GET['Alert'])) {
            if($_GET['Alert'] == "Unselected") {
                echo "<script>alert('Please select one item first.')</script>";
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
      if(isset($_POST['u_Item_Id']) && !empty($_POST["u_Item_Id"])) { 
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
                                    <li><a onclick="appear('el1')">— Item Name</a></li>
                                    <li><a onclick="appear('el2')">— Price</a></li>
                                    <li><a onclick="appear('el3')">— Working Time</a></li>
                                    <li><a onclick="appear('el4')">— Rating</a></li>
                                    <li><a onclick="appear('el5')">— Page View</a></li>
                                    <li><a onclick="appear('el6')">— Selected Quantities</a></li>
                                    <li><a onclick="appear('el7')">— Status</a></li>
                                    <li><a onclick="appear('el8')">— Category</a></li>
                                    <li><a onclick="appear('el9')">— Service Store</a></li>
                                </ul>
                            </div>
                            <p class='center-left-title' onclick="appear('update')">Update</p>
                            <div class='content'>
                                <ul>
                                    <li><a onclick="appear('el10')">— Item Name</a></li>
                                    <li><a onclick="appear('el11')">— Price</a></li>
                                    <li><a onclick="appear('el12')">— Working Time</a></li>
                                    <li><a onclick="appear('el13')">— Rating</a></li>
                                    <li><a onclick="appear('el14')">— Page View</a></li>
                                    <li><a onclick="appear('el15')">— Selected Quantities</a></li>
                                    <li><a onclick="appear('el16')">— Status</a></li>
                                    <li><a onclick="appear('el17')">— Category</a></li>
                                    <li><a onclick="appear('el18')">— Service Store</a></li>
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
                            <form class='baction bsearchform form1' enctype="multipart/form-data" method='post' action='management_service.php' name='form1'>
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
                                    <input name='Confirm_Item_Id' value="<?php 
                                                                        if (isset($show_result) && !empty($show_result)) {
                                                                                echo $one_show_result['Item_Id'];
                                                                        }?>" type="hidden">
                                    <div id="el1">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el1')"/>
                                        <label class="labelname">Item Name: </label>
                                        <input class="inputstyle" type="text" name='Item_Name' value="<?php 
                                                                                                    if (isset($show_result) && !empty($show_result)) {
                                                                                                        echo $one_show_result['Item_Name'];
                                                                                                    } ?>">
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="Item_Name_Yes_No" class="onoffswitch-checkbox" id="myonoffswitch1" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch1">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="el2">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el2')"/>
                                        <label class="labelname">Price: </label>
                                        <input id="Price_Dropdown_Input1" class="inputstyle" type="text" name='Price' value="<?php 
                                                                                                                            if (isset($show_result) && !empty($show_result)) {
                                                                                                                                echo $one_show_result['Price'];
                                                                                                                            }?>">
                                        <input id="Price_Dropdown_Input2" style="display:none;" class="inputstyle" type="number" name='Price2'>
                                        <select class="dropdown" name="Price_Range" id="Price_Dropdown" onclick="getSelectValue(this);">
                                            <option value="equal">equal</option>
                                            <option value="larger">larger</option>
                                            <option value="smaller">smaller</option>
                                            <option value="between">between</option>
                                        </select>
                                    </div>
                                    <div id="el3">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el3')"/>
                                        <label class="labelname">Working Time (Mins): </label>
                                        <input id="Working_Time_Dropdown_Input1" class="inputstyle" type="text" name='Working_Time' value="<?php 
                                                                                                                    if (isset($show_result) && !empty($show_result)) {
                                                                                                                        echo $one_show_result['Working_Time'];
                                                                                                                    }?>">
                                        <input id="Working_Time_Dropdown_Input2" style="display:none;" class="inputstyle" type="number" name='Working_Time2'>
                                        <select class="dropdown" name="Working_Time_Range" id="Working_Time_Dropdown" onclick="getSelectValue(this);">
                                            <option value="equal">equal</option>
                                            <option value="larger">larger</option>
                                            <option value="smaller">smaller</option>
                                            <option value="between">between</option>
                                        </select>
                                    </div>
                                    <div id="el4">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el4')"/>
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
                                    <div id="el5">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el5')"/>
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
                                    <div id="el6">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el6')"/>
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
                                    <div id="el7">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el7')"/>
                                        <label class="labelname">Status: </label>
                                        <select class="dropdown" type="text" name="Status">
                                            <option value="all">All</option>
                                            <?php
                                            while ($one_result = mysqli_fetch_assoc($Status_List)) {
                                                if (isset($show_result) && !empty($show_result)) {
                                                    if($one_result['Status'] == $one_show_result['Status']) {
                                                    ?>
                                                        <option value="<?php echo $one_result['Status_Id']; ?>" selected><?php echo $one_result['Status'];?></option>
                                                    <?php
                                                    }
                                                    else {
                                                    ?>
                                                        <option value="<?php echo $one_result['Status_Id']; ?>"><?php echo $one_result['Status'];?></option>
                                                    <?php
                                                    }
                                                }
                                                else {
                                                ?>
                                                    <option value="<?php echo $one_result['Status_Id']; ?>"><?php echo $one_result['Status'];?></option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="el8">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el8')"/>
                                        <label class="labelname">Category: </label>
                                        <select style="width:200px;" class="dropdown" type="text" name="Category">
                                            <option value="all">All</option>
                                            <?php
                                            while ($one_result = mysqli_fetch_assoc($Category_List)) {
                                                if($one_result['Category_Name'] == $one_show_result['Category_Name']) {
                                                ?>
                                                    <option value="<?php echo $one_result['Category_Id']; ?>" selected><?php echo $one_result['Category_Name'];?></option>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <option value="<?php echo $one_result['Category_Id']; ?>"><?php echo $one_result['Category_Name'];?></option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="el9">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el9')"/>
                                        <label class="labelname">Service Store: </label>
                                        <select style="width:200px;" class="dropdown" type="text" name="ServiceStore">
                                            <option value="all">All</option>
                                            <?php
                                            while ($one_result = mysqli_fetch_assoc($Service_Store_List)) {
                                                if($one_result['ServiceStore_Name'] == $one_show_result['ServiceStore_Name']) {
                                                ?>
                                                    <option value="<?php echo $one_result['ServiceStore_Id']; ?>" selected><?php echo $one_result['ServiceStore_Name'];?></option>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <option value="<?php echo $one_result['ServiceStore_Id']; ?>"><?php echo $one_result['ServiceStore_Name'];?></option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="Update_Field" style="display:none;">
                                    <h3 class='box-head'><label>Update</label></h3>
                                    <div id="el10">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el10')"/>
                                        <label class="labelname">Item Name: </label>
                                        <input class="inputstyle" type="text" name='u_Item_Name'>
                                    </div>
                                    <div id="el11">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el11')"/>
                                        <label class="labelname">Price: </label>
                                        <input class="inputstyle" type="text" name='u_Price'>
                                    </div>
                                    <div id="el12">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el12')"/>
                                        <label style="position:relative;top:-10px;" class="labelname">Item Picture: </label>
                                        <label style="position:relative;top:8px;" id="head_icon3" for="head_icon_input3">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input3" type="file" name='u_Item_Picture' hidden="hidden" onchange="Picture_Show3(this);">
                                        <label style="position:relative;top:8px;" id="head_icon4" for="head_icon_input4">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input4" type="file" name='u_Item_Detail_Picture' hidden="hidden" onchange="Picture_Show4(this);">
                                    </div>
                                    <div id="el13">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el13')"/>
                                        <label class="labelname">Working Time (Mins): </label>
                                        <input class="inputstyle" type="number" name='u_Working_Time'>
                                    </div>
                                    <div id="el14">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el14')"/>
                                        <label class="labelname">Rating: </label>
                                        <input class="inputstyle" type="number" name='u_Rating'>
                                    </div>
                                    <div id="el15">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el15')"/>
                                        <label class="labelname">Page View:</label>
                                        <input class="inputstyle" type="number" name='u_Page_View'>
                                    </div>
                                    <div id="el16">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el16')"/>
                                        <label class="labelname">Selected Quantities: </label>
                                        <input class="inputstyle" type="number" name='u_Selected_Quantities'>
                                    </div>
                                    <div id="el17">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el17')"/>
                                        <label class="labelname">Status: </label>
                                        <select class="dropdown" type="text" name="u_Status">
                                            <option value="nil">-----</option>
                                        <?php
                                            WHILE ($one_result = mysqli_fetch_assoc($u_Status_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['Status_Id']; ?>"><?php echo $one_result['Status'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="el18">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el18')"/>
                                        <label class="labelname">Category: </label>
                                        <select style="width:200px;" class="dropdown" type="text" name="u_Category">
                                            <option value="nil">-----</option>
                                            <?php
                                            WHILE ($one_result = mysqli_fetch_assoc($u_Category_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['Category_Id']; ?>"><?php echo $one_result['Category_Name'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="el19">
                                        <img class="redcross" src="images/article/red-cross.png" style="position:relative;width:12px;" onclick="disappear('el19')"/>
                                        <label class="labelname">Service Store: </label>
                                        <select style="width:200px;" class="dropdown" type="text" name="u_Service_Store">
                                            <option value="nil">-----</option>
                                            <?php
                                            WHILE ($one_result = mysqli_fetch_assoc($u_Service_Store_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['ServiceStore_Id']; ?>"><?php echo $one_result['ServiceStore_Name'];?></option>
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
                                        <label class="labelname">Item Name: </label>
                                        <input class="inputstyle" type="text" name='i_Item_Name'>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:-9px;width:12px;"/>
                                        <label style="position:relative;top:-10px;" class="labelname">Item Picture: </label>
                                        <label style="position:relative;top:8px;" id="head_icon" for="head_icon_input">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input" type="file" name='i_Item_Picture' hidden="hidden" onchange="Picture_Show(this);">
                                        <label style="position:relative;top:8px;" id="head_icon1" for="head_icon_input1">
                                            <img src="images/article/face_icon.png" width="50px" height="50px"/>
                                        </label>
                                        <input id="head_icon_input1" type="file" name='i_Item_Detail_Picture' hidden="hidden" onchange="Picture_Show1(this);">
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:-24px;width:12px;"/>
                                        <label class="labelname" style="position:relative;top:-25px;">Description: </label>
                                        <textarea class="inputstyle" type="text" name='i_Description' style="height:60px;width:250px;margin-top:10px;"></textarea>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Price: </label>
                                        <input class="inputstyle" type="text" name='i_Price'>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;width:12px;"/>
                                        <label class="labelname">Working Time (Mins): </label>
                                        <input class="inputstyle" type="number" name='i_Working_Time'>
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
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Status: </label>
                                        <select class="dropdown" type="text" name="i_Status">
                                        <?php
                                            WHILE ($one_result = mysqli_fetch_assoc($i_Status_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['Status_Id']; ?>"><?php echo $one_result['Status'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Category: </label>
                                        <select style="width:200px;" class="dropdown" type="text" name="i_Category">
                                            <?php
                                            WHILE ($one_result = mysqli_fetch_assoc($i_Category_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['Category_Id']; ?>"><?php echo $one_result['Category_Name'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <img class="redcross" src="images/article/star.png" style="position:relative;top:1px;width:12px;"/>
                                        <label class="labelname">Service Store: </label>
                                        <select style="width:200px;" class="dropdown" type="text" name="i_Service_Store">
                                            <?php
                                            WHILE ($one_result = mysqli_fetch_assoc($i_Service_Store_List)) {
                                            ?>
                                                <option value="<?php echo $one_result['ServiceStore_Id']; ?>"><?php echo $one_result['ServiceStore_Name'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <h3 class='box-head'><label>Show Fields</label></h3>
                                <div class="checkbox_box">
                                    <div><input type="checkbox" id="checkbox-1-1" class="regular-checkbox" name="ShowField[]" value="Item_Name" checked/><label for="checkbox-1-1"></label><span>Item Name&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-2" class="regular-checkbox" name="ShowField[]" value="Picture" /><label for="checkbox-1-2"></label><span>Picture&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-3" class="regular-checkbox" name="ShowField[]" value="Price" checked/><label for="checkbox-1-3"></label><span>Price&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-4" class="regular-checkbox" name="ShowField[]" value="Working_Time"/><label for="checkbox-1-4"></label><span>Working Time&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-5" class="regular-checkbox" name="ShowField[]" value="Rating"/><label for="checkbox-1-5"></label><span>Rating&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-6" class="regular-checkbox" name="ShowField[]" value="Page_View"/><label for="checkbox-1-6"></label><span>Page View&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-7" class="regular-checkbox" name="ShowField[]" value="Selected_Quantities"/><label for="checkbox-1-7"></label><span>Selected Quantities&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-8" class="regular-checkbox" name="ShowField[]" value="Status" checked/><label for="checkbox-1-8"></label><span>Status&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-9" class="regular-checkbox" name="ShowField[]" value="Category_Name" checked/><label for="checkbox-1-9"></label><span>Category&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-10" class="regular-checkbox" name="ShowField[]" value="ServiceStore_Name" checked/><label for="checkbox-1-10"></label><span>ServiceStore Name&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-11" class="regular-checkbox" name="ShowField[]" value="Updated"/><label for="checkbox-1-11"></label><span>Updated&nbsp;&nbsp;</span></div>
                                    <div><input type="checkbox" id="checkbox-1-12" class="regular-checkbox" name="ShowField[]" value="Created"/><label for="checkbox-1-12"></label><span>Created&nbsp;&nbsp;</span></div>
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
                                        while ($one_result = mysqli_fetch_assoc($search_result)) {
                                        ?> 
                                        <tr>
                                        <?php if(count($ShowField)>0){
                                        ?>
                                            <td>
                                                <form method="post" action="management_service.php">
                                                <input type="submit" class="myButton" id="Circle_btn" value="Update&nbsp;&gt;&gt;" />
                                                <input style="width:15px;" type="text" name="u_Item_Id" value="<?php echo $one_result["Item_Id"]; ?>" hidden/>
                                                </form>
                                                <form method="post" action="management_service.php" onsubmit="return confirm('Are you sure delete this record?')">
                                                <input type="submit" class="myButton" id="Circle_btn" value="Delete&nbsp;&gt;&gt;" />
                                                <input style="width:15px;" type="text" name="d_Item_Id" value="<?php echo $one_result["Item_Id"]; ?>" hidden/>
                                                </form>
                                            </td>
                                            <?php
                                            for ($i=0; $i<count($ShowField); $i++) {
                                                if($ShowField[$i] == "Picture") {
                                                ?>
                                                    <td><img src="<?php echo $one_result["Item_Picture"];?>" width="100px" />
                                                        <img src="<?php echo $one_result["Item_Detail_Picture"];?>" width="100px" />
                                                    </td>
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
    function Picture_Show(obj){
        var len = obj.files.length;
        for (var i =0 ; i < len ; i++){
            showimg(obj.files[i]);
        }
    }
    function showimg(img){
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
            document.getElementById('head_icon').appendChild(img);
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
</script>
            
<script>
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
</script>

<script>
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
</script>