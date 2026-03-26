@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || My Rewards')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">My Rewards</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">My Rewards</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Referral Count Banner --}}
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="ti ti-users fs-4 me-2"></i>
            <div>
                <strong>Your Active Refer Members (Level 1): {{ $eligibility['referral_count'] }}</strong>
                <span class="text-muted ms-2">Only activated members who have paid &#8377;519 count toward
                    milestones.</span>
            </div>
        </div>

        <div class="row">
            @foreach($eligibility['milestones'] as $milestone)
                <div class="col-md-4 mb-4">
                    <div
                        class="card h-100 {{ ($milestone['claim'] && $milestone['claim']->status == 1) ? 'border-success' : '' }}">
                        <div
                            class="card-header d-flex justify-content-between align-items-center
                                    {{ ($milestone['claim'] && $milestone['claim']->status == 1) ? 'bg-success text-white' : '' }}">
                            <h5 class="mb-0">{{ $milestone['tier']->name }}</h5>
                            @if($milestone['claim'] && $milestone['claim']->status == 1)
                                <span class="badge bg-white text-success">Claimed</span>
                            @elseif($milestone['claim'] && $milestone['claim']->status == 2)
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <span style="font-size:2.5rem;font-weight:700;color:#f0a500;">
                                    {{ number_format($milestone['tier']->g_coins) }}
                                </span>
                                <span class="text-muted fs-6"> G-Coins</span><br>
                                <small class="text-muted">= &#8377;{{ number_format($milestone['tier']->g_coins / 10, 2) }} INR
                                    value</small>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small>Progress</small>
                                    <small>{{ $milestone['progress'] }} / {{ $milestone['tier']->required_referrals }}</small>
                                </div>
                                <div class="progress" style="height:10px;">
                                    <div class="progress-bar bg-success"
                                        style="width:{{ ($milestone['progress'] / $milestone['tier']->required_referrals) * 100 }}%">
                                    </div>
                                </div>
                                <small class="text-muted">Need {{ $milestone['tier']->required_referrals }} active refer
                                    members</small>
                            </div>

                            {{-- Claim Button / Status --}}
                            @if($milestone['claim'] && $milestone['claim']->status == 1)
                                <div class="alert alert-success py-2 mb-0">
                                    <i class="ti ti-circle-check me-1"></i>
                                    Approved on {{ $milestone['claim']->approved_at?->format('d M Y') }}
                                </div>
                            @elseif($milestone['claim'] && $milestone['claim']->status == 2)
                                <div class="alert alert-warning py-2 mb-0">
                                    <i class="ti ti-clock me-1"></i> Claim under review
                                </div>
                            @elseif($milestone['can_claim'])
                                <button class="btn btn-success w-100 claim-btn" data-tier="{{ $milestone['tier']->id }}"
                                    data-name="{{ $milestone['tier']->name }}"
                                    data-coins="{{ number_format($milestone['tier']->g_coins) }}">
                                    <i class="ti ti-gift me-1"></i> Claim {{ number_format($milestone['tier']->g_coins) }} G-Coins
                                </button>
                            @elseif($milestone['eligible'] && !$milestone['can_claim'])
                                <div class="alert alert-secondary py-2 mb-0">
                                    <i class="ti ti-lock me-1"></i> {{ $milestone['reason'] }}
                                </div>
                            @else
                                <div class="alert alert-light py-2 mb-0 text-muted">
                                    <i class="ti ti-lock me-1"></i>
                                    Need {{ $milestone['tier']->required_referrals - $milestone['progress'] }} more referrals
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Info Box --}}
        <div class="card mt-2">
            <div class="card-header">
                <h5 class="mb-0">How It Works</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <i class="ti ti-users fs-2 text-primary"></i>
                        <p class="mt-2"><strong>10 Referrals</strong><br>First Reward: 10,000 G-Coins</p>
                    </div>
                    <div class="col-md-4">
                        <i class="ti ti-trophy fs-2 text-success"></i>
                        <p class="mt-2"><strong>15 Referrals</strong><br>Option A: 5,000 G-Coins</p>
                    </div>
                    <div class="col-md-4">
                        <i class="ti ti-award fs-2 text-warning"></i>
                        <p class="mt-2"><strong>20 Referrals</strong><br>Option B: 10,000 G-Coins</p>
                    </div>
                </div>
                <div class="alert alert-info mt-2 mb-0">
                    <strong>10 G-Coins = &#8377;1 INR</strong> &nbsp;|&nbsp;
                    Lifetime max: 25,000 G-Coins = &#8377;2,500 &nbsp;|&nbsp;
                    Must claim in order: First Reward &rarr; Option A &rarr; Option B
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on('click', '.claim-btn', function () {
            var tierId = $(this).data('tier');
            var name = $(this).data('name');
            var coins = $(this).data('coins');

            Swal.fire({
                title: 'Claim ' + coins + ' G-Coins?',
                text: name + ' — Your claim will be reviewed by admin.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Claim!',
                confirmButtonColor: '#28a745',
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('member.claim.reward') }}",
                        type: 'POST',
                        data: { tier_id: tierId, _token: "{{ csrf_token() }}" },
                        success: function (res) {
                            if (res.status) {
                                toastr.success(res.message);
                                setTimeout(function () { location.reload(); }, 1500);
                            } else {
                                toastr.error(res.message);
                            }
                        },
                        error: function () { toastr.error('Something went wrong'); }
                    });
                }
            });
        });
    </script>
@endsection