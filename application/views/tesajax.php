<table class="show_product"></table>

<script type="text/javascript" src="<?php echo base_url() . 'assets/lp/js/jquery-3.5.1.js' ?>"></script>
<script>
    $(document).ready(function() {
        // CALL FUNCTION SHOW PRODUCT
        show_product();
        // FUNCTION SHOW PRODUCT
        function show_product() {
            $.ajax({
                url: '<?php echo site_url("Client_Transaksi/get_transaksi"); ?>',
                type: 'GET',
                async: true,
                data: {
                    type_ft: '0',
                    id_user: '<?= $this->session->userdata('id_user') ?>',
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    var html = '';
                    var count = 1;
                    var i;
                    for (i = 0; i < data.length; i++) {
                        html += '<tr>' +
                            '<td>' + count++ + '</td>' +
                            '<td>' + data[i].LINK + '</td>' +
                            '<td>' + data[i].TYPE_FT + '</td>' +
                            '</tr>';
                    }
                    $('.show_product').html(html);
                }

            });
        }
    });
</script>