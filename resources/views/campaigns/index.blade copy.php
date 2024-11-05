@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Campaigns</h3>
                {{-- <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator"> 
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Home</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">User List</a>
                    </li>
                </ul> --}}
                @if(hasPermission('campaigns_create') == true)
                <button class="btn btn-primary btn-round ms-auto d-flex align-items-center">
                    <i class="fa fa-plus"></i>
                   <a href="{{ route('campaigns.create') }}" style="color: azure;"> Add Row </a>
                </button>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>ActiVe / InActive</th>
                                            <th style="">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            var table = $('#add-row').DataTable({
                /* "paging": true,
                "lengthChange": true,
                "info": true,
                "autoWidth": false,
                "searching": false,
                "responsive": true, */
                "search": {
                    regex: true
                },
                "destory": true,
                "bDestroy": true,
                "pageLength": 10,
                "ordering": true,
                "processing": true,
                "serverSide": true,
                language: {
                    "search": "",
                    // "lengthMenu": "Show",
                    "sLengthMenu": " _MENU_ ",
                    searchPlaceholder: "Search",
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>', // or '→'
                        previous: '<i class="fas fa-chevron-left"></i>' // or '←' 
                    }
                },
                dom: 'frtlip',
                "searching": true,
                "autoWidth": false,
                "responsive": true,
                "columnDefs": [{
                    "orderable": false,
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    }
                }],
                'select': {
                    'style': 'multi'
                },
                order: [
                    [1, "asc"]
                ],
                ajax: "{{ route('getcampaignslist') }}",
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'due_date'
                    },
                    {
                        data: 'status_id'
                    },
                    {
                        data: 'is_active'
                    },
                    {
                        data: 'action'
                    },
                ],
            });
        });

    </script>
@endsection
