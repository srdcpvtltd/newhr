<script src="{{ asset('assets/vendors/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/js/tinymce.js') }}"></script>

<script src="{{ asset('assets/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.toggleStatus').change(function(event) {
            event.preventDefault();
            var status = $(this).prop('checked') === true ? 1 : 0;
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to change status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding: '10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                } else if (result.isDenied) {
                    (status === 0) ? $(this).prop('checked', true): $(this).prop('checked',
                        false)
                }
            })
        })

        $('.deleteClientDetail').click(function(event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: 'Are you sure you want to Delete Client Detail ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding: '10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        })

        // Lead Source modal code here

        // old edit script
        $('.editLeadSourceBtn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            // Set the modal title and button text for editing
            $('#editLeadSourceModalLabel').text('Edit Lead Source');

            // Pre-fill the form with the existing data
            $('#editLeadSourceName').val(name);

            $('#leadSourceId').val(id);

            // Set the action URL to the update route leadsource.update 
            $('#editLeadSourceForm').attr('action', '/admin/leads-setting/leadsource/update/' + id);
            
        });

        $('.deleteLeadSourceLink').on('click', function() {
            var id = $(this).data('id');
            var url = "{{ route('admin.leadsource.destroy', '') }}/" + id;
            $('#delete-leadsource-form').attr('action', url);
            $('#deleteleadsourceModal').modal('show');
        });


        // Lead Status Modal Code Here
        // edit code
        $('.editLeadStatusBtn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            // Set the modal title and button text for editing
            $('#editLeadStatusModalLabel').text('Edit Lead Status');

            // Pre-fill the form with the existing data
            $('#editLeadStatusName').val(name);

            // Set the action URL to the update route leadsource.update 
            $('#editLeadStatusForm').attr('action', '/admin/leads-setting/leadstatus/update/' + id);
            
        });
        // delete code
        $('.deleteLeadStatusLink').on('click', function() {
            var id = $(this).data('id');
            var url = "{{ route('admin.leadstatus.destroy', '') }}/" + id;
            $('#deleteLeadStatusForm').attr('action', url);
            $('#deleteLeadStatusModal').modal('show');
        });


        // Lead Agent Modal Code Here

        $('.deleteLeadAgentLink').on('click', function() {
            var id = $(this).data('id');
            var url = "{{ route('admin.leadagent.delete', '') }}/" + id;
            $('#delete-form').attr('action', url);
            $('#deleteModal').modal('show');
        });



        // Lead Category Modal Code Here

        $('.editLeadCategoryBtn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            // Set the modal title and button text for editing
            $('#editLeadCategoryModalLabel').text('Edit Lead Category');

            // Pre-fill the form with the existing data
            $('#editLeadCategoryName').val(name);

            // Set the action URL to the update route leadCategory.update 
            $('#editLeadCategoryForm').attr('action', '/admin/leads-setting/leadcategory/update/' + id);
        });

        $('.deleteLeadCategoryLink').on('click', function() {
            var id = $(this).data('id');
            var url = "{{ route('admin.leadcategory.destroy', '') }}/" + id;
            $('#delete-leadcategory-form').attr('action', url);
            $('#deleteleadcategoryModal').modal('show');
        });








    });
</script>
