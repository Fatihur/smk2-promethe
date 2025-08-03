<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        $user->load('jurusan'); // Load jurusan relationship if exists
        
        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        $user->load('jurusan');
        
        // Get jurusan list for ketua_jurusan role
        $jurusan = null;
        if ($user->role === 'ketua_jurusan') {
            $jurusan = \App\Models\Jurusan::active()->orderBy('nama_jurusan')->get();
        }
        
        return view('profile.edit', compact('user', 'jurusan'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'jurusan_id' => 'nullable|exists:jurusan,id',
        ]);

        // Validate jurusan_id is required for ketua_jurusan role
        if ($user->role === 'ketua_jurusan' && !$request->jurusan_id) {
            return back()->withErrors([
                'jurusan_id' => 'Jurusan harus dipilih untuk role Ketua Jurusan.'
            ])->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ];

        // Only update jurusan_id for ketua_jurusan role
        if ($user->role === 'ketua_jurusan') {
            $updateData['jurusan_id'] = $request->jurusan_id;
        }

        $user->update($updateData);

        return redirect()->route('profile.show')
                        ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')
                        ->with('success', 'Password berhasil diperbarui.');
    }
}
