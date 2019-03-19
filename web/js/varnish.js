class Varnish {
    constructor(id) {
        this.id = id;
        this.websites_to_update = {};
    }

    addStatusChange(websiteId, status) {
        this.websites_to_update[websiteId] = status;
    }

    getPostData() {
        return this.websites_to_update;
    }
}

$(function () {
    let data_to_update = {},
        post_url = '/varnish-link',
        update_button = $('.varnish-update-button'),
        loader = $('.ajax-loader'),
        flash_info_container = $('.flash-info-container'),
        pending_request = false,
        flash_increment_id = 0,
        responseMessage = '',
        responseError = false;

    jQuery.fn.setElementVisibility = function (visible = false) {
        $(this).css('display', visible ? 'block' : 'none');
        return this;
    };

    jQuery.fn.setElementAvailability = function (available = false) {
        if (available) {
            $(this).removeAttr('disabled');
        } else {
            $(this).attr('disabled', '');
        }

        return this;
    };

    function addFlashInfo(message, type = 'success', timeout = 4000) {
        let flashContainerId = ++flash_increment_id;
        $(flash_info_container)
            .append(`<p class='flash-info bg-${type}' data-flash-id="${flashContainerId}">${message}</p>`)
            .toggle()
            .fadeIn(timeout / 10, function () {
                setTimeout(function () {
                    $(`p[data-flash-id=${flashContainerId}]`).fadeOut(timeout / 5, function () {
                        $(this).remove();
                    });
                }, timeout);
            })


    }

    function isDictEmpty(dict) {
        for (var key in dict) {
            if (dict.hasOwnProperty(key)) {
                return false;
            }
        }
        return true;
    }

    function addWebsiteChange(varnishId, websiteId, status) {
        if (!(varnishId in data_to_update)) {
            data_to_update[varnishId] = new Varnish(varnishId);
        }

        data_to_update[varnishId].addStatusChange(websiteId, status);
        $(update_button).setElementVisibility(true);
    }

    function preRequestPreparation() {
        pending_request = true;
        $('.varnish-website-checkbox').setElementAvailability(false);
        $(update_button).setElementAvailability(false);
    }

    function successCallback() {
        responseError = false;
        responseMessage = 'Varnish cache & websites link updated successfully!';
        data_to_update = {};
    }

    function failedRequestCallback() {
        responseError = true;
        responseMessage = 'Error occured while trying to update varnish & website links, code: ';
    }

    function afterRequestCallback() {
        console.log('after request');
        pending_request = false;
        $('.varnish-website-checkbox').setElementAvailability(true);
        $(update_button).setElementAvailability(true).setElementVisibility(false);
        $(loader).setElementVisibility(false);
        addFlashInfo(responseMessage, responseError ? 'error' : 'success');
    }


    function handlePostRequest() {
        let data = preparePostData();
        $.ajax({
            type: 'post',
            url: post_url,
            data: data,
            contentType: "application/json; charset=utf-8",
            traditional: true,
            success: successCallback,
            beforeSend: preRequestPreparation,
            complete: afterRequestCallback,
            error: failedRequestCallback
        });
    }

    function preparePostData() {
        let data = {};
        for (const varnishId of Object.keys(data_to_update)) {
            data[varnishId] = data_to_update[varnishId].getPostData();
        }
        data = JSON.stringify(data);
        return data;
    }

    $(update_button).on('click', function () {
        handlePostRequest();
    });

    $(window).bind('beforeunload', function () {
        if (!isDictEmpty(data_to_update) || pending_request) {
            return 'Are you sure you want to leave? You have unsaved data';
        }
    });

    $('.varnish-website-checkbox').change(function () {
        let status = $(this).is(':checked'),
            websiteId = $(this).attr('data-website-id'),
            parentVarnishTable = $(this).closest('table.varnish-website-table'),
            varnishId = $(parentVarnishTable).attr('data-varnish-id');

        addWebsiteChange(varnishId, websiteId, status);
    });


})
;