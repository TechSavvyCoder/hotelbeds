<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Search Results</title>
    <style>
        /* Add CSS styling here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        nav {
            text-align: center;
            margin-bottom: 20px;
        }
        nav a {
            text-decoration: none;
            color: #fff;
            margin: 0 10px;
        }
        .search-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .search-box input[type="text"] {
            width: 300px;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
        }
        .search-box input[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            border-radius: 3px;
        }
        .hotel-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
        }
        .hotel-card {
            width: calc(33.33% - 20px);
            background-color: #fff;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .hotel-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ccc;
        }
        .hotel-card .details {
            padding: 20px;
        }
        .hotel-card h3 {
            margin-top: 0;
        }
        .hotel-card p {
            margin: 10px 0;
            color: #666;
        }
        .book-now-btn {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .book-now-btn:hover {
            background-color: #0056b3;
        }
        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-form select {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }
        .search-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Hotel Search Results</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="booking.php">Booking</a>
            <a href="booking-list.php">Booking List</a>
            <a href="hotel-search.php">Hotel Search</a>
        </nav>
    </header>

    <div class="container">
        <div class="search-form">
            <form action="#" method="get">
                <label for="location">Select Location:</label>
                <select name="location" id="location">
                    <?php
                    // API credentials
                    $apiKey = "b0689713818666de7a176166f60d688a";
                    $secret = "683bbfe1ef";

                    // Construct the API request URL to retrieve available locations
                    $url = "https://api.test.hotelbeds.com/hotel-content-api/1.0/locations";

                    // Construct headers
                    $headers = array(
                        'Accept: application/json',
                        'Api-Key: ' . $apiKey,
                        'X-Signature: ' . hash('sha256', $apiKey . $secret . time())
                    );

                    // Initialize cURL session
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // Execute cURL request
                    $response = curl_exec($ch);

                    // Check for errors
                    if($response === false) {
                        echo 'Error: ' . curl_error($ch);
                    } else {
                        // Parse JSON response and display available locations in a dropdown
                        $data = json_decode($response, true);
                        if (!empty($data['locations'])) {
                            foreach ($data['locations'] as $location) {
                                echo '<option value="' . $location['code'] . '">' . $location['name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No locations found</option>';
                        }
                    }

                    // Close cURL session
                    curl_close($ch);
                    ?>
                </select>
                <input type="submit" value="Search">
            </form>
        </div>

        <div class="hotel-list">
            <?php
            // Check if location is selected
            if(isset($_GET['location'])) {
                $selected_location = $_GET['location'];

                // Construct the API request URL for hotel search based on selected location
                $url = "https://api.test.hotelbeds.com/hotel-api/1.0/hotels?location={$selected_location}";

                // Initialize cURL session
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Execute cURL request
                $response = curl_exec($ch);

                // Check for errors
                if($response === false) {
                    echo 'Error: ' . curl_error($ch);
                } else {
                    // Parse JSON response and display hotel search results
                    $data = json_decode($response, true);
                    if (!empty($data['hotels'])) {
                        foreach ($data['hotels'] as $hotel) {
                            echo '<div class="hotel-card">';
                            echo '<img src="' . $hotel['image'] . '" alt="' . $hotel['name'] . '">';
                            echo '<div class="details">';
                            echo '<h3>' . $hotel['name'] . '</h3>';
                            echo '<p>Location: ' . $hotel['location'] . '</p>';
                            echo '<p class="price">' . $hotel['price'] . '</p>';
                            echo '<a href="#" class="book-now-btn">Book Now</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo 'No hotels found.';
                    }
                }

                // Close cURL session
                curl_close($ch);
            }
            ?>
        </div>
    </div>
</body>
</html>
