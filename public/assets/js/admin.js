$(document).ready(function () {
    /**
     * Not working because the buttons are dynamically added, so we use EVENT DELEGATION: 
     * If you instead directly bind the event using $('.btn-primary').click(...), 
     * it will only work for .btn-primary elements that exist at the time the handler is assigned. 
     * Any new buttons added after that won't trigger the click event. 
     * By attaching the event handler to a parent container (like #gift-list), you avoid potential duplication of code and ensure maintainability. You don't have to re-bind click handlers every time you update or replace the .btn-primary elements.
     * So $('.reject-btn').on('click', function(e){}) Won't work 
     */        

    // ----------------------------------------------------
    // Reject
    // ----------------------------------------------------
    $('#deposit-list').on('click', '.reject-btn', function (e) {
        e.preventDefault(); // Doesn't show the pretty print json response
        const depositId = $(this).data('deposit-id');
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            data: JSON.stringify({ deposit_id: depositId }),
            contentType: 'application/json',
            success: function (response) {
                updateTable(response);
            },
            error: function (xhr, status, error) {
                alert("Error rejecting deposit.", error);
            }
        });
    });

    // ----------------------------------------------------
    // Add
    // ----------------------------------------------------
    $('#deposit-list').on('click', '.accept-btn', function (e) {
        e.preventDefault(); 
        const depositId = $(this).data('deposit-id');
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            data: JSON.stringify({ deposit_id: depositId }),
            contentType: 'application/json',
            success: function (response) {
                updateTable(response);
            },
            error: function (xhr, status, error) {
                alert("Error accepting deposit.", error);
            }
        });
    });


    function updateTable(deposits) {
        $('#deposit-list tbody').empty();
        deposits.forEach(function (deposit) { 
        // Array.from(deposits).forEach(function (deposit) { // Alternative
            let html = `
                    <tr>
                        <td scope="row"><a href="/user/${deposit['user_id']}}">${deposit['username']}</a></td>
                        <td>${deposit['amount']}</td>
                        <td>${deposit['date']}</td>
                        <td>
                            <div class="d-flex gap-3">
                                <a href="/api/reject/deposit/" class="btn btn-outline-danger btn-sm w-50 reject-btn" data-deposit-id=${deposit['move_id']}>
                                    <i class="fa fa-trash"></i> Reject
                                </a>
                                <a href="/api/accept/deposit/" class="btn btn-success btn-sm w-50 accept-btn" data-deposit-id=${deposit['move_id']}>
                                    <i class="fa fa-thumb"></i> Accept
                                </a>
                            </div>
                        </td>
                    </tr>
                    `;
            $('#deposit-list tbody').append(html);
        });
    };

    // ----------------------------------------------------
    // CRUD 
    // ----------------------------------------------------
    
    $('.delete-btn').click(function () {
        return confirm("Are you sure you want to delete this record?");
    });
    
    $('#modifyModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let modal = $(this);
    
        // Filling datas to the modal
        let columnNames = ['gift_id', 'gift_name', 'category_name', 'price', 'description', 'stock_quantity'];
        for (let column in columnNames) { 
            let value = button.data(`${column}`);
            modal.find(`#modal-${column}`).val(value);
        } 
    
        let id = button.data('id');
        modal.find('#modal-id').val(id);
    
        let tableName = 'christmas_gift';
        let formAction = "/update/" + tableName + "/" + id;
        modal.find('form').attr('action', formAction);
    });
});



/*
With vanilla javascript (raw js), event deleagation should look like this:

giftList.addEventListener('click', function(event) {
    // Check if the clicked element has the 'btn-primary' class
    if (event.target && event.target.classList.contains('btn-primary')) {
        // Handle the click event
        console.log(`Gift clicked: ${event.target.textContent}`);
    }
});

// Function to add a new gift dynamically
document.getElementById('add-gift').addEventListener('click', function() {
    const newGift = document.createElement('div');
    newGift.classList.add('gift');
    newGift.innerHTML = `<button class="btn-primary">Gift ${giftList.children.length + 1}</button>`;
    giftList.appendChild(newGift);  // Append new gift to the list
});
*/