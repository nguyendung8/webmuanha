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
   <title>Danh sách nhà đã đặt mua</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .borrow-container {
         display: flex;
         gap: 10px;
         flex-wrap: wrap;
      }
      .borrow-box {
         font-size: 19px;
         border: 2px solid #eee;
         border-radius: 4px;
         padding: 12px;
         box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
         width: 350px;
      }
      .borrow-box p {
         padding: 4px 0;
      }
      .cancel_ticket {
         border: 1px solid #ddd;
         padding: 4px 15px;
         border-radius: 4px;
         background: #ddb;
         color: red;
      }
      .cancel_ticket:hover {
         opacity: 0.8;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Danh sách nhà đã đặt mua của bạn</h1>

   <div class="borrow-container">

      <?php
         $order_query = mysqli_query($conn, "SELECT * FROM `pays` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($order_query) > 0){
            while($fetch_pays = mysqli_fetch_assoc($order_query)){
      ?>
      <div class="borrow-box">
         <p> ID người dùng : <span><?php echo $fetch_pays['user_id']; ?></span> </p>
         <p> ID nhà : <span><?php echo $fetch_pays['house_id']; ?></span> </p>
         <?php
            $house_id = $fetch_pays['house_id'];
            $room_query = mysqli_query($conn, "SELECT * FROM `houses` WHERE id = '$house_id'") or die('query failed');
            $fetch_room = mysqli_fetch_assoc($room_query)
         ?>
         <img width="250px" height="207px" src="uploaded_img/<?php echo $fetch_room['image']; ?>" alt="">
         <p> Địa chỉ : <span><?php echo $fetch_room['location']; ?> </span> </p>
         <p> Tổng tiền : <span><?php echo $fetch_room['price']; ?>  </span> </p>
         <p> Phương thức thanh toán: <span><?php echo $fetch_pays['payment'] ?></span> </p>
         <p> Ngày đặt mua:
            <span>
               <?php
                  $date_object = DateTime::createFromFormat('Y-m-d', $fetch_pays['date']);
                  echo $date_object->format('d-m-Y');
               ?>
            </span> 
         </p>
         <p style="margin-bottom: 10px;"> Trạng thái  : 
            <span style="color:<?php if($fetch_pays['is_confirmed'] == 1){ echo 'green'; }else if($fetch_pays['is_confirmed'] == '0'){ echo 'red'; }else{ echo 'orange'; } ?>;">
               <?php if ($fetch_pays['is_confirmed'] == 1) {
                     echo 'Đã xác nhận';
                  } else {
                     echo 'Chờ xác nhận';
                  }
               ?>
            </span> 
         </p>
      </div>
      <?php
       }
      }else{
         echo '<p class="empty">Chưa có nhà nào được đặt!</p>';
      }
      ?>
   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
<script>
   function confirmDelete() {
       return confirm("Bạn có chắc chắn muốn xóa vé này không?");
    }
</script>
</body>
</html>