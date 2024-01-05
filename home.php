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
   <title>Trang chủ</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="./css/main.css">
   <style>
      .list-cate {
         text-align: center;
         font-size: 20px;
         display: flex;
         gap: 10px;
         justify-content: center;
         border: 1px solid #ddd;
         margin: auto;
         margin-bottom: 20px;
         padding: 14px;
         width: fit-content;
         border-radius: 3px;
         box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
      }
      .list-cate a {
         border-right: 1px solid #ddd;
         padding-right: 11px;
         color: #e22121;
      }
      .list-cate a:hover {
         opacity: 0.7;
      }
      .list-cate a:last-child {
         border-right: 0;
         padding: 0;
      }
      .slideshow-container {
         position: relative;
         max-width: 800px;
         margin: 0 auto;
         overflow: hidden; /* Để ẩn phần ngoài khung hình ảnh */
      }
      .slide {
         display: none;
         animation: fade 2s ease-in-out infinite; /* Sử dụng animation để thêm hiệu ứng lướt sang */
      }
      @keyframes fade {
         0%, 100% {
            opacity: 0;
         }
         25%, 75% {
            opacity: 1;
         }
      }
      .slide img {
         width: 100%;
         height: 485px;
         border-radius: 9px;
      }
      .borrow_book:hover { 
         opacity: 0.9;
      }
      .borrow_book {
         padding: 5px 25px;
         background-image: linear-gradient(to right, #ff9800, #F7695D);
         border-radius: 4px;
         cursor: pointer;
         font-size: 20px;
         color: #fff;
         font-weight: 700;
      }
      .home-banner {
         min-height: 70vh;
         background:linear-gradient(rgba(0,0,0,.1), rgba(0,0,0,.1)), url(./images/home_background.png) no-repeat;
         background-size: cover;
         background-position: center;
         display: flex;
         align-items: center;
         justify-content: center;
      }
      .list_rate {
         display: flex;
         gap: 4px;
         justify-content: center;
         margin-top: 8px;
      }
      .star_icon {
         color: #ffb700;
         font-size: 18px !important;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="home home-banner">

   <div class="content">
      <div class="slideshow-container">
         <div class="slide fade">
            <img src="./images/slider1.jpg" alt="slide 1">
         </div>
         <div class="slide fade">
            <img src="./images/slider2.jfif" alt="slide 2">
         </div>
         <div class="slide fade">
            <img src="./images/slider3.jfif" alt="slide 3">
         </div>
         <div class="slide fade">
            <img src="./images/slider3.jfif" alt="slide 3">
         </div>
      </div>
   </div>

</section>

<section class="products">

   <h1 class="title">Danh sách nhà cho thuê</h1>
   <div class="list-cate">
      <?php  
         $select_categoriess = mysqli_query($conn, "SELECT * FROM `categories`") or die('query failed');
         if(mysqli_num_rows($select_categoriess) > 0){
            while($fetch_categoriess = mysqli_fetch_assoc($select_categoriess)){
      ?>
                  <a href="?cate_id=<?php echo $fetch_categoriess['id']; ?> "><?php echo $fetch_categoriess['cate_name']; ?></a>
      <?php
            }
         }else{
            echo '<p class="empty">Chưa có danh mục nào!</p>';
         }
      ?>
   </div>
   <div class="box-container">
      <?php
      if(isset($_GET['cate_id'])) {
         $cate_id = $_GET['cate_id'];
      } else {
         $cate_id = 16;
      }
         $select_products = mysqli_query($conn, "SELECT r.* FROM houses r JOIN categories c ON r.cate_id = c.id  WHERE cate_id = $cate_id") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_houses = mysqli_fetch_assoc($select_products)){
      ?>
         <form style="height: -webkit-fill-available;" action="" method="post" class="box">
            <img width="230px" height="200px" src="uploaded_img/<?php echo $fetch_houses['image']; ?>" alt="">
            <div class="list_rate">
            <?php if($fetch_houses['rate'] == 1) {  ?>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
            <?php } else if($fetch_houses['rate'] == 2) { ?>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
            <?php } else if($fetch_houses['rate'] == 3) {  ?>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
            <?php } else if($fetch_houses['rate'] == 4) {  ?>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
            <?php } else {  ?>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <i class="fa fa-star star_icon" aria-hidden="true"></i>
            <?php } ?>
         </div>
            <div class="name">
               <?php  if($fetch_houses['rate'] == 5) { ?>
                  <img src="./images/vip.gif" alt="" class="vip-icon">
               <?php } ?>
               <?php echo $fetch_houses['name']; ?>
            </div>
            <div class="book-action">
               <a href="house_detail.php?house_id=<?php echo $fetch_houses['id'] ?>" class="view-book" >Xem thông tin nhà</a>
               <a href="hire_room.php?house_id=<?php echo $fetch_houses['id'] ?>" class="borrow_book" >Mua nhà</a>
            </div>
         </form>
      <?php
            }
         }else{
            echo '<p class="empty">Chưa có nhà nào để mua!</p>';
         }
      ?>
   </div>

</section>

<section class="home-contact">

   <div class="content">
      <h3>Bạn có thắc mắc?</h3>
      <p>Hãy để lại những điều bạn còn thắc mắc, băn khoăn hay muốn chia sẻ thêm về những loại nhà cho chúng mình tại đây để chúng mình có thể giải đáp giúp bạn</p>
      <a href="contact.php" class="white-btn">Liên hệ</a>
   </div>

</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
<script src="./js/slide_show.js" ></script>

</body>
</html>