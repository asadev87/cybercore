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
        <li class="nav-item"><a class="nav-link" href="#how">How it works</a></li>
        <li class="nav-item"><a class="nav-link" href="#topics">Topics</a></li>
        <li class="nav-item"><a class="nav-link" href="#security">Security</a></li>

        @auth
          @can('view-reports') {{-- Assuming a Gate for admin dashboard access --}}
            <li class="nav-item ms-lg-2">
              <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-accent">Admin</a>
            </li>
          @endcan
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
          Learn to spot threats. <span>Stay safe online.</span>
        </h1>
        <p class="lead hero-sub mt-3">
          CyberCore is a secure, user-friendly e-learning platform designed to raise cybersecurity awareness
          for students, educators, and non-technical users through interactive modules and quizzes.
        </p>
        <div class="d-flex gap-3 mt-4">
          <a class="btn btn-primary btn-lg" href="{{ route('register') }}">Start learning</a>
          <a class="btn btn-outline-primary btn-lg" href="#topics">Browse topics</a>
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
        <div class="p-4 p-lg-5 border rounded-4 shadow-sm bg-surface">
          <svg role="img" aria-label="Illustration of secure learning" width="100%" height="260" viewBox="0 0 700 260" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="0" width="700" height="260" rx="18" fill="var(--cc-surface)"/>
            <rect x="28" y="24" width="300" height="210" rx="12" fill="#ffffff" stroke="var(--cc-border)"/>
            <rect x="52" y="56" width="252" height="16" rx="8" fill="var(--cc-border)"/>
            <rect x="52" y="84" width="180" height="12" rx="6" fill="var(--cc-accent)"/>
            <rect x="52" y="108" width="220" height="12" rx="6" fill="var(--cc-accent)"/>
            <rect x="52" y="156" width="120" height="32" rx="8" fill="var(--cc-primary)"/>
            <rect x="360" y="24" width="312" height="210" rx="12" fill="#ffffff" stroke="var(--cc-border)"/>
            <circle cx="516" cy="100" r="48" fill="var(--cc-primary)" opacity=".12"/>
            <path d="M488 112 l24 24 l40 -56" stroke="var(--cc-primary)" stroke-width="10" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            <rect x="420" y="156" width="180" height="12" rx="6" fill="var(--cc-border)"/>
            <rect x="420" y="176" width="160" height="12" rx="6" fill="var(--cc-border)"/>
          </svg>
          <div class="mt-3 small text-secondary">No downloads required • Web-based • Works on desktop & mobile</div>
        </div>
      </div>
    </div>
  </div>
</header>

{{-- HOW IT WORKS --}}
<section id="how" class="py-5 bg-surface">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="fw-bold">How CyberCore works</h2>
      <p class="text-secondary mb-0">Learn → Take quizzes → Earn certificates</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4"><div class="card h-100 module-card"><div class="card-body">
        <h5 class="card-title">1. Learn</h5>
        <p class="text-secondary mb-0">Short modules that respect your time.</p>
      </div></div></div>
      <div class="col-md-4"><div class="card h-100 module-card"><div class="card-body">
        <h5 class="card-title">2. Quiz</h5>
        <p class="text-secondary mb-0">Adaptive questions with instant feedback.</p>
      </div></div></div>
      <div class="col-md-4"><div class="card h-100 module-card"><div class="card-body">
        <h5 class="card-title">3. Certificate</h5>
        <p class="text-secondary mb-0">View-only certificate upon passing.</p>
      </div></div></div>
    </div>
  </div>
</section>

{{-- TOPICS (Modules) --}}
<section id="topics" class="py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="fw-bold mb-0">Core topics</h2>
        <p class="text-secondary mb-0">Start with these essentials—designed for all learners.</p>
      </div>
      <a class="btn btn-outline-primary" href="{{ route('register') }}">Enroll free</a>
    </div>

    @php($modules = \App\Models\Module::where('is_active',true)->orderBy('title')->limit(4)->get())
    <div class="row g-4">
      @forelse($modules as $m)
        <div class="col-md-6 col-lg-3">
          <div class="card h-100 module-card">
            <div class="card-body">
              <h5 class="card-title">{{ $m->title }}</h5>
              <p class="card-text text-secondary">{{ \Illuminate\Support\Str::limit($m->description, 90) }}</p>
              @auth
                <a href="{{ route('learn.start',$m) }}" class="btn btn-sm btn-primary">Begin</a>
              @else
                <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Begin</a>
              @endauth
            </div>
          </div>
        </div>
      @empty
        {{-- Fallback tiles if no DB modules yet --}}
        <div class="col-12">
          <div class="alert alert-info mb-0">Modules will appear here once published by Admin.</div>
        </div>
      @endforelse
    </div>
  </div>
</section>

{{-- SECURITY --}}
<section id="security" class="py-5">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-6">
        <h2 class="fw-bold"></h2>
        <ul class="text-secondary mb-0">
        </ul>
      </div>
      <div class="col-lg-6">
        <div class="cta rounded-4 p-4 p-lg-5">
          <h5 class="mb-3">Learners first, always.</h5>
          <p class="text-secondary mb-4">We combine engaging content with learning science so anyone can develop real-world cyber safety skills.</p>
          <a href="{{ route('register') }}" class="btn btn-danger">Create your free account</a>
        </div>
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
      <a class="footer-link" href="#how">How</a>
      <a class="footer-link" href="#topics">Topics</a>
      <a class="footer-link" href="#security">Security</a>
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