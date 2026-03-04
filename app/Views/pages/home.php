<?php
use App\Domain\Slideshow\Slide;
?>
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

    <?php foreach ($slides as $index => $slide): ?>
        <?php /** @var Slide $slide */ ?>
        <article
            class="hero-slide"
            data-slide
            style="background-image: linear-gradient(120deg, rgba(15,23,42,0.75), rgba(14,165,233,0.25)), url('<?= htmlspecialchars($slide->imageUrl, ENT_QUOTES, 'UTF-8'); ?>'); opacity: <?= $index === 0 ? '1' : '0'; ?>"
        >
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <div class="hero-panel">
                    <h1 class="hero-title">LOREM IPSUM</h1>
                    <p class="max-w-[480px] text-base text-white/80">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="#" class="rounded-full bg-accent px-5 py-2 text-sm font-semibold text-white shadow hover:bg-sky-500">Ver curso</a>
                    </div>
                </div>
            </div>
        </article>
    <?php endforeach; ?>

    <div class="hero-dots">
        <?php foreach ($slides as $index => $slide): ?>
            <button type="button" class="hero-dot <?= $index === 0 ? 'is-active' : ''; ?>" aria-label="Ir para o slide <?= $index + 1; ?>" data-slide-dot></button>
        <?php endforeach; ?>
    </div>
</section>