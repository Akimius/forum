@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-secondary font-weight-bold">
            {{$profileUser->name}}
            {{--<small>Since {{$profileUser->created_at->diffForHumans()}}</small>--}}
        </h2>

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
                <p>There are no activities for this user yet</p>
            @endforelse
        </div>
        {{$threads->links()}}
    </div>
@endsection
