<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\PetResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Gender;
use App\Models\Address;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
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

            $user->save();
            return response()->json(['user' => new UserResource($user)], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating user', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return void
     * @throws Throwable
     */
    public function destroy(User $user)
    {
        $user->deleteOrFail();
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

            return response()
                ->json(['message' => 'Logged in', 'user' => new UserResource($user)], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request) {
        $user = Auth::user();
        return response()
            ->json(['message' => 'Logged out'], 200);
    }
}
