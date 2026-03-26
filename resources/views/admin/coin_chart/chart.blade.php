@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Coin Chart')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Coin Candle Chart</h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success btn-sm tradeBtn" data-type="buy">
                            Buy
                        </button>

                        <button class="btn btn-danger btn-sm tradeBtn" data-type="sell">
                            Sell
                        </button>

                        <select id="filterType" class="form-select form-select-sm" style="width:200px;">
                            <option value="today">Today</option>
                            <option value="month">This Month</option>
                            <option value="4month">Last 4 Months</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div id="candleChart" style="height:450px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trade Modal -->

    <div class="modal fade" id="tradeModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="tradeForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tradeTitle">Trade</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="type" id="tradeType">

                        <div class="mb-3">
                            <label>Market Price</label>
                            <input type="number" step="0.01" name="price" id="tradePrice" class="form-control" readonly
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Quantity</label>
                            <input type="number" step="0.0001" name="quantity" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Submit Trade</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "3000"
        };
    </script>

    <script>
        window.coinChart = null;
        window.currentFilterType = 'today';
        window.latestPrice = 0;

        // Load Chart
        function loadCoinChart(type = 'today') {

            window.currentFilterType = type;

            $.ajax({
                url: "{{ route('admin.coin.chart') }}",
                method: "GET",
                data: {
                    type: type
                },

                success: function (res) {

                    if (!res.status || res.data.length === 0) {
                        $("#candleChart").html('<div class="text-center text-danger">No Data Found</div>');
                        return;
                    }

                    // Store latest close price
                    let lastCandle = res.data[res.data.length - 1];
                    window.latestPrice = lastCandle.y[3];

                    if (window.coinChart) {
                        window.coinChart.destroy();
                    }

                    let options = {
                        series: [{
                            data: res.data
                        }],
                        chart: {
                            type: 'rangeBar',
                            height: 450,
                            toolbar: {
                                show: true
                            }
                        },
                        title: {
                            text: 'Coin Price Movement',
                            align: 'left'
                        },
                        xaxis: {
                            type: 'datetime',
                            labels: {
                                datetimeUTC: false,
                                format: 'dd MMM HH:mm'
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function (val) {
                                    return "₹ " + val.toFixed(2);
                                }
                            }
                        },
                        plotOptions: {
                            candlestick: {
                                colors: {
                                    upward: '#00B746',
                                    downward: '#EF403C'
                                }
                            }
                        }
                    };

                    window.coinChart = new ApexCharts(document.querySelector("#candleChart"), options);
                    window.coinChart.render();
                },

                error: function () {
                    toastr.error("Chart Load Failed");
                }
            });
        }

        $(document).ready(function () {
            loadCoinChart('today');
        });

        $('#filterType').on('change', function () {
            loadCoinChart($(this).val());
        });

        setInterval(function () {
            loadCoinChart(window.currentFilterType);
        }, 300000);
    </script>

    <script>
        $(document).on('click', '.tradeBtn', function () {

            let type = $(this).data('type');

            if (!window.latestPrice) {
                toastr.warning("Waiting for market price...");
                return;
            }

            Swal.fire({
                title: (type === 'buy' ? 'Buy Coin' : 'Sell Coin'),

                html: `
                    <div style="text-align:left; margin-top:10px">
                        <div style="margin-bottom:15px">
                            <div style="font-weight:600; margin-bottom:5px">Market Price</div>
                            <input type="text"
                                id="swalPrice"
                                class="swal2-input"
                                value="₹ ${window.latestPrice.toFixed(2)}"
                                readonly
                                style="margin:0; width:100%;">
                        </div>
                        <div>
                            <div style="font-weight:600; margin-bottom:5px">Quantity</div>
                            <input type="number"
                                id="swalQty"
                                class="swal2-input"
                                placeholder="Enter Quantity"
                                step="0.0001"
                                style="margin:0; width:100%;">
                        </div>
                    </div>`,

                confirmButtonText: 'Submit Trade',
                showCancelButton: true,

                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading(),

                preConfirm: () => {

                    let qty = $('#swalQty').val();

                    if (!qty || qty <= 0) {
                        Swal.showValidationMessage('Enter valid quantity');
                        return false;
                    }
                    return $.ajax({
                        url: "{{ route('admin.coin.trade') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            type: type,
                            price: window.latestPrice,
                            quantity: qty
                        }
                    }).then(response => {

                        if (!response.status) {
                            throw new Error(response.message);
                        }

                        return response;

                    }).catch(error => {

                        Swal.showValidationMessage(
                            error.responseJSON?.message || error.message || 'Trade failed'
                        );

                    });
                }
            }).then((result) => {
                if (result.isConfirmed && result.value.status) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.value.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    loadCoinChart(window.currentFilterType);
                }
            });

        });
    </script>
@endsection