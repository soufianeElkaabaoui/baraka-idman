<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Admin;
use App\Models\Intervenant;
use App\Models\MedicalAssistant;
use App\Models\SocialAssistant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct() {
        $this->authorizeResource(User::class, 'user');
    }

    public function login(LoginRequest $request){
        if (Auth::check()) {
            return redirect()->back();
        }
        if (Auth::attempt($request->only(['email', 'password']))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->admin) {
                return $user;
            }elseif ($user->medical_assistant) {
                return $user;
            }elseif ($user->social_assistant) {
                return $user;
            }elseif ($user->intervenant) {
                return redirect('/new-user-form');
            }
        }else {
            $result = 'Email ou(et) mot de passe no valid';
            $status = 403;
            return response()->json($result, $status);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->cin = $request->cin;
        $user->phone_number = $request->phone_number;
        $user->birthday_date = $request->birthday_date;
        $user->email = $request->email;
        $user->password = Hash::make('demo0000');
        if ($user->save()) {
            if ($request->role == 'admin') {
                $admin = new Admin;
                if (($result = $admin->user()->associate($user)) && $admin->save()) {
                    $status = 200;
                } else {
                    $result = 'probleme au serveur.';
                    $status = 500;
                }
            }elseif ($request->role == 'social assistant') {
                $socialAssistant = new SocialAssistant;
                if (($result = $socialAssistant->user()->associate($user)) && $socialAssistant->save()) {
                    $status = 200;
                } else {
                    $result = 'probleme au serveur.';
                    $status = 500;
                }
            }elseif ($request->role == 'medical assistant') {
                $medicalAssistant = new MedicalAssistant;
                if (($result = $medicalAssistant->user()->associate($user)) && $medicalAssistant->save()) {
                    $status = 200;
                } else {
                    $result = 'probleme au serveur.';
                    $status = 500;
                }
            }elseif ($request->role == 'intervenant') {
                $intervenant = new Intervenant;
                if (($result = $intervenant->user()->associate($user)) && $intervenant->save()) {
                    $status = 200;
                } else {
                    $result = 'probleme au serveur.';
                    $status = 500;
                }
            }
        } else {
            $result = 'probleme au serveur.';
            $status = 500;
        }
        
        return response()->json($result, $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->cin = $request->cin;
        $user->phone_number = $request->phone_number;
        $user->birthday_date = $request->birthday_date;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        // get the role of the user .. 
        if ($user->admin != null) {
            $role = 'admin';
        }elseif ($user->intervenant != null) {
            $role = 'intervenant';
        }elseif ($user->social_assistant != null) {
            $role = 'social assistant';
        }elseif ($user->medical_assistant != null) {
            $role = 'medical assistant';
        }
        // check if the role was modified ..
        if ($request->role != $role) {
            // delete the old role first ..
            if ($role == 'admin') {
                $user->admin()->delete();
            } elseif ($role == 'intervenant') {
                $user->intervenant()->delete();
            } elseif ($role == 'social assistant') {
                $user->social_assistant()->delete();
            } elseif ($role == 'medical assistant') {
                $user->medical_assistant()->delete();
            }
            // give him new role ..
            if ($request->role == 'admin') {
                $admin = new Admin;
                if (($result = $admin->user()->associate($user)) && $admin->save()) {
                    $status = 200;
                } else {
                    $result = 'probleme au serveur.';
                    $status = 500;
                }
            }elseif ($request->role == 'social assistant') {
                $socialAssistant = new SocialAssistant;
                if (($result = $socialAssistant->user()->associate($user)) && $socialAssistant->save()) {
                    $status = 200;
                } else {
                    $result = 'probleme au serveur.';
                    $status = 500;
                }
            }elseif ($request->role == 'medical assistant') {
                $medicalAssistant = new MedicalAssistant;
                if (($result = $medicalAssistant->user()->associate($user)) && $medicalAssistant->save()) {
                    $status = 200;
                } else {
                    $result = 'probleme au serveur.';
                    $status = 500;
                }
            }elseif ($request->role == 'intervenant') {
                $intervenant = new Intervenant;
                if (($result = $intervenant->user()->associate($user)) && $intervenant->save()) {
                    $status = 200;
                } else {
                    $result = 'probleme au serveur.';
                    $status = 500;
                }
            }
            // update the user with new data ..
            if ($user->update()) {
                $result = $user;
                $status = 200;
            } else {
                $result = 'probleme au serveur.';
                $status = 500;
            }
        }else {
            // just update the data of the user without changing his role ..
            if ($user->update()) {
                $result = $user;
                $status = 200;
            } else {
                $result = 'probleme au serveur.';
                $status = 500;
            }
        }
        return response()->json($result, $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user != null) {
            if ($user->delete()) {
                $result = $user;
                $status = 200;
            } else {
                $result = 'probleme au serveur.';
                $status = 500;
            }
        } else {
            $result = "l'utilisateur n'existe pas.";
            $status = 404;
        }
        
        return response()->json($result, $status);
    }
    
}
