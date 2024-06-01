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
                    <div class="m-b-10"><b>Likes List ({{ $users->count() }})</b></div>

                    <ul class="friend-list clearfix">
                        @foreach ($users as $user)
                            <li>
                                <a href="{{ route('profile', $user->id) }}">
                                    <div class="friend-img"><img src="{{ $user->photo }}" alt="" /></div>
                                    <div class="friend-info">
                                        <h4>{{ $user->name }}</h4>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                        <li>
                            {!! $users->links('pagination::bootstrap-4') !!}

                        </li>
                     
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection