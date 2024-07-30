@extends('layouts.master')
@section('title','Asset Types')
@section('action','Assignments')
@section('button')
@can('create_type')
<a href="{{ route('admin.asset_assignment.create')}}">
    <button class="btn btn-primary">
        <i class="link-icon" data-feather="plus"></i>Assign to User
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

    .dropdown-item {
        color: #0e0d0d !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<section class="content">
    @include('admin.section.flash_message')
    @include('admin.assetManagement.assetAssignment.breadCrumb')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTableExample" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th class="text-center">Asset</th>
                            <th class="text-center">Assigned Date</th>
                            <th class="text-center">Return Date</th>
                            <th class="text-center">Damaged</th>
                            <th class="text-center">Damage Cost</th>
                            <th class="text-center">Recover</th>
                            <th class="text-center">Returned Status</th>
                            @canany(['edit_type','delete_type'])
                            <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @forelse($assetLists as $key => $value)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{ucfirst($value->assign_to)}}</td>
                            <td class="text-center">
                                {{$value->asset_name}}
                            </td>
                            <td class="text-center">
                                {{$value->assign_date}}
                            </td>
                            @if($value->return_date != null)
                            <td class="text-center">
                               {{$value->return_date}}
                            </td>
                            @else
                            <td class="text-center">
                                <b>- -</b>
                            </td> 
                            @endif
                            @if($value->damaged != null || $value->damaged == 1)
                            <td class="text-center">
                                Yes
                            </td>
                            @elseif($value->damaged == null)
                            <td class="text-center">
                                <b>- -</b>
                            </td>
                            @else
                            <td class="text-center">
                                No
                            </td>
                            @endif
                            @if($value->cost_of_damage != null)
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
                                <b>Pending</b>
                            </td>
                            @else
                            <td class="text-center">
                                <b>Paid</b>
                            </td>
                            @endif

                            @if($value->return_status == null)
                            <td class="text-center">
                                <b>Pending</b>
                            </td>
                            @elseif($value->return_status == 0)
                            <td class="text-center">
                                <b>Pending</b>
                            </td>
                            @else
                            <td class="text-center">
                                <b>Completed</b>
                            </td>
                            @endif
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #000;background-color: #fff; border-color: #fff;">
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        @if($value->return_status != 1)
                                        <li><a class="dropdown-item active" data-toggle="modal" data-target="#exampleModalCenter" href="#">Return Asset</a></li>
                                        @endif
                                        <li><a class="dropdown-item" href="#">Asset Assignment Agreement</a></li>
                                        <li><a class="dropdown-item" href="#">Asset Returned Agreement</a></li>

                                    </ul>
                                </div>
                            </td>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{route('admin.asset_return')}}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="damage">Damage</label>
                                                    <select name="damage" class="form-select" id="damageSelect" aria-label="Default select example">
                                                        <option selected>Open this select menu</option>
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </div>

                                                <!-- Fields to be shown when "Yes" is selected -->
                                                <div id="damageFields" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="cost_of_damage">Damage Cost</label>
                                                        <input name="cost_of_damage" type="number" class="form-control" id="damageCost" placeholder="Enter damage cost">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="amountPaid">Amount Paid</label>
                                                        <!-- <input type="number" class="form-control" id="amountPaid" placeholder="Enter amount paid"> -->
                                                        <select name="amount_paid" class="form-select" id="amountPaid" aria-label="Default select example">
                                                            <option selected value="{{null}}">Open this select menu</option>
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="reason">Reason</label>
                                                        <textarea name="damage_reason" class="form-control" id="reason" rows="3" placeholder="Enter reason"></textarea>
                                                    </div>
                                                </div>
                                                <!-- Field to be shown for both options -->
                                                <div id="returnDateField" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="returnDate">Return Date</label>
                                                        <input name="return_date" type="date" class="form-control" id="returnDate">
                                                    </div>
                                                </div>
                                                <input type="hidden" name="user_id" value="{{$value->user_id}}">
                                                <input type="hidden" name="asset_id" value="{{$value->asset_id}}">

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Return</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

    <!-- modal starts from here -->

    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button> -->



</section>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const damageSelect = document.getElementById("damageSelect");
        const damageFields = document.getElementById("damageFields");
        const returnDateField = document.getElementById("returnDateField");

        damageSelect.addEventListener("change", function() {
            if (damageSelect.value == "1") {
                // Show all fields if 'Yes' is selected
                damageFields.style.display = "block";
                returnDateField.style.display = "block";
            } else if (damageSelect.value == "0") {
                // Show only the return date if 'No' is selected
                damageFields.style.display = "none";
                returnDateField.style.display = "block";
            } else {
                // Hide all fields if no valid option is selected
                damageFields.style.display = "none";
                returnDateField.style.display = "none";
            }
        });
    });
</script>

@endsection

@section('scripts')
@include('admin.assetManagement.types.common.scripts')
@endsection