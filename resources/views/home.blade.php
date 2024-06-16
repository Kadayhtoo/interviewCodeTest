@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('User List') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if($users)
                        <table class="table">
                            <tr>
                                <th>No</th>
                                <th>Profile Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>ID Document</th>
                                <th>role</th>
                                <th>Action</th>
                            </tr>
                            @foreach($users as $key => $user)
                                @if($user->role != 'admin')
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td>
                                            <img src="{{ route('photo', ['image'=> $user->profile_photo]) }}" width="100px" height="100px" alt="Profile Photo">
                                        </td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td><img src="{{ route('photo', ['image'=> $user->id_document]) }}" width="100px" height="100px" alt="Id Document"></td>
                                        <td>user</td>
                                        <td>
                                            @if(Auth::user()->role=='admin')
                                                @if(!$user->is_approved) 
                                                    <a href="{{ route('approve', ['id'=> $user->id]) }}" class="button"> Approve </button>
                                                @else
                                                    Active
                                                @endif
                                            @else
                                                @if(!$user->is_approved) 
                                                    Pending
                                                @else
                                                    Active
                                                @endif
                                            @endif
                                            
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    @else
                       Ther has no users!!!
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
