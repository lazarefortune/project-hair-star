import 'select2/dist/css/select2.min.css';
import 'select2/dist/js/select2.min.js';

import './select2.scss';

import $ from 'jquery';

$(document).ready(function () {
    $('.select2').select2();

    $('.select2-tags').select2({
        tags: true,
        tokenSeparators: [',', ' ']
    }).on('change', function (e) {
        let option = $(this).find("[data-select2-tag=true]:last-of-type")
        if (option.length && $.inArray(option.val(), $(this).val()) !== -1) {
            $.ajax({
                headers: {
                    'Accept': 'application/json',
                },
                contentType: 'application/json',
                url: ' /api/admin/tags/new/',
                data: JSON.stringify({
                    name: option.val()
                }),
                method: 'POST',
            }).done(function (data) {
                option.replaceWith('<option value="' + data.id + '" selected>' + data.name + '</option>');
            });
        }
    });
});
