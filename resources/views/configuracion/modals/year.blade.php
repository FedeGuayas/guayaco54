{{--Agregar asociado, nuevo perfil sin cuenta de usuario--}}
<div class="modal fade customscroll" id="modal-year" tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="yearModal">Agregar año</h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Cerrar" data-toggle="tooltip"
                        data-placement="bottom" title=""
                        data-original-title="Cerrar Ventana">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['route' => 'ejercicios.store', 'method' => 'post', 'autocomplete'=> 'off']) !!}
            <div class="modal-body pd-0">
                <div class="task-list-form">
                    <ul>
                        <li>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Año</label>
                                        {!! Form::number('year',\Carbon\Carbon::now()->year,['class'=>'form-control','required']) !!}
                                        <small class="form-text">Ej: 2017, 2018, 2019, etc..</small>
                                    </div>
                                </div>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>