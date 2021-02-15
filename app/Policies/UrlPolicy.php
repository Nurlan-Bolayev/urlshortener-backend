<?php

namespace App\Policies;

use App\Models\Url;
use App\Models\User;
use Composer\DependencyResolver\Request;
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

    public function delete(Request $request, Url $url)
    {
        return $request->user()->id == $url->creator_id
            ? Response::allow()
            : Response::deny('You are not allowed to delete this url');
    }

}
