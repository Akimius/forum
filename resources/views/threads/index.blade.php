@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Forum threads</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                            @foreach($threads as $thread)
                                <atricle>
                                    <h4>
                                        <a href="{{$thread->path()}}">
                                        {{$thread->title}}
                                        </a>
                                    </h4>
                                    <div class="body">
                                        {{$thread->body}}
                                    </div>
                                </atricle>
                                <hr>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
