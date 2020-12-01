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

    // get country names
    $query = "SELECT id, country_name FROM country";
    $countries = mysqli_query($link, $query);
    
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
    <div class="form shadow p-3 mb-5 rounded">
        <form method="post" action="WHS.php" enctype="multipart/form-data">
            <h2>Add New List</h2>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input class="form-control" type="text" name="name" placeholder="Name" required>
                </div>
                <div class="form-group col-md-6">
                    <select class="form-control" name="country_id">
                        <option>Select country</option>
                        <?php  while ( $country = $countries->fetch_assoc() ){echo '<option value="'.$country['id'].'">'.$country['country_name'].'</option>';} ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="map_url" placeholder="Map Url">
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Image:</label>
                    <input type="file" class="form-control-file" name="image">
                </div>
                <div class="form-group col-md-3">
                    <input class="form-control" type="number" name="year" min="0" placeholder="Year" >
                </div>
                <div class="form-group col-md-3">
                    <input class="form-control" type="number" name="ranking" min="0" placeholder="Ranking">
                </div>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="description" rows="3" placeholder="Description"></textarea>
            </div>
            <div class="form_submit">
                <button type="submit" name="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>

    <?php
        // pagination
        if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }

        $no_of_records_per_page = 10;
        $offset = ($pageno-1) * $no_of_records_per_page; 

        $total_pages_sql = "SELECT COUNT(*) FROM whs";
        $result = mysqli_query($link, $total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

        // get data
        $query = "SELECT whs.id, whs.name, whs.country_id, whs.map_url, whs.image, whs.year, whs.ranking, whs.description, country.country_name FROM whs INNER JOIN country on whs.country_id = country.id ORDER BY RANKING ASC LIMIT $offset, $no_of_records_per_page";
        $result = mysqli_query( $link, $query );


        echo mysqli_error( $link );

        echo '<div class="list">';
        while ( $whs = $result->fetch_assoc() )
        {
            echo '<div class="d-flex shadow p-3 mb-5 bg-white rounded">';
            echo '<div class="item">';
            echo '<h3>'.$whs['name'].'<a href="country.php?id='.$whs['country_id'].'">('.$whs['country_name'].')</a></h3>';
            echo '<span><b>Inscription year: '.$whs['year'].'</b></span>';
            echo '<span><b>&nbsp;&nbsp;My ranking: '.$whs['ranking'].'</b></span>';
            echo '<p class="description">'.$whs['description'].'</p>';
            echo '<p><a href="edit.php?id='.$whs['id'].'">edit</a></p>';
            echo '</div>';
            echo '<div class="item"><img class="rounded" src="img/'.$whs['image'].'" alt="picture of the site"></div>';
            echo '<div class="item gmap-wrap"><iframe src="'.$whs['map_url'].'"></iframe></div>';
            echo '</div>';
        }

        // add or update or delete list
        if (isset($_POST['submit']) || isset($_POST['delete'])) {
            if(!empty($_POST['id'])){
                $id = $_POST['id'];
                $query = "SELECT * FROM whs WHERE id = $id";
                $result = mysqli_query( $link, $query );
                $whs = $result->fetch_assoc();
            }
            $name = mysqli_real_escape_string($link, $_POST['name']);
            $country_id = $_POST['country_id'];
            $map_url = $_POST['map_url'];

            if (!empty($_FILES['image']['tmp_name'])){
                $image = basename($_FILES['image']['tmp_name']);
                $filename = 'img/'.$image;
                move_uploaded_file($_FILES['image']['tmp_name'], $filename);
            }elseif(!empty($id)){
                $image = $whs['image']; 
            }else{
                $image = 'blank';
            }

            $year = $_POST['year'];
            $ranking = $_POST['ranking'];
            $description = mysqli_real_escape_string($link, $_POST['description']);

            if (!empty($id) && isset($_POST['delete'])){
                $sql = "DELETE FROM whs WHERE id = $id"; 
            } elseif (!empty($id)){
                $sql = "UPDATE whs set name='$name', country_id='$country_id', map_url='$map_url', image='$image', year='$year', ranking='$ranking', description='$description' WHERE id = $id";
            } else {
                $sql = "INSERT INTO whs (name, country_id, map_url, image, year, ranking, description) VALUES ('$name','$country_id','$map_url','$image','$year','$ranking','$description')";
            }

            if (mysqli_query($link, $sql)) {
                echo "<meta http-equiv='refresh' content='0'>";
            } else {
                echo "Error: " . $sql . " " . mysqli_error($link);
            }
        }
    ?>

    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
            </li>
            <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
