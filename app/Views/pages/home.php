<?php
use App\Domain\Course\Course;
use App\Domain\Slideshow\Slide;

$heroItems = $courseSlides ?? [];
?>
<?php if (!empty($courseSlides)): ?>
<section class="hero-shell" data-slider>
    <button type="button" class="hero-nav left-0" data-slide-prev aria-label="Slide anterior">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m15 19-7-7 7-7" />
        </svg>
    </button>
    <button type="button" class="hero-nav right-0" data-slide-next aria-label="Próximo slide">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m9 5 7 7-7 7" />
        </svg>
    </button>

    <?php foreach ($heroItems as $index => $item): ?>
        <?php /** @var Slide|Course $item */ ?>
        <?php
            $bg = $item->slideImageUrl ?? $item->imageUrl ?? $item->coverUrl ?? '/assets/uploads/slide-1-placeholder.svg';
            $title = $item->title ?? 'LOREM IPSUM';
            $description = $item->description ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.';
        ?>
        <article
            class="hero-slide"
            data-slide
            style="background-image: linear-gradient(120deg, rgba(15,23,42,0.75), rgba(14,165,233,0.25)), url('<?= htmlspecialchars($bg, ENT_QUOTES, 'UTF-8'); ?>'); opacity: <?= $index === 0 ? '1' : '0'; ?>"
        >
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <div class="hero-panel">
                    <h1 class="hero-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p class="max-w-[480px] text-base text-white/80">
                        <?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="#" class="rounded-full bg-accent px-5 py-2 text-sm font-semibold text-white shadow hover:bg-sky-500">Ver curso</a>
                    </div>
                </div>
            </div>
        </article>
    <?php endforeach; ?>

    <div class="hero-dots">
        <?php foreach ($heroItems as $index => $item): ?>
            <button type="button" class="hero-dot <?= $index === 0 ? 'is-active' : ''; ?>" aria-label="Ir para o slide <?= $index + 1; ?>" data-slide-dot></button>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="mx-auto max-w-6xl px-6 py-12">
    <div class="flex flex-wrap items-end justify-between gap-3 pb-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">MEUS CURSOS</h2>
        </div>
    </div>

    <?php if (empty($courses)): ?>
        <div class="mb-6 rounded-lg border border-dashed border-slate-200 bg-white px-4 py-3 text-sm text-muted shadow-sm">
            Nenhum curso cadastrado ainda. Adicione o primeiro curso abaixo.
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
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
                        <?= htmlspecialchars($course->description ?: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    <button
                        type="button"
                        class="mt-auto inline-flex items-center justify-center rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500"
                    >
                        VER CURSO
                    </button>
                </div>
            </article>
        <?php endforeach; ?>

        <article class="flex h-full flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-slate-300 bg-slate-100 px-4 py-6 text-center shadow-inner">
            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-white text-emerald-600 shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 8v8"></path>
                    <path d="M8 12h8"></path>
                </svg>
            </div>
            <div class="space-y-1">
                <p class="text-base font-semibold uppercase tracking-wide text-slate-900">Adicionar curso</p>
            </div>
            <a
                href="/admin/courses"
                class="inline-flex items-center justify-center rounded-full border border-emerald-500 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-emerald-600 transition hover:-translate-y-0.5 hover:bg-emerald-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500"
            >
                Novo curso
            </a>
        </article>
    </div>
</section>

<div class="modal-overlay is-hidden" data-modal>
    <div class="modal-shell" role="dialog" aria-modal="true" aria-label="Convite de inscrição">
        <button type="button" class="modal-close" data-modal-close aria-label="Fechar modal">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="m7 7 10 10M7 17 17 7" />
            </svg>
        </button>
        <div class="modal-body">
            <div class="modal-half modal-hero">IMAGEM</div>
            <div class="modal-half modal-content">
                <div class="modal-text">
                    <h3 class="modal-title">EGESTAS TORTOR VULPUTATE</h3>
                    <p>Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Donec ullamcorper nulla non metus auctor fringilla. Donec sed odio dui. Cras</p>
                </div>
                <button type="button" class="modal-action" data-modal-close>INSCREVA-SE</button>
            </div>
        </div>
    </div>
</div>
