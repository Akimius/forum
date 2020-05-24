@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create a new thread</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                            <form method="POST" action="{{route('threads.store')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <input type="text" value="{{old('title')}}" class="form-control"
                                           id="title" name="title" placeholder="Title here" required>
                                </div>

                                <div class="form-group">
                                    <label for="channelName">Select channel:</label>
                                    <select class="form-control" id="channelName" name="channel_id" required>
                                        <option value="">Choose one ...</option>
                                        @foreach($channels as $channel)
                                            <option value="{{$channel->id}}"
                                                    {{old('channel_id') == $channel->id ? 'selected' : ''}}>
                                                {{$channel->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="body">Body:</label>
                                    <textarea placeholder="Thread description here" rows="8" name="body" id="body"
                                              class="form-control" required>{{old('body')}}</textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Publish a thread</button>
                                </div>
                                <div class="form-group">
                                    @if(count($errors))
                                        <ul class="alert alert-danger">
                                            @foreach($errors->all() as $error)
                                                <li>{{$error}}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="6LeOnfsUAAAAAB8_e8a4dre6SPJ1kCuoqw1yk_WT"></div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
