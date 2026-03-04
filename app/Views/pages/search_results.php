<?php
use App\Domain\Course\Course;

/** @var Course[] $courses */
$resultsLabel = $resultsCount === 1 ? 'resultado' : 'resultados';
?>

<section class="mx-auto max-w-6xl px-6 py-12 space-y-8">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="space-y-2">
            <p class="text-sm font-semibold uppercase tracking-wide text-muted">Busca de cursos</p>
            <h1 class="text-3xl font-semibold text-slate-900">Resultados encontrados</h1>
            <?php if ($query === ''): ?>
                <p class="text-sm text-muted">Digite um título ou palavra-chave para localizar cursos.</p>
            <?php else: ?>
                <p class="text-sm text-muted">
                    <?= htmlspecialchars($resultsCount, ENT_QUOTES, 'UTF-8'); ?> <?= $resultsLabel; ?> para
                    <span class="font-semibold text-slate-900">"<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8'); ?>"</span>
                </p>
            <?php endif; ?>
        </div>
        <form action="/search" method="get" class="w-full sm:w-auto">
            <label class="search-field w-full sm:w-80" aria-label="Buscar cursos">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m16 16 4 4" />
                </svg>
                <input
                    type="search"
                    name="q"
                    value="<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8'); ?>"
                    placeholder="Pesquisar cursos..."
                    class="search-input"
                    autocomplete="off"
                >
            </label>
        </form>
    </div>

    <?php if ($query === ''): ?>
        <div class="rounded-lg border border-dashed border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-base font-semibold text-slate-900">Como funciona a busca?</p>
            <p class="mt-2 text-sm text-muted">
                Procure por qualquer parte do título ou descrição de um curso e mostraremos os resultados aqui.
            </p>
        </div>
    <?php elseif (empty($courses)): ?>
        <div class="rounded-lg border border-rose-300/60 bg-white p-6 shadow-sm ring-2 ring-rose-100">
            <p class="text-base font-semibold text-slate-900">Nenhum curso encontrado</p>
            <p class="mt-2 text-sm text-muted">
                Não localizamos cursos para "<span class="font-semibold text-slate-900"><?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8'); ?></span>".
                Tente outro termo ou verifique a ortografia.
            </p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($courses as $course): ?>
                <?php /** @var Course $course */ ?>
                <article class="group flex h-full flex-col overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="card-media">
                        <?php if (!empty($course->coverUrl)): ?>
                            <img
                                src="<?= htmlspecialchars($course->coverUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                alt="Capa do curso <?= htmlspecialchars($course->title, ENT_QUOTES, 'UTF-8'); ?>"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            >
                        <?php else: ?>
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="14" rx="2" ry="2"></rect>
                                    <circle cx="8" cy="10" r="1.25"></circle>
                                    <path d="m3 17 4.5-4.5c.66-.66 1.74-.66 2.4 0L17 19"></path>
                                    <path d="m14 15 1.5-1.5c.66-.66 1.74-.66 2.4 0L21 17"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        <div class="card-actions" data-course-token="<?= htmlspecialchars($course->token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            <a href="/admin/courses/<?= htmlspecialchars($course->token ?? '', ENT_QUOTES, 'UTF-8'); ?>/edit" class="card-icon" aria-label="Editar curso">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                </svg>
                            </a>
                            <button type="button" class="card-icon" data-course-delete aria-label="Excluir curso">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col gap-3 px-4 py-4">
                        <h3 class="text-lg font-semibold text-slate-900"><?= htmlspecialchars($course->title, ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p
                            class="text-sm text-muted"
                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                        >
                            <?= htmlspecialchars($course->description ?: 'Curso sem descrição.', ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                        <a
                            href="/courses/<?= htmlspecialchars($course->token ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            class="mt-auto inline-flex items-center justify-center rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500"
                        >
                            VER CURSO
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
