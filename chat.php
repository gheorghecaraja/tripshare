<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) exit;

$currentUser = intval($_SESSION['user_id']);
$otherUser   = intval($_GET['user']);  
$ride_id     = intval($_GET['ride']);  

$other = $conn->query("SELECT name FROM users WHERE id=$otherUser")
              ->fetch_assoc()['name'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Chat with <?= htmlspecialchars($other) ?></title>

<style>
.chat-container{max-width:650px;margin:25px auto;background:#fff;padding:20px;border-radius:12px;}
.msg{padding:12px 18px;border-radius:20px;margin:10px 0;max-width:70%;}
.me{background:#4f9efc;color:white;margin-left:auto;}
.them{background:#e8e8e8;}
input[type=text]{width:78%;padding:12px;border-radius:10px;border:1px solid #aaa;}
button{padding:12px 18px;border:none;border-radius:10px;background:#4f9efc;color:white;}
</style>

</head>
<body>

<h2 style="text-align:center;">Chat with <?= htmlspecialchars($other) ?></h2>

<div class="chat-container">

<div id="chatMessages">
    Loading...
</div>

<div style="margin-top:10px;">
<form id="chatForm">
    <input type="hidden" name="ride_id" value="<?= $ride_id ?>">
    <input type="hidden" name="driver_id" value="<?= $otherUser ?>">
    <input type="text" id="messageInput" name="message" placeholder="Type a message..." required>
    <button type="submit">Send</button>
</form>
</div>

</div>

<script>
// =========================
// Load messages from server
// =========================
function loadMessages() {
    fetch("fetch_messages.php?ride_id=<?= $ride_id ?>&other_id=<?= $otherUser ?>")
    .then(res => res.json())
    .then(data => {
        let box = document.getElementById("chatMessages");
        box.innerHTML = "";

        data.forEach(msg => {
            let div = document.createElement("div");
            div.classList.add("msg");
            div.classList.add(msg.is_me ? "me" : "them");

            div.innerHTML = `
                ${msg.message}<br>
                <small>${msg.created_at}</small>
            `;

            box.appendChild(div);
        });

        box.scrollTop = box.scrollHeight;
    });
}

// first load
loadMessages();
setInterval(loadMessages, 1500);

// =========================
// SEND MESSAGE via AJAX
// =========================
document.getElementById("chatForm").addEventListener("submit", function(e){
    e.preventDefault();

    const formData = new FormData(this);

    fetch("send_message.php", {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest" // IMPORTANT
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById("messageInput").value = "";
            loadMessages();
        } else {
            alert(data.error || "Error sending message");
        }
    })
    .catch(err => alert("Network error"));
});
</script>

</body>
</html>
