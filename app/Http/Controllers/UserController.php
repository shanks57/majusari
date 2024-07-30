<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
            'username' => 'required|string|max:255',
            'phone' => 'required|string',
            'debt_receipt' => 'required|numeric|min:0',
            'wages' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'status' => 'required',
        ]);

        try {
            $employee = new User();
            $employee->id = (string) Str::uuid();
            $employee->name = $request->input('name');
            $employee->username = $request->input('username');
            $employee->phone = $request->input('phone');
            $employee->debt_receipt = $request->input('debt_receipt');
            $employee->wages = $request->input('wages');
            $employee->address = $request->input('address');
            $employee->status = $request->input('status');
            
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
            'username' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'wages' => 'required|numeric|min:0',
            'debt_receipt' => 'required|numeric|min:0',
            'address' => 'required|string',
            'status' => 'required|boolean',
        ]);

        try {
            $employee = User::findOrFail($id);
            
            $employee->name = $request->input('name');
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

    public function resetPassword($id)
    {
        // Fetch the employee by ID
        $employee = User::findOrFail($id);

        // Set the password to null
        $employee->password = null;

        // Save the changes
        $employee->save();

        // Set session variable to trigger modal
        session()->flash('reset_success', $employee->id);


        // Redirect back to the previous page
        return redirect()->back();
    }

}
