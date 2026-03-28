<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

$sql = "
SELECT 
    m.*,
    s.name AS sender_name,
    d.name AS driver_name,
    r.leaving_from,
    r.going_to,
    r.ride_date,
    MAX(m.created_at) AS last_message
FROM messages m
JOIN users s ON m.sender_id = s.id
JOIN users d ON m.driver_id = d.id
JOIN rides r ON m.ride_id = r.id
WHERE m.sender_id = ? OR m.driver_id = ?
GROUP BY m.ride_id, 
         LEAST(m.sender_id, m.driver_id),
         GREATEST(m.sender_id, m.driver_id)
ORDER BY last_message DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$messages = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My Messages - TripShare</title>
<link rel="stylesheet" href="globals.css">
<link rel="stylesheet" href="styleguide.css">
<link rel="stylesheet" href="style.css">

<style>

/* LISTA DE CONVERSAȚII */
.msg-container {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    padding: 0;
    border-radius: 14px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
}

.msg-container h2 {
    margin: 0;
    padding: 18px 20px;
    font-size: 22px;
    font-weight: 600;
    background: #f5f6ff;
    border-bottom: 1px solid #eee;
}

.msg-item {
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
    transition: 0.25s;
}

.msg-item:hover {
    background: #f9f9ff;
}

.msg-header {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 3px;
}

.msg-meta {
    font-size: 13px;
    color: #777;
}

.msg-text {
    margin-top: 2px;
    background: #f1f2f6;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 14px;
}

/* BUTON CHAT */
.reply-btn {
    border: none;
    background: #3f5efb;
    color: #fff;
    padding: 6px 12px;
    border-radius: 14px;
    cursor: pointer;
    font-size: 13px;
    height: 32px;
    align-self: center;
    transition: 0.2s;
}

.reply-btn:hover {
    transform: scale(1.05);
}

/* POPUP CHAT */
.chat-bg {
    position: fixed;
    inset: 0;
    display: none;
    justify-content: center;
    align-items: center;
    background: rgba(0,0,0,.55);
    z-index: 9999;
}

.chat-box {
    width: 380px;
    background: #fff;
    border-radius: 14px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chat-header {
    padding: 12px 16px;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
}

.chat-messages {
    height: 320px;
    min-height: 320px;            
    max-height: 320px;
    overflow-y: auto !important;   
    overflow-x: hidden;
    padding: 6px 6px;
    background: #f7f8fc;
    display: block;
}



.chat-msg {
    margin-bottom: 1px;
    max-width: 55%;
    padding: 1px 4px;
    border-radius: 6px;
    font-size: 11px;
    line-height: 1.0;
    white-space: pre-wrap;
}



.chat-msg.me {
    margin-left: auto;
    background: #3f5efb;
    color: white;
    border-bottom-right-radius: 3px;
}

.chat-msg.other {
    background: #fff;
    border: 1px solid #dcdcdc;
    border-bottom-left-radius: 3px;
}


.chat-msg-meta {
    font-size: 10px;
    opacity: 0.6;
    margin-top: 1px;
}

/* INPUT CHAT – REDUS */
.chat-footer {
    padding: 8px;
    border-top: 1px solid #eee;
}

.chat-footer form {
    display: flex;
    gap: 6px;
}

.chat-footer textarea {
    flex: 1;
    height: 45px;
    resize: none;
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 13px;
}

.chat-footer button {
    background: #3f5efb;
    border: none;
    color: white;
    padding: 0 14px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
}

</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-container">
        <a href="index.php" class="navbar-logo"><img src="images/logo.png"></a>

        <ul class="navbar-links">
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="ride.php">Publish a ride</a></li>
            <li>
                <a href="search.php" class="icon-link active">
                    <img src="https://c.animaapp.com/3GpNtRxg/img/material-symbols-light-search.svg" />
                    Search
                </a>
            </li>            <li><a href="messages.php" class="icon-link active">Messages</a></li>

            <?php if(isset($_SESSION['user_id'])): ?>
                <li class="navbar-user">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></li>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<br><br><br>

<div class="msg-container">
<h2>Your Conversations</h2>

<?php if ($messages->num_rows > 0): ?>
    <?php while ($m = $messages->fetch_assoc()): ?>

        <?php
            if ($m['sender_id'] == $user_id) {
                $other_id   = $m['driver_id'];
                $other_name = $m['driver_name'];
            } else {
                $other_id   = $m['sender_id'];
                $other_name = $m['sender_name'];
            }
        ?>

        <div class="msg-item">
            <div>
                <div class="msg-header">Chat with <?= htmlspecialchars($other_name) ?></div>
                <div class="msg-meta">
                    Ride: <?= $m['leaving_from'] ?> → <?= $m['going_to'] ?> (<?= $m['ride_date'] ?>)
                </div>
                <div class="msg-text"><?= nl2br(htmlspecialchars($m['message'])) ?></div>
            </div>

            <button
                class="reply-btn open-chat"
                data-ride="<?= $m['ride_id'] ?>"
                data-other="<?= $other_id ?>"
                data-name="<?= htmlspecialchars($other_name, ENT_QUOTES) ?>"
            >Open</button>

        </div>

    <?php endwhile; ?>
<?php else: ?>
    <p style="padding:20px;">No messages yet.</p>
<?php endif; ?>

</div>


<!-- POPUP CHAT -->
<div class="chat-bg" id="chatBg">

    <div class="chat-box">

        <div class="chat-header">
            <span id="chatName"></span>
            <span id="chatClose" style="cursor:pointer;">&times;</span>
        </div>

        <div class="chat-messages" id="chatMessages"></div>

        <div class="chat-footer">
            <form id="chatForm">
                <textarea id="chatInput" name="message" required></textarea>
                <input type="hidden" id="chatRideId" name="ride_id">
                <input type="hidden" id="chatOtherId" name="driver_id">
                <button>Send</button>
            </form>
        </div>

    </div>

</div>



<script>
let chatBg = document.getElementById("chatBg");
let chatClose = document.getElementById("chatClose");
let chatRideId = document.getElementById("chatRideId");
let chatOtherId = document.getElementById("chatOtherId");
let chatName = document.getElementById("chatName");
let chatMessages = document.getElementById("chatMessages");
let chatForm = document.getElementById("chatForm");
let chatInput = document.getElementById("chatInput");

let interval = null;

/* DESCHIDERE CHAT */
document.querySelectorAll(".open-chat").forEach(btn => {
    btn.onclick = () => {
        chatBg.style.display = "flex";

        chatRideId.value = btn.dataset.ride;
        chatOtherId.value = btn.dataset.other;
        chatName.textContent = btn.dataset.name;

        chatInput.value = ""; // curățăm inputul

        loadMessages();
        if (interval) clearInterval(interval);
        interval = setInterval(loadMessages, 1500);
    };
});

/* ÎNCHIDERE CHAT */
chatClose.onclick = () => closeChat();
chatBg.onclick = e => { if (e.target === chatBg) closeChat(); };

function closeChat() {
    chatBg.style.display = "none";
    if (interval) clearInterval(interval);
}

/* ÎNCĂRCARE MESAJE */
function loadMessages() {

    fetch(`fetch_messages.php?ride_id=${chatRideId.value}&other_id=${chatOtherId.value}`)
        .then(r => r.json())
        .then(list => {

            // verificăm dacă userul e jos
            let wasAtBottom = chatMessages.scrollTop + chatMessages.clientHeight >= chatMessages.scrollHeight - 10;

            chatMessages.innerHTML = "";

            list.forEach(m => {
                let div = document.createElement("div");
                div.className = "chat-msg " + (m.is_me ? "me" : "other");

                div.innerHTML = `
                    ${m.message}
                    <div class="chat-msg-meta">${m.sender_name} • ${m.created_at}</div>
                `;

                chatMessages.appendChild(div);
            });

            // autoscroll doar dacă era la final înainte de update
            if (wasAtBottom) {
                setTimeout(() => {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 30);
            }
        });
}


/* TRIMITERE MESAJ — ȘI CURĂȚAREA INPUTULUI */
chatForm.onsubmit = (e) => {
    e.preventDefault();

    let fd = new FormData(chatForm);

    fetch("send_message.php", { method: "POST", body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                chatInput.value = "";  // curățăm câmpul
                loadMessages();
            } else {
                alert(data.error || "Error");
            }
        });
};
</script>

</body>
</html>
