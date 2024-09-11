@extends('layouts.master')

@section('title', 'CRM Enquery ')

@section('action', 'CRM Enquery')


@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('crmenquery.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Number</th>
                                <th>Address</th>
                                <th>Message</th>
                                <th>Assign To</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                                @if ($crmenquery->count() > 0)
                                    @foreach ($crmenquery as $key => $value)
                            <tr>
                                <td>{{ $crmenquery->firstItem() + $key }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->email }}</td>
                                <td>{{ $value->number }}</td>
                                <td>{{ $value->address }}</td>
                                <td>{{ $value->message }}</td>
                                <td>{{ $value->assignedUser->name }}</td>


                                <td class="text-center">
                                    @if ($isAdmin)
                                        <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                            {{-- For admins --}}
                                            <li class="me-2">
                                                <a href="{{ route('admin.crmenquery.edit', $value->id) }}"
                                                    title="Edit Client Detail">
                                                    <i class="link-icon" data-feather="edit"></i>Assign
                                                </a>
                                            </li>
                                        </ul>
                                    @else
                                        {{-- for users  --}}
                                        <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                            <li class="me-2">
                                                <a href="javascript:void(0)" class="enquire-modal-trigger"
                                                    data-id="{{ $value->id }}">
                                                    <i class="link-icon" data-feather="eye"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    @endif

                                </td>
                            </tr>
                           @endforeach   
                        @else
                            <p>No enquiries assigned to you.</p>
                         @endif

                        </tbody>
                    </table>
                </div>
                <br>
                <div class="row">{{ $crmenquery->links() }}</div>
            </div>
        </div>
    </section>


    {{-- new modal  --}}
    <div class="modal fade" id="enquire-modal" tabindex="-1" role="dialog" aria-labelledby="enquire-modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Enquire Details</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button> --}}
                </div>
                <div class="modal-body">
                    <div id="enquire-data">
                        <!-- Data will be displayed here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal  --}}

@endsection

@section('scripts')
    @include('admin.crmenquery.common.scripts')
@endsection
