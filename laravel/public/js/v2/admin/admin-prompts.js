$(function() {
	// ========================= ADMIN PROMPT FUNCTIONS ===============================
    // Toggle whether a prompt is active or not
    $('.admin-prompt .active').bind('change', function() {
        var prompt_id = $(this).data('prompt-id');
        var active = this.checked;
        console.log('prompt_id: ' + prompt_id + ' active: ' + active);
        $.ajax({
            url: window.site_url + 'admin/prompt/toggle-active',
            type: 'POST',
            data: {
                prompt_id: prompt_id,
                active: active
            },
            success: function ( data ) {
                if ( data.success ) {
                    console.log('success');
                } else {
                    alert('error');
                }
            }
        })
    });
    // Delete a prompt altogether
    $('.admin-prompt .delete').on('click', function() {
        var row = $(this).parent().parent();
        var prompt_id = $(this).prev().data('prompt-id');
        console.log('prompt_id: ' + prompt_id);
        $.ajax({
            url: window.site_url + 'admin/prompt/delete',
            type: 'POST',
            data: {
                prompt_id: prompt_id
            },
            success: function ( data ) {
                if ( data.success ) {
                    row.fadeOut(function() {
                        row.remove();
                    });
                } else {
                    alert('error');
                }
            }
        })
    });
});