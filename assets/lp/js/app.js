
var base_url = 'http://localhost:8080/smpanel';

window.onscroll = function () {
    scrollFunction()
};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("movetop").style.display = "block";
    } else {
        document.getElementById("movetop").style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
$(document).ready(function () {
    $('#laporanfollower').DataTable();;
    $('text').select2({
        placeholder: "Silahkan Pilih"
    });
});
show_all_transaksi();

function show_all_transaksi() {
    var id_user = $('#id_user').text()
    $.ajax({
        url: base_url + '/Client_Transaksi/get_transaksi',
        type: 'POST',
        async: true,
        data: {
            id_user: id_user,
        },
        dataType: 'json',
        success: function (data) {
            if (data['h'] != '') {
                var x = setInterval(function () {
                    var countDownDate = new Date(data['month'] + ' ' + data['day'] + ', ' + data['year'] + ' ' + data['h'] + data['i'] + data['s']).getTime();
                    var asiaTime = new Date().toLocaleString("en-US", {
                        timeZone: "Asia/Jakarta"
                    });
                    var time = new Date(asiaTime).getTime();

                    // Find the distance between now and the count down date
                    var distance = countDownDate - time;

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // If the count down is finished, write some text
                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("timer").innerHTML = '<div class="alert alert-info" role="alert" hidden><h4 class="alert-heading text-center">DELAY PERSUBMIT</h4><p class="text-center display-4" id="time">' + days + "d " + hours + "h " + minutes + "m " + seconds + "s " + '</p></div>';
                        $('#btn-fl').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#addfl" id="btn-fl" > <i class="fas fa-arrow-up"></i> Tambah Followers</button>')
                        $('#btn-likes').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#paneladdlikes" id="btn-likes"> <i class="fas fa-arrow-up"></i> Tambah Likes</button>')
                        $('#btn-comment').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#panelcomment" id="btn-comment" > <i class="fas fa-arrow-up"></i> Tambah Comments</button>')
                        $('#btn-likescomment').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#panelcommentlikes" id="btn-likescomment"> <i class="fas fa-arrow-up"></i> Tambah Likes Comments</button>')
                        $('#btn-story').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#panelstory" id="btn-story" disabled> <i class="fas fa-arrow-up"></i> Tambah Views Story</button>')
                        $('#btn-video').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#panelvideo" id="btn-video" disabled> <i class="fas fa-arrow-up"></i> Tambah Views Video</button>')
                    } else {
                        document.getElementById("timer").innerHTML = '<div class="alert alert-info" role="alert"><h4 class="alert-heading text-center">DELAY PERSUBMIT</h4><p class="text-center display-4" id="time">' + days + "d " + hours + "h " + minutes + "m " + seconds + "s " + '</p></div>';
                        $('#btn-fl').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#addfl" id="btn-fl" disabled> <i class="fas fa-arrow-up"></i> Tambah Followers</button>')
                        $('#btn-likes').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#paneladdlikes" id="btn-likes" disabled> <i class="fas fa-arrow-up"></i> Tambah Likes</button>')
                        $('#btn-comment').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#panelcomment" id="btn-comment" disabled> <i class="fas fa-arrow-up"></i> Tambah Comments</button>')
                        $('#btn-likescomment').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#panelcommentlikes" id="btn-likescomment" disabled> <i class="fas fa-arrow-up"></i> Tambah Likes Comments</button>')
                        $('#btn-story').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#panelstory" id="btn-story" disabled> <i class="fas fa-arrow-up"></i> Tambah Views Story</button>')
                        $('#btn-video').replaceWith('<button class="btn btn-info" data-toggle="modal" data-target="#panelvideo" id="btn-video" disabled> <i class="fas fa-arrow-up"></i> Tambah Views Video</button>')
                    }
                }, 1000);
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText)
        }
    });
}
$(document).ready(function () {
    show_transaksi_likes();
    show_transaksi_followers();
    show_transaksi_commentlikes();
    show_transaksi_comment();
    show_count();
    // FUNCTION SHOW TRANSAKSI
    $('.examplemodal').on('shown.bs.modal', function () {
        $('#panelfl').trigger('focus')
        $('#addfl').trigger('focus')
        $('#panellikes').trigger('focus')
        $('#paneladdlikes').trigger('focus')
        $('#riwayatcommentlikes').trigger('focus')
        $('#riwayatcomment').trigger('focus')

    })
    $('.riwayatfollowers').on('click', function () {
        show_transaksi_followers();
    })
    $('.panellikes').on('click', function () {
        show_transaksi_likes();
    })
    $('.riwayatcomment').on('click', function () {
        show_transaksi_comment();
    })
    $('.riwayatcommentlikes').on('click', function () {
        show_transaksi_commentlikes();
    })

    function show_count() {
        $.ajax({
            url: base_url + '/Client_Transaksi/get_all_count',
            type: 'POST',
            async: true,
            dataType: 'json',
            success: function (data) {
                if (+data[0].jumlah == null)
                    $('#countfl').html('0')
                $('#countfl').html(+data[0].jumlah)
                if (+data[1].jumlah == null)
                    $('#countlikes').html('0')
                $('#countlikes').html(+data[1].jumlah)
                if (+data[2].jumlah == null)
                    $('#countcomments').html('0')
                $('#countcomments').html(+data[2].jumlah)
                if (+data[3].jumlah == null)
                    $('#countlikescm').html('0')
                $('#countlikescm').html(+data[3].jumlah)
                if (+data[4].jumlah == null)
                    $('#countvs').html('0')
                $('#countvs').html(+data[4].jumlah)
                if (+data[5].jumlah == null)
                    $('#countvv').html('0')
                $('#countvv').html(+data[5].jumlah)
            }
        })
    };

    function show_transaksi_followers() {
        $.ajax({
            url: base_url + '/Client_Transaksi/get_transaksi',
            type: 'POST',
            async: true,
            data: {
                type_ft: '1',
            },
            dataType: 'json',
            success: function (data) {
                var html = '';
                var count = 1;
                var i;
                if (data == '') {
                    html += '<p class="text-center">Data tidak ditemukan</p>';
                } else {
                    for (i = 0; i < data.length; i++) {
                        html += '<tr>' +
                            '<td>' + count++ + '</td>' +
                            '<td>' + data[i].LINK + '</td>' +
                            '<td>' + data[i].JUMLAH + '</td>' +
                            '<td>' + data[i].DATE + '</td>' +
                            '</tr>';
                    }
                }
                $('.show_transaksi').html(html);
            },
        });
    }
    $('.submitfl').on('click', function () {
        $('.sukses_fl').html('<div class=" alert alert-info">Menunggu sedang proses submit...</div>');
        add_followers();
    })

    function add_followers() {
        var links = $('#username').text();
        $.ajax({
            url: base_url + '/Client_Transaksi/add_followers',
            type: 'POST',
            async: true,
            data: {
                link: links,
                type_ft: '1',
            },
            dataType: 'json',
            success: function (data) {
                var html = '';
                if (data['msg'] == 'delay') {
                    html += '<div class=" alert alert-warning">Harus menunggu Delay!</div>';
                    $('.sukses_fl').html(html);
                } else if (data['msg'] == 'tidak ada link') {
                    html += '<div class=" alert alert-warning">Link harus diisi terlebih dahulu!</div>';
                    $('.sukses_fl').html(html);
                } else if (data['msg'] == 'username tidak sama') {
                    html += '<div class=" alert alert-warning">Username harus sama dengan Akun!</div>';
                    $('.sukses_fl').html(html);
                } else if (data['msg'] == 'username tidak ditemukan') {
                    html += '<div class=" alert alert-warning">Username tidak ditemukan!</div>';
                    $('.sukses_fl').html(html);
                } else if (data['msg'] == 'success') {
                    html += '<div class=" alert alert-success">Sukses Submit Followers</div>';
                    $('.sukses_fl').html(html);
                    show_count();
                    show_all_transaksi();
                }
            },
        });
    }

    function show_transaksi_likes() {
        $.ajax({
            url: base_url + '/Client_Transaksi/get_transaksi',
            type: 'POST',
            async: true,
            data: {
                type_ft: '2',
            },
            dataType: 'json',
            success: function (data) {
                var html = '';
                var count = 1;
                var i;
                if (data == '') {
                    html += '<p class="text-center">Data tidak ditemukan</p>';
                } else {
                    for (i = 0; i < data.length; i++) {
                        html += '<tr>' +
                            '<td>' + count++ + '</td>' +
                            '<td>' + data[i].LINK + '</td>' +
                            '<td>' + data[i].JUMLAH + '</td>' +
                            '<td>' + data[i].DATE + '</td>' +
                            '</tr>';
                    }
                }
                $('.show_transaksi_likes').html(html);
            }
        });
    }
    $('#addlikes').on('click', function () {
        $('.sukses_likes').html('<div class=" alert alert-info">Menunggu sedang proses submit...</div>');
        add_likes();
    })

    function add_likes() {
        var links = $('.linklikes').val();

        $.ajax({
            url: base_url + '/Client_Transaksi/add_likes',
            type: 'POST',
            async: true,
            data: {
                link: links,
                type_ft: '2',
            },
            dataType: 'json',
            success: function (data) {
                var html = '';
                if (data['msg'] == 'delay') {
                    html += '<div class=" alert alert-warning">Harus menunggu Delay!</div>';
                    $('.sukses_likes').html(html);
                } else if (data['msg'] == 'username tidak sama') {
                    html += '<div class=" alert alert-warning">Username harus sama dengan Akun!</div>';
                    $('.sukses_likes').html(html);
                } else if (data['msg'] == 'tidak ada link') {
                    html += '<div class=" alert alert-warning">Link harus diisi terlebih dahulu!</div>';
                    $('.sukses_likes').html(html);
                } else if (data['msg'] == 'success') {
                    html += '<div class=" alert alert-success">Sukses Submit Likes</div>';
                    $('.sukses_likes').html(html);
                    show_count();
                    show_all_transaksi();
                }
            },
        });
    }

    $('#cekcomment').on('click', function () {
        $('.sukses_comment_likes').html('<div class=" alert alert-info">Menunggu sedang proses cek ID Komentar...</div>');
        cek_comment();
    })

    function cek_comment() {
        var links = $('.linkcommentlikes').val();
        $.ajax({
            url: base_url + '/Client_Transaksi/cek_comments',
            type: 'post',
            async: true,
            data: {
                link: links,
            },
            dataType: 'json',
            success: function (data) {
                var html = ''
                if (data['msg'] == 'delay') {
                    html += '<div class=" alert alert-warning">Harus menunggu Delay!</div>';
                    $('.sukses_comment_likes').html(html);
                } else if (data['msg'] == 'tidak ada link') {
                    html += '<div class=" alert alert-warning">Link harus diisi terlebih dahulu!</div>';
                    $('.sukses_comment_likes').html(html);
                } else {
                    if (data['msg'] == 'username ditemukan') {
                        html += '<div class="alert alert-info cekcomment">Sukses ambil ID Komentar</div>' +
                            '<div class="alert alert-info commentid">' + data['commentid'] +
                            '</div>';
                        $('.sukses_comment_likes').html(html)
                    } else {
                        html += '<div class="alert alert-info cekcomment">Gagal ambil ID Likes</div>';
                        $('.sukses_comment_likes').html(html)
                    }
                }
            }
        })
    }

    $('#submitcommentlikes').on('click', function () {
        $('.cekcomment').replaceWith('<div class=" alert alert-info cekcomment">Menunggu sedang proses submit...</div>');
        like_comment();
    })

    function like_comment() {
        var links = $('.commentid').text()
        $.ajax({
            url: base_url + '/Client_Transaksi/add_comments_likes',
            type: 'post',
            async: true,
            data: {
                link: links,
                type_ft: '4',
            },
            dataType: 'json',
            success: function (data) {
                var html = ''
                if (data['msg'] == 'delay') {
                    html += '<div class=" alert alert-warning">Harus menunggu Delay!</div>';
                    $('.sukses_comment_likes').html(html);
                } else if (data['msg'] == 'tidak ada link') {
                    html += '<div class=" alert alert-warning">Link harus diisi terlebih dahulu!</div>';
                    $('.sukses_comment_likes').html(html);
                } else if (data['msg'] == 'success') {
                    html += '<div class=" alert alert-success cekcomment">Sukses Submit Comment Likes</div>'
                    $('.cekcomment').replaceWith(html)
                    show_count();
                    show_all_transaksi();
                }
            }
        })
    }

    function show_transaksi_commentlikes() {
        $.ajax({
            url: base_url + '/Client_Transaksi/get_transaksi',
            type: 'POST',
            async: true,
            data: {
                type_ft: '4',
            },
            dataType: 'json',
            success: function (data) {
                var html = '';
                var count = 1;
                var i;
                if (data == '') {
                    html += '<p class="text-center">Data tidak ditemukan</p>';
                } else {
                    for (i = 0; i < data.length; i++) {
                        html += '<tr>' +
                            '<td>' + count++ + '</td>' +
                            '<td>' + data[i].LINK + '</td>' +
                            '<td>' + data[i].JUMLAH + '</td>' +
                            '<td>' + data[i].DATE + '</td>' +
                            '</tr>';
                    }
                }
                $('.show_transaksi_commentlikes').html(html);
            }
        });
    }
    $('#submitcomment').on('click', function () {
        $('.sukses_comment').html('<div class=" alert alert-info">Menunggu sedang proses submit...</div>');
        add_comment();
    })

    function add_comment() {
        var links = $('.linkcomment').val()
        var text = $('#text').val()
        $.ajax({
            url: base_url + '/Client_Transaksi/add_comments',
            type: 'post',
            async: true,
            data: {
                link: links,
                text: text,
                type_ft: '3',
            },
            dataType: 'json',
            success: function (data) {
                var html = ''
                if (data['msg'] == 'delay') {
                    html += '<div class=" alert alert-warning">Harus menunggu Delay!</div>';
                    $('.sukses_comment').html(html);
                } else if (data['msg'] == 'tidak ada link') {
                    html += '<div class=" alert alert-warning">Link harus diisi terlebih dahulu!</div>';
                    $('.sukses_comment').html(html);
                } else if (data['msg'] == 'tidak ada komen') {
                    html += '<div class=" alert alert-warning">Komen harus diisi terlebih dahulu!</div>';
                    $('.sukses_comment').html(html);
                } else if (data['msg'] == 'username tidak sama') {
                    html += '<div class=" alert alert-warning">Username harus sama dengan Akun!</div>';
                    $('.sukses_comment').html(html);
                } else if (data['msg'] == 'success') {
                    html += '<div class=" alert alert-info">Sukses Submit Comment</div>';
                    $('.sukses_comment').html(html);
                    show_count();
                    show_all_transaksi();
                }
            }
        })
    }

    function show_transaksi_comment() {
        $.ajax({
            url: base_url + '/Client_Transaksi/get_transaksi',
            type: 'POST',
            async: true,
            data: {
                type_ft: '3',
            },
            dataType: 'json',
            success: function (data) {
                var html = '';
                var count = 1;
                var i;
                if (data == '') {
                    html += '<p class="text-center">Data tidak ditemukan</p>';
                } else {
                    for (i = 0; i < data.length; i++) {
                        html += '<tr>' +
                            '<td>' + count++ + '</td>' +
                            '<td>' + data[i].LINK + '</td>' +
                            '<td>' + data[i].JUMLAH + '</td>' +
                            '<td>' + data[i].DATE + '</td>' +
                            '</tr>';
                    }
                }
                $('.show_transaksi_comment').html(html);
            }
        });
    }

});
