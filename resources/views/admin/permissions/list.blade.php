@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Permission List')
@section('content')
<div class="content">

    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Permission List</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Permissions</li>
                    <li class="breadcrumb-item active">Permission List</li>
                </ol>
            </nav>
        </div>

        @can('Create Permissions')
        <div>
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                <i class="ti ti-plus"></i> Add Permission
            </a>           
        </div>
        @endcan
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title">Permission Table</h4></div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered" id="Permission_List">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Permission Name</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->id }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ $permission->created_at->format('d M Y') }}</td>
                                        <td>
                                            @can('Edit Permissions')
                                                <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-sm btn-info">
                                                    Edit
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#Permission_List').DataTable();
});
</script>
@endsection
