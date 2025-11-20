<?php 
include('conexao.php');
mysqli_report(MYSQLI_REPORT_OFF);

$mensagem = "";
$tipo_msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senhaRaw = $_POST['senha'];
    $nivel = $_POST['nivel'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "E-mail inválido! Por favor, insira um e-mail válido.";
        $tipo_msg = "error";
    } else {
        $senha = password_hash($senhaRaw, PASSWORD_DEFAULT);

        $sql = "insert into usuarios_adm (nome, email, telefone, senha, nivel)
                values (?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($sql);

        if(!$stmt) {
            $mensagem = "Erro interno: " . $mysqli->error;
            $tipo_msg = "error";
        } else {
            $stmt->bind_param("sssss", $nome, $email, $telefone, $senha, $nivel);

            if($stmt->execute()) {
                $mensagem = "Usuário cadastrado com sucesso!";
                $tipo_msg = "success";
            } else {
                if($mysqli->errno == 1062) {
                    $mensagem = "Este e-mail já está cadastrado. Tente outro.";
                    $tipo_msg = "error";
                } else {
                    $mensagem = "Erro ao cadastrar: " . $stmt->error;
                    $tipo_msg = "error";
                }
            } 
            $stmt->close();
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/modal.css">
    <title>Cadastro</title>
</head>
<body>
    <header>
        <h1>BookLover</h1>
        <nav>
            <a href="../html/index.html">Início</a>
            <a href="./index.html #destaque">Destaques</a>
            <a href="#cadastro">Seja um lover</a>
        </nav>

        <div class="header-right">
            <button id="tema">
                <ion-icon name="moon-outline"></ion-icon>
            </button>

            <div class="btn-header">
                <a href="login.php">Login</a>
            </div>
        </div>
    </header>

    <div class="container">
        <h2>Cadastro de Usuário</h2>
        <form method="POST">
            <label for="nome"> Nome:</label>
            <input type="text" id="nome" name="nome" placeholder="Digite o seu nome" required>

            <label for="email"> E-mail:</label>
            <input type="email" id="email" name="email" placeholder="Ex.: fulano@email.com" required>

            <label for="telefone"> Telefone:</label>
            <input type="text" id="telefone" name="telefone" placeholder="(00) 12345-6789" required>

            <label for="senha"> Senha:</label>
            <input type="password" id="senha" name="senha" placeholder="Crie uma senha" required>

            <label for="nivel"> Cargo:</label>
            <select name="nivel" required>
                <option selected disabled value=""> Selecione</option>
                <option value="admin">Administrador</option>
                <option value="funcionario">Funcionário</option>
            </select>

            <button type="submit" class="botao">Cadastrar</button>
        </form>
    </div>

    <!-- Tema -->
     <script src="../js/theme.js"></script>
     <script src="../js/mascaras.js"></script>

    <!-- Ion Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<!-- Script do Modal -->
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