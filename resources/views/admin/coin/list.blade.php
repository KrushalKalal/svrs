@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Coin Master')
@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Coin Master</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            Coin Master
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Coin List</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Coin List</h4>
                    <a href="{{ route('admin.create.coin') }}" class="btn btn-sm btn-primary"><i class="ti ti-plus me-2"></i>Add Coin</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Start Price</th>
                                <th>End Price</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coins as $coin)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($coin->image)
                                            <img src="{{ asset($coin->image) }}" width="50" height="50"
                                                style="object-fit:cover;">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $coin->name }}</td>
                                    <td>₹ {{ number_format($coin->start_price, 2) }}</td>
                                    <td>₹ {{ number_format($coin->end_price, 2) }}</td>
                                    <td>
                                        @if ($coin->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.coin.edit', $coin->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
