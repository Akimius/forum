{{-- Editing the question. --}}
<div class="card mb-3 border-secondary" v-if="editing">
    <div class="h5 font-weight-bold card-header">
        <div class="level">
            <input type="text" class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <textarea class="form-control" rows="5" v-model="form.body"></textarea>
        </div>
    </div>
    <div class="card-footer">
        <div class="level">
            <button class="btn btn-xs level-item" @click="editing = true" v-show="! editing">Edit</button>
            <button class="btn btn-primary btn-xs level-item" @click="updateThread()">Update</button>
            <button class="btn btn-xs level-item" @click="resetForm()">Cancel</button>

            @can ('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>
            @endcan

        </div>
    </div>
</div>



{{-- Viewing the question. --}}
<div class="card mb-3 border-secondary" v-else>
    <div class="h5 font-weight-bold card-header">
        <div class="level">
            <div>
                <img src="{{asset($thread->owner->avatar_path)}}" alt="avatar"
                     width="25" height="25"
                     class="mr-2">
            </div>

            <span class="flex">
                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
{{--                {{ $thread->title }}--}}
                <span v-text="form.title"></span>
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

    <div class="card-body" v-text="body"></div>

    <div class="card-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-outline-secondary" @click="editing = true">Edit</button>
    </div>

</div>