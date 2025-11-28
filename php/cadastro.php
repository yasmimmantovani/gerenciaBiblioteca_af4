<?php 
include('conexao.php');
mysqli_report(MYSQLI_REPORT_OFF);

$mensagem = "";
$tipo_msg = "";

function validarTelefone($telefone) {
    $telefone = preg_replace('/\D/', '', $telefone);
    return preg_match('/^[0-9]{10,11}$/', $telefone);
}

function validarCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$t] != $d) return false;
    }
    return true;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $senhaRaw = $_POST['senha'];
    $nivel = $_POST['nivel'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "E-mail inválido! Por favor, insira um e-mail válido.";
        $tipo_msg = "error";
    } 
    elseif (!validarTelefone($telefone)) {
        $mensagem = "Telefone inválido! Digite apenas números (10 ou 11 dígitos).";
        $tipo_msg = "error";
    }
    elseif (!validarCPF($cpf)) {
        $mensagem = "CPF Inválido! Verifique e tente novamente.";
        $tipo_msg = "error";
    }
    else {
        $verificar = $mysqli->prepare("select id from usuarios_adm
                                      where email = ? or cpf = ? or telefone = ?");

        $verificar->bind_param("sss", $email, $cpf, $telefone);
        $verificar->execute();
        $result = $verificar->get_result();

        if ($result-> num_rows > 0) {
            $mensagem = "Já existe um usuário com este e-mail, CPF ou telefone.";
            $tipo_msg = "error";
        } else {
            $senha = password_hash($senhaRaw, PASSWORD_DEFAULT);

            $sql = "insert into usuarios_adm(nome, email, cpf, telefone, senha, nivel)
                    values (?, ?, ?, ?, ?, ?)";

            $stmt = $mysqli->prepare($sql);

            if (!$stmt) {
                $mensagem = "Erro interno: " . $mysqli->error;
                $tipo_msg = "error";
            } else {
                $stmt->bind_param("ssssss", $nome, $email, $cpf, $telefone, $senha, $nivel);

                if ($stmt->execute()) {
                    $mensagem = "Usuário cadastrado com sucesso!";
                    $tipo_msg = "success";
                } else {
                    $mensagem = "Erro ao cadastrar: " . $stmt->error;
                    $tipo_msg = "error";
                }

                $stmt->close();

            }
        }

        $verificar->close();

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
    <link rel="shortcut icon" href="../img/pngegg.png">
    <title>Cadastro - BookLover</title>
</head>
<body>
    <header>
        <h1>BookLover</h1>
        <nav>
            <a href="../html/index.html">Início</a>
            <a href="../html/index.html#destaque">Destaques</a>
            <a href="#cadastro">Seja um Lover</a>
        </nav>

        <div class="header-right">
            <button class="tema" id="tema">
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

            <label>CPF:</label>
            <input type="text" id="cpf" name="cpf" placeholder="Ex.: 123.456.789-10" required>
 
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

    <footer>
        &copy; 2025 BookLover | Desenvolvido por Yasmim Mantovani
        <p>Siga-nos:<br>
            <a href="https://instagram.com" target="_blank"><ion-icon name="logo-instagram"></ion></a> 
            <a href="https://github.com/yasmimmantovani" target="_blank"><ion-icon name="logo-github"></ion-icon></a> 
            <a href="https://www.linkedin.com/in/yasmim-mantovani/" target="_blank"><ion-icon name="logo-linkedin"></ion-icon></a>
        </p>
    </footer>

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