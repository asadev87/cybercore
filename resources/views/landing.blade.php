{{-- resources/views/landing.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CyberCore — Cybersecurity E-Learning for Everyone</title>
  <meta name="description" content="CyberCore is a secure, user-friendly e-learning platform to raise cybersecurity awareness for students, educators, and non-technical audiences.">
  <link rel="icon" href="/assets/img/favicon.svg">

  {{-- Bootstrap 5.3 CSS (CDN) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- CyberCore theme (load AFTER Bootstrap) --}}
  <link rel="stylesheet" href="{{ asset('assets/css/cybercore.css') }}?v={{ filemtime(public_path('assets/css/cybercore.css')) }}">
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <span class="brand-mark"></span>
      <span class="fw-bold">CyberCore</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#ccNav" aria-controls="ccNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="ccNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>

        @auth
          @role('admin')
            <li class="nav-item ms-lg-2">
              <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-accent">Admin</a>
            </li>
          @endrole
          <li class="nav-item ms-lg-2">
            <a class="btn btn-light btn-sm" href="{{ route('dashboard') }}">Dashboard</a>
          </li>
          <li class="nav-item ms-lg-2">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
          </li>
        @endauth

        @guest
          <li class="nav-item ms-lg-2">
            <a class="btn btn-outline-primary" href="{{ route('login') }}">Sign in</a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-accent btn-lg" href="{{ route('register') }}">Get started</a>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

{{-- HERO --}}
<header class="hero-wrap py-5 py-lg-5">
  <div class="container py-4">
    <div class="row align-items-center gy-4">
      <div class="col-lg-6">
        <div class="hero-kicker mb-2">Empowering Cybersecurity Knowledge</div>
        <h1 class="display-4 hero-title">
          Learn to spot threats. <span style="color:var(--cc-accent)">Stay safe online.</span>
        </h1>
        <p class="lead hero-sub mt-3">
          CyberCore is a secure, user-friendly e-learning platform designed to raise cybersecurity awareness
          for students, educators, and non-technical users through interactive modules and quizzes.
        </p>
        <div class="d-flex gap-3 mt-4">
          <a class="btn btn-primary btn-lg" href="{{ route('register') }}">Start learning</a>
          <a class="btn btn-light btn-lg" href="#features">Explore Features</a>
        </div>
        <div class="d-flex align-items-center gap-3 mt-4">
          <span class="badge-pill">Phishing</span>
          <span class="badge-pill">Malware</span>
          <span class="badge-pill">Password Hygiene</span>
          <span class="badge-pill">Safe Browsing</span>
        </div>
      </div>

      <div class="col-lg-6">
        {{-- Accessible illustrative panel (SVG, no external images) --}}
        <div class="p-4 p-lg-5 border rounded-4 shadow-sm" style="background-color: var(--cc-surface);">
          <svg role="img" aria-label="Illustration of secure learning" width="100%" height="260" viewBox="0 0 700 260" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="0" width="700" height="260" rx="18" fill="var(--cc-bg)"/>
            <rect x="28" y="24" width="300" height="210" rx="12" fill="var(--cc-surface)" stroke="var(--cc-border)"/>
            <rect x="52" y="56" width="252" height="16" rx="8" fill="rgba(var(--bs-primary-rgb), 0.2)"/>
            <rect x="52" y="84" width="180" height="12" rx="6" fill="rgba(var(--bs-primary-rgb), 0.2)"/>
            <rect x="52" y="108" width="220" height="12" rx="6" fill="rgba(var(--bs-primary-rgb), 0.2)"/>
            <rect x="52" y="156" width="120" height="32" rx="8" fill="var(--cc-primary)"/>
            <rect x="360" y="24" width="312" height="210" rx="12" fill="var(--cc-surface)" stroke="var(--cc-border)"/>
            <circle cx="516" cy="100" r="48" fill="rgba(var(--bs-danger-rgb), 0.1)"/>
            <path d="M488 112 l24 24 l40 -56" stroke="var(--cc-accent)" stroke-width="10" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            <rect x="420" y="156" width="180" height="12" rx="6" fill="rgba(var(--bs-primary-rgb), 0.1)"/>
            <rect x="420" y="176" width="160" height="12" rx="6" fill="rgba(var(--bs-primary-rgb), 0.1)"/>
          </svg>
          <div class="mt-3 small text-secondary">No downloads required • Web-based • Works on desktop & mobile</div>
        </div>
      </div>
    </div>
  </div>
</header>

{{-- FEATURES --}}
<section id="features" class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">A modern learning platform</h2>
      <p class="text-secondary mb-0">Designed for engagement and effective learning.</p>
    </div>
    <div class="row g-4 text-center">
      <div class="col-md-4">
        <div class="feature-icon-wrap mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16"><path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM5 11.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/></svg>
        </div>
        <h5 class="fw-semibold">Interactive Learning</h5>
        <p class="text-secondary">Engage with concise, easy-to-digest modules that respect your time and keep you focused.</p>
      </div>
      <div class="col-md-4">
        <div class="feature-icon-wrap mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16"><path d="M8.211 6.148a.5.5 0 0 0-.422 0l-3.32 1.66A.5.5 0 0 0 4 8.216V14.5a.5.5 0 0 0 .789.407l3.32-1.66a.5.5 0 0 0 .422 0l3.32 1.66A.5.5 0 0 0 12 14.5V8.216a.5.5 0 0 0-.789-.408l-3.32-1.66zM5 8.618l2.875-1.438L10.75 8.618 8 10.057 5 8.618z"/><path d="M14 4.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0 0 1h11a.5.5 0 0 0 .5-.5zM2 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11A.5.5 0 0 1 2 3zm12 3a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0 0 1h11a.5.5 0 0 0 .5-.5z"/></svg>
        </div>
        <h5 class="fw-semibold">Adaptive Quizzes</h5>
        <p class="text-secondary">Test your knowledge with dynamic quizzes that provide instant feedback to reinforce learning.</p>
      </div>
      <div class="col-md-4">
        <div class="feature-icon-wrap mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16"><path d="M8 11.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2a.5.5 0 0 1 .5-.5z"/><path d="M2.5 8a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 1 .5-.5zm2 1.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2-3a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5zm2 2.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 1 .5-.5zm2-5a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 1 .5-.5zM1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zM1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9z"/></svg>
        </div>
        <h5 class="fw-semibold">Progress & Certificates</h5>
        <p class="text-secondary">Track your progress and earn shareable certificates to showcase your achievements.</p>
      </div>
    </div>
  </div>
</section>


{{-- FOOTER --}}
<footer class="py-4 footer-dark">
  <div class="container d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3">
    <div class="text-secondary">&copy; <span id="year"></span> CyberCore. All rights reserved.</div>
    <div class="d-flex gap-3">
      <a class="footer-link" href="#">Privacy</a>
      <a class="footer-link" href="#">Terms</a>
      <a class="footer-link" href="#features">Features</a>
    </div>
  </div>
</footer>

{{-- Bootstrap JS bundle --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Custom JS --}}
<script src="/assets/js/cybercore.js"></script>
<script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
