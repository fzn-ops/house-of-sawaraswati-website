<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index()
    {
        $discountCodes = DiscountCode::latest()->get();
        return view('admin.discount-codes', compact('discountCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'  => 'required|string|max:50|unique:discount_codes,code',
            'type'  => 'required|in:percent,fixed',
            'value' => 'required|integer|min:1',
            'label' => 'nullable|string|max:255',
        ]);

        $data = $request->only('code', 'type', 'value', 'label');
        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = true;

        if ($data['type'] === 'percent' && $data['value'] > 100) {
            return back()->withInput()->with('error', 'Diskon persen tidak boleh lebih dari 100%.');
        }

        DiscountCode::create($data);

        return redirect()->route('admin.discount-codes')
            ->with('success', 'Kode diskon berhasil ditambahkan.');
    }

    public function update(Request $request, DiscountCode $discountCode)
    {
        $request->validate([
            'code'  => 'required|string|max:50|unique:discount_codes,code,' . $discountCode->id,
            'type'  => 'required|in:percent,fixed',
            'value' => 'required|integer|min:1',
            'label' => 'nullable|string|max:255',
        ]);

        $data = $request->only('code', 'type', 'value', 'label');
        $data['code'] = strtoupper(trim($data['code']));

        if ($data['type'] === 'percent' && $data['value'] > 100) {
            return back()->withInput()->with('error', 'Diskon persen tidak boleh lebih dari 100%.');
        }

        $discountCode->update($data);

        return redirect()->route('admin.discount-codes')
            ->with('success', 'Kode diskon berhasil diperbarui.');
    }

    public function toggleActive(DiscountCode $discountCode)
    {
        $discountCode->update(['is_active' => !$discountCode->is_active]);

        $status = $discountCode->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.discount-codes')
            ->with('success', "Kode diskon berhasil {$status}.");
    }

    public function destroy(DiscountCode $discountCode)
    {
        $discountCode->delete();

        return redirect()->route('admin.discount-codes')
            ->with('success', 'Kode diskon berhasil dihapus.');
    }

    public function getActiveCodes()
    {
        $codes = DiscountCode::where('is_active', true)->get()
            ->mapWithKeys(fn($d) => [
                $d->code => [
                    'type'  => $d->type,
                    'value' => $d->value,
                    'label' => $d->label ?? ($d->type === 'percent' ? "Diskon {$d->value}%" : "Potongan Rp" . number_format($d->value, 0, ',', '.')),
                ]
            ]);

        return response()->json($codes);
    }
}
