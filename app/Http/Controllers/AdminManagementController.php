<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return view('dashboard_admin.admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin baru berhasil ditambahkan!');
    }

    public function destroy(User $admin)
    {
        // Pastikan hanya bisa menghapus diri sendiri
        if (Auth::id() !== $admin->id) {
            return redirect()->route('admin.admins.index')->with('error', 'Anda hanya dapat menghapus akun Anda sendiri!');
        }

        $admin->delete();

        // Logout jika menghapus diri sendiri
        Auth::logout();
        return redirect()->route('login')->with('success', 'Akun Anda berhasil dihapus.');
    }
}