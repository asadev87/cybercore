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

<div class="background-grid"></div>

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
          <a class="btn btn-primary btn-lg" href="{{ route('register') }}">Start Learning</a>
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
          <svg width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13 2.05078C13 2.05078 16 6.00001 16 12C16 18 13 21.9492 13 21.9492" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11 2.05078C11 2.05078 8 6.00001 8 12C8 18 11 21.9492 11 21.9492" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path><path d="M2.62988 15.5H21.37" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path><path d="M2.62988 8.5H21.37" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        </div>
        <h5 class="fw-semibold">Interactive Learning</h5>
        <p class="text-secondary">Engage with concise, easy-to-digest modules that respect your time and keep you focused.</p>
      </div>
      <div class="col-md-4">
        <div class="feature-icon-wrap mb-3">
          <svg width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor"><path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path><path d="M16.8284 16.8284L18.2426 15.4142M18.2426 15.4142L15.4142 18.2426M18.2426 15.4142L19.6568 14M5.75736 5.75736L4.34315 7.17157M4.34315 7.17157L7.17157 4.34315M4.34315 7.17157L3 8.68629" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path><path d="M14.5 12C14.5 13.3807 13.3807 14.5 12 14.5C10.6193 14.5 9.5 13.3807 9.5 12C9.5 10.6193 10.6193 9.5 12 9.5C13.3807 9.5 14.5 10.6193 14.5 12Z" stroke="currentColor"></path></svg>
        </div>
        <h5 class="fw-semibold">Adaptive Quizzes</h5>
        <p class="text-secondary">Test your knowledge with dynamic quizzes that provide instant feedback to reinforce learning.</p>
      </div>
      <div class="col-md-4">
        <div class="feature-icon-wrap mb-3">
          <svg width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor"><path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.5 13.5L10.5 15.5L15.5 10.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path></svg>
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
