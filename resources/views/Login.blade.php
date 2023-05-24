<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <!-- Include any necessary CSS styling -->
    <style>
        .login-container {
            width: 300px;
            margin: 0 auto;
            margin-top: 100px;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
        }
        .form-group input {
            width: 100%;
            padding: 5px;
        }
        .form-group .btn {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn">Login</button>
        </div>
    </form>

    <div class="form-group">
        <a href="{{ route('about') }}">Return</a>
    </div>
</div>
</body>
</html>
