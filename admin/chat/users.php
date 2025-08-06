<?php
session_start();

if (!isset($_SESSION["id"])) {
  header("Location: login.php");
  exit();
}

// Optional: If admin should NOT access this page
if (isset($_SESSION["pos"]) && $_SESSION["pos"] === "admin") {
  header("Location: /TSP-System/ticketing-system/admin/chat/users.php");
  exit();
}

require_once "php/database.php";
?>


<?php include_once "header.php"; ?>

<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <?php
          $sql = mysqli_query($conn, "SELECT * FROM user WHERE id = {$_SESSION['id']}");
          if (mysqli_num_rows($sql) > 0) {
            $row = mysqli_fetch_assoc($sql);
          }
          ?>
          <img src="php/images/<?php echo !empty($row['img']) ? $row['img'] : 'default.jpg'; ?>" alt="">
          <div class="details">
            <span><?php echo $row['name']; ?></span>
            <p><?php echo $row['status']; ?></p>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?php echo $row['id']; ?>" class="logout">Logout</a>
      </header>
      <div class="search">
        <span class="text">Select a user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">

      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>
</body>

</html>