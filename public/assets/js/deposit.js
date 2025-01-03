$('#deposit-btn').on('click', (e) => {
    e.preventDefault();
    amount = $('#amount').val();
    $.ajax({
        url: '/api/add/deposit',
        type: 'POST',
        data: { amount },
        success: function (response) {
            alert("Error: " + response);
        }, 
        error: function (response) {
            alert("Error:" + response);
        }
    });
});