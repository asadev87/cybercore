<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Stream the certificate PDF inline.
     */
    public function show(Certificate $certificate)
    {
        abort_unless($certificate->user_id === Auth::id(), 403);

        $path = $certificate->pdf_path;

        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'Certificate file not found.');
        }

        $absolutePath = Storage::disk('local')->path($path);

        return response()->file($absolutePath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($absolutePath).'"',
        ]);
    }

    /**
     * Show the embed (view-only) version of a certificate.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\View\View
     */
    public function embed(Certificate $certificate)
    {
        // Only the owner may view
        abort_unless($certificate->user_id === Auth::id(), 403);

        return view('certificates.embed', compact('certificate'));
    }

    /**
     * Optionally, you could add another method to stream or download the certificate
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function stream(Certificate $certificate)
    {
        abort_unless($certificate->user_id === Auth::id(), 403);

        // Example: if you store a PDF file path in certificate model
        // Adjust according to how you’ve saved it
        $filePath = storage_path('certificates/' . $certificate->code . '.pdf');
        if (! file_exists($filePath)) {
            abort(404, 'Certificate file not found.');
        }

        return response()->file($filePath);
    }
}
