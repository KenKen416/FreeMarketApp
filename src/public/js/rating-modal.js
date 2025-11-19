// rating-modal.js
document.addEventListener('DOMContentLoaded', function () {
  const overlay = document.getElementById('ratingModalOverlay');
  if (!overlay) return;

  const modal = overlay.querySelector('.rating-modal');
  const closeBtn = document.getElementById('ratingModalClose');
  const stars = Array.from(overlay.querySelectorAll('.star'));
  const scoreInput = document.getElementById('rating_score');
  const form = document.getElementById('ratingForm');

  let selected = 0;

  // helper: set active class up to value
  function setStars(value) {
    selected = Number(value) || 0;
    stars.forEach(st => {
      const v = Number(st.dataset.value);
      if (v <= selected) {
        st.classList.add('active');
        st.setAttribute('aria-pressed', 'true');
      } else {
        st.classList.remove('active');
        st.setAttribute('aria-pressed', 'false');
      }
    });
    if (scoreInput) scoreInput.value = selected;
  }

  // hover effect
  stars.forEach(st => {
    st.addEventListener('click', function () {
      const v = Number(this.dataset.value);
      setStars(v);
    });
    st.addEventListener('mouseover', function () {
      const v = Number(this.dataset.value);
      stars.forEach(s => s.classList.toggle('hover', Number(s.dataset.value) <= v));
    });
    st.addEventListener('mouseout', function () {
      stars.forEach(s => s.classList.remove('hover'));
    });
    // keyboard
    st.addEventListener('keydown', function (e) {
      const current = Number(this.dataset.value);
      if (e.key === 'ArrowLeft' && current > 1) {
        stars[current - 2].focus();
      } else if (e.key === 'ArrowRight' && current < stars.length) {
        stars[current].focus();
      } else if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        setStars(current);
      }
    });
  });

  // close handler
  closeBtn?.addEventListener('click', function () {
    overlay.style.display = 'none';
    overlay.setAttribute('aria-hidden', 'true');
  });

  // overlay click outside to close
  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) {
      overlay.style.display = 'none';
      overlay.setAttribute('aria-hidden', 'true');
    }
  });

  // auto-open if server requested (body dataset or inline script)
  const shouldShow = document.body.dataset.showRating === '1' || document.body.dataset.showRating === 1 || document.body.dataset.showRating === 'true';
  if (shouldShow) {
    overlay.style.display = 'flex';
    overlay.setAttribute('aria-hidden', 'false');

    // focus first star for keyboard users
    const firstStar = stars[0];
    if (firstStar) firstStar.focus();
  }

  // safe default: submit only when star selected
  form?.addEventListener('submit', function (e) {
    if (!scoreInput || !scoreInput.value || Number(scoreInput.value) < 1) {
      e.preventDefault();
      // indicate user to select star (could be toast or simple shake)
      // simple alert for now:
      alert('評価を選択してください');
      return false;
    }
    // else normal submit proceeds (POST to ratings.store)
  });
});