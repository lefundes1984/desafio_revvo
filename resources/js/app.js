(() => {
  const sliders = document.querySelectorAll('[data-slider]');
  if (!sliders.length) return;

  sliders.forEach((slider) => {
    const slides = slider.querySelectorAll('[data-slide]');
    const dots = slider.querySelectorAll('[data-slide-dot]');
    const nextBtn = slider.querySelector('[data-slide-next]');
    const prevBtn = slider.querySelector('[data-slide-prev]');
    let index = 0;
    let timer;

    const setActive = (target) => {
      slides.forEach((slide, i) => {
        slide.style.opacity = i === target ? '1' : '0';
        slide.style.pointerEvents = i === target ? 'auto' : 'none';
      });
      dots.forEach((dot, i) => dot.classList.toggle('is-active', i === target));
      index = target;
    };

    const next = () => setActive((index + 1) % slides.length);
    const prev = () => setActive((index - 1 + slides.length) % slides.length);

    const start = () => {
      stop();
      timer = setInterval(next, 5000);
    };
    const stop = () => timer && clearInterval(timer);

    nextBtn?.addEventListener('click', () => {
      next();
      start();
    });
    prevBtn?.addEventListener('click', () => {
      prev();
      start();
    });
    dots.forEach((dot, i) =>
      dot.addEventListener('click', () => {
        setActive(i);
        start();
      })
    );

    slider.addEventListener('pointerenter', stop);
    slider.addEventListener('pointerleave', start);

    setActive(0);
    start();
  });
})();

(() => {
  const overlay = document.querySelector('[data-modal]');
  if (!overlay) return;

  const closeButtons = overlay.querySelectorAll('[data-modal-close]');
  const storageKey = 'revvo_modal_seen';

  const hide = () => overlay.classList.add('is-hidden');
  const show = () => overlay.classList.remove('is-hidden');

  const hasSeen = (() => {
    try {
      return localStorage.getItem(storageKey) === '1';
    } catch (e) {
      return false;
    }
  })();

  if (hasSeen) {
    hide();
  } else {
    show();
    try {
      localStorage.setItem(storageKey, '1');
    } catch (e) {
      // ignore storage errors
    }
  }

  closeButtons.forEach((btn) => btn.addEventListener('click', hide));
  overlay.addEventListener('click', (event) => {
    if (event.target === overlay) hide();
  });
})();

(() => {
  const makePreview = (inputId, previewId) => {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (!input || !preview) return;

    input.addEventListener('change', () => {
      const [file] = input.files || [];
      if (!file) {
        preview.classList.add('hidden');
        preview.src = '#';
        return;
      }
      const url = URL.createObjectURL(file);
      preview.src = url;
      preview.classList.remove('hidden');
      preview.onload = () => URL.revokeObjectURL(url);
    });
  };

  makePreview('cover_image', 'cover_preview');
  makePreview('slide_image', 'slide_preview');
})();
