<?php
    function doSaveData($ServiceStore_Name, $Address, $Location_Id, $Phone, $Email, $Create_Time, $Target1, $Target2, $Target3, $Description, $Time1, $Time2, $Car_Model, $lat, $lng) {
        
        $count = 0;
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('y-m-d h:i:s',time());
        
        $Opening_Hours = "From ".$Time1."am to ".$Time2."pm";
        
        $conn = mysqli_connect("localhost", "root", "","db160015Q_project");
        
        $sql_servicestore_name_search = "SELECT ServiceStore_Name FROM servicestore";
        $servicestore_name_search_result = mysqli_query($conn, $sql_servicestore_name_search);
        while ($one_servicestore_name_search_result = mysqli_fetch_assoc($servicestore_name_search_result)) {
            if($one_servicestore_name_search_result['ServiceStore_Name'] == $ServiceStore_Name) {
                header("Location:recruitment.php?register=duplicate_ServiceStore_Name");
            }
            else {
                $count++;
            }
        }
        
        if($count != 0) {
            $sql = "INSERT INTO servicestore (ServiceStore_Name, Address, Location_Id, Phone, Email, Create_Time, Picture1, Picture2, Picture3, Description, Opening_Hours, Car_Model, Rating, Page_View, Selected_Quantities, lat, lng, Updated, Created) VALUES ('$ServiceStore_Name', '$Address', $Location_Id, '$Phone', '$Email', '$Create_Time', '$Target1','$Target2', '$Target3', '$Description', '$Opening_Hours', '$Car_Model', 5, 0, 0, $lat, $lng, '$timestamp', '$timestamp')";
            
            $result = mysqli_query($conn, $sql);

            if($result) {             
                header("Location:recruitment.php?register=success");
            }
            else {
                header("Location:recruitment.php?register=fail");
            }
        }
        mysqli_close($conn);
    }

    if (isset($_POST['ServiceStore_Name']) && !empty($_POST["ServiceStore_Name"]) && !empty($_FILES['Picture1']['tmp_name']) && !empty($_FILES['Picture2']['tmp_name']) && !empty($_FILES['Picture3']['tmp_name'])) {
        
        $ServiceStore_Name = $_POST['ServiceStore_Name'];
        $file_ServiceStore_Name =  preg_replace('/[\s　]/', '_', $ServiceStore_Name);
        $Address = $_POST['Address'];
        $Location_Id = $_POST['Location'];
        $Phone = $_POST['Phone'];
        $Email = $_POST['Email'];
        $Create_Time = $_POST['Create_Time'];
        $Picture1 = $_POST['Picture1'];
        $Picture2 = $_POST['Picture2'];
        $Picture3 = $_POST['Picture3'];
        $Description = $_POST['Description'];
        $Time1 = $_POST['Time1'];
        $Time2 = $_POST['Time2'];
        $f_Car_Model = $_POST['f_Car_Model'];
        $lat = $_POST['Lat'];
        $lng = $_POST['Lng'];
        
        $n_Car_Model = "";
        for($i=0; $i<count($f_Car_Model); $i++) {
            $n_Car_Model = $n_Car_Model.$f_Car_Model[$i].",";
        }
        $n_Car_Model = substr($n_Car_Model,0,strlen($n_Car_Model)-1); 
        
        $allowedType = array("image/gif", "image/jpg", "image/png", "image/jpeg");

        if (in_array($_FILES["Picture1"]["type"], $allowedType) && in_array($_FILES["Picture2"]["type"], $allowedType) && in_array($_FILES["Picture3"]["type"], $allowedType)) {    
            if ($_FILES["Picture1"]["size"] < 5000000 && $_FILES["Picture2"]["size"] < 5000000 && $_FILES["Picture3"]["size"] < 5000000) { 
                $extension1 = pathinfo($_FILES['Picture1']['name'], PATHINFO_EXTENSION);
                $target1 = "images/source/Store/".$file_ServiceStore_Name."_1.".$extension1;
                $filename1 = "images/source/Store/".$file_ServiceStore_Name."_1.".$extension1;
                
                $extension2 = pathinfo($_FILES['Picture2']['name'], PATHINFO_EXTENSION);
                $target2 = "images/source/Store/".$file_ServiceStore_Name."_2.".$extension2;
                $filename2 = "images/source/Store/".$file_ServiceStore_Name."_2.".$extension2;
                
                $extension3 = pathinfo($_FILES['Picture3']['name'], PATHINFO_EXTENSION);
                $target3 = "images/source/Store/".$file_ServiceStore_Name."_3.".$extension3;
                $filename3 = "images/source/Store/".$file_ServiceStore_Name."_3.".$extension3;

                $result1 = move_uploaded_file($_FILES["Picture1"]["tmp_name"], $filename1);
                $result2 = move_uploaded_file($_FILES["Picture2"]["tmp_name"], $filename2);
                $result3 = move_uploaded_file($_FILES["Picture3"]["tmp_name"], $filename3);
                if($result1 && $result2 && $result3)   {
                    doSaveData($ServiceStore_Name, $Address, $Location_Id, $Phone, $Email, $Create_Time, $target1, $target2, $target3, $Description, $Time1, $Time2, $n_Car_Model, $lat, $lng);
                }
                else {
                    echo "<script>console.log('1');</script>";
                    header("Location:recruitment.php?register=fail");
                }
            }
            else {
                echo "<script>console.log('2');</script>";
                header("Location:recruitment.php?register=fail");
            }
        }
        else {
            echo "<script>console.log('3');</script>";
            header("Location:recruitment.php?register=fail");
        }
    }
    else {
        echo "<script>console.log('4');</script>";
        header("Location:recruitment.php?register=fail");
    }
?>