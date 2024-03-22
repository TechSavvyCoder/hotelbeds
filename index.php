<?php
// API credentials
$apiKey = "b0689713818666de7a176166f60d688a";
$secret = "683bbfe1ef";

// Construct the API request
$url = "https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels"; // Example endpoint, replace with actual endpoint
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking Homepage</title>
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
    </style>
</head>
<body>
    <header>
        <h1>Hotel Booking</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="booking.php">Booking</a>
            <a href="booking-list.php">Booking List</a>
            <a href="hotel-search.php">Hotel Search</a>
        </nav>
    </header>

    <div class="container">
        <div class="search-box">
            <h2>Find Your Perfect Hotel</h2>
            <form action="#" method="get">
                <input type="text" name="location" placeholder="Enter Location">
                <input type="submit" value="Search">
            </form>
        </div>

        <h2>Featured Hotels</h2>

        <?php
        // Check for errors
        if($response === false) {
            echo 'Error: ' . curl_error($ch);
        } else {
            // Get HTTP status code
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Check if HTTP status code indicates success (200)
            if ($http_status == 200) {
                // Parse JSON response and display
                $data = json_decode($response, true);
                // print_r($data);
                if (!empty($data['hotels'])) { ?>
                    <div class="hotel-list">
                        <?php foreach ($data['hotels'] as $hotel) { ?>
                        
                            <div class="hotel-card">
                                <!-- <img src="hotel1.jpg" alt="Hotel 1"> -->
                                <div class="details">
                                    <h3><?php echo $hotel['name']['content']; ?></h3>
                                    <p>Category: <?php echo $hotel['categoryCode']; ?></p>
                                    <p>Address: <?php echo $hotel['address']['content']; ?></p>
                                    <p>Selling Rate: <?php echo $hotel['totalSellingRate']['content']; ?>test</p>
                                    <a href="#">Book Now</a>
                                </div>
                            </div>
                            <!-- Add more hotel cards as needed -->
                        
                        <?php } ?>
                    </div>
                <?php
                } else {
                    echo 'No hotels found.';
                }
            } else {
                echo "API request failed with HTTP status code: $http_status";
            }
        }

        // Close cURL session
        curl_close($ch);
        ?>
    </div>
</body>
</html>