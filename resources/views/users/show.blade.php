@extends('layouts.app')

@section('content')
    <div>
        <div class="d-flex w-100 justify-content-start">
            <button onclick="window.location='{{ route('users.index')}}'" class="btn btn-outline-info" >Users</button>
        </div>
        <div class="d-flex w-100 justify-content-center align-items-center">
            <div class="card" style="width: 24rem;">
                <div class="w-100 d-flex justify-content-center">
                    <img src="{{$user['avatar']}}" class="card-img-top" alt="...">
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex gap-3">
                            <span class="link-primary">Email:</span>
                            <span class="link-info">{{$user['email']}}</span>
                        </li>

                        <li class="list-group-item d-flex gap-3">
                            <span class="link-primary">First Name:</span>
                            <span class="link-info">{{$user['firstname']}}</span>
                        </li>

                        <li class="list-group-item d-flex gap-3">
                            <span class="link-primary">Last Name:</span>
                            <span class="link-info">{{$user['lastname']}}</span>
                        </li>

                        <li class="list-group-item d-flex gap-3">
                            <span class="link-primary">Prefix Name:</span>
                            <span class="link-info">{{$user['prefixname']}}</span>
                        </li>

                        <li class="list-group-item d-flex gap-3">
                            <span class="link-primary">Middle Name:</span>
                            <span class="link-info">{{$user['middlename']}}</span>
                        </li>

                        <li class="list-group-item d-flex gap-3">
                            <span class="link-primary">Suffix Name:</span>
                            <span class="link-info">{{$user['suffixname']}}</span>
                        </li>

                        <li class="list-group-item d-flex gap-3">
                            <span class="link-primary">Full Name:</span>
                            <span class="link-info">{{$user['fullname']}}</span>
                        </li>

                        <li class="list-group-item d-flex gap-3">
                            <span class="link-primary">Type:</span>
                            <span class="link-info">{{$user['type']}}</span>
                        </li>
                    </ul>

                    <div class="d-flex mt-2 gap-1 w-100 justify-content-between">
                        <button onclick="window.location='{{ route('users.edit', $user['id']) }}'" class="btn btn-outline-success" >Edit</button>
                        <form method="POST" id="delete-form" action="{{route('users.delete', [$user->id])}}">
                            {{csrf_field()}}
                            <button class="btn btn-outline-danger">Temporary Delete</button>
                            <input type="hidden" name="_method" value="DELETE">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
