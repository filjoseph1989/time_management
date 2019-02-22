@extends('layouts.master')

@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Time Management</h1>
                    </div>
                    <div class="col-sm-6">
                        {{-- breadcrumb --}}
                    </div>
                </div>
            </div>
        </div>

        <section class="content">

            <router-view></router-view>

        </section>
    </div>

@endsection
