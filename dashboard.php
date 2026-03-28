<?php
require 'config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// dacă nu e logat → redirect
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

/* -----------------------------------------
   FUNCTII CRUD
----------------------------------------- */

// DELETE USER
if ($_GET['action'] ?? '' === 'delete_user' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: dashboard.php");
    exit;
}

// DELETE RIDE
if ($_GET['action'] ?? '' === 'delete_ride' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM rides WHERE id = $id");
    header("Location: dashboard.php");
    exit;
}

// DELETE CONTACT MESSAGE
if ($_GET['action'] ?? '' === 'delete_msg' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM contact_messages WHERE id = $id");
    header("Location: dashboard.php");
    exit;
}

// DELETE NEWSLETTER
if ($_GET['action'] ?? '' === 'delete_sub' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM newsletter_subscribers WHERE id = $id");
    header("Location: dashboard.php");
    exit;
}

/* SAVE USER */
if ($_GET['action'] ?? '' === 'save_user' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $phone, $id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}

/* SAVE RIDE */
if ($_GET['action'] ?? '' === 'save_ride' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $from = $_POST['from'];
    $to = $_POST['to'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $passengers = $_POST['passengers'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE rides SET leaving_from=?, going_to=?, ride_date=?, ride_time=?, passengers=?, price=? WHERE id=?");
    $stmt->bind_param("sssssdi", $from, $to, $date, $time, $passengers, $price, $id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}

/* -----------------------------------------
   CITIM LISTELE
----------------------------------------- */

$users_list = $conn->query("SELECT * FROM users ORDER BY id DESC");
$rides_list = $conn->query("SELECT * FROM rides ORDER BY id DESC");
$contact_list = $conn->query("SELECT * FROM contact_messages ORDER BY id DESC");
$newsletter_list = $conn->query("SELECT * FROM newsletter_subscribers ORDER BY id DESC");

$total_users = $users_list->num_rows;
$total_rides = $rides_list->num_rows;
$total_contact = $contact_list->num_rows;
$total_newsletter = $newsletter_list->num_rows;

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard CRUD</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body { background:#eef1f5; font-family:Arial; padding:30px; }
.dashboard { background:white; padding:25px; border-radius:12px; max-width:1100px; margin:auto; }

h1 { text-align:center; }

.stats {
  display:grid; grid-template-columns:repeat(4,1fr);
  gap:15px; margin-bottom:30px;
}
.stat { background:#f7f9fc; padding:20px; border-radius:12px; text-align:center; border:1px solid #dce3f1; }
.stat b { font-size:26px; color:#3f5efb; }

.section-title { margin-top:35px; border-left:4px solid #3f5efb; padding-left:10px; }

table { width:100%; border-collapse:collapse; margin-top:10px; border-radius:10px; overflow:hidden; }
th { background:#3f5efb; color:white; padding:12px; }
td { padding:12px; background:white; border-bottom:1px solid #eee; }
tr:hover td { background:#f5f7ff; }

.btn {
    padding:6px 12px;
    border-radius:6px;
    color:white;
    text-decoration:none;
    font-size:14px;
}
.edit-btn { background:#3498db; }
.del-btn  { background:#e74c3c; }

.edit-form { display:none; background:#f0f4ff; padding:15px; border-radius:10px; margin-top:10px; }

.input { width:100%; padding:8px; margin:6px 0; }

.save-btn { background:#2ecc71; color:white; width:100%; padding:10px; border:none; border-radius:6px; }

</style>

<script>
function openEdit(id) {
    document.getElementById("edit_"+id).style.display = "block";
}

function closeEdit(id) {
    document.getElementById("edit_"+id).style.display = "none";
}
</script>

</head>
<body>

<div class="dashboard">

<h1>TripShare Dashboard</h1>

<div class="stats">
   <div class="stat">Users<b> <?= $total_users ?></b></div>
   <div class="stat">Rides<b> <?= $total_rides ?></b></div>
   <div class="stat">Messages<b> <?= $total_contact ?></b></div>
   <div class="stat">Newsletter<b> <?= $total_newsletter ?></b></div>
</div>

<!-- USERS -->
<h2 class="section-title">Users</h2>

<table>
<tr>
 <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th>
</tr>

<?php while($u = $users_list->fetch_assoc()): ?>
<tr>
 <td><?= $u['id'] ?></td>
 <td><?= $u['name'] ?></td>
 <td><?= $u['email'] ?></td>
 <td><?= $u['phone'] ?></td>
 <td>
    <a class="btn edit-btn" onclick="openEdit('user<?= $u['id'] ?>')">Edit</a>
    <a class="btn del-btn" href="dashboard.php?action=delete_user&id=<?= $u['id'] ?>"
       onclick="return confirm('Delete this user?')">Delete</a>
 </td>
</tr>

<tr id="edit_user<?= $u['id'] ?>" class="edit-form">
<td colspan="5">

<form method="post" action="dashboard.php?action=save_user&id=<?= $u['id'] ?>">
    <input class="input" name="name" value="<?= $u['name'] ?>">
    <input class="input" name="email" value="<?= $u['email'] ?>">
    <input class="input" name="phone" value="<?= $u['phone'] ?>">
    <button class="save-btn">Save</button>
</form>

</td>
</tr>

<?php endwhile; ?>
</table>


<!-- RIDES -->
<h2 class="section-title">Rides</h2>

<table>
<tr>
 <th>ID</th><th>User</th><th>From</th><th>To</th><th>Date</th><th>Time</th><th>Actions</th>
</tr>

<?php while($r = $rides_list->fetch_assoc()): ?>
<tr>
 <td><?= $r['id'] ?></td>
 <td><?= $r['user_id'] ?></td>
 <td><?= $r['leaving_from'] ?></td>
 <td><?= $r['going_to'] ?></td>
 <td><?= $r['ride_date'] ?></td>
 <td><?= $r['ride_time'] ?></td>
 <td>
    <a class="btn edit-btn" onclick="openEdit('ride<?= $r['id'] ?>')">Edit</a>
    <a class="btn del-btn" href="dashboard.php?action=delete_ride&id=<?= $r['id'] ?>"
       onclick="return confirm('Delete this ride?')">Delete</a>
 </td>
</tr>

<tr id="edit_ride<?= $r['id'] ?>" class="edit-form">
<td colspan="7">

<form method="post" action="dashboard.php?action=save_ride&id=<?= $r['id'] ?>">

    <input class="input" name="from" value="<?= $r['leaving_from'] ?>">
    <input class="input" name="to" value="<?= $r['going_to'] ?>">
    <input class="input" name="date" type="date" value="<?= $r['ride_date'] ?>">
    <input class="input" name="time" type="time" value="<?= $r['ride_time'] ?>">
    <input class="input" name="passengers" value="<?= $r['passengers'] ?>">
    <input class="input" name="price" value="<?= $r['price'] ?>">

    <button class="save-btn">Save</button>
</form>

</td>
</tr>

<?php endwhile; ?>
</table>


<!-- CONTACT -->
<h2 class="section-title">Contact Messages</h2>

<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Message</th><th>Delete</th></tr>

<?php while($c = $contact_list->fetch_assoc()): ?>
<tr>
  <td><?= $c['id'] ?></td>
  <td><?= $c['first_name'] . " " . $c['last_name'] ?></td>
  <td><?= $c['email'] ?></td>
  <td><?= $c['phone'] ?></td>
  <td><?= $c['message'] ?></td>
  <td>
    <a class="btn del-btn" href="dashboard.php?action=delete_msg&id=<?= $c['id'] ?>"
       onclick="return confirm('Delete message?')">Delete</a>
  </td>
</tr>
<?php endwhile; ?>
</table>


<!-- NEWSLETTER -->
<h2 class="section-title">Newsletter Subscribers</h2>

<table>
<tr><th>ID</th><th>Email</th><th>Date</th><th>Delete</th></tr>

<?php while($n = $newsletter_list->fetch_assoc()): ?>
<tr>
 <td><?= $n['id'] ?></td>
 <td><?= $n['email'] ?></td>
 <td><?= $n['subscribed_at'] ?></td>
 <td>
    <a class="btn del-btn" href="dashboard.php?action=delete_sub&id=<?= $n['id'] ?>"
       onclick="return confirm('Delete subscriber?')">Delete</a>
 </td>
</tr>
<?php endwhile; ?>

</table>

</div>

</body>
</html>
