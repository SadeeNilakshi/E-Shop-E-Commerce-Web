<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Recents | eShop</title>

    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css" />

    <link rel="icon" href="resource/logo.svg" />
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <?php include "header.php";

            include "connection.php";

            if (isset($_SESSION["u"])) {

                $re_rs = Database::search("SELECT * FROM `recent` WHERE recent.user_email='" . $_SESSION["u"]["email"] . "'");

                $re_num = $re_rs->num_rows;

                ?>

                <div class="col-12">
                    <div class="row">
                        <div class="col-12 border border-1 border-primary rounded mb-2">
                            <div class="row">

                                <div class="col-12">
                                    <label class="form-label fs-1 fw-bolder">My Recent Preferences &hearts;</label>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <hr />
                                </div>

                                <div class="col-12">
                                    <div class="row">
                                        <div class="offset-lg-2 col-12 col-lg-6 mb-3">
                                            <input type="text" class="form-control" placeholder="Search in Recents..." id="r"/>
                                        </div>
                                        <div class="col-12 col-lg-2 mb-3 d-grid">
                                            <button class="btn btn-primary"  onclick="searchRecents();">Search</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr />
                                </div>

                                <div class="col-11 col-lg-2 border-0 border-end border-1 border-dark">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Recent</li>
                                        </ol>
                                    </nav>
                                    <nav class="nav nav-pills flex-column">
                                        <a class="nav-link" href="watchlist.php">My watchlist</a>
                                        <a class="nav-link" href="cart.php">My Cart</a>
                                        <a class="nav-link active" aria-current="page" href="#">Recents</a>
                                    </nav>
                                </div>

                                <?php

                                if ($re_num == 0) {
                                    ?>
                                    <!-- empty view -->
                                    <div class="col-12 col-lg-9">
                                        <div class="row">
                                            <div class="col-12 emptyView"></div>
                                            <div class="col-12 text-center">
                                                <label class="form-label fs-1 fw-bold">You have no items in your Recent Preferences
                                                    yet.</label>
                                            </div>
                                            <div class="offset-lg-4 col-12 col-lg-4 d-grid mb-3">
                                                <a href="home.php" class="btn btn-warning fs-3 fw-bold">Start Shopping</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- empty view -->
                                    <?php
                                } else {
                                    ?>
                                    <!-- have products -->
                                    <div class="col-12 col-lg-9" id="searchresult">
                                        <div class="row">
                                            <?php
                                            for ($x = 0; $x < $re_num; $x++) {
                                                $re_data = $re_rs->fetch_assoc();
                                    
                                                $product_rs = Database::search("SELECT* FROM `product` WHERE `id`='" . $re_data["product_id"] . "'");
                                                $product_data = $product_rs->fetch_assoc();

                                                $image_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $re_data["product_id"] . "'");
                                                $image_data = $image_rs->fetch_assoc();

                                                $color_rs = Database::search("SELECT * FROM `color` WHERE `clr_id`='" . $product_data["color_clr_id"] . "'");
                                                $color = $color_rs->fetch_assoc();

                                                $condition_rs = Database::search("SELECT * FROM `condition` WHERE `condition_id`='" . $product_data["condition_condition_id"] . "'");
                                                $condition = $condition_rs->fetch_assoc();

                                                $seller_rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $product_data["user_email"] . "'");
                                                $seller_data = $seller_rs->fetch_assoc();

                                                ?>

                                                <div class="card mb-3 mx-0 mx-lg-2 col-12">
                                                    <div class="row g-0">
                                                        <div class="col-md-4">

                                                            <?php

                                                            $query = "SELECT * FROM `product`";

                                                            ?>

                                                            <img src="<?php echo $image_data["img_path"]; ?>"
                                                                class="img-fluid rounded-start" style="height: 200px;" />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="card-body">

                                                                <h5 class="card-title fs-2 fw-bold text-primary">
                                                                    <?php echo $product_data["title"] ?>
                                                                </h5>

                                                                <span class="fs-5 fw-bold text-black-50">Colour :
                                                                    <?php echo $color["clr_name"]; ?>
                                                                </span>
                                                                &nbsp;&nbsp; | &nbsp;&nbsp;

                                                                <span class="fs-5 fw-bold text-black-50">Condition :
                                                                    <?php echo $condition["condition_name"]; ?>
                                                                </span>
                                                                <br />
                                                                <span class="fs-5 fw-bold text-black-50">Price :</span>&nbsp;&nbsp;
                                                                <span class="fs-5 fw-bold text-black">Rs.
                                                                    <?php echo $product_data["price"]; ?> .00
                                                                </span>
                                                                <br />
                                                                <span class="fs-5 fw-bold text-black-50">Quantity
                                                                    :</span>&nbsp;&nbsp;
                                                                <span class="fs-5 fw-bold text-black">
                                                                    <?php echo $product_data["qty"]; ?> Items available
                                                                </span>
                                                                <br />
                                                                <span class="fs-5 fw-bold text-black-50">Seller :</span>
                                                                <br />
                                                                <span class="fs-5 fw-bold text-black">
                                                                    <?php echo $seller_data["fname"] . " " . $seller_data["lname"]; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mt-5">
                                                            <div class="card-body d-lg-grid">
                                                            <a href="<?php echo "SingleProductView.php?id=" . $product_data["id"]; ?>" class="btn btn-outline-success fw-bold mb-2">Buy Again</a>
                                                                <a href="#" class="btn btn-outline-warning mb-2" onclick="addToCart(<?php echo $product_data['id']; ?>);">Add to Cart</a>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>



                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <!-- have products -->
                                    <?php
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            } else {
                ?>
                <script>
                    window.location = "home.php";
                </script>
                <?php
            }

            ?>

            <?php include "footer.php"; ?>

        </div>
    </div>

    <script src="bootstrap.bundle.js"></script>
    <script src="script.js"></script>
</body>

</html>