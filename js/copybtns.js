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