<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["pos"] !== "admin") {
    header("Location: /TSP-System/ticketing-system/");
    exit();
}

require_once "database.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toyota</title>
    <?php include 'nav.php'; ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <div class="dashboardContainer">
        <div class="displayCount bg-transparent">
            <h1>DISPLAY COUNT</h1>

            <div id="dashboardContent"></div>
                <script>
                function updateDashboard() {
                    $.ajax({
                        url: 'get_count.php',
                        type: 'GET',
                        success: function (data) {
                           
                            $('#dashboardContent').html(data);
                        },
                        error: function (error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                }
                setInterval(updateDashboard, 1000);
                updateDashboard();
                </script>

        </div>

         <hr style="margin:10px">

        <div class="first-section">
            <div class="overview-section">


            </div>

           <div class="ranking-section">
    <table>
        <thead>
            <tr>
                <th>Person In-Charge (PIC)</th>
                <th>Number of Concerns Solved</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once "database.php";

            $sql = "SELECT pic, COUNT(*) AS concern_count 
                    FROM concerns 
                    WHERE pic IS NOT NULL AND TRIM(pic) != '' 
                    GROUP BY pic 
                    ORDER BY concern_count DESC 
                    LIMIT 10";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
            ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['pic']); ?></td>
                        <td><?php echo htmlspecialchars($row['concern_count']); ?></td>
                    </tr>
                <?php endwhile;
            else: ?>
                <tr>
                    <td colspan="10">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>






        </div>

           




    </div>

        


    <style>

        body {
            background-color: rgb(221, 221, 221);

        }

        .dashboardContainer {
            display: flex;
            flex-direction: column;
            background-color: white;
            width: 85.5%;
            height: 91%;
            position: fixed;
            right: 10px;
            margin-top: 83px;
            border: 1px black solid;
            border-radius: 15px;
            padding-bottom: 20px;

            overflow-y: auto;
            /* Enable vertical scroll */
            overflow-x: hidden;
        }

        .displayCount h1 {
            color: black;
            font-family: 'Mulish', sans-serif;
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
        }

        .numbersContainer {   
            background-color: #e7e7e7;
            width: 70%;
            height: fit-content;
            margin: 10px auto;
            text-align: center;
            font-family: 'Mulish', sans-serif;
            border-radius: 15px;
            font-weight: bold;
            display: grid;
            grid-template-columns: repeat(2, auto);
            z-index: 1;
       }

       .numbersContainer label {
            background-color: transparent;
            color: black;
            margin: 10px 5%;
       }

       .numbersContainer span {
            background-color: #e7e7e7;
            margin-top: -30px;
            color: black;
            font-size: 70px;
       }



       /* <---first-section--> */
        .first-section {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            gap: 20px;
        }

        .overview-section{
            width: 70%;
            overflow-x: auto;
        }


        .ranking-section{
            width: 30%;
            overflow-x: auto;
        }
        .ranking-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .ranking-section th,
        .ranking-section td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .ranking-section th {
            background-color: #418bebff;
            color: white;
        }


    </style>



</body>

</html>