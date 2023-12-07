<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic | Bootstrap HTML, VueJS, React, Angular, Asp.Net Core, Rails, Spring, Blazor, Django, Flask & Laravel Admin Dashboard Theme
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    <title>{{ env('APP_NAME') }} | {{ ucwords(request()->segment(1)) }}</title>
    <base href="" />
    <meta charset="utf-8" />
    <meta name="token" content="{{ session('bearer_token') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="hosturl" content="{{ url('') }}">
    <meta name="assets" content="{{ asset('') }}">
    <meta name="urlupload" content="{{ url('api/file_upload') }}">
    <meta name="deletefile" content="{{ url('api/delete_file') }}">
    @yield('meta')
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    @yield('customcss')
    @include('metronic/css')
</head>
<!--end::Head-->
<!--begin::Body-->
<script src="{{ asset('assets/js/custom/theme-handler.js') }}"></script>

<body
    @if (session('success')) notification_success="true"
	notification_message="{{ session('success') }}" @endif
    @if (count($errors) > 0) notification_error="true"
	notification_data="{{ json_encode($errors->all()) }}" @endif
    id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="false" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <div class="app-page-loader page-loader-logo d-block">
        <div class="d-flex align-items-center justify-content-center flex-column h-100">
            <img alt="Logo" class="max-h-110px logo-spinner" src="{{ asset('assets/media/logos/custom-2.svg') }}"
                width="70">
            <div class="spinner-border text-primary mt-5" role="status">
                <span class="visually-hidden">{{ __('Loading...') }}</span>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <div id="kt_app_header" class="app-header">
                <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
                    id="kt_app_header_container">
                    <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
                        <div class="btn btn-icon btn-active-color-primary w-35px h-35px"
                            id="kt_app_sidebar_mobile_toggle">
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                                        fill="currentColor" />
                                    <path opacity="0.3"
                                        d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                        <a href="{{ url('dashboard') }}" class="d-lg-none">
                            <img alt="Logo" src="{{ asset('assets/media/logos/default-small.svg') }}"
                                class="h-30px" />
                        </a>
                    </div>
                    @include('_components/top-menu')
                </div>
            </div>
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('_components/side-menu')
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                                @section('segments')
                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                        <h1
                                            class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                            {{ __(ucwords(request()->segment(1))) }}</h1>
                                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                            <li class="breadcrumb-item text-muted">
                                                <i class="fas fa-home"></i>
                                            </li>
                                            @foreach (request()->segments() as $segment)
                                                @if (is_numeric($segment))
                                                    @continue
                                                @endif
                                                <li class="breadcrumb-item">
                                                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                                                </li>
                                                <li class="breadcrumb-item text-muted">
                                                    @if ($loop->first)
                                                        <a href="{{ url(request()->segment(1)) }}"
                                                            class="text-hover-primary">{{ __(ucwords(str_replace('_', ' ', $segment))) }}</a>
                                                    @else
                                                        {{ __(ucwords(str_replace('_', ' ', $segment))) }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @show
                                @section('toolbar')
                                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                                    </div>
                                @show
                            </div>
                        </div>
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            @section('content')
                            @show
                        </div>
                    </div>
                    @include('_components/footer')
                </div>
            </div>
        </div>
    </div>
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <span class="svg-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1"
                    transform="rotate(90 13 6)" fill="currentColor" />
                <path
                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                    fill="currentColor" />
            </svg>
        </span>
    </div>

    @include('metronic/javascript')
    @yield('customjs')
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
</body>

</html>
