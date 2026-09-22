<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfilController extends Controller
{
    public function index()
    {
        // Ambil user pertama sebagai simulasi profil aktif saat ini
        $user = User::first();
        return view('profil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::first();
        $request->validate([
            'nama' => 'required|string|min:3',
            'username' => 'required|string'
        ]);

        $user->update([
            'nama' => $request->nama,
            'username' => $request->username
        ]);

        return redirect()->route('profil.index')->with('success', 'Profil akun berhasil diperbarui!');
    }
}