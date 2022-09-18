const clipboardButtons = document.querySelectorAll('#translator .copy-text');
clipboardButtons.forEach((clipboardButton) => {
    const tooltip = new bootstrap.Tooltip(clipboardButton, {
        delay: {
            show: 0,
            hide: 500
        }
    });
    clipboardButton.addEventListener('click', function (e) {
        navigator.clipboard.writeText(clipboardButton.innerText)
            .then(() => {
                tooltip.show();
                setTimeout(function () {
                    tooltip.hide();
                }, 2000);
            });
    });
});

$('#translator [data-edit-translate]').on('click', function () {
    let target = $(this).data('edit-translate');
    $('[data-edit-translate-target=' + target + ']').prop('readonly', false);
    $(this).prop('disabled', true);
    $('#publish').prop('disabled', true);
    $('#save').prop('disabled', false);
});
$('#translator [data-edit-translate-editor]').on('click', function () {
    let target = $(this).data('edit-translate-editor');
    $('[data-edit-translate-placeholder=' + target + ']').addClass('d-none');
    $('[data-edit-translate-target=' + target + ']').removeClass('d-none');
    $(this).prop('disabled', true);
    $('#publish').prop('disabled', true);
    $('#save').prop('disabled', false);
});