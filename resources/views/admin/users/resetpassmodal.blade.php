<div class="modal fade" id="resetpassmodal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="resetpassmodalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetpassmodalLabel">{{ trans('labels.reset_password') }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" novalidate="" method="post" data-next="{{ route('password.reset') }}"
                id="resetpassform">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="userid" id="userid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="new_password"
                                    class="form-label required">{{ trans('labels.new_password') }}</label>
                                <input type="password" class="form-control" name="new_password" id="new_password"
                                    placeholder="{{ trans('labels.new_password') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="confirm_password"
                                    class="form-label required">{{ trans('labels.confirm_password') }}</label>
                                <input type="password" class="form-control" name="confirm_password"
                                    id="confirm_password" placeholder="{{ trans('labels.confirm_password') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" type="reset"
                        data-bs-dismiss="modal">{{ trans('labels.cancel') }}</button>
                    <button class="btn btn-primary" type="submit">{{ trans('labels.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('body').on('click', ' .reset-password', function(params) {
        "use strict";
        $('#userid').val($(this).attr('data-uid'));
    });
    $('#resetpassmodal').on('hidden.bs.modal', function() {
        "use strict";
        $('#userid').val('');
    });
    $('#resetpassform').submit(function(event) {
        "use strict";
        event.preventDefault();
        $('.err').remove();
        var formData = $(this).serialize();
        $.ajax({
            url: $(this).attr('data-next'),
            type: 'POST',
            data: formData,
            beforeSend: function(response) {
                showpreloader()
            },
            success: function(response) {
                hidepreloader()
                if (response.status == 0) {
                    if (response.errors && Object.keys(response.errors).length > 0) {
                        $.each(response.errors, function(key, value) {
                            if (key == 'userid') {
                                showtoast('danger', value);
                            } else {
                                $('#' + key).parent().append(
                                    '<small class="err text-danger">' + value +
                                    '</small>')
                            }
                        });
                    } else {
                        showtoast('danger', response.message)
                    }
                    return false;
                } else {
                    showtoast('success', response.message)
                    $('#resetpassmodal').modal('hide');
                    $('#resetpassform').removeClass('was-validated').find('button[type="reset"]')
                        .click();
                    $('#table_subadmins').bootstrapTable('refresh');
                }
            },
            error: function(xhr, status, error) {
                hidepreloader()
                showtoast('danger', wrong)
                return false;
            }
        });
    });
</script>
