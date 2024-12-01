<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index()
    {
        try {
            $employees = Employee::paginate(request()->all);

            return response()->json([
                $employees
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve employees',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:employees,username',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'debt_bond' => 'integer',
            'address' => 'required|string|max:255',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
             $employee = Employee::create([
                'id' => Str::uuid(),
                'username' => $request->username,
                'name' => $request->name,
                'phone' => $request->phone,
                'debt_bond' => $request->debt_bond ?? 0,
                'address' => $request->address,
                'status' => $request->status ?? true,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Employee created successfully',
                'data' => $employee
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Employee retrieved successfully',
                'data' => $employee
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:employees,username,' . $id,
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'debt_bond' => 'integer',
            'address' => 'required|string|max:255',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found'
                ], 404);
            }

            $employee->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Employee updated successfully',
                'data' => $employee
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found'
                ], 404);
            }

            $employee->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Employee deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Search customer by username, name, address, phone
    public function search(Request $request)
    {
        $query = $request->query('query');
        
        if (!$query) {
            return response()->json([
                'status' => 'error',
                'message' => 'Query parameter is required'
            ], 400);
        }

         $employees = Employee::where('username', 'LIKE', "%{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->orWhere('address', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->paginate();


        if ($employees->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No employe types found'
            ], 404);
        }

        return response()->json([
            $employees
        ]);
    }
}
