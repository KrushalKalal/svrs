
<?php $__env->startSection('title', config('app.name') . ' || Coin Chart'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="container-fluid">

            <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
                <div class="my-auto mb-2">
                    <h2 class="mb-1">Coin Chart</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('member.dashboard')); ?>"><i
                                        class="ti ti-smart-home"></i></a></li>
                            <li class="breadcrumb-item active">Coin Chart</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <span class="badge bg-success  px-3 py-2">
                        <i class="ti ti-wallet me-1"></i>
                        Wallet: ₹<span id="walletBalanceTop"><?php echo e(number_format($wallet->balance ?? 0, 2)); ?></span>
                    </span>
                    <span class="badge bg-warning text-dark  px-3 py-2">
                        <i class="ti ti-coins me-1"></i>
                        <span id="coinBalanceTop"><?php echo e(number_format($coinBalance ?? 0, 4)); ?></span> SVRS
                    </span>
                </div>
            </div>

            <div class="row">

                
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">SVRS Coin Price Chart</h4>
                            <select id="filterType" class="form-select form-select-sm" style="width:160px;">
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="all">All Time</option>
                            </select>
                        </div>
                        <div class="card-body">
                            <div id="candleChart" style="height:400px;"></div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="ti ti-info-circle me-1"></i>
                            Selling is temporarily stopped. Only buying is active.
                        </div>
                    </div>
                </div>

                
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="ti ti-shopping-cart me-2"></i>Buy SVRS Coins</h5>
                        </div>
                        <div class="card-body">

                            <div class="p-3 bg-light rounded mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Current Price</span>
                                    <strong class="text-primary" id="currentPriceDisplay">
                                        ₹<?php echo e(number_format($currentPrice ?? 0, 4)); ?>

                                    </strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Wallet Balance</span>
                                    <strong class="text-success">
                                        ₹<span id="walletBalancePanel"><?php echo e(number_format($wallet->balance ?? 0, 2)); ?></span>
                                    </strong>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Enter Amount (₹) <span
                                        class="text-danger">*</span></label>
                                <input type="number" id="buyAmount" class="form-control form-control-lg"
                                    placeholder="e.g. 500" min="1" step="1">
                                <small class="text-danger d-block mt-1" id="amountError"></small>
                            </div>

                            <div class="p-2 bg-light rounded mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">You will receive</span>
                                    <strong class="text-success" id="coinPreview">0.0000 SVRS</strong>
                                </div>
                            </div>

                            <button class="btn btn-success w-100 btn-lg" id="buyBtn">
                                <span class="btn-text"><i class="ti ti-shopping-cart me-1"></i>Buy Now</span>
                                <span class="btn-loader d-none">
                                    <span class="spinner-border spinner-border-sm me-1"></span>Processing...
                                </span>
                            </button>

                            <div class="alert alert-info mt-3 mb-0 py-2">
                                <small>
                                    <i class="ti ti-info-circle me-1"></i>
                                    Amount deducted from wallet. Coins credited instantly.
                                </small>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="ti ti-chart-pie me-1"></i>My Portfolio</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Coins</span>
                                <strong><?php echo e(number_format($coinBalance ?? 0, 4)); ?> SVRS</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Approx Value</span>
                                <strong class="text-success">
                                    ₹<?php echo e(number_format(($coinBalance ?? 0) * ($currentPrice ?? 0), 2)); ?>

                                </strong>
                            </div>
                            <a href="<?php echo e(route('member.coin.history')); ?>"
                                class="btn btn-outline-secondary btn-sm w-100 mt-2">
                                <i class="ti ti-history me-1"></i>Trade History
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var currentPrice = <?php echo e($currentPrice ?? 0); ?>;
        var walletBalance = <?php echo e($wallet->balance ?? 0); ?>;

        // ── Chart ──────────────────────────────────────────────
        window.coinChart = null;
        window.currentFilterType = 'today';

        function loadCoinChart(type) {
            window.currentFilterType = type;
            $.ajax({
                url: "<?php echo e(route('member.coin.chart')); ?>",
                method: "GET",
                data: { type: type },
                success: function (res) {
                    if (!res.status || res.data.length === 0) {
                        $("#candleChart").html('<div class="text-center text-muted py-5">No chart data available.</div>');
                        return;
                    }

                    // Update current price from latest candle close
                    var last = res.data[res.data.length - 1];
                    currentPrice = last.y[3];
                    $('#currentPriceDisplay').text('₹' + currentPrice.toFixed(4));
                    recalculate();

                    if (window.coinChart) { window.coinChart.destroy(); }

                    var options = {
                        series: [{ data: res.data }],
                        chart: { type: 'candlestick', height: 400, toolbar: { show: true } },
                        title: { text: 'SVRS Price Movement', align: 'left' },
                        xaxis: { type: 'datetime', labels: { datetimeUTC: false, format: 'dd MMM HH:mm' } },
                        yaxis: { labels: { formatter: val => '₹ ' + val.toFixed(4) } },
                        plotOptions: {
                            candlestick: { colors: { upward: '#00B746', downward: '#EF403C' } }
                        }
                    };

                    window.coinChart = new ApexCharts(document.querySelector("#candleChart"), options);
                    window.coinChart.render();
                },
                error: function () { toastr.error("Chart load failed."); }
            });
        }

        $(document).ready(function () { loadCoinChart('today'); });

        $('#filterType').on('change', function () { loadCoinChart($(this).val()); });

        // Auto refresh every 5 min
        setInterval(function () { loadCoinChart(window.currentFilterType); }, 300000);

        // ── Buy Logic ──────────────────────────────────────────
        function recalculate() {
            var amount = parseFloat($('#buyAmount').val()) || 0;
            var coins = currentPrice > 0 ? (amount / currentPrice) : 0;
            $('#coinPreview').text(coins.toFixed(4) + ' SVRS');
        }

        $('#buyAmount').on('input keyup change', function () {
            var amount = parseFloat($(this).val()) || 0;
            $('#amountError').text('');
            if (amount > walletBalance) {
                $('#amountError').text('Insufficient balance. Available: ₹' + walletBalance.toFixed(2));
            }
            recalculate();
        });

        $('#buyBtn').on('click', function () {
            var amount = parseFloat($('#buyAmount').val()) || 0;
            $('#amountError').text('');

            if (amount <= 0) {
                $('#amountError').text('Please enter a valid amount.');
                return;
            }
            if (amount > walletBalance) {
                $('#amountError').text('Insufficient balance. Available: ₹' + walletBalance.toFixed(2));
                return;
            }
            if (currentPrice <= 0) {
                toastr.error('Coin price not available. Please refresh.');
                return;
            }

            var quantity = amount / currentPrice;

            Swal.fire({
                title: 'Confirm Purchase',
                html: `
                            <div class="text-start">
                                <p>Amount: <strong>₹${amount.toFixed(2)}</strong></p>
                                <p>Price: <strong>₹${currentPrice.toFixed(4)}</strong></p>
                                <p>You will receive: <strong>${quantity.toFixed(4)} SVRS</strong></p>
                            </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirm Buy',
                confirmButtonColor: '#198754',
            }).then(function (result) {
                if (!result.isConfirmed) return;

                $('#buyBtn .btn-text').addClass('d-none');
                $('#buyBtn .btn-loader').removeClass('d-none');
                $('#buyBtn').prop('disabled', true);

                $.ajax({
                    url: "<?php echo e(route('member.coin.trade')); ?>",
                    type: 'POST',
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
                        type: 'buy',
                        price: currentPrice,
                        quantity: quantity,
                    },
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.message ?? 'Coins purchased successfully!');
                            walletBalance -= amount;
                            $('#walletBalanceTop').text(walletBalance.toFixed(2));
                            $('#walletBalancePanel').text(walletBalance.toFixed(2));
                            $('#buyAmount').val('');
                            $('#coinPreview').text('0.0000 SVRS');
                            setTimeout(function () { location.reload(); }, 1500);
                        } else {
                            toastr.error(res.message ?? 'Something went wrong.');
                        }
                    },
                    error: function (xhr) {
                        var msg = xhr.responseJSON?.message ?? 'Server error.';
                        toastr.error(msg);
                    },
                    complete: function () {
                        $('#buyBtn .btn-text').removeClass('d-none');
                        $('#buyBtn .btn-loader').addClass('d-none');
                        $('#buyBtn').prop('disabled', false);
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/coin_chart.blade.php ENDPATH**/ ?>