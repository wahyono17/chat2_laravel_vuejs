@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body" id="app">

                    <!--
                    <all-contacts :user="{{ auth()->user() }}"></all-contacts>
                    -->
                </div>
            </div>
        </div>
            
    </div>
    <div class="">
            @foreach($contacts as $contact)
                <h6>Nama : <a href="/chatwith/{{$contact->id}}">{{$contact->name}}</a></h6>
                <h6>Email : {{$contact->email}}</h6>
            @endforeach        
    </div>
</div>
@endsection
