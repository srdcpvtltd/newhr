
@extends('layouts.master')

@section('title','Edit Client ')

@section('action','Edit Client Detail')

@section('button')
    <a href="{{route('admin.crmenquery.index')}}" >
        <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> Back</button>
    </a>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.crmenquery.common.breadcrumb')

        <div class="card">
            <div class="card-body pb-0">
                <form id="clientEdit" class="forms-sample" action="{{route('admin.crmenquery.update',$crmenquery->id)}}"   method="post">
                    @method('PUT')
                    @csrf
                    @include('admin.crmenquery.common.form')
                </form>
            </div>
        </div>

       

    </section>
@endsection

@section('scripts')
    @include('admin.crmenquery.common.scripts')
    {{-- @include('admin.users.common.scripts') --}}
@endsection

