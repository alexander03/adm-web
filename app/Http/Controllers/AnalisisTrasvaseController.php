<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\AnalisisTrasvase;
use App\CategoriaIrrigacion;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnalisisTrasvaseController extends Controller
{
    protected $folderview      = 'app.analisis-trasvase';
    protected $tituloAdmin     = 'Relaci&oacute;n con Inversionistas Trasvase';
    protected $entidad         = 'Home';
    protected $tituloRegistrar = 'Registrar Relaci&oacute;n';
    protected $tituloModificar = 'Modificar Relaci&oacute;n';
    protected $tituloEliminar  = 'Eliminar Relaci&oacute;n';
    protected $rutas           = array('create' => 'analisis-trasvase.create', 
            'edit'     => 'analisis-trasvase.edit', 
            'delete'   => 'analisis-trasvase.eliminar',
            'search'   => 'analisis-trasvase.buscar',
            'index'    => 'analisis-trasvase.index',
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
        $categoria_id     = Libreria::getParam($request->input('categoria_id'));
        $resultado        = AnalisisTrasvase::where('anio','like','%'.$name.'%')->orderBy('anio','desc')->orderBy('trimestre','desc');
        if($categoria_id!=""){
            $resultado = $resultado->where('categoria_id','=',$categoria_id);
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Categoria', 'numero' => '1');
        $cabecera[]       = array('valor' => 'A&ntilde;o', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Trimestre', 'numero' => '1');
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
        $cboCategoria = array('' => 'Todos') + CategoriaIrrigacion::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboCategoria'));
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
        $formData     = array('analisis-trasvase.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off','enctype' => 'multipart/form-data');
        $boton        = 'Registrar'; 
        $cboTrimestre = array("1er Trimestre"=>"1er Trimestre","2do Trimestre"=>"2do Trimestre","3er Trimestre"=>"3er Trimestre","4to Trimestre"=>"4to Trimestre","Apoyo"=>"Apoyo","Class"=>"Class","Equilibrium"=>"Equilibrium","Auditados"=>"Auditados","Memoria"=>"Memoria");
        $cboCategoria = array('' => '--Seleccione--') + CategoriaIrrigacion::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        return view($this->folderview.'.mant')->with(compact('home', 'formData', 'entidad', 'boton', 'listar','cboTrimestre','cboCategoria'));
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
                'anio' => 'required',
                'trimestre' => 'required',
                'archivo' => 'required',
                'categoria_id' => 'required',
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
            $obj       = new AnalisisTrasvase();
            $obj->anio = $request->input('anio');
            $obj->trimestre = $request->input('trimestre');
            $obj->categoria_id = $request->input('categoria_id');
            $obj->archivo = $nombre;
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
        $existe = Libreria::verificarExistencia($id, 'analisis-trasvase');
        if ($existe !== true) {
            return $existe;
        }
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $home  = AnalisisTrasvase::find($id);
        $entidad      = $this->entidad;
        $formData     = array('analisis-trasvase.update', $id);
        $formData     = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off','enctype' => 'multipart/form-data');
        $boton        = 'Modificar';
        $cboTrimestre = array("1er Trimestre"=>"1er Trimestre","2do Trimestre"=>"2do Trimestre","3er Trimestre"=>"3er Trimestre","4to Trimestre"=>"4to Trimestre","Apoyo"=>"Apoyo","Class"=>"Class","Equilibrium"=>"Equilibrium","Auditados"=>"Auditados","Memoria"=>"Memoria");
        $cboCategoria = array('' => '--Seleccione--') + CategoriaIrrigacion::orderBy('nombre','asc')->pluck('nombre','id')->toArray();
        return view($this->folderview.'.mant')->with(compact('home', 'formData', 'entidad', 'boton', 'listar','cboTrimestre','cboCategoria'));
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
        $existe = Libreria::verificarExistencia($id, 'analisis-trasvase');
        if ($existe !== true) {
            return $existe;
        }
        $validacion = Validator::make($request->all(),
            array(
                'anio' => 'required',
                'trimestre' => 'required',
                'categoria_id' => 'required',
                )
            );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $obj       = AnalisisTrasvase::find($id);

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
                $obj->anio = $request->input('anio');
                $obj->trimestre = $request->input('trimestre');
                $obj->categoria_id = $request->input('categoria_id');
                $obj->archivo = $nombre;
                $obj->save();
            }else{
                $obj->anio = $request->input('anio');
                $obj->trimestre = $request->input('trimestre');
                $obj->categoria_id = $request->input('categoria_id');
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
        $existe = Libreria::verificarExistencia($id, 'analisis-trasvase');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $obj = AnalisisTrasvase::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'analisis-trasvase');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = AnalisisTrasvase::find($id);
        $entidad  = $this->entidad;
        $formData = array('route' => array('analisis-trasvase.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

}
