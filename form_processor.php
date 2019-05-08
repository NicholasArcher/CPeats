<!DOCTYPE html>
<html>
<head>
<title>CP Eats</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="home.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <style>
        .restaurantTable td {
            padding: 8px 15px;
        }
        .restaurantTable th {
            background: #ccc;
            text-align: center;
            padding: 8px 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./index.html"><img class="logoimg" src=./imgs/logo.png> </img> </a> </div> <div
              class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
              <li class="active"><a href="./CPeatsSearchPage.HTML">Search</a></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="./Recomendations.html">Recomendations <span
                    class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="./FamilyFriendly.html">Family Friendly</a></li>
                  <li><a href="./HappyHour.html">Happy Hour Deals</a></li>
                  <li><a href="./LateNight.html">Late Night</a></li>
                </ul>
              </li>

            </ul>
        </div>
      </div>
    </nav>
<?php
$dbuser = 'root';
$dbpass = 'root';
$dbname = 'cp_eats';

$conn = new mysqli("localhost", $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
  die('Connection Failed: ' . $conn->connect_error);
}

$category = intval($_POST['category']);
$price = intval($_POST['price']);

$sql_price = <<<SQL
    SELECT * FROM Price where price_id='$price' LIMIT 1;
SQL;

$result = $conn->query($sql_price);
$price_row = mysqli_fetch_assoc($result);

$sql_category = <<<SQL
    SELECT * FROM Category where category_id='$category' LIMIT 1;
SQL;

$result = $conn->query($sql_category);
$category_row = mysqli_fetch_assoc($result);


$sql = <<<SQL
        SELECT *, Hours.open, Hours.close FROM Restaurant
            INNER JOIN Hours ON Restaurant.hours_id=Hours.hours_id
            WHERE category_id='$category' AND price_id='$price';
SQL;

$restaurants= $conn->query($sql);

$tableData = '';
$found_results = false;
if ($restaurants->num_rows > 0) {
    $found_results = true;
    while ($restaurant = mysqli_fetch_assoc($restaurants)) {
        $tableData .= <<<HTML
        <tr>
            <td>{$restaurant['name']}</td>
            <td>{$restaurant['street']}, {$restaurant['city']} {$restaurant['state']} {$restauraunt['zip']}</td>
            <td>{$restaurant['phone_number']}</td>
            <td><a href="{$restaurant['url']}">Link</a></td>
            <td>{$restaurant['open']} - {$restaurant['close']} </td>
        </tr>
HTML;
    }
    mysqli_free_result($restaurants);
} else {
    $tableData = <<<HTML
    <h4>
        0 Results Found
    </h4>
HTML;
}

$conn->close();

?>
<div class="boxA">
    <div class="boxB">
        <h3>Cuisine Category: <?php echo $category_row['category']; ?></h3>
        <h3>Price: <?php echo $price_row['price']; ?></h3>
        
        <?php 
        
        ?>
        <table class="restaurantTable">
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Website</th>
                <th>Hours</th>
            </tr>
            <?php 
                echo $tableData;
            ?>
        </table>
    </div>
</div>
</body>
</html>
