<!DOCTYPE html>
<html>
<head>
    <title>My AI Chatbot</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 40px auto; }
        #messages { border: 1px solid #ccc; padding: 10px; height: 400px; overflow-y: auto; margin-bottom: 10px; }
        .user { text-align: right; color: blue; margin: 5px 0; }
        .bot { text-align: left; color: green; margin: 5px 0; }
        #chat-form { display: flex; gap: 10px; }
        #message-input { flex: 1; padding: 8px; }
    </style>
</head>
<body>
    <h1>Chat with my AI</h1>
    <div id="messages"></div>
    <form id="chat-form">
        @csrf
        <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
        <button type="submit">Send</button>
    </form>

    <script>
        const form = document.getElementById('chat-form');
        const messagesDiv = document.getElementById('messages');
        const input = document.getElementById('message-input');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            messagesDiv.innerHTML += `<div class="user">${message}</div>`;
            input.value = '';

            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({ message }),
            });

            const data = await response.json();
            messagesDiv.innerHTML += `<div class="bot">${data.reply}</div>`;
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        });
    </script>
</body>
</html>