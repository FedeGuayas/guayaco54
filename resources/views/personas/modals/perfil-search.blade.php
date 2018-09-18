{{--Buscar perfil--}}
<div class="modal fade customscroll" id="perfil-search" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchModalTitle">Buscar perfil</h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Cerrar" data-toggle="tooltip"
                        data-placement="bottom" title=""
                        data-original-title="Cerra Ventana"
                        id="cerrar-modal">
                    <span aria-hidden="true">&times</span>
                </button>
            </div>
            {!! Form::open(['id'=>'perfil-search-form']) !!}
            {!! Form::hidden('persona_id_show',null,['id'=>'persona_id_show']) !!}
            <div class="modal-body pd-0">
                <div class="task-list-form">
                    <ul>
                        <li>
                            <div class="form-group row">
                                <label class="col-md-3">Identificación</label>
                                <div class="input-group col-md-8">
                                    <input type="text" class="form-control" id="search-doc" name="search-doc" value=""
                                           placeholder="Cédula o Pasaporte" aria-label="Buscar">
                                    <span class="input-group-btn">
                                        <button type="submit" id="search" name="search"
                                                    class="btn btn-outline-primary"
                                                    data-toggle="tooltip"
                                                    data-placement="top" title="Buscar">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </span>
                                    <small class="form-text text-muted">
                                        Puede entrar parte del documento para realizar la busquedad, sino se muestran los datos  deseados puede aumentar la cantidad de caracteres para mejorar la precisión.
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2">Nombres</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="nombres-show" id="nombres-show"
                                           readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2">Apellidos</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="apellidos-show" id="apellidos-show"
                                           readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" id="addNames" disabled>
                                        <label class="custom-control-label" for="addNames" >Seleccione para confirmar sus datos</label>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-dismiss="modal" id="addNamesAceptar" disabled>Aceptar
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>