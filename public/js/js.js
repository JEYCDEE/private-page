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
     * @return {Void}
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
     * @return {Void}
     */
    static showMediaMeta(elem)
    {
    }

    /**
     * Hide file metadata.
     *
     * @param {HTMLElement} elem media element
     *
     * @return {Void}
     */
    static hideMediaMeta(elem)
    {
    }

    /**
     * Open modal window and place photo inside. Photo's url we take from
     * 'elem' attribute 'url'.
     *
     * @param {HTMLElement} elem       media element
     * @param {Integer}     animSpeed  media appearance animation speed
     *
     * @return {Void}
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

        return;

    }

    /**
     * Set navigation bar menu for each media, where user can save image, slide
     * backwards and forwards, etc.
     *
     * @param {HTMLElement} mediaHtml   image html element
     * @param {HTMLElement} navbarHtml  navigaion bar html element
     * @param {Integer}     animSpeed   navigaion bar appearance animation speed
     *
     * @return {Void}
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
     * @param {Integer}     animSpeed  media appearance animation speed
     *
     * @return {Void}
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

        return;

    }

    /**
     * Set current media index.
     *
     * @param  {HTMLElement} elem media element
     *
     * @return {Void}
     */
    static setCurrentMedia(elem)
    {

        DOM_MODS.currentMedia = $(elem).closest('li').index();

        return;

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
     * @param {Integer}     level      could be 1,2,3 - depends on modal number
     * @param {HTMLElement} data       dom element / object
     * @param {Integer}     animSpeed  animation speed, optional
     *
     * @return {Void}
     */
    static openModalWindow(level, data, animSpeed)
    {

        var modalWindow = $('#modal-' + level);
        var animSpeed   = typeof animSpeed == 'undeined' ? ANIM_FAST : animSpeed;

        $('html').css('overflow', 'hidden');

        modalWindow.find('#data-modal-' + level).html(data);
        modalWindow.stop().fadeIn(animSpeed);

        DOM_MODS.startListenToMediaKeys(level);

        return;

    }

    /**
     * Close modal window and clean up it's data.
     *
     * @param {Integer} level      could be 1,2,3 - depends on modal window number
     * @param {Integer} animSpeed  animation speed, optional
     *
     * @return {Void}
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

        return;

    }

    /**
     * Add listener for media keys (previous, next, etc).
     *
     * @param {Integer} level could be 1,2,3 - depends on modal window number
     *
     * @return {Void}
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

        return;

    }

    /**
     * Close current modal window and open a new one with next media. Cycle from
     * the beginning if the end is reached and go forward.
     *
     * @param {Integer} level could be 1,2,3 - depends on modal window number
     *
     * @return {Void}
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
     * @param {Integer} level could be 1,2,3 - depends on modal window number
     *
     * @return {Void}
     */
    static previousMedia(level)
    {

        var previousChild = $('.media li:eq(' + (DOM_MODS.getCurrentMedia() - 1) + ')');
        var previousElem  = previousChild.find('div').get(0);
        var previousData  = DOM_MODS.showFullMedia(previousElem);

        DOM_MODS.closeModalWindow(level);
        DOM_MODS.setCurrentMedia(previousElem);
        DOM_MODS.openModalWindow(level, previousData);

        return;

    }

    /**
     * Remove listener for media keys (previous, next, etc).
     */
    static stopListenToMediaKeys()
    {

        $(document).unbind('keydown');

        return;

    }

    /**
     * Show additional +n media files each time you scroll down and hit the floor.
     *
     * @constructor
     *
     * @return {Void}
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

            if (currentScroll >= documentHeight - 200) {

                APP.find('.media li').slice(PAGIN_STEP, paginationNext).each(function(index, li) {

                    $(li).get(0).removeAttribute('hidden');

                });

                $this.unbind("scroll.mediaPagination");

                DOM_MODS.turnOnMediaPagination();

            }

        });

        return;

    }

    /**
     * Get currently loaded through ajax page name.
     *
     * @return {String}
     */
    static getCurrentPage()
    {

        return APP.attr('page');

        return;

    }

    /**
     * Set new page name.
     *
     * @param {String} name
     * @return {Void}
     */
    static setCurrentPage(name)
    {

        APP.attr('page', name);

        return;

    }

    /**
     * Open login modal box, load login form box and paste it inside the modal
     * window. Add submit and cancel button listeners to react on this actions.
     *
     * @param  {HTMLElement} elem : navbar button, that user clicked itself
     *
     * @return {Void}
     */
    static openLoginBox(elem)
    {

        var elem       = $(elem);
        var modalLevel = 1;
        var customUrl  = '/getPartLoginBox';

        CUSTOM_AJAX(function(result) {

            if (result.completed && result.success) {

                DOM_MODS.openModalWindow(modalLevel, result.data, ANIM_MEDIUM);

                /* Add submit button listener */
                $('#login-box_submit').unbind('click').on('click', function () {

                    var loginBox = $(this).closest('#login-box');
                    var login = loginBox.find('input#login-box_login').val();
                    var password = loginBox.find('input#login-box_password').val();

                    LOGIN(login, password);

                });

                /* Add cancel button listener */
                $('#login-box_cancel').unbind('click').on('click', function () {

                    DOM_MODS.closeModalWindow(modalLevel, ANIM_MEDIUM);

                });
            } else {
                console.log(result.message);
            }

        }, customUrl, 'GET', {}, 'html', false);

        return;

    }

    /**
     * Switch between login and logout navbar buttons with icons and text.
     *
     * @return {Void}
     */
    static switchLoginLogoutButtons()
    {

        var loginButton  = $('#navbar-login-button');
        var logoutButton = $('#navbar-logout-button');

        if (loginButton.prop('hidden') === true) {
            loginButton.prop('hidden', false);
            logoutButton.prop('hidden', true);
        } else {
            loginButton.prop('hidden', true);
            logoutButton.prop('hidden', false);
        }

    }

    /**
     * Show admin control panel with additional controls, if root user
     * successfully loggs in.
     *
     * @return {Void}
     */
    static showAdminControlPanel()
    {

        var customUrl = '/getPartControlPanel';

        CUSTOM_AJAX(function(result) {

            $('body').prepend(result.data);

        }, customUrl, 'GET', {}, 'html', false);

    }

    /**
     * Hide admin control panel when root user loggs out.
     *
     * @return {Void}
     */
    static hideAdminControlPanel()
    {

        $('#control-panel').remove();

    }

}

/**
 * Here we store all content manipulation methods like adding new element,
 * modifing existing one or deleting it. All methods are common for each media
 * type and accept parameters to define which type is going to be manipulated.
 * The type we usualy take from 'div#app page='  attribute. If it's empty, then
 * we are dealing with home / main / news page.
 */
const CONTENT_MANAGER = class CONTENT_MOD
{

    /**
     * Define what type of page currently loaded. We take the 'pagep' value from
     * 'div#app' element.
     *
     * @return {String}
     */
    static definePageType()
    {

        return $('#app').attr('page') == '' ? 'news' : $('#app').attr('page');

    }

    /**
     ***************************************************************************
     * ADD METHODS *************************************************************
     ***************************************************************************
     * Here we define the type of the page and call specific method to ADD new
     * content.
     *
     * @return {Void}
     */
    static add()
    {

        var type   = this.definePageType();
        var method = 'add' + type.charAt(0).toUpperCase() + type.slice(1);

        this[method]();

    }

    /**
     * Add new posts to news / main / home page.
     */
    static addNews()
    {

        alert('Adding new posts!');

    }

    /**
     * Add new photos to photos page.
     */
    static addPhotos()
    {

        alert('Adding new photos!');

    }

    /**
     *  Add new videos to videos page.
     */
    static addVideos()
    {

        alert('Adding new videos!');

    }

    /**
     * Add new contacts to contacts page.
     */
    static addContacts()
    {

        alert('Adding new contacts!');

    }

    /**
     ***************************************************************************
     * DELETE METHODS **********************************************************
     ***************************************************************************
     * Here we define the type of the page and call specific method to DELETE
     * already existing content.
     *
     * @return {Void}
     */
    static delete()
    {

        var type   = this.definePageType();
        var method = 'delete' + type.charAt(0).toUpperCase() + type.slice(1);

        this[method]();

    }

    /**
     * Delete existing posts from news / main / home page.
     */
    static deleteNews()
    {

        alert('Deleting existing posts!');

    }

    /**
     * Delete existing photos from photos page.
     */
    static deletePhotos()
    {

        alert('Deleting existing photos!');

    }

    /**
     *  Delete existing videos from videos page.
     */
    static deleteVideos()
    {

        alert('Deleting existing videos!');

    }

    /**
     * Delete existing contacts from contacts page.
     */
    static deleteContacts()
    {

        alert('Deleting existing contacts!');

    }

    /**
     ***************************************************************************
     * EDIT METHODS ************************************************************
     ***************************************************************************
     * Here we define the type of the page and call specific method to EDIT
     * existing content.
     *
     * @return {Void}
     */
    static edit()
    {

        var type   = this.definePageType();
        var method = 'edit' + type.charAt(0).toUpperCase() + type.slice(1);

        this[method]();

    }

    /**
     * Edit existing posts from news / main / home page.
     */
    static editNews()
    {

        alert('Editing existing posts!');

    }

    /**
     * Edit existing photos from photos page.
     */
    static editPhotos()
    {

        alert('Editing existing photos!');

    }

    /**
     *  Edit existing videos from videos page.
     */
    static editVideos()
    {

        alert('Editing existing videos!');

    }

    /**
     * Edit existing contacts from contacts page.
     */
    static editContacts()
    {

        alert('Editing existing contacts!');

    }

}

/**
 * Standard jQuery .ajax function a bit refactored for current project.
 *
 * @param {action}  action    run this method after ajax finished
 * @param {String}  customUrl name of the page to load
 * @param {String}  method    default is 'POST', but feel free to change it
 * @param {Object}  data      data you would like to send to controller
 * @param {String}  type      default is 'json', but feel free to change it
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
 * @param  {String}      name name of the page, that is going to be loaded
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

/**
 * Login root user so he can take control over the whole web app. If success
 * change navbar icon and text to logout.
 *
 * @param  String         login
 * @param  String         password
 *
 * @return {Void}
 */
const LOGIN = function(login, password)
{

    var customUrl  = '/loginAction';
    var data       = {
        login    : login,
        password : password
    }

    CUSTOM_AJAX(function(result) {

        if (result.completed && result.success && result.data === true) {
            /* Close modal, we don't need it anymore. */
            DOM_MODS.closeModalWindow(1, ANIM_MEDIUM);

            /* Switch between login and logout buttons. */
            DOM_MODS.switchLoginLogoutButtons();

            /* Show control panel */
            DOM_MODS.showAdminControlPanel();
        } else {
            alert('Nice try, but no, you are not a root user');
        }

    }, customUrl, 'GET', data, 'json', false);

}

/**
 * Due to we have only one user - root, we have to remove only him from redis.
 *
 * @return {Boolean}
 */
const LOGOUT = function()
{

    var customUrl = '/logoutAction';

    CUSTOM_AJAX(function(result) {

        /* Switch between login and logout buttons. */
        if (result.completed && result.success && result.data === true) {

            /* Switch between login and logout buttons. */
            DOM_MODS.switchLoginLogoutButtons();

            /* Show control panel */
            DOM_MODS.hideAdminControlPanel();
        }

    }, customUrl, 'GET', {}, 'json', false);

}