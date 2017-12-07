tinymce.init({
    // General options
    mode : "exact",
    elements : "fulltext",
    theme: "modern",
    theme_advanced_fonts : "Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;AkrutiKndPadmini=Akpdmi-n",
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor"
    ],
    toolbar: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons | template",
    width : 1000,
    height : 500,

    // Config URL conversion, template
    plugins: 'advlist autolink link image lists print preview hr pagebreak spellchecker  code fullscreen media nonbreaking save table contextmenu paste textcolor link image code, template',
    relative_urls: false,
    remove_script_host: false,

    // Example content CSS (should be your site CSS)
    content_css : "../include/js/jquery/tinymce_4.0.28/css/content.css",

    // Style line height
    style_formats_merge: false,
    style_formats: [
        {
            title: 'Line Height',
            items: [
                { title: 'Normal Line Height', inline: 'span', styles: { "line-height": '100%' } },
                { title: 'Line Height + 10%', inline: 'span', styles: { "line-height": '110%' } },
                { title: 'Line Height + 50%', inline: 'span', styles: { "line-height": '150%' } },
                { title: 'Line Height + 100%', inline: 'span', styles: { "line-height": '200%' } }
            ]
        }
    ],
    fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
    setup : function(ed){
        ed.addButton('formath1', // name to add to toolbar button list
            {
                title : 'Make h1', // tooltip text seen on mouseover
                image : 'http://xuatkhaulaodongceo.vn/images/quotes/thumbnail-quote-jesicas-bella-1509339509.jpg',
                onclick : function()
                {
                    ed.execCommand('FormatBlock', false, 'h1');
                }
            });
    },

    // File uploads manager
    file_browser_callback: function(field_name, url, type, win) {
        tinymce.activeEditor.windowManager.open({
            file: 'filemanager.php',// use an absolute path!
            title: 'Quản lý hình ảnh',
            width: 980,
            height: 500,
            resizable: 'yes'
        }, {
            setUrl: function (url) {
                win.document.getElementById(field_name).value = url;
            }
        });
        return false;
    },

    // Template design
    templates: [
        {title: 'Box 1: Giới thiệu về sản phẩm', description: 'Box giới thiệu về sản phẩm', url: '../templates/admin/interface/box_top_product_background_none.tpl'},
        {title: 'Box 2: Các bước sử dụng/Hoạt động', description: 'Box mô tả các bước sử dung(hoặc hoạt động) của sản phẩm', url: '../templates/admin/interface/box_cac_buoc_su_dung_hoac_cach_thuc_su_dung_san_pham.tpl'},
        {title: 'Box 3: Lợi ích/Tác dụng', description: 'Box mô tả lợi ích sản phẩm hoặc tác dụng sản phẩm', url: '../templates/admin/interface/box_loi_ich_su_dung_hoac_tac_dung_san_pham.tpl'},
        {title: 'Box 4: Liệt kê ưu điểm sản phẩm', description: 'Box liệt kê tất cả các ưu điểm của sản phẩm', url: '../templates/admin/interface/box_liet_ke_cac_uu_diem_san_pham.tpl'},
        {title: 'Box 5: Mô tả sử dụng', description: 'Box mô tả sử dụng sản phẩm', url: '../templates/admin/interface/box_mo_ta_su_dung_san_pham.tpl'},
        {title: 'Box 6: Reviews sản phẩm', description: 'Box Reviews sản phẩm', url: '../templates/admin/interface/box_review_product.tpl'},
        {title: 'Box 7: Logo', description: 'Box logo', url: '../templates/admin/interface/box_logo.tpl'},
        {title: 'Box 8: FAQS', description: 'Box FAQS', url: '../templates/admin/interface/box_faq.tpl'},
        {title: 'Box 9: Thông số kỹ thuật', description: 'Box thông số kỹ thuật', url: '../templates/admin/interface/box_thong_so_ky_thuat.tpl'},
        {title: 'Box 10: Đội ngũ phát triển - Đánh giá khách hàng', description: 'Box Đội ngũ phát triển - Đánh giá khách hàng', url: '../templates/admin/interface/box_doi_ngu_phat_trien_hoac_danh_gia_cua_khach_hang.tpl'},
        {title: 'Box 11: Timeline - Mốc thời gian', description: 'Box Timeline - Mốc thời gian phát triển sản phẩm', url: '../templates/admin/interface/box_timeline_moc_thoi_gian_phat_trien_san_pham.tpl'},
        {title: 'Box 12: Download App', description: 'Box Download App', url: '../templates/admin/interface/box_download_app.tpl'}
    ]
});

/* Tinymce */
tinymce.init({
    // General options
    mode : "exact",
    elements : "tinyfulltext",
    theme: "modern",
    theme_advanced_fonts : "Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;AkrutiKndPadmini=Akpdmi-n",
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor"
    ],
    toolbar: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons | template",
    width : 800,
    height : 500,

    // Config URL conversion, template
    plugins: 'advlist autolink link image lists print preview hr pagebreak spellchecker  code fullscreen media nonbreaking save table contextmenu paste textcolor link image code, template',
    relative_urls: false,
    remove_script_host: false,

    // Example content CSS (should be your site CSS)
    content_css : "../include/js/jquery/tinymce_4.0.28/css/content.css",

    // Style line height
    style_formats_merge: false,
    style_formats: [
        {
            title: 'Line Height',
            items: [
                { title: 'Normal Line Height', inline: 'span', styles: { "line-height": '100%' } },
                { title: 'Line Height + 10%', inline: 'span', styles: { "line-height": '110%' } },
                { title: 'Line Height + 50%', inline: 'span', styles: { "line-height": '150%' } },
                { title: 'Line Height + 100%', inline: 'span', styles: { "line-height": '200%' } }
            ]
        }
    ],
    fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
    setup : function(ed)
    {
        // set the editor font size
        ed.onInit.add(function(ed)
        {
            ed.getBody().style.fontSize = '10pt';
        });
    },

    // File uploads manager
    file_browser_callback: function(field_name, url, type, win) {
        tinymce.activeEditor.windowManager.open({
            file: 'filemanager.php',// use an absolute path!
            title: 'Quản lý hình ảnh',
            width: 980,
            height: 500,
            resizable: 'yes'
        }, {
            setUrl: function (url) {
                win.document.getElementById(field_name).value = url;
            }
        });
        return false;
    }
});