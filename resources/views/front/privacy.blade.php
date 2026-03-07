@extends('front.layout.main-layout')
@section('title', config('app.name') . ' || Privacy Policy')
@section('content')
    <section class="page-title">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="content">
                        <h2 class="title">Privacy Policy</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="token-details style-1">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block-text center">
                        {!! $policy->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
