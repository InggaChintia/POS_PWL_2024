<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function showProfile()
    {
        // Mengambil user yang sedang login dari tabel m_user menggunakan UserModel
        $user = UserModel::find(Auth::user()->user_id);

        if (!$user) {
            return redirect()->route('login')->withErrors('Anda harus login terlebih dahulu.');
        }

        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => [
                'Home',
                (object) ['url' => route('profile.profil'), 'label' => 'Profile'],
                'Profil'
            ]
        ];

        // Mengirimkan 'profile' sebagai nilai $activeMenu
        return view('profile.profil', compact('user', 'breadcrumb'))->with('activeMenu', 'profile');
    }

    // Menampilkan halaman edit profil
    public function edit()
    {
        // Mengambil user yang sedang login dari tabel m_user menggunakan UserModel
        $user = UserModel::find(Auth::user()->user_id);

        $breadcrumb = (object) [
            'title' => 'Edit Profile',
            'list' => [
                (object) ['label' => 'Profile', 'url' => route('profile.edit')],
                'Edit'
            ]
        ];

        return view('profile.edit', compact('user', 'breadcrumb'))->with('activeMenu', 'profile');
    }

    // Memperbarui data profil
    public function update(Request $request)
    {
        // Mengambil user yang sedang login dari tabel m_user menggunakan UserModel
        $user = UserModel::find(Auth::user()->user_id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:m_user,username,' . $user->user_id . ',user_id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
        ]);

        // Update nama dan username
        $user->nama = $request->nama;
        $user->username = $request->username;

        // Handle upload avatar
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('uploads/avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Simpan perubahan
        $user->save();

        return redirect()->route('profile.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    // Menampilkan halaman ganti password
    public function changePassword()
    {
        $breadcrumb = (object) [
            'title' => 'Ganti Password',
            'list' => [
                (object) ['url' => route('profile.profil'), 'label' => 'Profile'],
                'Ganti Password'
            ]
        ];

        return view('profile.password', compact('breadcrumb'))->with('activeMenu', 'profile');
    }

    // Memperbarui password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Mengambil user yang sedang login dari tabel m_user menggunakan UserModel
        $user = UserModel::find(Auth::user()->user_id);

        // Log untuk debug
        Log::info('User ID: ' . $user->user_id);
        Log::info('Current Password: ' . $request->current_password);

        // Cek apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            Log::info('Old password does not match');
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        // Update password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        Log::info('Password updated successfully for User ID: ' . $user->user_id);
        return redirect()->route('profile.profil')->with('success', 'Password berhasil diubah.');
    }
}