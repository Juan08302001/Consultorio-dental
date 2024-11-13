<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Inicio de Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #e0f7fa; /* Fondo azul claro */
        }
        
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 400px;
            width: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .header img {
            width: 64px; /* Imagen más grande */
            height: 64px;
            margin-bottom: 1rem;
        }
        
        h1 {
            font-size: 1.5rem;
            color: #00796b; /* Azul oscuro para el título */
            margin: 0;
        }
        
        form {
            display: flex;
            flex-direction: column;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        label {
            display: block;
            font-size: 0.875rem;
            color: #004d40; /* Azul oscuro */
            margin-bottom: 0.5rem;
        }
        
        input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #b2dfdb; /* Borde azul claro */
            border-radius: 0.5rem;
            font-size: 1rem;
        }
        
        .forgot-password {
            text-align: right;
            margin-bottom: 1rem;
        }
        
        .forgot-password a {
            color: #00796b; /* Azul oscuro */
            text-decoration: none;
            font-size: 0.875rem;
        }
        
        button {
            background-color: #00796b; /* Azul oscuro */
            color: #ffffff;
            border: none;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        button:hover {
            background-color: #004d40; /* Azul aún más oscuro */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://cdn-icons-png.flaticon.com/512/1077/1077012.png" alt="Dental Icon"> <!-- Cambiar icono -->
            <h1>Bienvenido a DentalCare</h1>
        </div>
        <form action="\ConsultorioDental\controlador\login.php" method="POST">

    <div class="form-group">
        <label for="username">Nombre de usuario</label>
        <input type="text" id="username" name="username" placeholder="Ingrese su nombre de usuario" required>
    </div>
    <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
    </div>
    <button type="submit">Iniciar Sesión</button>
</form>

    </div>
</body>
</html>
