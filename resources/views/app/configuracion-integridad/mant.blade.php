<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($configuracion, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		{!! Form::label('nombre', 'Nombre:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre', 'readonly' => 'true')) !!}
		</div>
	</div>
	@if($configuracion->id!=1 && $configuracion->id!=3)
	<div class="form-group">
		{!! Form::label('valor', 'Valor:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::textarea('valor', null, array('class' => 'form-control input-xs', 'id' => 'valor', 'placeholder' => 'Ingrese valor')) !!}
		</div>
	</div>
	@endif
	@if($configuracion->id==1 || $configuracion->id==3)
	<div class="form-group">
		{!! Form::label('archivo', 'Archivo:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::file('archivo', null, array('class' => 'form-control input-xs', 'id' => 'archivo', 'placeholder' => 'Ingrese archivo')) !!}
		</div>
	</div>
	@endif
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
tinymce.remove('#valor');
tinymce.init({
    selector: '#valor',
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
    $("#valor").val(ed.getContent());
}
</script>