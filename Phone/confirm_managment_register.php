<?php
    function doSaveData($Administrator_Name, $Password) {
        
        $count = 0;
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());
        
        $conn = mysqli_connect("localhost", "root", "","db160015Q_project");
        
        $sql_username_search = "SELECT Administrator_Name FROM administrator";
        $administrator_name_search_result = mysqli_query($conn, $sql_administrator_name_search);
        while ($one_administrator_name_search_result = mysqli_fetch_assoc($administrator_name_search_result)) {
            if($one_administrator_name_search_result['Administrator_Name'] == $Administrator_Name) {
                header("Location:management_register.php?register=duplicate_Administrator_Name");
            }
            else {
                $count++;
            }
        }
        
        if($count == 0) {
            $Description = 'I am a New Administrator';
            $sql = "INSERT INTO administrator (Administrator_Name, Password, Description, Last_Region_Id, Last_Login_Time, Updated, Created) VALUES ('$Administrator_Name', '$Password', '$Description', 1, '$timestamp', '$timestamp', '$timestamp')";
        
            $result = mysqli_query($conn, $sql);

            if($result) {
                header("Location:management_login.php");
            }
            else {
                header("Location:management_register.php?register=fail");
            }
        }
        
        mysqli_close($conn);
    }

    if (isset($_POST['Administrator_Name']) && !empty($_POST["Administrator_Name"]) && isset($_POST['Password']) && !empty($_POST["Password"])) {
        
        $Administrator_Name = $_POST['Administrator_Name'];
        $Password = $_POST['Password'];

        doSaveData($Administrator_Name, $Password);
        session_start();
        $_SESSION['Administrator_Name'] = $Administrator_Name;
    }
    else {
        echo "<script>console.log('4');</script>";
        header("Location:management_register.php?register=fail");
    }
?>