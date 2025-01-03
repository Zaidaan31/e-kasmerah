

$(document).ready(function() {
    $('#myTable').DataTable({
        scrollX: true,
        info: false
    });

   

    $('.dataTables_length').each(function() {
        $(this).html(
            '<label style="display: flex; align-items: center;">' +
            '<i class="fa fa-list" style="margin-right: 8px; color: #888;"></i>' +
            $(this).find('select').prop('outerHTML') +
            '</label>'
        );
    });

    // Delete user functionality
    $('.delete-btn').click(function() {
        var userId = $(this).data('id');
        var row = $(this).closest('tr');

        if (confirm("Are you sure you want to delete this user?")) {
            $.ajax({
                url: '../deleteuser.php',
                type: 'POST',
                data: {
                    id: userId
                },
                success: function(response) {
                    if (response === 'success') {
                        alert("User deleted successfully.");
                        row.remove();
                    } else {
                        alert("Failed to delete user.");
                    }
                },
                error: function() {
                    alert("Error in AJAX request.");
                }
            });
        }
    });
});