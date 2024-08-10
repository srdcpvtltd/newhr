@extends('layouts.master')
@section('title','Procurement')
@section('action','Procurements')
@section('button')
@can('create_type')
<a href="{{ route('admin.procurement.create')}}">
    <button class="btn btn-primary">
        <i class="link-icon" data-feather="plus"></i>Add Request
    </button>
</a>
@endcan
@endsection
@section('main-content')
<style>
    .navbar {
        position: absolute !important;
    }

    .btn-primary {
        color: #fff !important;
        background-color: #e82e5f !important;
        border-color: #e82e5f !important;
    }

    .btn-block {
        height: fit-content;
    }

    .filter-btn {
        margin-top: 8px;
    }

    .dropdown-item {
        color: #0e0d0d !important;
    }

    .table thead th {
        font-size: 12px !important;
        font-weight: 600 !important;

    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<section class="content">
    @include('admin.section.flash_message')
    @include('admin.procurement.breadCrumb')

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTableExample" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center">Request Number</th>
                            <th>Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Types</th>
                            <th class="text-center">Brands</th>
                            <th class="text-center">Request Date</th>
                            <th class="text-center">Status</th>
                            <!-- <th class="text-center">Damage Cost</th>
                            <th class="text-center">Recover</th>
                            <th class="text-center">Returned Status</th> -->
                            @canany(['edit_type','delete_type'])
                            <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $changeColor = [
                            0 => 'danger',
                            1 => 'success',
                            null => 'warning'
                        ]
                        ?>
                        <tr>
                            @forelse($requests as $key => $value)
                        <tr>
                            <td>{{++$key}}</td>
                            <td class="text-center">
                                {{$value->procurement_number}}
                            </td>
                            <td>{{ucfirst($value->name)}}</td>
                            <td class="text-center">
                                {{$value->email}}
                            </td>
                            <td class="text-center">
                                {{$value->asset_types}}
                            </td>
                            <td class="text-center">
                                {{$value->brand}}
                            </td>
                            <td class="text-center">
                                {{$value->request_date}}
                            </td>
                            <td class="text-center">
                                {{$value->status == 0 ? "Pending" : "Approved"}}
                            </td>
                            <!-- @if($value->return_date != null)
                            <td class="text-center">
                                {{$value->return_date}}
                            </td>
                            @else
                            <td class="text-center">
                                <b>- -</b>
                            </td>
                            @endif
                            @if($value->damaged == 1)
                            <td class="text-center">
                                Yes
                            </td>
                            @elseif($value->damaged == 0)
                            <td class="text-center">
                                No
                            </td>
                            @else
                            <td class="text-center">
                                <b>- -</b>
                            </td>
                            @endif -->
                            <!-- @if($value->cost_of_damage != null)
                            <td class="text-center">
                                ₹{{$value->cost_of_damage}}
                            </td>
                            @else
                            <td class="text-center">
                                <b>- -</b>
                            </td>
                            @endif
                            @if($value->paid == null)
                            <td class="text-center">
                                <b>- -</b>
                            </td>
                            @elseif($value->paid == 0)
                            <td class="text-center">
                                <h6 style="border-radius: 5px; padding: 4px; opacity: 0.6;font-family: serif;" class="btn-{{$changeColor[$value->paid]}}">Unpaid</h6>
                            </td>
                            @else
                            <td class="text-center">
                                <h6 style="border-radius: 5px; padding: 4px; opacity: 0.6;font-family: serif;" class="btn-{{$changeColor[$value->paid]}}">Paid</h6>
                            </td>
                            @endif

                            @if($value->return_status == null || $value->return_status == 0)
                            <td class="text-center">
                                <h6 style="border-radius: 5px; padding: 4px; opacity: 0.6;font-family: serif;" class="btn-{{$changeColor[null]}}">Pending</h6>
                            </td>
                            @else
                            <td class="text-center">
                                <h6 style="border-radius: 5px; padding: 4px; opacity: 0.6;font-family: serif;" class="btn-{{$changeColor[$value->return_status]}}">Completed</h6>
                            </td>
                            @endif -->
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #000;background-color: #fff; border-color: #fff;">
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        @if($value->return_status != 1)
                                        <li><a class="dropdown-item active" data-toggle="modal" data-target="#exampleModalCenter{{ $value->id }}" href="#">Return Asset</a></li>
                                        <li><a class="dropdown-item" href="{{route('admin.download.pdf')}}">Asset Assignment Agreement</a></li>
                                        <li><a class="dropdown-item" href="{{route('admin.download.return.pdf')}}">Asset Returned Agreement</a></li>
                                        @else
                                        <li><a class="dropdown-item active" href="{{route('admin.download.pdf')}}">Asset Assignment Agreement</a></li>
                                        <li><a class="dropdown-item" href="{{route('admin.download.return.pdf')}}">Asset Returned Agreement</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                            <!-- Modal -->
                               
                            @empty
                        <tr>
                            <td colspan="100%">
                                <p class="text-center"><b>No records found!</b></p>
                            </td>
                        </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('ckeditor');
</script>
@endsection

@section('scripts')
<!-- @include('admin.assetManagement.assetAssignment.scripts') -->
@include('admin.procurement.script')
<!-- @include('admin.assetManagement.types.common.scripts') -->
@endsection