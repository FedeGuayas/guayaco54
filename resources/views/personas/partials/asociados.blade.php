<div class="tab-pane fade" id="asociados" role="tabpanel">
    <div class="pd-20 profile-task-wrap">
        <div class="container pd-0">
            <!-- Open Task start -->
            <div class="task-title row align-items-center">
                <div class="col-md-6 col-sm-12">
                    <h5>Amigos asociados</h5>
                </div>
                <div class="col-md-6 col-sm-12 pull-right text-right">
                    <a href="asociado-search" data-toggle="modal"
                       data-target="#asociado-search"
                       class="bg-light-blue btn text-blue weight-500"><i
                                class="ion-search"></i> Buscar</a>
                    <a href="{{route('perfil-asociado.create')}}"
                       class="bg-light-blue btn text-blue weight-500"><i
                                class="ion-plus-round"></i> Agregar</a>
                </div>
            </div>
            @if(count($asociados)>0)
                <div class="contact-directory-list">

                    <ul class="row">
                        @foreach($asociados as $asociado)
                            <li class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <div class="contact-directory-box">
                                    <div class="contact-dire-info text-center">
                                        <div class="contact-avatar">
                                                                <span>
                                                                    @if ($asociado->persona->privado==\App\Persona::PERFIL_PRIVADO)
                                                                        <img src="{{asset('images/default-user.jpg')}}"
                                                                             alt="" class="avatar-photo">
                                                                    @else
                                                                        @if ($asociado->persona->user['avatar'])
                                                                            <img src="{{ asset('dist/img/users/perfil/'.$asociado->persona->user['avatar'])}}"
                                                                                 alt="Foto de Usuario"
                                                                                 class="avatar-photo">
                                                                        @else
                                                                            <img src="{{asset('images/default-user.jpg')}}"
                                                                                 alt="" class="avatar-photo">
                                                                        @endif
                                                                    @endif
                                                                </span>
                                        </div>

                                        <div class="contact-name">
                                            <h5>{{$asociado->persona->getFullName()}}</h5>
                                            <p>{{$asociado->persona->num_doc}}</p>
                                            @if($asociado->persona->privado==\App\Persona::PERFIL_PRIVADO)
                                                <div class="work text-danger">
                                                    @if ($asociado->persona->discapacitado==\App\Persona::DISCAPACITADO)
                                                        <span class="badge badge-info"><i class="ti-wheelchair"></i></span>
                                                    @else
                                                        <i class="ion-android-person"></i>
                                                    @endif
                                                    Privado
                                                </div>
                                            @elseif($asociado->persona->privado==\App\Persona::PERFIL_PUBLICO)
                                                <div class="work text-success">
                                                    @if ($asociado->persona->discapacitado==\App\Persona::DISCAPACITADO)
                                                        <span class="badge badge-pill badge-primary"><i class="ti-wheelchair"></i></span>
                                                    @else
                                                        <i class="ion-android-person"></i>
                                                    @endif
                                                    Público
                                                </div>
                                            @endif
                                        </div>
                                        <div class="contact-skill">
                                            @if($asociado->persona->privado==\App\Persona::PERFIL_PUBLICO)
                                                <a href="{{route('perfil-asociado.edit',$asociado->persona_id)}}"
                                                   class="btn btn-outline-success">
                                                <i class="ti-pencil"></i>
                                                </a>
                                            @endif
                                            <a href="#"
                                               class="btn btn-outline-danger delete-asociado"
                                               data-id="{{$asociado->id}}">
                                                <i class="ti-trash" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="view-contact">
                                        <a href="{{route('inscription.create',$asociado->id)}}">Incribir</a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p> No tiene perfiles de amigos asociados</p>
        @endif
        <!-- Open Task End -->
            <!-- Buscar Asociado modal -->
        @include('personas.modals.asociado-search')
        <!-- Buscar Asociado modal End -->
            <!-- Agregar Asociado modal -->
        @include('personas.modals.asociado-add')
        <!-- Agregar Asociado modal End -->
        </div>
    </div>
</div>