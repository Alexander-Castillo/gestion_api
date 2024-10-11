<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryTransactionsResource;
use App\Models\Inventory_transactions;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InventoryTransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // mostrar la informacion de la transaccion
        $inventary = Inventory_transactions::join('users', 'users.id', '=', 'inventory_transactions.user_id')
            ->join('products', 'products.id', '=', 'inventory_transactions.product_id')
            ->select(
                'inventory_transactions.id',
                'users.name as user_name',
                'products.name as product_name',
                'products.price as unity_price',
                Inventory_transactions::raw('inventory_transactions.quantity * products.price as total_price'),
                'inventory_transactions.transaction_type',
                'inventory_transactions.created_at'
            )
            ->get();
        // Verificar si hay registros
        if ($inventary->isEmpty()) {
            // No hay registros, devolver un mensaje con status 400
            return response()->json([
                'message' => 'No hay registros'
            ], 400);
        }

        // Si hay registros, aplicar el Resource y devolver con status 200
        return response()->json([
            'inventario' => InventoryTransactionsResource::collection($inventary)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // crear un nuevo registro
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'transaction_type' => [
                'required',
                Rule::in(['entradas', 'salidas']),
            ],
        ]);
        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json(['message' => 'Datos de entrada no válidos', 'errors' => $validator->errors()], 400);
        }
        // iniciando una transaccion en la base de datos
        DB::beginTransaction();
        try {
            // obtenemos el producto
            $product = Products::findOrFail($request->product_id);
            // Actualizar el stock de producto segun el tipo de una transaccion
            if ($request->transaction_type === 'entradas') {
                $product->stock += $request->quantity;
            } elseif ($request->transaction_type === 'salidas') {
                // verificamos si  hay suficientes stock para la salida
                if ($request->quantity > $product->stock) {
                    // No hay suficiente stock, devolver un mensaje con status 400
                    return response()->json([
                        'message' => 'No hay suficiente stock para realizar la transaccion'
                    ], 400);
                }
                $product->stock -= $request->quantity;
            }
            // guardar el producto en la base de datos
            $product->save();
            // crear una nueva transaccion en la base de datos
            $inventary = Inventory_transactions::create([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'transaction_type' => $request->transaction_type,
            ]);
            // confirmar la transaccion
            DB::commit();

            return response()->json(['message' => 'Transacción registrada exitosamente.'], 201);
        } catch (\Exception $e) {
            // revertir la transaccion en caso de error rollback revierte la migracion o la insercion en la base de datos en caso de error
            DB::rollBack();
            return response()->json(['message' => 'Error al registrar la transacción.', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * Display a listing of the resource filtered by type.
     */
    public function filterEntradasByDate(Request $request){
        $date = $request->input('date');
        $validator = Validator::make(['date' => $date], ['date' =>'required|date']);
        if ($validator->fails()) {
            return response()->json(['message' => 'Formato de fecha no válido', 'errors' => $validator->errors()], 400);
        }
        $inventary = Inventory_transactions::with(['user','product'])
        ->where('transaction_type', 'entradas')
        ->whereDate('created_at', $date)
        ->select()
        ->get()
        ->map(function($inventary){
            return [
                'user_name' => $inventary->user->name,
                'product_name' => $inventary->product->name,
                'unity_price' => $inventary->product->price,
                'total_price' => $inventary->quantity * $inventary->product->price,
                'quantity' => $inventary->quantity,
                'transaction_type' => $inventary->transaction_type,
                'created_at' => Carbon::parse($inventary->created_at)->format('d-m-Y')
            ];
        });
        if ($inventary->isEmpty()) {
            return response()->json(['message' => 'No hay entradas para la fecha '. $date], 400);
        }
        return response()->json(['inventario' => $inventary], 200);
    }
    /**
     * filtrar registros por años
     */
    public function filterByYear(Request $request){
        $year = $request->input('year');
        $validator = Validator::make(['year' => $year], ['year' =>'required|integer']);
        if ($validator->fails()) {
            return response()->json(['message' => 'Formato de año no válido', 'errors' => $validator->errors()], 400);
        }
        $inventary = Inventory_transactions::with(['user','product'])
        ->whereYear('created_at', $year)
        ->select()
        ->get()
        ->map(function($inventary){
            return [
                'user_name' => $inventary->user->name,
                'product_name' => $inventary->product->name,
                'unity_price' => $inventary->product->price,
                'total_price' => $inventary->quantity * $inventary->product->price,
                'quantity' => $inventary->quantity,
                'transaction_type' => $inventary->transaction_type,
                'created_at' => Carbon::parse($inventary->created_at)->format('d-m-Y')
            ];
        });
        // validamos si el objeto no tiene datos
        if ($inventary->isEmpty()) {
            return response()->json(['message' => 'No hay transacciones para el año '. $year], 400);
        }
        // devolvemos los datos filtrados
        return response()->json($inventary, 200);
    }
    /**
     * Display a listing of the resource filtered by type.
     */
    public function filterSalidasByDate(Request $request){
        $date = $request->input('date');
        $validator = Validator::make(['date' => $date], ['date' =>'required|date']);
        if ($validator->fails()) {
            return response()->json(['message' => 'Formato de fecha no válido', 'errors' => $validator->errors()], 400);
        }
        $inventary = Inventory_transactions::with(['user','product'])
        ->where('transaction_type', 'salidas')
        ->whereDate('created_at', $date)
        ->select()
        ->get()
        ->map(function($inventary){
            return [
                'user_name' => $inventary->user->name,
                'product_name' => $inventary->product->name,
                'unity_price' => $inventary->product->price,
                'total_price' => $inventary->quantity * $inventary->product->price,
                'quantity' => $inventary->quantity,
                'transaction_type' => $inventary->transaction_type,
                'created_at' => Carbon::parse($inventary->created_at)->format('d-m-Y')
            ];
        });
        if ($inventary->isEmpty()) {
            return response()->json(['message' => 'No hay entradas para la fecha '. $date], 400);
        }
        return response()->json(['inventario' => $inventary], 200);
    }
    /**
     * ordenar los registros por tipo
     */
    public function sortByType(Request $request){
        $type = $request->input('type');
        if (!in_array($type, ['entradas', 'salidas'])) {
            return response()->json(['error' => 'Tipo de transacción inválido'], 400);
        }
        $inventary = Inventory_transactions::with(['user','product'])
        ->where('transaction_type', $type)
        ->select()
        ->orderBy('created_at','DESC')
        ->get()
        ->map(function($inventary){
            return [
                'user_name' => $inventary->user->name,
                'product_name' => $inventary->product->name,
                'unity_price' => $inventary->product->price,
                'total_price' => $inventary->quantity * $inventary->product->price,
                'quantity' => $inventary->quantity,
                'transaction_type' => $inventary->transaction_type,
                'created_at' => Carbon::parse($inventary->created_at)->format('d-m-Y')
            ];
        });
        if ($inventary->isEmpty()) {
            return response()->json(['message' => 'No hay transacciones de tipo '. $type], 400);
        }
        // ordenamos los resultados por su tipo 
        return response()->json($inventary, 200);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inventary = Inventory_transactions::join('users', 'users.id', '=', 'inventory_transactions.user_id')
            ->join('products', 'products.id', '=', 'inventory_transactions.product_id')
            ->select(
                'inventory_transactions.id',
                'users.name as user_name',
                'products.name as product_name',
                'products.price as unity_price',
                Inventory_transactions::raw('inventory_transactions.quantity * products.price as total_price'),
                'inventory_transactions.transaction_type',
                'inventory_transactions.created_at'
            )
            ->where('inventory_transactions.id', $id)
            ->get();
        // validation de si hay o no datos con ese id
        if ($inventary->isEmpty()) {
            return response()->json(['message' => 'No se encontró la transacción con id '. $id], 404);
        }
        return response()->json($inventary, 200);       
    }
}
