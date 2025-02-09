@extends('master')
@section('title')
    Thêm mới khách hàng
@endsection
@section('content')
    <h1 class="text-center">CHỈNH SỬA KHÁCH HÀNG</h1>

    @if (session()->has('sucsses') && session()->get('sucsses'))
        <div class="alert alert-danger" role="alert">
            {{session()->get('error')}}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container mt-3">
        <form method="POST" action="{{ route('customers.update',$data->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3 row">
                <label for="name" class="col-4 col-form-label">Name</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="name" id="name" value="{{$data->name}}" />
                </div>
            </div>
            <div class="mb-3 row">
                <label for="email" class="col-4 col-form-label">email</label>
                <div class="col-8">
                    <input type="email" class="form-control" name="email" id="email" value="{{$data->email}}" />
                </div>
            </div>
            <div class="mb-3 row">
                <label for="phone" class="col-4 col-form-label">phone</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="phone" id="phone" value="{{$data->phone}}" />
                </div>
            </div>
            <div class="mb-3 row">
                <label for="address" class="col-4 col-form-label">address</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="address" id="address" value="{{$data->address}}" />
                </div>
            </div>

            <div class="mb-3 row">
                <label for="is_active" class="col-4 col-form-label">is_active</label>
                <div class="col-8">
                    <input type="checkbox" class="form-checkbox" value="1" name="is_active" id="is_active" @checked($data->is_active)/>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="image" class="col-4 col-form-label">image</label>
                <div class="col-8">
                    <input type="file" class="form-control" name="image" id="image" />
                    <img src="{{ asset('storage/' . $data->image) }}" alt="" width="150px">
                </div>
            </div>
            <div class="text-center">
                <a class="btn btn-dark" href="{{route('customers.index')}}">Come back</a>
              <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
