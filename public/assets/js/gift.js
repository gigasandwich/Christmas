$(document).ready(function () {
    $("#view-gifts").click(function (e) {
        e.preventDefault();

        $.ajax({
            url: '/api/gifts',
            type: 'GET',
            beforeSend: function () {
                $('#loading').show();
            },
            complete: function () {
                $('#loading').hide();
            },
            success: function (response) {
                $('#gift-list .row').empty();

                response.forEach(function (gift) {
                    // <div class="card shadow-sm d-flex flex-column" style="height: 100%;"> makes sure all elements are on the same dimension
                    let html = `
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm d-flex flex-column" style="height: 100%;">
                                <img src="/assets/img/gifts/${gift.pic}" class="card-img-top" alt="${gift.gift_name}" style="height: 200px; object-fit: cover;">
                                <div class="card-body d-flex flex-column flex-grow-1">
                                    <h5 class="card-title">${gift.gift_name}</h5>
                                    <p class="card-text">${gift.description}</p>
                                    <p class="card-text"><strong>Price:</strong> $${gift.price}</p>
                                    <div class="mt-auto">
                                        <a href="#" class="btn btn-primary w-100">Replace Gift</a>
                                    </div>
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


// For replacing 
$('#gift-list').on('click', '.btn-primary', function (e) {
    e.preventDefault();

    const card = $(this).closest('.card');
    const giftIndex = card.data('gift-index'); // Assuming each card has a data attribute for the index

    $.ajax({
        url: '/replace-gift',
        method: 'POST',
        data: { index: giftIndex },
        success: function (response) {
            if (response.error) {
                alert(response.error);
            } else {
                const newGift = response.new_gift;

                // Update the card with new gift details
                card.find('.card-img-top').attr('src', '/assets/img/gifts/' + newGift.pic).attr('alt', newGift.gift_name);
                card.find('.card-title').text(newGift.gift_name);
                card.find('.card-text').first().text(newGift.description);
                card.find('.card-text strong').next().text(`$${newGift.price}`);
                card.data('gift-id', newGift.gift_id); // Update the gift ID
            }
        },
        error: function () {
            alert('Error replacing the gift. Please try again.');
        }
    });
});
