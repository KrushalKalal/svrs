


<?php $__env->startSection('title', 'Referral Network'); ?>
<?php $__env->startSection('nav-title', 'Referral Network'); ?>
<?php $__env->startSection('nav-back'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('nav-back-url', route('member.profile')); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $user = auth()->user();
        $activeTab = request('tab', 'overview');
        $membership = \App\Models\MemberMembership::where('user_id', $user->id)->where('status', 1)->first();

        // already computed in controller — use passed vars with fallback
        $level1 = $level1 ?? collect();
        $level2 = $level2 ?? collect();
        $level3 = $level3 ?? collect();
        $earnings = $earnings ?? [1 => 0, 2 => 0, 3 => 0];

        $earn1 = $earnings[1] ?? 0;
        $earn2 = $earnings[2] ?? 0;
        $earn3 = $earnings[3] ?? 0;

        $allEarnings = \App\Models\ReferralReward::with('fromUser')
            ->where('earner_id', $user->id)->latest()->get();
    ?>

    
    <div style="padding:16px 20px 0;">
        <div class="segment-ctrl" id="refNetTabs">
            <button class="<?php echo e($activeTab === 'overview'  ? 'active' : ''); ?>" onclick="switchRefNet('overview')">Overview</button>
            <button class="<?php echo e($activeTab === 'team'      ? 'active' : ''); ?>" onclick="switchRefNet('team')">Team</button>
            <button class="<?php echo e($activeTab === 'earnings'  ? 'active' : ''); ?>" onclick="switchRefNet('earnings')">Earnings</button>
        </div>
    </div>

    
    <div id="refNetOverview" style="<?php echo e($activeTab === 'overview' ? '' : 'display:none;'); ?>">
        <div style="margin:16px 20px 0;">
            <div class="gold-card" style="padding:20px;text-align:center;">
                <p style="font-size:12px;color:rgba(240,165,0,0.7);margin-bottom:8px;letter-spacing:0.5px;">Your Referral Code</p>
                <div style="font-size:28px;font-weight:800;letter-spacing:4px;font-family:'Space Mono',monospace;color:var(--gold);margin-bottom:16px;">
                    <?php echo e($membership->refer_code ?? $user->member_code); ?>

                </div>
                <div style="display:flex;gap:10px;justify-content:center;">
                    <button onclick="copyText('<?php echo e($membership->refer_code ?? $user->member_code); ?>')"
                        style="flex:1;padding:10px;border-radius:10px;background:rgba(240,165,0,0.12);border:1px solid rgba(240,165,0,0.3);color:var(--gold);font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                        <i class="fa fa-copy"></i> Copy Code
                    </button>
                    <?php if($membership && $membership->refer_link): ?>
                    <button onclick="copyText_val('<?php echo e($membership->refer_link); ?>')"
                        style="flex:1;padding:10px;border-radius:10px;background:rgba(0,212,170,0.12);border:1px solid rgba(0,212,170,0.3);color:var(--accent);font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                        <i class="fa fa-link"></i> Copy Link
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="section-label">Statistics</div>
        <div style="margin:0 20px;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div class="app-card app-card-inner">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(0,212,170,0.12);display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--accent);margin-bottom:10px;">
                    <i class="fa fa-users"></i>
                </div>
                <p style="font-size:24px;font-weight:800;color:var(--accent);"><?php echo e($level1->count() + $level2->count() + $level3->count()); ?></p>
                <p style="font-size:12px;color:var(--muted);">Total Referrals</p>
            </div>
            <div class="app-card app-card-inner">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(0,212,170,0.12);display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--accent);margin-bottom:10px;">
                    <i class="fa fa-shield-halved"></i>
                </div>
                <p style="font-size:24px;font-weight:800;color:var(--accent);"><?php echo e($level1->where('status', 1)->count()); ?></p>
                <p style="font-size:12px;color:var(--muted);">Active Members</p>
            </div>
        </div>

        <div class="section-label">How It Works</div>
        <div style="margin:0 20px;" class="app-card">
            <?php $__currentLoopData = [
                ['1', 'gold',        'Level 1', 'Direct referrals — highest reward %'],
                ['2', 'teal',        'Level 2', "Your referral's referrals"],
                ['3', 'accent-blue', 'Level 3', 'Third level network'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$num, $color, $title, $desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="list-row">
                <div style="width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800;color:var(--<?php echo e($color); ?>);flex-shrink:0;border:1.5px solid rgba(240,165,0,0.25);">
                    <?php echo e($num); ?>

                </div>
                <div class="list-body">
                    <div class="title" style="color:var(--<?php echo e($color); ?>);"><?php echo e($title); ?></div>
                    <div class="sub"><?php echo e($desc); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div style="height:8px;"></div>
    </div>

    
    <div id="refNetTeam" style="<?php echo e($activeTab === 'team' ? '' : 'display:none;'); ?>">
        <div style="padding:12px 20px 0;">
            <div class="segment-ctrl" id="teamLevelTabs">
                <button class="active" onclick="switchTeamLevel('l1', this)">L1 (<?php echo e($level1->count()); ?>)</button>
                <button onclick="switchTeamLevel('l2', this)">L2 (<?php echo e($level2->count()); ?>)</button>
                <button onclick="switchTeamLevel('l3', this)">L3 (<?php echo e($level3->count()); ?>)</button>
            </div>
        </div>

        <?php $__currentLoopData = [['l1', $level1, 1], ['l2', $level2, 2], ['l3', $level3, 3]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$pid, $members, $lvl]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div id="teamLevel_<?php echo e($pid); ?>" style="<?php echo e($pid === 'l1' ? '' : 'display:none;'); ?>padding:10px 20px 0;">
            <?php if($members->isEmpty()): ?>
                <div class="empty-state"><i class="fa fa-users"></i><p>No members at this level</p></div>
            <?php else: ?>
                <div class="app-card" style="overflow:hidden;">
                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-row">
                        <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:800;color:#000;flex-shrink:0;">
                            <?php echo e(strtoupper(substr($m->first_name, 0, 1))); ?>

                        </div>
                        <div class="list-body">
                            <div class="title"><?php echo e($m->full_name); ?></div>
                            <div class="sub" style="font-family:'Space Mono',monospace;font-size:11px;"><?php echo e($m->member_code); ?></div>
                            <div class="sub">Joined <?php echo e($m->created_at->format('d M Y')); ?></div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:4px;align-items:flex-end;">
                            <?php if($m->status == 1): ?><span class="badge-app badge-green" style="font-size:10px;">Active</span>
                            <?php elseif($m->status == 0): ?><span class="badge-app badge-red" style="font-size:10px;">Inactive</span>
                            <?php else: ?><span class="badge-app badge-gold" style="font-size:10px;">Pending</span><?php endif; ?>
                            <?php if($m->is_refer_member): ?><span class="badge-app badge-teal" style="font-size:10px;">Refer</span><?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div style="height:8px;"></div>
    </div>

    
    <div id="refNetEarnings" style="<?php echo e($activeTab === 'earnings' ? '' : 'display:none;'); ?>">
        <div style="margin:16px 20px 0;">
            <div class="gold-card" style="padding:16px;">
                <p style="font-size:12px;color:rgba(240,165,0,0.7);text-align:center;margin-bottom:14px;">Total Coin Earnings by Level</p>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;text-align:center;">
                    <div>
                        <p style="font-size:11px;color:rgba(240,165,0,0.7);margin-bottom:4px;">L1</p>
                        <p style="font-size:20px;font-weight:800;color:var(--gold);"><?php echo e(number_format($earn1, 4)); ?></p>
                        <p style="font-size:10px;color:var(--muted);">SVRS</p>
                    </div>
                    <div style="border-left:1px solid rgba(240,165,0,0.2);border-right:1px solid rgba(240,165,0,0.2);">
                        <p style="font-size:11px;color:rgba(0,212,170,0.7);margin-bottom:4px;">L2</p>
                        <p style="font-size:20px;font-weight:800;color:var(--accent);"><?php echo e(number_format($earn2, 4)); ?></p>
                        <p style="font-size:10px;color:var(--muted);">SVRS</p>
                    </div>
                    <div>
                        <p style="font-size:11px;color:rgba(59,130,246,0.7);margin-bottom:4px;">L3</p>
                        <p style="font-size:20px;font-weight:800;color:var(--accent-blue);"><?php echo e(number_format($earn3, 4)); ?></p>
                        <p style="font-size:10px;color:var(--muted);">SVRS</p>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding:12px 20px 0;">
            <?php if($allEarnings->isEmpty()): ?>
                <div class="empty-state"><i class="fa fa-coins"></i><p>No referral earnings yet</p></div>
            <?php else: ?>
                <div class="app-card" style="overflow:hidden;">
                    <?php $__currentLoopData = $allEarnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-row">
                        <div class="list-icon <?php echo e($rw->level == 1 ? 'gold' : ($rw->level == 2 ? 'teal' : 'blue')); ?>" style="width:40px;height:40px;border-radius:12px;font-size:13px;">
                            L<?php echo e($rw->level); ?>

                        </div>
                        <div class="list-body">
                            <div class="title"><?php echo e($rw->fromUser->full_name ?? '—'); ?></div>
                            <div class="sub"><?php echo e($rw->percentage); ?>% • <?php echo e($rw->created_at->format('d M Y, h:i A')); ?></div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:14px;font-weight:700;color:var(--green);">+<?php echo e(number_format($rw->reward_quantity, 4)); ?></div>
                            <div style="font-size:11px;color:var(--muted);">SVRS</div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
        <div style="height:8px;"></div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
var _refNetActive = '<?php echo e($activeTab); ?>';
function switchRefNet(tab) {
    ['overview','team','earnings'].forEach(function(t) {
        var el = document.getElementById('refNet' + t.charAt(0).toUpperCase() + t.slice(1));
        if (el) el.style.display = (t === tab) ? '' : 'none';
    });
    document.querySelectorAll('#refNetTabs button').forEach(function(b, i) {
        b.classList.toggle('active', ['overview','team','earnings'][i] === tab);
    });
}
function switchTeamLevel(level, btn) {
    ['l1','l2','l3'].forEach(function(l) {
        var el = document.getElementById('teamLevel_' + l);
        if (el) el.style.display = (l === level) ? '' : 'none';
    });
    document.querySelectorAll('#teamLevelTabs button').forEach(function(b){ b.classList.remove('active'); });
    if (btn) btn.classList.add('active');
}
function copyText_val(val) {
    navigator.clipboard.writeText(val);
    if (typeof toastr !== 'undefined') toastr.success('Copied!');
}
document.addEventListener('DOMContentLoaded', function() { switchRefNet(_refNetActive); });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('member.layout.app-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/membership/referrals.blade.php ENDPATH**/ ?>