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
                        @forelse($threads as $thread)
                            <article>
                                <div class="card mb-3 border-secondary p-2">
                                <div class=" card-header level">
                                    <h4 class="flex">
                                        <a href="{{$thread->path()}}">
                                            {{$thread->title}}
                                        </a>
                                    </h4>
                                    <a href="{{$thread->path()}}">
                                        <strong>{{$thread->replies_count}} {{str_plural('reply', $thread->replies_count)}}</strong>
                                    </a>
                                </div>
                                <div class="body p-2">
                                    {{$thread->body}}
                                </div>
                                </div>
                            </article>
                            @empty
                            <p>There are no relevant results at this time</p>
                        @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
