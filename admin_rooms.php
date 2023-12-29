<?php

   include 'config.php';

   session_start();

   $admin_id = $_SESSION['admin_id']; //tạo session admin

   if(!isset($admin_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }

   if(isset($_POST['add_room'])){//thêm sách mới từ submit form name='add_room'

      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $category = mysqli_real_escape_string($conn, $_POST['category']);
      $location = mysqli_real_escape_string($conn, $_POST['location']);
      $price = mysqli_real_escape_string($conn, $_POST['price']);
      $rate = mysqli_real_escape_string($conn, $_POST['rate']);
      $description = mysqli_real_escape_string($conn, $_POST['description']);
      $image = $_FILES['image']['name'];
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = 'uploaded_img/'.$image;

      $select_product_name = mysqli_query($conn, "SELECT name FROM `rooms` WHERE name = '$name'") or die('query failed');//truy vấn kiểm tra phòng đã tồn tại chưa

      if(mysqli_num_rows($select_product_name) > 0){
         $message[] = 'Phòng đã tồn tại.';
      }else{//chưa thì thêm mới
         $add_room_query = mysqli_query($conn, "INSERT INTO `rooms`(name, cate_id, location, price, rate, description, image) VALUES('$name', '$category', '$location', '$price', '$rate', '$description', '$image')") or die('query failed');
         if($add_room_query){
            if($image_size > 2000000){//kiểm tra kích thước ảnh
               $message[] = 'Kích thước ảnh quá lớn, hãy cập nhật lại ảnh!';
            }else{
               move_uploaded_file($image_tmp_name, $image_folder);//lưu file ảnh xuống
               $message[] = 'Thêm phòng thành công!';
            }
         }else{
            $message[] = 'Thêm phòng không thành công!';
         }
      }
   }

   if(isset($_GET['delete'])){//xóa sách từ onclick <a></a> href='delete'
      $delete_id = $_GET['delete'];
      $delete_image_query = mysqli_query($conn, "SELECT image FROM `rooms` WHERE id = '$delete_id'") or die('query failed');
      $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
      unlink('uploaded_img/'.$fetch_delete_image['image']);//xóa file ảnh của sách cần xóa
      mysqli_query($conn, "DELETE FROM `rooms` WHERE id = '$delete_id'") or die('query failed');

      $message[] = 'Xóa phòng thành công!';
   }

   if(isset($_POST['update_product'])){//cập nhật sách từ form submit name='update_product'

      $update_p_id = $_POST['update_p_id'];
      $update_name = $_POST['update_name'];
      $update_price = $_POST['update_price'];
      $update_location = $_POST['update_location'];
      $update_category = $_POST['update_category'];

      mysqli_query($conn, "UPDATE `rooms` SET name = '$update_name', location = '$update_location', cate_id='$update_category', price='$update_price' WHERE id = '$update_p_id'") or die('query failed');

      // $message[] = 'Cập nhật phòng thành công!';
      header('location:admin_rooms.php');

   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Phòng</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Phòng</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Thêm phòng</h3>
      <input type="text" name="name" class="box" placeholder="Tên phòng" required>
      <select name="category" class="box">
         <?php
            $select_category= mysqli_query($conn, "SELECT * FROM `categories`") or die('Query failed');
            if(mysqli_num_rows($select_category)>0){
               while($fetch_category=mysqli_fetch_assoc($select_category)){
                  echo "<option value='" . $fetch_category['id'] . "'>".$fetch_category['cate_name']."</option>";
               }
            }
            else{
               echo "<option>Không có loại phòng nào.</option>";
            }
         ?>
      </select>
      <input type="text" name="location" class="box" placeholder="Địa chỉ" required>
      <input type="number" name="price" class="box" placeholder="Giá phòng / tháng" required>
      <textarea class="box" name="description" rows="6" placeholder="Mô tả"></textarea>
      <input type="number" min=1 max=5 name="rate" class="box" placeholder="Đánh giá sao" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
      <input type="submit" value="Thêm" name="add_room" class="btn">
   </form>

</section>

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `rooms`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
               <div style="height: -webkit-fill-available;" class="box">
                  <img width="180px" height="207px" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <a href="admin_rooms.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">Cập nhật</a>
                  <a href="admin_rooms.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Xóa phòng này?');">Xóa</a>
               </div>
      <?php
            }
      }else{
         echo '<p class="empty">Không có phòng nào được thêm!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){//hiện form update từ onclick <a></a> href='update'
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `rooms` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
               <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                  <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
                  <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Tên truyện">
                  <select name="update_category" class="box">
                     <?php
                        $cate_id = $fetch_update['cate_id'];
                        $select_category_name= mysqli_query($conn, "SELECT * FROM `categories` c WHERE c.id = $cate_id") or die('Truy vấn lỗi');
                        while($fetch_category_name=mysqli_fetch_assoc($select_category_name)){
                           echo"<option value='" .$fetch_category_name['id'] . "'>".$fetch_category_name['cate_name']."</option>";
                        }
                     ?>
                     <?php
                      $cate_id = $fetch_update['cate_id'];
                      $select_category_name= mysqli_query($conn, "SELECT * FROM `categories` c WHERE c.id = $cate_id") or die('Truy vấn lỗi');
                      $fetch_category_name=mysqli_fetch_assoc($select_category_name);
                      $select_category= mysqli_query($conn, "SELECT * FROM `categories`") or die('Truy vấn lỗi');
                        while($fetch_category=mysqli_fetch_assoc($select_category)){
                           if($fetch_category['cate_name']!=$fetch_category_name['cate_name']){
                              echo"<option value='" . $fetch_category['id'] . "'>".$fetch_category['cate_name']."</option>";
                           }
                        }
                     ?>
                  </select>
                  <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" class="box" required placeholder="Giá">
                  <input type="hidden" name="update_rate" min=1 max=5 value="<?php echo $fetch_update['rate']; ?>" class="box" required placeholder="Đánh giá">
                  <input type="text" name="update_location" value="<?php echo $fetch_update['location']; ?>" class="box" required placeholder="Địa chỉ">
                  <input type="submit" value="update" name="update_product" class="btn">
                  <input type="reset" value="cancel" id="close-update" class="option-btn">
               </form>
   <?php
            }
         }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>

<script src="js/admin_script.js"></script>
<script>
   document.querySelector('#close-update').onclick = () =>{
   document.querySelector('.edit-product-form').style.display = 'none';
   window.location.href = 'admin_rooms.php';
}
</script>
</body>
</html>