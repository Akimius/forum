@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="container">
            <avatar-form :user="{{ $profileUser }}"></avatar-form>
            <br>
        </div>

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
