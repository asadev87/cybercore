{{-- resources/views/admin/reports/pdf.blade.php --}}

<!doctype html>
<html><head>
  <meta charset="utf-8"><title>Participant Scores</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #ccc; padding:6px; }
    th { background:#f5f5f5; }
    h3 { margin:0 0 8px; }
    .muted { color:#666; font-size:11px; }
  </style>
</head><body>
  <h3>Participant Scores</h3>
  <div class="muted">Range: {{ $from->toDateString() }} — {{ $to->toDateString() }}</div>
  <table>
    <thead><tr><th>When</th><th>User</th><th>Module</th><th>Score</th></tr></thead>
    <tbody>
      @foreach($rows as $a)
        <tr>
          <td>{{ $a->completed_at?->format('Y-m-d H:i') }}</td>
          <td>{{ optional($a->user)->name ?? optional($a->user)->email ?? 'Unknown user' }}</td>
          <td>{{ $a->module->title }}</td>
          <td>{{ $a->score }}%</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body></html>
