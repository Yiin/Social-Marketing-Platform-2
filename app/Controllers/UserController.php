<?php

namespace App\Controllers;

use App\Http\Requests\User\CreateOrUpdateUser;
use App\Http\Requests\User\UpdateUser;
use App\Mail\SendUserPassword;
use App\Models\User;
use App\Http\Requests\User\ChangeUserPassword;
use Auth;
use Spatie\Permission\Models\Role;

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
        $user->password = bcrypt($request->new_password);
        $user->save();

        flash('Password changed!');

        return redirect()->back();
    }

    public function index()
    {
        /**
         * @var $authUser User
         */
        $authUser = Auth::user();

        if ($authUser->hasPermissionTo(User::MANAGE_RESELLERS)) {
            $users = User::role([User::ROLE_RESELLER, User::ROLE_CLIENT])->paginate(15);
        } else if ($authUser->hasPermissionTo(User::MANAGE_CLIENTS)) {
            $users = $authUser->clients()->paginate(15);
        } else {
            return redirect()->back();
        }

        if ($authUser->hasRole(User::ROLE_ADMIN)) {
            $roles = Role::all();
        } else if ($authUser->hasRole(User::ROLE_RESELLER)) {
            $roles = Role::whereNotIn('name', [User::ROLE_ADMIN, User::ROLE_RESELLER])->get();
        } else {
            return redirect()->back();
        }

        return view('user.index', compact('users', 'roles'));
    }

    public function store(CreateOrUpdateUser $request)
    {
        $authUser = Auth::user();

        $data = [
            'password' => bcrypt($password = str_random()),
            'api_token' => str_random()
        ];

        $user = User::create(array_merge($request->all(), $data));

        if ($authUser->hasRole(User::ROLE_ADMIN)) {
            $user->assignRole(Role::find($request->get('role_id')));
        } else {
            $user->assignRole(User::ROLE_CLIENT);
        }

        flash('User created! Password: ' . $password, 'primary');

        $email = $request->get('email');

        \Mail::to($email)->send(new SendUserPassword($email, $password));

        return redirect()->route('user.index');
    }
}
