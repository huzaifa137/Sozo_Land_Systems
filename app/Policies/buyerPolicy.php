<?php

namespace App\Policies;

use App\Models\User;
use App\Models\buyer;

class buyerPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function edit(User $user, buyer $model)
    {
        // Check if the user is authorized to edit the model
        return $user->role === 'SuperAdmin';
    }

    public function update(User $user, buyer $model)
    {
        // Check if the user is authorized to update the model
        return $user->role === 'SuperAdmin';
    }

    public function delete(User $user, buyer $model)
    {
        // Check if the user is authorized to delete the model
        return $user->role === 'SuperAdmin';
    }

}
