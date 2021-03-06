<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserCTRL extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin');
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'type' => ['required'],
        ]);
        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => $request['photo'],
        ]);
    }

    public function UpdateProfile(Request $request){
        $user = auth('api')->user();
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['sometimes', 'min:6'],
        ]);

        $currentUser = $user->photo;

        if ($request->photo != $currentUser){
            $name = time().'.' . explode('/' , explode(':' ,
                    substr($request->photo, 0, strpos($request->photo , ';')))[1])[1];
            \Intervention\Image\Facades\Image::make($request->photo)->save(
                public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);

            $userPhoto = public_path('img/profile/'. $currentUser);
            //$move = move_uploaded_file($currentUser , 'img/profile/del/');
//            return 'userPhoto: ' . $userPhoto . ' - currentUser: ' .
//            $currentUser . ' - request : ' . $request->photo .
//            ' - name' . $name;
            //move_uploaded_file($currentUser , 'img/profile/del/');

            if (file_exists($userPhoto)){
                copy('img/profile/'.$currentUser, 'img/profile/del/'.$currentUser);
                @unlink($userPhoto);
//                return 'userPhoto: ' . $userPhoto . ' - currentUser: ' .
//                    $currentUser . ' - request : ' . $request->photo .
//                    ' - name' . $name;

            }
        }


        if (!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }


        $user->update($request->all());
        return ['msg' => 'success'];
    }

    public function profile(){
        return auth('api')->user();
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['sometimes', 'min:6'],
        ]);

        $user->update($request->all());
        return ['message' => 'User updated'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return ['message' => 'User Deleted'];
    }
}
