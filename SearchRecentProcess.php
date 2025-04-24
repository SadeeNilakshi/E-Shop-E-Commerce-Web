<?php
session_start();
 require "connection.php";

 if(isset($_SESSION["u"])){
    if(isset($_GET["r"])){

  $search = $_GET["r"];
  $user = $_SESSION["u"]["email"];

  $p_rs = Database::search("SELECT * FROM `product` WHERE `title` LIKE '%".$search."%'");
  $pid = $p_rs->fetch_assoc();

  $watch_rs = Database::search("SELECT * FROM `recent` WHERE `user_email`='".$user."' 
  AND `product_id` = '".$pid["id"]."'");
  
  $watch_num = $watch_rs->num_rows;
  if($watch_num == 0){
    echo ("no such product in the watchlist");

    }else{

        for($x=0;$x< $watch_num; $x++){
            $watch_data = $watch_rs->fetch_assoc();

            $product_rs = Database::search("SELECT* FROM `product` WHERE `id`='".$watch_data["product_id"]."'");
            $product_data = $product_rs->fetch_assoc();

            $image_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $watch_data["product_id"] . "'");
            $image_data = $image_rs->fetch_assoc();

            $color_rs = Database::search("SELECT * FROM `color` WHERE `clr_id`='" . $product_data["color_clr_id"] . "'");
            $color = $color_rs->fetch_assoc();

            $condition_rs = Database::search("SELECT * FROM `condition` WHERE `condition_id`='" . $product_data["condition_condition_id"] . "'");
            $condition = $condition_rs->fetch_assoc();

  }
 
  ?>


  <div class="card mb-3 mx-lg-2 col-12 " style="max-width: 1040px;" >
  
  <div class="row g-0">
      <div class="col-md-4">
      <img src="<?php echo $image_data["img_path"]; ?>" class="img-fluid rounded-start p-1" >
      </div>
   <div class="col-md-5">
      <div class="card-body">
        <h5 class="card-title fw-bold" id="s"><?php echo $product_data["title"] ?></h5>
        <span class="fs-5 fw-bold text-black-50">Colour : <?php echo $color["clr_name"]; ?></span>
        &nbsp;&nbsp; | &nbsp;&nbsp;
        <span class="fs-5 fw-bold text-black-50">Condition : <?php echo $condition["condition_name"]; ?></span><br/>
        <span class="fs-5 fw-bold text-black-50">Price :</span>&nbsp;&nbsp;
        <span class="fs-5 fw-bold text-black-50">Rs. <?php echo $product_data["price"] ?> .00</span><br/>
        <span class="fs-5 fw-bold text-black-50">Quantity :</span>&nbsp;&nbsp;
        <span class="fs-5 fw-bold text-black-50"><?php echo $product_data["qty"] ?> items Available</span><br/>
                          
      </div>
   </div>
   <div class="col-md-3 mt-5">
      <div class="card-body d-grid">
          <a href="<?php echo "SingleProductView.php?id=" . $product_data["id"]; ?>" class="btn btn-outline-success mb-2">Buy again</a>
          <a href="#" class="btn btn-outline-warning mb-2" onclick="addToCart(<?php echo $product_data['id']; ?>);">Add to cart</a>

      </div>
   </div>
</div>
</div>


<!--have products-->
<?php
}

    }else{
        echo("something went wrong");
    }

 }else{
    echo("please Log in first");
 }


?>

