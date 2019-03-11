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

                        @forelse($threads as $thread)
                            <atricle>

                                <div class="level">
                                    <h4 class="flex">

                                        <a href="{{$thread->path()}}">
                                            {{$thread->title}}
                                        </a>
                                    </h4>
                                    <a href="{{$thread->path()}}">
                                        <strong>{{$thread->replies_count}} {{str_plural('reply', $thread->replies_count)}}</strong>
                                    </a>
                                </div>

                                <div class="body">
                                    {{$thread->body}}
                                </div>
                            </atricle>
                            <hr>
                            @empty

                            <p>There are no relevant results at this time</p>

                        @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
