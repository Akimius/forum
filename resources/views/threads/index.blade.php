@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="h3 card-header font-weight-bold text-primary">Forum threads</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @include('threads._list')
                        {{$threads->render()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
