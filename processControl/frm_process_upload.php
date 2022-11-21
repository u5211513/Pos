<?php
ob_start();
session_start();
error_reporting(E_ALL ^ E_NOTICE);
if ($_SESSION["USERID"] == "") {
    echo "<script>alert('Please Log In.');</script>";
    echo "<script>location.replace('../frm_login.php');</script>";
}
include("../inc/fun_connect.php");
$USERID             = $_SESSION["USERID"];
$datetime_cur       = date("Y-m-d H:i:s");
// if(trim($_FILES["fileupload"]["tmp_name"]) != ""){
//     $images         = $_FILES["fileUpload"]["tmp_name"];
//     $images_file    = $_FILES["fileUpload"]["type"];
//     $images_time    = date('YmdHis'); 
//     $Pic_name       = rand(1,9999999);
//     if( $images_file == "image/gif" ) {
//         $filename   = $Pic_name.".gif";
//     }
//     if( $images_file == "image/png" ) {
//         $filename   = $Pic_name.".png";
//     }
//     if (($images_file =="image/jpg")||($images_file=="image/jpeg")||($images_file=="image/pjpeg")) {
//         $filename   = $Pic_name.".jpg";
//     }
//     $new_images         = "$images_time"."$filename";
//     $new_images_GN      = "B_"."$images_time"."$filename";
//     copy($_FILES["fileUpload"]["tmp_name"][$i],"../manageWeb/banner/".$new_images_GN);
//     $width              = 350;  
//     $size               = GetimageSize($images);
//     $height=round($width*$size[1]/$size[0]);
//     if( $images_file == "image/gif" ) 	{
//         $images_orig = ImageCreateFromGIF($images);
//     }
//     if( $images_file == "image/png" ) {
//         $images_orig = ImageCreateFromPNG($images);
//     }
//     if (($images_file=="image/jpg")||($images_file=="image/jpeg")||($images_file=="image/pjpeg")) {
//         $images_orig = ImageCreateFromJPEG($images);
//     }
//     $photoX         = ImagesX($images_orig);
//     $photoY         = ImagesY($images_orig);
//     $images_fin     = ImageCreateTrueColor($width, $height);
//     ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
//     ImageGIF($images_fin,"../upload/".$new_images);
//     ImageJPEG($images_fin,"../upload/".$new_images);
//     ImageDestroy($images_orig);
//     ImageDestroy($images_fin); 



//     //////////////////////////
//     if(trim($_FILES["fileupload"]["tmp_name"]) != ""){
//         $name = $_FILES['file']['name'];
// 		$target_dir = "../images/NewFleet/";
// 		$target_file = $target_dir . basename($_FILES["file"]["name"]);
// 		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// 		$extensions_arr = array("jpg","jpeg","png","gif"); 
// 		if(in_array($imageFileType,$extensions_arr) ){ 
// 			$image_base64 = base64_encode(file_get_contents($_FILES['file']['tmp_name']) );
// 			$image = 'data:../images/NewFleet/'.$imageFileType.';base64,'.$image_base64; 
// 			// Insert record
// 			// $data = array(  
// 			// 	"group_Images" => $image, 
// 			// 	"group_id" => $_POST["group_id"]
// 			// );
// 			// updateImageCarByGroup($data);

// 			// $query = "insert into images(image) values('".$image."')";
// 			// mysqli_query($con,$query); 
// 			move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$name);
// 		} 
//     }


//     ///////////////////////
//     $data = array(

//     );
// } 

if (!empty($_FILES['uploadfile']['name'][0])) {
    $errors = array();
    foreach ($_FILES['uploadfile']['tmp_name'] as $key => $tmp_name) {
        $file_name      = $_FILES['uploadfile']['name'][$key];
        $file_size      = $_FILES['uploadfile']['size'][$key];
        $file_tmp       = $_FILES['uploadfile']['tmp_name'][$key];
        $file_type      = $_FILES['uploadfile']['type'][$key];
        $detail         = $_POST["detail"][$key];
        $USER_UID       = $_POST["USER_UID"];
        if (!empty($file_name)) {
            $file           = strtolower($_FILES["uploadfile"]["name"][$key]);
            $sizefile       = $_FILES["uploadfile"]["size"][$key];
            $type           = strrchr($file, "."); 
            $img_time       = date('dmyHis') . $key;
            $new_name       = "F_" . $img_time . $type; 
            $newimages      = $file_name;
            $desired_dir    = "../upload/" . $new_name;

            if (is_uploaded_file($_FILES['uploadfile']['tmp_name'][$key]) && $_FILES['uploadfile']['error'][$key] == 0) {
                $path = $desired_dir;
                if (!file_exists($path)) {
                    if (move_uploaded_file($_FILES['uploadfile']['tmp_name'][$key], $path)) {
                        if ($_POST['file_name'] != '') {
                            $file = "../upload/" . $_POST['file_name'];
                            unlink($file);
                        }
                       
                        $add__img        = " INSERT INTO TB_IMAGES (IMAGE_NAME ,IMAGE_DETAIL, USERID , USER_CREID, CREATEDATE) ";
                        $add__img        .= " VALUES('".$new_name."','".$detail."','".$USER_UID."','".$USERID."','".$datetime_cur."')";
                        $insert__img     = $conn_1->query($add__img); 
                       
                        echo "<script>location.replace('../operation_sys/frm_Assest_outin.php?IDD=".$USER_UID."');</script>"; 
                    }
                }
            }
        }
    }
}
