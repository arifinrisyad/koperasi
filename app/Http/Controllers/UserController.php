<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->get();

        if ($request->ajax()) {
            return response()->json([
                'users' => $users
            ]);
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,petugas',
            ], [
                'name.required' => 'Nama harus diisi',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'role.required' => 'Role harus dipilih',
                'role.in' => 'Role tidak valid'
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_active' => true
            ]);

            // Log aktivitas create user
            ActivityLogService::logCreate(auth()->user(), "Membuat pengguna baru: {$user->name}");

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengguna berhasil ditambahkan',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'is_active' => $user->is_active
                    ]
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil ditambahkan');
                
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menambah pengguna'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambah pengguna')
                ->withInput();
        }
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,pemasok,petugas',
        ]);

        $oldData = $user->only(['name', 'email', 'role']);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Log aktivitas update user
        $changes = [];
        foreach ($oldData as $field => $value) {
            if ($value !== $user->$field) {
                $changes[] = "$field: $value -> {$user->$field}";
            }
        }
        if (!empty($changes)) {
            $changeText = implode(', ', $changes);
            ActivityLogService::logUpdate(auth()->user(), "Mengubah data pengguna {$user->name}: $changeText");
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Mencegah penghapusan diri sendiri
            if ($user->id === auth()->id()) {
                return response()->json([
                    'message' => 'Anda tidak dapat menghapus akun Anda sendiri'
                ], 403);
            }

            // Mencegah penghapusan user terakhir dengan role admin
            $adminCount = User::where('role', 'admin')->count();
            if ($user->role === 'admin' && $adminCount <= 1) {
                return response()->json([
                    'message' => 'Tidak dapat menghapus admin terakhir'
                ], 403);
            }

            // Log aktivitas sebelum menghapus
            ActivityLogService::logDelete($user, "Menghapus pengguna {$user->name}");

            // Hapus user
            $user->delete();

            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Pengguna berhasil dihapus'
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Terjadi kesalahan saat menghapus pengguna'
                ], 500);
            }

            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat menghapus pengguna');
        }
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah',
            'is_active' => $user->is_active
        ]);
    }

    public function getOnlineStatus()
    {
        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'role' => $user->role,
                'is_online' => $user->isOnline(),
                'last_seen' => $user->last_seen,
                'last_seen_human' => $user->last_seen ? $user->last_seen->diffForHumans() : null
            ];
        });

        return response()->json(['users' => $users]);
    }

    public function checkStatus()
    {
        if (!auth()->check()) {
            return response()->json(['logout' => true]);
        }

        return response()->json([
            'is_active' => auth()->user()->is_active
        ]);
    }
}
