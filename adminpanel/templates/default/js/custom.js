$(document).ready(function(){

    $("#datepicker").datepicker({
        firstDay: 1,
        showOtherMonths: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        maxDate: new Date()
      });
      
      $(".date").mousedown(function() {
        $(".ui-datepicker").addClass("active");
      });

    /*MULTI ACTION SECTION START-------------------------------------------------*/
    // add multiple select / deselect functionality
    $(document).on('change', '.selectall', function () {
        if($(this).parents('.card-wrap').length){
            if($(".selectall:checked").length)
                $(this).parents('.card-wrap').find('.case').attr("checked", true);
            else
                $(this).parents('.card-wrap').find('.case').attr("checked", false);
        }
        else{
            if($(".selectall:checked").length)
                $(this).parents('form').find('.case').attr("checked", true);
            else
                $(this).parents('form').find('.case').attr("checked", false);
        }
    });

    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    $(document).on('change', '.case', function() {
        if($(this).parents('.card-wrap').length){
            if($(".case").length == $(".case:checked").length) {
                $(this).parents('.card-wrap').find(".selectall").attr("checked", true);
            } else {
                $(this).parents('.card-wrap').find(".selectall").attr("checked", false);
            }
        }
        else{
            if($(".case").length == $(".case:checked").length) {
                $(this).parents('form').find(".selectall").attr("checked", true);
            } else {
                $(this).parents('form').find(".selectall").attr("checked", false);
            }
        }
    });
    /*END-------------------------------------------------*/
    
    /*GENERATE PASSWORD ACTION SECTION START-------------------------------------------------*/
    $(".generate").click(function(e) {
        e.preventDefault();
        var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
        var string_length = 8;
        var randomstring = '';
        for (var i = 0; i < string_length; i++) {
            var rnum = Math.floor(Math.random() * chars.length);
            randomstring += chars.substring(rnum,rnum+1);
        }
        $('.gen_pass').val(randomstring);
        $('.new_pass').fadeIn(400);
        $('.new_pass > input[type=text]').val(randomstring);
    });
    /*END-------------------------------------------------*/

    /*TEXTCOUNT SECTION START-------------------------------------------------*/
    $('.textlimit').each(function () {
        var elem         = $(this).find('textarea'),
            charCount    = $(this).find('.charcount');

        elem.keyup(function (e) {
            var textLength      = elem.val().length;

            charCount.html('(' + textLength + ' characters)');
        });
    });
    $('.limitedtext').each(function () {
        var elem         = $(this).find('textarea'),
            maxChar      = elem.attr('maxlength'),
            textLength   = elem.val().length,
            charCount    = $(this).find('.charcount');

        if(textLength > 1) 
            charCount.html('(' + textLength + ' / ' + maxChar + ' characters)');
        else
            charCount.html('(0 / ' + maxChar + ' characters)');

        elem.keyup(function (e) {
            var textLength      = elem.val().length;

            charCount.html('(' + textLength + ' / ' + maxChar + ' characters)');
        });
    });
    /*END-------------------------------------------------*/

    /*GENERATE PERMALINK ACTION SECTION START-------------------------------------------------*/
    $('.permalink').keyup(function(){
        var	qrystr      = $(this).data('qrystr');
        var	ENTITY      = $(this).data('entity');
        var	permalink   = $(this).val();
        $.ajax({
            url:'./',
            type:'post',
            data:{ajx_action:'permalink', permalink:permalink, ENTITY:ENTITY, qrystr: qrystr},
            success:function(res){
                if(typeof res.permalink != 'undefined')
                $('.gen_permalink').val(res.permalink);
            }
        });
    });
    
    $('.gen_permalink').bind('keypress', function (e) {
        if ($(this).val().length == 0) {
            var valid = (e.which >= 48 && e.which <= 57) || (e.which >= 65 && e.which <= 90) || (e.which >= 97 && e.which <= 122);
            if (!valid) {
                e.preventDefault();
            }
        } else {
            var valid = (e.which >= 48 && e.which <= 57) || (e.which >= 65 && e.which <= 90) || (e.which >= 97 && e.which <= 122 || e.which == 45 || e.which == 95 || e.which == 8);
            if (!valid) {
                e.preventDefault();
            }
        }
    });
    /*END-------------------------------------------------*/

    /*FAQ SECTION START-------------------------------------------------*/
    $(".toggle_block > .ques").bind("click", function () {
        if ($(this).parent().hasClass('opened')) {
            $(this).parent().siblings().removeClass('opened');
            $(this).parent().siblings().children(".ans_toggle").slideUp(300);
            $(this).parent().removeClass('opened');
            $(this).next('.ans_toggle').slideUp(300);
            return false;
        } else {
            $(this).parent().siblings().removeClass('opened');
            $(this).parent().siblings().children(".ans_toggle").slideUp(300);
            $(this).parent().addClass('opened');
            $(this).next('.ans_toggle').slideDown(300);
            return false;
        }
    });
    /*END-------------------------------------------------*/

    /*STICKYSIDEBAR SECTION START-------------------------------------------------*/
    $(".contentL, .contentS").theiaStickySidebar({additionalMarginTop: 60});
    /*END-------------------------------------------------*/

    /*DATEPICKER SECTION START-------------------------------------------------*/
    if($('[type="date"]').length) {
		if ( $('[type="date"]').prop('type') != 'date' ) {
			$('[type="date"]').datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: "yy-mm-dd",
				yearRange: "c-0:c+10"
			});
		}
	}
    /*END-------------------------------------------------*/

    /*IMAGE_POPUP SECTION START-------------------------------------------------*/
    $(document).on('click', '.imagePopup', function(){
        var src = $(this).find('img').attr('src'),
            alt = $(this).find('img').attr('alt');
        swal({
            title: "",
            text: "<img src='"+src+"' alt='"+alt+"'>",
            html: true
        });
        setTimeout(function () {
            var sweetHeight = $('.showSweetAlert').outerHeight() / 2;
            $('.showSweetAlert').animate({'margin-top': '-'+sweetHeight+'px'},800);
        }, 300);
    });
    /*END-------------------------------------------------*/
    
    $('.mod').on('change', function () {
        $(this).parents('.modpane').find('.respane').empty();
        $(this).parents('.modpane').find('.srchmod').val('');
    });
    
    $('.srchmod').on('keyup', function() {
        var modpane     = $(this).parents('.modpane');
        var respane     = modpane.find('.respane');

        var	pd          = modpane.find('.mod option:selected').attr('data-pd');
        var	cd          = modpane.find('.mod option:selected').attr('data-cd');
        var	mid         = modpane.find('.mod option:selected').val();
        
        var srch        = $(this).val();
        
        var options     = {
                    'append':false,
                    'replace':false,
                    'prepend':false,
                    'eventData':{}
                };
        
        respane.empty();

        $.ajax({
            url:'./index.php?pageType='+pd+'&dtls='+cd,
            type:'post',
            data:{ajx_action:'modPage', mid: mid, srch:srch},
            success:function(res){
                
                var transform = {'<>':'li','html':[
                                    {'<>':'label','html':[
                                        {'<>':'input', value:'${id}', type:'checkbox', name:'categoryId[]', html:'<span>${page}</span>'}
                                    ]}
                                ]};
                

                respane.json2html(res, transform, options);
            }
        });
    });
});

function toster(msgtype,msg,title){
    if(msgtype==1){
        toastr.success(msg,title,{
            "positionClass": "toast-bottom-full-width",
            timeOut: 5000,
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "tapToDismiss": false
        });
    }
    else{
        toastr.error(msg,title,{
            "positionClass": "toast-bottom-full-width",
            timeOut: 5000,
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "tapToDismiss": false
        });
    }
}

function sAlert(title, text, html){
    swal({
        title: title,
        text: text,
        html: html,

        /* type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it !!",
        closeOnConfirm: false */
    });
}

(function($) {

    var methods = {
        init: function(opts) {
            opts = opts || {};
            opts.tags = opts.tags || [];

            var me = $(this);
            var clean = new RegExp(',', 'g');
            me.wrap('<div class="tag-cloud" />');
            var cloud = me.parents('.tag-cloud');

            cloud.click(function() {
                me.focus();
            });

            var addTag = function(value) {
                value = value.replace(clean, '');
                if (value !== '') {
                    var tag = $('<div class="tag"><span>' + value + '</span></div>');
                    var del = $('<a href="#" class="close">Delete</a>');

                    del.click(function() {
                        del.parent().remove();
                    });
                    
                    tag.append(del);
                    tag.insertBefore(me);
                }
            }

            me.blur(function() {
                addTag(this.value);
                this.value = '';
            });
            me.keyup(function(e) {
                 var key = e.keyCode;
                var isEnter = key == 13;
                var isComma = key == 188;
                var isBack = key == 8;
                
                if (isEnter || isComma) {
                    addTag(this.value);
                    this.value = '';
                }
                if (isBack && this.data('delete-prev')) { 
                    $(this).prev().remove(); 
                    $(this).data('delete-prev',false);
                } else if(isBack && this.value === '') {
                    $(this).data('delete-prev', true); 
                    
                }
            });
            $.each(opts.tags, function(i, e) {
                addTag(e);
            });
        },
        get: function() {
            return $('.tag span',this.parent()).map(function() {
                return $(this).text();
            });
        },
        clear: function() {
            $('div', this).remove();
        }
    };
    $.fn.tagcloud = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on tagcloud plugin');
        }
    };
})(jQuery);

$('.tags').tagcloud({
    tags: ['Quint', 'USS Indianapolis', 'Orca']

});
var tags = $('.tags').tagcloud('get');

// function getDateformat(dateVal){
//     var currentTime = dateVal;
//     var Month=new Array("January", "February", "March","April", "May", "June", "July", "August", "September", "October", "November", "December");
//     var Suffix=new Array("th", "st", "nd", "rd", "th", "th", "th", "th", "th", "th");
//     var day = currentTime.getDate();
//     var month = currentTime.getMonth();
//     var year = currentTime.getFullYear();
//     if (day % 100 >= 11 && day % 100 <= 13){
//         today = day + "th";
//     }else{
//         today = day + Suffix[day % 10];
//     }

// return (today + " " + Month[month] + ' ' + year);
// }

// getDateformat('2024-09-2');