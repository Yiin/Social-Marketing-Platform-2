<?php

namespace App\Controllers;

use App\Http\Requests\User\UpdateUser;
use App\Models\User;
use App\Http\Requests\User\ChangeUserPassword;

class UserController extends Controller
{
    public function update(UpdateUser $request, User $user)
    {
        $user->update($request->only([
            'name',
            'email'
        ]));

        flash('Profile updated!');

        return redirect()->back();
    }

    public function changePassword(ChangeUserPassword $request, User $user)
    {
        $user->password = bcrypt($request->get('new_password'));
        $user->save();

        flash('Password changed!');

        return redirect()->back();
    }
}
