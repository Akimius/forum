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

                            <form method="POST" action="/threads">
                                @csrf
                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Title here">
                                </div>
                                <div class="form-group">
                                    <label for="body">Body:</label>
                                    <textarea placeholder="Thread description here" rows="8" name="body" id="body"
                                              class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Publish a thread</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
