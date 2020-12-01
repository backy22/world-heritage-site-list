<?php
    // connect
    $link = mysqli_connect(
        "localhost",
        "root", //username
        "root", //password
        "ayatsuba_mmdd"); //database name
    if(!$link){
        die( 'Connect Error: '. mysqli_connect_error() );
    };

    // get country data
    if( !empty($_GET['id']) ) {
        $id = $_GET['id'];
        $query = "SELECT * FROM country WHERE id = $id";
        $result = mysqli_query( $link, $query );
        $country = $result->fetch_assoc();
        if ($country){
        }else{
            header("Location: ./WHS.php");
        }
    }
 
?>

<!doctype html>
<html>
<head>
<title>World Heritage Sites I've ever been</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Cardo|Montserrat:400,700&display=swap" rel="stylesheet">
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Treavel tips for each country I've been to.</h1>
  <h2><?php if ($country) echo $country['country_name'] ?></h2>
  <p class="tips"><?php if($country) echo $country['tips'] ?></p>
  <h2>World Heritage site list</h2>

    <?php
        // get whs data
        $query = "SELECT id, name FROM whs  WHERE country_id = $id";
        $result = mysqli_query( $link, $query );

        echo mysqli_error( $link );

        echo '<ul>';
        while ( $whs = $result->fetch_assoc() )
        {
            echo '<li><a href="WHS.php">'.$whs['name'].'</a></li>';
        }
        echo '</ul>';

    ?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
