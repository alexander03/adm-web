<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Home;
use App\Blog;
use App\BlogImagen;
use App\SeccionHome;
use App\TestimonioConocenos;
use App\ConfiguracionConocenos;
use App\BeneficioConocenos;
use App\Configuracion;
use App\ConfiguracionTrasvase;
use App\InfraestructuraTrasvase;
use App\GobernanzaTrasvase;
use App\MedioTrasvase;
use App\ResponsabilidadTrasvase;
use App\AnalisisTrasvase;
use App\DirectorioTrasvase;
use App\GaleriaTrasvase;
use App\VisitaTrasvase;
use App\VisitaTrasvaseImagen;
use App\CarruselTrasvase;
use App\ConstruccionTrasvase;
use App\ConstruccionIrrigacion;
use App\ConfiguracionIrrigacion;
use App\InfraestructuraIrrigacion;
use App\GobernanzaIrrigacion;
use App\MedioIrrigacion;
use App\ResponsabilidadIrrigacion;
use App\AnalisisIrrigacion;
use App\DirectorioIrrigacion;
use App\GaleriaIrrigacion;
use App\VisitaIrrigacion;
use App\VisitaIrrigacionImagen;
use App\ConfiguracionComunicaciones;
use App\Influencer;
use App\ConfiguracionLinea;
use App\ConfiguracionContacto;
use App\ConfiguracionIntegridad;
use App\LinkLinea;
use App\CategoriaIrrigacion;
use App\CarruselIrrigacion;
use App\ConfiguracionSstma;
use Illuminate\Support\Facades\DB;
use App\Librerias\Libreria;
use DateTime;

class WebServicesController extends Controller
{

    public function home(){
    	$slider = Home::orderBy('orden','asc')->get();
        foreach($slider as $k=>$v){
            $v->url_imagen = asset('image-web/home/'.$v->archivo);
        }

        $seccion = SeccionHome::orderBy('orden','asc')->get();
        foreach($seccion as $k=>$v){
            $v->url_imagen = asset('image-web/seccion-home/'.$v->archivo);
        }

        $noticias = Blog::orderBy('orden','asc')->get();
        foreach($noticias as $k=>$v){
            $v->url_imagen = asset('image-web/blog/'.$v->archivo);
            $galeria = BlogImagen::where('blog_id','=',$v->id)->orderBy('id')->select('id','archivo')->get();
            foreach($galeria as $m=>$n){
                $n->url_imagen = asset('image-web/blog/'.$n->archivo);
            }
            $v->galeria = $galeria ;
        }
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("slider"=>$slider,"seccion" => $seccion,"noticias"=>$noticias,"redes"=>$redes));
    }

    public function conocenos(){
        $configuracion = ConfiguracionConocenos::orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/conocenos/'.$v->archivo);
            if($v->id==2){
                $v->url_imagen = asset('image-web/conocenos/'.$v->imagen);
                $v->url_archivo = asset('image-web/conocenos/'.$v->archivo);
            }
        }

        $testimonio = TestimonioConocenos::orderBy('orden','asc')->get();
        foreach($testimonio as $k=>$v){
            $v->url_imagen = asset('image-web/conocenos/'.$v->archivo);
        }

        $beneficios = BeneficioConocenos::orderBy('orden','asc')->get();
        foreach($beneficios as $k=>$v){
            $v->url_imagen = asset('image-web/conocenos/'.$v->archivo);
        }
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"testimonio" => $testimonio,"beneficios"=>$beneficios,"redes"=>$redes));
    }

    public function sstma(){
        $configuracion = ConfiguracionSstma::orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/sstma/'.$v->archivo);
            if($v->id==2){
                $v->url_imagen = asset('image-web/sstma/'.$v->imagen);
                $v->url_archivo = asset('image-web/sstma/'.$v->archivo);
            }
        }

        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"redes"=>$redes));
    }


    public function trasvaseQuienes(){
        $configuracion = ConfiguracionTrasvase::where('id','<',9)->whereNotIn('id',['6'])->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
            if($v->id==2){
                $v->url_archivo = asset('image-web/trasvase/'.$v->imagen);
            }
        }
        $infraestructura = InfraestructuraTrasvase::orderBy('orden','asc')->get();
        foreach($infraestructura as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
        }

        $gobernanza = GobernanzaTrasvase::orderBy('orden','asc')->get();
        foreach($gobernanza as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
        }
        $medio = MedioTrasvase::orderBy('orden','asc')->limit(3)->get();
        foreach($medio as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
        }
        $responsabilidad = ResponsabilidadTrasvase::orderBy('orden','asc')->get();
        foreach($responsabilidad as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
        }
        $carrusel = CarruselTrasvase::orderBy('orden','asc')->get();
        foreach($carrusel as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
        }
        $construccion = ConstruccionTrasvase::orderBy('orden','asc')->get();
        foreach($construccion as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
        }
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"infraestructura" => $infraestructura,"gobernanza"=>$gobernanza,"esListaGobernanza"=>count($gobernanza)==1?false:true,"medio"=>$medio,"responsabilidad"=>$responsabilidad,"carrusel"=>$carrusel,"construccion"=>$construccion,"redes"=>$redes));
    }

    public function trasvaseInversionistas(){
        $configuracion = ConfiguracionTrasvase::where('id','=',9)->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
        }
        $categorias = CategoriaIrrigacion::orderBy('orden','asc')->get();
        foreach($categorias as $x=>$y){
            $analisis = AnalisisTrasvase::where('categoria_id',$y->id)->orderBy('anio','asc')->orderBy('trimestre','asc')->get();
            foreach($analisis as $k=>$v){
                $v->url_archivo = asset('image-web/trasvase/'.$v->archivo);
            }
            $y->years = $analisis;
        }
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"categories" => $categorias,"redes"=>$redes));
    }

    public function trasvaseDirectorio(){
        $configuracion = ConfiguracionTrasvase::where('id','=',10)->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
        }
        $directorio = DirectorioTrasvase::orderBy('orden','asc')->get();
        foreach($directorio as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
            $v->cv = asset('image-web/trasvase/'.$v->cv);
            //$v->url_cv = asset('image-web/trasvase/'.$v->cv);
        }

        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"directorio" => $directorio,"redes"=>$redes));
    }

    public function trasvaseJuntas(){
        $configuracion = ConfiguracionTrasvase::whereIn('id',[6,11])->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            if($v->id==6){
                $v->url_archivo = asset('image-web/trasvase/'.$v->archivo);
            }else{
                $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
            }
        }
        $directorio = DirectorioTrasvase::orderBy('orden','asc')->get();
        foreach($directorio as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
            $v->cv = asset('image-web/trasvase/'.$v->cv);
            //$v->url_cv = asset('image-web/trasvase/'.$v->cv);
        }

        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"directorio" => $directorio,"redes"=>$redes));
    }

    public function trasvaseGaleria(){
        $configuracion = ConfiguracionTrasvase::whereIn('id',[12])->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
        }
        $galeria = GaleriaTrasvase::orderBy('orden','asc')->get();
        foreach($galeria as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
        }

        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"galeria" => $galeria,"redes"=>$redes));
    }

    public function trasvaseVisitas(){
        $configuracion = ConfiguracionTrasvase::whereIn('id',[13])->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/trasvase/'.$v->archivo);
        }
        $visitas = VisitaTrasvase::orderBy('orden','asc')->get();
        foreach($visitas as $k=>$v){
            $galeria = VisitaTrasvaseImagen::where('visita_id','=',$v->id)->orderBy('id')->select('id','archivo')->get();
            foreach($galeria as $m=>$n){
                $n->url_imagen = asset('image-web/trasvase/'.$n->archivo);
            }
            $v->galeria = $galeria ;
        }

        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"visitas" => $visitas,"redes"=>$redes));
    }

    public function irrigacionQuienes(){
        $configuracion = ConfiguracionIrrigacion::where('id','<',8)->whereNotIn('id',['6'])->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
            if($v->id==2){
                $v->url_archivo = asset('image-web/irrigacion/'.$v->imagen);
            }
        }
        $infraestructura = InfraestructuraIrrigacion::orderBy('orden','asc')->get();
        foreach($infraestructura as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
        }

        $gobernanza = GobernanzaIrrigacion::orderBy('orden','asc')->get();
        foreach($gobernanza as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
        }
        $medio = MedioIrrigacion::orderBy('orden','asc')->limit(3)->get();
        foreach($medio as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
        }
        $responsabilidad = ResponsabilidadIrrigacion::orderBy('orden','asc')->get();
        foreach($responsabilidad as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
        }
        $carrusel = CarruselIrrigacion::orderBy('orden','asc')->get();
        foreach($carrusel as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
        }
        $construccion = ConstruccionIrrigacion::orderBy('orden','asc')->get();
        foreach($construccion as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
        }
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"infraestructura" => $infraestructura,"gobernanza"=>$gobernanza,"esListaGobernanza"=>count($gobernanza)==1?false:true,"medio"=>$medio,"responsabilidad"=>$responsabilidad,"carrusel"=>$carrusel,"presa"=>$construccion,"redes"=>$redes));
    }

    public function irrigacionInversionistas(){
        $configuracion = ConfiguracionIrrigacion::where('id','=',9)->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
        }
        $categorias = CategoriaIrrigacion::orderBy('orden','asc')->get();
        foreach($categorias as $x=>$y){
            $analisis = AnalisisIrrigacion::where('categoria_id',$y->id)->orderBy('anio','asc')->orderBy('trimestre','asc')->get();
            foreach($analisis as $k=>$v){
                if($v->id>=156 && $v->id<=159){
                    $v->url_archivo = $v->trimestre;
                    $v->trimestre = "Link";
                }else{
                    $v->url_archivo = asset('image-web/irrigacion/'.$v->archivo);
                }
            }
            $y->years = $analisis;
        }

        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"categories" => $categorias,"redes"=>$redes));
    }

    public function irrigacionDirectorio(){
        $configuracion = ConfiguracionIrrigacion::where('id','=',10)->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
        }
        $directorio = DirectorioIrrigacion::orderBy('orden','asc')->get();
        foreach($directorio as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
            $v->cv = asset('image-web/irrigacion/'.$v->cv);
            //$v->url_cv = asset('image-web/irrigacion/'.$v->cv);
        }

        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"directorio" => $directorio,"redes"=>$redes));
    }

    public function irrigacionJuntas(){
        $configuracion = ConfiguracionIrrigacion::whereIn('id',[6,11])->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            if($v->id==6){
                $v->url_archivo = asset('image-web/irrigacion/'.$v->archivo);
            }else{
                $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
            }
        }
        $directorio = DirectorioIrrigacion::orderBy('orden','asc')->get();
        foreach($directorio as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
            $v->cv = asset('image-web/irrigacion/'.$v->cv);
            //$v->url_cv = asset('image-web/irrigacion/'.$v->cv);
        }

        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"directorio" => $directorio,"redes"=>$redes));
    }

    public function irrigacionGaleria(){
        $configuracion = ConfiguracionIrrigacion::whereIn('id',[12])->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
        }
        $galeria = GaleriaIrrigacion::orderBy('orden','asc')->get();
        foreach($galeria as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
        }

        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"galeria" => $galeria,"redes"=>$redes));
    }

    public function irrigacionVisitas(){
        $configuracion = ConfiguracionIrrigacion::whereIn('id',[13])->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/irrigacion/'.$v->archivo);
        }
        $visitas = VisitaIrrigacion::orderBy('orden','asc')->get();
        foreach($visitas as $k=>$v){
            $galeria = VisitaIrrigacionImagen::where('visita_id','=',$v->id)->orderBy('id')->select('id','archivo')->get();
            foreach($galeria as $m=>$n){
                $n->url_imagen = asset('image-web/irrigacion/'.$n->archivo);
            }
            $v->galeria = $galeria ;
        }

        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"visitas" => $visitas,"redes"=>$redes));
    }

    public function noticias(){
        $noticias = Blog::orderBy('orden','asc')->get();
        foreach($noticias as $k=>$v){
            $v->url_imagen = asset('image-web/blog/'.$v->archivo);
            $galeria = BlogImagen::where('blog_id','=',$v->id)->orderBy('id')->select('id','archivo')->get();
            foreach($galeria as $m=>$n){
                $n->url_imagen = asset('image-web/blog/'.$n->archivo);
            }
            $v->galeria = $galeria ;
        }
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("noticias"=>$noticias,"redes"=>$redes));
    }

    public function redes(){
        $configuracion = ConfiguracionComunicaciones::whereIn('id',[1])->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/comunicaciones/'.$v->archivo);
        }
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"redes"=>$redes));
    }

    public function influencer(){
        $configuracion = ConfiguracionComunicaciones::whereIn('id',[2])->orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/comunicaciones/'.$v->archivo);
        }
        $influencer = Influencer::orderBy('orden','asc')->get();
        foreach($influencer as $k=>$v){
            $v->url_imagen = asset('image-web/comunicaciones/'.$v->archivo);
        }
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"influencer"=>$influencer,"redes"=>$redes));
    }

    public function linea(){
        $configuracion = ConfiguracionLinea::orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/linea/'.$v->archivo);
        }
        $link = LinkLinea::orderBy('orden','asc')->get();
        
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"link"=>$link,"redes"=>$redes));
    }

    public function noticiasSlug($slug){
        $noticias = Blog::orderBy('orden','asc')->where('slug','like',$slug)->get();
        foreach($noticias as $k=>$v){
            $v->url_imagen = asset('image-web/blog/'.$v->archivo);
            $galeria = BlogImagen::where('blog_id','=',$v->id)->orderBy('id')->select('id','archivo')->get();
            foreach($galeria as $m=>$n){
                $n->url_imagen = asset('image-web/blog/'.$n->archivo);
            }
            $v->galeria = $galeria ;
        }
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("noticias"=>$noticias,"redes"=>$redes));
    }

    public function contacto(){
        $configuracion = ConfiguracionContacto::orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/contacto/'.$v->archivo);
        }
        
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"redes"=>$redes));
    }

    public function integridad(){
        $configuracion = ConfiguracionIntegridad::orderBy('id','asc')->get();
        foreach($configuracion as $k=>$v){
            $v->url_imagen = asset('image-web/integridad/'.$v->archivo);
        }
        
        $redes = Configuracion::orderBy('id','asc')->select('id','nombre',DB::raw('valor as link'))->get();
        return json_encode(array("configuracion"=>$configuracion,"redes"=>$redes));
    }
}
