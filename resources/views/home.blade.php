@extends('layouts.back.master')

@section('page_title','Inicio')
@section('breadcrumbs')
    {!! Breadcrumbs::render('home') !!}
@stop

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
       @include('shared.reglamento_2018')
    </div>

@endsection
