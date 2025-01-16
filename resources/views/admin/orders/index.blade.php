@extends('layouts.admin')

@section('title', 'My Orders')
    
@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header fw-bold">
                <h3>My Orders</h3>
            </div>
            <div class="card-body">

                <form action="" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="" >Filter by Date</label><br><br>
                            <input type="date" name="date" value="{{ Request::get('date') ?? date('Y-m-d') }}" class="form-control" >
                        </div>
                        <div class="col-md-3">
                            <label for="">Filter by Status</label><br><br>
                            <select name="status"class="form-select text-dark">
                                <option value="">Select All Status</option>
                                <option value="in progress" {{ Request::get('status') == 'in progress' ? 'selected':'' }} >In Progress</option>
                                <option value="completed" {{ Request::get('status') == 'completed' ? 'selected':'' }} >Completed</option>
                                <option value="pending" {{ Request::get('status') == 'pending' ? 'selected':'' }} >Pending</option>
                                <option value="cancelled" {{ Request::get('status') == 'cancelled' ? 'selected':'' }} >Cancelled</option>
                                <option value="out-for-delivery" {{ Request::get('status') == 'out-of-delivery' ? 'selected':'' }} >Out for delivery</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <br><br>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
                <hr>
                    <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="fw-bold">
                                        <th>Order ID</th>
                                        <th>Tracking No</th>
                                        <th>Username</th>
                                        <th>Payment Method</th>
                                        <th>Orderd Date</th>
                                        <th>Status Message</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->tracking_no }}</td>
                                            <td>{{ $item->fullname }}</td>
                                            <td>{{ $item->payment_method }}</td>
                                            <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                            <td>{{ $item->status_message }}</td>
                                            <td><a href="{{ url('admin/orders/'.$item->id) }}" class="btn btn-sm btn-primary">View</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                No Orders Available
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div>
                                {{ $orders->links() }}
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection