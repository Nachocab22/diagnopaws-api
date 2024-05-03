<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pet $pet): bool
    {
        return $user->id === $pet->owner->id
            ? Response::allow()
            : Response::deny('You do not own this pet.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pet $pet): bool
    {
        return $user->id === $pet->owner->id //Añadir opcion de veterinario cuando se implemente
            ? Response::allow()
            : Response::deny('You do not own this pet.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pet $pet): bool
    {
        //
    }
}
