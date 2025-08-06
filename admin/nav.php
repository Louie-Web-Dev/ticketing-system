<?php
ob_start();
require_once "database.php";

$pendingCount = 0;


$sql_count = "SELECT COUNT(*) AS total FROM concerns WHERE status = 'pending'";
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
    <title>Toyota IT Ticketing System</title>
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
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
    <div class="container">
        <label for="title" id="text-one">Toyota IT Ticketing System |</label>
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
            <h1 style="display: inline-block; margin-right: 20px;">Welcome! <?php echo  $_SESSION['name']; ?></h1>

            <div style="position: relative; display: inline-block; margin-top: 20px;">
                <i class="fa fa-bell" aria-hidden="true" onclick="toggleDropdown()"></i>
                <span id="noti_number">0</span>
                <div class="dropdown">
                    <div class="dropdown-content" id="notificationContent">

                    </div>
                </div>
            </div>

            <script type="text/javascript">
                function loadDoc() {
                    setInterval(function() {
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                document.getElementById("noti_number").innerHTML = this.responseText;
                                updateNotificationContent();
                            }
                        };
                        xhttp.open("GET", "get_notify.php", true);
                        xhttp.send();
                    }, 1000);
                }

                function toggleDropdown() {
                    var dropdown = document.querySelector('.dropdown');
                    dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
                }

                function updateNotificationContent() {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("notificationContent").innerHTML = this.responseText;
                            addClickEventToNotifications(); // Add click event to each notification
                        }
                    };
                    xhttp.open("GET", "get_notify_content.php", true);
                    xhttp.send();
                }

                function addClickEventToNotifications() {
                    var notifications = document.querySelectorAll('.notification-item');
                    notifications.forEach(function(notification) {
                        notification.addEventListener('click', function() {

                            window.location.href = 'pending.php';
                        });
                    });
                }

                loadDoc();
            </script>

            <script>
                function toggleDropdown() {
                    const dropdown = document.querySelector('.dropdown');
                    dropdown.classList.toggle('show');
                }
            </script>

            <a class="button" onclick="logout()">

                <i class="fa-solid fa-user-tie"></i>

                <div class="logout">LOGOUT</div>
            </a>
        </div>
    </div>

    <script>
        // logout js
        function logout() {
            var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
            logoutModal.show();
        }
    </script>

    <div class="navbar">
        <div class="header">
            <a href="dashboard.php">
                <img src="../images/logo2.png" alt="" style="background-color: transparent; width: 160px; height: 130px;">
                <hr>
            </a>
        </div>

        <div class="nav">
            <div class="dashboard bg-transparent">
                <a href="dashboard.php" class="bg-transparent">
                    <i class="fa-solid fa-table-columns bg-transparent" style="padding-right: 3px;"></i>
                    <span class="bg-transparent no-underline">DASHBOARD</span>
                </a>
            </div>

            <div class="pending bg-transparent">
                <a href="pending.php" class="bg-transparent">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <span class="bg-transparent no-underline">PENDING</span>
                    <?php if ($pendingCount >= 0): ?>
                        <span class="badge bg-danger position-absolute top-1 start-95 translate-middle rounded-pill" id="pendingBadge" style="display: none;">
                            <?= $pendingCount ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>

            <div class=" history bg-transparent">
                <a href="history.php" class="bg-transparent">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span class="bg-transparent no-underline">HISTORY</span>
                </a>
            </div>

            <div class=" history bg-transparent">
                <a href="chat/users.php" class="bg-transparent">
                    <i class="fa-solid fa-comment-dots"></i>
                    <span class="bg-transparent no-underline">CHAT</span>
                </a>
            </div>

            <div class="user bg-transparent">
                <a href="view_user.php" class="bg-transparent">
                    <i class="fa-solid fa-user-tie bg-transparent" style="padding-right: 7px;"></i>
                    USERS
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
                const toggleButtons = document.querySelectorAll('.report-toggle-button');

                toggleButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();

                        const reportList = this.nextElementSibling;

                        reportList.style.display = (reportList.style.display === 'none' || reportList.style.display === '') ? 'block' : 'none';

                        sessionStorage.setItem('reportListState', reportList.style.display);

                        toggleButtons.forEach(otherButton => {
                            if (otherButton !== this) {
                                otherButton.nextElementSibling.style.display = 'none';
                            }
                        });
                    });

                    const storedState = sessionStorage.getItem('reportListState');
                    if (storedState) {
                        button.nextElementSibling.style.display = storedState;
                    }
                });
            });
        </script>


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toggleButtons = document.querySelectorAll('.toggle-button');

                toggleButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();

                        const reports = this.nextElementSibling;

                        reports.style.display = (reports.style.display === 'none' || reports.style.display === '') ? 'block' : 'none';

                        sessionStorage.setItem('reportsState', reports.style.display);

                        toggleButtons.forEach(otherButton => {
                            if (otherButton !== this) {
                                otherButton.nextElementSibling.style.display = 'none';
                            }
                        });
                    });

                    const storedState = sessionStorage.getItem('reportsState');
                    if (storedState) {
                        button.nextElementSibling.style.display = storedState;
                    }
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toggleNavButton = document.getElementById('toggleNavButton');
                const navContent = document.querySelector('.navbar .nav');

                const storedNavState = sessionStorage.getItem('navState');
                if (storedNavState) {
                    navContent.style.display = storedNavState;
                }

                toggleNavButton.addEventListener('click', function() {

                    if (navContent.style.display === 'none' || navContent.style.display === '') {
                        navContent.style.display = 'block';
                    } else {
                        navContent.style.display = 'none';
                    }

                    sessionStorage.setItem('navState', navContent.style.display);
                });
            });
        </script>

        <script>
            window.addEventListener('resize', function() {
                const navContent = document.querySelector('.navbar .nav');
                const toggleBtn = document.querySelector('.togglebtn');

                if (window.innerWidth > 1555) {
                    navContent.style.display = 'block';
                    sessionStorage.removeItem('navState');
                } else {
                    if (sessionStorage.getItem('navState') === 'none') {
                        navContent.style.display = 'none';
                    }
                }
            });
        </script>

    </div>

    <style>
        @import url(https://fonts.googleapis.com/css?family=Oswald:400);
        @import url('https://fonts.googleapis.com/css2?family=Crimson+Text&family=Merriweather:wght@300&family=Oswald:wght@300;400&family=Rubik&family=Tiro+Tamil&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@300&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Crimson+Text&family=Oswald:wght@300&family=Tiro+Tamil&display=swap');

        .color-bg {
            position: fixed;
            height: 80px;
            width: 82%;
            background-color: #418bebff;
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
            background-color: black;

            border-radius: 15px;
            border: 1px black solid;
            display: flex;
            z-index: 1;
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
            top: 20px;
            left: 20%;
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
            position: absolute;
            /* Changed */
            background-color: #f9f9f9;
            min-width: 200px;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
            z-index: 100;
            color: black;
            top: 35px;
            /* Distance below bell */
            right: 0;
            /* Align to right of bell */
            border-radius: 7px;
            transition: opacity 0.3s ease;
        }

        .dropdown {
            display: none;
        }

        .dropdown.show {
            display: block;
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
            background: black;
            border-radius: 7px;
        }

        .notification-item {
            display: flex;
            flex-direction: column;
            padding: 12px;
            border-bottom: 1px solid #ddd;
            /* Add a border between notifications */
            cursor: pointer;
            /* Add cursor pointer */
            transition: background-color 0.3s;
            /* Add smooth transition for background color */
            background-color: white;
        }

        .notification-item:hover {
            background-color: #f0f0f0;
            /* Add the background color you want on hover */
        }

        .detail-label,
        .detail-value {
            font-weight: 400;
            margin-bottom: 4px;
            background-color: white;
            transition: background-color 0.3s;
            /* Add smooth transition for background color */
        }

        .notification-item:hover .detail-label,
        .notification-item:hover .detail-value {
            background-color: #f0f0f0;
            /* Add the background color you want on hover */
        }

        .detail-label {
            font-weight: 400;
            margin-bottom: 4px;
            background-color: white;
        }

        .detail-value {
            margin-bottom: 8px;
            background-color: white;
        }

        .notification-item .detail-label {
            font-weight: 400;
            margin-bottom: 4px;
            background-color: white;
        }

        .notification-item .detail-value {
            margin-bottom: 8px;
            background-color: white;
        }

        #noti_number {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #EE4B2B;
            color: white;
            font-weight: bold;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 50%;
            display: inline-block;
            min-width: 18px;
            text-align: center;
            line-height: 1;
        }


        .fa-bell {
            font-size: 25px;
            cursor: pointer;
        }

        #noti_number:hover {
            cursor: pointer;
            /* Change cursor to pointer on hover */
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

    <script>
        // Store the previous count
        let lastCount = <?php echo $pendingCount; ?>;

        // Function to play sound and log it
        function playNotificationSound() {
            console.log("[Sound] Playing notification sound!"); // Log sound event
            // Example: Use the Web Audio API or an <audio> element
            const audio = new Audio('notif/notif.mp3'); // Replace with your sound file
            audio.play().catch(e => console.error("Sound error:", e));
        }

        // Function to check for count changes
        function checkPendingUpdates() {
            fetch('get_pending_count.php')
                .then(response => response.json())
                .then(data => {
                    const currentCount = data.count;
                    const badge = document.getElementById('pendingBadge');

                    // Only trigger when count increased
                    if (currentCount > lastCount) {
                        badge.textContent = currentCount;
                        console.log(`[Notification] New pending tickets! (Total: ${currentCount})`);
                        playNotificationSound(); // Play sound + log
                    } else if (currentCount != lastCount) {
                        badge.textContent = currentCount;
                        console.log(`[Notification] Pending count changed to: ${currentCount}`);
                    }

                    lastCount = currentCount;
                })
                .catch(error => console.error('Fetch error:', error));
        }

        // Check every 3 seconds
        setInterval(checkPendingUpdates, 1000);

        // Initial check
        checkPendingUpdates();
    </script>


</body>

</html>