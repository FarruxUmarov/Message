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
            background-color: #1e1e1e;
            color: #ffffff;
        }

        .chat-container {
            display: flex;
            width: 80%;
            height: 80vh;
            background-color: #2c2c2c;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        /* Left Sidebar (User List) */
        .user-list {
            width: 30%;
            border-right: 1px solid #444;
            padding: 10px;
            background-color: #333;
            color: #ffffff;
        }

        .user-list header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
        }

        .user-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .user-item {
            display: flex;
            align-items: center;
            padding: 10px;
            margin-bottom: 8px;
            background-color: #3b3b3b;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
            color: #ffffff;
        }

        .user-item:hover {
            background-color: #505050;
        }

        .user-item.active {
            background-color: #505050;
        }

        .user-item img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .user-item .user-name {
            font-weight: bold;
        }

        /* Chat Area */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 15px;
            background-color: #1e1e1e;
            color: #ffffff;
        }

        .chat-header {
            font-size: 18px;
            font-weight: bold;
            padding: 10px 0;
            border-bottom: 1px solid #444;
            color: #ffffff;
        }

        .messages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background-color: #2c2c2c;
            margin-top: 10px;
            color: #ffffff;
        }

        .message {
            display: flex;
            margin-bottom: 10px;
            align-items: flex-end;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message .text {
            max-width: 60%;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.4;
            color: #ffffff;
        }

        .message.user .text {
            background-color: #007bff;
            border-top-right-radius: 0;
        }

        .message.bot .text {
            background-color: #444;
            border-top-left-radius: 0;
        }

        /* Input Area */
        .input-area {
            display: flex;
            padding: 10px 0;
            border-top: 1px solid #444;
            align-items: center;
        }

        .input-area input {
            flex: 1;
            padding: 10px;
            border: 1px solid #555;
            border-radius: 20px;
            outline: none;
            background-color: #333;
            color: #ffffff;
        }

        .input-area button {
            padding: 10px 15px;
            margin-left: 5px;
            border: none;
            background-color: #007bff;
            color: #ffffff;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .input-area button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <!-- User List -->
    <div class="user-list">
        <header>
            <span>Chats</span>
        </header>
        <ul>
            @foreach($users as $user)
                <a href="{{ route('messages-show',$user->id) }}"
                   class="user-item @if(isset($next_user) && $next_user->id == $user->id) active @endif"
                   style="text-align:center; font-weight: bold;">{{$user->name}}</a>
            @endforeach
        </ul>
    </div>

    <!-- Chat Area -->
    <div class="chat-area">
        <div class="chat-header">Chat with @if(isset($next_user)) {{ $next_user->name }} @endif</div>

        <div class="messages" id="messages">
            @if(isset($messages))
                @foreach($messages as $message)
                    <div class="message {{ $message->sender_id == auth()->id() ? 'user' : 'bot' }}">
                        <div class="text">{{ $message->message }}</div>
                    </div>
                @endforeach
            @else
                <p>Select a user to chat with</p>
            @endif
        </div>

        @if(isset($next_user))
            <form action="{{ route('messages-store') }}" method="POST" class="input-area">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $next_user->id }}">
                <input type="text" name="message" placeholder="Write a message..." required>
                <button type="submit">Send</button>
            </form>
        @endif
    </div>
</div>

</body>
</html>
