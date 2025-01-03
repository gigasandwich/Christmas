$(document).ready(function () {
    /* Not working because the buttons are dynamically added, so we use event delegation HONO?? */
    // $('.reject-btn').on('click', function(e){
    //     e.preventDefault(); // Doesn't print the json response on the navigator 

    //     $.ajax({
    //         url: $(this).attr('href'),
    //         type: 'GET',
    //         dataType: 'json',        
    //         success: function (response) {
    //             updateTable(response);
    //         },
    //         error: function (xhr, status, error) {
    //             alert("Error rejecting deposit.", xhr, status, error);
    //         }
    //     })
    // });

    // REJECT
    $('#deposit-list').on('click', '.reject-btn', function (e) {
        e.preventDefault(); 

        $.ajax({
            url: $(this).attr('href'),
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                updateTable(response);
            },
            error: function (xhr, status, error) {
                alert("Error rejecting deposit.");
            }
        });
    });

    // ADD
    $('#deposit-list').on('click', '.accept-btn', function (e) {
        e.preventDefault(); 

        $.ajax({
            url: $(this).attr('href'),
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                updateTable(response);
            },
            error: function (xhr, status, error) {
                alert("Error accepting deposit.");
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
                        <td>${deposit['amount']} $</td>
                        <td>${deposit['date']}}/td>
                        <td>
                            <div class="d-flex gap-3">
                                <a href="/api/reject/deposit/${deposit['move_id']}" class="btn btn-outline-danger btn-sm w-50 reject-btn">
                                    <i class="fa fa-trash"></i> Reject
                                </a>
                                <a href="/api/accept/deposit/${deposit['move_id']}" class="btn btn-success btn-sm w-50 accept-btn">
                                    <i class="fa fa-thumb"></i> Accept
                                </a>
                            </div>
                        </td>
                    </tr>
                    `;
            $('#deposit-list tbody').append(html);
        });
    };
});
