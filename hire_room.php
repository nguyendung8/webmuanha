<?php

   include 'config.php';

   session_start();

   $user_id = $_SESSION['user_id']; //tạo session người dùng thường

   if(!isset($user_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }
   $room_id = $_GET['room_id'];

   // Lấy ra thông tin sách
   $sql = "SELECT * FROM rooms WHERE id = $room_id";
   $result = $conn->query($sql);
   $roomItem = $result->fetch_assoc();

   // Lấy ra thông tin student
   $sql1 = "SELECT * FROM users WHERE id = $user_id";
   $result1 = $conn->query($sql1);
   $user = $result1->fetch_assoc();

   // Lúc click vào nút mượn
   if(isset($_POST['submit'])) {
      $roomId = $room_id;
      $studentId = $user_id;
      $payment = $_POST['method'];
      $date = date('Y-m-d');
      if($roomItem['is_hired'] == 1) {
         $message[] = 'Phòng đã có người thuê!';
      } else {
         $hire = mysqli_query($conn, "INSERT INTO `pays`(room_id, student_id, payment, date) VALUES('$roomId', '$studentId', '$payment','$date')") or die('query failed');
         if($hire) {
            $message[] = 'Thuê phòng thành công!';
         } else {
            $message[] = 'Thuê phòng không thành công!';
         }
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thuê phòng</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .view-book {
         padding: 15px;
      }
      .modal{
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
      .roomdetail-title {
         font-size: 21px;
         padding-top: 10px;
         color: #9e1ed4;
      }
      .roomdetail-img {
         margin-top: 18px;
         width: 369px;
         height: 226px;
      }
      .roomdetail-author {
         margin-top: 19px;
         font-size: 20px;
      }
      .roomdetail-desc {
         margin-top: 20px;
         font-size: 16px;
      }
      .form-item {
         display: flex;
         align-items: center;
         justify-content: space-evenly;
         padding: 0 15px;
      }
      .form-item span {
         font-size: 18px;
         flex: 0.5;
      }
      .form-item input {
         border: 1px solid #eee !important;
         padding: 7px 18px;
         margin-top: 4px;
         flex: 1;
         font-size: 15px;
      }
      .book_ticket_input {
         display: flex;
         flex-direction: column;
         align-items: center;
      }
      .borrow-btn {
         margin-top: 21px;
         padding: 8px;
         border-radius: 4px;
         background: #1ed224;
         color: #fff;
         font-size: 20px;
         cursor: pointer;
      }
      .borrow-btn:hover {
         opacity: 0.8;
      }
      .form_input {
         margin-top: 13px;
         font-size: 20px;
      }
      .select_method {
         border: 1px solid #ddd;
         padding: 5px 13px;
         font-size: 19px;
         cursor: pointer;
      }
      .star_icon {
         color: #ffb700;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="view-book">
   <?php if ($roomItem) : ?>
         <!-- Modal View Detail Book -->
      <form class="modal" method="post">
         <div class="modal-container">
            <h3 class="roomdetail-title"><?php echo($roomItem['name']) ?></h3>
            <div>
               <img class="roomdetail-img" src="uploaded_img/<?php echo $roomItem['image']; ?>" alt="">
            </div>
            <p class="roomdetail-author">
               Đánh giá: 
               <?php if($roomItem['rate'] == 1) {  ?>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <?php } else if($roomItem['rate'] == 2) { ?>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <?php } else if($roomItem['rate'] == 3) {  ?>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
                  <i class="fa fa-star star_icon" aria-hidden="true"></i>
               <?php } else if($roomItem['rate'] == 4) {  ?>
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
            <p class="roomdetail-author">
               Giá phòng: 
               <?php echo number_format($roomItem['price'],0,',','.' ); ?> đ/ tháng
            </p>
            <p class="roomdetail-author">
               Địa điểm: 
               <?php echo ($roomItem['location']) ?>
            </p>
               <div class="form_input">
                  <span for="">Phương thức thanh toán: </span>
                  <select class="select_method" name="method">
                     <option value="Tiền mặt khi nhận hàng">Tiền mặt khi nhận hàng</option>
                     <option value="Thẻ ngân hàng">Thẻ ngân hàng</option>
                     <option value="Paypal">Paypal</option>
                  </select>
               </div>
            <input class="borrow-btn" name="submit" type="submit" value="Thuê phòng">
         </div>
      </form>
   <?php else : ?>
      <p style="font-size: 20px; text-align: center;">Không thuê được phòng này</p>
   <?php endif; ?>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>