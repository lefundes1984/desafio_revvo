<?php
  $pageTitle = $pageTitle ?? 'Desafio Revvo';
  $searchValue = (string) ($searchValue ?? ($query ?? ''));
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
            <div class="nav-actions">
                <form class="search-field" action="/search" method="get" role="search" aria-label="Buscar cursos">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m16 16 4 4" />
                    </svg>
                    <input type="search" name="q" placeholder="Pesquisar cursos..." value="<?= htmlspecialchars($searchValue, ENT_QUOTES, 'UTF-8'); ?>" class="search-input" autocomplete="off">
                </form>
                <span class="nav-divider" aria-hidden="true"></span>
                <button type="button" class="user-chip">
                    <span class="user-avatar" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 20a6 6 0 0 0-12 0" />
                            <circle cx="12" cy="10" r="4" />
                        </svg>
                    </span>
                    <div class="leading-tight text-left">
                        <p class="text-[9px] text-muted font-normal">Seja bem-vindo</p>
                        <p class="text-[11px] font-semibold">XPTO</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M12 15.75a.75.75 0 0 1-.53-.22l-5.25-5.25a.75.75 0 1 1 1.06-1.06L12 13.94l4.72-4.72a.75.75 0 1 1 1.06 1.06l-5.25 5.25a.75.75 0 0 1-.53.22Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <main class="min-h-screen">
        <?= $content ?? ''; ?>
    </main>

    <footer class="footer-shell">
        <div class="footer-top">
            <div class="footer-inner">
                <div>
                    <div class="footer-brand">REVVO</div>
                    <p class="footer-subcopy">
                        Maecenas faucibus mollis interdum. Morbi leo risus, porta ac<br>
                        consectetur ac, vestibulum at eros.
                    </p>
                </div>
                <div class="footer-links">
                    <div>
                        <p class="footer-heading">// CONTATO</p>
                        <p class="footer-link">(21) 98765-3434</p>
                        <p class="footer-link">contato@leolearning.com</p>
                    </div>
                    <div>
                        <p class="footer-heading">// REDES SOCIAIS</p>
                        <div class="footer-social">
                            <a href="#" aria-label="Twitter" class="footer-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 9.4 2.2.1 4.4-.6 6-2C3 13.5 1.7 7.8 4.8 5c2 2.5 5 4 8.2 4-.7-3 3-5 5.5-3.2 1.1 0 3-1.2 3-1.2Z" />
                                </svg>
                            </a>
                            <a href="#" aria-label="YouTube" class="footer-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 8.5c0-1.1.9-2 2-2 4.7-.3 11.3-.3 16 0 1.1 0 2 .9 2 2 .2 2 .2 4 .2 4s0 2-.2 4c0 1.1-.9 2-2 2-4.7.3-11.3.3-16 0-1.1 0-2-.9-2-2-.2-2-.2-4-.2-4s0-2 .2-4Z" />
                                    <path d="m10 9 5 3-5 3V9Z" />
                                </svg>
                            </a>
                            <a href="#" aria-label="Pinterest" class="footer-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.2 7.4c1-2.8 6.7-3 7.3 1.1.7 4.8-5.6 5.7-5.6 2.6" />
                                    <path d="M8 20c.2-1.2 1.1-6.6 1.1-6.6a3.4 3.4 0 0 1-.3-2.3c.4-2 2.5-2.4 3.2-.9.7 1.4-.4 3.5-1.5 5.5-.4.8 0 1.2.7 1 1.9-.8 3.8-3.4 3.8-6 0-3-2.2-5.1-5.4-5.1-3.6 0-5.8 2.6-5.8 5.4 0 1.3.5 2.6 1.6 3.2.2.1.3.1.2-.1-.2-.6-.7-1.9-.8-2.2-.2-.6-.1-1 .1-1.7.7-1.8 1.1-1.8 1.5-1.8.8 0 .6.9.6 1.5 0 .8-.5 2-.5 2.8 0 .8.5 1.4 1.3 1.4.6 0 1.1-.2 1.7-.7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-inner text-xs text-slate-600">
                <span>Copyright 2026. Direitos reservados.</span>
            </div>
        </div>
    </footer>

    <script src="/assets/dist/app.min.js"></script>
</body>
</html>
