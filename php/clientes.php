<?php 
session_start();
include('conexao.php');

// Cadastrar ou editar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cep = $_POST['cep'];
    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    $verificaEmail = $mysqli->prepare(
        "SELECT id_clientes FROM clientes WHERE email = ? AND id_clientes != ?"
    );
    $idAtual = $_POST['id_clientes'] ?? 0;
    $verificaEmail->bind_param("si", $email, $idAtual);
    $verificaEmail->execute();
    $resEmail = $verificaEmail->get_result();

    if ($resEmail->num_rows > 0) {
        $_SESSION['mensagem'] = "Este e-mail já está cadastrado para outro cliente.";
        $_SESSION['tipo_msg'] = "error";

        header("Location: clientes.php");
        exit;
    }

    if (!empty($_POST['id_clientes'])) {
        $id = $_POST['id_clientes'];
        $mysqli->query("update clientes set
                        nome='$nome',
                        email='$email',
                        cep='$cep',
                        logradouro='$logradouro',
                        numero='$numero',
                        complemento='$complemento',
                        bairro='$bairro',
                        cidade='$cidade',
                        estado='$estado'
                        where id_clientes=$id");
    } else {
        $mysqli->query("insert into clientes(nome, email, cep, logradouro, numero, complemento, bairro, cidade, estado)
                        values('$nome', '$email', '$cep', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$estado')");
    }
    header("Location: clientes.php");
    exit;
}

// Excluir
if(isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $consulta = $mysqli->prepare("select id_emprestimo
                                  from emprestimos
                                  where id_clientes = ?
                                  and status = 'Ativo'");

    $consulta->bind_param("i", $id);
    $consulta->execute();
    $res = $consulta->get_result();

    if ($res->num_rows > 0) {
        $_SESSION['mensagem'] = "Este cliente possui empréstimos em aberto e não pode ser excluído.";
        $_SESSION['tipo_msg'] = "error";

        header("Location: clientes.php");
        exit;
    }
    $mysqli->query("delete from clientes where id_clientes = $id");
    $mensagem = "Cliente excluído com sucesso.";
    $tipo_msg = "success";
    header("Location: clientes.php");
    exit;
}

// Buscar para editar
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $mysqli->query("select * from clientes where id_clientes=$id limit 1");
    $edit = $res->fetch_assoc();
}

// Listar com pesquisa
$busca = $_GET['q'] ?? "";
$sql = "select * from clientes where 1";

if ($busca !== "") {
    if (is_numeric($busca)) {
        $sql .= " and (id_clientes = $busca 
                       or nome like '%$busca%'
                       or email like '%$busca%'
                       or cidade like '%$busca%')";
    } else {
        $sql .= " and (nome like '%$busca%' 
                       or email like '%$busca%' 
                       or cidade like '%$busca%')";
    }
}

$sql .= " order by nome asc";
$dados = $mysqli->query($sql);
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Clientes - BookLover</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/modal.css">
  <link rel="shortcut icon" href="../img/pngegg.png">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <header>
        <button class="sidebar-toggle">
            <ion-icon name="menu-outline"></ion-icon>
        </button>
        
        <h1>BookLover</h1>

        <div class="header-right">
            <button class="tema" id="tema">
                <ion-icon name="moon-outline"></ion-icon>
            </button>

            <span class="user">Olá, <?= htmlspecialchars($_SESSION['nome']) ?> (<?= htmlspecialchars($_SESSION['nivel']) ?>)</span>
            <a href="logout.php">Sair</a>
        </div>
    </header>

    <div class="layout">

        <aside class="sidebar">
            <nav>
                <a href="dashboard.php">Visão geral</a>
                <a href="livros.php">Gerenciar livros</a>
                <a href="clientes.php" class="active">Gerenciar clientes</a>
                <a href="emprestimos.php">Empréstimos</a>
            </nav>
        </aside>

        <main class="main-content">
            <h2>Gerenciar clientes</h2>

            <form method="POST" class="form-card">
                <input type="hidden" name="id_clientes" class="input" value="<?= $edit['id_clientes'] ?? '' ?>">

                <label>Nome:</label>
                <input type="text" name="nome" class="input" required value="<?= $edit['nome'] ?? '' ?>">

                <label>E-mail:</label>
                <input type="email" name="email" class="input" required value="<?= $edit['email'] ?? '' ?>">

                <label>Endereço:</label>

                <label>CEP:</label>
                <input type="text" name="cep" id="cep" class="input" placeholder="Ex.:12345-0000" required maxlength="9" value="<?= $edit['cep'] ?? '' ?>">

                <label>Logradouro:</label>
                <input type="text" name="logradouro" id="logradouro" class="input" required value="<?= $edit['logradouro'] ?? '' ?>">

                <label>Número:</label>
                <input type="number" name="numero" class="input" required value="<?= $edit['numero'] ?? '' ?>">

                <label>Complemento:</label>
                <input type="text" name="complemento" class="input" placeholder="Ex.: Casa, Apartamento..." value="<?= $edit['complemento'] ?? '' ?>">

                <label>Bairro:</label>
                <input type="text" name="bairro" id="bairro" class="input" required value="<?= $edit['bairro'] ?? '' ?>">

                <label>Cidade:</label>
                <input type="text" name="cidade" id="cidade" class="input" required value="<?= $edit['cidade'] ?? '' ?>">

                <label>Estado:</label>
                <input type="text" name="estado" id="estado" class="input" required value="<?= $edit['estado'] ?? '' ?>">


                <button type="submit" class="btn-submit"><?= $edit ? "Salvar Alterações" : "Cadastrar" ?></button>
            </form>

            <form method="GET" class="search-box">
                <input type="text" name="q" placeholder="Pesquisar clientes..." value="<?= $busca ?>">
                <button class="btn-submit">Buscar</button>
            </form>

            <table border="1" width="100%" cellpadding="8">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Cep</th>
                    <th>Logradouro</th>
                    <th>Número</th>
                    <th>Complemento</th>
                    <th>Bairro</th>
                    <th>Cidade</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>

                <?php while($l = $dados->fetch_assoc()): ?>
                    <tr>
                        <td><?= $l['id_clientes'] ?></td>
                        <td><?= $l['nome'] ?></td>
                        <td><?= $l['email'] ?></td>
                        <td><?= $l['cep'] ?></td>
                        <td><?= $l['logradouro'] ?></td>
                        <td><?= $l['numero'] ?></td>
                        <td><?= $l['complemento'] ?></td>
                        <td><?= $l['bairro'] ?></td>
                        <td><?= $l['cidade'] ?></td>
                        <td><?= $l['estado'] ?></td>
                        <td class="table-actions">
                            <a class="action-btn" href="clientes.php?edit=<?= $l['id_clientes'] ?>">Editar</a>
                            <a class="action-btn" href="clientes.php?del=<?= $l['id_clientes'] ?>" onclick="return confirmarExclusao(<?= $l['id_clientes'] ?>);">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>

    <!-- Modal de confirmação -->
    <div id="confirmModal" class="modal-overlay" style="display: none;">
        <div class="modal-box">
            <h3>Confirmar Exclusão</h3>
            <p>Tem certeza que deseja excluir este registro?</p>

            <div class="modal-actions">
                <button id="cancelBtn" class="btn-secondary">Cancelar</button>
                <a id="confirmDelete" class="btn-danger">Excluir</a>
            </div>
        </div>
    </div>

    <!-- Modal de aviso CEP -->
    <div id="cepModal" class="modal-overlay" style="display: none;">
        <div class="modal-box">
            <h3>Aviso</h3>
            <p id="cepMessage"></p>

            <div class="modal-actions">
                <button id="closeCepModal" class="btn-secondary">Fechar</button>
            </div>
        </div>
    </div>

    <!-- Script do Modal -->
    <?php if (!empty($_SESSION['mensagem'])): ?>
    <div class="modal-bg" id="modal">
        <div class="modal <?= $_SESSION['tipo_msg'] ?>">
            <p><?= $_SESSION['mensagem'] ?></p>

            <button onclick="document.getElementById('modal').remove();">OK</button>
        </div>
    </div>

    <?php
        unset($_SESSION['mensagem']);
        unset($_SESSION['tipo_msg']);
    ?>
    <?php endif; ?>
        

    <!-- Tema -->
    <script src="../js/cep.js"></script>
    <script src="../js/theme.js"></script>
    <script src="../js/dashboard.js"></script>

    <!-- Ion Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
