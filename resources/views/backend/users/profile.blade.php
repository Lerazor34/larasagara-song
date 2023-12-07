@extends('skeleton')

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="mb-5 card mb-xxl-8">
            <div class="pb-0 card-body pt-9">
                <div class="flex-wrap d-flex flex-sm-nowrap">
                    <div class="mb-4 me-7">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ Auth::getImage() }}" alt="image">
                            <div
                                class="bottom-0 mb-6 border border-4 position-absolute translate-middle start-100 bg-success rounded-circle border-body h-20px w-20px">
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="flex-wrap mb-2 d-flex justify-content-between align-items-start">
                            <div class="d-flex flex-column">
                                <div class="mb-2 d-flex align-items-center">
                                    <a href="#"
                                        class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $model['first_name'] . ' ' . $model['last_name'] }}</a>
                                    <a href="#">
                                        <span class="svg-icon svg-icon-1 svg-icon-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z"
                                                    fill="currentColor"></path>
                                                <path
                                                    d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z"
                                                    fill="white"></path>
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                                <div class="flex-wrap mb-4 d-flex fw-semibold fs-6 pe-2">
                                    <a href="#"
                                        class="mb-2 text-gray-400 d-flex align-items-center text-hover-primary">
                                        <span class="svg-icon svg-icon-4 me-1">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3"
                                                    d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z"
                                                    fill="currentColor"></path>
                                                <path
                                                    d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        {{ $model['email'] }}</a>
                                </div>
                            </div>
                            <div class="my-4 d-flex">

                            </div>
                        </div>
                        <div class="flex-wrap d-flex flex-stack">
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <div class="flex-wrap d-flex">
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="13" y="6" width="13"
                                                        height="2" rx="1" transform="rotate(90 13 6)"
                                                        fill="currentColor"></rect>
                                                    <path
                                                        d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                data-kt-countup-value="4500" data-kt-countup-prefix=""
                                                data-kt-initialized="1">90</div>
                                        </div>
                                        <div class="fw-semibold fs-6 text-gray-400">{{ __('Login Attempts') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 d-flex align-items-center w-200px w-sm-300px flex-column">

                            </div>
                        </div>
                    </div>
                </div>
                <ul class="border-transparent nav nav-stretch nav-line-tabs nav-line-tabs-2x fs-5 fw-bold">
                    <li class="mt-2 nav-item">
                        <a class="py-5 nav-link text-active-primary ms-0 me-10 active"
                            href="#">{{ __('Profile') }}</a>
                    </li>
                    <li class="mt-2 nav-item">
                        <a class="py-5 nav-link text-active-primary ms-0 me-10" href="#">{{ __('User Access') }}</a>
                    </li>
                    <li class="mt-2 nav-item">
                        <a class="py-5 nav-link text-active-primary ms-0 me-10"
                            href="#">{{ __('Update Profile') }}</a>
                    </li>
                    <li class="mt-2 nav-item">
                        <a class="py-5 nav-link text-active-primary ms-0 me-10"
                            href="{{ url(Request::segment(1) . '/reset_password') }}">{{ __('Update Password') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mb-5 card mb-xl-10" id="kt_profile_details_view">
            <div class="cursor-pointer card-header">
                <div class="m-0 card-title">
                    <h3 class="m-0 fw-bold">{{ __('Detail Profil') }}</h3>
                </div>
            </div>
            <div class="card-body p-9">
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ __('Full Name') }}</label>
                    <div class="col-lg-8">
                        <span
                            class="fw-bold fs-6 text-gray-800">{{ $model['first_name'] . ' ' . $model['last_name'] }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ __('Email') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $model['email'] }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">
                        {{ __('Contact Phone') }}
                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                            aria-label="Phone number must be active" data-bs-original-title="Phone number must be active"
                            data-kt-initialized="1"></i>
                    </label>
                    <div class="col-lg-8 d-flex align-items-center">
                        <span class="fw-bold fs-6 text-gray-800 me-2">{{ $model['phone_number'] }}</span>
                        <span class="badge badge-success">Verified</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ __('Address') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $model['address'] }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ __('Gender') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $model['gender']['value'] }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ __('Status') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $model['status']['value'] }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ __('Role') }}</label>
                    <div class="col-lg-8">
                        @for ($i = 0; $i < count($model['roles']); $i++)
                            <span
                                class="badge py-3 px-4 fs-7 badge-light-success me-1">{{ $model['roles'][$i]['roles_id']['name'] }}</span>
                        @endfor
                    </div>
                </div>
                <div class="notice d-flex bg-light-success rounded border-success border border-dashed  p-6">
                    <span class="svg-icon svg-icon-2tx svg-icon-success me-4"><svg width="24" height="24"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                rx="10" fill="currentColor"></rect>
                            <rect x="11" y="14" width="7" height="2" rx="1"
                                transform="rotate(-90 11 14)" fill="currentColor"></rect>
                            <rect x="11" y="17" width="2" height="2" rx="1"
                                transform="rotate(-90 11 17)" fill="currentColor"></rect>
                        </svg>
                    </span>
                    <div class="d-flex flex-stack flex-grow-1 ">
                        <div class=" fw-semibold">
                            <h4 class="text-gray-900 fw-bold">We need your attention!</h4>
                            <div class="fs-6 text-gray-700 ">Your payment was declined. To start using tools, please <a
                                    class="fw-bold" href="/metronic8/demo1/../demo1/account/billing.html">Add Payment
                                    Method</a>.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
