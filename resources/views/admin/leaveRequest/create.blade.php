@extends('layouts.master')

@section('title','Request Leave Post')

@section('action','Create')

@section('main-content')
    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.leaveRequest.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form class="forms-sample"
                      action="{{route('admin.employee-leave-request.store')}}"
                      method="post">
                    @csrf

                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <label for="leave_type" class="form-label">Leave Type<span style="color: red">*</span></label>
                            <select class="form-select" id="leaveType" name="leave_type_id" required>
                                <option selected disabled> Select Leave Type</option>
                                @foreach($leavetypes as $key=>$value)
                                    <option value="{{ $key }}" @if( old('leave_type_id')  == $key) selected @endif > {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="leave_from" class="form-label">From Date<span style="color: red">*</span></label>
                            <input class="form-control" type="date" name="leave_from" value="{{old('leave_from')}}" required  />
                        </div>

                        <div class="col-lg-3 mb-3 leaveTime d-none" >
                            <label for="leave_from" class="form-label">Start Time<span style="color: red">*</span></label>
                            <input class="form-control" type="time" name="start_time" value="{{old('start_time')}}"  />
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="leave_to" class="form-label">To Date<span style="color: red">*</span></label>
                            <input class="form-control" type="date" name="leave_to" value="{{old('leave_to')}}" required  />
                        </div>

                        <div class="col-lg-3 mb-3 leaveTime d-none">
                            <label for="leave_from" class="form-label">End Time <span style="color: red">*</span></label>
                            <input class="form-control" type="time" name="end_time" value="{{old('end_time')}}"  />
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="note" class="form-label"> Reason <span style="color: red">*</span> </label>
                            <textarea class="form-control" name="reasons" rows="5" >{{  old('reasons') }}</textarea>
                        </div>

                        <div class="text-start">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $('document').ready(function(){

            $('#earlyExit').change(function() {
                let selected = $('#earlyExit option:selected').val();
                let leaveTypeId = "{{ old('leave_type_id') }}";
                $('#leaveType').empty();

                if (selected) {
                    $('.inputLeaveType').removeClass('d-none')
                    if (selected === '1') {
                        $('.leaveTime').removeClass('d-none');
                    } else {
                        $('.leaveTime').addClass('d-none');
                    }

                    $.ajax({
                        type: 'GET',
                        url: "{{ url('admin/leaves/get-leave-types')}}" + '/' + selected,
                    }).done(function(response) {
                        let leaveTypeData = response.data;
                        if(!leaveTypeId){
                            $('#leaveType').append('<option value=""  selected >Select Leave Type </option>')
                        }
                        for (const leaveId in leaveTypeData) {
                            const leaveTypeName = leaveTypeData[leaveId];
                            $('#leaveType').append('<option ' + ((leaveTypeId == leaveId) ? "selected" : '' ) +' value="'+leaveId+'" >'+leaveTypeName+'</option>')
                        }
                    });
                }
            }).trigger('change');
        });
        // $('#leaveFrom').nepaliDatePicker({
        //     language: "english",
        //     dateFormat: "MM/DD/YYYY",
        //     ndpYear: true,
        //     ndpMonth: true,
        //     ndpYearCount: 20,
        //     disableAfter: "2089-12-30",
        // });
        //
        // $('#leaveTo').nepaliDatePicker({
        //     language: "english",
        //     dateFormat: "MM/DD/YYYY",
        //     ndpYear: true,
        //     ndpMonth: true,
        //     ndpYearCount: 20,
        //     disableAfter: "2089-12-30",
        // });
    </script>

@endsection

