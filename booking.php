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
        <h2>Find Your Booking</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="booking_id">Enter Booking ID:</label>
            <input type="text" id="booking_id" name="booking_id">
            <select name="action">
                <option value="get_details">Get Booking Details</option>
                <option value="confirm_booking">Confirm Booking</option>
                <option value="get_bookings">Get List of Bookings</option>
                <option value="modify_booking">Modify Booking</option>
                <option value="cancel_booking">Cancel Booking</option>
            </select>
            <input type="submit" value="Submit">
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Function to handle API requests
            function makeApiRequest($url, $method = 'GET', $data = array()) {
                // API credentials
                $apiKey = "b0689713818666de7a176166f60d688a";
                $secret = "683bbfe1ef";

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

                // Set request method and data
                if ($method == 'POST') {
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                } elseif ($method == 'PUT') {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                } elseif ($method == 'DELETE') {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                }

                // Execute cURL request
                $response = curl_exec($ch);

                // Check for errors
                if ($response === false) {
                    return array('success' => false, 'error' => curl_error($ch));
                }

                // Get HTTP status code
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                // Close cURL session
                curl_close($ch);

                // Return response
                return array('success' => true, 'status_code' => $http_status, 'response' => json_decode($response, true));
            }

            // Function to display booking details
            function displayBookingDetails($booking_id) {
                $url = "https://api.test.hotelbeds.com/booking-api/1.0/bookings/" . $booking_id;
                $response = makeApiRequest($url);
                if ($response['success']) {
                    $data = $response['response'];
                    if (!empty($data['booking'])) {
                        echo '<div class="booking">';
                        echo '<h2>Booking Details</h2>';
                        echo '<p><strong>Booking ID:</strong> ' . $data['booking']['reference'] . '</p>';
                        echo '<p><strong>Status:</strong> ' . $data['booking']['status'] . '</p>';
                        echo '<p><strong>Check-in Date:</strong> ' . $data['booking']['checkIn'] . '</p>';
                        echo '<p><strong>Check-out Date:</strong> ' . $data['booking']['checkOut'] . '</p>';
                        echo '</div>';
                    } else {
                        echo 'No booking found with the provided ID.';
                    }
                } else {
                    echo 'Error: ' . $response['error'];
                }
            }

            // Function to confirm a booking
            function confirmBooking($booking_id) {
                $url = "https://api.test.hotelbeds.com/booking-api/1.0/bookings/" . $booking_id . "/confirm";
                $response = makeApiRequest($url, 'PUT');
                if ($response['success']) {
                    echo 'Booking confirmed successfully.';
                } else {
                    echo 'Error: ' . $response['error'];
                }
            }

            // Function to get a list of bookings
            function getBookings() {
                $url = "https://api.test.hotelbeds.com/booking-api/1.0/bookings";
                $response = makeApiRequest($url);
                if ($response['success']) {
                    $data = $response['response'];
                    if (!empty($data['bookings'])) {
                        echo '<h2>List of Bookings</h2>';
                        foreach ($data['bookings'] as $booking) {
                            echo '<div class="booking">';
                            echo '<p><strong>Booking ID:</strong> ' . $booking['reference'] . '</p>';
                            echo '<p><strong>Status:</strong> ' . $booking['status'] . '</p>';
                            echo '<p><strong>Check-in Date:</strong></p>';
                            echo '<p><strong>Check-out Date:</strong> ' . $booking['checkOut'] . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo 'No bookings found.';
                    }
                } else {
                    echo 'Error: ' . $response['error'];
                }
            }

            // Function to modify a booking
            function modifyBooking($booking_id, $new_data) {
                $url = "https://api.test.hotelbeds.com/booking-api/1.0/bookings/" . $booking_id;
                $response = makeApiRequest($url, 'PUT', $new_data);
                if ($response['success']) {
                    echo 'Booking modified successfully.';
                } else {
                    echo 'Error: ' . $response['error'];
                }
            }

            // Function to cancel a booking
            function cancelBooking($booking_id) {
                $url = "https://api.test.hotelbeds.com/booking-api/1.0/bookings/" . $booking_id . "/cancel";
                $response = makeApiRequest($url, 'PUT');
                if ($response['success']) {
                    echo 'Booking canceled successfully.';
                } else {
                    echo 'Error: ' . $response['error'];
                }
            }

            // Main code execution
            $booking_id = $_POST['booking_id'];
            $action = $_POST['action'];
            switch ($action) {
                case 'get_details':
                    displayBookingDetails($booking_id);
                    break;
                case 'confirm_booking':
                    confirmBooking($booking_id);
                    break;
                case 'get_bookings':
                    getBookings();
                    break;
                case 'modify_booking':
                    // Example data to modify booking
                    $new_data = array(
                        // Specify new booking details here
                    );
                    modifyBooking($booking_id, $new_data);
                    break;
                case 'cancel_booking':
                    cancelBooking($booking_id);
                    break;
                default:
                    echo 'Invalid action.';
            }
        }
            
        // Close cURL session
        curl_close($ch);
        ?>
    </div>
</body>
</html>