<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //

  public function index()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function create(User $user)
    {
        return view('admin.users.create',['user'=>$user]);
    }

    public function store(Request $request,User $user)
    {
        return redirect()->route('send-sms',$request);
    }

    public function saveData(Request $request,User $user){
        $user->name = $request->name;
        $user->mobile_number = $request->mobile_number;
        $user->password = Hash::make($request->password);
        $user->save();
       return redirect()->route('home');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
                $request->validate([
            'name' => 'required|string',
            'password' => 'required|string|min:6',
            'mobile_number' => 'required|string|unique:users',
        ]);

        $user->name = $request->name;
        $user->mobile_number = $request->mobile_number;
        $user->password = Hash::make($request->password);
        $user->save();
         return redirect()->route('admin.users.index');
        return "updated";
}
public function delete(Request $request,User $user)
{
    $user->delete();
    return redirect()->route('admin.users.index');
}
}