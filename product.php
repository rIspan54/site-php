<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="font-circe-master/css/font-circe.css">
    <link rel="stylesheet" href="style.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js" type="text/javascript"></script>
	<script src="package/dist/sweetalert2.all.min.js"></script>
	<script src="scripts.js"></script>
    <title>Магазин</title>
</head>
<?php
  $conn = mysqli_connect("localhost", "mysql", "mysql", "mydb");
  if($conn->connect_error){
          die("Ошибка: " . $conn->connect_error);
      }
  $id = (int)$_GET['id'];
  if($result = mysqli_query($conn, "SELECT p.*, mcat.category_id as main_category, category.name as cat_name, category.description as cat_description, img.url, img.alt  from product p
    INNER JOIN main_category mcat
    ON mcat.product_id = p.id
    INNER JOIN category
    ON category.id = mcat.category_id
    INNER JOIN main_image mimg
    ON mimg.product_id = p.id
    INNER JOIN img
    ON img.id = mimg.img_id
    WHERE p.id = $id and p.status = 1") and mysqli_num_rows($result))
    {foreach($result as $row){
        $title =  $row[title];
        $cost = $row[cost];
        $full_cost = $row[full_cost];
        $sale_cost = $row[sale_cost];
        $main_cat = $row[cat_name];
        $main_img = $row[url];
        $description = $row[description];
        $cat_id=$row[main_category];
    }}
  else {
    http_response_code(404);
    include("404.php");
    die();
  }?>
<body>
    <div class="main">
        <div class="main">
            <div class="block_photo">
                <?php if ($images = mysqli_query($conn,
                "SELECT pi.*, i.url, i.alt FROM product_img pi
                 JOIN img i
                 ON pi.img_id = i.id
                 WHERE pi.product_id = $id"))
                 {foreach($images as $img){
                       $url = $img[url];
                       echo '<img class="photo" src=' . $url . '>';
                   }
                 } ?>
            </div>
                <?php echo '<img src=' . $main_img . ' id="main"
                 style="padding-left: 30px; margin-top: 40px; padding-right: 90px; height: 492px;">' ?>

            <div style="margin-top: 40px;">
            <div class="text"><?php echo $title?></div>
            <div>
              <?php
               echo '<a href="#" class="link">' . $main_cat . '</a>';
               if ($categs = mysqli_query($conn,
              "SELECT pc.product_id ,c.*  FROM product_category pc
               JOIN category c
               ON pc.category_id = c.id
               WHERE product_id = $id"))
               {foreach($categs as $categ){
                     $cat_name = $categ[name];
                     echo '<a href="#" class="link">' . $cat_name . '</a>';
                 }
               } ?>
            </div>
            <div class="cost">
                <?php echo
                '<div style="padding-right: 40px; color: #9a9b9b;">
                    <s style="color: #bfbebe; padding-right: 10px;">' . $full_cost . '</s> ' . $cost . ' ₽</div>
                <div><b>' . $sale_cost . ' ₽</b> — с промокодом</div>'
                ?>
            </div>
            <div class=delivery>
                <div><img style="padding-left: 20px; padding-right: 15px; padding-top: 14px;" src="img/del_1.png">В наличи в магазине <a href="#">Lamoda</a></div>
                <div><img style="padding-left: 20px; padding-right: 15px; padding-top: 12px;" src="img/del_2.png">Бесплатная доставка</div>
            </div>
            <div class="produce_info">
                <input type="button" style="color: #d0d1d1;" id="minus" value="-">
                <input type="text" onkeyup="this.value = this.value.replace (/\D/gi, '').replace (/^0+/, '')" value="1" id="count" />
                <input type="button" id="plus" value="+">
            </div>
            <div>
                <input value="КУПИТЬ" type="button" name="shop" id="notification">
                <input value="В ИЗБРАННОЕ" type="button" name=like>
            </div>
            <?php
              echo '<div class="info">' . $description . '</div>';
            ?>
            <div class="repost">
                <label style="vertical-align: middle;">ПОДЕЛИТЬСЯ:</label>
                <a href="#"><img style="margin-left: 20px; vertical-align: middle;" src="img/vk.png"></a>
                <a href="#"><img style="margin-left: 10px; vertical-align: middle" src="img/g.png"></a>
                <a href="#"><img style="margin-left: 10px; vertical-align: middle" src="img/f.png"></a>
                <a href="#"><img style="margin-left: 10px; vertical-align: middle" src="img/t.png"></a>
                <img style="margin-left: 10px; vertical-align: middle" src="img/square.png" title="123">
            </div>
            <div style="margin-top: 30px; margin-left:130px;">
            <?php
              $id_pr = (int)($id/12);
              if ($id_pr/12!=0) {
                $id_pr ++;
              }
              if ($id_pr == 0) {
                $id_pr ++;
              }
              echo '<a href="/products.php?cat_id='.$cat_id.'&page=' . $id_pr . '" style="margin-top: 130px; font-size: 25px;">Назад</a>';
            ?>
            </div>
        </div>
    </div>
  </div>
</body>
</html>
