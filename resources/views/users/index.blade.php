@extends('layouts.app')

@php
    $headerColumns = [
                ['name' => 'id', 'beValue' => 'id'],
                ['name' => 'First Name', 'beValue' => 'firstname'],
                ['name' => 'Last Name', 'beValue' => 'lastname'],
                ['name' => 'Username', 'beValue' => 'username'],
                ['name' => 'Email', 'beValue' => 'email'],
                ['name' => 'Type', 'beValue' => 'type'],
            ];
@endphp

@section('content')
    <div>
        <div class="d-flex justify-content-between w-100 mb-5">
            <button class="btn btn-outline-info" onclick="window.location='{{ route('users.trashed') }}'">Trashed</button>

            <button class="btn btn-outline-primary" onclick="window.location='{{ route('users.create') }}'">Create User</button>
        </div>
        <table class="table">
            <thead>
            <tr>
                @foreach ($headerColumns as $header)
                    <th scope="col">
                        <div class="d-flex justify-content-center">
                            {{ $header['name']  }}
                        </div>
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="c-pointer" onclick="window.location='{{ route('users.show', $user->id) }}'">
                    @for($i = 0; $i < count($headerColumns); $i++)
                        <td>
                            <div class="d-flex justify-content-center">
                                {{$user[$headerColumns[$i]['beValue']]}}
                            </div>
                        </td>
                    @endfor
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="container">
            <div class="col col-12 d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
