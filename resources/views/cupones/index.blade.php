@extends('principal.index')

@section('content')
    <br>
    <a href="{{route('cupones.create')}}" class="btn btn-info">
        <i class="fa fa-hand-peace-o" aria-hidden="true"></i>
        Agregar Cupon
    </a>
    <br>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title text-center"><i class="fa fa-hand-peace-o" aria-hidden="true"></i>
                Cupones</h3></div>
        <div class="panel-body">
            <table class="table table-hover table-striped " s>
                <thead class="bg-primary">
                <tr>
                    <th>Clave</th>
                    <th>Descripcion</th>
                    <th>Descuento</th>
                    <th>Accion</th>
                </tr>
                <thead>
                <tbody>

                </tbody>
                @foreach($cupon as $cu)
                    <tr>
                            <td>{{ $cu-> clave}}</td>
                            <td>{{ $cu-> descripcion }}</td>
                            <td>{{ $cu-> descuento }}</td>
                            <td><a  href="{{ route('cupones.edit',$cu->id) }}" class="btn btn-success" >
                                    <i class="fa fa-pencil fa-lg" ></i>
                                </a>
                                <a href="{{route('cupones.destroy',$cu->id)}}"  onclick="return confirm('¿Seguro que desea eliminar esta Categoria?')" class="btn btn-danger">
                                    <i class="fa fa-remove fa-lg "></i>
                                </a></td>
                    </tr>
                @endforeach
            </table>
            {!! $cupon->render() !!}
        </div>
    </div>
@stop