<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Librerias\Libreria;
use App\Blog;
use App\BlogImagen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
//use App\Events\UserHasCreatedOrDeleted;
//use App\Events\UserHasEdited;

//use App\Rules\RuleImgFirmaExist;

class BlogImagenController extends Controller
{
    protected $folderview      = 'app.blogimagen';
    protected $tituloAdmin     = 'Galeria';
    protected $tituloRegistrar = 'Registrar imagen';
    protected $tituloModificar = 'Modificar imagen';
    protected $tituloEliminar  = 'Eliminar imagen';
    protected $entidad         = 'Galeria';
    protected $rutas           = array('create' => 'blog-imagen.create', 
            'edit'   => 'blog-imagen.edit', 
            'delete' => 'blog-imagen.eliminar',
            'search' => 'blog-imagen.buscar',
            'index'  => 'blog-imagen.index',
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

        $blog_id      = Libreria::getParam($request->input('blog_id'));
        

        $resultado        = BlogImagen::where('blog_id','=',$blog_id);
        
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
        $blog_id	  = $request->input('blog_id');
        $blog = Blog::find($blog_id);
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'blog_id','blog'));
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

        $blog_id = $request->input('blog_id');
        
        $error = DB::transaction(function() use($request){

     		$obj = new BlogImagen();

            $blog = Blog::find($request->input('blog_id'));
             
            $archivo  = $request->file('archivo');
            $nameArchivo = $blog->id . '_' . $archivo->getClientOriginalName();
            $obj->archivo =  $nameArchivo;
            $obj->nombre =  $archivo;
            $archivo->move(public_path('image-web/blog/'), $nameArchivo);       
     		$obj->blog_id = $request->input('blog_id');
            $obj->save();


        });

        return is_null($error) ? "OK" : $error;
    }

    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'blog-imagen');
        if ($existe !== true) return $existe;
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) $listar = $listarLuego;
        $mensaje = true;
        $modelo   = BlogImagen::find($id);
        $entidad  = $this->entidad;
        $formData = array('route' => array('blog-imagen.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar','mensaje'));
    }

    public function destroy($id) {
        $existe = Libreria::verificarExistencia($id, 'blog-imagen');
        if ($existe !== true) return $existe;
        $error = DB::transaction(function() use($id){
            $obj = BlogImagen::find($id);
            $obj->delete();
        });
        return is_null($error) ? "OK" : $error;
    }
}
