<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\User;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();
        
        foreach($usuarios as $usu){
           echo $usu->nombre_completo; 
        echo '</br>';
        }        
//        return $this->showAll($usuarios);        
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

        $reglas = [
           'name' => 'required',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:6|confirmed' 
        ];

        $this->validate($request, $reglas);

        
        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;

        $usuario = User::create($campos);

        return $this->showOne($usuario, 201);



        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, User $user)
    {

        $reglas = [
           'email' => 'email|unique:users,email,' . $user->id,
           'password' => 'min:6|confirmed', 
           'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR,
        ];

        $this->validate($request, $reglas);

        if($request->has('name')){
            $user->name = $request->name;
        }
 
        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }
        
        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }

         if($request->has('admin')){
            if(!$user->esVerificado()){
                return $this->errorResponse('Unicamente los usuarios verificadospueden cambiar su valor', 409);
            }
            $user->admin = $request->admin;
        }

        if(!$user->isDirty()){

                return $this->errorResponse('cambia un valor', 422);
        }
            
        $user->save();

            
        return $this->showOne($user);
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

        return $this->showOne($user);
            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
}
