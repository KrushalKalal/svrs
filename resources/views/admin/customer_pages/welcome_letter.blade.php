@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Welcome Letter')
@section('content')

    <style>
        .letter-wrapper {
            background: #fff;
            border: 1px solid #e5e5e5;
            padding: 50px;
            max-width: 900px;
            margin: auto;
            font-family: "Times New Roman", serif;
            line-height: 1.8;
        }

        .letter-header {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .letter-title {
            color: #0d6efd;
            letter-spacing: 2px;
            font-weight: 700;
        }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 20px;
            margin-top: 25px;
        }

        .signature-box {
            margin-top: 60px;
        }

        @media print {
            body * {
                visibility: hidden;
            }
        }

        .custom-hr {
            border: 2px solid #dfdfdf;
            opacity: 1;
        }
    </style>
    <div class="content">
        <div class="container-fluid mb-3">
            <div class="letter-wrapper">
                <div class="letter-header d-flex justify-content-between">
                    <div>
                        {{-- <img src="{{ asset('logo.png') }}" height="50"> --}}
                        <h4 class="mb-0 fw-bold">{{ config('app.name') }}</h4>
                        <small class="text-muted">Customer Relations Department</small>
                    </div>

                    <div class="text-end">
                        <h3 class="letter-title">WELCOME LETTER</h3>
                        <small>Date: {{ \Carbon\Carbon::parse($member->created_at)->format('d F Y') }}</small>
                    </div>
                </div>
                <p>
                    Dear
                    <strong>
                        {{ trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) }}
                    </strong>,
                </p>
                <p>
                    We are pleased to welcome you to <strong>{{ config('app.name') }}</strong>.
                    Thank you for placing your trust in us. Our mission is to deliver
                    exceptional service and ensure your experience with us is both
                    rewarding and seamless.
                </p>
                <p>
                    Your membership has been successfully activated. You may now enjoy
                    all the services, support, and benefits available through our platform.
                    Our dedicated team remains available to assist you whenever required.
                </p>
                <div class="info-box">
                    <h5 class="mb-3">Member Details</h5>
                    <p class="mb-1"><strong>Member ID:</strong> {{ $member->member_code }}</p>
                    <p class="mb-1"><strong>Email Address:</strong> {{ $member->email }}</p>
                    <p class="mb-0"><strong>Joining Date:</strong>
                        {{ \Carbon\Carbon::parse($member->created_at)->format('d F Y') }}</p>
                </div>
                <p class="mt-4">
                    We look forward to a long and successful association with you.
                    Once again, welcome to our organization.
                </p>
                <div class="signature-box">
                    <p class="mb-1">Sincerely,</p>
                    <h5 class="fw-bold mb-0">{{ config('app.name') }}</h5>
                    <small>Customer Support Team</small>
                </div>
                <hr class="mt-3 custom-hr">
                <p class="text-muted small text-center">
                    This is a computer-generated document and does not require a signature.
                </p>
            </div>
        </div>
    </div>
@endsection
