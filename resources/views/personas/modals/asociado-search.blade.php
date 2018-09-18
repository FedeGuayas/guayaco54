{{--Buscar asociado, nuevo perfil sin cuenta de usuario--}}
<div class="modal fade customscroll" id="asociado-search" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asociadoSearchModalTitle">Buscar asociado</h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Cerrar" data-toggle="tooltip"
                        data-placement="bottom"
                        data-original-title="Cerra Ventana"
                        id="cerrar-modal">
                    <span aria-hidden="true">&times</span>
                </button>
            </div>

            {!! Form::open(['route' => 'perfil-asociado.store', 'method' => 'post', 'autocomplete'=> 'off','id'=>'form-asociadoSearch' ]) !!}
            {!! Form::hidden('persona_id_show',null,['id'=>'persona_id_show']) !!}
            <div class="modal-body pd-0">
                <div class="task-list-form">
                    <ul>
                        <li>
                            <div class="form-group row">
                                <label class="col-md-3">Identificación</label>
                                <div class="input-group col-md-8">
                                    {!! Form::text('search-doc',null,['class'=>'form-control','placeholder'=>'Cédula o Pasaporte','id'=>'search-doc','aria-label'=>'Buscar']) !!}
                                    <span class="input-group-btn">
                                        <button type="submit" id="search" name="search"
                                                class="btn btn-outline-primary"
                                                data-toggle="tooltip"
                                                data-placement="top" title="Buscar">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-md-4">Nombres</label>
                                <div class="col-md-8">
                                    {!! Form::text('nombres-show',null,['class'=>'form-control','id'=>'nombres-show','readonly']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4">Apellidos</label>
                                <div class="col-md-8">
                                    {!! Form::text('apellidos-show',null,['class'=>'form-control',
                                    'id'=>'apellidos-show','readonly']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" id="addNames" disabled>
                                        <label class="custom-control-label" for="addNames">Seleccionar asociado</label>
                                    </div>
                                    <small class="form-text text-muted" id="info-asociado" hidden>Esto le permitira vincular el perfil de un amigo para poder inscribirlo</small>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-primary" id="aceptar-vinculado" disabled><i class="ti-check-box"></i> Agregar Asociado</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>