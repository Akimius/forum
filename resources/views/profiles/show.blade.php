@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="card-header">
            <h1>
                {{$profileUser->name}}
                <small>Since {{$profileUser->created_at->diffForHumans()}}</small>
            </h1>
        </div>

        @foreach($threads as $thread)
            <div class="card">
                <div class="card-header">
                    <a href="{{route('profile', $thread->owner)}}">
                        {{$thread->owner->name}}
                    </a>
                    posted:
                    <strong>
                        <a href="{{$thread->path()}}">{{$thread->title}}</a>
                        ... {{$thread->created_at->diffForHumans()}}
                    </strong>
                </div>

                <div class="card-body">
                    {{$thread->body}}
                </div>
            </div>
        @endforeach

        {{$threads->links()}}


    </div>

@endsection