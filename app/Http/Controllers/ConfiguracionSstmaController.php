<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\ConfiguracionSstma;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ConfiguracionSstmaController extends Controller
{
    protected $folderview      = 'app.configuracion-sstma';
    protected $tituloAdmin     = 'Configuracion Sstma';
    protected $tituloRegistrar = 'Registrar configuracion';
    protected $tituloModificar = 'Modificar configuracion';
    protected $tituloEliminar  = 'Eliminar configuracion';
    protected $rutas           = array('create' => 'configuracion-sstma.create', 
            'edit'     => 'configuracion-sstma.edit', 
            'delete'   => 'configuracion-sstma.eliminar',
            'search'   => 'configuracion-sstma.buscar',
            'index'    => 'configuracion-sstma.index',
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
        $entidad          = 'Configuracion';
        $name             = Libreria::getParam($request->input('name'));
        $resultado        = ConfiguracionSstma::where('nombre','like','%'.$name.'%');
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Valor', 'numero' => '1');
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
        $entidad          = 'Configuracion';
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
        $entidad      = 'Configuracion';
        $configuracion  = null;
        $formData     = array('configuracion-sstma.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('configuracion', 'formData', 'entidad', 'boton', 'listar'));
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
                'valor' => 'required'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $modalidad       = new Modalidad();
            $modalidad->descripcion = $request->input('descripcion');
            $modalidad->save();
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
        $existe = Libreria::verificarExistencia($id, 'configuracion-sstma');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $configuracion  = ConfiguracionSstma::find($id);
        $entidad      = 'Configuracion';
        $formData     = array('configuracion-sstma.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off','enctype' => 'multipart/form-data');
        $boton        = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('configuracion', 'formData', 'entidad', 'boton', 'listar'));
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
        $existe = Libreria::verificarExistencia($id, 'configuracion-sstma');
        if ($existe !== true) {
            return $existe;
        }
        /*$validacion = Validator::make($request->all(),
            array(
                'valor' => 'required'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } */
        $error = DB::transaction(function() use($request, $id){
            $obj       = ConfiguracionSstma::find($id);

            $file = $request->file('archivo');
            if($file){
                $old_image = $obj->archivo;
                $old_path = public_path('image-web/sstma/' . $old_image);
                if (file_exists($old_path)) {
                    @unlink($old_path);
                }
                
                $nombre = time()."-".$file->getClientOriginalName();
                $path = public_path('image-web/sstma/'. $nombre);

                $file->move('image-web/sstma',$nombre);
                $obj->archivo = $nombre;
            }
            $imagen = $request->file('imagen');
            if($imagen){
                $old_image = $obj->imagen;
                $old_path = public_path('image-web/sstma/' . $old_image);
                if (file_exists($old_path)) {
                    @unlink($old_path);
                }
                
                $nombre = time()."-".$imagen->getClientOriginalName();
                $path = public_path('image-web/sstma/'. $nombre);

                $imagen->move('image-web/sstma',$nombre);
                $obj->imagen = $nombre;
            }
            $obj->nombre = $request->input('nombre');
            $obj->valor = $request->input('valor');
            $obj->save();

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
        $existe = Libreria::verificarExistencia($id, 'configuracion');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $configuracion = Configuracion::find($id);
            $configuracion->delete();
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
        $existe = Libreria::verificarExistencia($id, 'configuracion');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Configuracion::find($id);
        $entidad  = 'Configuracion';
        $formData = array('route' => array('modalidad.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
