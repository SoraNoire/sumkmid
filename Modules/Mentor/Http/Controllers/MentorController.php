<?php

namespace Modules\Mentor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Helpers\SSOHelper;
use Illuminate\Routing\Route;
use Validator;

class MentorController extends Controller
{
    
    function __construct(Request $request)
    {
        $route = (null !=($request->route())) ? $request->route()->getActionName() : '';
        
        $method = explode('@',$route)[1] ?? '';
        
        $public = in_array($method, ['editMyProfile','frontIndex','updateMyProfile']);
        if( !$public )
        {
            if ( SSOHelper::Auth() )
            {
                if ('superadmin' != SSOHelper::Auth()->role) {
                    return Redirect( route('home') )->send();
                }
                return view('auth::auth.index');
            }
            return Redirect( route('auth.login') )->send();
        }
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function index()
    {
        $mentors = json_decode(SSOHelper::listMentors());

        return view('mentor::mentors.index')->with('mentors',$mentors);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function add()
    {
        return view('mentor::mentors.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = [
                    'name' => $request->name,
                    'role' => 'mentor',
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                ];
        $validator = [
                        'name'=>'required|max:120',
                        'email'=>'required',
                        'password'=>'required|confirmed',
                    ];

        $validator = Validator::make($data, $validator);
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        SSOHelper::SUAddUser($data);
        return redirect()->route('mentorm.index')
            ->with('flash_message',
             'Mentor Added.');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('mentor::mentors.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $mentor = json_decode(SSOHelper::SUGetUserDetail($id, 'id') );
        // User::role('mentor')->where('id',$id)->first(); // get user with given id
        
        return view('mentor::mentors.edit', ['mentor'=>$mentor]); //pass user and roles data to view
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $user = json_decode(SSOHelper::SUGetUserDetail($id, 'id') );
        $desc = json_decode($user->description) ?? new \stdClass;

        //Validate name, email and password fields  
        $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'id'    => $id,
                ];
        $validator = [
                        'name'=>'required|max:120',
                        'email'=>'required|email|unique:users,email,'.$id,
                    ];

        if ($request->password != '' )
        {
            $data['password'] = $request->password;
            $validator['password'] = 'required|min:6|confirmed';
        }
        $validator = Validator::make($data, $validator);
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        if( isset($data['password'])  )
            $data['password'] = bcrypt($data['password']);
        
        if( $request->description != ''  ){
            $desc->mentor = $request->description;
            $data['description'] = json_encode($desc);
        }

        if (SSOHelper::Auth()->role != 'superadmin')
        {
            if (SSOHelper::Auth()->role !='admin')
            {
                //$user->fill($data)->save();
                return back()->with('flash_message',
             'Updated Successfully.');
            }    
        }
        SSOHelper::SUUpdateUser($data);
        return redirect()->route('mentorm.index')
            ->with('flash_message',
             'User successfully edited.');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }


    /**
     * frontIndex, front end view of mentors
     *
     * @return view
     * @author 
     **/
    public function frontIndex()
    {
        $mentors = json_decode(SSOHelper::mentorList());

        return view('mentor::frontend.index')->with('mentors',$mentors);
    }

    /**
     * frontView, viewing mentor's profile 
     *
     * @return view
     * @author 
     **/
    public function frontView()
    {
    }

    /**
     * frontEdit, editing profile by logged in mentor
     *
     * @return view
     * @author 
     **/
    public function editMyProfile()
    {
        $mentor = json_decode(SSOHelper::getUserDetail())->message;
        return view('mentor::frontend.edit')->with('mentor',$mentor);
    }

    /**
     * frontUpdate, updating edits that mentor created
     *
     * @return view
     * @author 
     **/
    public function frontUpdate()
    {
    
    }
}
