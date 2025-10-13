<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function show(Certificate $certificate)
    {
        $this->authorizeCertificate($certificate);

        $absolutePath = $this->resolvePath($certificate);

        return response()->file($absolutePath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($absolutePath).'"',
        ]);
    }

    public function embed(Certificate $certificate)
    {
        $this->authorizeCertificate($certificate);

        return view('certificates.embed', compact('certificate'));
    }

    public function stream(Certificate $certificate)
    {
        return $this->show($certificate);
    }

    public function download(Certificate $certificate)
    {
        $this->authorizeCertificate($certificate);

        $path = $certificate->pdf_path;

        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'Certificate file not found.');
        }

        $filename = 'certificate-' . ($certificate->serial ?? $certificate->id) . '.pdf';

        return Storage::disk('local')->download($path, $filename);
    }

    protected function authorizeCertificate(Certificate $certificate): void
    {
        abort_unless($certificate->user_id === Auth::id(), 403);
    }

    protected function resolvePath(Certificate $certificate): string
    {
        $path = $certificate->pdf_path;

        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'Certificate file not found.');
        }

        return Storage::disk('local')->path($path);
    }
}
