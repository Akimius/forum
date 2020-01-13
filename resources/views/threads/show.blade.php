@extends('layouts.app')

@section('content')
<thread-view :initial-replies-count="{{$thread->replies_count}}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3 border-secondary">
                    <div class="h5 font-weight-bold card-header">
                        <div class="level">
                    <span class="flex">
                        <a href="{{route('profile', $thread->owner)}}">{{$thread->owner->name}}</a>
                        posted:
                        <strong>
                            <a href="{{$thread->path()}}">
                                {{$thread->title}}
                            </a>
                        </strong>
                    </span>
                            @can('update', $thread)
                                <form action="{{$thread->path()}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        {{$thread->body}}
                    </div>
                </div>

                <replies
                    :data="{{$thread->replies}}"
                         @removed ="repliesCount--"
                         @added ="repliesCount++"
                ></replies>

            </div>

            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-header">
                        Created by <a href="{{route('profile', $thread->owner)}}">{{$thread->owner->name}}</a>
                    </div>

                    <div class="card-body">
                        This thread posted <b>{{$thread->created_at->diffForHumans()}}</b>
                        <p>and currently has <b v-text="repliesCount"></b> {{str_plural('comment', $thread->reply_count)}}</p>
                    </div>

                    <div class="card-footer">
                        <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                    </div>


                </div>
            </div>

        </div>
    </div>
</thread-view>
@endsection
