@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="level">
                        <span class="flex">
                            <a href="{{route('profile', $thread->owner)}}">
                                {{$thread->owner->name}}
                            </a>
                            posted:
                            <strong>
                                {{$thread->title}}
                            </strong>
                        </span>

                        <form action="{{$thread->path()}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>

                    </div>
                </div>

                <div class="card-body">
                    {{$thread->body}}
                </div>
            </div>

            @foreach($thread->replies as $reply)
                @include('threads.reply')
            @endforeach

        <div class="level">
            {{ $replies->links() }}
        </div>


    @if(auth()->check())

        <form method="POST" action="{{$thread->path() . '/replies'}}">
            @csrf
            <div class="form-group">
                <label for="body">Post here</label>
                <textarea placeholder="Have something to say ???" rows="2" name="body" id="body"
                          class="form-control"></textarea>
                <small id="bodyHelp" class="form-text text-muted">Please be polite.</small>
            </div>

            <button type="submit" class="btn btn-primary">Post</button>
        </form>
    @else

            <p class="text-center">
                Please <a href="{{route('login')}}">sign in</a> to participate in forum
            </p>

    @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Created by <a href="#">{{$thread->owner->name}}</a>
            </div>

            <div class="card-body">
                This thread posted <b>{{$thread->created_at->diffForHumans()}}</b>
                <p>and currently has <b>{{$thread->reply_count}}</b> {{str_plural('comment', $thread->reply_count)}}</p>
            </div>

        </div>
    </div>

</div>
@endsection
