<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class EmployeeController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->user->employees()->paginate($request->per_page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->only('name', 'email', 'position', 'birthday', 'address', 'skills');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email',
            'position' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'skills' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $exists = $this->user->employees()
            ->where('email', $request->email)
            ->orWhere('name', $request->name)->count();
        Log::debug($exists);
        if ($exists) {
            return response()->json([
                'success' => true,
                'message' => 'Email or name duplicated'
            ], Response::HTTP_OK);
        }

        $employee = $this->user->employees()->create([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'birthday' => Carbon::parse($request->birthday)->format('Y-m-d'),
            'address' => $request->address,
            'skills' => $request->skills
        ]);

        //Product created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully',
            'data' => $employee
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
        $employee = $this->user->employees()->find($id);
    
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, employee not found.'
            ], 400);
        }
    
        return $employee;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $data = $request->only('name', 'email', 'position', 'birthday', 'address', 'skills');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email',
            'position' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'skills' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $employee = $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'birthday' => Carbon::parse($request->birthday)->format('Y-m-d'),
            'address' => $request->address,
            'skills' => $request->skills
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully',
            'data' => $employee
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully'
        ], Response::HTTP_OK);

    }
}
