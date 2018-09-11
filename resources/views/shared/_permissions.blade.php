<div class="card border-info">
    <div class="card-header" role="tab" id="{{ isset($title) ? str_slug($title) :  'permissionHeading' }}">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}"
           aria-expanded="{{ $closed or 'true' }}" aria-controls="dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}">
            {{ $title or 'Override Permissions' }} {!! isset($user) ? '<span class="text-danger">(' . $user->getDirectPermissions()->count() . ')</span>' : '' !!}
        </a>
    </div>
    <div id="dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}"
         class="collapse {{ $closed or 'in' }}" aria-labelledby="dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}">
        <div class="card-body">
            <div class="row">
                @foreach($permissions as $perm)
                    <?php
                    $per_found = null;

                    if (isset($role)) {
                        $per_found = $role->hasPermissionTo($perm->name);
                    }

                    if (isset($user)) {
                        $per_found = $user->hasDirectPermission($perm->name);
                    }
                    ?>

                    <div class="col-md-3">
                        <div class="checkbox">
                            <label class="{{ str_contains($perm->name, 'delete') ? 'text-danger' : '' }}">
                                {!! Form::checkbox("permissions[]", $perm->name, $per_found, isset($options) ? $options : []) !!} {{ $perm->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @can('edit_roles')
            {{--Option existe cuando es admin por lo tanto no mostrar actualizar ni eliminar, admin no se puede modificar--}}
            @if (!isset($options))
                <div class="dropdown pull-right">
                    <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown">
                        <i class="fa fa-ellipsis-h"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#">
                            <button type="submit" class="dropdown-item">
                                <i class="fa fa-edit text-success"></i> Actualizar
                            </button>
                        </a>
                        <a class="dropdown-item delete" href="#" data-id="{{$role->id}}" data-rname="{{$role->name}}"><i
                                    class="fa fa-trash-o text-danger"></i> Eliminar</a>
                    </div>
                </div>
            @endif
        @endcan
    </div>
</div>

