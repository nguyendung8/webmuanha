<?php
   include 'config.php';

   session_start();

   $user_id = $_SESSION['user_id']; //tạo session người dùng thường

   if(!isset($user_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thông tin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="about">

   <div class="flex">

      <div class="image">
         <img height="390px" style="border-radius: 3px;" src="images/about-img.jpg" alt="">
      </div>

      <div class="content">
         <h3>Tại sao lại có BĐS Quỳnh Quỳnh?</h3>
         <p>Thuận tiện và Nhanh chóng.</p>
         <p> Người mua có thể đọc đánh giá và nhận xét từ người khác về bất động sản hoặc người cho thuê trên các trang web này, giúp họ đưa ra quyết định thông tin và chân thực.</p>
         <a href="contact.php" class="btn">Liên hệ</a>
      </div>

   </div>

</section>

<section class="authors">

   <h1 class="title">Thành viên của BĐS Quỳnh Quỳnh</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/info_img.jfif" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-instagram"></a>
         </div>
         <h3>Như Quỳnh</h3>
      </div>
   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>