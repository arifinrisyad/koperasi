<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string', 
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ]);

            // Update foto profil jika ada
            if ($request->hasFile('profile_photo')) {
                // Hapus foto lama jika ada
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                // Simpan foto baru
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $user->profile_photo = $path;
            }

            // Update data user
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            // Update password jika diisi
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            $response = [
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => ucfirst($user->role),
                    'profile_photo' => $user->profile_photo ? asset('storage/' . $user->profile_photo) : null
                ]
            ];

            if ($request->expectsJson()) {
                return response()->json($response);
            }

            return redirect()->route('profile.edit')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function deletePhoto()
    {
        try {
            $user = Auth::user();

            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
                $user->profile_photo = null;
                $user->save();

                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Foto profil berhasil dihapus!'
                    ]);
                }

                return redirect()->route('profile.edit')
                    ->with('success', 'Foto profil berhasil dihapus!');
            }

            throw new \Exception('Tidak ada foto profil untuk dihapus.');

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}