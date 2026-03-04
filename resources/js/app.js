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

(() => {
  const initialPreview = (inputId, previewId, value) => {
    const preview = document.getElementById(previewId);
    if (!preview || !value) return;
    preview.src = value;
    preview.classList.remove('hidden');
  };

  initialPreview('cover_image', 'cover_preview', document.getElementById('cover_preview')?.dataset.src);
  initialPreview('slide_image', 'slide_preview', document.getElementById('slide_preview')?.dataset.src);
})();

(() => {
  const buttons = document.querySelectorAll('[data-course-delete]');
  if (!buttons.length) return;

  const confirmBox = async () => {
    return new Promise((resolve) => {
      const overlay = document.createElement('div');
      overlay.className = 'swal-overlay';
      overlay.innerHTML = `
        <div class="swal-dialog">
          <h3 class="swal-title">Deseja excluir?</h3>
          <p class="swal-text">Essa ação é irreversível.</p>
          <div class="swal-actions">
            <button type="button" data-swal-cancel>Cancelar</button>
            <button type="button" data-swal-confirm>Excluir</button>
          </div>
        </div>
      `;
      document.body.appendChild(overlay);

      const cleanup = () => overlay.remove();
      overlay.querySelector('[data-swal-cancel]')?.addEventListener('click', () => {
        cleanup();
        resolve(false);
      });
      overlay.querySelector('[data-swal-confirm]')?.addEventListener('click', () => {
        cleanup();
        resolve(true);
      });
    });
  };

  buttons.forEach((btn) => {
    btn.addEventListener('click', async (event) => {
      event.preventDefault();
      const token = btn.closest('[data-course-token]')?.dataset.courseToken;
      if (!token) return;
      const ok = await confirmBox();
      if (!ok) return;

      fetch(`/admin/courses/${encodeURIComponent(token)}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json' }
      })
        .then((res) => res.ok ? location.reload() : res.json())
        .catch(() => null);
    });
  });
})();

(() => {
  const createToast = (message, type = 'success', redirectTo = null) => {
    const toast = document.createElement('div');
    toast.className = `toast ${type === 'error' ? 'is-error' : 'is-success'}`;
    toast.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        ${type === 'error'
          ? '<path d="M12 9v4"/><path d="M12 17h.01"/><path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />'
          : '<path d="M9 12.75 11.25 15 15 9.75" /><path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />'}
      </svg>
      <span>${message}</span>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
      toast.classList.add('opacity-0', '-translate-y-2');
    }, 2200);
    setTimeout(() => {
      toast.remove();
      if (redirectTo) window.location.href = redirectTo;
    }, 3000);
  };

  const toastEl = document.querySelector('[data-toast]');
  if (toastEl) {
    const type = toastEl.dataset.toastType || 'success';
    const msg = toastEl.textContent.trim();
    createToast(msg, type, '/');
    toastEl.remove();
  }

  const form = document.querySelector('[data-course-form]');
  if (!form) return;

  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const action = form.getAttribute('action') || window.location.pathname;
    const method = (form.getAttribute('method') || 'POST').toUpperCase();
    const redirectTo = form.dataset.redirect || '/';
    const formData = new FormData(form);

    fetch(action, {
      method,
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(async (res) => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
          if (data?.errors) {
            const first = Object.values(data.errors)[0]?.[0] ?? 'Não foi possível salvar.';
            createToast(first, 'error');
          } else {
            createToast('Não foi possível salvar.', 'error');
          }
          return;
        }
        createToast(data?.message ?? 'Salvo com sucesso!', 'success', redirectTo);
      })
      .catch(() => {
        createToast('Erro de rede. Tente novamente.', 'error');
      });
  });
})();
