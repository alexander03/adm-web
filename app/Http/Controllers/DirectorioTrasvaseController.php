<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\DirectorioTrasvase;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DirectorioTrasvaseController extends Controller
{
    protected $folderview      = 'app.directorio-trasvase';
    protected $tituloAdmin     = 'Directorio Trasvase';
    protected $entidad         = 'Home';
    protected $tituloRegistrar = 'Registrar Directorio';
    protected $tituloModificar = 'Modificar Directorio';
    protected $tituloEliminar  = 'Eliminar Directorio';
    protected $rutas           = array('create' => 'directorio-trasvase.create', 
            'edit'     => 'directorio-trasvase.edit', 
            'delete'   => 'directorio-trasvase.eliminar',
            'search'   => 'directorio-trasvase.buscar',
            'index'    => 'directorio-trasvase.index',
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
     * Mostrar el resultado de b�squedas
     * 
     * @return Response 
     */
    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = $this->entidad;
        $name             = Libreria::getParam($request->input('name'));
        $resultado        = DirectorioTrasvase::where('nombre','like','%'.$name.'%')->orderBy('orden','asc');
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cargo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombres', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Direccion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CV', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Orden', 'numero' => '1');
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
        $formData     = array('directorio-trasvase.store');
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
                'cargo' => 'required',
                'nombre' => 'required',
                'archivo' => 'required',
                'cv' => 'required',
                'orden' => 'required',
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){

            $file = $request->file('archivo');
            $nombre = time()."-".$file->getClientOriginalName();
            $path = public_path('image-web/trasvase/'. $nombre);

            $file->move('image-web/trasvase',$nombre);

            $nombre2 = time()."-".$file2->getClientOriginalName();
            $path = public_path('image-web/trasvase/'. $nombre2);
            $file2->move('image-web/trasvase',$nombre2);
            
            $obj       = new DirectorioTrasvase();
            $obj->cargo = $request->input('cargo');
            $obj->nombre = $request->input('nombre');
            $obj->descripcion = $request->input('descripcion');
            $obj->archivo = $nombre;
            $obj->cv = $nombre2;
            $obj->orden = $request->input('orden');
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
        $existe = Libreria::verificarExistencia($id, 'directorio-trasvase');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $home  = DirectorioTrasvase::find($id);
        $entidad      = $this->entidad;
        $formData     = array('directorio-trasvase.update', $id);
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
        $existe = Libreria::verificarExistencia($id, 'directorio-trasvase');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'cargo' => 'required',
                'nombre' => 'required',
                'orden' => 'required'
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $obj       = DirectorioTrasvase::find($id);

            $file = $request->file('archivo');
            if($file){
                $old_image = $obj->archivo;
                $old_path = public_path('image-web/trasvase/' . $old_image);
                if (file_exists($old_path)) {
                    @unlink($old_path);
                }
                
                $nombre = time()."-".$file->getClientOriginalName();
                $path = public_path('image-web/trasvase/'. $nombre);

                $file->move('image-web/trasvase',$nombre);
                $obj->cargo = $request->input('cargo');
                $obj->nombre = $request->input('nombre');
                $obj->descripcion = $request->input('descripcion');
                $obj->archivo = $nombre;
                $obj->orden = $request->input('orden');

                $file2 = $request->file('cv');
                if($file2){
                    $nombre2 = time()."-".$file2->getClientOriginalName();
                    $path = public_path('image-web/trasvase/'. $nombre2);

                    $file2->move('image-web/trasvase',$nombre2);
                    $obj->cv = $nombre2;
                }

                $obj->save();
            }else{
                $file2 = $request->file('cv');
                if($file2){
                    $nombre2 = time()."-".$file2->getClientOriginalName();
                    $path = public_path('image-web/trasvase/'. $nombre2);

                    $file2->move('image-web/trasvase',$nombre2);
                    $obj->cv = $nombre2;
                }
                $obj->cargo = $request->input('cargo');
                $obj->nombre = $request->input('nombre');
                $obj->orden = $request->input('orden');
                $obj->descripcion = $request->input('descripcion');
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
        $existe = Libreria::verificarExistencia($id, 'directorio-trasvase');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = DirectorioTrasvase::find($id);
            $obj->delete();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Funci�n para confirmar la eliminaci�n de un registrlo
     * @param  integer $id          id del registro a intentar eliminar
     * @param  string $listarLuego consultar si luego de eliminar se listar�
     * @return html              se retorna html, con la ventana de confirmar eliminar
     */
    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'directorio-trasvase');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = DirectorioTrasvase::find($id);
        $entidad  = $this->entidad;
        $formData = array('route' => array('directorio-trasvase.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
