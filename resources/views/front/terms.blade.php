@extends('front.layout.main-layout')
@section('title', config('app.name') . ' || Terms & Conditions')
@section('content')
    <section class="page-title">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="content">
                        <h2 class="title">Terms & Conditions</h2>
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
                        {!! $terms->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection