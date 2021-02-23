<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use DateTime;
use DB;
use URL;
use Auth;
use Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function list(){
        $title = trans('app.inspektors');
        $users = DB::table('users')->
        select(
            'users.*',
            'regions.name as region_name',
            'cities.name as city_name'
        )->
        join('regions', 'regions.id', '=', 'users.region_id')->
        join('cities', 'cities.id', '=', 'users.city_id')->
        get()->toArray();
        return view('inspektor.list', compact('title', 'users'));
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'middlename' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }
    public function add(Request $request, $id = null){
    	if($request->post()){
    		$validate = validator((array) $request);
    		if($validate){
    			if(!empty($request->hasFile('image'))){
	    			$image = $request->file('image');
			        $filepath = public_path().'/users/';
			        $filename = $request->file('image')->getClientOriginalName();
			        $image->move($filepath, $filename);
			        $image = $filename;
	    		}else{
	    			$image = null;
	    		}
		    	$data = new User;
	            $data->firstname = $request['firstname'];
	            $data->lastname = $request['lastname'];
	            $data->middlename = $request['middlename'];
	            $data->email = $request['email'];
	            $data->password = Hash::make($request['password']);
	            $data->region_id = $request['region'];
	            $data->city_id = $request['city'];
	            $data->language = $request['language'];
	            $data->phone = $request['phone'];
	            $data->role_id = $request['role'];
	            $data->address = $request['address'];
	            $data->photo = $image;
	            $data->save();
	    		return redirect('/user/list');
    		}
    		
    	}else{
    		if(!empty($id)){

                $title = trans('app.edit user account');
    			$user = DB::table('users')->where('id', '=', $id)->get()->first();
    			$cities = DB::table('cities')->where('region_id', '=', $user->region_id)->get()->toArray();
    		}else{
                $title = trans('app.add inspektors');
                $user = null;
                $auth = Auth::user();
    			$cities = DB::table('cities')->where('region_id', '=', $auth->region_id)->get()->toArray();
            }
            $roles = DB::table('roles')->where('status', '=', 'active')->get()->toArray();
    		$regions = DB::table('regions')->get()->toArray();
    		return view('inspektor.add', compact('title', 'regions', 'user', 'cities', 'roles'));
    	}
    }
    public function update(Request $request, $id){
        $title = trans('app.updated inspektors');
        if(!empty($request->hasFile('image'))){
            $image = $request->file('image');
            $filepath = public_path().'/users/';
            $filename = $request->file('image')->getClientOriginalName();
            $image->move($filepath, $filename);
            $image = $filename;
        }else{
            $image = null;
        }
        $data = User::find($id);
        $data->firstname = $request['firstname'];
        $data->lastname = $request['lastname'];
        $data->middlename = $request['middlename'];
        $data->email = $request['email'];
        if(!empty($request['password'])){
            $data->password = Hash::make($request['password']);
        }
        $data->region_id = $request['region'];
        $data->city_id = $request['city'];
        $data->language = $request['language'];
        $data->phone = $request['phone'];
        if(!empty($request['role'])){
            $data->role_id = $request['role'];
        }
        $data->address = $request['address'];
        if(!empty($request['image'])){
            $data->photo = $image;
        }
        
        $data->save();
        return redirect('/user/list')->with('title', $title);
        
    }
}
