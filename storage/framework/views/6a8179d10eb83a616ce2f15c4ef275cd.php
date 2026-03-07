
<?php $__env->startSection('title', config('app.name') . ' || Edit Permission'); ?>
<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Permission</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Permissions</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Permission</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Permission</h5>
                </div>
                <div class="card-body">
                    <form id="permissionEditForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($permission->id); ?>">

                        <div class="mb-3">
                            <label class="form-label">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   value="<?php echo e($permission->name); ?>" required>
                            <span class="text-danger error-name"></span>
                        </div>

                        <div class="text-end">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('View Permissions')): ?>
                            <a href="<?php echo e(route('admin.permissions.list')); ?>" class="btn btn-secondary">Cancel</a>
                            <?php endif; ?>

                            <button type="submit" id="updateBtn" class="btn btn-primary">
                                <span class="btn-text">Update Permission</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
$("#permissionEditForm").on('submit', function(e) {
    e.preventDefault();

    $("#updateBtn").attr("disabled", true);
    $("#updateBtn .btn-text").addClass('d-none');
    $("#updateBtn .spinner-border").removeClass('d-none');

    $.ajax({
        url: "<?php echo e(route('admin.permissions.update')); ?>",
        type: "POST",
        data: $(this).serialize(),

        success: function(res) {
            toastr.success("Permission updated successfully");

            $("#updateBtn").attr("disabled", false);
            $("#updateBtn .btn-text").removeClass('d-none');
            $("#updateBtn .spinner-border").addClass('d-none');

            setTimeout(() => {
                window.location.href = "<?php echo e(route('admin.permissions.list')); ?>";
            }, 800);
        },

        error: function(xhr) {
            $("#updateBtn").attr("disabled", false);
            $("#updateBtn .btn-text").removeClass('d-none');
            $("#updateBtn .spinner-border").addClass('d-none');

            if (xhr.status === 422) {
                $('.error-name').text(xhr.responseJSON.errors.name);
                toastr.error(xhr.responseJSON.errors.name);
            } else {
                toastr.error("Something went wrong!");
            }
        }
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/admin/permissions/edit.blade.php ENDPATH**/ ?>