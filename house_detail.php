<?php

   include 'config.php';

   session_start();

   $user_id = $_SESSION['user_id']; //tạo session người dùng thường

   if(!isset($user_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }
   $house_id = $_GET['house_id'];

   $sql = "SELECT * FROM houses WHERE id = $house_id";
   $result = $conn->query($sql);
   $houseItem = $result->fetch_assoc()


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Xem thông tin nhà</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .view-book {
         padding: 15px;
      }
      .modal{
         padding: 0 15px;
         width: 650px;
         margin: auto;
         border: 2px solid #eee;
         padding-bottom: 27px;
         box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
         border-radius: 5px;
      }
      .modal-container{
         background-color:#fff;
         text-align: center;
      }
      .bookdetail-title {
         font-size: 21px;
         padding-top: 10px;
         color: #9e1ed4;
      }
      .bookdetail-img {
         margin-top: 18px;
         width: 300px;
         height: 230px;
      }
      .bookdetail-author {
         margin-top: 19px;
         font-size: 20px;
      }
      .bookdetail-desc {
         margin: 20px 10px;
         font-size: 16px;
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
      .star_icon {
         color: #ffb700;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="view-book">
   <?php if ($houseItem) : ?>
         <!-- Modal View Detail Book -->
      <div class="modal">
         <div class="modal-container">
            <h3 class="bookdetail-title"><?php echo($houseItem['name']) ?></h3>
            <div>
               <img class="bookdetail-img" src="uploaded_img/<?php echo $houseItem['image']; ?>" alt="">
            </div>
            <p class="bookdetail-author">
               Đánh giá: 
               <?php if($houseItem['rate'] == 1) {  ?>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <?php } else if($houseItem['rate'] == 2) { ?>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <?php } else if($houseItem['rate'] == 3) {  ?>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <?php } else if($houseItem['rate'] == 4) {  ?>
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
            </p>
            <p class="bookdetail-author">
               Địa điểm: 
               <?php echo ($houseItem['location']) ?>
            </p>
            <p class="bookdetail-author">
               Diện tích:
               <span style="color: red;">
                  <?php echo ($houseItem['area']) ?>
               </span> 
            </p>
            <p class="bookdetail-author">
               Giá nhà:
               <span style="color: red;">
                  <?php echo $houseItem['price']; ?>
               </span> 
            </p>
            <p style="margin-bottom: 15px;" class="bookdetail-author">
               Mô tả: 
               <?php echo ($houseItem['description']) ?>
            </p>
            <a href="hire_room.php?house_id=<?php echo $houseItem['id'] ?>" class="borrow_book" >Mua nhà</a>
         </div>
      </div>
   <?php else : ?>
      <p style="font-size: 20px; text-align: center;">Không xem được chi tiết nhà này</p>
   <?php endif; ?>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>