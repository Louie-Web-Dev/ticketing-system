<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["pos"])) {
    switch ($_SESSION["pos"]) {
        case "admin":
            header("Location: /TSP-system/ticketing-system/admin/dashboard.php");
            break;
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toyota</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="images/logo2.png">
    <link rel="shortcut icon" type="x-icon" href="images/logo2.png">

</head>

<body>
    <div class="custom-container">
        <div class="cont-2">
            <img src="images/logo1.png" id="logo2" height="100px" width="200px">
            <h1>Toyota IT<br>Ticketing System</h1>

            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="username" placeholder="Enter username:" name="username" class="form-control" required autocomplete="off">
                    <div class="icon">
                        <i class="fa-solid fa-user bg-transparent"></i>
                    </div>
                </div>
                <div class="form-group ">
                    <input type="password" placeholder="Enter Password:" name="password" class="form-control" required autocomplete="off">
                    <div class="icon2">
                        <i class="fa-solid fa-lock bg-transparent"></i>
                    </div>
                </div>

                <?php
                if (isset($_POST["login"])) {
                    $username = $_POST["username"];
                    $password = $_POST["password"];

                    require_once "database.php";

                    $sql = "SELECT * FROM user WHERE username = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "s", $username);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    if ($user) {
                        // Compare the entered password with the stored password
                        if ($password == $user["password"]) {
                            $_SESSION["user"] = "yes";
                            $_SESSION["username"] = $user["username"];
                            $_SESSION["pos"] = $user["pos"];
                            $_SESSION["fullName"] = $user["full_name"];

                            // Redirect based on position
                            switch ($_SESSION["pos"]) {
                                case "admin":
                                    header("Location: /TSP-system/ticketing-system/admin/dashboard.php");
                                    exit();
                                case "":
                                case null: // Handles both empty string and null
                                    header("Location: /TSP-system/ticketing-system/user/user_dashboard.php");
                                    exit();
                            }
                        } else {
                            $_SESSION["error_message"] = "Invalid password!";
                        }
                    } else {
                        $_SESSION["error_message"] = "Username does not exist!";
                    }

                    // Always redirect back to login page after checking
                    header("Location: index.php");
                    exit();
                }
                ?>


                <div class="form-btn">
                    <input type="submit" value="Login" name="login" class="btn btn-primary">
                </div>


            </form>

            <div>
                <p>Don't have an account? <a href="mailto:admin@company.com?subject=Request%20for%20Account%20Creation" onclick="openOutlook()">Contact Admin</a></p>
            </div>

            <script>
                function openEmailClient() {
                    const subject = encodeURIComponent("Request for Account Creation");
                    const body = encodeURIComponent(`Dear [Admin's Name or Team],
                    I hope this email finds you in good health and high spirits. I am writing to formally request the creation of a new account within our organization's system.

                    Below are the details for the account to be created:

                    1. Full Name of Account Holder: [Your Full Name]
                    2. Position/Role: [Your Position/Role]
                    3. Department: [Your Department]
                    5. Contact Information:
                        - Email: [Your Email Address]
                        - Phone: [Your Phone Number]

                    Reason for Account Creation:
                    [Provide a brief explanation of why you need this account, its intended use, and how it will contribute to your role and the organization's objectives.]

                    Access and Permissions:
                    [Specify the access and permissions you require for this account, ensuring it aligns with your role and responsibilities.]

                    Additional Information (if any):
                    [Include any additional information or requirements related to the account setup, if applicable.]

                    I appreciate your prompt attention to this matter and kindly request that you confirm receipt of this request. If any further information or clarification is needed, please feel free to reach out to me.

                    Thank you for your assistance.

                    Best regards,
                    [Your Full Name]
                    [Your Position/Role]
                    [Your Contact Information]`);

                    const mailtoLink = `mailto:admin@company.com?subject=${subject}&body=${body}`;
                    window.location.href = mailtoLink; // Open the email client directly
                }
            </script>



        </div>
</body>




</html>