@extends('master')
@section('title')
    Quản lý khách hàng
@endsection
@section('content')
    <h1 class="text-center">QUẢN LÝ KHÁCH HÀNG</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <a class="btn btn-info" href="{{ route('customers.create') }}">Create</a>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">NAME</th>
                    <th scope="col">EMAIL</th>
                    <th scope="col">PHONE</th>
                    <th scope="col">ADDRESS</th>
                    <th scope="col">IS_ACTIVE</th>
                    <th scope="col">IMAGE</th>
                    <th scope="col">Created_at </th>
                    <th scope="col">Updated_at </th>
                    <th scope="col">Action </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $customer)
                    <tr class="">
                        <td scope="row">{{ $customer->id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->address }}</td>
                        <td>
                            @if ($customer->is_active)
                                <span class="btn btn-primary">YES</span>
                            @else
                                <span class="btn btn-danger">NO</span>
                            @endif
                        </td>
                        <td>
                            @if ($customer->image)
                                <img src="{{ asset('storage/' . $customer->image) }}" alt="" width="150px">
                            @else
                                <span class="btn btn-danger">!IMG</span>
                            @endif
                        </td>
                        <td>{{ $customer->created_at }}</td>
                        <td>{{ $customer->updated_at }}</td>
                        <td><a class="btn btn-info" href="{{ route('customers.show', $customer->id) }}">Show</a></td>
                        <td><a class="btn btn-warning" href="{{ route('customers.edit', $customer->id) }}">Edit</a></td>
                        <td>
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
