<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Gender;
use App\Models\Address;
use App\Models\Pet;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
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

        if ($request->has('pets')) {
            foreach ($request->pets as $petId) {
                $pet = Pet::find($petId);
                if ($pet) {
                    $user->pets()->save($pet);
                }
            }
        }

        $user->save();

        return new UserResource($user);
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

        if (!Auth::attempt($validatedData)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(['message' => 'Logged in', 'user' => Auth::user()], 200);
        //TODO: Añadir cookies con el token cuando se llegue a esa parte
    }
}
