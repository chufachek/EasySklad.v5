$(function () {
    const themeToggle = $('#themeToggle');
    const storedTheme = localStorage.getItem('theme') || 'light';
    $('html').attr('data-theme', storedTheme);

    themeToggle.on('click', function () {
        const current = $('html').attr('data-theme');
        const next = current === 'light' ? 'dark' : 'light';
        $('html').attr('data-theme', next);
        localStorage.setItem('theme', next);
    });

    function recalcTotals($form) {
        let net = 0;
        let vat = 0;
        let gross = 0;
        const taxMode = $form.data('tax-mode');
        const vatMode = $form.data('vat-mode');
        $form.find('tbody.pos-items tr').each(function () {
            const qty = parseFloat($(this).find('.qty-input').val()) || 0;
            const price = parseFloat($(this).find('.price-input').val()) || 0;
            const vatRate = $(this).find('.vat-input').val();
            let lineNet = 0;
            let lineVat = 0;
            let lineGross = 0;
            let rateValue = 0;
            if (vatRate === '20') rateValue = 0.2;
            if (vatRate === '10') rateValue = 0.1;
            if (vatRate === '0') rateValue = 0;

            if (taxMode === 'no_vat' || vatRate === 'none') {
                lineGross = price * qty;
                lineNet = lineGross;
                lineVat = 0;
            } else if (vatMode === 'included') {
                lineGross = price * qty;
                lineVat = lineGross * rateValue / (1 + rateValue);
                lineNet = lineGross - lineVat;
            } else {
                lineNet = price * qty;
                lineVat = lineNet * rateValue;
                lineGross = lineNet + lineVat;
            }
            net += lineNet;
            vat += lineVat;
            gross += lineGross;
            $(this).find('.line-total').text(lineGross.toFixed(2));
        });
        let html = '';
        if (taxMode === 'vat') {
            html += '<div>Итого без НДС: ' + net.toFixed(2) + '</div>';
            html += '<div>НДС: ' + vat.toFixed(2) + '</div>';
            html += '<div>Итого с НДС: ' + gross.toFixed(2) + '</div>';
        } else {
            html += '<div>Итого: ' + gross.toFixed(2) + '</div>';
        }
        $form.find('.pos-totals').html(html);
    }

    function bindRowEvents($row) {
        $row.find('.qty-input, .price-input, .vat-input').on('input change', function () {
            recalcTotals($row.closest('form'));
        });
        $row.find('.product-select').on('change', function () {
            const option = $(this).find('option:selected');
            const priceInput = $row.find('.price-input');
            const vatSelect = $row.find('.vat-input');
            const formAction = $row.closest('form').attr('action');
            if (formAction.indexOf('receipt') !== -1) {
                priceInput.val(option.data('cost') || 0);
            } else {
                priceInput.val(option.data('sell') || 0);
            }
            if (option.data('vat')) {
                vatSelect.val(option.data('vat'));
            }
            recalcTotals($row.closest('form'));
        });
        $row.find('.remove-row').on('click', function () {
            const $form = $row.closest('form');
            if ($form.find('tbody.pos-items tr').length > 1) {
                $row.remove();
                recalcTotals($form);
            }
        });
    }

    $('.pos-form').each(function () {
        const $form = $(this);
        $form.find('tbody.pos-items tr').each(function () {
            bindRowEvents($(this));
        });
        $form.find('.add-row').on('click', function () {
            const $newRow = $form.find('tbody.pos-items tr:first').clone();
            $newRow.find('input').val('');
            $newRow.find('.qty-input').val(1);
            $newRow.find('.price-input').val(0);
            $form.find('tbody.pos-items').append($newRow);
            bindRowEvents($newRow);
        });
        $form.find('.counterparty-select').on('change', function () {
            const hasCounterparty = $(this).val();
            const debtInput = $form.find('.pay-debt');
            if (hasCounterparty) {
                debtInput.prop('disabled', false);
            } else {
                debtInput.prop('disabled', true);
                $form.find('.pay-cash').prop('checked', true);
            }
        });
        recalcTotals($form);
    });
});
