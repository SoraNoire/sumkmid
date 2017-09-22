<?php

namespace Modules\Mentor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\User;
use Validator;

class MentorController extends Controller
{
    
    function __construct()
    {
        $this->middleware(['auth', 'clearance'])->except('frontIndex', 'frontView');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function index()
    {
        $mentors = User::role('mentor')->get();

        return view('mentor::mentors.index')->with('mentors',$mentors);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('mentor::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
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
        $mentor = User::role('mentor')->where('id',$id)->first(); // get user with given id
        
        return view('mentor::mentors.edit', compact('mentor')); //pass user and roles data to view
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $user = User::where('id',$id)->first(); //Get role specified by id
        $desc = json_decode($user->description) ?? new \stdClass;

        //Validate name, email and password fields  
        $data = [
                    'name' => $request->name,
                    'email' => $request->email,
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

        if (!\Auth::user()->hasPermissionTo('administration'))
        {
            if (\Auth::user()->hasPermissionTo('moderation'))
            {
                $user->fill($data)->save();
                return back()->with('flash_message',
             'Updated Successfully.');
            }    
        }

        $user->fill($data)->save();
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
    public function frontEdit()
    {

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
