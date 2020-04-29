@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="h5 font-weight-bold text-primary">
                            Trending threads (Top 5)
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($trending as $thread)
                            <li class="list-group-item">
                                <a href="{{url($thread->path)}}">
                                    {{$thread->title}}
                                </a>
                            </li>
                        @endforeach
                    </div>
{{--                    <div class="card-footer">--}}
{{--                        <div class="text-success">Trending threads</div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection
