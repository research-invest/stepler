(function () {
    'use strict'

    let perPageElem = $('#select-per-page'),
        periodsElem = $('#dropdown-periods li'),
        perPageValue, periodValue, page = null;

    perPageElem.on('change', () => {
        perPageValue = perPageElem.val();
        updatePairs();
    });

    periodsElem.on('click', (e) => {
        periodValue = e.target.getAttribute('data-value');
        updatePairs();
    });

    $(document).on('click', '.paginate-pairs', (e) => {
        page = e.target.getAttribute('data-value');
        updatePairs();
    });

    function updatePairs() {
        $('.loader').show();
        $.ajax({
            url: '/',
            data: {
                period: periodValue,
                per_page: perPageValue,
                page: page,
            },
            type: 'GET',
            success: function (data) {
                $('#pairs-content').html(data);
                $('.loader').hide();
            }
        });
    }

    setInterval(() => updatePairs(), 60 * 1000);
})()



