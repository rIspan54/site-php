<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Товары</title>
    <link href="style.css" rel=" stylesheet" type=" text/css">
</head>
<body>
    <?php
    $conn = mysqli_connect("localhost", "mysql", "mysql", "mydb");
    $host = $_SERVER['REQUEST_URI'];
    if ($host == "/products.php" or $host == "/products.php/") {
      echo '<h1>Категории:</h1>';
      if($all_categ = mysqli_query($conn, "SELECT cat.id as id_cat, cat.name, COUNT(pr.category_id or mc.category_id) as count FROM category cat
            LEFT JOIN product_category pr
            ON cat.id = pr.category_id
            LEFT JOIN main_category mc
            ON cat.id = mc.category_id
            INNER JOIN product p
            ON mc.product_id = p.id or pr.product_id = p.id
            WHERE p.status = 1
            GROUP BY id_cat
            ORDER BY count DESC")){
              foreach($all_categ as $category){
            $category_name = $category[name];
            $category_id = $category[id_cat];
            $count_pr = $category[count];
            echo '<div><a href="/products.php?cat_id=' . $category_id . '&page=1" class="categ">' . $category_name . ' - ' . $count_pr . ' товаров</a> </div>';
          }}
      die();
    }
    if($conn->connect_error){
            die("Ошибка: " . $conn->connect_error);
        }
    $cat_id = (int)$_GET['cat_id'];
    $page = (int)$_GET['page'];
    if ($page == 0) {
      $page = 1;
    }
    $min_limit = 12*($page-1);
    $max_limit = 12*($page);
    $pages =  mysqli_query($conn, "SELECT * FROM `product_category` pc
          JOIN `main_category` mc
          ON mc.product_id = pc.product_id
          WHERE mc.category_id = $cat_id or pc.category_id = $cat_id");
    $pagesCount = mysqli_num_rows($pages);
    if ($pagesCount%12==0){
      $pagesCount = (int)($pagesCount/12);
    }
    else {
      $pagesCount = (int)($pagesCount/12)+1;
    }
    if($result_pr = mysqli_query($conn, "SELECT p.*, mcat.category_id as main_category, category.name as cat_name, category.description, img.url, img.alt FROM product_category pc
      INNER JOIN product p
      ON pc.product_id = p.id
      INNER JOIN main_category mcat
      ON mcat.product_id = p.id
      INNER JOIN category
      ON category.id = mcat.category_id
      INNER JOIN main_image mimg
      ON mimg.product_id = p.id
      INNER JOIN img
      ON img.id = mimg.img_id
      WHERE p.status = 1 and (pc.category_id = $cat_id or mcat.category_id = $cat_id)
      LIMIT $min_limit, $max_limit") and mysqli_num_rows($result_pr)){
        http_response_code(200);}

    else {
      http_response_code(404);
      include("404.php");
      die();
    }?>


     <?php
     $count_row = mysqli_num_rows($result_pr);
     if ($count_row%3 == 0){
       $count = (int)($count_row / 3);
     }
     else {
       $count = (int)($count_row / 3) + 1;
     }
     $main_id = 0;
     for ($i = 1; $i <= $count; $i++) {
         $string = '<div class="container">';
         for ($j = 1; $j <= 3; $j++) {
           $row = $result_pr->fetch_assoc();
           $id_pr = $row[id];
           $title = $row[title];
           $url = $row[url];
           $alt = $row[alt];
           if ($main_id != $id_pr) {
              $string .= '
              <div class="object">
              <a href="/product.php?id=' . $id_pr . '"><img src="' . $url . '" alt ="' . $alt . '" id="card"></a>
              <h4><a href="/product.php?id=' . $id_pr . '">' . $title . '</a></h4>
              </div>';}
         }
         $string .= '</div>';
         echo $string;
     }
      ?>
    <div style="text-align: center; font-size: 30px;">
      <?php for ($pageNum = 1; $pageNum <= $pagesCount; $pageNum++): ?>
          <a href=/products.php?cat_id=<?=$cat_id?>&page=<?=$pageNum?> id="<?=$pageNum?>""><?= $pageNum ?></a>
      <?php endfor; ?>
    </div>
</body>
</html>
