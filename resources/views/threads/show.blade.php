@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="#">
                            {{$thread->owner->name}}
                        </a>
                        posted:
                        <strong>
                             {{$thread->title}}
                        </strong>
                    </div>

                    <div class="card-body">
                        {{$thread->body}}
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($thread->replies as $reply)

                    @include('threads.reply')

                @endforeach
            </div>
        </div>

        @if(auth()->check())
        <div class="row justify-content-center">
            <div class="col-md-8">

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
            </div>
        </div>
        @else
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <p class="text-center">
                        Please <a href="{{route('login')}}">sign in</a> to participate in forum
                    </p>
                </div>
            </div>
        @endif

    </div>
@endsection
