{{-- resources/views/certificates/template.blade.php --}}

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    @page { size: 210mm 297mm; margin: 32px; }
    body {
      font-family: 'Plus Jakarta Sans', 'DejaVu Sans', sans-serif;
      margin: 0;
      padding: 0;
      background: radial-gradient(circle at top, rgba(191,219,254,0.6), transparent 60%), #f8fbff;
      color: #0f172a;
    }
    .wrapper {
      position: relative;
      padding: 48px 56px;
      min-height: 900px;
      border-radius: 28px;
      border: 6px solid transparent;
      background:
        linear-gradient(#ffffff, #ffffff) padding-box,
        linear-gradient(135deg, rgba(96,165,250,0.8), rgba(59,130,246,0.4)) border-box;
      box-shadow: 0 30px 80px -45px rgba(15,23,42,0.35);
      overflow: hidden;
    }
    .wrapper::before,
    .wrapper::after {
      content: "";
      position: absolute;
      inset: 24px;
      border-radius: 22px;
      background:
        linear-gradient(120deg, rgba(56,189,248,0.12) 1px, transparent 1px),
        linear-gradient(210deg, rgba(37,99,235,0.08) 1px, transparent 1px);
      background-size: 56px 56px;
      opacity: 0.35;
      pointer-events: none;
    }
    .wrapper::after {
      inset: 32px;
      border-radius: 20px;
      background:
        radial-gradient(circle at 10% 20%, rgba(96,165,250,0.25), transparent 50%),
        radial-gradient(circle at 85% 80%, rgba(14,165,233,0.2), transparent 55%);
      mix-blend-mode: screen;
      opacity: 0.65;
    }
    .content {
      position: relative;
      z-index: 1;
      display: flex;
      flex-direction: column;
      gap: 28px;
      align-items: center;
      text-align: center;
    }
    .logo img {
      max-height: 78px;
      filter: drop-shadow(0 16px 32px rgba(37,99,235,0.45));
    }
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 10px 28px;
      border-radius: 999px;
      font-size: 11px;
      letter-spacing: 0.32em;
      text-transform: uppercase;
      font-weight: 600;
      background: linear-gradient(135deg, rgba(56,189,248,0.18), rgba(59,130,246,0.12));
      color: #1d4ed8;
      border: 1px solid rgba(59,130,246,0.35);
    }
    .heading {
      display: flex;
      align-items: center;
      gap: 16px;
      font-size: 13px;
      color: #334155;
    }
    .heading .line {
      flex: 1;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(59,130,246,0.4), transparent);
    }
    .recipient-name {
      font-size: 52px;
      font-weight: 700;
      margin: 8px 0 4px;
      color: #0f172a;
    }
    .subtitle {
      font-size: 18px;
      color: #475569;
      letter-spacing: 0.18em;
      text-transform: uppercase;
    }
    .module-title {
      font-size: 30px;
      font-weight: 600;
      color: #0f172a;
      margin: 12px 0 0;
    }
    .stats {
      display: flex;
      justify-content: center;
      gap: 18px;
      font-size: 15px;
      color: #1e293b;
    }
    .stats strong {
      color: #0f172a;
      font-weight: 600;
    }
    .info-grid {
      margin-top: 12px;
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 240px));
      gap: 18px;
    }
    .info-card {
      padding: 18px 20px;
      border-radius: 18px;
      border: 1px solid rgba(148,163,184,0.35);
      background: linear-gradient(135deg, rgba(248,250,252,0.95), rgba(241,245,249,0.9));
      text-align: left;
    }
    .info-card .label {
      font-size: 11px;
      letter-spacing: 0.28em;
      color: #1d4ed8;
      text-transform: uppercase;
      font-weight: 600;
    }
    .info-card .value {
      margin-top: 8px;
      font-size: 15px;
      color: #0f172a;
      font-weight: 500;
    }
    .divider {
      margin: 28px auto 0;
      width: 70%;
      height: 2px;
      background: linear-gradient(90deg, transparent, rgba(56,189,248,0.75), transparent);
      box-shadow: 0 0 22px rgba(56,189,248,0.35);
    }
    .signature-area {
      margin-top: 36px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 14px;
      color: #0f172a;
      text-transform: uppercase;
      letter-spacing: 0.24em;
    }
    .signature-area .line {
      width: 240px;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(148,163,184,0.65), transparent);
    }
    .signature-area img {
      max-height: 82px;
      filter: drop-shadow(0 16px 32px rgba(37,99,235,0.35));
    }
    .footer {
      margin-top: 32px;
      font-size: 12px;
      color: #475569;
      letter-spacing: 0.18em;
      text-transform: uppercase;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="content">
      @php
        $logoPath = public_path('images/logo.png');
        $signaturePath = public_path('images/signature.png');
      @endphp

      <div class="logo">
        @if(file_exists($logoPath))
          <img src="{{ $logoPath }}" alt="Organization Logo">
        @else
          <span style="font-size:16px; font-weight:600;">[Organization Logo]</span>
        @endif
      </div>

      <div class="badge">Certificate of Achievement</div>

      <div class="heading">
        <span class="line"></span>
        <span>Issued {{ $issued_at->format('F j, Y') }}</span>
        <span class="line"></span>
      </div>

      <div>
        <p class="subtitle">This certifies that</p>
        <p class="recipient-name">{{ $user->name }}</p>
        <p class="subtitle" style="letter-spacing:0.12em;">has successfully completed the module</p>
      </div>

      <p class="module-title">{{ $module->title }}</p>

      <div class="stats">
        <span>Final score <strong>{{ $score }}%</strong></span>
        <span>&bull;</span>
        <span>Module duration <strong>{{ $module->questions()->count() }} questions</strong></span>
      </div>

      <div class="info-grid">
        <div class="info-card">
          <div class="label">Learner ID</div>
          <div class="value">{{ $user->email }}</div>
        </div>
        <div class="info-card">
          <div class="label">Certificate No.</div>
          <div class="value">{{ $serial }}</div>
        </div>
      </div>

      <div class="divider"></div>

      <div class="signature-area">
        @if(file_exists($signaturePath))
          <img src="{{ $signaturePath }}" alt="Executive Signature">
        @else
          <span style="font-size:14px;">[Signature]</span>
        @endif
        <div class="line"></div>
        <span>Executive Manager from CyberCore</span>
      </div>

      <div class="footer">
        CyberCore Security Office &mdash; Verified credential
      </div>
    </div>
  </div>
</body>
</html>




