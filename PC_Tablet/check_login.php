<?php
	session_start();

    function CheckLogin($Name, $Password) {
        
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());
        
        $conn = mysqli_connect("localhost", "root", "","db160015q_project" );
        $user_sql = "SELECT * FROM customer WHERE Username = '$Name' and Password ='$Password'";
        $user_result = mysqli_query($conn, $user_sql);
        $userfound = mysqli_num_rows($user_result);
        
        if($userfound >= 1) {
            $one_result = mysqli_fetch_assoc($user_result);
            $Customer_Id = $one_result['Customer_Id'];
            
            $u_sql = "UPDATE customer SET Last_Login_Time = '$timestamp' WHERE Username = '$Name'";
            $update_time = mysqli_query($conn, $u_sql);
            
            $cart_sql = "SELECT B.Item_Id, B.Item_Name, C.ServiceStore_Id, C.ServiceStore_Name FROM cart AS A INNER JOIN serviceitem AS B ON A.Item_Id = B.Item_Id INNER JOIN servicestore AS C ON A.ServiceStore_Id = C.ServiceStore_Id WHERE A.Customer_Id = $Customer_Id";
            $cart_result = mysqli_query($conn, $cart_sql);
            $one_cart_result = mysqli_fetch_assoc($cart_result);
            $_SESSION['Item_Id'] = $one_cart_result['Item_Id'];
            $_SESSION['Item_Name'] = $one_cart_result['Item_Name'];
            $_SESSION['ServiceStore_Id'] = $one_cart_result['ServiceStore_Id'];
            $_SESSION['ServiceStore_Name'] = $one_cart_result['ServiceStore_Name'];
            $_SESSION['Username'] = $Name;
            $Item_Id = $_SESSION['Item_Id'];
            $Item_Name = $_SESSION['Item_Name'];
            $ServiceStore_Id = $_SESSION['ServiceStore_Id'];
            $ServiceStore_Name = $_SESSION['ServiceStore_Name'];
            
            header("Location:index.php");
        } 
        else {
            $admin_sql = "SELECT * FROM administrator WHERE Administrator_Name = '$Name' and Password ='$Password'";
            $admin_result = mysqli_query($conn, $admin_sql); // search table NOW! 
            $adminfound = mysqli_num_rows($admin_result);
            
            if($adminfound >= 1) {
                $_SESSION['Administrator_Name'] = $Name;
                
                $u_sql = "UPDATE administrator SET Last_Login_Time = '$timestamp' WHERE Administrator_Name = '$Name' and Password ='$Password'";
                $update_time = mysqli_query($conn, $u_sql);
                
                header("Location:management.php");
            }
            else {
                header("Location:login.php?Login=fail");
            }
        }
        mysqli_close($conn);
    }

    if(isset($_POST['Username']) && $_POST['Password']) {
        
        $Name = $_POST['Username'];
        $Password = $_POST['Password'];
        CheckLogin($Name, $Password);
    }
    else {
        header("Location:login.php?login=fail");
    }
?>
