<?php

   include 'config.php';

   session_start();

   $admin_id = $_SESSION['admin_id']; //tạo session admin

   if(!isset($admin_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   };
   
   // Click duyệt
   if(isset($_POST['confirmed'])) {
      $room_id = $_POST['room_id'];
      $hire_room_id = $_POST['hire_room_id'];
      
      // Lấy thông tin phòng
      $sql = "SELECT * FROM rooms WHERE id = $room_id";
      $result = $conn->query($sql);
      $roomItem = $result->fetch_assoc();

      //Kiểm tra xem đã có ai thuê chưa
      if($roomItem['is_hired'] == 1) {
         $message[] = 'Phòng này đã được thuê!';
      } else {
         mysqli_query($conn, "UPDATE rooms SET is_hired = 1 WHERE id = $room_id;") or die('query failed');
         mysqli_query($conn1, "UPDATE pays SET is_confirmed = 1 WHERE id = $hire_room_id;") or die('query failed');
         $message[] = 'Xác nhận phiếu thuê thành công!';
      }
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Phiếu thuê phòng</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
      .confirm-btn {
         margin-top: 16px;
         padding: 7px 16px;
         border-radius: 4px;
         font-size: 18px;
         color: #fff;
         cursor: pointer;
      }
      .confirm-btn:hover {
         opacity: 0.8;
      }
      p {
         font-size: 17px !important;
      }
   </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="orders">

   <h1 class="title">Danh sách phiếu thuê phòng</h1>
   <div class="box-container">
      <?php
         $select_orders = mysqli_query($conn, "SELECT * FROM `pays`") or die('query failed');
         if(mysqli_num_rows($select_orders) > 0){
            while($fetch_pays = mysqli_fetch_assoc($select_orders)){
      ?>
               <div style="height: -webkit-fill-available; text-align: center;" class="box">
                  <p> Student id : <span><?php echo $fetch_pays['student_id']; ?></span> </p>
                  <p> Room id : <span><?php echo $fetch_pays['room_id']; ?></span> </p>
                  <?php
                     $room_id = $fetch_pays['room_id'];
                     $room_query = mysqli_query($conn, "SELECT * FROM `rooms` WHERE id = '$room_id'") or die('query failed');
                     $fetch_room = mysqli_fetch_assoc($room_query)
                  ?>
                  <img width="250px" height="170px" src="uploaded_img/<?php echo $fetch_room['image']; ?>" alt="">
                  <p> Tổng tiền : <span><?php echo number_format($fetch_room['price'],0,',','.' ); ?> đ </span> </p>
                  <p> Phương thức thanh toán: <span><?php echo $fetch_pays['payment'] ?></span> </p>
                  <p> Ngày thuê phòng:
                     <span>
                        <?php
                           $date_object = DateTime::createFromFormat('Y-m-d', $fetch_pays['date']);
                           echo $date_object->format('d-m-Y');
                        ?>
                     </span> 
                  </p>
                  <p style="margin-top: 10px;"> Trạng thái  : 
                     <span style="color:<?php if($fetch_pays['is_confirmed'] == 1){ echo 'green'; }else if($fetch_pays['is_confirmed'] == '0'){ echo 'red'; }else{ echo 'orange'; } ?>;">
                        <?php if ($fetch_pays['is_confirmed'] == 1) {
                              echo 'Đã xác nhận';
                           } else {
                              echo 'Chờ xác nhận';
                           }
                        ?>
                     </span> 
                  </p>
                  <form action="" method="post">
                     <input type="hidden" name="room_id" value="<?php echo $fetch_pays['room_id'] ?>">
                     <input type="hidden" name="hire_room_id" value="<?php echo $fetch_pays['id'] ?>">
                     <?php if($fetch_pays['is_confirmed'] == 0) { ?>
                        <input style="background: #1f7bc4" class="confirm-btn" type="submit" value="Xác nhận" name="confirmed">
                     <?php } ?>
                  </form>
               </div>
      <?php
            }
         }else{
            echo '<p class="empty">Không có phiếu thuê phòng nào!</p>';
         }
      ?>
   </div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>