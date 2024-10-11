<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //mostrar todos los productos
        $product = Products::join('categories','categories.id','=','products.category_id')
        ->select('products.id', 'products.name', 'products.description', 'products.price', 'products.stock','categories.name as category')
        ->get();
        // validamos si hay o no productos en la base de datos
        if($product->isEmpty()) {
            return response()->json(['message' => 'Not Found: No hay productos registrados'], 404);
        }
        // si hay productos mostrarlos
        return response()->json($product, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //creacion de un nuevo producto
        $product = new Products();
        //validamos las entradas que lleguen por request a traves de validator
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => ['required', 'regex:/^\d{1,8}(\.\d{1,2})?$/'],
            'stock' => 'required',
            'category_id' => 'required|int'
        ]);
        // verificamos si se cumplen o no las validaciones
        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error: ',
                'errors' => $validator->errors()
            ], 400);
        }
        // si las validaciones pasan, guardamos el producto en la base de datos
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->save();
        // si se guarda correctamente, retornamos el producto creado
        return response()->json(['message' => 'Producto creado correctamente.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //encontrar producto por id
        $product = Products::find($id);
        // verificamos si el producto existe
        if($product != null){
            return response()->json($product,200);
        }
        // si el producto no existe, retornamos un mensaje de error
        return response()->json(['message' => 'Not Found: Producto no encontrado'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //actualizacion de un registro
        $product = Products::find($id);
        // verificamos si el producto existe
        if($product!= null){
            // validamos las entradas que lleguen por request a traves de validator
            $validator = Validator::make($request->all(),[
                'name' =>'required|string|max:255',
                'description' =>'required|string|max:255',
                'price' => ['required', 'regex:/^\d{1,8}(\.\d{1,2})?$/'],
                'stock' => 'required|integer',
                'category_id' => 'required|int'
            ]);
            // verificamos si se cumplen o no las validaciones
            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error: ',
                    'errors' => $validator->errors()
                ], 400);
            }
            // si las validaciones pasan, actualizamos el producto en la base de datos
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->category_id = $request->category_id;
            $product->save();
            // si se guarda correctamente, retornamos el producto actualizado
            return response()->json(['message' => 'Producto actualizado correctamente.'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // eliminar un producto
        $product = Products::find($id);
        // verificamos si el producto existe
        if($product!= null){
            $product->delete();
            // si se elimina correctamente, retornamos un mensaje de confirmacion
            return response()->json(['message' => 'Producto eliminado correctamente.'], 200);
        }
        // si el producto no existe, retornamos un mensaje de error
        return response()->json(['message' => 'Not Found: Producto no encontrado'], 404);
    }
    /**
     * buscar products cuyo nombre comience con una letra dada
     */
    public function searchByNameLike(string $name){
        //sql = "SELECT * FROM products WHERE name LIKE '$name%'";
        $product = Products::where('name', 'LIKE', '%'.$name.'%')->get();
        if (count($product) > 0) {
            # mandar todos los resultados con status 200
            return response()->json($product, 200);
        }
        # si no hay products, mandar status 400 con un mensaje de error
        return response()->json(['error' => 'No se encontraron producto con ese nombre'], 400);
    }
    /**
     * Display the specified resource.
     */
    public function showByCategory(string $id)
    {
        //ver usuario por id
        $product = Products::join('categories','categories.id','=','products.category_id')
        ->select('products.id', 'products.name', 'products.description', 'products.price', 'products.stock','categories.name as category')
        ->where('category_id', $id)->get();
        //validamos que el usuario exista
        if($product != null){
            return response()->json($product, 200);
        }
        # si el usuario no existe retornamos una respuesta de no encontrado
        return response()->json(['message' => 'Not Found: Productos no encontrados'], 404);
    }
    public function searchProductsByCategoryLike(string $name){
        //sql = "SELECT * FROM products WHERE name LIKE '$name%'";
        $product = Products::join('categories','categories.id','=','products.category_id')
        ->where('categories.name', 'LIKE', '%' . $name . '%')
        ->select('products.id', 'products.name', 'products.description', 'products.price', 'products.stock', 'categories.name as category')
        ->get();
         // Verificar si hay productos encontrados
    if ($product->isEmpty()) {
        // Si no hay productos, retorna un mensaje de error con status 400
        return response()->json(['error' => 'No se encontraron productos con esa categoría'], 400);
    }

    // Si hay productos, retornarlos con status 200
    return response()->json($product, 200);
    }
}
