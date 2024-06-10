<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\PetResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Gender;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => UserResource::collection($users)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return response()->json(['user' => new UserResource($user)], 200);
    }

    /**
     * Display the pets of the specified resource.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function showPets()
    {
        $pets = Auth::user()->pets();
        return response()->json(['pets' => PetResource::collection($pets)], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        try{
            $user = new User();
            $user->fill($request->only(['name', 'surname', 'birth_date', 'dni', 'phone', 'email']));
            $user->password = Hash::make($request->password);

            $gender = Gender::find($request->gender_id);
            $address = Address::find($request->address_id);

            if (!$gender || !$address) {
                return response()->json(['message' => 'Invalid gender or address provided'], 400);
            }

            $user->gender()->associate($gender);
            $user->address()->associate($address);
            $user->assignRole('owner');

            $user->save();
            return response()->json(['user' => new UserResource($user)], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating user', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     *
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $user->fill($request->validated());
            $user->save();
            return response()->json(['user' => new UserResource($user)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating user', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['message' => 'User deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting user', 'error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($validatedData)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response()
                ->json(['message' => 'Logged in', 'user' => new UserResource($user)], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request) {
        Auth::user()->tokens()->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()
            ->json(['message' => 'Logged out'], 200);
    }

    /**
     * Search for a user or pet by name or dni.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function search($query = null)
    {
        if(!$query) {
            $users = User::all();
            return response()->json(['users' => UserResource::collection($users)], 200);
        } else {
            $users = User::where('name', 'like', "%$query%")
                ->orWhere('surname', 'like', "%$query%")
                ->orWhere('dni', 'like', "%$query%")
                ->get();
        }
        return response()->json(['users' => UserResource::collection($users)], 200);
    }

    public function modifyRole(Request $request, User $user)
    {
        $user->removeRole($user->roles->first());
        $user->assignRole($request->role);
        return response()->json(['message' => 'Role modified'], 200);
    }
}
