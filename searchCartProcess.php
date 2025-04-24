<?php
session_start();
require "connection.php";

if (isset($_SESSION["u"])) {
  if (isset($_GET["c"])) {

    $search = $_GET["c"];
    $user = $_SESSION["u"]["email"];

    $total = 0;
    $subtotal = 0;
    $shipping = 0;

    $p_rs = Database::search("SELECT * FROM `product` WHERE `title` LIKE '%" . $search . "%'");
    $p_data = $p_rs->fetch_assoc();

    $cart_rs = Database::search("SELECT * FROM `cart` WHERE `user_email`='" . $user . "' 
    AND `product_id` = '" . $p_data['id'] . "'");
    $cart_num = $cart_rs->num_rows;

    if ($cart_num == 0) {
      echo ("No product in the Cart");
    } else {

      for ($x = 0; $x < $cart_num; $x++) {
        $cart_data = $cart_rs->fetch_assoc();

        $product_rs = Database::search("SELECT* FROM `product` WHERE `id`='" . $cart_data["product_id"] . "'");
        $product_data = $product_rs->fetch_assoc();

        $image_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $cart_data["product_id"] . "'");
        $image_data = $image_rs->fetch_assoc();

        $color_rs = Database::search("SELECT * FROM `color` WHERE `clr_id`='" . $product_data["color_clr_id"] . "'");
        $color = $color_rs->fetch_assoc();

        $condition_rs = Database::search("SELECT * FROM `condition` WHERE `condition_id`='" . $product_data["condition_condition_id"] . "'");
        $condition = $condition_rs->fetch_assoc();

        $total = $total + ($product_data["price"] * $cart_data["qty"]);

        $addrerss_Rsesultset = Database::search("SELECT district.district_id AS did FROM `user_has_address` INNER JOIN `city` ON user_has_address.city_city_id=city.city_id 
            INNER JOIN `district` ON city.district_district_id=district.district_id WHERE `user_email`='" . $user . "'");
        $addrerss_data = $addrerss_Rsesultset->fetch_assoc();

        $ship = 0;

        if ($addrerss_data["did"] == 2) {
          $ship = $product_data["delivery_fee_colombo"];
          $shipping = $shipping + $ship;
        } else {
          $ship = $product_data["delivery_fee_other"];
          $shipping = $shipping + $ship;
        }

        $seller_rs = Database::search("SELECT * FROM `user` WHERE `email` = '" . $product_data["user_email"] . "'");
        $seller_data = $seller_rs->fetch_assoc();
        $seller = $seller_data["fname"] . " " . $seller_data["lname"];
      }

?>

      <div class="card mb-3 mx-0 col-12">
        <div class="row g-0">
          <div class="col-md-12 mt-3 mb-3">
            <div class="row">
              <div class="col-12">
                <span class="fw-bold text-black-50 fs-5">Seller :</span>&nbsp;
                <span class="fw-bold text-black fs-5"><?php echo $seller; ?></span>&nbsp;
              </div>
            </div>
          </div>

          <hr class="border border-1 border-light">

          <div class="col-md-4">
            <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="<?php echo $product_data["description"]; ?>" title="<?php echo $product_data["title"]; ?>">
              <img src="<?php echo $image_data["img_path"]; ?>" class="img-fluid rounded-start" style="max-width: 200px;">
            </span>
          </div>
          <div class="col-md-5">
            <div class="card-body">

              <h3 class="card-title"><?php echo $product_data["title"] ?></h3>

              <span class="fw-bold text-black-50">Colour : <?php echo $color["clr_name"]; ?></span> &nbsp; |

              &nbsp; <span class="fw-bold text-black-50">Condition : <?php echo $condition["condition_name"]; ?></span>
              <br>
              <span class="fw-bold text-black-50">Price :</span>&nbsp;
              <span class="fw-bold text-black fs-5">Rs. <?php echo $product_data["price"]; ?> .00</span>
              <br>
              <span class="fw-bold text-black-50">Quantity :</span>&nbsp;
              <input type="number" class="mt-3 border border-2 border-secondary fs-4 fw-bold px-3 cardqtytext"
              value="<?php echo $cart_data["qty"]; ?>" onchange="changeQTY(<?php echo $cart_data['cart_id']; ?>);" id="qty_num">
              <br><br>
              <span class="fw-bold text-black-50">Delivery Fee for Colombo :</span>&nbsp;
              <span class="fw-bold text-black fs-5"><?php echo $product_data["delivery_fee_colombo"]; ?></span>
              <br />
              <span class="fw-bold text-black-50">Delivery Fee for other areas :</span>&nbsp;
              <span class="fw-bold text-black fs-5"><?php echo $product_data["delivery_fee_other"]; ?></span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-body d-grid">
              <a href="<?php echo "SingleProductView.php?id=" . $product_data["id"]; ?>" class="btn btn-outline-success mb-2 fw-bold">Buy Now</a>
              <a class="btn btn-outline-danger mb-2 fw-bold" onclick="deleteFromCart(<?php echo $cart_data['cart_id']; ?>)" ;>Remove</a>
            </div>
          </div>

          <hr>

          <div class="col-md-12 mt-3 mb-3">
            <div class="row">
              <div class="col-6 col-md-6">
                <span class="fw-bold fs-5 text-black-50t">Requested Total <i class="bi bi-info-circle"></i></span>
              </div>
              <div class="col-6 col-md-6 text-end">
                <span class="fw-bold fs-5 text-black-50">Rs.<?php echo ($product_data["price"] * $cart_data["qty"]) + $ship; ?>.00</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--have products-->

<?php
    }
  } else {
    echo ("something went wrong");
  }
} else {
  echo ("please Log in first");
}


?>