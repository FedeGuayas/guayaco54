<div class="tab-pane fade show active height-100-p" id="setting" role="tabpanel">
    <div class="profile-setting">
        <div class="row">
            <div class="col-md-6">
                {!! Form::model($persona, ['route' => ['personas.update', $persona->id],'method'=>'PUT','class'=>'form_noEnter','id'=>'personal-form','autocomplete'=> 'off']) !!}
                <ul class="profile-edit-list">
                    <li class="weight-500 col-md-12">
                        <h4 class="text-blue mb-20">Editar sus Datos Personales</h4>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-5">
                                <input type="checkbox" class="custom-control-input"
                                       id="editar-personal">
                                <label class="custom-control-label weight-400"
                                       for="editar-personal">Editar
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nombres: *</label>
                            {!! Form::text('nombres',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
                        </div>
                        <div class="form-group">
                            <label>Apellidos: *</label>
                            {!! Form::text('apellidos',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            {!! Form::email('email',null,['class'=>'form-control','placeholder'=>'Email','style'=>'text-transform: lowercase','required']) !!}
                            <small class="form-text text-muted">
                                Se utilizará por defecto para la facturación, no es el de
                                inicio de sessión, aunque puede utilizar el mismo.
                            </small>
                        </div>
                        <div class="form-group">
                            <label>Identificación: *</label>
                            {!! Form::text('num_doc',null,['class'=>'form-control','placeholder'=>'Cédula o Pasaporte','style'=>'text-transform: uppercase','required']) !!}
                        </div>
                        <div class="form-group">
                            <label>Fecha de Nacimiento: *</label>
                            {!! Form::text('fecha_nac',null,['class'=>'form-control date-picker','onkeydown'=>'return false;','required','data-language'=>'es','data-date-format'=> 'yyyy-mm-dd','data-position'=>'right bottom']) !!}
                        </div>
                        <div class="form-group">
                            <label>Genero: *</label>
                            {!! Form::select('gen', ['MASCULINO' => 'Masculino', 'FEMENINO' => 'Femenino'],$persona->gen, ['class'=>'selectpicker show-tick form-control required','data-style'=>'btn-outline-primary','value'=>'{{ old("gen") }}','required']) !!}
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Discapacitado: *</label>
                                    <div class="d-flex">
                                        <div class="custom-control custom-radio mb-5 mr-20">
                                            <input type="radio" id="discapacidad-si"
                                                   name="discapacitado"
                                                   class="custom-control-input" value="si"
                                                   @if( $persona->discapacitado==\App\Persona::DISCAPACITADO) checked @endif>
                                            <label class="custom-control-label weight-400"
                                                   for="discapacidad-si">Si</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-5">
                                            <input type="radio" id="discapacidad-no"
                                                   name="discapacitado"
                                                   class="custom-control-input" value="no"
                                                   @if( $persona->discapacitado==\App\Persona::NO_DISCAPACITADO) checked @endif>
                                            <label class="custom-control-label weight-400"
                                                   for="discapacidad-no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Perfil privado? *</label>
                                    <div class="d-flex">
                                        <div class="custom-control custom-radio mb-5 mr-20">
                                            <input type="radio" id="privacidad-si"
                                                   name="privado"
                                                   class="custom-control-input" value="si"
                                                   @if($persona->privado==\App\Persona::PERFIL_PRIVADO) checked @endif>
                                            <label class="custom-control-label weight-400"
                                                   for="privacidad-si">Si</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-5">
                                            <input type="radio" id="privacidad-no"
                                                   name="privado"
                                                   class="custom-control-input" value="no"
                                                   @if($persona->privado==\App\Persona::PERFIL_PUBLICO) checked @endif>
                                            <label class="custom-control-label weight-400"
                                                   for="privacidad-no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Teléfono</label>
                            {!! Form::text('telefono',null,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label>Dirección</label>
                            <textarea class="form-control required" id="direccion"
                                      name="direccion"
                                      style="text-transform: uppercase"
                                      required>{{ $persona->direccion}}</textarea>
                        </div>
                        <small class="form-text text-danger">
                            * Campos Obligatorios
                        </small>
                        <div class="form-group mb-0">
                            <input type="submit" class="btn btn-primary"
                                   value="Actualizar Información">
                        </div>
                    </li>
                </ul>
                {!! Form::close() !!}
            </div>

            <div class="col-md-6">
                {!! Form::open(['route' => ['user.password.update', Auth::user()->id],'method'=>'PUT','class'=>'form_noEnter','id'=>'cuenta-form','autocomplete'=> 'off']) !!}
                <ul class="profile-edit-list">

                    <li class="weight-500 col-md-12">
                        <h4 class="text-blue mb-20">Editar datos de la cuenta de
                            acceso</h4>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-5">
                                <input type="checkbox" class="custom-control-input"
                                       id="editar-cuenta">
                                <label class="custom-control-label weight-400"
                                       for="editar-cuenta">Editar Cuenta
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Contraseña actual: *</label>
                            {!! Form::password('password',['class'=>'form-control']) !!}
                            <small class="form-text text-muted">
                                La contraseña con que inicio sessión, debe confirmarla para
                                cambiar el correo o crear una contraseña nueva
                            </small>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            {!! Form::email('email_new',Auth::user()->email,['class'=>'form-control','placeholder'=>'Email de su cuenta','style'=>'text-transform: lowercase']) !!}
                            <small class="form-text text-muted">
                                Este correo es con el que accede a su cuenta si lo
                                cambia deberá hacerlo por uno válido al que tenga acceso
                                para posteriormente poder validarlo, sino perdera el acceso
                                a su cuenta <br>
                                Dejelo en blanco si no desea cambiarlo.
                            </small>
                        </div>
                        <div class="form-group">
                            <label>Nueva contraseña: *</label>
                            {!! Form::password('password_new',['class'=>'form-control']) !!}
                            <small class="form-text text-muted">
                                La contraseña nueva debe contener al menos 6 caracteres
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Confirmar contraseña: *</label>
                            {!! Form::password('password_new_confirmation',['class'=>'form-control']) !!}
                            <small class="form-text text-muted">
                                Debe conincidir con la contraseña nueva
                            </small>
                        </div>

                        <small class="form-text text-danger">
                            * Campos Obligatorios
                        </small>
                        <div class="form-group mb-0">
                            <input type="submit" class="btn btn-primary"
                                   value="Salvar y Actualizar">
                        </div>
                    </li>
                </ul>
                {!! Form::close() !!}

                <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                    <div class="clearfix mb-20">
                        <div class="pull-left">
                            <h4 class="text-blue">Imagen de usuario</h4>
                        </div>
                    </div>

                    {!! Form::open(['route' => 'user.avatar.upload', 'class'=>'dropzone','id'=>'my-dropzone','files'=>'true','method'=>'post']) !!}
                    <div class="fallback">
                        {!! Form::file('file') !!}
                        {{--<input type="file" name="avatar" id="avatar" />--}}
                    </div>
                    <button type="submit" class="btn btn-outline-success"
                            id="submit-avatar">Subir
                    </button>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>
</div>