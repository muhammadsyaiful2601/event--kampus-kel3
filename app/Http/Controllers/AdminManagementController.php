<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\AdminActivityLogger;
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

        $newAdmin = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'admin',
        ]);

        AdminActivityLogger::log(
            'admin.created',
            'Menambahkan admin baru: "' . $newAdmin->name . '" (' . $newAdmin->email . ')',
            'User',
            $newAdmin->id
        );

        return redirect()->route('admin.admins.index')->with('success', 'Admin baru berhasil ditambahkan!');
    }

    public function destroy(User $admin)
    {
        if (Auth::id() !== $admin->id) {
            return redirect()->route('admin.admins.index')->with('error', 'Anda hanya dapat menghapus akun Anda sendiri!');
        }

        $adminName  = $admin->name;
        $adminEmail = $admin->email;
        $adminId    = $admin->id;

        AdminActivityLogger::log(
            'admin.deleted',
            'Menghapus akun admin: "' . $adminName . '" (' . $adminEmail . ')',
            'User',
            $adminId
        );

        $admin->delete();

        // Logout jika menghapus diri sendiri
        Auth::logout();
        return redirect()->route('login')->with('success', 'Akun Anda berhasil dihapus.');
    }
}