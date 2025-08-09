<?php
session_start();
include_once "../user_nav.php";
include_once "php/database.php";

if (!isset($_SESSION['id'])) {
  header("Location: ../../login.php");
  exit();
}

$sql = mysqli_query($conn, "SELECT * FROM user WHERE id = {$_SESSION['id']}");
if (mysqli_num_rows($sql) > 0) {
  $row = mysqli_fetch_assoc($sql);
  $img = (!empty($row['img'])) ? $row['img'] : 'default.jpg';
}
?>
<?php include_once "header.php"; ?>

<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <img src="php/images/<?php echo $img; ?>" alt="">
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
        <!-- Dynamic user list loads here -->
      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>
</body>

</html>