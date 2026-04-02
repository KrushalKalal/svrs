@extends('member.layout.app-layout')

@section('title', 'My Profile')@section('nav-back')@endsection
@section('nav-back-url', route('member.profile'))

@section('content')
    @php
        $user = auth()->user();
        $activeTab = request('tab', 'account');
    @endphp

        {{-- Segment tabs --}}
        <div style="padding:16px 20px 0;">
            <div class="segment-ctrl">
                <button class="{{ $activeTab === 'account' ? 'active' : '' }}" onclick="switchTab('account')">Account</button>
                <button class="{{ $activeTab === 'edit' ? 'active' : '' }}"    onclick="switchTab('edit')">Edit</button>
                <button class="{{ ($activeTab === 'bank' || $activeTab === 'password') ? 'active' : '' }}" onclick="switchTab('bank')">Bank</button>
            </div>
        </div>

        {{-- ── ACCOUNT TAB ── --}}
        <div id="tabAccount" class="{{ $activeTab === 'account' ? '' : 'd-none-tab' }}">
            <div style="padding:20px 20px 0;text-align:center;">
                <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:#000;margin:0 auto 12px;overflow:hidden;border:3px solid rgba(240,165,0,0.4);">
                    @if($user->profile_image)<img src="{{ asset($user->profile_image) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else{{ strtoupper(substr($user->first_name, 0, 1)) }}@endif
                </div>
                <h3 style="font-size:18px;font-weight:800;margin-bottom:3px;">{{ $user->full_name }}</h3>
                <p style="font-size:13px;color:var(--muted);margin-bottom:10px;">{{ $user->email }}</p>
                <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">
                    <div class="stat-pill gold" onclick="copyText('{{ $user->member_code }}')" style="cursor:pointer;">
                        <i class="fa fa-copy" style="font-size:10px;"></i>{{ $user->member_code }}
                    </div>
                    <div class="stat-pill {{ $user->status == 1 ? 'green' : 'gold' }}">
                        <i class="fa fa-circle" style="font-size:7px;"></i>{{ $user->status == 1 ? 'Active' : 'Pending' }}
                    </div>
                </div>
            </div>

            <div class="section-label">Account Details</div>
            <div style="margin:0 20px;" class="app-card">
                <div class="list-row">
                    <div class="list-icon blue" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i class="fa fa-user"></i></div>
                    <div class="list-body"><div class="sub" style="font-size:11px;">Full Name</div><div class="title">{{ $user->full_name }}</div></div>
                </div>
                <div class="list-row">
                    <div class="list-icon teal" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i class="fa fa-envelope"></i></div>
                    <div class="list-body"><div class="sub" style="font-size:11px;">Email</div><div class="title">{{ $user->email }}</div></div>
                </div>
                <div class="list-row">
                    <div class="list-icon green" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i class="fa fa-phone"></i></div>
                    <div class="list-body"><div class="sub" style="font-size:11px;">Mobile</div><div class="title">{{ $user->mobile }}</div></div>
                </div>
                <div class="list-row">
                    <div class="list-icon gold" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i class="fa fa-id-card"></i></div>
                    <div class="list-body"><div class="sub" style="font-size:11px;">Member Code</div><div class="title" style="font-family:'Space Mono',monospace;">{{ $user->member_code }}</div></div>
                    <button onclick="copyText('{{ $user->member_code }}')" style="background:none;border:none;color:var(--muted);cursor:pointer;padding:4px;"><i class="fa fa-copy"></i></button>
                </div>
                <div class="list-row">
                    <div class="list-icon blue" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i class="fa fa-calendar"></i></div>
                    <div class="list-body"><div class="sub" style="font-size:11px;">Joining Date</div><div class="title">{{ $user->created_at->format('d M Y') }}</div></div>
                </div>
            </div>
            <div style="height:8px;"></div>
        </div>

        {{-- ── EDIT TAB ── --}}
        <div id="tabEdit" style="display:none;">
            <div style="padding:20px 20px 0;text-align:center;">
                <div style="position:relative;width:72px;margin:0 auto 8px;">
                    <div id="avatarWrap" style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#000;overflow:hidden;border:3px solid rgba(240,165,0,0.4);">
                        @if($user->profile_image)<img id="avatarImg" src="{{ asset($user->profile_image) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else<span id="avatarInitial">{{ strtoupper(substr($user->first_name, 0, 1)) }}</span>@endif
                    </div>
                    <button type="button" onclick="document.getElementById('photoInput').click()"
                        style="position:absolute;bottom:0;right:0;width:26px;height:26px;border-radius:50%;background:var(--gold);border:none;cursor:pointer;color:#000;font-size:11px;">
                        <i class="fa fa-camera"></i>
                    </button>
                </div>
                <p style="font-size:12px;color:var(--muted);">Tap to change photo</p>
            </div>

            <div style="padding:0 20px 20px;">
                <div class="section-label" style="padding:0;margin:16px 0 14px;">Edit Profile</div>
                <form id="editProfileForm" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="photoInput" name="photo" style="display:none;" accept="image/*">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label class="input-label">First Name</label>
                            <input type="text" name="first_name" class="input-app" value="{{ $user->first_name }}">
                        </div>
                        <div>
                            <label class="input-label">Last Name</label>
                            <input type="text" name="last_name" class="input-app" value="{{ $user->last_name }}">
                        </div>
                    </div>
                    <div style="margin-bottom:16px;">
                        <label class="input-label">Mobile</label>
                        <input type="text" class="input-app" value="{{ $user->mobile }}" readonly style="color:var(--muted);">
                    </div>
                    <button type="submit" class="btn-app btn-gold" id="saveProfileBtn">Save Profile</button>
                </form>

                {{-- Change Password --}}
                <div class="section-label" style="padding:0;margin:24px 0 14px;">Change Password</div>
                <form id="changePassForm">
                    @csrf
                    <div style="margin-bottom:12px;position:relative;">
                        <label class="input-label">Current Password</label>
                        <input type="password" name="current_password" class="input-app" placeholder="Current Password" id="cp1">
                        <button type="button" onclick="togglePw('cp1',this)" style="position:absolute;right:14px;bottom:14px;background:none;border:none;color:var(--muted);cursor:pointer;"><i class="fa fa-eye-slash"></i></button>
                    </div>
                    <div style="margin-bottom:12px;position:relative;">
                        <label class="input-label">New Password</label>
                        <input type="password" name="new_password" class="input-app" placeholder="New Password" id="cp2">
                        <button type="button" onclick="togglePw('cp2',this)" style="position:absolute;right:14px;bottom:14px;background:none;border:none;color:var(--muted);cursor:pointer;"><i class="fa fa-eye-slash"></i></button>
                    </div>
                    <div style="margin-bottom:16px;position:relative;">
                        <label class="input-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="input-app" placeholder="Confirm New Password" id="cp3">
                        <button type="button" onclick="togglePw('cp3',this)" style="position:absolute;right:14px;bottom:14px;background:none;border:none;color:var(--muted);cursor:pointer;"><i class="fa fa-eye-slash"></i></button>
                    </div>
                    <button type="submit" class="btn-app btn-outline-gold" id="savePassBtn">
                        <i class="fa fa-lock"></i> Update Password
                    </button>
                </form>
            </div>
        </div>

        {{-- ── BANK TAB ── --}}
        <div id="tabBank" style="display:none;padding:20px;">
            <div class="section-label" style="padding:0;margin:0 0 14px;">Bank Information</div>
            <form id="bankForm">
                @csrf
                <div style="margin-bottom:12px;">
                    <label class="input-label">Account Holder Name</label>
                    <input type="text" name="account_name" class="input-app" value="{{ $bankdetail->account_holder_name ?? '' }}" placeholder="Account Holder Name">
                </div>
                <div style="margin-bottom:12px;">
                    <label class="input-label">Account Number</label>
                    <input type="text" name="account_number" class="input-app" value="{{ $bankdetail->account_number ?? '' }}" placeholder="Account Number">
                </div>
                <div style="margin-bottom:12px;">
                    <label class="input-label">IFSC Code</label>
                    <input type="text" name="ifsc_code" class="input-app" value="{{ $bankdetail->ifsc_code ?? '' }}" placeholder="IFSC Code">
                </div>
                <div style="margin-bottom:12px;">
                    <label class="input-label">Bank Name</label>
                    <input type="text" name="bank_name" class="input-app" value="{{ $bankdetail->bank_name ?? '' }}" placeholder="Bank Name">
                </div>
                <div style="margin-bottom:12px;">
                    <label class="input-label">Branch</label>
                    <input type="text" name="branch_name" class="input-app" value="{{ $bankdetail->branch_name ?? '' }}" placeholder="Branch">
                </div>
                <div style="margin-bottom:20px;">
                    <label class="input-label">UPI ID (optional)</label>
                    <input type="text" name="upi" class="input-app" value="{{ $bankdetail->upi ?? '' }}" placeholder="yourname@upi">
                </div>
                <button type="submit" class="btn-app btn-gold" id="saveBankBtn">
                    <i class="fa fa-building-columns"></i> Save Bank Details
                </button>
            </form>
        </div>

        <div style="height:8px;"></div>

@endsection

@push('styles')
    <style>
    .d-none-tab { display: none !important; }
    </style>
@endpush

@push('scripts')
    <script>
    var activeTab = '{{ $activeTab }}';

    function switchTab(tab) {
        ['account','edit','bank'].forEach(function(t) {
            var el = document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1));
            if (el) el.style.display = (t === tab) ? '' : 'none';
        });
        // Update segment buttons
        var btns = document.querySelectorAll('.segment-ctrl button');
        var tabs = ['account','edit','bank'];
        btns.forEach(function(b, i) { b.classList.toggle('active', tabs[i] === tab); });
    }

    // Init on page load
    document.addEventListener('DOMContentLoaded', function() {
        switchTab(activeTab === 'password' ? 'edit' : activeTab);
    });

    function togglePw(id, btn) {
        var inp = document.getElementById(id);
        var show = inp.type === 'password';
        inp.type = show ? 'text' : 'password';
        btn.innerHTML = show ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
    }

    // Photo preview
    document.getElementById('photoInput').addEventListener('change', function() {
        if (!this.files[0]) return;
        var r = new FileReader();
        r.onload = function(e) {
            var wrap = document.getElementById('avatarWrap');
            wrap.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
        };
        r.readAsDataURL(this.files[0]);
    });

    // Profile update
    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();
        var btn = document.getElementById('saveProfileBtn');
        btn.disabled = true; btn.innerHTML = '<span class="spin"></span>';
        $.ajax({
            url: "{{ route('member.profile.update') }}",
            type: 'POST', data: new FormData(this),
            contentType: false, processData: false,
            success: function(r) { r.status ? toastr.success(r.message) : toastr.error(r.message); },
            error: function(xhr) {
                if (xhr.status===422) $.each(xhr.responseJSON.errors, function(k,v){ toastr.error(v[0]); });
                else toastr.error('Something went wrong.');
            },
            complete: function() { btn.disabled=false; btn.innerHTML='Save Profile'; }
        });
    });

    // Password
    $('#changePassForm').on('submit', function(e) {
        e.preventDefault();
        var btn = document.getElementById('savePassBtn');
        btn.disabled = true; btn.innerHTML = '<span class="spin" style="border-top-color:var(--gold);"></span>';
        $.ajax({
            url: "{{ route('member.password.update') }}",
            type: 'POST', data: $(this).serialize(),
            success: function(r) {
                if (r.status) { toastr.success(r.message); document.getElementById('changePassForm').reset(); }
                else toastr.error(r.message);
            },
            error: function(xhr) {
                if (xhr.status===422) $.each(xhr.responseJSON.errors, function(k,v){ toastr.error(v[0]); });
                else toastr.error('Something went wrong.');
            },
            complete: function() { btn.disabled=false; btn.innerHTML='<i class="fa fa-lock"></i> Update Password'; }
        });
    });

    // Bank
    $('#bankForm').on('submit', function(e) {
        e.preventDefault();
        var btn = document.getElementById('saveBankBtn');
        btn.disabled = true; btn.innerHTML = '<span class="spin"></span>';
        $.ajax({
            url: "{{ route('member.bank.details') }}",
            type: 'POST', data: $(this).serialize(),
            success: function(r) { r.status ? toastr.success(r.message) : toastr.error(r.message); },
            error: function(xhr) {
                if (xhr.status===422) $.each(xhr.responseJSON.errors, function(k,v){ toastr.error(v[0]); });
                else toastr.error('Something went wrong.');
            },
            complete: function() { btn.disabled=false; btn.innerHTML='<i class="fa fa-building-columns"></i> Save Bank Details'; }
        });
    });
    </script>
@endpush