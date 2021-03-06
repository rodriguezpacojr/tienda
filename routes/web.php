<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('principal.index');
});

Route::group(['prefix'=>'admin','middleware'=>'auth'],function () {
    Route::resource('clientes','ClientesController');
    Route::resource('categorias','CategoriasController');
    Route::resource('proveedores','ProveedoresController');
    Route::resource('productos','ProductosController');
    Route::resource('cupones','CuponesController');
    Route::resource('clientecupones','ClienteCuponesController');
    Route::resource('detalleordenes','DetalleOrdenesController');
    Route::resource('metodos','MetodosController');

    Route::get('categorias/{id}/destroy', ['uses' => "CategoriasController@destroy", 'as' => 'categorias.destroy']);
    Route::get('proveedores/{id}/destroy', ['uses' => "ProveedoresController@destroy", 'as' => 'proveedores.destroy']);
    Route::get('clientes/{id}/destroy', ['uses' => "ClientesController@destroy", 'as' => 'clientes.destroy']);
    Route::get('productos/{id}/destroy', ['uses' => "ProductosController@destroy", 'as' => 'productos.destroy']);
    Route::get('cupones/{id}/destroy', ['uses' => "CuponesController@destroy", 'as' => 'cupones.destroy']);
    Route::get('clientecupones/{id}/destroy', ['uses' => "ClienteCuponesController@destroy", 'as' => 'clientecupones.destroy']);
    Route::get('metodos/{id}/destroy', ['uses' => "MetodosController@destroy", 'as' => 'metodos.destroy']);

    Route::get('productoscliente', 'GraficasController@productos_cliente');

    Route::get('totales', ['uses'=>'GraficasController@contador', 'as' => 'graficas.contador']);
    Route::get('ventasmes',['uses'=>'GraficasController@ventas_mensuales', 'as' => 'graficas.ventasmes']);
    Route::get('ventasanio',['uses'=>'GraficasController@ventas_anuales', 'as' => 'graficas.ventasanio']);
    Route::get('productos_mes', ['uses'=>'GraficasController@productos_mes', 'as' => 'graficas.productosmes']);
    Route::get('ventascliente', ['uses'=>'GraficasController@ventas_cliente', 'as' => 'graficas.ventascliente']);
});


Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('api')->group(function ()
{
    Route::get('/cupon/{id}', 'ClienteCuponesController@show');
    Route::post('/valcliente', 'ClientesController@val');
    Route::get('/perfil/{id}', 'ClientesController@perfil');
    Route::post('/users/insert', 'ClientesController@api_insert');
    Route::put('/users/update/{id}', 'ClientesController@api_update');
    Route::get('/estado', 'EstadosController@servicio_index');
    Route::get('/estado/pais/{id}', 'EstadosController@show');
    Route::get('/ciudad', 'CiudadesController@servicio_index');
    Route::get('/ciudad/estado/{id}', 'CiudadesController@show');
    Route::get('/pais', 'PaisesController@servicio_index');
    Route::put('/stock/{id}', 'ProductosController@update_stock');
    Route::post('/venta/insert', 'DetalleOrdenesController@api_insert');


    Route::get('categorias', 'CategoriasController@servicio_index');
    Route::get('proveedor', 'ProveedoresController@servicio_index');
    Route::get('cliente', 'ClientesController@servicio_index');
    Route::get('producto', 'ProductosController@servicio_index');
    Route::get('cupon', 'CuponesController@servicio_index');
    Route::get('clientecupon', 'ClienteCuponesController@servicio_index');
    Route::get('detalleordenes/{id}', 'DetalleOrdenesController@servicio_index');
    Route::get('pago', 'MetodosController@servicio_index');

    Route::get('borraclientecupon/{id_ct}/{clave}', 'ClienteCuponesController@delete_clientecupon');
    Route::post('clientecupon/insert', 'ClienteCuponesController@api_insert');
    Route::get('categoria_insert', 'CategoriasController@store');
});