@extends('layouts.app')

@php
    $headerColumns = [
                ['name' => 'id', 'beValue' => 'id'],
                ['name' => 'First Name', 'beValue' => 'firstname'],
                ['name' => 'Last Name', 'beValue' => 'lastname'],
                ['name' => 'Username', 'beValue' => 'username'],
                ['name' => 'Email', 'beValue' => 'email'],
                ['name' => 'Type', 'beValue' => 'type'],
                ['name' => 'Actions', 'beValue' => 'actions']
            ];
@endphp

@section('content')
    <div>
        <div class="d-flex justify-content-between w-100 mb-5">
            <button class="btn btn-outline-info" onclick="window.location='{{ route('users.index') }}'">Users</button>
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
                <tr>
                    @for($i = 0; $i < count($headerColumns); $i++)
                        <td>
                            @if($headerColumns[$i]['beValue'] !== 'actions')
                                <div class="d-flex justify-content-center">
                                    {{$user[$headerColumns[$i]['beValue']]}}
                                </div>
                            @else
                                <div class="d-flex justify-content-center gap-2">
                                    <form method="POST" id="delete-form" action="{{route('users.restore', [$user->id])}}">
                                        {{csrf_field()}}
                                        <button class="btn btn-outline-info">
                                            Restore
                                        </button>
                                        <input type="hidden" name="_method" value="PATCH">
                                    </form>
                                    <form method="POST" id="delete-form" action="{{route('users.destroy', [$user->id])}}">
                                        {{csrf_field()}}
                                        <button class="btn btn-outline-danger">
                                            Delete
                                        </button>
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>

                                </div>
                            @endif
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
