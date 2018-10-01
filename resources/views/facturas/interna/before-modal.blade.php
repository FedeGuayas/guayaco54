<div class="modal" id="facturacionMasiva" tabindex="-1" role="dialog" aria-labelledby="facturacionMasivaTitle"
     aria-describedby="Confirmar la facturación masiva">

    <div class="modal-dialog  modal-dialog-centered " role="document">
        <div class="modal-content">

            <div class="modal-header ">
                <h5 class="modal-title text-info" id="facturacionMasivaTitle">Confirmar Facturación Masiva</h5>
                <button type="button" class="close" aria-label="Cerrar" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['route' =>['admin.facturacion.masiva'], 'method'=>'post','id'=>'facturacion-form']) !!}

            <div class="modal-body">
                <div class="container-fluid">

                    <p> Se generará el archivo excel de la facturación masiva para  el período de fechas seleccionados.
                        <br>
                        Confirme para continua
                    </p>

                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('fecha_desde','Fecha Desde') !!}
                            {!! Form::text('fecha_desde', null, ['class' => 'form-control','id'=>'fecha_desde','readonly']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('fecha_hasta','Fecha Hasta') !!}
                            {!! Form::text('fecha_hasta', null, ['class' => 'form-control','id'=>'fecha_hasta','readonly']) !!}
                        </div>
                    </div>

                </div><!--./container-fluid -->
            </div><!--./modal-body -->

            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-primary" id="send_facturacion">
                    Aceptar
                </button>
                <button type="button" class="btn btn-outline-default" data-dismiss="modal"><i class="ti-close"></i>
                    Cerrar
                </button>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
</div>