<?php
// Include config file
require_once "config.php";
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: index.php");
  exit;
}

// Define variables and initialize with empty values
$result = array();

// Processing form data when form is submitted
// query to database
$sql = "SELECT title, content, created_at FROM notes WHERE user_id = ?";

// mysqli_prepare will return false if error occured
if($stmt = mysqli_prepare($dbConn, $sql)){

    // bind var to make param of prepared statement
    mysqli_stmt_bind_param($stmt, "s", $paramUserID);

    // set username parameter in the query to $username
    $paramUserID = $_SESSION["id"];

    // execute the prepared statement
    if(mysqli_stmt_execute($stmt)){

        $meta = $stmt->result_metadata(); 
        while ($field = $meta->fetch_field()) 
        { 
            $params[] = &$row[$field->name]; 
        }

        call_user_func_array(array($stmt, 'bind_result'), $params); 

        while ($stmt->fetch()) { 
            foreach($row as $key => $val) 
            { 
                $c[$key] = $val; 
            } 
            $result[] = $c; 
        } 

        $stmt->close(); 

    } else{
        // unexpected error
        $res = "Unexpected error occured. Please try again later.";
    }
}

// Close connection
mysqli_close($dbConn);
?>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
        <link rel="icon" type="image/png" href="../assets/img/favicon.png">
        <title>Notey. 📝</title>
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <!-- Nucleo Icons -->
        <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
        <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
        <!-- rc="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
        <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
        <!-- CSS Files -->
        <link rel="stylesheet" href="extensions/sticky-header/bootstrap-table-sticky-header.css">
        <script src="extensions/sticky-header/bootstrap-table-sticky-header.js"></script>
        <style>
            .outer-wrapper{height: 80vh; margin-left:15vw; display: flex; margin: auto; justify-content: center; align-items: center;}
            .inner-wrapper{position: absolute; background-color: white; border-radius: 10px; padding: 30px; left: 20vw; top: 24vh; bottom: 10vh; width: 60vw; overflow: scroll;}
            .main-content{width: 100vw; height: 100vh;}
            .all-notes-title{position: absolute; left: 25vw; top: 15vh;}
            .all-notes-list{display: flex; flex-direction: column; row-gap: 40px;}
            .card{padding: 30px; width: 100%;}
            
        </style>
        <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.6" rel="stylesheet" />
    </head>
    <body class="g-sidenav-show  bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main" style="padding-top: 15vh;">
        <hr class="horizontal dark mt-0">
        <div class="text-center">
        <h2>Notey. 📝</h2>
        </div>
        <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item mb-2"">
            <a class="nav-link  active" href="homepage.php">
                <span class="nav-link-text ms-1">🏠 Home</span>
            </a>
            </li>
            <li class="nav-item mb-2"">
            <a class="nav-link  active" href="newNotes.php">
                <span class="nav-link-text ms-1">➕ New Note</span>
            </a>
            </li>
            <li class="nav-item mb-2"">
            <a class="nav-link  active" href="allNotes.php">
                <span class="nav-link-text ms-1">📖 View All Notes</span>
            </a>
            </li>
            <li class="nav-item mb-2"">
            <a class="nav-link  active" href="resetPassword.php">
                <span class="nav-link-text ms-1">🔐 Reset Password</span>
            </a>
            </li>
            <?php if ($_SESSION["role"] == 1){ ?>
            <li class="nav-item mb-2"">
            <a class="nav-link  active" href="allUser.php">
                <span class="nav-link-text ms-1">👥 View All User</span>
            </a>
            </li>
            <?php }?>
            <li class="nav-item mb-2"">
            <a class="nav-link  active" href="logout.php">
                <span class="nav-link-text ms-1">🚪 Logout</span>
            </a>
            </li>
        </ul>
        </div>
    </aside>
    <div class="outer-wrapper">
        <div class="main-content d-flex justify-content-center">
            <?php 
            if (count($result) < 1) {
                echo "<h1>You haven't take any notes!📝</h1>";
            } else {
                echo '<h1 class="all-notes-title">List of all your notes!📝</h1>';
                echo '<div class="inner-wrapper">';
                echo '<div class="all-notes-list">';
                foreach ($result as $row) 
                {
                        echo '
                        <div class="card">
                        <h5 class="card-title">';
                        echo  array_values($row)[0];
                        echo '</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Created At:';
                        echo  array_values($row)[2];
                        echo'</h6>
                        <p class="card-text">';
                        echo  array_values($row)[1];
                        echo '</p>
                        </div>
                        ';
                }
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    </div>
    <!--   Core JS Files   -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <script>
        var ctx = document.getElementById("chart-bars").getContext("2d");

        new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
            label: "Sales",
            tension: 0.4,
            borderWidth: 0,
            borderRadius: 4,
            borderSkipped: false,
            backgroundColor: "#fff",
            data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
            maxBarThickness: 6
            }, ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
            legend: {
                display: false,
            }
            },
            interaction: {
            intersect: false,
            mode: 'index',
            },
            scales: {
            y: {
                grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                },
                ticks: {
                suggestedMin: 0,
                suggestedMax: 500,
                beginAtZero: true,
                padding: 15,
                font: {
                    size: 14,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                },
                color: "#fff"
                },
            },
            x: {
                grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false
                },
                ticks: {
                display: false
                },
            },
            },
        },
        });


        var ctx2 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
        gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

        new Chart(ctx2, {
        type: "line",
        data: {
            labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Mobile apps",
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#cb0c9f",
                borderWidth: 3,
                backgroundColor: gradientStroke1,
                fill: true,
                data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                maxBarThickness: 6

            },
            {
                label: "Websites",
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#3A416F",
                borderWidth: 3,
                backgroundColor: gradientStroke2,
                fill: true,
                data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
                maxBarThickness: 6
            },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
            legend: {
                display: false,
            }
            },
            interaction: {
            intersect: false,
            mode: 'index',
            },
            scales: {
            y: {
                grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5]
                },
                ticks: {
                display: true,
                padding: 10,
                color: '#b2b9bf',
                font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                },
                }
            },
            x: {
                grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5]
                },
                ticks: {
                display: true,
                color: '#b2b9bf',
                padding: 20,
                font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                },
                }
            },
            },
        },
        });
    </script>
        <script>
            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }
        </script>
        <!-- Github buttons -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.6"></script>
    </body>
</html>
