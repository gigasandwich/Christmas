$(document).ready(function () {
    // Show gifts after filling the form 
    let totalPrice = 0;
    $("#view-gifts").click(function (e) {
        e.preventDefault();

        const girlCount = $("#girl").val() || 0;
        const boyCount = $("#boy").val() || 0;

        if (girlCount <= 0 && boyCount <= 0) {
            alert("Please enter the number of boys or girls to suggest gifts for");
            return;
        }

        fetchGiftSuggestions(girlCount, boyCount);
    });

    // Replace gifts
    $('#gift-list').on('click', '.btn-primary', function (e) {
        e.preventDefault();
        const card = $(this).closest('.col-md-4');
        const giftIndex = card.data('index');

        replaceGift(card, giftIndex);
    });

    // Ajax 
    function fetchGiftSuggestions(girls, boys) {
        $.ajax({
            url: '/api/gifts',
            type: 'GET',
            data: { girls, boys },
            success: function (response) {
                console.log(response);

                const gifts = response.gifts;
                totalPrice = response.total_price; // Already defined up there to use it below
                const giftList = $('#gift-list .row');
                giftList.empty();

                if (!gifts || gifts.length === 0) {
                    giftList.append('<p class="text-center">No gifts available for the selected input.</p>');
                    return;
                }

                gifts.forEach(gift => {
                    giftList.append(createGiftCard(gift));
                });
            $("#total-price-button").attr('data-value', totalPrice); 
            $("#total-price-button").text(`$${totalPrice}`);
            },
            error: function () {
                alert("Error fetching gifts.");
            }
        });
    }

    function replaceGift(card, giftIndex) {
        // Loading: so we put a sleep in the controller 
        const btn = card.find('.btn-primary');
        btn.text('Loading...').prop('disabled', true);

        $.ajax({
            url: '/api/replace-gift',
            method: 'GET', // TODO: make it POST method but too lazy
            data: { index: giftIndex },
            success: function (response) {
                const newGift = response.new_gift;
                totalPrice = response.total_price;

                card.find('.card-img-top')
                    .attr('src', '/assets/img/gifts/' + newGift['pic'])
                    .attr('alt', newGift['gift_name']);
                card.find('.card-title').text(newGift['gift_name']);
                card.find('.card-text').first().text(newGift['description']);
                card.find('.card-text strong').next().text(`$${newGift['price']}`);
                card.data('gift-id', newGift['gift_id']);

                // Reset the button 
                btn.text('Replace Gift').prop('disabled', false);
                // Update the total price displayed on the page
                $('#total-price-button').text(`$${totalPrice}`);
            },
            error: function () {
                alert('Error replacing the gift. Please try again.');
                btn.text('Replace Gift').prop('disabled', false);
            }
        });
    }

    // Helper methods
    function getCategoryIcon(categoryId) {
        switch (categoryId) {
            case 1:
                return 'fa-venus';
            case 2:
                return 'fa-mars';
            case 3:
                return 'fa-genderless';
            default:
                return '';
        }
    }

    function createGiftCard(gift) {
        const categoryIcon = getCategoryIcon(gift['category_id']);
        return `
            <div class="col-md-4 mb-4" data-index=${gift['index']}>
                <div class="card shadow-sm d-flex flex-column" style="height: 100%;">
                    <div class="position-relative" style="height: 200px;">
                        <img src="/assets/img/gifts/${gift['pic']}" class="card-img-top" alt="${gift['gift_name']}" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 p-2 text-white" style="background-color: rgba(0, 0, 0, 0.6); border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);">
                            <p class="m-0" style="font-size: 1.1em; font-weight: bold;">
                                <i class="fas ${categoryIcon}"></i>
                            </p>
                        </div>
                        <div class="position-absolute bottom-0 end-0 p-2 text-white" style="background-color: rgba(0, 0, 0, 0.6); border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);">
                            <p class="m-0" style="font-size: 1.1em;">
                                <i class="fas fa-tag"></i> $${gift['price']}
                            </p>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column flex-grow-1">
                        <h5 class="card-title">${gift['gift_name']}</h5>
                        <p class="card-text">${gift['description']}</p>
                        <div class="mt-auto">
                            <a href="#" class="btn btn-primary w-100">Replace Gift</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Final validation
    let balance = parseFloat($('#balance').attr('value'));
    $("#validate-btn").click(function () {
        const remainingBalance = balance - totalPrice;

        if (remainingBalance < 0) {
            alert("You do not have enough balance to finalize the purchase.");
            return;
        }

        // Populate modal with calculated values
        $("#totalCost").text(`$${totalPrice}`);
        $("#remainingBalance").text(`$${remainingBalance}`);
        $("#confirmationModal").modal("show");
    });

    // Submit form when modal confirmation is accepted
    $("#confirmValidation").click(function () {
        $("#totalPriceInput").val(totalPrice);
        const remainingBalance = balance - totalPrice;
        $("#remainingBalanceInput").val(remainingBalance);
        $("#validationForm").submit();
    });
    
});
