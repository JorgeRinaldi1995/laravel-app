<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RegisteredCustomerController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'surname' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string', 'max:255'],
                'birthdate' => ['required', 'string', 'max:255'],
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
            ]);
    
            Customer::create([
                'user_id' => $user->id,
                'surname' => $request->surname,
                'gender' => $request->gender,
                'birthdate' => $request->birthdate,
            ]);
    
            event(new Registered($user));
    
            Auth::login($user);
    
            return response()->json(['message' => 'Customer registered successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            Log::error('Registration Error: '.$e->getMessage());
            return response()->json(['error' => 'An error occurred during registration. Please try again.'], 500);
        }
        
    }
}
