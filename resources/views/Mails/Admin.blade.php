<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 90%;
        }

        h1 {
            color: #2a9d8f;
            margin-bottom: 15px;
        }

        p {
            font-size: 1.2em;
            color: #555;
        }

        .highlight {
            color: #264653;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nuevo Usuario</h1>    
        <p>El usuario <span class="highlight">{{ $user->name }}</span> activó su cuenta.</p>
    </div>
</body>
</html>
