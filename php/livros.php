<?php 
session_start();
include('conexao.php');

// Cadastrar ou editar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $ano = $_POST['ano'];
    $genero = $_POST['genero'];
    $quantidade = $_POST['quantidade'];
    $arquivoCapa = null;

    if (!empty($_FILES['capa']['name'])) {
        $nomeArquivo = time() . "_" . $_FILES['capa']['name'];
        $destino = "../uploads/" . $nomeArquivo;

        if (!is_dir("../uploads")) {
            mkdir("../uploads", 0777, true);
        }

        move_uploaded_file($_FILES['capa']['tmp_name'], $destino);

        $arquivoCapa = $nomeArquivo;
    }

    if (!empty($_POST['id_livro'])) {
        $id = $_POST['id_livro'];

        $sqlUpdate = "update livros set
                      titulo='$titulo',
                      autor='$autor',
                      ano='$ano',
                      genero='$genero',
                      quantidade='$quantidade'";
        
        if ($arquivoCapa) {
            $sqlUpdate .= ", capa='$arquivoCapa'";
        }

        $sqlUpdate .= " where id_livro=$id";

        $mysqli->query($sqlUpdate);
    } else {
        $mysqli->query("INSERT INTO livros(titulo, autor, ano, genero, quantidade, capa)
        VALUES ('$titulo', '$autor', '$ano', '$genero', '$quantidade', '$arquivoCapa')");
    }

    header("Location: livros.php");
    exit;

}

// Excluir
if(isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $consulta = $mysqli->prepare("select id_emprestimo
                                  from emprestimos
                                  where id_livro = ?
                                  and status = 'Ativo'");

    $consulta->bind_param("i", $id);
    $consulta->execute();
    $res = $consulta->get_result();

    if ($res->num_rows > 0) {
        $_SESSION['mensagem'] = "Este livro não pode ser excluído pois possui empréstimos em aberto.";
        $_SESSION['tipo_msg'] = "error";

        header("Location: livros.php");
        exit;
    }
    $mysqli->query("delete from livros where id_livro = $id");
    $mensagem = "Livro excluído com sucesso!";
    $tipo_msg = "success";
    header("Location: livros.php");
    exit;
}

// Buscar para editar
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $mysqli->query("select * from livros where id_livro=$id limit 1");
    $edit = $res->fetch_assoc();
}

// Listar com pesquisa
$busca = $_GET['q'] ?? "";
$sql = "select * from livros where 1";

if ($busca !== "") {
    if (is_numeric($busca)) {
        $sql .= " and (id_livro = $busca 
                       or ano like '%$busca%'
                       or titulo like '%$busca%'
                       or autor like '%$busca%'
                       or genero like '%$busca%')";
    } else {
        $sql .= " and (titulo like '%$busca%' 
                       or genero like '%$busca%' 
                       or ano like '%$busca%')";
    }
}

$sql .= " order by titulo asc";
$dados = $mysqli->query($sql);
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Livros - BookLover</title>
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
                <a href="livros.php" class="active">Gerenciar livros</a>
                <a href="clientes.php">Gerenciar clientes</a>
                <a href="emprestimos.php">Empréstimos</a>
            </nav>
        </aside>

        <main class="main-content">
            <h2>Gerenciar livros</h2>

            <form method="POST" class="form-card" enctype="multipart/form-data">
                <input type="hidden" name="id_livro" class="input" value="<?= $edit['id_livro'] ?? '' ?>">

                <label>Título:</label>
                <input type="text" name="titulo" class="input" required value="<?= $edit['titulo'] ?? '' ?>">

                <label>Autor:</label>
                <input type="text" name="autor" class="input" required value="<?= $edit['autor'] ?? '' ?>">

                <label>Ano:</label>
                <input type="text" name="ano" class="input" required value="<?= $edit['ano'] ?? '' ?>">

                <label>Gênero:</label>
                <input type="text" name="genero" class="input" required value="<?= $edit['genero'] ?? '' ?>">

                <label>Quantidade:</label>
                <input type="number" name="quantidade" class="input" required value="<?= $edit['quantidade'] ?? '' ?>">

                <label>Capa do livro</label>
                <input type="file" name="capa" class="input" accept="image/*">

                <button type="submit" class="btn-submit"><?= $edit ? "Salvar Alterações" : "Cadastrar" ?></button>
            </form>

            <form method="GET" class="search-box">
                <input type="text" name="q" placeholder="Pesquisar livro..." value="<?= $busca ?>">
                <button class="btn-submit">Buscar</button>
            </form>

            <table border="1" width="100%" cellpadding="8">
                <tr>
                    <th>Capa</th>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Ano</th>
                    <th>Gênero</th>
                    <th>Qtd</th>
                    <th>Ações</th>
                </tr>

                <?php while($l = $dados->fetch_assoc()): ?>
                    <tr>
                        <td>
                        <?php if (!empty($l['capa'])): ?>
                            <img src="../uploads/<?= $l['capa']; ?>" class="img-livro" alt="Capa do livro" width="60">
                        <?php else: ?>
                            Sem capa 
                        <?php endif; ?>
                        </td>
                        <td><?= $l['id_livro'] ?></td>
                        <td><?= $l['titulo'] ?></td>
                        <td><?= $l['autor'] ?></td>
                        <td><?= $l['ano'] ?></td>
                        <td><?= $l['genero'] ?></td>
                        <td><?= $l['quantidade'] ?></td>
                        <td class="table-actions">
                            <a class="action-btn" href="livros.php?edit=<?= $l['id_livro'] ?>">Editar</a>
                            <a class="action-btn" href="livros.php?del=<?= $l['id_livro'] ?>" onclick="return confirmarExclusao(<?= $l['id_livro'] ?>);">Excluir</a>
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
