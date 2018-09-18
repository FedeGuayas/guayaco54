@extends('layouts.back.master')

@section('page_title','Inicio')
@section('breadcrumbs')
    {!! Breadcrumbs::render('home') !!}
@stop

@section('content')


    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        @if ($perfil===false)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h4 class="alert-heading mb-2">Bienvenid@!</h4>
                <p>Antes de continuar debe terminar su perfil.</p>
                <hr>
                <p class="mb-0">Esta acción la podrá realizar en el menú superior derecho donde aparece su nombre.</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

        @endif

        @include('shared.reglamento_2018')
    </div>

@endsection

@push('scripts')
<script>


</script>

@endpush
