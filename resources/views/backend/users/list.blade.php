@extends('skeleton')

@section('meta')
<meta name="datatable-url" content="{{ url('api/' . Request::segment(1) . '/datatable') }}">
<meta name="first-segment" content="{{ url(Request::segment(1)) }}">
<meta name="first-segment-api" content="{{ url('api/'.Request::segment(1)) }}">
<meta name="permission" update="{{ Auth::allowedUri(Request::segment(1).'.edit') ? true : false }}" trash="{{ Auth::allowedUri(Request::segment(1).'.trash') ? true : false }}" activation="{{ Auth::allowedUri('user.activation') ? true : false }}">
@endsection

@section('toolbar')
@if(Auth::allowedUri(url(Request::segment(1) . '/trashed')))
<a href="{{ url(Request::segment(1) . '/trashed') }}" class="btn btn-lg btn-light-youtube">
    <span class="svg-icon svg-icon-danger svg-icon-2">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24"/>
                <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="currentColor" fill-rule="nonzero"/>
                <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="currentColor" opacity="0.3"/>
            </g>
        </svg>
    </span>
    {{ __('Trash') }}
</a>
@endif
@endsection

@section('content')

<?php
    $column = [
        ['data' => 'id']
    ];
?>

<div id="kt_app_content_container" class="app-container container-fluid">
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush h-md-100">
                <div class="card-header pt-7">
                    <!--begin::Title-->
                    <div class="my-1 d-flex align-items-center position-relative">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        {{-- <input type="text" name="search" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search user" value="{{ Request::get('search') }}"/> --}}
                        <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="{{ __('Search') }} {{ __($segmentName) }}"/>
                    </div>
                    <!--end::Title-->
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Filter-->
                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->{{ __('Filter') }}</button>
                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                        <!--begin::Header-->
                        <div class="py-5 px-7">
                            <div class="fs-5 text-dark fw-bolder">{{ __('Filter Options') }}</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Separator-->
                        <div class="border-gray-200 separator"></div>
                        <!--end::Separator-->
                            <!--begin::Content-->
                            <form id="searchform" class="py-5 px-7" data-kt-user-table-filter="form">
                                <!--begin::Input group-->
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-bold">{{ __('Role') }} :</label>
                                    <select
                                        class="form-select select2ajax"
                                        data-model="roles"
                                        data-key="id"
                                        data-display="name"
                                        name="role"
                                    >
                                    </select>
                                </div>
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-bold">{{ __('Status') }} :</label>
                                    <select
                                        class="form-select sysparam-reference"
                                        data-key="key"
                                        data-display="value"
                                        data-group="Activation"
                                        name="status"
                                    >
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    {{-- <button type="reset" class="px-6 btn btn-light btn-active-light-primary fw-bold me-2" data-kt-menu-dismiss="true" data-kt-user-table-filter="reset">Reset</button> --}}
                                    <a class="px-6 btn btn-primary fw-bold" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter">{{ __('Submit') }}</a>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Content-->
                        </div>
                        <!--end::Menu 1-->
                        <!--end::Filter-->
                        <!--begin::Add user-->
                        @if(Auth::allowedUri(url(Request::segment(1) . '/create')))
                        <a href="{{ url(Request::segment(1) . '/create') }}" class="btn btn-primary">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->{{ __('New Record') }}</a>
                        @endif
                        <!--end::Add user-->
                    </div>
                    <!--end::Toolbar-->
                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                        <div class="fw-bolder me-5">
                        <span class="me-2" data-kt-user-table-select="selected_count"></span>{{ __('Selected') }}</div>
                        <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">{{ __('Delete Selected') }}</button>
                    </div>
                    <!--end::Toolbar-->
                </div>
                <div class="card-body pt-6">
                    <div class="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_datatable_example_1">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_datatable_example_1 .form-check-input" value="1"/>
                                            </div>
                                        </th>
                                        @foreach($forms as $key => $items)
                                            @if($items['display'])
                                            <th class="text-nowrap">{{ __($items['label']) }}</th>
                                            <?php
                                                if($items['type'] == 'select2' || $items['type'] == 'sysparam') {
                                                    $column[] = ['data' => $items['name'] . '.' . $items['options']['display']];
                                                } else {
                                                    $column[] = ['data' => $items['name']];
                                                }
                                            ?>
                                            @endif
                                        @endforeach
                                        <?php 
                                            $column[] = ['data' => 'roles'];
                                            $column[] = ['data' => null]; 
                                        ?>
                                        <th class="text-nowrap">Roles</th>
                                        <th class="text-end min-w-100px"></th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="data-columns" value='@json($column)'>
@endsection

@section('customjs')
<script>
"use strict";

var columns = $('input#data-columns').val();
// Class definition
var KTDatatablesServerSide = function () {
    // Shared variables
    var table;
    var dt;
    var filterPayment;

    var searchAdvance = [];
    // Private functions
    var initDatatable = function () {
        dt = $("#kt_datatable_example_1").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            // fixedColumns: {
            //     left: 1,
            //     right: 1
            // },
            // stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "{{ url('api/' . Request::segment(1) . '/datatable') }}",
                headers: {
                    'Authorization': 'Bearer {{session('bearer_token')}}'
                },
                data: function (data) {
                    data.params = searchAdvance;
                    data.search = data.search.value;
                },
                complete: function (result) {
                    $("#kt_datatable_example_1").find("th:first-child").removeClass("sorting_asc");
                }
            },
            columns: JSON.parse(columns),
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    render: function (data) {
                        return `
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="${data}" />
                            </div>`;
                    }
                },
                {
                    targets: 1,
                    render: function (data) {
                        return `
                            <a href="#" class="text-bold">${data}</a>
                        `;
                    }
                },
                {
                    targets: -3,
                    render: function (data) {
                        let color = 'danger';
                        if(data == 'Active') {
                            color = 'primary';
                        }

                        return `
                            <span class="badge py-3 px-4 fs-7 badge-light-${color}">${data}</span>
                        `;
                    }
                },
                {
                    targets: -2,
                    orderable: false
                },
                {
                    targets: -1,
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        var action = `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                {{ __('Action') }}
                                <span class="m-0 svg-icon svg-icon-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                            <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                        </g>
                                    </svg>
                                </span>
                            </a>
                            <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px" data-kt-menu="true">
                                @if(Auth::allowedUri(Request::segment(1).'.edit'))
                                <div class="px-3 menu-item">
                                    <a href="{{ url(Request::segment(1)) }}/${data.id}/edit" class="px-3 menu-link" data-kt-user-table-filter="edit_row">
                                        {{ __('Edit') }}
                                    </a>
                                </div>
                                @endif
                                @if(Auth::allowedUri(Request::segment(1).'.trash'))
                                <div class="px-3 menu-item">
                                    <a data-remote="{{ url('api/'.Request::segment(1)) }}/${data.id}/trash" class="px-3 menu-link" data-kt-user-table-filter="delete_row">
                                        {{ __('Delete') }}
                                    </a>
                                </div>
                                @endif
                        `;

                        if(data.status.value == 'Active') {
                            action += `
                                @if(Auth::allowedUri('user.activation'))
                                <div class="px-3 menu-item">
                                    <a data-remote="{{ url('api/'.Request::segment(1)) }}/${data.id}/activation/${data.status.key}" class="px-3 menu-link" data-kt-user-table-filter="set_not_active">
                                        {{ __('Set Not Active') }}
                                    </a>
                                </div>
                                @endif
                            `;
                        } else {
                            action += `
                                @if(Auth::allowedUri('user.activation'))
                                <div class="px-3 menu-item">
                                    <a data-remote="{{ url('api/'.Request::segment(1)) }}/${data.id}/activation/${data.status.key}" class="px-3 menu-link" data-kt-user-table-filter="activate_user">
                                        {{ __('Activate') }}
                                    </a>
                                </div>
                                @endif
                            `;
                        }

                        action += `</div>`;

                        return action;
                    },
                },
            ],
            // Add data-filter attribute
            // createdRow: function (row, data, dataIndex) {
            //     $(row).find('td:eq(4)').attr('data-filter', data.CreditCardType);
            // }
        });

        table = dt.$;

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        dt.on('draw', function () {
            initToggleToolbar();
            toggleToolbars();
            handleDeleteRows();
            handleActivationUser();
            handleNotActiveUser();
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-user-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    }

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        const filterButton = document.querySelector('[data-kt-user-table-filter="filter"]');

        // Filter datatable on submit
        filterButton.addEventListener('click', function () {
            var obj = $('form#searchform').serializeArray();
            searchAdvance = obj;
            dt.draw();
        });
    }

    var handleDeleteRows = () => {
        const deleteButtons = document.querySelectorAll('[data-kt-user-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();
                const url = $(this).data('remote');
                const parent = e.target.closest('tr');
                const customerName = parent.querySelectorAll('td')[1].innerText;

                Swal.fire({
                    text: "{{ __('Are you sure want to delete') }} " + customerName + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "{{ __('Yes') }}, {{ __('delete') }}!",
                    cancelButtonText: "{{ __('No') }}, {{ __('cancel') }}",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: url,
                            type: "POST",
                            headers: {
                                'Authorization': 'Bearer {{session('bearer_token')}}'
                            },
                            success: function (resp) {
                                Swal.fire({
                                    text: "{{ __(ucwords(Request::segment(1))) }} " + "{{ __('Deleted') }}",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "{{ __('Yes') }}",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function () {
                                    dt.draw();
                                });
                            }
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: customerName + " {{ __('Not deleted') }}.",
                            icon: "info",
                            buttonsStyling: false,
                            confirmButtonText: "{{ __('Yes') }}",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            })
        });
    }

    // Init toggle toolbar
    var initToggleToolbar = function () {
        const container = document.querySelector('#kt_datatable_example_1');
        const checkboxes = container.querySelectorAll('[type="checkbox"]');

        const deleteSelected = document.querySelector('[data-kt-user-table-select="delete_selected"]');

        checkboxes.forEach(c => {
            // Checkbox on click event
            c.addEventListener('click', function () {
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });

        deleteSelected.addEventListener('click', function () {
            var selectedId = [];
            var checkbox = $('[type="checkbox"]:checked');
            checkbox.each(function(key, items, data) {
                if($(items).val() != 'on') selectedId.push($(items).val());
            });

            var selectedList = selectedId.join(",");

            Swal.fire({
                text: "{{ __('Are you sure want to delete selected rows') }} ?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                showLoaderOnConfirm: true,
                confirmButtonText: "{{ __('Yes, delte') }}!",
                cancelButtonText: "{{ __('No, Cancel') }}",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                },
            }).then(function (result) {
                if (result.value) {
                    // Simulate delete request -- for demo purpose only
                    const url = `{{ url('api/'.Request::segment(1)) }}/${selectedList}/trash`;
                    $.ajax({
                        url: url,
                        type: "POST",
                        headers: {
                            'Authorization': 'Bearer {{session('bearer_token')}}'
                        },
                        success: function (resp) {
                            Swal.fire({
                                text: "Anda telah menghapus semua baris yang dipilih!.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Baiklah, Silahkan!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            }).then(function () {
                                dt.draw();
                            });

                            const headerCheckbox = container.querySelectorAll('[type="checkbox"]')[0];
                            headerCheckbox.checked = false;
                        }
                    });
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "{{ __('Rows is not deleted') }}!",
                        icon: "info",
                        buttonsStyling: false,
                        confirmButtonText: "{{ __('Yes') }}",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });
        });
    }

    var handleActivationUser = () => {
        // Select all delete buttons
        const buttons = document.querySelectorAll('[data-kt-user-table-filter="activate_user"]');

        buttons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();
                
                const url = $(this).data('remote');

                Swal.fire({
                    text: "{{ __('Are you sure wan to activate this user') }} ?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "{{ __('Yes') }}, {{ __('activate') }}!",
                    cancelButtonText: "{{ _('No') }}, {{ __('cancel') }}",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: url,
                            type: "PUT",
                            headers: {
                                'Authorization': 'Bearer {{session('bearer_token')}}'
                            },
                            success: function (resp) {
                                Swal.fire({
                                    text: resp.message,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "{{ __('Yes') }}",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function () {
                                    dt.draw();
                                });
                            }
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: "{{ __('User is not active') }}.",
                            icon: "info",
                            buttonsStyling: false,
                            confirmButtonText: "{{ __('Yes') }}",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            })
        });
    }

    var handleNotActiveUser = () => {
        const button = document.querySelectorAll('[data-kt-user-table-filter="set_not_active"]');

        button.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();
                const url = $(this).data('remote');

                Swal.fire({
                    text: "{{ __('Are you sure want to non active this user') }} ?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "{{ __('Yes') }}, {{ __('non active') }}!",
                    cancelButtonText: "{{ _('No') }}, {{ __('cancel') }}",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: url,
                            type: "PUT",
                            headers: {
                                'Authorization': 'Bearer {{session('bearer_token')}}'
                            },
                            success: function (resp) {
                                Swal.fire({
                                    text: resp.message,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "{{ __('Yes') }}",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function () {
                                    dt.draw();
                                });
                            }
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: "{{ __('User is active') }}.",
                            icon: "info",
                            buttonsStyling: false,
                            confirmButtonText: "{{ __('Yes') }}!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            })
        });
    }

    // Reset Filter
    var handleResetForm = () => {
        // const resetButton = document.querySelector('[data-kt-user-table-filter="reset"]');
        // resetButton.addEventListener('click', function () {
        //     filterPayment[0].checked = true;
        //     dt.search('').draw();
        // });
    }

    // Toggle toolbars
    var toggleToolbars = function () {
        // Define variables
        const container = document.querySelector('#kt_datatable_example_1');
        const toolbarBase = document.querySelector('[data-kt-user-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-user-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-user-table-select="selected_count"]');

        // Select refreshed checkbox DOM elements
        const allCheckboxes = container.querySelectorAll('tbody [type="checkbox"]');

        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;

        // Count checked boxes
        allCheckboxes.forEach(c => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        // Toggle toolbars
        if (checkedState) {
            selectedCount.innerHTML = count;
            toolbarBase.classList.add('d-none');
            toolbarSelected.classList.remove('d-none');
        } else {
            toolbarBase.classList.remove('d-none');
            toolbarSelected.classList.add('d-none');
        }
    }

    // Public methods
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
            initToggleToolbar();
            handleFilterDatatable();
            handleDeleteRows();
            handleActivationUser();
            handleNotActiveUser();
            handleResetForm();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();
});
</script>
@endsection
