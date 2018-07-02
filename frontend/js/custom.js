$('#sign-in').click(() => {
    $('#sign-in').attr('disabled', true);
    var form_data = {
        username: $('#signin-username').val(),
        password : $('#signin-password').val()
    };
    $.post('<?= base_url() ?>index.php/loginapi/loginapi', form_data,
        function (response) {
            if(response === 404) {
                $('#sign-in').attr('disabled', false);
                $('#response').text('*Account not found.')
            } else {
                $(location).attr("href", '<?= base_url() ?>index.php/user/dashboard');
            }
        });
});

$('#signup').click(() => {
    $('#signup').attr('disabled', true);
    var form_data = {
        username: $('#signup-username').val(),
        email: $('#signup-email').val(),
        password : $('#signup-password').val()
    };
    $.post('<?= base_url() ?>index.php/loginapi/signupapi', form_data, function (response) {
        if(response === 400) {
            $('#signup').attr('disabled', false);
            $('#signup-message').text('Username/Email already registered.');
        } else {
            $(location).attr("href", '<?= base_url() ?>index.php/user/dashboard');
        }
    });
});
$('#newsletter-btn').click(() => {
    var email = $('#newsletter-email').val();
    $('#newsletter-btn').attr('disabled', true);
    $.post('<?= base_url() ?>index.php/newsletter/subscribe',
        {email: email}, function (response) {
            $('#newsletter-btn').attr('disabled', false);
            $('#newsletter-msg').text(response);
        });
});

var items = [];
var total = paypal.minicart.cart.total();
if(total === 0){
    $('#checkout-grid').html('<p> Your cart is empty. </p>');
    $('#checkout-form').hide();
} else {
    paypal.minicart.cart.items().forEach( item => {
        items.push({
            id: item._data.product_id,
            name: item._data.item_name,
            amount: item._amount,
            quantity: item._data.quantity,
            currency: item._data.currency
        })
    });
    var html = '';
    items.map(item => {

        html = html + '<tr>\n' +
            '                <td>'+item.name+'</td>\n' +
            '                <td>'+item.currency+'</td>\n' +
            '                <td>'+item.quantity+'</td>\n' +
            '                <td>'+item.amount+'</td>\n' +
            '            </tr>'
    });
    html = '<div class="tab1">\n' +
        '\n' +
        '                        <div class="single_page_agile_its_w3ls">\n' +
        '                            <div class="bootstrap-tab-text-grids">' +
        '                               <table class="table">\n' +
        '                                    <thead>\n' +
        '                                    <tr>\n' +
        '                                        <th>Product</th>\n' +
        '                                        <th>Currency</th>\n' +
        '                                        <th>Quantity</th>\n' +
        '                                        <th>Price</th>\n' +
        '                                    </tr>\n' +
        '                                    </thead>\n' +
        '                                    <tbody>' + html + '</tbody>\n' +
        '                                </table> <div class="total">Total: '+total+'</div></div>\n' +
        '                        </div>\n' +
        '                    </div> ';
    $('#checkout-grid').html(html);
}

$('#place-order').click(function () {
    $('#place-order').attr('disabled', true);
    var data = {
        products : items,
        full_name : $('#fname').val(),
        email : $('#email').val(),
        contact : $('#contact').val(),
        shipping_address : $('#adr').val(),
        shipping_city_state_country : $('#city').val(),
        total_amount: total,
        currency: items[0].currency
    };
    $.post('<?= base_url() ?>index.php/product/placeorder',
        data, response => {
            response = JSON.parse(response);
            if(response.status === 200) {
                $('#message').text(response.message);
                paypal.minicart.reset();
                window.setTimeout(function () {
                    $(location).attr("href", '<?= base_url() ?>index.php/user/dashboard');
                }, 5);
            } else {
                $('#place-order').attr('disabled', false);
                $('#message').text(response.message);
            }
        });

});
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
function orderModal(index) {
    let order = JSON.parse('<?= json_encode($orders) ?>');
    order = order[index];
    let html = '';
    order.products.map(item => {

        html = html + '<tr>\n' +
            '                <td>'+item.product_title+'</td>\n' +
            '                <td>'+order.currency+'</td>\n' +
            '                <td>'+item.quantity+'</td>\n' +
            '                <td>'+item.price+'</td>\n' +
            '            </tr>'
    });
    html = '<div class="tab1">\n' +
        '\n' + '<p>Order #: '+order.order_id+'</p>'+
        '                            <div class="bootstrap-tab-text-grids">' +
        '                               <table class="table">\n' +
        '                                    <thead>\n' +
        '                                    <tr>\n' +
        '                                        <th>Product</th>\n' +
        '                                        <th>Currency</th>\n' +
        '                                        <th>Quantity</th>\n' +
        '                                        <th>Price</th>\n' +
        '                                    </tr>\n' +
        '                                    </thead>\n' +
        '                                    <tbody>' + html + '</tbody>\n' +
        '                                </table> <div class="total">Total: '+ order.total_amount+'</div>' +
        '                                <div><p>Name: '+order.full_name+'</p>' +
        '                                       <p>Contact #: '+order.contact+'</p>'+
        '                                       <p>Shipping Address: '+order.shipping_address+order.shipping_city_state_country+'</p>'+
        '                                       <p>Status: '+order.status+'</p>'+
        '                               </div>'+
        '                           </div>\n' +
        '                    </div> ';
    $('.modal-body').html(html);
}