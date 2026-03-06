<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthEmployeeController extends Controller
{
    /**
     * Handle Login Request (API)
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string', // Opsional: Untuk nama token (misal: "Samsung S21")
        ]);

        // 2. Cari User berdasarkan Email
        $user = User::where('email', $request->email)->first();

        // 3. Cek Password & Keberadaan User
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau Password salah.',
            ], 401);
        }

        // 4. Validasi Tambahan (Keamanan)
        
        // A. Cek Role (Admin tidak boleh login di aplikasi karyawan)
        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'Akun Admin tidak dapat login di aplikasi mobile.',
            ], 403);
        }

        // B. Cek Status Akun (Jika dibanned/pending)
        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'Akun Anda sedang dinonaktifkan atau menunggu persetujuan.',
            ], 403);
        }

        // C. Cek Verifikasi Email (PENTING sesuai fitur sebelumnya)
        if (! $user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email belum diverifikasi. Silakan cek inbox email Anda.',
            ], 403); // 403 Forbidden
        }

        // 5. Generate Token (Sanctum)
        // Menghapus token lama (opsional, agar 1 device 1 login) atau biarkan multi-device
        // $user->tokens()->delete(); 
        
        $deviceName = $request->device_name ?? 'Mobile Device';
        $token = $user->createToken($deviceName)->plainTextToken;

        // 6. Return Response JSON
        return response()->json([
            'message' => 'Login Berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);
    }

    /**
     * Handle Logout Request (API)
     */
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan saat ini
        $currentToken = $request->user()->currentAccessToken();
        if ($currentToken && isset($currentToken->id)) {
            // Hapus token lewat relasi untuk memastikan delete() dipanggil pada query/builder
            $request->user()->tokens()->where('id', $currentToken->id)->delete();
        }

        return response()->json([
            'message' => 'Logout Berhasil'
        ], 200);
    }
    
    /**
     * Get User Profile (Opsional, untuk cek token valid)
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}