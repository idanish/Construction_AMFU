@extends('master')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
                </div>
                <div class="h4 m-0"><span>Procurements</span></div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block">
                    <a href="{{ route('finance.procurements.create') }}" class="btn btn-primary mb-3">Create Procurement</a>
                </div>
            </div>
        </div>
    </div>


    <div class="main-card mb-3 card">
        <div class="card-body">
            <table id="procurementTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Cost Estimate</th>
                        <th>Department</th>
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
            var table = $('#procurementTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('finance.procurements.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'item_name',
                        name: 'item_name'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'cost_estimate',
                        name: 'cost_estimate'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name'
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
                    }
                ]
            });

            // SweetAlert delete confirmation
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                var url = "{{ url('finance/procurements') }}/" + id;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
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
