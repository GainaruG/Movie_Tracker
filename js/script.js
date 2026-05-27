(function () {
  var root = document.documentElement;
  var THEME_KEY = 'mt-theme';
  var LANG_KEY = 'mt-lang';

  // Apply persisted theme immediately to avoid any flash.
  var savedTheme = localStorage.getItem(THEME_KEY);
  if (savedTheme !== 'light' && savedTheme !== 'dark') savedTheme = 'dark';
  root.setAttribute('data-theme', savedTheme);
  root.style.colorScheme = savedTheme;

  // Sync language selector from localStorage if available and different from server value.
  document.addEventListener('DOMContentLoaded', function () {
    var sel = document.querySelector('[data-lang]');
    if (sel) {
      var savedLang = localStorage.getItem(LANG_KEY);
      if (savedLang && savedLang !== sel.value) {
        var url = new URL(location.href);
        if (url.searchParams.get('lang') !== savedLang) {
          url.searchParams.set('lang', savedLang);
          location.replace(url.toString());
          return;
        }
      } else {
        localStorage.setItem(LANG_KEY, sel.value);
      }
    }
  });

  document.addEventListener('click', function (e) {
    if (e.target.closest('[data-theme-toggle]')) {
      var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-theme', next);
      root.style.colorScheme = next;
      localStorage.setItem(THEME_KEY, next);
      return;
    }
    if (e.target.closest('[data-menu]')) {
      document.querySelector('[data-links]')?.classList.toggle('open');
    }
    // Profile dropdown toggle
    var profileToggle = e.target.closest('[data-profile-toggle]');
    if (profileToggle) {
      e.stopPropagation();
      var wrapper = profileToggle.closest('.profile-wrapper');
      wrapper?.classList.toggle('open');
      document.querySelector('.notif-wrapper.open')?.classList.remove('open');
      return;
    }
    // Notifications bell toggle
    var notifToggle = e.target.closest('[data-notif-toggle]');
    if (notifToggle) {
      e.stopPropagation();
      var nWrap = notifToggle.closest('.notif-wrapper');
      nWrap?.classList.toggle('open');
      document.querySelector('.profile-wrapper.open')?.classList.remove('open');
      return;
    }
    // Mark all notifications as read
    if (e.target.closest('[data-notif-mark]')) {
      var badge = document.querySelector('.nfx-bell .nfx-badge');
      if (badge) badge.remove();
      var list = document.querySelector('.notif-list');
      if (list) {
        var emptyText = (window.MT_I18N && window.MT_I18N.notif_empty) || 'No new notifications';
        list.outerHTML = '<div class="notif-empty">' + emptyText + '</div>';
      }
      document.querySelector('[data-notif-mark]')?.remove();
      return;
    }
    // Close profile dropdown when clicking outside
    var profileWrapper = document.querySelector('.profile-wrapper.open');
    if (profileWrapper && !e.target.closest('.profile-menu')) {
      profileWrapper.classList.remove('open');
    }
    // Close notifications when clicking outside
    var notifWrapper = document.querySelector('.notif-wrapper.open');
    if (notifWrapper && !e.target.closest('.notif-menu')) {
      notifWrapper.classList.remove('open');
    }
    var open = e.target.closest('[data-open]');
    if (open) {
      document.querySelector(open.dataset.open)?.classList.add('open');
    }
    if (e.target.closest('[data-close]')) {
      e.target.closest('.modal')?.classList.remove('open');
    }
    var detail = e.target.closest('[data-detail]');
    if (detail) {
      var m = JSON.parse(detail.dataset.detail);
      var i = window.MT_I18N || {};
      var tGenre = i['g_' + m.genre] || m.genre;
      document.querySelector('#movieTitle').textContent = m.title;
      document.querySelector('#movieMeta').textContent =
        (i.genre || 'Genre') + ': ' + tGenre + ' · ' +
        (i.year || 'Year') + ': ' + m.year + ' · ' +
        (i.duration || 'Duration') + ': ' + m.duration + ' · ' +
        (i.age_rating || 'Age') + ': ' + m.age;
      document.querySelector('#movieDesc').textContent = m.description;
      document.querySelector('#movieStars').textContent = m.stars;
      var poster = document.querySelector('#moviePoster');
      poster.style.setProperty('--poster', m.poster || 'linear-gradient(135deg,#111,#e50914)');
      poster.style.setProperty('--poster-img', m.poster_url ? "url('" + m.poster_url + "')" : 'none');
      // Also inject an <img> for guaranteed rendering
      var existingImg = poster.querySelector('.billboard-poster');
      if (existingImg) existingImg.remove();
      if (m.poster_url) {
        var img = document.createElement('img');
        img.className = 'billboard-poster';
        img.src = m.poster_url;
        img.alt = m.title || '';
        poster.insertBefore(img, poster.firstChild);
      }
      document.querySelector('#detailsModal').classList.add('open');
    }
  });

  document.addEventListener('change', function (e) {
    if (e.target.matches('[data-lang]')) {
      var lang = e.target.value;
      localStorage.setItem(LANG_KEY, lang);
      var url = new URL(location.href);
      url.searchParams.set('lang', lang);
      location.href = url.toString();
    }
  });

  window.filterMovies = function () {
    var q = (document.querySelector('[data-search]')?.value || '').toLowerCase();
    var genre = document.querySelector('[data-genre]')?.value || 'all';
    document.querySelectorAll('[data-card]').forEach(function (card) {
      var ok = (genre === 'all' || card.dataset.genre === genre) && (card.dataset.text || '').includes(q);
      card.style.display = ok ? 'flex' : 'none';
    });
  };

  window.fillMovieForm = function (movie) {
    var form = document.querySelector('#movieForm');
    if (!form) return;
    form.reset();
    form.action_type.value = movie ? 'edit' : 'add';
    form.id.value = movie?.id || '';
    ['title', 'description', 'genre', 'year', 'rating', 'section', 'duration', 'age', 'status', 'poster_url'].forEach(function (k) {
      if (form[k]) form[k].value = movie?.[k] || '';
    });
    document.querySelector('#movieFormModal').classList.add('open');
  };

  window.updateCount = function (el) {
    var c = document.querySelector('#count');
    if (c) c.textContent = el.value.length;
  };
})();
