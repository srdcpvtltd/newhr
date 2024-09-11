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



        // ajax will be defined here 
        // Listen for changes to the department select field
        $('#department').on('change', function() {
            var departmentId = $(this).val();

            // Make an AJAX request to retrieve the users for the selected department
            $.ajax({
                type: 'GET',
                url: "{{ url('admin/crmenquery/get-users-by-department') }}" + '/' +
                    departmentId,
                success: function(data) {
                    // Update the assign user select field with the retrieved users
                    $('#assign_user').empty();
                    $.each(data, function(index, user) {
                        if (user.id != 1) {
                            $('#assign_user').append('<option value="' + user.id +
                                '">' + user.name + '</option>');
                        }
                    });
                }
            });
        });

        // modal code 
        $(document).ready(function() {
    $('.enquire-modal-trigger').on('click', function(event) {
        event.preventDefault();
        // var enquireId = $(this).val(); 
        var enquireId = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: "{{ url('admin/crmenquery/crm-enqueries') }}" + '/' +
            enquireId,
            success: function(data) {
                $('#enquire-data').html('');
                $('#enquire-data').append('<p><strong>Name:</strong> ' + data.name + '</p>');
                $('#enquire-data').append('<p><strong>Email:</strong> ' + data.email + '</p>');
                $('#enquire-data').append('<p><strong>Number:</strong> ' + data.number + '</p>');
                $('#enquire-data').append('<p><strong>Address:</strong> ' + data.address + '</p>');
                $('#enquire-data').append('<p><strong>Message:</strong> ' + data.message + '</p>');
                $('#enquire-modal').modal('show');
            }
        });
    });
});




    });
</script>
