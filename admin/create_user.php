<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toyota - User Registration</title>
    <?php include 'nav.php'; ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>
    <div class="createContainer">
        <div class="custom-container">
            <div class="cont-2">
                <h1>Create New Account</h1>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error_message'];
                        unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success_message'];
                        unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>

                <form action="insert_user.php" method="post" id="registrationForm">
                    <!-- CSRF Token for security -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32)); ?>">

                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Enter full name" required autocomplete="name">
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required autocomplete="username">
                    </div>

                    <div class="form-group">
                        <label for="department">Department:</label>
                        <select name="department" id="department" required>
                            <option value="" disabled selected hidden>Select Department</option>
                            <option value="IT">IT</option>
                            <option value="Admin">Admin</option>
                            <option value="Finance and Accounting">Finance and Accounting</option>
                            <option value="Parts Counter">Parts Counter</option>
                            <option value="Parts Warehouse">Parts Warehouse</option>
                            <option value="Sales (Financing)">Sales (Financing)</option>
                            <option value="Sales (MP)">Sales (MP)</option>
                            <option value="Sales Admin">Sales Admin</option>
                            <option value="Sales Training">Sales Training</option>
                            <option value="Service">Service</option>
                            <option value="Tool Room">Tool Room</option>
                            <option value="Tsure">Tsure</option>

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="password-container">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm password" required autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="toggleConfirmPassword()" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="password-match"></div>
                    </div>

                    <div class="admin-checkbox">
                        <input type="checkbox" id="is_admin" name="is_admin" value="1">
                        <label for="is_admin">Admin User (grants full system access)</label>
                    </div>

                    <div class="form-btn">
                        <input type="submit" value="Create Account" name="register" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.querySelector('#password + .toggle-password i');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function toggleConfirmPassword() {
            const confirmPassword = document.getElementById('confirm_password');
            const icon = document.querySelector('#confirm_password + .toggle-password i');
            if (confirmPassword.type === 'password') {
                confirmPassword.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                confirmPassword.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

    <style>
        .createContainer {
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

        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .custom-container {
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .cont-2 {
            background: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 20px 0;
        }

        .cont-2 h1 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }

        .form-control {
            height: 45px;
            padding: 10px 15px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #4a90e2;
            outline: none;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }

        .password-container {
            position: relative;
        }

        .password-container .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            color: #6c757d;
            cursor: pointer;
            font-size: 18px;
        }


        .form-btn {
            text-align: center;
            margin-top: 20px;
        }

        .form-btn .btn {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            background-color: #4a90e2;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .form-btn .btn:hover {
            background-color: #3a7bc8;
        }

        .alert {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 4px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .admin-checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .admin-checkbox input {
            margin-right: 10px;
            width: 18px;
            height: 18px;
        }

        .admin-checkbox label {
            margin: 0;
            font-weight: 500;
            color: #555;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 14px;
            color: #666;
        }

        .password-strength.weak {
            color: #dc3545;
        }

        .password-strength.medium {
            color: #fd7e14;
        }

        .password-strength.strong {
            color: #28a745;
        }
    </style>
</body>

</html>