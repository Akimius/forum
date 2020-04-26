@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-secondary font-weight-bold">
            {{$profileUser->name}}
            {{--<small>Since {{$profileUser->created_at->diffForHumans()}}</small>--}}
        </h2>
        @can('update', $profileUser)
            <div class="m-2">
                <form method="POST" action="{{route('avatar', $profileUser)}}"
                      enctype="multipart/form-data">
                    @csrf()
                    <input type="file" name="avatar">
                    <button type="submit" class="btn btn-primary">Add avatar</button>
                </form>
            </div>
            <div class="m-5">
                <img src="{{asset($profileUser->avatar_path)}}" alt="avatar" width="100" height="100">
            </div>
        @endcan

        <div class="card">
            @forelse ($activities as $date => $activity)
                <h3 class="card-header">{{ $date }}</h3>
                @foreach ($activity as $record)
                    <div class="card-body">
                        @if (view()->exists("profiles.activities.{$record->type}"))
                            @include ("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    </div>
                @endforeach
            @empty
                <div class="m-2">
                    There are no activities for this user yet
                </div>
            @endforelse
        </div>
        {{$threads->links()}}
    </div>
@endsection
