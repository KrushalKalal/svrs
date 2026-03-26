<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Member Code</th>
                <th>Status</th>
                <th>Refer Member</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $member)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $member->full_name }}</td>
                    <td><span class="badge bg-primary">{{ $member->member_code }}</span></td>
                    <td>
                        @if($member->status == 1)
                            <span class="badge bg-success">Active</span>
                        @elseif($member->status == 0)
                            <span class="badge bg-danger">Inactive</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($member->is_refer_member)
                            <span class="badge bg-success"><i class="ti ti-check"></i> Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </td>
                    <td>{{ $member->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No Level {{ $level }} referrals yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>