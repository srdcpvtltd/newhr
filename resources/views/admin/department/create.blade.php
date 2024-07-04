
@extends('layouts.master')

@section('title','Create Department')

@section('button')
    <a href="{{route('admin.departments.index')}}" >
        <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
    </a>
@endsection

@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')
        @include('admin.department.common.breadcrumb')
        <div class="card">
            <div class="card-body pb-0">
                <form class="forms-sample" action="{{route('admin.departments.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @include('admin.department.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection

