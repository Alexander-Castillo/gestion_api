<?php
use App\Http\Middleware\ApiTokenMiddleware;
use App\Http\Controllers\InventoryTransactionsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// rutas publicas
Route::prefix('V1')->group(function () {
    # Ruta para Login
    Route::post('/login', [LoginController::class, 'login']);

    # ruta para renovar el token
    Route::post('/token_refresh', [LoginController::class, 'refreshToken']);
    
    # ruta para crear un nuevo usuario
    Route::post('/users/new_user', [UsersController::class, 'store']);

});
//rutas protegidas 
Route::middleware([ApiTokenMiddleware::class])->group(function () {
    // prefix version for the route
    Route::prefix('V1')->group(function () {
        # ruta mostrar todos los usuarios
        Route::get('/users', [UsersController::class, 'index']);

        # ruta para mostrar un usuario específico por el id
        Route::get('/users/{id}', [UsersController::class, 'show']);

        # ruta para actualizar un usuario específico por el id
        Route::put('/users/update/{id}', [UsersController::class, 'update']);

        # ruta para eliminar un usuario específico por el id
        Route::delete('/users/delete/{id}', [UsersController::class, 'destroy']);

        # ruta para buscar usuarios por medio de email o letra
        Route::get('/users/search_by_name/{name}', [UsersController::class, 'searchUsersByNameLike']);

        # ruta para buscar usuarios por medio de email o letra
        Route::get('/users/search_by_email/{email}', [UsersController::class, 'searchUsersByEmailLike']);

        # ruta para buscar users en orden desc
        Route::get('/name_users_DESC', [UsersController::class, 'orderDESC']);

        # ruta para buscar users en orden asc
        Route::get('/name_users_ASC', [UsersController::class, 'orderASC']);
        /************************************************************************************************/
        # RUTAS PRODUCT
        # ruta para mostrar todos los productos
        Route::get('/products', [ProductsController::class, 'index']);

        # ruta para mostrar un producto específico por el id
        Route::get('/products/{id}', [ProductsController::class, 'show']);

        # ruta para crear un nuevo producto
        Route::post('/products/new_product', [ProductsController::class, 'store']);

        # ruta para actualizar un producto específico por el id
        Route::put('/products/update/{id}', [ProductsController::class, 'update']);

        # ruta para eliminar un producto específico por el id
        Route::delete('/products/delete/{id}', [ProductsController::class, 'destroy']);

        # ruta para buscar productos por medio de nombre o letra
        Route::get('/products/search_by_name/{name}', [ProductsController::class, 'searchByNameLike']);

        # ruta para mostrar los productos por su category
        Route::get('/products/search_by_category/{id}', [ProductsController::class, 'showByCategory']);

        # ruta para mostrar los productos por su category
        Route::get('/products/search_by_category_name/{name}', [ProductsController::class, 'searchProductsByCategoryLike']);
        /************************************************************************************************/
        # RUTAS Inventario
        # ruta para mostrar todos los inventarios
        Route::get('/inventory', [InventoryTransactionsController::class, 'index']);

        #ruta para crear un nuevo registro en el inventario
        Route::post('/inventory/new_inventory', [InventoryTransactionsController::class, 'store']);

        # ruta para filtrar los registros de entradas por fecha
        Route::get('/inventory/filter_entradas_by_date/', [InventoryTransactionsController::class, 'filterEntradasByDate']);

        # ruta para filtrar los registros de entradas por fecha
        Route::get('/inventory/filter_salidas_by_date/', [InventoryTransactionsController::class, 'filterSalidasByDate']);

        # ruta para filtrar los registros de entradas por año
        Route::get('/inventory/filter_entradas_by_year/', [InventoryTransactionsController::class, 'filterByYear']);

        # ruta para ordenar los registros por tipo y fecha de creacion
        Route::get('/inventory/order_by_type/', [InventoryTransactionsController::class, 'sortByType']);

        # ruta para mostrar un registro específico por el id
        Route::get('/inventory/{id}', [InventoryTransactionsController::class, 'show']);
    });
});
