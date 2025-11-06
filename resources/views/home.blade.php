@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="css/chart.min.css">
    <style>
        body {
            background-color: #f8fafc;
            padding: 20px;
        }

        .dashboard-card {
            background: #fff;
            border: none;
            border-radius: 5px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease;
            height: 150px;
            justify-content: start;
            align-items: center;
            padding: 16px 24px;
            display: flex;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .dashboard-icon {
            width: 48px;
            height: 48px;
            margin-right: 24px;
            object-fit: contain;
        }

        .dashboard-value {
            font-weight: 600;
            text-align: start;
        }

        /* Colors */
        .text-teal {
            color: #03A6A1;
            font-size: 24px;
        }

        .text-pink {
            color: #EA2264;
            font-size: 24px;
        }

        .text-green {
            color: #59AC77;
            font-size: 24px;
        }

        .text-purple {
            color: #B5179E;
            font-size: 24px;
        }

        .text-orange {
            color: #FF714B;
            font-size: 24px;
        }

        .text-blue {
            color: #007BFF;
            font-size: 24px;
        }

        .text-cyan {
            color: #1E93AB;
            font-size: 24px;
        }

        .text-green-secondary {
            color: #67C090;
            font-size: 24px;
        }

        .text-purple-secondary {
            color: #9929EA;
            font-size: 24px;
        }

        .text-ash-blue {
            color: #696FC7;
            font-size: 24px;
        }

        .text-blue-secondary {
            color: #0BA6DF;
            font-size: 24px;
        }

        .card-text {
            font-size: 16px;
            text-align: start;
            color: #000;
        }

        /* Grid layout fix */
        .dashboard-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            /* ✅ Aligns last row to the left */
            gap: 20px;
        }

        .dashboard-row .dashboard-col {
            flex: 1 1 calc(33.333% - 20px);
            max-width: calc(33.333% - 20px);
        }

        @media (max-width: 992px) {
            .dashboard-row .dashboard-col {
                flex: 1 1 calc(50% - 20px);
                max-width: calc(50% - 20px);
            }
        }

        @media (max-width: 576px) {
            .dashboard-row .dashboard-col {
                flex: 1 1 100%;
                max-width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="dashboard-row">

                <div class="dashboard-col">
                    <div class="dashboard-card">
                        <img src="{{ asset('icon/customer.png') }}" class="dashboard-icon" alt="Party">
                        <div class="dashboard-value text-teal">{{ $party ?? 0 }} TK
                            <p class="card-text">Party</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-col">
                    <div class="dashboard-card">
                        <img src="{{ asset('icon/labor.png') }}" class="dashboard-icon" alt="labor">
                        <div class="dashboard-value text-pink">{{ $labor ?? 0 }} TK
                            <p class="card-text">Labor</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-col">
                    <div class="dashboard-card">
                        <img src="{{ asset('icon/categories.png') }}" class="dashboard-icon" alt="category">
                        <div class="dashboard-value text-green">{{ $category ?? 0 }}
                            <p class="card-text">Category</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-col">
                    <div class="dashboard-card">
                        <img src="{{ asset('icon/product.png') }}" class="dashboard-icon" alt="product">
                        <div class="dashboard-value text-purple">{{ $product ?? 0 }}
                            <p class="card-text">Product</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-col">
                    <div class="dashboard-card">
                        <img src="{{ asset('icon/purchase.png') }}" class="dashboard-icon" alt="purchase">
                        <div class="dashboard-value text-orange">{{ number_format($purchases, 2) }}
                            <p class="card-text">Purchase</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-col">
                    <div class="dashboard-card">
                        <img src="{{ asset('icon/sale.png') }}" class="dashboard-icon" alt="sale">
                        <div class="dashboard-value text-cyan">{{ number_format($sales, 2) }}
                            <p class="card-text">{{ __('file.Sale') }} </p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-col">
                    <div class="dashboard-card">
                        <img src="{{ asset('icon/mill.png') }}" class="dashboard-icon" alt="{{ __('file.Mill') }}">
                        <div class="dashboard-value text-green-secondary">{{ $mill ?? 0 }}
                            <p class="card-text">{{ __('file.Mill') }}</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-col">
                    <div class="dashboard-card">
                        <img src="{{ asset('icon/transport.png') }}" class="dashboard-icon"
                            alt="{{ __('file.Transport') }}">
                        <div class="dashboard-value text-purple-secondary">{{ $truck ?? 0 }}
                            <p class="card-text">{{ __('file.Transport') }}</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-col">
                    <div class="dashboard-card">
                        <img src="{{ asset('icon/user.png') }}" class="dashboard-icon" alt="{{ __('file.User') }}">
                        <div class="dashboard-value text-ash-blue">{{ $user ?? 0 }}
                            <p class="card-text">{{ __('file.User') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
