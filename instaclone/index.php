<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
}

// Fetch posts
$stmt = $conn->query("SELECT p.id, p.image_url, p.caption, p.created_at, u.username 
                      FROM posts p 
                      JOIN users u ON p.user_id = u.id 
                      ORDER BY p.created_at DESC");
$posts = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/billabong" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #fafafa;
            font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
        }
        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 32px auto;
            min-height: 650px;
            gap: 30px;
        }
        .phone-container {
            position: relative;
            width: 450px;
            height: 638px;
        }
        #phone {
            width: 101%;
            height: 100%;
            object-fit: contain;
        }
        #imageslideshow {
            position: absolute;
            top: 30px; /* Ajustado para centrar en la pantalla del teléfono */
            left: 150px; /* Ajustado para centrar en la pantalla del teléfono */
            width: 250px; /* Ajustado para el área de la pantalla */
            height: 541px; /* Ajustado para el área de la pantalla */
            background-image: url(images/ss1.png);
            background-size: cover;
            animation: changeImage 6s ease-in infinite;
        }
        /* Si el slideshow no está alineado, ajusta top, left, width, height en el inspector (F12) */
        @keyframes changeImage {
            0% { background-image: url(images/ss1.png); }
            50% { background-image: url(images/ss2.png); }
            100% { background-image: url(images/ss3.png); }
        }
        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 345px;
        }
        .loginbox {
            background-color: white;
            border: 1px solid rgb(149, 149, 149, 0.4);
            width: 100%;
            padding: 20px;
            text-align: center;
            margin-bottom: 10px;
        }
        #title {
            font-family: 'Billabong', cursive;
            font-size: 40px;
            color: #262626;
            margin-bottom: 20px;
        }
        .input {
            background-color: #fafafa;
            border: 1px solid rgb(149, 149, 149, 0.4);
            height: 36px;
            width: 100%;
            max-width: 260px;
            margin: 6px auto;
            padding: 0 8px;
            font-size: 14px;
            font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
        }
        #loginbutton {
            background-color: #4bb4f8;
            color: white;
            height: 32px;
            width: 100%;
            max-width: 260px;
            border-radius: 7px;
            border: none;
            font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            margin: 10px auto;
            display: block;
        }
        #or {
            text-transform: uppercase;
            text-align: center;
            font-size: 13px;
            font-weight: bolder;
            color: rgb(149, 149, 149);
            margin: 10px 0;
        }
        a {
            text-decoration: none;
        }
        #fblink {
            color: #385185;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        #fbicon {
            height: 16px;
            width: 16px;
            margin-right: 8px;
        }
        #forgotpass {
            color: #385185;
            font-weight: 100;
            font-size: 12px;
            margin-top: 10px;
            display: block;
        }
        .signup {
            background-color: white;
            border: 1px solid rgb(149, 149, 149, 0.4);
            width: 100%;
            padding: 15px;
            text-align: center;
        }
        #Signup {
            font-weight: 550;
            color: #1fa2f6;
        }
        .app {
            width: 100%;
            text-align: center;
            margin: 20px 0;
        }
        .gettheapp {
            font-weight: 400;
            font-size: 14px;
            margin-bottom: 10px;
        }
        #gplay, #microsoft {
            height: 40px;
            margin: 0 5px;
        }
        .footer {
            width: 100%;
            text-align: center;
            padding: 20px 0;
        }
        .linksdiv {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .links {
            color: rgb(103, 103, 103);
            font-size: 12px;
            margin: 0 10px;
            font-weight: 200;
        }
        .copyright {
            font-size: 12px;
            color: rgb(103, 103, 103);
        }
        .navbar {
            background: white;
            border-bottom: 1px solid rgb(149, 149, 149, 0.4);
            padding: 10px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }
        .navbar .logo {
            font-family: 'Billabong', cursive;
            font-size: 24px;
            color: #262626;
        }
        .navbar .icons span {
            margin-left: 20px;
            cursor: pointer;
        }
        .container {
            max-width: 935px;
            margin: 80px auto 20px;
            display: flex;
            gap: 20px;
        }
        .feed {
            width: 60%;
        }
        .post {
            background: white;
            border: 1px solid rgb(149, 149, 149, 0.4);
            margin-bottom: 20px;
            border-radius: 3px;
        }
        .post-header {
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .post-header img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
        .post-image img {
            width: 100%;
            max-height: 600px;
            object-fit: cover;
        }
        .post-actions {
            padding: 8px 16px;
        }
        .post-actions span {
            margin-right: 16px;
            cursor: pointer;
        }
        .post-caption {
            padding: 0 16px 16px;
        }
        .sidebar {
            width: 35%;
            background: white;
            border: 1px solid rgb(149, 149, 149, 0.4);
            padding: 16px;
            border-radius: 3px;
            position: sticky;
            top: 100px;
        }
        #postForm {
            background: white;
            border: 1px solid rgb(149, 149, 149, 0.4);
            padding: 16px;
            margin-bottom: 20px;
            border-radius: 3px;
        }
        #postForm input,
        #postForm textarea {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid rgb(149, 149, 149, 0.4);
            border-radius: 3px;
        }
        #postForm button {
            padding: 8px 16px;
            background: #4bb4f8;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        @media (max-width: 900px) {
            .main {
                flex-direction: column;
                min-height: auto;
                padding: 20px;
                margin: 20px auto;
            }
            .phone-container {
                display: none;
            }
            .login-container {
                width: 100%;
                max-width: 345px;
            }
            .loginbox, .signup, .app {
                width: 100%;
            }
            .container {
                flex-direction: column;
                margin: 60px 20px;
            }
            .feed, .sidebar {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="main">
            <div class="phone-container">
                <img id="phone" src="images/phone.png" alt="Phone">
                <!-- Si phone.png no está, usa: <img id="phone" src="https://via.placeholder.com/450x638" alt="Phone"> -->
                <div id="imageslideshow"></div>
            </div>
            <div class="login-container">
                <div class="loginbox">
                    <div id="title">Instagram</div>
                    <form id="authForm" action="<?php echo isLoginMode() ? 'login.php' : 'register.php'; ?>" method="POST">
                        <input class="input" type="email" name="email" placeholder="Correo electrónico" required>
                        <input class="input" type="password" name="password" placeholder="Contraseña" required>
                        <button id="loginbutton" type="submit"><?php echo isLoginMode() ? 'Iniciar Sesión' : 'Registrarse'; ?></button>
                    </form>
                    <div id="or">O</div>
                    <a id="fblink" href="#"><img id="fbicon" src="images/facebook.png" alt="Facebook Icon">Iniciar sesión con Facebook</a>
                    <!-- Si facebook.png no está, usa: <img id="fbicon" src="https://via.placeholder.com/16x16" alt="Facebook Icon"> -->
                    <a id="forgotpass" href="#">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="signup">
                    <p>¿No tienes una cuenta? <a id="Signup" href="#" onclick="toggleAuthMode()">Regístrate</a></p>
                </div>
                <div class="app">
                    <div class="gettheapp">Obtén la aplicación.</div>
                    <img id="gplay" src="images/gplay.png" alt="Google Play">
                    <!-- Si gplay.png no está, usa: <img id="gplay" src="https://via.placeholder.com/136x40" alt="Google Play"> -->
                    <img id="microsoft" src="images/microsoft.png" alt="Microsoft Store">
                    <!-- Si microsoft.png no está, usa: <img id="microsoft" src="https://via.placeholder.com/136x40" alt="Microsoft Store"> -->
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="linksdiv">
                <a class="links" href="#">Sobre nosotros</a>
                <a class="links" href="#">Soporte</a>
                <a class="links" href="#">Prensa</a>
                <a class="links" href="#">API</a>
                <a class="links" href="#">Empleos</a>
                <a class="links" href="#">Privacidad</a>
                <a class="links" href="#">Términos</a>
            </div>
            <div class="copyright">© 2025 MiRedSocial</div>
        </div>
    <?php else: ?>
        <div class="navbar">
            <div class="logo">Instagram</div>
            <div class="icons">
                <span class="material-icons" onclick="showPostForm()">add_box</span>
                <span class="material-icons" onclick="window.location.href='logout.php'">logout</span>
            </div>
        </div>
        <div class="container">
            <div class="feed">
                <div id="postForm" style="display: none;">
                    <form action="post.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="image" accept="image/*" required>
                        <textarea name="caption" placeholder="Escribe una descripción" required></textarea>
                        <button type="submit">Publicar</button>
                    </form>
                </div>
                <div id="posts">
                    <?php foreach ($posts as $post): ?>
                        <div class="post">
                            <div class="post-header">
                                <img src="https://via.placeholder.com/32" alt="Profile">
                                <span><?php echo htmlspecialchars($post['username']); ?></span>
                            </div>
                            <div class="post-image">
                                <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="Post">
                            </div>
                            <div class="post-actions">
                                <span class="material-icons">favorite_border</span>
                                <span class="material-icons">comment</span>
                                <span class="material-icons">share</span>
                            </div>
                            <div class="post-caption">
                                <p><strong><?php echo htmlspecialchars($post['username']); ?></strong> <?php echo htmlspecialchars($post['caption']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="sidebar">
                <h3>Perfil</h3>
                <div id="userInfo">
                    <?php if (isset($user)): ?>
                        <p><strong><?php echo htmlspecialchars($user['username']); ?></strong></p>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    <?php else: ?>
                        <p>Inicia sesión para ver tu perfil</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        let isLogin = <?php echo json_encode(isLoginMode()); ?>;

        function toggleAuthMode() {
            isLogin = !isLogin;
            document.getElementById('authForm').action = isLogin ? 'login.php' : 'register.php';
            document.getElementById('authForm').querySelector('#loginbutton').textContent = isLogin ? 'Iniciar Sesión' : 'Registrarse';
            document.getElementById('Signup').parentElement.innerHTML = isLogin ? '¿No tienes una cuenta? <a id="Signup" href="#" onclick="toggleAuthMode()">Regístrate</a>' : '¿Ya tienes una cuenta? <a id="Signup" href="#" onclick="toggleAuthMode()">Inicia sesión</a>';
        }

        function showPostForm() {
            document.getElementById('postForm').style.display = 'block';
        }
    </script>
</body>
</html>

<?php
function isLoginMode() {
    return !isset($_GET['register']);
}
?>