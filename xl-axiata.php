<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include 'db.php';
include 'auth.php';
include 'db_config.php';
// about.php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the account name from the URL
$account_name = isset($_GET['name']) ? $_GET['name'] : '';





if (!empty($account_name)) {
    // Initialize error message
    $error_message = "";

    // Fetch About Information
    $stmt_about = $conn->prepare("SELECT About FROM about WHERE AccountName = ?");
    if ($stmt_about) {
        $stmt_about->bind_param("s", $account_name);
        $stmt_about->execute();
        $result_about = $stmt_about->get_result();
        $about = $result_about->num_rows > 0 ? $result_about->fetch_assoc()['About'] : "Account information not found.";
        $stmt_about->close();
    } else {
        $error_message .= "Error preparing 'about' query: " . $conn->error . "\n";
    }





    // Fetch Focal Points Information
    $stmt_fp = $conn->prepare("SELECT Name, Type FROM focalpoints WHERE AccountName = ?");
    if ($stmt_fp) {
        $stmt_fp->bind_param("s", $account_name);
        $stmt_fp->execute();
        $result_fp = $stmt_fp->get_result();
        $focal_points = [];
        while ($row = $result_fp->fetch_assoc()) {
            $focal_points[] = $row;
        }
        $stmt_fp->close();
    } else {
        $error_message .= "Error preparing 'focalpoints' query: " . $conn->error . "\n";
    }






    // Fetch Agreement Information
	if (!empty($account_name)) {
		// Prepare and execute the SQL query
		$stmt_agreement = $conn->prepare("SELECT Agreement, FOPs, StartDate, EndDate FROM agreement WHERE AccountName = ?");
		$stmt_agreement->bind_param("s", $account_name);
		$stmt_agreement->execute();
		$result_agreement = $stmt_agreement->get_result();

		// Fetch all agreement data
		$agreements = [];
		while ($row = $result_agreement->fetch_assoc()) {
			$agreements[] = $row;
		}

		// Close the statement and connection
		$stmt_agreement->close();
	} else {
		$agreements = [];
	}
	
	
	
	
	
	// Initialize variable to hold fetched data
	$extension_support = [];

	if (!empty($account_name)) {
		// Prepare and execute the SQL query for Extension Support
		$stmt = $conn->prepare("SELECT FINANCIALYEAR, REQUESTEDRC, APPROVEDRC, APPROVEDTILL, APPROVEDBY, COMMENT FROM extensionsupport WHERE AccountName = ?");
		$stmt->bind_param("s", $account_name);
		$stmt->execute();
		$result = $stmt->get_result();

		// Fetch all extension support data
		while ($row = $result->fetch_assoc()) {
			$extension_support[] = $row;
		}

		// Close the statement
		$stmt->close();
	}
	
	
	
	
	
	
// Query to fetch data from the installedproduct table
if (!empty($account_name)) {
    // Prepare and execute the SQL query for Installed Products
    $stmt = $conn->prepare("SELECT Product, Version FROM installedproduct WHERE AccountName = ?");
    $stmt->bind_param("s", $account_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all installed products data
    while ($row = $result->fetch_assoc()) {
        $installed_products[] = $row;
    }

    // Close the statement
    $stmt->close();
}






// Check if the account name is not empty
if (!empty($account_name)) {
    // Prepare and execute the SQL query to fetch the account name
    $stmt = $conn->prepare("SELECT AccountName FROM accountname WHERE AccountName = ?");
    $stmt->bind_param("s", $account_name);
    $stmt->execute();
    $result = $stmt->get_result();
    // Check if the account name was found
    if ($result->num_rows > 0) {
        // Fetch the account name
        $row = $result->fetch_assoc();
        $display_name = $row['AccountName'];
    } else {
        $display_name = "Account not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    $display_name = "Invalid account name.";
}




// Initialize the variable to hold the fetched data
$sam_name = '';

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// Check if the account name is not empty
if (!empty($account_name)) {
    // Prepare and execute the SQL query to fetch the data
    $stmt = $conn->prepare("SELECT sam_name FROM sam WHERE AccountName = ?");
    $stmt->bind_param("s", $account_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data was found
    if ($result->num_rows > 0) {
        // Fetch the data
        $row = $result->fetch_assoc();
        $sam_name = $row['sam_name'];
    } else {
        $sam_name = "SAM not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    $sam_name = "Invalid account name.";
}




// Initialize variable to hold fetched data
$region = '';

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Check if the account name is not empty
if (!empty($account_name)) {
    // Prepare and execute the SQL query to fetch the data
    $stmt = $conn->prepare("SELECT region FROM region WHERE AccountName = ?");
    
    // Check if prepare() succeeded
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $account_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data was found
    if ($result->num_rows > 0) {
        // Fetch the data
        $row = $result->fetch_assoc();
        $region = $row['region'];
    } else {
        $region = "Region not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    $region = "Invalid account name.";
}





// Initialize variable to hold fetched data
$country = '';

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Check if the account name is not empty
if (!empty($account_name)) {
    // Prepare and execute the SQL query to fetch the data
    $stmt = $conn->prepare("SELECT country FROM country WHERE AccountName = ?");
    
    // Check if prepare() succeeded
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $account_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data was found
    if ($result->num_rows > 0) {
        // Fetch the data
        $row = $result->fetch_assoc();
        $country = $row['country'];
    } else {
        $country = "Country not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    $country = "Invalid account name.";
}




// Initialize variable to hold fetched data
$information_list = [];

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Check if the account name is not empty
if (!empty($account_name)) {
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
            $information_list[] = $row['information'];
        }
    } else {
        $information_list[] = "No important information found.";
    }

    // Close the statement
    $stmt->close();
} else {
    $information_list[] = "Invalid account name.";
}




// Initialize variable to hold fetched data
$caseData = [];

if (!empty($account_name)) {
    // Determine the last three months
    $currentMonth = date('F');
    $month1 = date('F', strtotime('-1 month'));
    $month2 = date('F', strtotime('-2 months'));
    $lastThreeMonths = [$currentMonth, $month1, $month2];

    // Prepare and execute the SQL query for the last three months
    $stmt = $conn->prepare("
        SELECT Month, Severity, Count 
        FROM in_flow_out_flow 
        WHERE AccountName = ? AND Month IN (?, ?, ?)
    ");
    $stmt->bind_param("ssss", $account_name, $lastThreeMonths[0], $lastThreeMonths[1], $lastThreeMonths[2]);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all case data
    while ($row = $result->fetch_assoc()) {
        $caseData[] = $row;
    }

    // Close the statement
    $stmt->close();
}
// Initialize variable to hold fetched data
$fixData = [];


if (!empty($account_name)) {
    // Determine the last three months
    $currentMonth = date('F');
    $month1 = date('F', strtotime('-1 month'));
    $month2 = date('F', strtotime('-2 months'));
    $lastThreeMonths = [$currentMonth, $month1, $month2];

    // Prepare and execute the SQL query for the last three months
    $stmt = $conn->prepare("
        SELECT Month, 
               SUM(Handling) AS Handling, 
               SUM(Resolved_Without_FIx) AS Resolved_Without_FIx, 
               SUM(Resolved_With_FIx) AS Resolved_With_FIx
        FROM Fix_No_Fix
        WHERE AccountName = ? AND Month IN (?, ?, ?)
        GROUP BY Month
    ");
    $stmt->bind_param("ssss", $account_name, $lastThreeMonths[0], $lastThreeMonths[1], $lastThreeMonths[2]);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all fix data
    while ($row = $result->fetch_assoc()) {
        $fixData[] = $row;
    }

    // Close the statement
    $stmt->close();
}





// Get the last three months including the current month
$current_month = date('M');
$months = [];
for ($i = 0; $i < 3; $i++) {
    $months[] = date('M', strtotime("-$i month"));
}

// Prepare the SQL statement
$sql = "SELECT Month, AHT FROM average_handling_time WHERE AccountName = ? AND Severity = 'S1' AND Month IN (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $account_name, $months[0], $months[1], $months[2]);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['Month']] = $row['AHT'];
}

}

// Close the connection
$conn->close();
?>


<script>
    var caseData = <?php echo json_encode($caseData); ?>;
</script>



<script>
    // Pass PHP data to JavaScript
    var fixData = <?php echo json_encode($fixData); ?>;
</script>

<script>
// PHP array to JavaScript
var caseata = <?php echo json_encode($data); ?>;
var labels = <?php echo json_encode($months); ?>;
</script>


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
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">

        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
			      <li class="nav-section">
        <span class="sidebar-mini-icon">
          <i class="fa fa-ellipsis-h"></i>
        </span>
        <h4 class="text-section">Navigation</h4>
      </li>
<li class="nav-item active">
  <a href="dashboard.php">
    <i class="fas fa-home"></i>
    <p>Dashboard</p>
  </a>
</li>
	<li class="nav-section">
        <span class="sidebar-mini-icon">
          <i class="fa fa-ellipsis-h"></i>
        </span>
        <h4 class="text-section">Components</h4>
      </li>
              <li class="nav-item active">
                <a href="../mainpage.php">
                  <i class="fas fa-money-check"></i>
                  <p>Main Page</p>
               
                </a>
              </li>
<li class="nav-item active">
  <a href="http://sciomeil/sites/PBG-Oncall-Support-Info/Pages/On-Call-Manager.aspx" " target="_blank">
    <i class="fas fa-headphones"></i>
    <p>Oncall Page</p>
  </a>
</li>
<li class="nav-item active">
  <a href="https://confluence/display/AC/FY24+CES+Consolidate+Cadence" " target="_blank">
    <i class="icon-graph"></i>
    <p>CES Consolidate Cadence</p>
  </a>
</li>

			                <li class="nav-item active">
                <a href="../contact.php">
                  <i class="fas fa-headset"></i>
                  <p>Contact Us</p>
               
                </a>
              </li>
      <li class="nav-section">
        <span class="sidebar-mini-icon">
          <i class="fa fa-ellipsis-h"></i>
        </span>
        <h4 class="text-section">List</h4>
      </li>
<li class="nav-item active">
  <a data-bs-toggle="collapse" href="#base">
    <i class="fas fa-th-list"></i>
    <p>Accounts</p>
    <span class="caret"></span>
  </a>
  <div class="collapse" id="base">
    <ul class="nav nav-collapse">
      <li>
        <a href="customer.php?name=XL%20Axiata">
          <span class="sub-item">XL Axiata</span>
        </a>
      </li>
      <li>
        <a href="customer.php?name=ICE">
          <span class="sub-item">ICE</span>
        </a>
      </li>
      <li>
        <a href="customer.php?name=Telnet">
          <span class="sub-item">Telnet</span>
        </a>
      </li>
      <li>
        <a href="customer.php?name=Claro%20Puerto%20Rico">
          <span class="sub-item">Claro Puerto Rico</span>
        </a>
      </li>
      <li>
        <a href="customer.php?name=T-Mobile">
          <span class="sub-item">T-Mobile</span>
        </a>
      </li>
      <li>
        <a href="customer.php?name=Claro%20Brasil">
          <span class="sub-item">Claro Brasil</span>
        </a>
      </li>
      <li>
        <a href="customer.php?name=Azercell">
          <span class="sub-item">Azercell</span>
        </a>
      </li>
      <li>
        <a href="customer.php?name=A1%20Croatia">
          <span class="sub-item">A1 Croatia</span>
        </a>
      </li>
      <li>
        <a href="customer.php?name=PPF">
          <span class="sub-item">PPF</span>
        </a>
      </li>
    </ul>
  </div>
</li>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
<div class="container-fluid">
  <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
    <!-- Removed search functionality -->
  </nav>

  <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
    <!-- Removed search functionality -->

    <li class="nav-item">
      <a class="nav-link logout-btn" href="logout.php">
        <i class="fa fa-sign-out-alt"></i> Logout
      </a>
    </li>
  </ul>
</div>
          </nav>
          <!-- End Navbar -->
        </div>

        <div class="container">
		<?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger"><?php echo nl2br(htmlspecialchars($error_message)); ?></div>
        <?php endif; ?>
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
             <div>
        <h3 class="fw-bold mb-3"><?php echo htmlspecialchars($display_name); ?></h3>
    </div>

            </div>
            <div class="row">
<div class="col-sm-6 col-md-4">
        <div class="card card-stats card-primary card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="text">
                            <p class="card-category">SAM</p>
                            <h4 class="card-title"><?php echo htmlspecialchars($sam_name); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="col-sm-6 col-md-4">
        <div class="card card-stats card-success card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-map"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Region</p>
                            <h4 class="card-title"><?php echo htmlspecialchars($region); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="col-sm-6 col-md-4">
        <div class="card card-stats card-secondary card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-map-marker"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Country</p>
                            <h4 class="card-title"><?php echo htmlspecialchars($country); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

			  </div>
<div class="highlight">
    <h1>Important Information</h1>
    <?php if (!empty($information_list)): ?>
        <?php foreach ($information_list as $info): ?>
            <b><?php echo htmlspecialchars($info); ?></b>
            <p></p>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Add Button -->
    <a href="manage_information.php" class="btn btn-primary">Add Information</a>
</div>
<div class="row">
<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <div class="card-title">In-Flow Out-Flow</div>
        </div>
        <div class="card-body pb-0" style="max-height: 370px; overflow-y: auto;">
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Fix No Fix</div>
        </div>
        <div class="card-body pb-0" style="max-height: 370px; overflow-y: auto;">
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Average Handling Time</div>
            </div>
            <div class="card-body pb-0" style="max-height: 370px; overflow-y: auto;">
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

		 <div class="row">

    <div class="col-md-6">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">About</div>
                </div>
            </div>
            <div class="card-body" style="height: 550px; overflow-y: auto;">
                <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <p><?php echo nl2br(htmlspecialchars($about)); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="col-md-6 adjust-position">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Installed Product</h4>
        </div>
        <div class="card-body pb-0" style="height: 550px; overflow-y: auto;">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="multi-filter-select" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Version</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Product</th>
                                <th>Version</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php if (!empty($installed_products)): ?>
                                <?php foreach ($installed_products as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['Product']); ?></td>
                                    <td><?php echo htmlspecialchars($product['Version']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr><td colspan='2'>No results found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

	</div>
	 <div class="row">
<div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Focal Points</div>
            </div>
            <div class="card-body" style="height: 300px; overflow-y: auto;">
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($focal_points)) : ?>
                            <?php foreach ($focal_points as $focal_point) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($focal_point['Name']); ?></td>
                                    <td><?php echo htmlspecialchars($focal_point['Type']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="2">No focal points found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Agreement</div>
      </div>
      <div class="card-body" style="height: 300px; overflow-y: auto;">
        <table class="table mt-3">
          <thead>
            <tr>
              <th scope="col">Agreement</th>
              <th scope="col">FOPs</th>
              <th scope="col">Start Date</th>
              <th scope="col">End Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($agreements as $agreement): ?>
            <tr>
              <td><?php echo htmlspecialchars($agreement['Agreement']); ?></td>
              <td><?php echo htmlspecialchars($agreement['FOPs']); ?></td>
              <td><?php echo htmlspecialchars($agreement['StartDate']); ?></td>
              <td><?php echo htmlspecialchars($agreement['EndDate']); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
</div>
  </div>



 <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Extension Support</div>
      </div>
      <div class="card-body" style="max-height: 250px; overflow-y: auto;">
        <table class="table mt-3">
          <thead>
            <tr>
              <th scope="col">Financial Year</th>
              <th scope="col">Requested RC</th>
              <th scope="col">Approved RC</th>
              <th scope="col">Approved Till</th>
              <th scope="col">Approved by</th>
              <th scope="col">Comment</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($extension_support as $support): ?>
            <tr>
              <td><?php echo htmlspecialchars($support['FINANCIALYEAR']); ?></td>
              <td><?php echo htmlspecialchars($support['REQUESTEDRC']); ?></td>
              <td><?php echo htmlspecialchars($support['APPROVEDRC']); ?></td>
              <td><?php echo htmlspecialchars($support['APPROVEDTILL']); ?></td>
              <td><?php echo htmlspecialchars($support['APPROVEDBY']); ?></td>
              <td><?php echo htmlspecialchars($support['COMMENT']); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
</div>

  </div>
    


     </div>




        <footer class="footer">
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


	<script>
  $(document).ready(function () {
    $("#multi-filter-select").DataTable({
      pageLength: 5,
      initComplete: function () {
        this.api()
          .columns()
          .every(function () {
            var column = this;
            var select = $(
              '<select class="form-select"><option value=""></option></select>'
            )
              .appendTo($(column.footer()).empty())
              .on("change", function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                column
                  .search(val ? "^" + val + "$" : "", true, false)
                  .draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append(
                  '<option value="' + d + '">' + d + "</option>"
                );
              });
          });
      },
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
        // Get the popup and close button elements
        const popup = document.getElementById('popup');
        const closeBtn = document.getElementById('close-btn');

        // Add an event listener to the close button
        closeBtn.addEventListener('click', () => {
            // Hide the popup when the close button is clicked
            popup.style.display = 'none';
        });
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Check if caseData is defined
        if (typeof caseData !== 'undefined' && caseData.length > 0) {
            // Initialize arrays to hold data for each severity level
            var labels = ['June', 'July', 'August']; // Last three months
            var s1Data = [0, 0, 0];
            var s2Data = [0, 0, 0];
            var s3Data = [0, 0, 0];
            var s4Data = [0, 0, 0];

            // Parse the case data and populate the arrays
            caseData.forEach(function(caseEntry) {
                var month = caseEntry.Month;
                var severity = caseEntry.Severity;
                var count = parseInt(caseEntry.Count);

                switch (month) {
                    case "June":
                        if (severity === "S1") s1Data[0] = count;
                        if (severity === "S2") s2Data[0] = count;
                        if (severity === "S3") s3Data[0] = count;
                        if (severity === "S4") s4Data[0] = count;
                        break;
                    case "July":
                        if (severity === "S1") s1Data[1] = count;
                        if (severity === "S2") s2Data[1] = count;
                        if (severity === "S3") s3Data[1] = count;
                        if (severity === "S4") s4Data[1] = count;
                        break;
                    case "August":
                        if (severity === "S1") s1Data[2] = count;
                        if (severity === "S2") s2Data[2] = count;
                        if (severity === "S3") s3Data[2] = count;
                        if (severity === "S4") s4Data[2] = count;
                        break;
                }
            });

            // Debug: Log the data arrays
            console.log("Labels:", labels);
            console.log("S1 Data:", s1Data);
            console.log("S2 Data:", s2Data);
            console.log("S3 Data:", s3Data);
            console.log("S4 Data:", s4Data);

            // Create the bar chart
            var barChart = document.getElementById("barChart").getContext("2d");
            var myBarChart = new Chart(barChart, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "S1 Cases",
                            backgroundColor: "rgb(255, 99, 132)",
                            borderColor: "rgb(255, 99, 132)",
                            borderWidth: 1,
                            data: s1Data
                        },
                        {
                            label: "S2 Cases",
                            backgroundColor: "rgb(54, 162, 235)",
                            borderColor: "rgb(54, 162, 235)",
                            borderWidth: 1,
                            data: s2Data
                        },
                        {
                            label: "S3 Cases",
                            backgroundColor: "rgb(255, 206, 86)",
                            borderColor: "rgb(255, 206, 86)",
                            borderWidth: 1,
                            data: s3Data
                        },
                        {
                            label: "S4 Cases",
                            backgroundColor: "rgb(75, 192, 192)",
                            borderColor: "rgb(75, 192, 192)",
                            borderWidth: 1,
                            data: s4Data
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        } else {
            console.log("No case data found for the specified account.");
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof fixData !== 'undefined' && fixData.length > 0) {
            var totalHandling = 0;
            var totalResolvedWithoutFix = 0;
            var totalResolvedWithFix = 0;

            // Aggregate totals
            fixData.forEach(function(entry) {
                totalHandling += parseInt(entry.Handling);
                totalResolvedWithoutFix += parseInt(entry.Resolved_Without_FIx);
                totalResolvedWithFix += parseInt(entry.Resolved_With_FIx);
            });

            // Data for pie chart
            var pieChart = document.getElementById("pieChart").getContext("2d");
            var myPieChart = new Chart(pieChart, {
                type: "pie",
                data: {
                    datasets: [{
                        data: [totalHandling, totalResolvedWithoutFix, totalResolvedWithFix],
                        backgroundColor: ["#1d7af3", "#f3545d", "#36a2eb"],
                        borderWidth: 0
                    }],
                    labels: ["Handling", "Resolved Without Fix", "Resolved With Fix"]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: "bottom",
                        labels: {
                            fontColor: "rgb(154, 154, 154)",
                            fontSize: 11,
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    pieceLabel: {
                        render: "percentage",
                        fontColor: "white",
                        fontSize: 14
                    },
                    tooltips: false,
                    layout: {
                        padding: {
                            left: 20,
                            right: 20,
                            top: 20,
                            bottom: 20
                        }
                    }
                }
            });
        } else {
            console.log("No fix data found for the specified account.");
        }
    });
</script>
<script>
        var ctx = document.getElementById("lineChart").getContext("2d");

        // Initialize data array for S1 severity level
        var s1Data = [];

        // Populate data array with values from caseData
        labels.forEach(function(month) {
            s1Data.push(caseata[month] ? caseata[month] : 0);
        });

        var myLineChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "S1 Cases",
                        borderColor: "#ff6384",
                        pointBorderColor: "#FFF",
                        pointBackgroundColor: "#ff6384",
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 1,
                        pointRadius: 4,
                        backgroundColor: "transparent",
                        fill: true,
                        borderWidth: 2,
                        data: s1Data
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "bottom",
                    labels: {
                        padding: 10,
                        fontColor: "#1d7af3"
                    }
                },
                tooltips: {
                    bodySpacing: 4,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                layout: {
                    padding: {left: 15, right: 15, top: 15, bottom: 15}
                }
            }
        });
    </script>

	

  </body>
</html>