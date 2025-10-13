{{-- resources/views/certificates/template.blade.php --}}

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    @page { size: A4 landscape; margin: 32px; }
    html, body { font-family: DejaVu Sans, sans-serif; color:#111; background:#ffffff !important; }
    body { margin:0; }
    .wrap { border:6px solid #8b0000; border-radius:16px; padding:32px; background:#ffffff; position:relative; min-height:500px; }
    .title { font-size: 28px; letter-spacing: .06em; color:#8b0000; margin-bottom: 8px; }
    .big { font-size: 42px; font-weight: 700; margin: 8px 0 2px; }
    .muted { color:#6b7280; }
    .row { display:flex; justify-content: space-between; margin-top: 24px; }
    .sig { margin-top:28px; border-top:1px solid #ddd; padding-top:6px; }
    .badge { padding:6px 10px; border:1px solid #ddd; border-radius:8px; font-size:12px; }
    .logo { text-align:center; margin-bottom: 24px; }
    .logo img { max-height: 80px; }
    .signature { margin-top: 40px; display:flex; justify-content:flex-end; align-items:center; gap:16px; }
    .signature img { max-height: 70px; }
    .signature .caption { text-align:right; font-size:12px; text-transform:uppercase; letter-spacing:.18em; }
  </style>
</head>
<body>
  <div class="wrap">
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
    <div class="title">Certificate of Completion</div>
    <div class="big">{{ $user->name }}</div>
    <div class="muted">has successfully completed</div>
    <h2 style="margin:8px 0 0">{{ $module->title }}</h2>
    <div class="muted">Score: {{ $score }}% &nbsp;•&nbsp; Date: {{ $issued_at->format('Y-m-d') }}</div>

    <div class="row">
      <div>
        <div class="sig">Training Lead</div>
      </div>
      <div class="badge">Cert No: {{ $serial }}</div>
    </div>

    <div class="signature">
      @if(file_exists($signaturePath))
        <img src="{{ $signaturePath }}" alt="Authorized Signature">
      @else
        <span style="font-size:14px;">Authorized Signature</span>
      @endif
      <div class="caption">
        Authorized Signatory<br>
        CyberCore Security Office
      </div>
    </div>
  </div>
</body>
</html>
