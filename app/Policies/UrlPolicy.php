<?php

namespace App\Policies;
use App\Models\User;
use App\Models\Url;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UrlPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function delete(?User $user, Url $url)
    {
        return optional($user)->id === $url->creator_id
            ? Response::allow()
            : Response::deny('You are not allowed to delete this url');
    }

}
