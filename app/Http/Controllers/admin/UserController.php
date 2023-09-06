<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = DB::table('roles')->get(['id', 'name']);

        $users = DB::select('SELECT a.id, a.username, a._password, b.name as role 
                             FROM users a, roles b 
                             WHERE a.role_id=b.id
                             ORDER BY a.created_at DESC
                            ');
        
        return view('admin.user.index', compact('roles', 'users'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'password' => ['required', 'string', 'max:30'],
        ]);

        $user = User::create([
            'username'  => $request->name,
            '_password' => $request->password,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id
        ]);

        return redirect()->route('users.index');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = DB::table('roles')->get(['id', 'name']);

        return view('admin.user.index', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'password' => ['required', 'string', 'max:30'],
        ]);

        $user->update([
            'username'  => $request->name,
            '_password' => $request->password,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id
        ]);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');   
    }
}
