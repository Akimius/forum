@forelse($threads as $thread)
    <article>
        <div class="card mb-3 border-secondary p-2">
            <div class=" card-header level">
                <div class="flex">
                    <h4 class="flex">
                        <a href="{{ $thread->path() }}">
                            @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>
                                    {{ $thread->title }}
                                </strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>
                    <h5>Posted by:
                        <a href="{{route('profile', $thread->owner)}}">
                            {{$thread->owner->name}}
                        </a>
                    </h5>
                </div>
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