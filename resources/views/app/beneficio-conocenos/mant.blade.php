<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($home, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		{!! Form::label('titulo', 'Titulo:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('titulo', null, array('class' => 'form-control input-xs', 'id' => 'titulo', 'placeholder' => 'Ingrese titulo')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('subtitulo', 'Subtitulo:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('subtitulo', null, array('class' => 'form-control input-xs', 'id' => 'subtitulo', 'placeholder' => 'Ingrese subtitulo')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('nombre', 'Nombre:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('descripcion', 'Descripcion:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::textarea('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'descripcion', 'placeholder' => 'Ingrese descripcion')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('archivo', 'Imagen:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::file('archivo', null, array('class' => 'form-control input-xs', 'id' => 'archivo', 'placeholder' => 'Ingrese archivo')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('orden', 'Orden:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::number('orden', null, array('class' => 'form-control input-xs', 'id' => 'orden', 'placeholder' => 'Ingrese orden','min'=>'1')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check "></i> '.$boton, array('class' => 'btn btn-primary btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-undo "></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('600');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
tinymce.remove('#descripcion');
tinymce.init({
    selector: '#descripcion',
    setup: function (ed) {
        ed.on('keyup', function (e) {
            tinyMceChange(ed);
        });
        ed.on('change', function(e) {
            tinyMceChange(ed);
        });
    }
});

function tinyMceChange(ed) {
    $("#descripcion").val(ed.getContent());
}
</script>