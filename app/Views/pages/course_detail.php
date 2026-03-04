<?php
use App\Domain\Course\Course;
/** @var Course $course */
?>
<section class="mx-auto max-w-5xl px-6 py-12 space-y-6">
    <div class="flex flex-wrap items-center gap-4">
        <a href="/" class="text-sm font-semibold text-accent hover:text-sky-500">← Voltar</a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <?php if (!empty($course->coverUrl)): ?>
            <img src="<?= htmlspecialchars($course->coverUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="Capa do curso" class="h-72 w-full object-cover">
        <?php endif; ?>
        <div class="space-y-4 p-6">
            <h1 class="text-3xl font-semibold text-slate-900"><?= htmlspecialchars($course->title, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="text-base text-slate-700 leading-relaxed"><?= nl2br(htmlspecialchars($course->description, ENT_QUOTES, 'UTF-8')); ?></p>
            <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-700 ring-1 ring-slate-100">
                <h2 class="text-base font-semibold text-slate-900">Conteúdo do curso</h2>
                <p class="mt-2 text-sm text-slate-700">Em desenvolvimento.</p>
            </div>
        </div>
    </div>
</section>
