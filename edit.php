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

    // get data
    if( !empty($_GET['id']) ) {
        $id = $_GET['id'];
        $query = "SELECT * FROM whs WHERE id = $id";
        $result = mysqli_query( $link, $query );
        $whs = $result->fetch_assoc();

        // get country names
        $sql = "SELECT id, country_name FROM country";
        $countries = mysqli_query($link, $sql);

        if ($whs && $countries){
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
  <h1>World Heritage Sites from a MySQL Table</h1>
    <div class="form">
        <form method="post" action="WHS.php" enctype="multipart/form-data">
            <h2>Update the List</h2>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Name:</label>
                    <input class="form-control" type="text" name="name" value="<?php if ($whs) echo $whs['name']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Country:</label>
                    <select class="form-control" name="country_id">
                            <?php  while ( $country = $countries->fetch_assoc() )
                            {
                                if ($country['id'] == $whs['country_id']){
                                    echo '<option value="'.$country['id'].'" selected>'.$country['country_name'].'</option>';
                                }else{
                                    echo '<option value="'.$country['id'].'">'.$country['country_name'].'</option>';
                                }
                            } 
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Map Url:</label>
                <input class="form-control" type="text" name="map_url" value="<?php if ($whs) echo $whs['map_url']; ?>" >
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Image:</label>
                    <input type="file" class="form-control-file" name="image">
                </div>
                <div class="form-group col-md-3">
                    <label>Year:</label>
                    <input class="form-control" type="number" name="year" min="0" value="<?php if ($whs) echo $whs['year']; ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Ranking:</label>
                    <input class="form-control" type="number" name="ranking" min="0" value="<?php if ($whs) echo $whs['ranking']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea class="form-control" name="description" rows="3"><?php if ($whs) echo $whs['description']; ?></textarea>
            </div>
            <div class="form_submit">
                <button class="btn btn-secondary"><a class="btn_cancel" href="WHS.php">Cancel</a></button>
                <button type="submit" name="submit" class="btn btn-success">Update</button>
                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
            </div>
            <input type="hidden" name="id" value="<?php echo $whs['id']; ?>">
        </form>
    </div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
