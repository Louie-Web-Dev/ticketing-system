<?php
ob_start();
include 'database.php';

$pendingCount = 0;

// Query to get count of pending concerns
$sql_count = "SELECT COUNT(*) AS total FROM concerns WHERE status = 'pending' AND name = '$name_q'";
$result = mysqli_query($conn, $sql_count);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $pendingCount = (int)$row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toyota</title>
    <link rel="icon" type="image/x-icon" href="../admin\Image\sto_thomas.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/971c1cface.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <link rel="icon" type="image/x-icon" href="images/logo2.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

    <script>
        function logout() {
            var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
            logoutModal.show();
        }
    </script>



</head>

<body>
    <div class="container">
        <h1 for="title" id="text-one">Toyota IT Ticketing System |</h1>
        <h1 id="datetime"></h1>
        <script>
            function updateDateTime() {
                const datetimeElement = document.getElementById("datetime");
                const currentDate = new Date();
                datetimeElement.textContent = currentDate.toLocaleString();
            }
            setInterval(updateDateTime, 1000);
        </script>

        <div class="navigation">

            <h1 style="display: inline-block;"> <?php echo  $_SESSION['name']; ?></h1>

            <a class="button" onclick="logout()">

                <i class="fa-solid fa-user-tie"></i>

                <div class="logout">LOGOUT</div>
            </a>
        </div>
    </div>

    <div class="navbar">
        <div class="header">
            <a href="#">
                <img src="../images/logo2.png" alt="" style="background-color: transparent; width: 160px; margin-top: 15px;">
                <hr>
            </a>
        </div>

        <div class="nav">

            <div class="add-report bg-transparent position-relative">
                <a href="user_pending.php" class="bg-transparent text-decoration-none text-dark">
                    <i class="fa-solid fa-hourglass-half bg-transparent"></i>
                    Pending
                    <?php if ($pendingCount > 0): ?>
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">
                            <?= $pendingCount ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>

            <div class="report-list bg-transparent">
                <a href="create_ticket.php" class="bg-transparent">
                    <i class="fa-solid fa-plus-circle bg-transparent" style="padding-right: 3px;"></i>
                    Create Ticket
                </a>
            </div>

            <div class="report-list bg-transparent">
                <a href="user_history.php" class="bg-transparent">
                    <i class="fa-solid fa-history bg-transparent" style="padding-right: 3px;"></i>
                    History
                </a>
            </div>
        </div>


        <div class="togglebtn">
            <button id="toggleNavButton" class="toggle-nav-button bg-transparent">
                <i class="fa-solid fa-grip"></i>
            </button>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toggleNavButton = document.getElementById('toggleNavButton');
                const navContent = document.querySelector('.navbar .nav');

                // Always show nav by default
                navContent.style.display = 'block';

                toggleNavButton.addEventListener('click', function() {
                    if (navContent.style.display === 'none') {
                        navContent.style.display = 'block';
                    } else {
                        navContent.style.display = 'none';
                    }
                });
            });
        </script>

    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>


    <style>
        @import url(https://fonts.googleapis.com/css?family=Oswald:400);
        @import url('https://fonts.googleapis.com/css2?family=Crimson+Text&family=Merriweather:wght@300&family=Oswald:wght@300;400&family=Rubik&family=Tiro+Tamil&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@300&display=swap');

        .navigation h1 {
            font-family: 'Inter', sans-serif;
        }

        .color-bg {
            position: fixed;
            height: 80px;
            width: 82%;
            background-color: rgb(161, 8, 8);
            right: 0;
            z-index: 0;
        }

        .container {
            border: 1px white solid;
            position: fixed;
            margin: 0.7%;
            right: 0;
            height: 65px;
            min-width: 82%;
            margin-left: 100px;
            background-color: #343a40;
            border-radius: 15px;
            border: 1px black solid;
            display: flex;
            z-index: 1;
        }

        .add-report {
            position: relative;
            display: inline-block;
            margin-top: 60px;
        }

        .add-report .badge {
            font-size: 0.7rem;
            padding: 0.4em 0.6em;
        }


        h1 {
            margin: 3.5px;
            margin-top: 22px;
            margin-left: 10px;
            color: white;
            background-color: transparent;
            font-family: 'Rubik', sans-serif;
            font-size: 23px;
            font-family: 'Merriweather Sans', sans-serif;
        }

        #text-one {
            font-family: 'Merriweather Sans', sans-serif;
            margin-top: 16px;
            font-size: 23px;
        }


        #datetime {
            color: white;
            font-size: 20px;
        }

        .navigation {
            position: absolute;
            margin-top: -7px;
            right: 5px;
            background-color: transparent;
        }

        .navigation i {
            color: white;
            margin-left: -2px;
            font-size: 25px;
            background-color: transparent;
            margin-top: -1px;
        }

        .logout {
            padding-left: 25px;
            margin-top: -35px;
            background-color: transparent;
            color: white;
            font-size: 15px;
            font-family: 'Oswald', sans-serif;
            position: relative;
            overflow: hidden;
            letter-spacing: 1px;
            opacity: 0;
            transition: opacity .45s;
            -webkit-transition: opacity .35s;
        }

        .button {
            text-decoration: none;
            float: right;
            padding: 12px;
            margin: 15px;
            width: 50px;
            background-color: transparent;
            transition: width .35s;
            -webkit-transition: width .35s;
            overflow: hidden;
        }

        .navigation a:hover {
            border-radius: 15px;
            background-color: rgb(199, 78, 78);
            width: 100px;
        }

        .navigation a:hover .logout {
            opacity: .9;
        }

        .navigation a {
            text-decoration: none;
        }

        .navbar {
            position: fixed;
            width: 250px;
            height: 98.8%;
            background-color: #ffffff;
            font-family: 'Mulish', sans-serif;
            font-weight: 600;
            margin: 0.4%;
            border-radius: 15px;
            border: 1px black solid;
        }

        .header {
            background-color: transparent;
        }

        a {
            color: black;
            cursor: pointer;
        }

        .header img {
            position: absolute;
            top: 10px;
            left: 15%;
        }

        .container label {
            color: white;
            text-align: left;
            margin: 3.5px;
            margin-top: 12px;
            font-family: 'Mulish', sans-serif;
            font-weight: light;
            font-size: 25px;
        }

        #text-two {
            margin-left: -286px;
            background-color: transparent;
        }

        .nav {
            position: absolute;
            font-size: 20px;
            top: 9.5rem;
            margin: 20px;
            background-color: white;
            display: flex;
            flex-direction: column;
        }

        .nav a,
        i {
            color: black;
            text-decoration: none;
            margin: 10px;
        }

        .reports {
            display: none;
        }

        .toggle-button {
            cursor: pointer;
        }

        .nav a {
            font-family: 'Merriweather Sans', sans-serif;
            font-weight: bold;
            color: black;
            background-color: white;
        }

        .nav a:after {
            content: "";
            position: absolute;
            background-color: blue;
            height: 3px;
            width: 0;
            left: 0;
            bottom: -10px;
            transition: 0.4s;
        }

        .aksyon-bilis img {
            position: absolute;
            left: 15px;
            bottom: 4%;
            height: 140px;
            width: 240px;
        }

        .reports {
            background-color: transparent;
            margin-left: 50px;
            font-size: 17px;
        }

        .reportList {
            display: none;
        }

        .reportList {
            background-color: transparent;
            margin-left: 50px;
            font-size: 17px;
        }

        .nav a:hover {
            color: grey;
            background-color: white;
            transition-duration: 0.4s;

        }

        .dropdown {
            display: none;
            position: fixed;
            background-color: #f9f9f9;
            min-width: 200px;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
            z-index: 100;
            color: black;
            top: 1.5%;
            right: 9%;
            margin-top: 10px;
            border-radius: 7px;
        }

        .dropdown::-webkit-scrollbar {
            display: block;
            width: 5px;
        }

        .dropdown::-webkit-scrollbar-thumb {
            background: white;
            border-radius: 15px;
        }

        .dropdown::-webkit-scrollbar-track {
            background: #f9f9f9;
            border-radius: 7px;
        }

        .togglebtn {
            display: none;
        }

        .nav {
            display: block;
        }

        @media screen and (max-width: 1555px) and (min-width: 320px) {

            .nav,
            .header img,
            .aksyon-bilis,
            .container label {
                display: none;
            }

            .container {
                max-width: 91.8%;
            }

            .toggle-nav-button,
            .togglebtn {
                display: block;
            }

            .navbar {
                width: 7%;
                height: 65px;
                border-radius: 15px;
                z-index: 1;
                border-radius: 7px;
            }

            .togglebtn {
                background-color: transparent;
                margin: auto;
                margin-top: 0%;
                text-align: center;
                font-size: 25px;
            }

            .togglebtn:hover {
                background-color: transparent;
                font-size: 32px;
                transition-duration: 0.3s;
                padding: 0;
            }

            .nav {
                top: 25px;

            }

            .nav {
                width: 200px;
                border-radius: 7px;
                padding-top: 10px;
                padding-bottom: 10px;
                background-color: #f2f1ed;
            }
        }

        @media screen and (max-width: 1950px) and (min-width: 1620px) {
            .container {
                max-width: 85.4%;
            }
        }
    </style>


</body>

</html>