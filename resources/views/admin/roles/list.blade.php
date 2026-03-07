@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Roles List')
@section('content')
<div class="content">

    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Roles List</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Roles</li>
                    <li class="breadcrumb-item active" aria-current="page">Role List</li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Role List</h4>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered" id="rolesTable">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Role Name</th>
                                    <th style="width: 80%;">Permissions</th>
                                    <th style="width: 20%;">Action</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td>{{ ucfirst($role->name) }}</td>
                                    <td>
                                        @can('Edit Roles')
                                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-info">Edit</a>
                                        @endcan
                                    </td>
                                    <td>
                                        @foreach($role->permissions as $perm)
                                            <span class="badge bg-success m-1">{{ $perm->name }}</span>
                                        @endforeach
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

{{-- Datatable + Delete Script --}}
<script>
$(document).ready(function() {
    $('#rolesTable').DataTable();
});
</script>
@endsection
