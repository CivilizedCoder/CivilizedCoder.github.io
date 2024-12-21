<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/secondStyle.css">
    <title>Movie List</title>
</head>
<body>

    <?php
    $server = "localhost";
    $user = "labuser";
    $password = "labuser123";
    $db = "sakila";
    $age = strip_tags($_POST['age']);
    $name = strip_tags($_POST['name']);
    $genre = strip_tags($_POST['genre']);

    print "<h1>Genre: ";
    if($genre){
    	print "$genre";
    }
    else{
    	print "Any";
    }

    print ",		Actor: ";
    if($actor){
    	print "$actor";
    }
    else{
    	print "Any";
    }
    print ",		Appropriate for age: ";
    if($age){
    	print "$age";
    }
    else{
    	print "Unspecified";
    }
    print "</h1>";


    $name = "%".strip_tags($_POST['name'])."%"; //this way I can use any part of the first or last name.


    try {
        $conn = new PDO("mysql:host=$server;dbname=$db", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Determine the appropriate film rating based on age
        if(strlen($age) < 1){
        	$rating = '%';
        }elseif ($age < 13) {
            $rating = '%G';
        } elseif ($age >= 13 && $age < 17) {
            $rating = 'PG-13';
        } elseif ($age == 17) {
            $rating = 'R';
        } else {
            $rating = 'NC-17';
        }

        // Prepare the SQL query
if ($genre != "Any") {
    $select = "
        SELECT film.title, film.description, film.release_year, film.rating, film_list.actors
        FROM film
        JOIN film_category ON film.film_id = film_category.film_id
        JOIN category ON film_category.category_id = category.category_id
        JOIN film_list ON film_list.fid = film.film_id
        WHERE category.name = :genre
          AND film.rating LIKE :rating
          AND film_list.actors LIKE :name
    ";
} else {
    $select = "
        SELECT film.title, film.description, film.release_year, film.rating, film_list.actors
        FROM film
        JOIN film_category ON film.film_id = film_category.film_id
        JOIN category ON film_category.category_id = category.category_id
        JOIN film_list ON film_list.fid = film.film_id
        WHERE film.rating LIKE :rating
          AND film_list.actors LIKE :name
    ";
}

$query = $conn->prepare($select);

// Bind parameters dynamically
$query->bindParam(':rating', $rating, PDO::PARAM_STR);
$query->bindParam(':name', $name, PDO::PARAM_STR);

if ($genre != "Any") {
    $query->bindParam(':genre', $genre, PDO::PARAM_STR);
}

// Execute the query
$query->execute();


        $query->setFetchMode(PDO::FETCH_ASSOC);

        while ($row = $query->fetch()) {
            // Assign each column's data to variables
            $title = $row['title'];
            $description = $row['description'];
            $release_year = $row['release_year'];
            $rating = $row['rating'];
            $actors = $row['actors'];

            // Display the data
            print "<p><strong>Title:</strong> $title<br/>";
            print "<strong>Description:</strong> $description<br/>";
            print "<strong>Release Year:</strong> $release_year<br/>";
            print "<strong>Rating:</strong> $rating<br/>";
            print "<strong>Category:</strong> $genre <br/>";
            print "<strong>Actors:</strong> $actors </p><hr/>";
        }

        $conn = null; // Close the database connection

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    ?>
</body>
</html>
