@extends('layouts.app')
@section('css')
    @include('users.css')
@endsection
@section('content')
    <div class="container">
        <div id="content" class="content p-0">

            <div class="col-md-8">
                <div class="tab-content p-0">

                    <div class="tab-pane fade active show" id="profile-friends">
                        <div class="m-b-10"><b>Requests Friend List ({{ $users->count() }})</b></div>

                        <ul class="friend-list clearfix">
                            @foreach ($users as $user)
                                <li>
                                    <a href="{{ route('profile', $user->id) }}">
                                        <div class="friend-img"><img src="{{ $user->photo }}" alt="" /></div>
                                        <div class="friend-info">
                                            <h4>{{ $user->name }}</h4>
                                        </div>
                                        <div>
                                            <br />
                                            <form action="{{ route('request-response', [$user->id, 1]) }}" method="POST"
                                                style="display: inline-block">
                                                @csrf
                                                <button class="btn btn-success" type="submit">
                                                    Accept
                                                </button>
                                            </form>

                                            <form action="{{ route('request-response', [$user->id, 0]) }}" method="POST"
                                                style="display: inline-block">
                                                @csrf
                                                <button class="btn btn-danger" type="submit">
                                                    Refuse
                                                </button>
                                            </form>
                                        </div>

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
