<reply :attributes="{{$reply}}" inline-template>

    <div class="card border-secondary mb-3">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a href="{{route('profile', $thread->owner)}}">
                        {{$reply->owner->name}}
                    </a> said ... {{$reply->created_at->diffForHumans()}}
                </h5>

                <div>
                    <form method="POST" action="/replies/{{$reply->id}}/favorites">
                        @csrf
                        <button type="submit" class="btn btn-secondary" {{$reply->isFavorited() ? 'disabled' : ''}}>
                            {{$reply->favorites_count}}
                            {{str_plural('Favorite', $reply->favorites_count)}}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-sm btn-primary" @click="update()">Update</button>
                <button class="btn btn-sx btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>

        @can('update', $reply)
            <div class="card-footer level">
                <button class="btn btn-secondary btn-sm mr-2" @click="editing = true">Edit</button>

                <form action="{{route('reply.destroy', [$reply->id])}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">DELETE</button>
                </form>

            </div>
        @endcan
    </div>

</reply>

