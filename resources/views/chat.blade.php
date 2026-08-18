<!DOCTYPE html>
<html>
<head>
    <title>My AI Chatbot</title>
    <style>
        body {
            font-family: -apple-system, sans-serif;
            max-width: 600px;
            margin: 40px auto;
            background: #f5f5f5;
        }
        h1 { color: #333; }
        #messages {
            background: white;
            border-radius: 12px;
            padding: 16px;
            height: 400px;
            overflow-y: auto;
            margin-bottom: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .user, .bot {
            padding: 8px 14px;
            border-radius: 16px;
            margin: 6px 0;
            max-width: 75%;
            line-height: 1.4;
        }
        .user {
            background: #007bff;
            color: white;
            margin-left: auto;
            text-align: right;
        }
        .bot {
            background: #eee;
            color: #222;
            margin-right: auto;
        }
        .typing {
            font-style: italic;
            color: #888;
            margin: 6px 0;
        }
        #chat-form { display: flex; gap: 10px; }
        #message-input {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            background: #007bff;
            color: white;
            cursor: pointer;
        }
        button:disabled { background: #aaa; cursor: not-allowed; }
    </style>
</head>
<body>
    <h1>Chat with my AI</h1>
    <div id="messages"></div>
    <form id="chat-form">
        @csrf
        <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
        <button type="submit" id="send-btn">Send</button>
    </form>

    <script>
        const form = document.getElementById('chat-form');
        const messagesDiv = document.getElementById('messages');
        const input = document.getElementById('message-input');
        const sendBtn = document.getElementById('send-btn');

        function scrollToBottom() {
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            messagesDiv.innerHTML += `<div class="user">${message}</div>`;
            input.value = '';
            input.disabled = true;
            sendBtn.disabled = true;
            scrollToBottom();

            const typingEl = document.createElement('div');
            typingEl.className = 'typing';
            typingEl.textContent = 'Bot is typing...';
            messagesDiv.appendChild(typingEl);
            scrollToBottom();

            try {
                const response = await fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({ message }),
                });

                const data = await response.json();
                typingEl.remove();
                messagesDiv.innerHTML += `<div class="bot">${data.reply}</div>`;
            } catch (err) {
                typingEl.remove();
                messagesDiv.innerHTML += `<div class="bot">Something went wrong. Please try again.</div>`;
            } finally {
                input.disabled = false;
                sendBtn.disabled = false;
                input.focus();
                scrollToBottom();
            }
        });
    </script>
</body>
</html>