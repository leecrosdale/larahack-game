@extends('layouts.app')

@section('content')

<div id="app">
    <map-component :map_id="{{ \Illuminate\Support\Facades\Auth::user()->tile->city->id }}"></map-component>
</div>

@endsection
