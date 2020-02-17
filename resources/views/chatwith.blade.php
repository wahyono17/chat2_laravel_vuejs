@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body" id="app">

                    <conversation-with :contact="{{ $contact[0] }}" :user="{{ auth()->user() }}"></conversation-with>
                    
                </div>
            </div>
        </div>
            
    </div>
    <div class="">
           
    </div>
</div>
@endsection
