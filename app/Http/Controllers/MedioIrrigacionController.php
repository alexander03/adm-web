<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\MedioIrrigacion;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MedioIrrigacionController extends Controller
{
    protected $folderview      = 'app.medio-irrigacion';
    protected $tituloAdmin     = 'Medio Ambiente Irrigacion';
    protected $entidad         = 'Home';
    protected $tituloRegistrar = 'Registrar Medio Ambiente';
    protected $tituloModificar = 'Modificar Medio Ambiente';
    protected $tituloEliminar  = 'Eliminar Medio Ambiente';
    protected $rutas           = array('create' => 'medio-irrigacion.create', 
            'edit'     => 'medio-irrigacion.edit', 
            'delete'   => 'medio-irrigacion.eliminar',
            'search'   => 'medio-irrigacion.buscar',
            'index'    => 'medio-irrigacion.index',
        );

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el resultado de búsquedas
     * 
     * @return Response 
     */
    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = $this->entidad;
        $name             = Libreria::getParam($request->input('name'));
        $resultado        = MedioIrrigacion::where('titulo','like','%'.$name.'%')->orderBy('orden','asc');
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Titulo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Descripcion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Orden', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Ancho', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $ruta             = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidad          = $this->entidad;
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = $this->entidad;
        $home  = null;
        $formData     = array('medio-irrigacion.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off','enctype' => 'multipart/form-data');
        $boton        = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('home', 'formData', 'entidad', 'boton', 'listar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $validacion = Validator::make($request->all(),
            array(
                'titulo' => 'required',
                'descripcion' => 'required',
                'archivo' => 'required',
                'orden' => 'required',
                'ancho' => 'required',
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){

            $file = $request->file('archivo');
            $nombre = time()."-".$file->getClientOriginalName();
            $path = public_path('image-web/irrigacion/'. $nombre);

            $file->move('image-web/irrigacion',$nombre);
            $obj       = new MedioIrrigacion();
            $obj->titulo = $request->input('titulo');
            $obj->descripcion = $request->input('descripcion');
            $obj->archivo = $nombre;
            $obj->orden = $request->input('orden');
            $obj->ancho = $request->input('ancho');
            $obj->save();
        });
        return is_null($error) ? "OK" : $error;
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
    public function edit($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'medio-irrigacion');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $home  = MedioIrrigacion::find($id);
        $entidad      = $this->entidad;
        $formData     = array('medio-irrigacion.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off','enctype' => 'multipart/form-data');
        $boton        = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('home', 'formData', 'entidad', 'boton', 'listar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'medio-irrigacion');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'orden' => 'required',
                'ancho' => 'required',
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $obj       = MedioIrrigacion::find($id);

            $file = $request->file('archivo');
            if($file){
                $old_image = $obj->archivo;
                $old_path = public_path('image-web/irrigacion/' . $old_image);
                if (file_exists($old_path)) {
                    @unlink($old_path);
                }
                
                $nombre = time()."-".$file->getClientOriginalName();
                $path = public_path('image-web/irrigacion/'. $nombre);

                $file->move('image-web/irrigacion',$nombre);
                $obj->titulo = $request->input('titulo');
                $obj->descripcion = $request->input('descripcion');
                $obj->archivo = $nombre;
                $obj->orden = $request->input('orden');
                $obj->ancho = $request->input('ancho');
                $obj->save();
            }else{
                $obj->titulo = $request->input('titulo');
                $obj->descripcion = $request->input('descripcion');
                $obj->orden = $request->input('orden');
                $obj->ancho = $request->input('ancho');
                $obj->save();
            }

        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'medio-irrigacion');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = MedioIrrigacion::find($id);
            $obj->delete();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Función para confirmar la eliminación de un registrlo
     * @param  integer $id          id del registro a intentar eliminar
     * @param  string $listarLuego consultar si luego de eliminar se listará
     * @return html              se retorna html, con la ventana de confirmar eliminar
     */
    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'medio-irrigacion');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = MedioIrrigacion::find($id);
        $entidad  = $this->entidad;
        $formData = array('route' => array('medio-irrigacion.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
