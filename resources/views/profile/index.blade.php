@extends('layouts.app')
@section('content')
<section class="bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-4 mb-sm-5">
                <div class="card card-style1 border-0">
                    <div class="card-body p-1-9 p-sm-2-3 p-md-6 p-lg-7">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <img src="{{$user->photo}}" alt="..." width="200" height="200">
                            </div>
                            <div class="col-lg-6 px-xl-10">
                                <div class="bg-secondary d-lg-inline-block mb-2 py-1-9 px-1-9 px-sm-6 mb-1-9 rounded">
                                    <h3 class="h2 text-white px-5">{{$user->name}}</h3>
                                </div>
                                <ul class="list-unstyled mb-1-9">
                                    <li class="mb-2 mb-xl-3 display-28"><span class="display-26 text-secondary me-2 font-weight-600">Name:</span> {{$user->name}}</li>
                                    <li class="mb-2 mb-xl-3 display-28"><span class="display-26 text-secondary me-2 font-weight-600">Email:</span> {{$user->email}}</li>
                                    <li class="mb-2 mb-xl-3 display-28"><span class="display-26 text-secondary me-2 font-weight-600">
                                        @if (Auth::user()->id == $user->id)
                                        <a class="btn btn-primary" href="{{route('profile.edit')}}">
                                            Edit My Profile
                                        </a>

                                        <a class="btn btn-danger" href="{{route('profile.edit.password')}}">
                                            Update Password
                                        </a>
                                        @endif
                                    </li>
                                </ul>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-4 mb-sm-5">
                <div>
                    <span class="section-title text-primary mb-3 mb-sm-4">Bio</span>
                    <p>{{$user->bio ?? "---"}}</p>
                </div>
            </div>
            <div class="col-lg-12">
              
            </div>
        </div>
    </div>
</section>
@endsection