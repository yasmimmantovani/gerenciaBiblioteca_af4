<?php 
session_start();
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_livro = $_POST['id_livro'];
    $id_clientes = $_POST['id_clientes'];
    $data_devolucao = $_POST['data_devolucao'];

    if (!empty($_POST['id_emprestimo'])) {
        $id = $_POST['id_emprestimo'];
        $mysqli->query("UPDATE emprestimos SET
                        id_livro = '$id_livro',
                        id_clientes = '$id_clientes',
                        data_devolucao = '$data_devolucao'
                        WHERE id_emprestimo = $id");
    } else {

        $query = $mysqli->prepare("SELECT quantidade FROM livros WHERE id_livro = ?");
        $query->bind_param("i", $id_livro);
        $query->execute();
        $result = $query->get_result();
        $livro = $result->fetch_assoc();

        if ($livro['quantidade'] <= 0) {
            $_SESSION['mensagem'] = "Este livro está indisponível para empréstimo.";
            $_SESSION['tipo_msg'] = "error";

            header("Location: emprestimos.php");
            exit;
        }

        $mysqli->query("INSERT INTO emprestimos
                        (id_livro, id_clientes, data_emprestimo, data_devolucao, status)
                        VALUES ('$id_livro', '$id_clientes', NOW(), '$data_devolucao', 'Ativo')");

        $mysqli->query("UPDATE livros
                        SET quantidade = quantidade - 1
                        WHERE id_livro = $id_livro");

        $mysqli->query("UPDATE livros
                        SET disponibilidade = IF(quantidade > 0, 'Disponível', 'Emprestado')
                        WHERE id_livro = $id_livro");
    }
    header("Location: emprestimos.php");
    exit;
}

if (isset($_GET['del'])) {

    $id = (int)$_GET['del'];

    $res = $mysqli->query("SELECT id_livro FROM emprestimos WHERE id_emprestimo = $id");
    $row = $res->fetch_assoc();
    $id_livro = $row['id_livro'];

    $mysqli->query("DELETE FROM emprestimos WHERE id_emprestimo = $id");

    $mysqli->query("UPDATE livros 
                    SET quantidade = quantidade + 1
                    WHERE id_livro = $id_livro");

    $mysqli->query("UPDATE livros 
                    SET disponibilidade = IF(quantidade > 0, 'Disponível', 'Emprestado')
                    WHERE id_livro = $id_livro");

    header("Location: emprestimos.php");
    exit;
}

if (isset($_GET['dev'])) {

    $id = (int)$_GET['dev'];

    $res = $mysqli->query("SELECT id_livro FROM emprestimos WHERE id_emprestimo = $id");
    $row = $res->fetch_assoc();
    $id_livro = $row['id_livro'];


    $mysqli->query("UPDATE emprestimos 
                    SET status = 'Devolvido',
                        data_devolucao = NOW()
                    WHERE id_emprestimo = $id");


    $mysqli->query("UPDATE livros 
                    SET quantidade = quantidade + 1
                    WHERE id_livro = $id_livro");


    $mysqli->query("UPDATE livros 
                    SET disponibilidade = IF(quantidade > 0, 'Disponível', 'Emprestado')
                    WHERE id_livro = $id_livro");

    header("Location: emprestimos.php");
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $mysqli->query("SELECT * FROM emprestimos WHERE id_emprestimo = $id LIMIT 1");
    $edit = $res->fetch_assoc();
}

$busca = $_GET['q'] ?? "";
$sql = "SELECT * FROM emprestimos WHERE 1";

if ($busca !== "") {
    $sql .= " AND (id_emprestimo LIKE '%$busca%')";
}

$sql .= " ORDER BY id_emprestimo DESC";
$dados = $mysqli->query($sql);
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Empréstimos - BookLover</title>
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
                <a href="clientes.php">Gerenciar clientes</a>
                <a href="emprestimos.php" class="active">Empréstimos</a>
            </nav>
        </aside>

        <main class="main-content">
            <h2>Gerenciar empréstimos</h2>

            <form method="POST" class="form-card">
                <input type="hidden" name="id_emprestimo" class="input" value="<?= $edit['id_emprestimo'] ?? '' ?>">

                <label>ID do livro:</label>
                <input type="number" name="id_livro" class="input" required value="<?= $edit['id_livro'] ?? '' ?>">

                <label>ID do cliente:</label>
                <input type="number" name="id_clientes" class="input" required value="<?= $edit['id_clientes'] ?? '' ?>">

                <input type="hidden" name="data_emprestimo" class="input" value="<?= $edit['data_emprestimo'] ?? '' ?>">

                <input type="hidden" name="data_devolucao" class="input" value="<?= $edit['data_devolucao'] ?? '' ?>">

                <button type="submit" class="btn-submit"><?= $edit ? "Salvar Alterações" : "Cadastrar" ?></button>
            </form>

            <form method="GET" class="search-box">
                <input type="text" name="q" placeholder="Pesquisar empréstimo..." value="<?= $busca ?>">
                <button class="btn-submit">Buscar</button>
            </form>

            <table border="1" width="100%" cellpadding="8">
                <tr>
                    <th>ID</th>
                    <th>ID Livro</th>
                    <th>ID Cliente</th>
                    <th>Data Empréstimo</th>
                    <th>Data Devolução</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>

                <?php while($l = $dados->fetch_assoc()): ?>
                    <tr>
                        <td><?= $l['id_emprestimo'] ?></td>
                        <td><?= $l['id_livro'] ?></td>
                        <td><?= $l['id_clientes'] ?></td>
                        <td><?= $l['data_emprestimo'] ?></td>
                        <td><?= $l['data_devolucao'] ?></td>
                        <td><?= $l['status'] ?></td>
                        <td class="table-actions">
                            <a class="action-btn" href="emprestimos.php?edit=<?= $l['id_emprestimo'] ?>">Editar</a>
                            <?php if($l['status'] !== 'Ativo'): ?>
                                <a class="action-btn" href="emprestimos.php?del=<?= $l['id_emprestimo'] ?>" onclick="return confirmarExclusao(<?= $l['id_emprestimo'] ?>);">Excluir</a>
                            <?php endif; ?>

                            <?php if($l['status'] !== 'Devolvido'): ?>
                                <a class="action-btn" href="emprestimos.php?dev=<?= $l['id_emprestimo'] ?>">Devolver</a>
                            <?php endif; ?>
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
    <script src="../js/theme.js"></script>
    <script src="../js/dashboard.js"></script>

    <!-- Ion Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
