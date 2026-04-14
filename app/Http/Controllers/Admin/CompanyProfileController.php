<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyProfileController extends Controller
{
    // tampilProfil() : CompanyProfile / getProfil() : CompanyProfile
    public function index()
    {
        $profile = CompanyProfile::first();
        return view('admin.company-profile', compact('profile'));
    }

    // updateProfil() : void
    public function updateProfil(Request $request)
    {
        $request->validate([
            'about'   => 'nullable|string',
            'vision'  => 'nullable|string',
            'mission' => 'nullable|string',
            'history' => 'nullable|string',
        ]);

        $profile = CompanyProfile::firstOrCreate([], []);

        $profile->update([
            'about'      => $request->about,
            'vision'     => $request->vision,
            'mission'    => $request->mission,
            'history'    => $request->history,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Profil perusahaan berhasil diperbarui.');
    }

    // updateKontak() : void
    public function updateKontak(Request $request)
    {
        $request->validate([
            'address' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
        ]);

        $profile = CompanyProfile::firstOrCreate([], []);

        $profile->update([
            'address'    => $request->address,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Kontak perusahaan berhasil diperbarui.');
    }
}
