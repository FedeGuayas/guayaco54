{{--Agregar asociado, nuevo perfil sin cuenta de usuario--}}
<div class="modal fade customscroll" id="asociado-add" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asociadoAddModalTitle">Agregar asociado</h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Cerrar" data-toggle="tooltip"
                        data-placement="bottom" title=""
                        data-original-title="Cerrar Ventana">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-0">
                <div class="task-list-form">
                    <ul>
                        <li>
                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Nombres</label>
                                        <input class="form-control form-control-lg" type="text">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Apellidos</label>
                                        <input class="form-control form-control-lg" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Email</label>
                                        <input class="form-control form-control-lg" type="email">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Identificación</label>
                                        <input class="form-control form-control-lg" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Fecha de Nacimiento</label>
                                        <input class="form-control form-control-lg date-picker"
                                               data-language='es' type="text" onkeydown='return false;'>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Genero</label>
                                        <select class="selectpicker form-control form-control-lg"
                                                data-style="btn-outline-secondary btn-lg" id="gen_add">
                                            <option selected>Hombre</option>
                                            <option>Mujer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="col-md-4">Discapacitado</label>
                                        <div class="col-md-8">
                                            <div class="d-flex">
                                                <div class="custom-control custom-radio mb-5 mr-20">
                                                    <input type="radio" id="discapacidad-si-add" name="discapacidad"
                                                           class="custom-control-input">
                                                    <label class="custom-control-label weight-400"
                                                           for="discapacidad-si-add">Si</label>
                                                </div>
                                                <div class="custom-control custom-radio mb-5">
                                                    <input type="radio" id="discapacidad-no-add" name="discapacidad"
                                                           checked class="custom-control-input">
                                                    <label class="custom-control-label weight-400"
                                                           for="discapacidad-no-add">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Teléfono</label>
                                        <input class="form-control form-control-lg" type="text">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                    <label>Dirección</label>
                                        <textarea class="form-control"></textarea>
                                    </div>
                                </div>


                            </form>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Agregar</button>
                <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">Cerrar
                </button>
            </div>
        </div>
    </div>
</div>