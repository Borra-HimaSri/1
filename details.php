<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .background-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: brightness(50%);
        }
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 48px;
            font-weight: bold;
            z-index: 1;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Text shadow for better readability */
        }
        .content {
            margin-top: 100px; /* Adjust to the height of your header */
            color: white;
            padding: 20px;
            position: relative;
        }
        .details-container {
            display: grid;
            grid-template-columns: auto auto;
            gap: 10px;
            font-size: 24px;
            max-width: 600px; /* Adjust as needed */
        }
        .detail-heading {
            color: orange;
            font-weight: bold;
            text-align: right;
            padding-right: 10px;
        }
        .detail {
            color: white;
            text-align: left;
        }
        .error-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            color: white;
        }
        .error {
            color: red;
            font-weight: bold;
            font-size: 24px;
            text-align: center;
            margin-top: 20px;
        }
        .error-img {
            max-width: 200px; /* Adjust as needed */
            height: auto;
        }
        @keyframes borderMove {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 100% 50%;
            }
        }
        .student-image {
            position: absolute;
            top: 20px; /* Adjust as needed */
            right: 100px; /* Adjust as needed */
            max-width: 400px;
            height: auto;
            border-radius: 10px;
            z-index: 2; /* Ensure it's above other content */
            border: 3px solid transparent; /* Transparent border */
            background-image: linear-gradient(white, white), /* White inner border */
                              linear-gradient(45deg, red, orange, yellow, green, blue, indigo, violet); /* Rainbow outer border */
            background-origin: border-box;
            background-clip: content-box, border-box; /* Clip the background to the border box */
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.8); /* Glowing effect */
            background-size: 200%; /* Ensure the background gradient is large enough to move */
            animation: borderMove 3s linear infinite; /* Apply the border move animation */
        }
        .error-img {
    max-width: 600px; /* Increased size */
    height: 400px;
}

    </style>
</head>
<body>
    <video autoplay muted loop class="background-video">
        <source src="images/vid.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="content">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "student_database";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $name = $register = $course = $year = $email = $dob = $image = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $register = strtoupper($_POST['Register']);

            $stmt = $conn->prepare("SELECT name, register_number, course, year, email, date_of_birth, image FROM students WHERE register_number = ?");
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("s", $register);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $name = $row['name'];
                $course = $row['course'];
                $year = $row['year'];
                $email = $row['email'];
                $dob = $row['date_of_birth'];
                $image = $row['image'];
            } else {
                echo "<div class='error-container'>
                        <img src='images/error.png' class='error-img' alt='Error Image'>
                        <p class='error'>Oops! You forget your Register Number.</p>
                      </div>";
            }
            $stmt->close();
        } else {
            echo "<div class='error-container'>
                    <img src='images/error-image.png' class='error-img' alt='Error Image'>
                    <p class='error'>Invalid request.</p>
                  </div>";
        }

        $conn->close();

        // Print the details if data is fetched
        if ($name && $register) {
            echo "<div class='header'>Student Details</div>";
            echo "<div class='details-container'>";
            echo "<div class='detail-heading'>Name:</div>";
            echo "<div class='detail'>" . htmlspecialchars($name) . "</div>";
            echo "<div class='detail-heading'>Register Number:</div>";
            echo "<div class='detail'>" . htmlspecialchars($register) . "</div>";
            echo "<div class='detail-heading'>Course:</div>";
            echo "<div class='detail'>" . htmlspecialchars($course) . "</div>";
            echo "<div class='detail-heading'>Year:</div>";
            echo "<div class='detail'>" . htmlspecialchars($year) . "</div>";
            echo "<div class='detail-heading'>Email:</div>";
            echo "<div class='detail'>" . htmlspecialchars($email) . "</div>";
            echo "<div class='detail-heading'>Date of Birth:</div>";
            echo "<div class='detail'>" . htmlspecialchars($dob) . "</div>";
            echo "</div>";
        }
        ?>
        <?php if ($image): ?>
            <img src="<?php echo htmlspecialchars($image); ?>" class="student-image" alt="Student Image">
        <?php endif; ?>
    </div>
</body>
</html>
