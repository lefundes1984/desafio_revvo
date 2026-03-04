<?php
  $pageTitle = $pageTitle ?? 'Desafio Revvo';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="/assets/dist/app.min.css">
</head>
<body>
    <header class="nav-shell">
        <div class="nav-inner">
            <div class="brand-mark">
                <span class="hidden sm:block">REVVO</span>
            </div>
            <div class="ml-auto flex items-center gap-3">
                <input class="search-field" type="search" name="q" placeholder="Pesquisar cursos..." aria-label="Buscar">
                <div class="user-chip">
                    <img src="https://placekitten.com/80/80" alt="Avatar do usuário" class="h-8 w-8 rounded-full object-cover shadow-inner">
                    <div class="leading-tight text-left">
                        <p class="text-xs text-muted">Seja bem-vindo</p>
                        <p class="text-sm font-semibold">XPTO</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M12 15.75a.75.75 0 0 1-.53-.22l-5.25-5.25a.75.75 0 1 1 1.06-1.06L12 13.94l4.72-4.72a.75.75 0 1 1 1.06 1.06l-5.25 5.25a.75.75 0 0 1-.53.22Z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
    </header>

    <main class="min-h-screen">
        <?= $content ?? ''; ?>
    </main>

    <script src="/assets/dist/app.min.js"></script>
</body>
</html>
