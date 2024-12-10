<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container Register">
            <h1>Sign in</h1>
            <form action="register.php" method="POST">
                <label for="username">User</label>
                <input type="text" id="username" name="username" required>

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <button type="submit">Sign in</button>
                <div class="login">
                    <label for="">Already have an account?<a href="#" class="Login-link">Log in</a></label>
                </div>
            </form>
    </div>
    <div class="form-container Login">
            <h1>Login</h1>
            <form action="login.php" method="POST">
                <label for="username">User</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>

                <div class="register">
                    <label for="">Haven't you registered yet?<a href="#" class="Register-link">Register now</a></label>
                </div>
            </form>
    </div>
</body>
</html>
