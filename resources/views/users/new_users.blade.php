@extends('layouts.app')
@section('css')
    @include('users.css')
@endsection
@section('content')
    <div class="container">
        <div id="content" class="content p-0">

            <div class="col-md-12">
                <div class="tab-content p-0">
                    <div class="row">
                        <div id="search-div" class="col-md-12">
                            <form action="{{ route('new.users') }}" method="GET" class="form-inline">
                                @csrf
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Search By Name</span>
                                    </div>
                                    <input type="text" name="search" class="form-control" value="{{ old('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade active show" id="profile-friends">
                        <div class="m-b-10"><b>New Friend List ({{ $users->count() }})</b></div>
                        <ul class="friend-list clearfix">
                            @foreach ($users as $user)
                                <li>
                                    <a href="{{ route('profile', $user->id) }}">
                                        <div class="friend-img"><img src="{{ $user->photo }}" alt="" /></div>
                                        <div class="friend-info">
                                            <h4>{{ $user->name }}</h4>
                                        </div>
                                        <a class="btn btn-primary" href="{{ route('send-friend-request', $user->id) }}">
                                            Send Request
                                        </a>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
