<?php

namespace App\Http\Controllers;

use App\DetalleOrden;
use App\Orden;
use App\User;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Caffeinated\Flash\Facades\Flash;

class DetalleOrdenesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $do = DB::table('detalleorden')->orderBy('detalleorden.id_orden', 'asc')
            ->join('users', 'users.id', '=', 'detalleorden.id_cliente')
            ->join('orden', 'orden.id', '=', 'detalleorden.id_orden')
            ->join('producto', 'producto.id', '=', 'detalleorden.id_producto')
            ->select('orden.id','users.nombres', 'users.apellidos', 'producto.nombre', 'detalleorden.cantidad')
            ->get();
        $data = array();
        $data['detalleorden'] = $do;
        return view('detalleordenes.index')
            ->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = User::all();
        $productos = Producto::all();
        $ordenes = Orden::all();
        return view('detalleordenes.create')
            ->with('clientes',$clientes)
            ->with( 'productos',$productos)
            ->with( 'ordenes',$ordenes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $do= new DetalleOrden($request->all());
        $do->id_orden= $request->id_orden;
        $do->id_cliente= $request->id_cliente;
        $do->id_producto= $request->id_producto;
        $do->cantidad= $request->cantidad;
        $do->save();

        Flash::success("Registro insertado de forma exitosa");
        return redirect()->route('detalleordenes.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function servicio_index($id)
    {
        $deo = DB::table('detalleorden')->orderBy('detalleorden.id', 'asc')
            ->join('orden', 'orden.id', '=', 'detalleorden.id_orden')
            ->join('users', 'users.id', '=', 'detalleorden.id_cliente')
            ->join('producto', 'producto.id', '=', 'detalleorden.id_producto')
            ->select('orden.id as orden','users.usuario as usuario', 'producto.nombre as producto', 'cantidad')
            ->where('detalleorden.id_cliente', '=', $id)
            ->get();

        return JsonResponse::create($deo);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Regresa los cupones para un cliente
        $cupon = DB::table('detalleorden')
            ->join('orden', 'detalleorden.id_orden', '=', 'orden.id')
            ->join('producto', 'detalleorden.id_producto', '=', 'producto.id')
            ->select('producto.nombre', 'orden.id','detalleorden.cantidad')
            ->where('detalleorden.id_cliente', '=', $id)->get();
        return JsonResponse::create($cupon);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function api_insert(Request $request)
    {
        $response = new \stdClass();
        $json = new \stdClass();
        $json = $request;
        //dd($json);
        $max = DB::table('orden')
            ->select(DB::raw('MAX(id) as maximo'))
            ->get();
        $id_ord = ($max[0]->maximo)+1;

        date_default_timezone_set('America/Mexico_City');
        $date = date('m/d/Y h:i:s', time());

        DB::table('orden')
            ->insert(
                [
                    'id'=>$id_ord,
                    'fecha' => $date
                ]
            );

            if (!$request->has('id_cliente')) {
                $response->success = false;
                $response->mensaje = 'no se recibio usuario';
                return JsonResponse::create($response);
            }
            if (!$request->has('id_producto')) {
                $response->success = false;
                $response->mensaje = 'no se recibio producto';
                return JsonResponse::create($response);
            }
            if (!$request->has('cantidad')) {
                $response->success = false;
                $response->mensaje = 'no se recibio cantidad';
                return JsonResponse::create($response);
            }
            DB::table('detalleorden')
                ->insert(
                    [
                        'id_orden' => $id_ord,
                        'id_cliente' => $request->id_cliente,
                        'id_producto' => $request->id_producto,
                        'cantidad' => $request->cantidad
                    ]
                );

        $response->success = true;
        return JsonResponse::create($response);
    }
}
