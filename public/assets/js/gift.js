$(document).ready(function () {
    $("#view-gifts").click(function (e) {
        e.preventDefault();

        $.ajax({
            url: '/api/gifts',  
            type: 'GET',
            beforeSend: function() {
                $('#loading').show();
            },
            complete: function() {
                $('#loading').hide();
            },
            success: function (response) {
                $('#gift-list .row').empty();

                response.forEach(function (gift) {
                    let html = `
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm" style="height: 100%; display: flex; flex-direction: column;">
                                <img src="${gift.pic}" class="card-img-top" alt="${gift.gift_name}">
                                <div class="card-body">
                                    <h5 class="card-title">${gift.gift_name}</h5>
                                    <p class="card-text">${gift.description}</p>
                                    <p class="card-text"><strong>Price:</strong> $${gift.price}</p>
                                    <a href="#" class="btn btn-primary w-100">Replace Gift</a>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#gift-list .row').append(html);
                });
            },
            error: function () {
                alert("Error fetching gifts.");
            }
        });
    });
});
