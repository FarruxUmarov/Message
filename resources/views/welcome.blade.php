<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .chat-container {
            display: flex;
            border: 1px solid #ccc;
            width: 60%;
            height: 80vh;
        }
        .user-list {
            width: 20%;
            border-right: 1px solid #ccc;
            padding: 10px;
            list-style: none;
        }
        .user-list li {
            padding: 8px;
            cursor: pointer;
        }
        .user-list li:hover {
            background-color: #f0f0f0;
        }
        .chat-area {
            width: 80%;
            display: flex;
            flex-direction: column;
            padding: 10px;
        }
        .messages {
            flex: 1;
            border-bottom: 1px solid #ccc;
            overflow-y: auto;
            padding: 10px;
        }
        .message {
            margin: 5px 0;
        }
        .message.user {
            text-align: right;
            color: blue;
        }
        .message.bot {
            text-align: left;
            color: green;
        }
        .input-area {
            display: flex;
            padding: 10px 0;
        }
        .input-area input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .input-area button {
            padding: 8px 12px;
            margin-left: 5px;
            border: none;
            background-color: #28a745;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
        }
        .input-area button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <!-- User List -->
    <ul class="user-list">
        <li>Valijon</li>
        <li>Sobirjon</li>
    </ul>

    <!-- Chat Area -->
    <div class="chat-area">
        <div class="messages" id="messages"></div>

        <!-- Input Area -->
        <div class="input-area">
            <input type="text" id="messageInput" placeholder="Type a message...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<script>
    // Simulate the messages array for demo
    let messages = [];

    // Display initial messages
    function displayMessages() {
        const messagesContainer = document.getElementById("messages");
        messagesContainer.innerHTML = '';
        messages.forEach(msg => {
            const messageDiv = document.createElement("div");
            messageDiv.classList.add("message", msg.user === "Me" ? "user" : "bot");
            messageDiv.textContent = `${msg.user}: ${msg.text}`;
            messagesContainer.appendChild(messageDiv);
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    displayMessages();

    // Send message function
    function sendMessage() {
        const messageInput = document.getElementById("messageInput");
        const text = messageInput.value;
        if (text.trim() === "") return;

        // Add the user's message
        messages.push({ user: "Me", text });
        displayMessages();
        messageInput.value = "";

        // Send the message to the server
        fetch("/send", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ text })
        }).then(() => longPoll()); // Start long polling after sending
    }

    // Long polling function
    function longPoll() {
        fetch("/poll")
            .then(response => response.json())
            .then(newMessages => {
                // Update messages if there are new ones
                messages = messages.concat(newMessages);
                displayMessages();
                setTimeout(longPoll, 1000); // Poll again after 1 second
            })
            .catch(() => {
                setTimeout(longPoll, 2000); // Retry in 2 seconds on error
            });
    }

    // Start long polling
    longPoll();
</script>

</body>
</html>
