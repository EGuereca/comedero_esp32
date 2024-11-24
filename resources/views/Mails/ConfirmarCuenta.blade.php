<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activacion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            color: #333;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #2a9d8f;
        }

        p, h3 {
            font-size: 1.2em;
            color: #555;
        }

        a {
            text-decoration: none;
            display: inline-block;
            padding: 12px 25px;
            margin-top: 20px;
            background-color: #2a9d8f;
            color: white;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        a:hover {
            background-color: #264653;
            transform: scale(1.05);
        }

        h3 {
            color: #e76f51;
        }
    </style>
</head>
<body>
    <h1>¡Bienvenido {{ $usuario->name }}!</h1><br>
    <p>Para activar tu cuenta, da click en el siguiente enlace:</p>
    <a href="{{ $activationUrl }}">Activar cuenta</a><br>
    <h3>Este enlace expirará en 5 minutos.</h3>
</body>
</html>
