<?php
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";

if (isset($_SESSION['username']) && isset($_SESSION['student_id'])) {
    $title = "EduFun - Chatbot";

    include "inc/Header.php";
    include "inc/NavBar.php";
?>

<div class="container mt-5">
    <h2 class="mb-4">EduFun Chatbot</h2>
    <div id="chatbox" style="height: 300px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background: #fff;"></div>
    <div class="mt-3">
        <input type="text" id="userInput" class="form-control" placeholder="Ask Anything">
        <button class="btn btn-primary mt-2" onclick="sendMessage()">Send</button>
    </div>
</div>

<script>
async function sendMessage(message = null) {
    const inputField = document.getElementById("userInput");
    const chatbox = document.getElementById("chatbox");

    const userMessage = message || inputField.value.trim();
    if (!userMessage) return;

    if (!message) {
        chatbox.innerHTML += `<div><strong>You:</strong> ${userMessage}</div>`;
        inputField.value = "";
    }

    try {
        const response = await fetch("ask-gemini.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ question: userMessage })
        });

        const data = await response.json();
        const botMessage = data.response || "Maaf, terjadi kesalahan pada server.";

        chatbox.innerHTML += `<div><strong>Bot:</strong> ${botMessage}</div>`;
        chatbox.scrollTop = chatbox.scrollHeight;
    } catch (error) {
        chatbox.innerHTML += `<div><strong>Bot:</strong> An error occurred while contacting the server.</div>`;
    }
}

window.onload = function() {
    sendMessage("Introduce yourself as EduFun chatbot and ask how you can help..");
};
</script>


<?php
    include "inc/Footer.php";
} else {
    $em = "Please login first.";
    Util::redirect("../login.php", "error", $em);
}
?>
