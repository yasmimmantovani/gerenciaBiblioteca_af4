<?php 
include('conexao.php');

$mensagem = "";
$tipo_msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "select id, nome, email, senha, nivel from usuarios_adm where email = ?";
    $stmt = $mysqli->prepare($sql);

    if($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();

            if (password_verify($senha, $usuario['senha'])) {
                session_start();
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['nivel'] = $usuario['nivel'];

                $mensagem = "Login efetuado com sucesso!";
                $tipo_msg = "success";

                echo "
                <script>
                    setTimout(() => {
                        window.location.href = 'dashboard.php';
                    }, 1200);
                </script>";
            } else {
                $mensagem = "Senha incorreta!";
                $tipo_msg = "error";
            }
        } else {
            $mensagem = "E-mail não encontrado!";
            $tipo_msg = "error";
        }

        $stmt->close();
    } else {
        $mensagem = "Erro ao consultar o banco!";
        $tipo_msg = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BookLover</title>
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/modal.css">
    <link rel="shortcut icon" href="../img/pngegg.png">
</head>
<body>

<header>
    <h1>BookLover</h1>
    <nav>
        <a href="../html/index.html">Início</a>
        <a href="../html/index.html#destaque">Destaques</a>
        <a href="cadastro.php">Seja um Lover</a>
    </nav>

    <div class="header-right">
        <button class="tema" id="tema">
            <ion-icon name="moon-outline"></ion-icon>
        </button>
    </div>
</header>

<div class="container">
    <h2>Login</h2>

    <form method="POST">

        <label for="email">E-mail:</label>
        <input type="email" name="email" placeholder="Digite seu e-mail" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" placeholder="Digite sua senha" required>

        <button type="submit" class="botao">Entrar</button>

    </form>
</div>

<footer>
    &copy; 2025 BookLover | Desenvolvido por Yasmim Mantovani
    <p>Siga-nos:<br>
        <a href="https://instagram.com" target="_blank"><ion-icon name="logo-instagram"></ion></a> 
        <a href="https://github.com/yasmimmantovani" target="_blank"><ion-icon name="logo-github"></ion-icon></a> 
            <a href="https://www.linkedin.com/in/yasmim-mantovani/" target="_blank"><ion-icon name="logo-linkedin"></ion-icon></a>
    </p>
</footer>

<!-- Scripts -->
<script src="../js/theme.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<?php if(!empty($mensagem)) : ?>
    <div class="modal-bg" id="modal">
        <div class="modal <?= $tipo_msg ?>">
            <p><?= $mensagem ?></p>
            <?php if($tipo_msg === "success") : ?>
                <button onclick="window.location.href='dashboard.php'">OK</button>
            <?php else : ?>
                <button onclick="document.getElementById('modal').remove();">OK</button>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

</body>
</html>
