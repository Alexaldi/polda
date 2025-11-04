<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $service)
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('pages.profile.edit', [
            'title' => 'Profile',
            'user' => auth()->user(),
            'institutions' => $this->service->getInstitutions(),
            'divisions' => $this->service->getDivisions(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($request->user()->id)],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($request->user()->id)],
            'institution_id' => ['nullable', 'integer', 'exists:institutions,id'],
            'division_id' => ['nullable', 'integer', 'exists:divisions,id'],
        ]);

        DB::beginTransaction();
        try {
            $this->service->updateProfile($request->user()->id, $data);
            DB::commit();
            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        DB::beginTransaction();
        try {
            $this->service->updatePassword($request->user()->id, $data['password']);
            DB::commit();
            return back()->with('success', 'Password berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Gagal mengubah password.');
        }
    }
}
