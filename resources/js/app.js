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
