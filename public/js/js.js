"use strict";

const APP          = $('#app');
const TOKEN        = $('body').attr('token');
const PAGES_PREFIX = '/pages/';
const ANIM_NONE    = 0;
const ANIM_FAST    = 200;
const ANIM_MEDIUM  = 500;
const ANIM_LONG    = 1000;
const PAGIN_STEP   = 35;
const WIN_WIDTH    = parseInt($(window).width());
const WIN_HEIGHT   = parseInt($(window).height());
const IS_DESKTOP   = WIN_WIDTH >= 1025 ? true : false;
const IS_TABLET    = WIN_WIDTH <= 1024 && WIN_WIDTH >= 769 ? true : false;
const IS_PHONE     = WIN_WIDTH <= 768 ? true : false;
const MEDIA_WIDTH  = IS_DESKTOP ? 960 : IS_TABLET ? 680 : IS_PHONE ? WIN_WIDTH - 100 : 0;
const MEDIA_HEIGHT = IS_DESKTOP || IS_TABLET ? WIN_HEIGHT - 100 : IS_PHONE ? WIN_HEIGHT - 100 : 0;

/**
 * Here we store all useful trick and fixes to Dom Elements.
 */
const DOM_FIXES = class DOM_FIXES
{

    /**
     * Build responsive grid for each device. First we save parent width, then,
     * according to grid number, we calculate width for each child.
     *
     * @param  String mediaContainer : parent dom element with all children inside
     * @return void
     */
    static buildMediaGrid(mediaContainer, grid)
    {

        var parentWidth  = $(mediaContainer).width();
        var parentHeight = $(mediaContainer).height();
        var grid         = typeof grid == 'undefined' ? 5 : grid;

        $.each($(mediaContainer).find('li'), function(index, object) {

            var newWidth    = parentWidth / grid - 1;
            var newHeight   = newWidth;

            $(object).css({
                height: newHeight + 'px',
                width: newWidth + 'px',
            });

        })

    }

}

/**
 * Here we store all dom modifications, that we are going to make, such as
 * opening photo / video in fullscreen inside custom modal window, etc.
 */
const DOM_MODS = class DOM_MODS
{

    constructor()
    {

        DOM_MODS.currentMedia = null;

    }

    /**
     * Show file metadata inside the same block over the media file.
     *
     * @param  {HTMLElement} elem media element
     *
     * @return void
     */
    static showMediaMeta(elem)
    {
    }

    /**
     * Hide file metadata.
     *
     * @param  {HTMLElement} elem media element
     *
     * @return void
     */
    static hideMediaMeta(elem)
    {
    }

    /**
     * Open modal window and place photo inside. Photo's url we take from
     * 'elem' attribute 'url'.
     *
     * @param {HTMLElement} elem       media element
     * @param {integer}     animSpeed  media appearance animation speed
     *
     * @return void
     */
    static showFullMedia(elem, animSpeed)
    {

        var mediaUrl  = $(elem).attr('url');
        var photoHtml = $("<img src='" + mediaUrl + "' />");
        var videoHtml = $("<video controls><source src='" + mediaUrl + "' type='video/mp4' /></video>");
        var animSpeed = animSpeed === undefined ? 0 : animSpeed;

        if (DOM_MODS.getCurrentPage() == 'photos') {
            DOM_MODS.openModalWindow(1, photoHtml, animSpeed);
            DOM_MODS.setMediaSize(photoHtml, animSpeed);
            DOM_MODS.addMediaNavbar(photoHtml, animSpeed);
        }

        if (DOM_MODS.getCurrentPage() == 'videos') {
            DOM_MODS.openModalWindow(1, videoHtml, animSpeed);
            DOM_MODS.setMediaSize(videoHtml, animSpeed);
            DOM_MODS.addMediaNavbar(videoHtml, animSpeed);
        }

    }

    /**
     * Set navigation bar menu for each media, where user can save image, slide
     * backwards and forwards, etc.
     *
     * @param {HTMLElement} mediaHtml   image html element
     * @param {HTMLElement} navbarHtml  navigaion bar html element
     * @param {integer}     animSpeed   navigaion bar appearance animation speed
     *
     * @return void
     */
    static addMediaNavbar(mediaHtml, animSpeed)
    {

        var customUrl = '/getPartMediaNavbar';
        var isImage   = mediaHtml.is('img');
        var method    = 'GET';
        var type      = 'html';
        var async     = false;
        var data      = {
            _token : TOKEN,
            link   : isImage ? mediaHtml.attr('src') : mediaHtml.find('source').attr('src'),
        };

        CUSTOM_AJAX(function(result) {

            var navbarHtml = $(result.data);

            mediaHtml.on('load loadstart', function() {

                navbarHtml.css('width', $(this).width() + 'px');
                mediaHtml.parent().prepend(navbarHtml);

            });

        }, customUrl, method, data, type, async)

    }

    /**
     * Calculate and set actual size for image. We use this tricks to get
     * correct width and height for both landscape and portrait media.
     *
     * @param {HTMLElement} mediaHtml  image html element
     * @param {integer}     animSpeed  media appearance animation speed
     *
     * @return void
     */
    static setMediaSize(mediaHtml, animSpeed)
    {

        var media;
        var mediaWidth;
        var mediaHeight;

        mediaHtml.unbind('load loadstart').on('load loadstart', function() {

            media       = $(this);
            mediaWidth  = media.width();
            mediaHeight = media.height();

            if (mediaWidth >= mediaHeight) {
                media.css({
                    width  : MEDIA_WIDTH + 'px',
                    height : 'auto',
                });
            } else {
                media.css({
                    height : MEDIA_HEIGHT + 'px',
                    width  : 'auto',
                });
            }

            media.addClass('fullsize-media with-static-shadow-40');
            media.stop().animate({marginTop: 0}, animSpeed);

        });

    }

    /**
     * Set current media index.
     *
     * @param  {HTMLElement} elem media element
     *
     * @return void
     */
    static setCurrentMedia(elem)
    {

        DOM_MODS.currentMedia = $(elem).closest('li').index();

    }

    /**
     * Get currently active media index.
     *
     * @returns {null|integer}
     */
    static getCurrentMedia()
    {

        return DOM_MODS.currentMedia;

    }

    /**
     * Open modal window and paste given data inside it.
     *
     * @param {integer}     level      could be 1,2,3 - depends on modal number
     * @param {HTMLElement} data       dom element / object
     * @param {integer}     animSpeed  animation speed, optional
     *
     * @return void
     */
    static openModalWindow(level, data, animSpeed)
    {

        var modalWindow = $('#modal-' + level);
        var animSpeed   = typeof animSpeed == 'undeined' ? ANIM_FAST : animSpeed;

        $('html').css('overflow', 'hidden');

        modalWindow.find('#data-modal-' + level).html(data);
        modalWindow.stop().fadeIn(animSpeed);

        DOM_MODS.startListenToMediaKeys(level);

    }

    /**
     * Close modal window and clean up it's data.
     *
     * @param {integer} level      could be 1,2,3 - depends on modal window number
     * @param {integer} animSpeed  animation speed, optional
     *
     * @return void
     */
    static closeModalWindow(level, animSpeed)
    {

        var modalWindow = $('#modal-' + level);
        var animSpeed   = typeof animSpeed == 'undeined' ? ANIM_FAST : animSpeed;

        modalWindow.stop().fadeOut(animSpeed, function() {

            modalWindow.find('#data-modal-' + level).empty();

            $('html').css('overflow', 'auto');

        });

        DOM_MODS.stopListenToMediaKeys();

    }

    /**
     * Add listener for media keys (previous, next, etc).
     *
     * @param {integer} level could be 1,2,3 - depends on modal window number
     *
     * @return void
     */
    static startListenToMediaKeys(level)
    {

        var previousMedia = DOM_MODS.previousMedia;
        var nextMedia     = DOM_MODS.nextMedia;
        var closeMedia    = DOM_MODS.closeModalWindow;

        $(document).unbind('keydown').keydown(function(event) {

            var keyCode = event.keyCode;

            /* Escape - close modal window */
            if (keyCode == 27) closeMedia(level);

            /* Left arrow <- previous */
            if (keyCode == 37) previousMedia(level);

            /* Right arrow -> next */
            if (keyCode == 39) nextMedia(level);

        });

    }

    /**
     * Close current modal window and open a new one with next media. Cycle from
     * the beginning if the end is reached and go forward.
     *
     * @param {integer} level could be 1,2,3 - depends on modal window number
     *
     * @return void
     */
    static nextMedia(level)
    {

        var nextChild = function() {

            var nextChild = $('.media li:eq(' + (DOM_MODS.getCurrentMedia() + 1) + ')');

            if (nextChild.length === 0)
                nextChild = $('.media li:eq(0)');

            return nextChild;

        };
        var nextElem  = nextChild().find('div').get(0);
        var nextData  = DOM_MODS.showFullMedia(nextElem);

        DOM_MODS.closeModalWindow(level);
        DOM_MODS.setCurrentMedia(nextElem);
        DOM_MODS.openModalWindow(level, nextData);

    }

    /**
     * Close current modal window and open a new one with previous media. Open
     * the first one from the end, if negative value and go backwards.
     *
     * @param {integer} level could be 1,2,3 - depends on modal window number
     *
     * @return void
     */
    static previousMedia(level)
    {

        var previousChild = $('.media li:eq(' + (DOM_MODS.getCurrentMedia() - 1) + ')');
        var previousElem  = previousChild.find('div').get(0);
        var previousData  = DOM_MODS.showFullMedia(previousElem);

        DOM_MODS.closeModalWindow(level);
        DOM_MODS.setCurrentMedia(previousElem);
        DOM_MODS.openModalWindow(level, previousData);

    }

    /**
     * Remove listener for media keys (previous, next, etc).
     */
    static stopListenToMediaKeys()
    {

        $(document).unbind('keydown');

    }

    /**
     * Show additional +n media files each time you scroll down and hit the floor.
     *
     * @constructor
     *
     * @return void
     */
    static turnOnMediaPagination()
    {

        var paginationStart = APP.find('.media li[hidden]:first').index();
        var paginationNext  = paginationStart + PAGIN_STEP;

        $.each(APP.find('.media li[hidden]'), function(index, mediaObj) {

            if (index < PAGIN_STEP) {
                mediaObj.removeAttribute('hidden');
            }

        });

        $(window).bind("scroll.mediaPagination", function() {

            var $this = $(this);
            var documentHeight = $(document).height();
            var currentScroll  = $this.scrollTop() + WIN_HEIGHT;

            if (currentScroll >= documentHeight - 100) {

                APP.find('.media li').slice(PAGIN_STEP, paginationNext).each(function(index, li) {

                    $(li).get(0).removeAttribute('hidden');

                });

                $this.unbind("scroll.mediaPagination");

                DOM_MODS.turnOnMediaPagination();

            }

        });

    }

    /**
     * Get currently loaded through ajax page name.
     *
     * @returns {string}
     */
    static getCurrentPage()
    {

        return APP.attr('page');

    }

    /**
     * Set new page name.
     *
     * @param {string} name
     * @return void
     */
    static setCurrentPage(name)
    {

        APP.attr('page', name);

    }

}

/**
 * Standard jQuery .ajax function a bit refactored for current project.
 *
 * @param {action}  action    run this method after ajax finished
 * @param {string}  customUrl name of the page to load
 * @param {string}  method    default is 'POST', but feel free to change it
 * @param {Object}  data      data you would like to send to controller
 * @param {string}  type      default is 'json', but feel free to change it
 * @param {Boolean} async     default is 'true', but feel free to change it
 *
 * @return {Object}
 */
const CUSTOM_AJAX = function(action, customUrl, method, data, type, async)
{

    if (typeof action == 'undefined')
        return {error : 'Action should not be empty'};

    if (typeof customUrl == 'undefined')
        return {error : 'URL should not be empty'};

    var method = typeof method == 'undefined' ? 'POST' : method;
    var data   = typeof data   == 'undefined' ? {}     : data;
    var type   = typeof type   == 'undefined' ? 'json' : type;
    var async  = typeof async  == 'undefined' ? true   : async;
    var result = {
        completed : false,
        success   : false,
        data      : '',
    };

	var response = $.ajax({
		url      : customUrl,
		method 	 : method,
		data 	 : $.extend(data, {_token : TOKEN}),
        async    : async,
		dataType : type,
	});

    return response
        .done(function(data) {

           result.success = true;
           result.data    = data;

        })
        .fail(function(data, error, xhr) {

            result.success = false;
            result.data    = data;
            result.message = error + ': ' + xhr;

        })
        .always(function(data) {

            result.completed = true;
            action(result);

        });

}

/**
 * Load selected page into main application block instead of already loaded one.
 *
 * @param  {HTMLElement} elem anchor button
 * @param  {string}      name name of the page, that is going to be loaded
 *
 * @return {Void}
 */
const LOAD_PAGE = function(elem, name)
{

	var elem             = $(elem);
	var pageSwitcher     = $('.page-switcher');
	var customUrl        = PAGES_PREFIX + name;
	var switchActivePage = switchActivePage;
	var loadSelectedPage = loadSelectedPage;

	switchActivePage();
    loadSelectedPage();

	return;

    /* Switch active state for selected section */
	function switchActivePage() {

		pageSwitcher.removeClass('active');
		elem.addClass('active');

	}

    /* Load selected page thourh ajax */
	function loadSelectedPage() {

        CUSTOM_AJAX(function(result) {

            if (result.completed && result.success) {
                APP.slideUp(ANIM_FAST, function() {

                    $(this).empty().html(result.data).slideDown(ANIM_FAST);

                    DOM_MODS.setCurrentPage(name);

                });
            } else {
                console.log(result);
                alert('Error. Please see console log.');
            }

        }, customUrl, 'GET', {}, 'html', false);

    }

}