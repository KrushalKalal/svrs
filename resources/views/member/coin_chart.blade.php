@extends('member.layout.app-layout')
@section('title', 'SVRS Coin')
@section('nav-title', 'SVRS Coin')
@section('hide-member-code') @endsection

@section('nav-actions')
    <button class="nav-action-btn" onclick="loadCoinChart(currentFilter)" title="Refresh">
        <i class="fa fa-rotate-right"></i>
    </button>
@endsection

@section('content')

    {{-- Segment: Buy / Overview / History --}}
    <div style="padding:16px 20px 0;">
        <div class="segment-ctrl">
            <button class="active" id="tabBuyBtn" onclick="switchCoinTab('buy')">Buy</button>
            <button id="tabOverviewBtn" onclick="switchCoinTab('overview')">Overview</button>
            <button id="tabHistBtn" onclick="switchCoinTab('history')">History</button>
        </div>
    </div>

    {{-- Live price bar --}}
    <div style="margin:14px 20px 0;">
        <div style="background:var(--bg-card2);border:1px solid var(--border);border-radius:var(--radius);padding:16px;">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:12px;color:var(--muted);margin-bottom:4px;">Live Price</p>
                    <p style="font-size:28px;font-weight:800;color:var(--gold);" id="livePriceDisplay">
                        ₹{{ number_format($currentPrice ?? 0, 2) }}
                    </p>
                </div>
                <div class="badge-app badge-green" style="font-size:11px;">
                    <i class="fa fa-circle" style="font-size:7px;margin-right:3px;"></i>LIVE
                </div>
                <div style="text-align:right;">
                    <p style="font-size:12px;color:var(--muted);margin-bottom:4px;">Your Holdings</p>
                    <p style="font-size:18px;font-weight:800;">{{ number_format($coinBalance ?? 0, 4) }} SVRS</p>
                    <p style="font-size:12px;color:var(--muted);">≈
                        ₹{{ number_format(($coinBalance ?? 0) * ($currentPrice ?? 0), 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── BUY TAB ── --}}
    <div id="panelBuy">
        <div class="section-label">Price Chart</div>
        <div style="margin:0 20px;">
            <div class="app-card" style="padding:16px;">
                <div class="segment-ctrl" id="chartFilterCtrl" style="margin-bottom:14px;">
                    <button class="active" onclick="loadCoinChart('today');setChartFilter(this)">TODAY</button>
                    <button onclick="loadCoinChart('week');setChartFilter(this)">WEEK</button>
                    <button onclick="loadCoinChart('month');setChartFilter(this)">MONTH</button>
                    <button onclick="loadCoinChart('all');setChartFilter(this)">ALL</button>
                </div>
                <div id="candleChart" style="height:260px;"></div>
            </div>
        </div>

        <div class="section-label">Buy SVRS Coins</div>
        <div style="margin:0 20px;">
            <div class="app-card app-card-inner">
                <div class="info-row" style="padding:0 0 12px;border-bottom:1px solid var(--border2);margin-bottom:14px;">
                    <span class="key">Wallet Balance</span>
                    <span class="val" style="color:var(--accent);">₹<span
                            id="walletBal">{{ number_format($wallet->balance ?? 0, 2) }}</span></span>
                </div>
                <label class="input-label">Enter Amount (₹)</label>
                <div style="margin-bottom:10px;">
                    <input type="number" id="buyAmount" class="input-app" placeholder="e.g. 500" min="1" step="1">
                    <p style="font-size:12px;color:var(--red);margin-top:4px;display:none;" id="amtError"></p>
                </div>
                <div class="app-card app-card-inner"
                    style="background:var(--bg-card2);margin-bottom:14px;padding:12px 14px;">
                    <div class="info-row" style="padding:0;border:none;">
                        <span class="key">You will receive</span>
                        <span class="val" style="color:var(--green);" id="coinPreview">0.0000 SVRS</span>
                    </div>
                </div>
                <button class="btn-app btn-gold" id="buyBtn" onclick="confirmBuy()">
                    <i class="fa fa-cart-shopping"></i> Buy Now
                </button>
                <div class="alert-app" style="margin:12px 0 0;padding:10px 12px;">
                    <i class="fa fa-circle-info" style="color:var(--accent-blue);"></i>
                    <span>Amount deducted from wallet. Coins credited instantly.</span>
                </div>
            </div>
        </div>
        <div style="height:8px;"></div>
    </div>

    {{-- ── OVERVIEW TAB ── --}}
    <div id="panelOverview" style="display:none;">
        <div class="section-label">My Portfolio</div>
        <div style="margin:0 20px;" class="app-card">
            <div class="info-row px" style="padding-top:14px;">
                <span class="key">Total Coins</span>
                <span class="val">{{ number_format($coinBalance ?? 0, 4) }} SVRS</span>
            </div>
            <div class="info-row px">
                <span class="key">Approx Value</span>
                <span class="val"
                    style="color:var(--green);">₹{{ number_format(($coinBalance ?? 0) * ($currentPrice ?? 0), 2) }}</span>
            </div>
            <div class="info-row px" style="padding-bottom:14px;">
                <span class="key">Current Price</span>
                <span class="val" style="color:var(--gold);">₹{{ number_format($currentPrice ?? 0, 4) }}</span>
            </div>
        </div>
        <div style="height:8px;"></div>
    </div>

    {{-- ── HISTORY TAB ── loaded from $trades passed by CoinController@coin --}}
    <div id="panelHistory" style="display:none;">
        <div style="padding:16px 20px 0;">
            @if(isset($trades) && $trades->count())
                <div class="app-card" style="overflow:hidden;">
                    @foreach($trades as $trade)
                        <div class="list-row">
                            <div class="list-icon {{ $trade->type === 'buy' ? 'green' : ($trade->type === 'reward' ? 'gold' : 'red') }}"
                                style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                <i
                                    class="fa fa-{{ $trade->type === 'buy' ? 'arrow-down' : ($trade->type === 'reward' ? 'gift' : 'arrow-up') }}"></i>
                            </div>
                            <div class="list-body">
                                <div class="title">{{ ucfirst($trade->type) }}
                                    @if($trade->type === 'buy')
                                        <span class="badge-app badge-green" style="font-size:10px;margin-left:4px;">BUY</span>
                                    @elseif($trade->type === 'reward')
                                        <span class="badge-app badge-gold" style="font-size:10px;margin-left:4px;">REWARD</span>
                                    @endif
                                </div>
                                <div class="sub">{{ $trade->created_at->format('d M Y, h:i A') }}</div>
                                <div class="sub">{{ $trade->coin->name ?? 'SVRS' }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:14px;font-weight:700;color:var(--white);">
                                    {{ number_format($trade->quantity, 4) }} SVRS
                                </div>
                                <div style="font-size:12px;color:var(--muted);">₹{{ number_format($trade->price, 2) }}/coin</div>
                                <div style="font-size:12px;color:var(--muted);">
                                    Total: ₹{{ number_format($trade->price * $trade->quantity, 2) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fa fa-clock-rotate-left"></i>
                    <p>No trade history yet</p>
                </div>
            @endif
        </div>
        <div style="height:8px;"></div>
    </div>

@endsection

@push('styles')
    <style>
        #candleChart .apexcharts-canvas {
            background: transparent !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var currentPrice = {{ $currentPrice ?? 0 }};
        var walletBalance = {{ $wallet->balance ?? 0 }};
        var currentFilter = 'today';
        window.coinChart = null;

        // Tab switcher
        function switchCoinTab(tab) {
            ['buy', 'overview', 'history'].forEach(function (t) {
                document.getElementById('panel' + t.charAt(0).toUpperCase() + t.slice(1)).style.display = (t === tab) ? '' : 'none';
                document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1) + 'Btn').classList.toggle('active', t === tab);
            });
        }

        function setChartFilter(btn) {
            document.querySelectorAll('#chartFilterCtrl button').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
        }

        function loadCoinChart(type) {
            currentFilter = type;
            $.ajax({
                url: "{{ route('member.coin.chart') }}",
                data: { type: type },
                success: function (res) {
                    if (!res.status || !res.data.length) {
                        document.getElementById('candleChart').innerHTML =
                            '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--muted);font-size:13px;">No data available</div>';
                        return;
                    }
                    var last = res.data[res.data.length - 1];
                    currentPrice = last.y[3];
                    document.getElementById('livePriceDisplay').textContent = '₹' + currentPrice.toFixed(2);
                    recalculate();

                    if (window.coinChart) window.coinChart.destroy();
                    window.coinChart = new ApexCharts(document.querySelector('#candleChart'), {
                        series: [{ data: res.data }],
                        chart: {
                            type: 'candlestick', height: 260,
                            background: 'transparent',
                            toolbar: { show: false },
                            animations: { enabled: true, speed: 400 }
                        },
                        theme: { mode: 'dark' },
                        grid: { borderColor: '#1E2D45', strokeDashArray: 4 },
                        xaxis: {
                            type: 'datetime',
                            labels: { style: { colors: '#6B7A9A', fontSize: '10px' }, datetimeUTC: false }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#6B7A9A', fontSize: '10px' },
                                formatter: function (v) { return '₹' + v.toFixed(2); }
                            }
                        },
                        plotOptions: {
                            candlestick: { colors: { upward: '#10B981', downward: '#EF4444' }, wick: { useFillColor: true } }
                        },
                        tooltip: { theme: 'dark' }
                    });
                    window.coinChart.render();
                }
            });
        }

        function recalculate() {
            var amt = parseFloat(document.getElementById('buyAmount').value) || 0;
            var coins = currentPrice > 0 ? amt / currentPrice : 0;
            document.getElementById('coinPreview').textContent = coins.toFixed(4) + ' SVRS';
        }

        document.getElementById('buyAmount').addEventListener('input', function () {
            var amt = parseFloat(this.value) || 0;
            var errEl = document.getElementById('amtError');
            if (amt > walletBalance) {
                errEl.style.display = '';
                errEl.textContent = 'Insufficient balance. Available: ₹' + walletBalance.toFixed(2);
            } else {
                errEl.style.display = 'none';
            }
            recalculate();
        });

        function confirmBuy() {
            var amt = parseFloat(document.getElementById('buyAmount').value) || 0;
            if (amt <= 0) { toastr.error('Enter a valid amount.'); return; }
            if (amt > walletBalance) { toastr.error('Insufficient balance.'); return; }
            if (currentPrice <= 0) { toastr.error('Price unavailable. Refresh.'); return; }
            var qty = amt / currentPrice;

            Swal.fire({
                background: '#0D1626', color: '#fff',
                title: 'Confirm Purchase',
                html: '<div style="text-align:left;font-size:14px;">' +
                    '<div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #1E2D45;"><span style="color:#6B7A9A;">Amount</span><strong>₹' + amt.toFixed(2) + '</strong></div>' +
                    '<div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #1E2D45;"><span style="color:#6B7A9A;">Price/Coin</span><strong style="color:#F0A500;">₹' + currentPrice.toFixed(4) + '</strong></div>' +
                    '<div style="display:flex;justify-content:space-between;padding:10px 0;"><span style="color:#6B7A9A;">You receive</span><strong style="color:#10B981;">' + qty.toFixed(4) + ' SVRS</strong></div></div>',
                showCancelButton: true,
                confirmButtonText: 'Confirm Buy',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#F0A500',
                cancelButtonColor: '#1E2D45',
            }).then(function (r) {
                if (!r.isConfirmed) return;
                var btn = document.getElementById('buyBtn');
                btn.disabled = true;
                btn.innerHTML = '<span class="spin"></span> Processing...';
                $.ajax({
                    url: "{{ route('member.coin.trade') }}",
                    type: 'POST',
                    data: { _token: "{{ csrf_token() }}", type: 'buy', price: currentPrice, quantity: qty },
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.message);
                            walletBalance -= amt;
                            document.getElementById('walletBal').textContent = walletBalance.toFixed(2);
                            document.getElementById('buyAmount').value = '';
                            document.getElementById('coinPreview').textContent = '0.0000 SVRS';
                            setTimeout(function () { location.reload(); }, 1500);
                        } else { toastr.error(res.message); }
                    },
                    error: function (xhr) { toastr.error(xhr.responseJSON?.message || 'Error'); },
                    complete: function () {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa fa-cart-shopping"></i> Buy Now';
                    }
                });
            });
        }

        $(document).ready(function () { loadCoinChart('today'); });
        setInterval(function () { loadCoinChart(currentFilter); }, 300000);
    </script>
@endpush