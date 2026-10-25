<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Librerias\Libreria;
use App\VisitaIrrigacion;
use App\VisitaIrrigacionImagen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
//use App\Events\UserHasCreatedOrDeleted;
//use App\Events\UserHasEdited;

//use App\Rules\RuleImgFirmaExist;

class VisitaIrrigacionImagenController extends Controller
{
    protected $folderview      = 'app.visita-irrigacion-imagen';
    protected $tituloAdmin     = 'Galeria';
    protected $tituloRegistrar = 'Registrar imagen';
    protected $tituloModificar = 'Modificar imagen';
    protected $tituloEliminar  = 'Eliminar imagen';
    protected $entidad         = 'Galeria';
    protected $rutas           = array('create' => 'visita-irrigacion-imagen.create', 
            'edit'   => 'visita-irrigacion-imagen.edit', 
            'delete' => 'visita-irrigacion-imagen.eliminar',
            'search' => 'visita-irrigacion-imagen.buscar',
            'index'  => 'visita-irrigacion-imagen.index',
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

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = $this->entidad;

        $visita_id      = Libreria::getParam($request->input('visita_id'));
        

        $resultado        = VisitaIrrigacionImagen::where('visita_id','=',$visita_id);
        
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'F. Registro', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Archivo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Opciones', 'numero' => '1');
        
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

    public function index( Request $request ){
        
        $entidad          = $this->entidad;
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $visita_id	  = $request->input('visita_id');
        $visita = Visitairrigacion::find($visita_id);
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'visita_id','visita'));
    }

    public function store(Request $request){
        $reglas = array(
            'archivo' => 'required',
        );
        $mensajes = array(
            'archivo.required' => 'Debe ingresar archivo',
        );

        $validacion = Validator::make($request->all(), $reglas, $mensajes);

        if ($validacion->fails()) return $validacion->messages()->toJson();

        $visita_id = $request->input('visita_id');
        
        $error = DB::transaction(function() use($request){

     		$obj = new VisitaIrrigacionImagen();

            $visita = VisitaIrrigacion::find($request->input('visita_id'));
             
            $archivo  = $request->file('archivo');
            $nameArchivo = $visita->id . '_' . $archivo->getClientOriginalName();
            $obj->archivo =  $nameArchivo;
            $archivo->move(public_path('image-web/irrigacion/'), $nameArchivo);       
     		$obj->visita_id = $request->input('visita_id');
            $obj->save();


        });

        return is_null($error) ? "OK" : $error;
    }

    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'visitas-irrigacion-imagen');
        if ($existe !== true) return $existe;
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) $listar = $listarLuego;
        $mensaje = true;
        $modelo   = VisitaIrrigacionImagen::find($id);
        $entidad  = $this->entidad;
        $formData = array('route' => array('visita-irrigacion-imagen.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar','mensaje'));
    }

    public function destroy($id) {
        $existe = Libreria::verificarExistencia($id, 'visitas-irrigacion-imagen');
        if ($existe !== true) return $existe;
        $error = DB::transaction(function() use($id){
            $obj = VisitaIrrigacionImagen::find($id);
            $obj->delete();
        });
        return is_null($error) ? "OK" : $error;
    }
}
