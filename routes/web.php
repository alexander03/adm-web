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
if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    // Ignores notices and reports all other kinds... and warnings
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/asd', function () {
    return view('auth.login');
});

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});
//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});
//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});
//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});
//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});
//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('webservices/home', 'WebServicesController@home')->middleware('cors');
Route::get('webservices/conocenos', 'WebServicesController@conocenos')->middleware('cors');
Route::get('webservices/trasvase/quienes-somos', 'WebServicesController@trasvaseQuienes')->middleware('cors');
Route::get('webservices/trasvase/inversionistas', 'WebServicesController@trasvaseInversionistas')->middleware('cors');
Route::get('webservices/trasvase/directorio', 'WebServicesController@trasvaseDirectorio')->middleware('cors');
Route::get('webservices/trasvase/juntas', 'WebServicesController@trasvaseJuntas')->middleware('cors');
Route::get('webservices/trasvase/galeria', 'WebServicesController@trasvaseGaleria')->middleware('cors');
Route::get('webservices/trasvase/visitas', 'WebServicesController@trasvaseVisitas')->middleware('cors');
Route::get('webservices/irrigacion/quienes-somos', 'WebServicesController@irrigacionQuienes')->middleware('cors');
Route::get('webservices/irrigacion/inversionistas', 'WebServicesController@irrigacionInversionistas')->middleware('cors');
Route::get('webservices/irrigacion/directorio', 'WebServicesController@irrigacionDirectorio')->middleware('cors');
Route::get('webservices/irrigacion/juntas', 'WebServicesController@irrigacionJuntas')->middleware('cors');
Route::get('webservices/irrigacion/galeria', 'WebServicesController@irrigacionGaleria')->middleware('cors');
Route::get('webservices/irrigacion/visitas', 'WebServicesController@irrigacionVisitas')->middleware('cors');
Route::get('webservices/noticias', 'WebServicesController@noticias')->middleware('cors');
Route::get('webservices/redes', 'WebServicesController@redes')->middleware('cors');
Route::get('webservices/influencer', 'WebServicesController@influencer')->middleware('cors');
Route::get('webservices/linea-etica', 'WebServicesController@linea')->middleware('cors');
Route::get('webservices/noticias/{slug}', 'WebServicesController@noticiasSlug')->middleware('cors');
Route::get('webservices/contacto', 'WebServicesController@contacto')->middleware('cors');
Route::get('webservices/integridad', 'WebServicesController@integridad')->middleware('cors');
Route::get('webservices/sstma', 'WebServicesController@sstma')->middleware('cors');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function () {
        return View::make('layouts.master');
    });
    Route::post('categoriaopcionmenu/buscar', 'CategoriaopcionmenuController@buscar')->name('categoriaopcionmenu.buscar');
    Route::get('categoriaopcionmenu/eliminar/{id}/{listarluego}', 'CategoriaopcionmenuController@eliminar')->name('categoriaopcionmenu.eliminar');
    Route::resource('categoriaopcionmenu', 'CategoriaopcionmenuController', array('except' => array('show')));

    Route::post('opcionmenu/buscar', 'OpcionmenuController@buscar')->name('opcionmenu.buscar');
    Route::get('opcionmenu/eliminar/{id}/{listarluego}', 'OpcionmenuController@eliminar')->name('opcionmenu.eliminar');
    Route::resource('opcionmenu', 'OpcionmenuController', array('except' => array('show')));

    Route::post('tipousuario/buscar', 'TipousuarioController@buscar')->name('tipousuario.buscar');
    Route::get('tipousuario/obtenerpermisos/{listar}/{id}', 'TipousuarioController@obtenerpermisos')->name('tipousuario.obtenerpermisos');
    Route::post('tipousuario/guardarpermisos/{id}', 'TipousuarioController@guardarpermisos')->name('tipousuario.guardarpermisos');
    Route::get('tipousuario/eliminar/{id}/{listarluego}', 'TipousuarioController@eliminar')->name('tipousuario.eliminar');
    Route::resource('tipousuario', 'TipousuarioController', array('except' => array('show')));

    Route::post('usuario/buscar', 'UsuarioController@buscar')->name('usuario.buscar');
    Route::get('usuario/eliminar/{id}/{listarluego}', 'UsuarioController@eliminar')->name('usuario.eliminar');
    Route::resource('usuario', 'UsuarioController', array('except' => array('show')));
    Route::get('usuario/personautocompletar/{searching}', 'UsuarioController@personautocompletar')->name('usuario.personautocompletar');
    Route::get('usuario/cambiarcaja', 'UsuarioController@cambiarcaja')->name('usuario.cambiarcaja');


    /* Zona -> Modalidad */
    Route::post('modalidad/buscar', 'ModalidadController@buscar')->name('modalidad.buscar');
    Route::get('modalidad/eliminar/{id}/{listarluego}', 'ModalidadController@eliminar')->name('modalidad.eliminar');
    Route::resource('modalidad', 'ModalidadController', array('except' => array('show')));

    /* SUCURSAL */
    Route::post('sucursal/buscar', 'sucursalController@buscar')->name('sucursal.buscar');
    Route::get('sucursal/eliminar/{id}/{listarluego}', 'sucursalController@eliminar')->name('sucursal.eliminar');
    Route::resource('sucursal', 'sucursalController', array('except' => array('show')));


    /* PERSONA */
    Route::post('persona/buscar', 'PersonaController@buscar')->name('persona.buscar');
    Route::get('persona/eliminar/{id}/{listarluego}', 'PersonaController@eliminar')->name('persona.eliminar');
    Route::get('persona/import', 'PersonaController@import')->name('persona.import');
    Route::post('persona/saveimport', 'PersonaController@saveimport')->name('persona.saveimport');
    Route::resource('persona', 'PersonaController', array('except' => array('show')));
    Route::post('persona/buscarDNI', 'PersonaController@buscarDNI')->name('persona.buscarDNI');
    Route::post('persona/buscarRUC', 'PersonaController@buscarRUC')->name('persona.buscarRUC');


    /* VENTA */
    Route::post('venta/buscar', 'VentaController@buscar')->name('venta.buscar');
    Route::get('venta/eliminar/{id}/{listarluego}', 'VentaController@eliminar')->name('venta.eliminar');
    Route::resource('venta', 'VentaController');
    Route::post('venta/buscarproducto', 'VentaController@buscarproducto')->name('venta.buscarproducto');
    Route::post('venta/buscarproductobarra', 'VentaController@buscarproductobarra')->name('venta.buscarproductobarra');
    Route::post('venta/generarNumero', 'VentaController@generarNumero')->name('venta.generarNumero');
    Route::get('venta/personautocompletar/{searching}', 'VentaController@personautocompletar')->name('venta.personautocompletar');
    Route::post('venta/imprimirVenta', 'VentaController@imprimirVenta')->name('venta.imprimirVenta');
    Route::post('venta/declarar', 'VentaController@declarar')->name('venta.declarar');
    Route::get('venta/verpdf/{id}', 'VentaController@pdfTicket')->name('venta.verpdf');

    /* Home-Slider */
    Route::post('home2/buscar', 'Home2Controller@buscar')->name('home2.buscar');
    Route::get('home2/eliminar/{id}/{listarluego}', 'Home2Controller@eliminar')->name('home2.eliminar');
    Route::resource('home2', 'Home2Controller', array('except' => array('show')));

    /* Home-Seccion */
    Route::post('seccion-home/buscar', 'SeccionHomeController@buscar')->name('seccion-home.buscar');
    Route::get('seccion-home/eliminar/{id}/{listarluego}', 'SeccionHomeController@eliminar')->name('seccion-home.eliminar');
    Route::resource('seccion-home', 'SeccionHomeController', array('except' => array('show')));

    /* Blog */
    Route::post('blog/buscar', 'BlogController@buscar')->name('blog.buscar');
    Route::get('blog/eliminar/{id}/{listarluego}', 'BlogController@eliminar')->name('blog.eliminar');
    Route::resource('blog', 'BlogController', array('except' => array('show')));
 
    /* Blog Imagen */
    Route::post('blog-imagen/buscar', 'BlogImagenController@buscar')->name('blog-imagen.buscar');
    Route::get('blog-imagen/eliminar/{id}/{listarluego}', 'BlogImagenController@eliminar')->name('blog-imagen.eliminar');
    Route::resource('blog-imagen', 'BlogImagenController', array('except' => array('show')));

    /* Configuracion - Trasvase */
    Route::post('configuracion-trasvase/buscar', 'ConfiguracionTrasvaseController@buscar')->name('configuracion-trasvase.buscar');
    Route::get('configuracion-trasvase/eliminar/{id}/{listarluego}', 'ConfiguracionTrasvaseController@eliminar')->name('configuracion-trasvase.eliminar');
    Route::resource('configuracion-trasvase', 'ConfiguracionTrasvaseController', array('except' => array('show')));

    /* Infraestructura - Trasvase */
    Route::post('infraestructura-trasvase/buscar', 'InfraestructuraTrasvaseController@buscar')->name('infraestructura-trasvase.buscar');
    Route::get('infraestructura-trasvase/eliminar/{id}/{listarluego}', 'InfraestructuraTrasvaseController@eliminar')->name('infraestructura-trasvase.eliminar');
    Route::resource('infraestructura-trasvase', 'InfraestructuraTrasvaseController', array('except' => array('show')));

    /* Gobernanza - Trasvase */
    Route::post('gobernanza-trasvase/buscar', 'GobernanzaTrasvaseController@buscar')->name('gobernanza-trasvase.buscar');
    Route::get('gobernanza-trasvase/eliminar/{id}/{listarluego}', 'GobernanzaTrasvaseController@eliminar')->name('gobernanza-trasvase.eliminar');
    Route::resource('gobernanza-trasvase', 'GobernanzaTrasvaseController', array('except' => array('show')));

    /* Medio - Trasvase */
    Route::post('medio-trasvase/buscar', 'MedioTrasvaseController@buscar')->name('medio-trasvase.buscar');
    Route::get('medio-trasvase/eliminar/{id}/{listarluego}', 'MedioTrasvaseController@eliminar')->name('medio-trasvase.eliminar');
    Route::resource('medio-trasvase', 'MedioTrasvaseController', array('except' => array('show')));

    /* Responsabilidad - Trasvase */
    Route::post('responsabilidad-trasvase/buscar', 'ResponsabilidadTrasvaseController@buscar')->name('responsabilidad-trasvase.buscar');
    Route::get('responsabilidad-trasvase/eliminar/{id}/{listarluego}', 'ResponsabilidadTrasvaseController@eliminar')->name('responsabilidad-trasvase.eliminar');
    Route::resource('responsabilidad-trasvase', 'ResponsabilidadTrasvaseController', array('except' => array('show')));

    /* Analisis - Trasvase */
    Route::post('analisis-trasvase/buscar', 'AnalisisTrasvaseController@buscar')->name('analisis-trasvase.buscar');
    Route::get('analisis-trasvase/eliminar/{id}/{listarluego}', 'AnalisisTrasvaseController@eliminar')->name('analisis-trasvase.eliminar');
    Route::resource('analisis-trasvase', 'AnalisisTrasvaseController', array('except' => array('show')));

    /* Directorio - Trasvase */
    Route::post('directorio-trasvase/buscar', 'DirectorioTrasvaseController@buscar')->name('directorio-trasvase.buscar');
    Route::get('directorio-trasvase/eliminar/{id}/{listarluego}', 'DirectorioTrasvaseController@eliminar')->name('directorio-trasvase.eliminar');
    Route::resource('directorio-trasvase', 'DirectorioTrasvaseController', array('except' => array('show')));

    /* Galeria - Trasvase */
    Route::post('galeria-trasvase/buscar', 'GaleriaTrasvaseController@buscar')->name('galeria-trasvase.buscar');
    Route::get('galeria-trasvase/eliminar/{id}/{listarluego}', 'GaleriaTrasvaseController@eliminar')->name('galeria-trasvase.eliminar');
    Route::resource('galeria-trasvase', 'GaleriaTrasvaseController', array('except' => array('show')));

    /* Visita - Trasvase */
    Route::post('visita-trasvase/buscar', 'VisitaTrasvaseController@buscar')->name('visita-trasvase.buscar');
    Route::get('visita-trasvase/eliminar/{id}/{listarluego}', 'VisitaTrasvaseController@eliminar')->name('visita-trasvase.eliminar');
    Route::resource('visita-trasvase', 'VisitaTrasvaseController', array('except' => array('show')));

    /* Visita Trasvase Imagen */
    Route::post('visita-trasvase-imagen/buscar', 'VisitaTrasvaseImagenController@buscar')->name('visita-trasvase-imagen.buscar');
    Route::get('visita-trasvase-imagen/eliminar/{id}/{listarluego}', 'VisitaTrasvaseImagenController@eliminar')->name('visita-trasvase-imagen.eliminar');
    Route::resource('visita-trasvase-imagen', 'VisitaTrasvaseImagenController', array('except' => array('show')));

    /* Carrusel - Trasvase */
    Route::post('carrusel-trasvase/buscar', 'CarruselTrasvaseController@buscar')->name('carrusel-trasvase.buscar');
    Route::get('carrusel-trasvase/eliminar/{id}/{listarluego}', 'CarruselTrasvaseController@eliminar')->name('carrusel-trasvase.eliminar');
    Route::resource('carrusel-trasvase', 'CarruselTrasvaseController', array('except' => array('show')));

    /* Construccion - Trasvase */
    Route::post('construccion-trasvase/buscar', 'ConstruccionTrasvaseController@buscar')->name('construccion-trasvase.buscar');
    Route::get('construccion-trasvase/eliminar/{id}/{listarluego}', 'ConstruccionTrasvaseController@eliminar')->name('construccion-trasvase.eliminar');
    Route::resource('construccion-trasvase', 'ConstruccionTrasvaseController', array('except' => array('show')));

    /* Configuracion - Conocenos */
    Route::post('configuracion-conocenos/buscar', 'ConfiguracionConocenosController@buscar')->name('configuracion-conocenos.buscar');
    Route::get('configuracion-conocenos/eliminar/{id}/{listarluego}', 'ConfiguracionConocenosController@eliminar')->name('configuracion-conocenos.eliminar');
    Route::resource('configuracion-conocenos', 'ConfiguracionConocenosController', array('except' => array('show')));

    /* Testimonio - Conocenos */
    Route::post('testimonio-conocenos/buscar', 'TestimonioConocenosController@buscar')->name('testimonio-conocenos.buscar');
    Route::get('testimonio-conocenos/eliminar/{id}/{listarluego}', 'TestimonioConocenosController@eliminar')->name('testimonio-conocenos.eliminar');
    Route::resource('testimonio-conocenos', 'TestimonioConocenosController', array('except' => array('show')));

    /* Beneficio - Conocenos */
    Route::post('beneficio-conocenos/buscar', 'BeneficioConocenosController@buscar')->name('beneficio-conocenos.buscar');
    Route::get('beneficio-conocenos/eliminar/{id}/{listarluego}', 'BeneficioConocenosController@eliminar')->name('beneficio-conocenos.eliminar');
    Route::resource('beneficio-conocenos', 'BeneficioConocenosController', array('except' => array('show')));

    /* Configuracion - Footer */
    Route::post('configuracion/buscar', 'ConfiguracionController@buscar')->name('configuracion.buscar');
    Route::get('configuracion/eliminar/{id}/{listarluego}', 'ConfiguracionController@eliminar')->name('configuracion.eliminar');
    Route::resource('configuracion', 'ConfiguracionController', array('except' => array('show')));

    /* Configuracion - Comunicaciones */
    Route::post('configuracion-comunicaciones/buscar', 'ConfiguracionComunicacionesController@buscar')->name('configuracion-comunicaciones.buscar');
    Route::get('configuracion-comunicaciones/eliminar/{id}/{listarluego}', 'ConfiguracionComunicacionesController@eliminar')->name('configuracion-comunicaciones.eliminar');
    Route::resource('configuracion-comunicaciones', 'ConfiguracionComunicacionesController', array('except' => array('show')));

    /* Influencer */
    Route::post('influencer/buscar', 'InfluencerController@buscar')->name('influencer.buscar');
    Route::get('influencer/eliminar/{id}/{listarluego}', 'InfluencerController@eliminar')->name('influencer.eliminar');
    Route::resource('influencer', 'InfluencerController', array('except' => array('show')));

    /* Configuracion - Linea */
    Route::post('configuracion-linea/buscar', 'ConfiguracionLineaController@buscar')->name('configuracion-linea.buscar');
    Route::get('configuracion-linea/eliminar/{id}/{listarluego}', 'ConfiguracionLineaController@eliminar')->name('configuracion-linea.eliminar');
    Route::resource('configuracion-linea', 'ConfiguracionLineaController', array('except' => array('show')));

    /* Link - Linea */
    Route::post('link-linea/buscar', 'LinkLineaController@buscar')->name('link-linea.buscar');
    Route::get('link-linea/eliminar/{id}/{listarluego}', 'LinkLineaController@eliminar')->name('link-linea.eliminar');
    Route::resource('link-linea', 'LinkLineaController', array('except' => array('show')));

    /* Configuracion - Irrigacion */
    Route::post('configuracion-irrigacion/buscar', 'ConfiguracionIrrigacionController@buscar')->name('configuracion-irrigacion.buscar');
    Route::get('configuracion-irrigacion/eliminar/{id}/{listarluego}', 'ConfiguracionIrrigacionController@eliminar')->name('configuracion-irrigacion.eliminar');
    Route::resource('configuracion-irrigacion', 'ConfiguracionIrrigacionController', array('except' => array('show')));

    /* Infraestructura - Irrigacion */
    Route::post('infraestructura-irrigacion/buscar', 'InfraestructuraIrrigacionController@buscar')->name('infraestructura-irrigacion.buscar');
    Route::get('infraestructura-irrigacion/eliminar/{id}/{listarluego}', 'InfraestructuraIrrigacionController@eliminar')->name('infraestructura-irrigacion.eliminar');
    Route::resource('infraestructura-irrigacion', 'InfraestructuraIrrigacionController', array('except' => array('show')));

    /* Gobernanza - Irrigacion */
    Route::post('gobernanza-irrigacion/buscar', 'GobernanzaIrrigacionController@buscar')->name('gobernanza-irrigacion.buscar');
    Route::get('gobernanza-irrigacion/eliminar/{id}/{listarluego}', 'GobernanzaIrrigacionController@eliminar')->name('gobernanza-irrigacion.eliminar');
    Route::resource('gobernanza-irrigacion', 'GobernanzaIrrigacionController', array('except' => array('show')));

    /* Medio - Irrigacion */
    Route::post('medio-irrigacion/buscar', 'MedioIrrigacionController@buscar')->name('medio-irrigacion.buscar');
    Route::get('medio-irrigacion/eliminar/{id}/{listarluego}', 'MedioIrrigacionController@eliminar')->name('medio-irrigacion.eliminar');
    Route::resource('medio-irrigacion', 'MedioIrrigacionController', array('except' => array('show')));

    /* Responsabilidad - Irrigacion */
    Route::post('responsabilidad-irrigacion/buscar', 'ResponsabilidadIrrigacionController@buscar')->name('responsabilidad-irrigacion.buscar');
    Route::get('responsabilidad-irrigacion/eliminar/{id}/{listarluego}', 'ResponsabilidadIrrigacionController@eliminar')->name('responsabilidad-irrigacion.eliminar');
    Route::resource('responsabilidad-irrigacion', 'ResponsabilidadIrrigacionController', array('except' => array('show')));

    /* Analisis - Irrigacion */
    Route::post('analisis-irrigacion/buscar', 'AnalisisIrrigacionController@buscar')->name('analisis-irrigacion.buscar');
    Route::get('analisis-irrigacion/eliminar/{id}/{listarluego}', 'AnalisisIrrigacionController@eliminar')->name('analisis-irrigacion.eliminar');
    Route::resource('analisis-irrigacion', 'AnalisisIrrigacionController', array('except' => array('show')));

    /* Directorio - Irrigacion */
    Route::post('directorio-irrigacion/buscar', 'DirectorioIrrigacionController@buscar')->name('directorio-irrigacion.buscar');
    Route::get('directorio-irrigacion/eliminar/{id}/{listarluego}', 'DirectorioIrrigacionController@eliminar')->name('directorio-irrigacion.eliminar');
    Route::resource('directorio-irrigacion', 'DirectorioIrrigacionController', array('except' => array('show')));

    /* Galeria - Irrigacion */
    Route::post('galeria-irrigacion/buscar', 'GaleriaIrrigacionController@buscar')->name('galeria-irrigacion.buscar');
    Route::get('galeria-irrigacion/eliminar/{id}/{listarluego}', 'GaleriaIrrigacionController@eliminar')->name('galeria-irrigacion.eliminar');
    Route::resource('galeria-irrigacion', 'GaleriaIrrigacionController', array('except' => array('show')));

    /* Visita - Irrigacion */
    Route::post('visita-irrigacion/buscar', 'VisitaIrrigacionController@buscar')->name('visita-irrigacion.buscar');
    Route::get('visita-irrigacion/eliminar/{id}/{listarluego}', 'VisitaIrrigacionController@eliminar')->name('visita-irrigacion.eliminar');
    Route::resource('visita-irrigacion', 'VisitaIrrigacionController', array('except' => array('show')));

    /* Visita Irrigacion Imagen */
    Route::post('visita-irrigacion-imagen/buscar', 'VisitaIrrigacionImagenController@buscar')->name('visita-irrigacion-imagen.buscar');
    Route::get('visita-irrigacion-imagen/eliminar/{id}/{listarluego}', 'VisitaIrrigacionImagenController@eliminar')->name('visita-irrigacion-imagen.eliminar');
    Route::resource('visita-irrigacion-imagen', 'VisitaIrrigacionImagenController', array('except' => array('show')));

    /* Carrusel - Irrigacion */
    Route::post('carrusel-irrigacion/buscar', 'CarruselIrrigacionController@buscar')->name('carrusel-irrigacion.buscar');
    Route::get('carrusel-irrigacion/eliminar/{id}/{listarluego}', 'CarruselIrrigacionController@eliminar')->name('carrusel-irrigacion.eliminar');
    Route::resource('carrusel-irrigacion', 'CarruselIrrigacionController', array('except' => array('show')));

    /* Construccion - Irrigacion */
    Route::post('construccion-irrigacion/buscar', 'ConstruccionIrrigacionController@buscar')->name('construccion-irrigacion.buscar');
    Route::get('construccion-irrigacion/eliminar/{id}/{listarluego}', 'ConstruccionIrrigacionController@eliminar')->name('construccion-irrigacion.eliminar');
    Route::resource('construccion-irrigacion', 'ConstruccionIrrigacionController', array('except' => array('show')));

    /* Configuracion - Contacto */
    Route::post('configuracion-contacto/buscar', 'ConfiguracionContactoController@buscar')->name('configuracion-contacto.buscar');
    Route::get('configuracion-contacto/eliminar/{id}/{listarluego}', 'ConfiguracionContactoController@eliminar')->name('configuracion-contacto.eliminar');
    Route::resource('configuracion-contacto', 'ConfiguracionContactoController', array('except' => array('show')));

    /* Configuracion - Integridad */
    Route::post('configuracion-integridad/buscar', 'ConfiguracionIntegridadController@buscar')->name('configuracion-integridad.buscar');
    Route::get('configuracion-integridad/eliminar/{id}/{listarluego}', 'ConfiguracionIntegridadController@eliminar')->name('configuracion-integridad.eliminar');
    Route::resource('configuracion-integridad', 'ConfiguracionIntegridadController', array('except' => array('show')));

    /* Configuracion - Sstma */
    Route::post('configuracion-sstma/buscar', 'ConfiguracionSstmaController@buscar')->name('configuracion-sstma.buscar');
    Route::get('configuracion-sstma/eliminar/{id}/{listarluego}', 'ConfiguracionSstmaController@eliminar')->name('configuracion-sstma.eliminar');
    Route::resource('configuracion-sstma', 'ConfiguracionSstmaController', array('except' => array('show')));
});
