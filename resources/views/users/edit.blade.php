@extends('layouts.app')

@section('content')

    <div class="d-flex w-100 justify-content-center">
        <div class="card" style="width: 24rem;">
            <div class="card-body">
                <form enctype="multipart/form-data" action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    <fieldset>
                        <div class="mb-3">
                            <div>
                                <input
                                    class="form-control @error('file') is-invalid @enderror"
                                    type="file"
                                    id="formFileLg"
                                    name="file"
                                >

                                @error('file')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" id="email" name="email" value="{{$user->email}}"
                                   class="form-control @error('email') is-invalid @enderror" required
                                   placeholder="Email">
                            @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" id="firstname" name="firstname" value="{{$user->firstname}}"
                                   class="form-control @error('firstname') is-invalid @enderror" required
                                   placeholder="First Name">
                            @error('firstname')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" id="lastname" name="lastname" value="{{$user->lastname}}"
                                   class="form-control" required
                                   placeholder="Last Name">

                            @error('lastname')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" value="{{$user->username}}"
                                   class="form-control" required
                                   placeholder="Username">

                            @error('username')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password (optional)</label>
                            <input type="password" name="password" id="password" class="form-control"
                                   placeholder="Password *******">

                            @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="prefixname" class="form-label">Prefix Name (optional)</label>
                            <select id="prefixname" name="prefixname" class="form-select" value="{{$user->prefixname}}">
                                <option>Mrs.</option>
                                <option>Mr.</option>
                                <option>Ms.</option>
                            </select>

                            @error('prefixname')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="middlename" class="form-label">Middle Name (optional)</label>
                            <input type="text" id="middlename" name="middlename" value="{{$user->middlename}}"
                                   class="form-control"
                                   placeholder="Middle Name">

                            @error('middlename')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="suffixname" class="form-label">Suffix Name (optional)</label>
                            <input type="text" id="suffixname" name="suffixname" value="{{$user->suffixname}}"
                                   class="form-control"
                                   placeholder="Suffix Name">
                        </div>

                        @error('suffixname')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

@endsection
