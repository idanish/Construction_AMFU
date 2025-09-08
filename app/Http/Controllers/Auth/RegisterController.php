<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request; // <-- ye correct import
use Spatie\Permission\Models\Role; // Spatie Role model

class RegisterController extends Controller
{
    use RegistersUsers {
        register as traitRegister; // original method alias
    }

    protected $redirectTo = '/login'; // login page

    public function register(Request $request)
    {
        // validate input
        $this->validator($request->all())->validate();

        // create user WITHOUT logging in
        $user = $this->create($request->all());

        // optional: flash message
        $request->session()->flash('success', 'Account created! Please login to continue.');

        // redirect to login page
        return redirect()->route('login');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function create()
{
    $roles = Role::all(); // Sare roles le aao
    return view('admin.users.create', compact('roles'));
}
    }
