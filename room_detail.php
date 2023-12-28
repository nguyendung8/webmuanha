<?php

   include 'config.php';

   session_start();

   $user_id = $_SESSION['user_id']; //tạo session người dùng thường

   if(!isset($user_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }
   $room_id = $_GET['room_id'];

   $sql = "SELECT * FROM rooms WHERE id = $room_id";
   $result = $conn->query($sql);
   $roomItem = $result->fetch_assoc()


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Xem thông tin phòng</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .view-book {
         padding: 15px;
      }
      .modal{
         width: 500px;
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
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="view-book">
   <?php if ($roomItem) : ?>
         <!-- Modal View Detail Book -->
      <div class="modal">
         <div class="modal-container">
            <h3 class="bookdetail-title"><?php echo($roomItem['name']) ?></h3>
            <div>
               <img class="bookdetail-img" src="uploaded_img/<?php echo $roomItem['image']; ?>" alt="">
            </div>
            <p class="bookdetail-author">
               Địa điểm: 
               <?php echo ($roomItem['location']) ?>
            </p>
            <p class="bookdetail-author">
               Giá phòng: 
               <?php  echo number_format($roomItem['price'],0,',','.' ); ?> đ/ tháng
            </p>
            <p style="margin-bottom: 15px;" class="bookdetail-author">
               Mô tả: 
               <?php echo ($roomItem['description']) ?>
            </p>
            <a href="book_ticket.php?room_id=<?php echo $roomItem['id'] ?>" class="borrow_book" >Thuê phòng</a>
         </div>
      </div>
   <?php else : ?>
      <p style="font-size: 20px; text-align: center;">Không xem được chi tiết phòng này</p>
   <?php endif; ?>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>