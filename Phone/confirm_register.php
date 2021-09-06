<?php
    function doSaveData($Customer_Name, $Username, $target, $Password, $Gender, $Phone, $Address, $Email, $License_No) {
        
        $count = 0;
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());
        
        $conn = mysqli_connect("localhost", "root", "","db160015Q_project");
        
        $sql_username_search = "SELECT Username FROM customer";
        $username_search_result = mysqli_query($conn, $sql_username_search);
        while ($one_username_search_result = mysqli_fetch_assoc($username_search_result)) {
            if($one_username_search_result['Username'] == $Username) {
                header("Location:register.php?register=duplicate_Username");
            }
            else {
                $count++;
            }
        }
        
        if($count != 0) {
            $sql = "INSERT INTO customer (Customer_Name, Username, Customer_Picture, Password, Gender, Phone, Address, Email, License_No, Last_Login_Time, Updated, Created) VALUES ('$Customer_Name', '$Username', '$target', '$Password', '$Gender', '$Phone', '$Address','$Email', '$License_No', '$timestamp', '$timestamp', '$timestamp')";
        
            $result = mysqli_query($conn, $sql);

            if($result) {
                header("Location:login.php");
            }
            else {
                header("Location:register.php?register=fail");
            }
        }
        
        mysqli_close($conn);
    }

    if (isset($_POST['Customer_Name']) && !empty($_POST["Customer_Name"]) && isset($_POST['Username']) && !empty($_POST["Username"]) && !empty($_FILES['Customer_Picture']['tmp_name']) && isset($_POST['Password']) && !empty($_POST["Password"]) && isset($_POST['Gender']) && !empty($_POST["Gender"]) && isset($_POST['Phone']) && !empty($_POST["Phone"]) && isset($_POST['Address']) && !empty($_POST["Address"]) && isset($_POST['Email']) && !empty($_POST["Email"]) && isset($_POST['License_No']) && !empty($_POST["License_No"])) {
        
        $Customer_Name = $_POST['Customer_Name'];
        $Username = $_POST['Username'];
        $Password = $_POST['Password'];
        $Gender = $_POST['Gender'];
        $Phone = $_POST['Phone'];
        $Address = $_POST['Address'];
        $Email = $_POST['Email'];
        $License_No = $_POST['License_No'];
        
        $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

        if (in_array($_FILES["Customer_Picture"]["type"], $allowedType)) {    
            if ($_FILES["Customer_Picture"]["size"] < 5000000) { 
                $extension = pathinfo($_FILES['Customer_Picture']['name'], PATHINFO_EXTENSION);
                $target = "images/source/Customer/".$Username.".".$extension;
                $filename = "images/source/Customer/".$Username.".".$extension;

                $result = move_uploaded_file($_FILES["Customer_Picture"]["tmp_name"], $filename);
                if($result) {
                    doSaveData($Customer_Name, $Username, $target, $Password, $Gender, $Phone, $Address, $Email, $License_No);
                    session_start();
                    $_SESSION['Username'] = $Username;
                }
                else {
                    echo "<script>console.log('1');</script>";
                    header("Location:register.php?register=fail");
                }
            }
            else {
                echo "<script>console.log('2');</script>";
                header("Location:register.php?register=fail");
            }
        }
        else {
            echo "<script>console.log('3');</script>";
            header("Location:register.php?register=fail");
        }
    }
    else {
        echo "<script>console.log('4');</script>";
        header("Location:register.php?register=fail");
    }
?>