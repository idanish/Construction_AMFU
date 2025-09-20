@extends('master')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
                </div>
                <div class="h4 m-0">Budgets</div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block">
                    <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary mb-3">Create Budget</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card ">
        <div class="card-body">
            <div class="table-responsive">
                <table id="budgetsTable" class="table table-bordered table-striped">
                    <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark ">
                        <tr class="text-center align-middle fw-bold ">
                            <th>No</th>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Allocated</th>
                            <th>Spent</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Attachment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    @endsection

    @section('js')
        <script>
            $(document).ready(function() {
                var table = $('#budgetsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('finance.budgets.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'department',
                            name: 'department'
                        },
                        {
                            data: 'year',
                            name: 'year'
                        },
                        {
                            data: 'allocated',
                            name: 'allocated'
                        },
                        {
                            data: 'spent',
                            name: 'spent'
                        },
                        {
                            data: 'balance',
                            name: 'balance'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'attachment',
                            name: 'attachment',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });

                // SweetAlert delete confirmation
                $(document).on('click', '.delete-btn', function() {
                    var id = $(this).data('id');
                    var url = "{{ url('finance/budgets') }}/" + id;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This budget will be deleted permanently!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire('Deleted!', response.success, 'success');
                                    table.ajax.reload();
                                },
                                error: function(error) {
                                    Swal.fire('Error!', 'Something went wrong', 'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endsection
