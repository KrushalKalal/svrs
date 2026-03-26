@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Trade History')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Trade History</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped" id="HistoryTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Coin</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($trades as $index => $trade)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        {{ $trade->created_at->format('d M Y h:i A') }}
                                    </td>

                                    <td>
                                        {{ $trade->coin->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        @if ($trade->type == 'buy')
                                            <span class="badge bg-success">BUY</span>
                                        @else
                                            <span class="badge bg-info">REWARD</span>
                                        @endif
                                    </td>

                                    <td>₹ {{ number_format($trade->price, 2) }}</td>
                                    <td>{{ number_format($trade->quantity, 2) }}</td>
                                    <td>₹ {{ number_format($trade->price * $trade->quantity, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Trade History Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            let table = $('#HistoryTable').DataTable();
        });
    </script>
@endsection