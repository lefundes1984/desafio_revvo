<?php
use App\Domain\Course\Course;

$errors = $errors ?? [];
$old = $old ?? [];
$flash = $flash ?? null;
?>
<?php
$isEdit = ($old['mode'] ?? null) === 'edit';
$idToken = $old['id_token'] ?? null;
?>
<section class="mx-auto max-w-6xl px-6 py-12 space-y-8">
    <?php if ($flash): ?>
        <div class="hidden" data-toast data-toast-type="<?= htmlspecialchars($flash['type'] ?? 'success', ENT_QUOTES, 'UTF-8'); ?>">
            <?= htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>
    <header class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-accent">Administração</p>
            <h1 class="text-3xl font-semibold text-slate-900"><?= $isEdit ? 'Editar curso' : 'Cadastrar curso'; ?></h1>
            <p class="text-sm text-muted"><?= $isEdit ? 'Atualize os dados do curso selecionado.' : 'Preencha os dados para criar um novo curso na plataforma.'; ?></p>
        </div>
        <a href="/" class="text-sm font-semibold text-accent hover:text-sky-500">Voltar para a home</a>
    </header>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <form method="POST" action="<?= $isEdit ? '/admin/courses/' . htmlspecialchars((string) $idToken, ENT_QUOTES, 'UTF-8') : '/admin/courses'; ?>" enctype="multipart/form-data" class="space-y-6 px-6 py-6" data-course-form data-redirect="/">
            <div class="space-y-2">
                <label for="title" class="block text-sm font-medium text-slate-700">Título *</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= htmlspecialchars($old['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    class="w-full rounded-lg border <?= isset($errors['title']) ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200 focus:border-accent focus:ring-2 focus:ring-accent/30'; ?> bg-white px-4 py-3 text-sm shadow-inner outline-none transition"
                    placeholder="Ex: Fundamentos de Design Thinking"
                    <?= $isEdit ? '' : 'required'; ?>
                >
                <?php if (isset($errors['title'])): ?>
                    <p class="text-xs font-semibold text-rose-600"><?= htmlspecialchars(implode(' ', $errors['title']), ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
            </div>

            <div class="space-y-2">
                <label for="description" class="block text-sm font-medium text-slate-700">Descrição *</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full rounded-lg border <?= isset($errors['description']) ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200 focus:border-accent focus:ring-2 focus:ring-accent/30'; ?> bg-white px-4 py-3 text-sm shadow-inner outline-none transition"
                    placeholder="Descreva brevemente o curso..."
                    <?= $isEdit ? '' : 'required'; ?>
                ><?= htmlspecialchars($old['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                <?php if (isset($errors['description'])): ?>
                    <p class="text-xs font-semibold text-rose-600"><?= htmlspecialchars(implode(' ', $errors['description']), ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
            </div>

            <div class="space-y-2">
                <label for="cover_image" class="block text-sm font-medium text-slate-700">Imagem de capa *</label>
                <div class="flex items-center gap-3">
                    <input
                        type="file"
                        id="cover_image"
                        name="cover_image"
                    accept="image/*"
                    class="block w-full cursor-pointer rounded-lg border <?= isset($errors['cover_url']) ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200 focus:border-accent focus:ring-2 focus:ring-accent/30'; ?> bg-white px-4 py-3 text-sm shadow-inner transition"
                    <?= $isEdit ? '' : 'required'; ?>
                >
                    <img id="cover_preview" src="<?= htmlspecialchars($old['cover_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" data-src="<?= htmlspecialchars($old['cover_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" alt="" class="hidden h-14 w-14 rounded-lg object-cover ring-1 ring-slate-200">
                </div>
                    <?php if ($isEdit && !empty($old['cover_url'])): ?>
                        <p class="text-xs text-slate-500">Atual: <a href="<?= htmlspecialchars($old['cover_url'], ENT_QUOTES, 'UTF-8'); ?>" class="underline" target="_blank" rel="noreferrer">ver imagem</a></p>
                    <?php endif; ?>
                    <?php if (isset($errors['cover_url'])): ?>
                        <p class="text-xs font-semibold text-rose-600"><?= htmlspecialchars(implode(' ', $errors['cover_url']), ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </div>

            <div class="space-y-2">
                <label for="slide_image" class="block text-sm font-medium text-slate-700">Imagem do slide *</label>
                <div class="flex items-center gap-3">
                    <input
                        type="file"
                        id="slide_image"
                        name="slide_image"
                    accept="image/*"
                    class="block w-full cursor-pointer rounded-lg border <?= isset($errors['slide_image_url']) ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200 focus:border-accent focus:ring-2 focus:ring-accent/30'; ?> bg-white px-4 py-3 text-sm shadow-inner transition"
                    <?= $isEdit ? '' : 'required'; ?>
                >
                    <img id="slide_preview" src="<?= htmlspecialchars($old['slide_image_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" data-src="<?= htmlspecialchars($old['slide_image_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" alt="" class="hidden h-14 w-14 rounded-lg object-cover ring-1 ring-slate-200">
                </div>
                    <?php if ($isEdit && !empty($old['slide_image_url'])): ?>
                        <p class="text-xs text-slate-500">Atual: <a href="<?= htmlspecialchars($old['slide_image_url'], ENT_QUOTES, 'UTF-8'); ?>" class="underline" target="_blank" rel="noreferrer">ver imagem</a></p>
                    <?php endif; ?>
                    <?php if (isset($errors['slide_image_url'])): ?>
                        <p class="text-xs font-semibold text-rose-600"><?= htmlspecialchars(implode(' ', $errors['slide_image_url']), ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </div>

            <div class="flex flex-wrap items-center justify-end gap-3">
                <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                    <input type="checkbox" name="is_featured" value="1" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" <?= (!empty($old['is_featured'])) ? 'checked' : ''; ?>>
                    Destaque
                </label>
                <a href="/admin/courses" class="text-sm font-semibold text-muted hover:text-slate-900">Limpar</a>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-accent px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                >
                    <?= $isEdit ? 'Atualizar curso' : 'Salvar curso'; ?>
                </button>
            </div>
        </form>
    </div>
</section>
