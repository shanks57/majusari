<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $employees = User::orderBy('updated_at', 'desc')->get();
        $title = 'Pegawai';
        return view('pages.master-employees', compact('employees', 'title'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users',
            'phone' => 'required|string|regex:/^[0-9]{10,13}$/',
            'debt_receipt' => 'required|numeric|min:0',
            'wages' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'status' => 'required',
        ],
        [
            'email.unique' => 'Email sudah digunakan. Silakan pilih email lain.',
            'username.unique' => 'Username sudah digunakan. Silakan pilih username lain.',
            'phone.regex' => 'Nomor HP harus terdiri dari 10 hingga 13 digit tanpa karakter non-numerik.',
        ]);

        try {
            $employee = new User();
            $employee->id = (string) Str::uuid();
            $employee->name = $request->input('name');
            $employee->email = $request->input('email');
            $employee->username = $request->input('username');
            $employee->phone = $request->input('phone');
            $employee->debt_receipt = $request->input('debt_receipt');
            $employee->wages = $request->input('wages');
            $employee->address = $request->input('address');
            $employee->status = $request->input('status');
            
            $employee->assignRole('admin');
            
            $employee->save();
            session()->flash('success', 'Berhasil Menambah Pegawai Baru');
            return redirect()->route('master.employees');
           
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'username' => 'required|string|max:255|unique:users,username,'. $id,
            'phone' => 'required|string|regex:/^[0-9]{10,13}$/',
            'wages' => 'required|numeric|min:0',
            'debt_receipt' => 'required|numeric|min:0',
            'address' => 'required|string',
            'status' => 'required|boolean',
        ],
        [
            'email.unique' => 'Email sudah digunakan. Silakan pilih email lain.',
            'username.unique' => 'Username sudah digunakan. Silakan pilih username lain.',
            'phone.regex' => 'Nomor HP harus terdiri dari 10 hingga 13 digit tanpa karakter non-numerik.',
        ]);

        try {
            $employee = User::findOrFail($id);
            
            $employee->name = $request->input('name');
            $employee->email = $request->input('email');
            $employee->username = $request->input('username');
            $employee->phone = $request->input('phone');
            $employee->debt_receipt = $request->input('debt_receipt');
            $employee->wages = $request->input('wages');
            $employee->address = $request->input('address');
            $employee->status = $request->input('status') ? 1 : 0;

            $employee->save();
            
            session()->flash('success-edit', 'Berhasil Memperbarui Data Pegawai.');
            return redirect()->route('master.employees');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('master.employees')->withErrors(['error' => 'Data Pegawai tidak ditemukan.']);
        } catch (\Exception $e) {
            return redirect()->route('master.brands')->withErrors(['error' => 'Data Merek Barang tidak ditemukan.']);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.']);
        }
    }

    public function setPassword(Request $request, $id)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
                'status' => 'required|boolean',
            ]);

            $employee = User::findOrFail($id);

            $employee->password = Hash::make($request->input('password'));
            $employee->status = $request->input('status') ? 1 : 0;
            $employee->save();

            return redirect()->route('master.employees')->with('success-edit', 'Password pegawai berhasil diperbarui');
            
        } catch (ModelNotFoundException $e) {
            return redirect()->route('master.employees')->with('error', 'Pegawai tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            return redirect()->route('master.employees')->with('error', 'Terjadi kesalahan saat memperbarui password.');
        }
    }

    public function updateProfile(Request $request, $id)
    {
        // Validasi data yang dikirim dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|digits_between:10,15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($id);

        // Perbarui data pengguna
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diperbarui!');
    }
}
