<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // mostrar el listado de todos los usuarios
        $user = User::all();
        // validamos que hayan datos para mostrar
        if(count($user) > 0){
            return response()->json($user, 200);
        }
        # si no hay usuarios retornar una respuesta de no hay datos
        return response()->json(['message' => 'Bad Request: No hay usuarios registrados'], 400);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //metodo para crear un nuevo usuario
        $user = new User();
        //validamos los datos que llegan por el request
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:80',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
        //verificamos si se cumplen o no las validaciones
        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error: ',
                'errors' => $validator->errors()
            ], 400);
        }
        //si se cumplen las validaciones, creamos el usuario
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password =  password_hash($request->password, PASSWORD_BCRYPT);
        $user->save();
        //retornamos la respuesta de creacion con exito
        return response()->json(['message' => 'Usuario creado con éxito'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //ver usuario por id
        $user = User::find($id);
        //validamos que el usuario exista
        if($user != null){
            return response()->json($user, 200);
        }
        # si el usuario no existe retornamos una respuesta de no encontrado
        return response()->json(['message' => 'Not Found: Usuario no encontrado'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //actualizando un usuario existente
        $user = User::find($id);
        //validamos que el usuario exista
        if(!$user){
            return response()->json(['message' => 'Not Found: Usuario no encontrado'], 404);
        }
        //validamos los datos que llegan por el request
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:80',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'required|string|min:8'
        ]);
        //verificamos si se cumplen o no las validaciones
        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error: ',
                'errors' => $validator->errors()
            ], 400);
        }
        //Actualizamos los campos solo si estan presente en el request
        if($request->name){
            $user->name = $request->name;
        }
        if($request->email){
            $user->email = $request->email;
        }
        if($request->password){
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
        }
        //guardamos los cambios en la base de datos
        $user->save();
        //retornamos la respuesta de actualización con exito
        return response()->json(['message' => 'Usuario actualizado con éxito'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //eliminando un usuario existente
        $user = User::find($id);
        //validamos que el usuario exista
        if(!$user){
            return response()->json(['error' => 'Not Found: Usuario no encontrado'], 404);
        }
        //eliminamos el usuario de la base de datos
        $user->delete();
        //retornamos la respuesta de eliminación con exito
        return response()->json(['message' => 'Usuario eliminado con éxito'], 200);
    }

    /**
     * buscar users cuyo nombre comience con una letra dada
     */
    public function searchUsersByNameLike(string $name){
        //sql = "SELECT * FROM users WHERE name LIKE '$name%'";
        $users = User::where('name', 'LIKE', '%'.$name.'%')->get();
        if (count($users) > 0) {
            # mandar todos los resultados con status 200
            return response()->json($users, 200);
        }
        # si no hay users, mandar status 400 con un mensaje de error
        return response()->json(['error' => 'No se encontraron usuarios con ese nombre'], 400);
    }
    /**
     * buscar users cuyo nombre comience con una letra dada
     */
    public function searchUsersByEmailLike(string $email){
        //sql = "SELECT * FROM users WHERE email LIKE '$email%'";
        $users = User::where('email', 'LIKE', '%'.$email.'%')->get();
        if (count($users) > 0) {
            # mandar todos los resultados con status 200
            return response()->json($users, 200);
        }
        # si no hay users, mandar status 400 con un mensaje de error
        return response()->json(['error' => 'No se encontraron usuarios con ese email'], 400);
    }

    /**
     * ordenar los usuarios por nombre desc
     */
    public function orderDESC(){
        //mostrar el listado de todos los usuarios ordenados de manera descendente por nombre
        $user = User::orderBy('name', 'desc')->get();
        // validamos que hayan datos para mostrar
        if(count($user) > 0){
            return response()->json($user, 200);
        }
        # si no hay usuarios retornar una respuesta de no hay datos
        return response()->json(['message' => 'Bad Request: No hay usuarios registrados'], 400);
    }

    /**
     * ordenar usuarios por orden asc
     */
    public function orderASC(){
        //mostrar el listado de todos los usuarios ordenados de manera ascendente por nombre
        $user = User::orderBy('name', 'asc')->get();
        // validamos que hayan datos para mostrar
        if(count($user) > 0){
            return response()->json($user, 200);
        }
        # si no hay usuarios retornar una respuesta de no hay datos
        return response()->json(['message' => 'Bad Request: No hay usuarios registrados'], 400);
    }
}