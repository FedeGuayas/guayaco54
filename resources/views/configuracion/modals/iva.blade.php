
<div class="modal fade customscroll" id="modal-iva" tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ivaModal">Agregar iva</h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Cerrar" data-toggle="tooltip"
                        data-placement="bottom" title=""
                        data-original-title="Cerrar Ventana">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['route' => 'taxes.store', 'method' => 'post', 'autocomplete'=> 'off']) !!}
            <div class="modal-body pd-0">
                <div class="task-list-form">
                    <ul>
                        <li>
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label weight-600">Nombre:</label>
                                <div class="col-sm-6 col-md-10">
                                    {!! Form::text('nombre',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
                                    <small class="form-text">Ej: 12 %</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label weight-600">Porciento:</label>
                                <div class="col-sm-6 col-md-10">
                                    {!! Form::number('porciento',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
                                    <small class="form-text">Ej: 12</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label weight-600">Divisor:</label>
                                <div class="col-sm-6 col-md-10">
                                    {!! Form::number('divisor',null,['step'=>'0.01','min'=>'1.01','max'=>'1.99','class'=>'form-control','required']) !!}
                                    <small class="form-text">Ej: IVA 12% => 1.12  14% => 1.14</small>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Agregar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>