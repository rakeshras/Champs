<?php
// Include the database connection file
include 'db_config.php'; // Ensure this file contains the correct DB connection settings

// Initialize variable to hold fetched data
$information_list = [];
$account_name = '';

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Fetch all account names for the dropdown
$account_stmt = $conn->prepare("SELECT DISTINCT AccountName FROM importantinformation");
$account_stmt->execute();
$account_result = $account_stmt->get_result();
$accounts = $account_result->fetch_all(MYSQLI_ASSOC);
$account_stmt->close();

// Check if an account is selected
if (isset($_POST['account_name'])) {
    $account_name = $_POST['account_name'];

    // Prepare and execute the SQL query to fetch the data
    $stmt = $conn->prepare("SELECT information FROM importantinformation WHERE AccountName = ?");
    
    // Check if prepare() succeeded
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $account_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data was found
    if ($result->num_rows > 0) {
        // Fetch all rows
        while ($row = $result->fetch_assoc()) {
            $information_list[] = $row;
        }
    } else {
        $information_list[] = ["information" => "No important information found."];
    }

    // Close the statement
    $stmt->close();
}

// Handle deletion of information
if (isset($_GET['delete_info']) && isset($_GET['account_name'])) {
    $delete_info = $_GET['delete_info'];
    $account_name = $_GET['account_name'];
    
    $delete_stmt = $conn->prepare("DELETE FROM importantinformation WHERE information = ? AND AccountName = ?");
    $delete_stmt->bind_param("ss", $delete_info, $account_name);
    $delete_stmt->execute();
    $delete_stmt->close();
    
    // Redirect to the same page to avoid resubmission of form data
    header("Location: manage_information.php");
    exit();
}

// Handle addition of new information
if (isset($_POST['new_info']) && !empty($_POST['account_name'])) {
    $new_info = $_POST['new_info'];

    $add_stmt = $conn->prepare("INSERT INTO importantinformation (AccountName, information) VALUES (?, ?)");
    $add_stmt->bind_param("ss", $account_name, $new_info);
    $add_stmt->execute();
    $add_stmt->close();
    
    // Redirect to the same page to avoid resubmission of form data
    header("Location: manage_information.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Champs</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />
	<style>
    .navbar {
      background-color: blue !important; /* Override default background color */
    }
    </style>
    <style>
    .logout-btn {
      color: #000000; /* Light gray text color */
      background-color: #ffffff; /* Dark background color */
      border-radius: 4px; /* Optional: rounded corners */
      padding: 8px 12px; /* Padding for better spacing */
      text-decoration: none; /* Remove underline from the link */
    }

    .logout-btn:hover {
      background-color: #000000; /* Slightly lighter gray on hover */
      color: #000000; /* White text color on hover */
    }
  </style>
  <style>
        /* Styles for the popup */
        .popup {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .popup-content {
            position: relative;
            width: 500px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            text-align: center;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
	 <style>
	.highlight {
    background-color: #fff; /* Yellow background */
    color: #FF0000;              /* Black text color */
    padding: 5px;            /* Padding around the text */
    text-align: center;       /* Centered text */
    border-bottom: 2px solid #fbc02d; /* Border to emphasize separation */
    margin-bottom: 25px;      /* Space below the highlight section */
}
 </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Manage Important Information</h1>
        <form method="POST" action="manage_information.php">
            <div class="form-group">
                <label for="account_name">Select Account Name:</label>
                <select name="account_name" id="account_name" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Select Account --</option>
                    <?php foreach ($accounts as $account): ?>
                        <option value="<?php echo htmlspecialchars($account['AccountName']); ?>" <?php if ($account['AccountName'] == $account_name) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($account['AccountName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if (!empty($information_list)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Information</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($information_list as $info): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($info['information']); ?></td>
                            <td>
                                <a href="manage_information.php?delete_info=<?php echo urlencode($info['information']); ?>&account_name=<?php echo urlencode($account_name); ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <form method="POST" action="manage_information.php">
            <div class="form-group">
                <label for="new_info">Add New Information:</label>
                <input type="text" name="new_info" id="new_info" class="form-control" placeholder="Enter new information">
            </div>
            <input type="hidden" name="account_name" value="<?php echo htmlspecialchars($account_name); ?>">
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
    </div>
	          <div class="container-fluid d-flex justify-content-between">

          </div>
        </footer>
      </div>
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>


    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>


    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>