<?php
include('conexao.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Cadastro</title>
</head>
<body>
    <header>
        <h1>BookLover</h1>
        <nav>
            <a href="index.html">Início</a>
            <a href="index.html #destaque">Destaques</a>
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

    <!-- Tema -->
     <script src="../js/theme.js"></script>

    <!-- Ion Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>