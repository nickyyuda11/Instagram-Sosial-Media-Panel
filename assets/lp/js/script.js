$(document).ready(function () {
    // CALL FUNCTION SHOW PRODUCT
    show_product();

    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;

    // var pusher = new Pusher('13fa0cc30c051e629cb0', {
    //     cluster: 'ap1',
    //     forceTLS: true
    // });

    // var channel = pusher.subscribe('my-channel');
    // channel.bind('my-event', function(data) {
    //     if (data.message === 'success') {
    //         show_product();
    //     }
    // });

    // FUNCTION SHOW PRODUCT
    function show_product() {
        $.ajax({
            url: '<?php echo base_url("Client_Transaksi/getTransaksi"); ?>',
            type: 'GET',
            async: true,
            dataType: 'json',
            success: function (data) {
                var html = '';
                var count = 1;
                var i;
                for (i = 0; i < data.length; i++) {
                    html += '<tr>' +
                        '<td>' + count++ + '</td>' +
                        '<td>' + data[i].LINK + '</td>' +
                        '<td>' + data[i].JUMLAH + '</td>' +
                        '<td>' + data[i].DATE + '</td>' +
                        '<td>' +
                        '</td>' +
                        '</tr>';
                }
                $('.show_product').html(html);
            }

        });
    }

    // CREATE NEW PRODUCT
    $('.btn-save').on('click', function () {
        var product_name = $('.name').val();
        var product_price = $('.price').val();
        $.ajax({
            url: '<?php echo site_url("product/create"); ?>',
            method: 'POST',
            data: {
                product_name: product_name,
                product_price: product_price
            },
            success: function () {
                $('#ModalAdd').modal('hide');
                $('.name').val("");
                $('.price').val("");
                show_product();
            }
        });
    });
    // END CREATE PRODUCT

    // UPDATE PRODUCT
    $('#mytable').on('click', '.item_edit', function () {
        var product_id = $(this).data('id');
        var product_name = $(this).data('name');
        var product_price = $(this).data('price');
        $('#ModalEdit').modal('show');
        $('.id_edit').val(product_id);
        $('.name_edit').val(product_name);
        $('.price_edit').val(product_price);
    });

    $('.btn-edit').on('click', function () {
        var product_id = $('.id_edit').val();
        var product_name = $('.name_edit').val();
        var product_price = $('.price_edit').val();
        $.ajax({
            url: '<?php echo site_url("product/update"); ?>',
            method: 'POST',
            data: {
                product_id: product_id,
                product_name: product_name,
                product_price: product_price
            },
            success: function () {
                $('#ModalEdit').modal('hide');
                $('.id_edit').val("");
                $('.name_edit').val("");
                $('.price_edit').val("");
                show_product();
            }
        });
    });
    // END EDIT PRODUCT

    // DELETE PRODUCT
    $('#mytable').on('click', '.item_delete', function () {
        var product_id = $(this).data('id');
        $('#ModalDelete').modal('show');
        $('.product_id').val(product_id);
    });

    $('.btn-delete').on('click', function () {
        var product_id = $('.product_id').val();
        $.ajax({
            url: '<?php echo site_url("product/delete"); ?>',
            method: 'POST',
            data: {
                product_id: product_id
            },
            success: function () {
                $('#ModalDelete').modal('hide');
                $('.product_id').val("");
                show_product();
            }
        });
    });
    // END DELETE PRODUCT

});