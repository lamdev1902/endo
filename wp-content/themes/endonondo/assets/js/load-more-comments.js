jQuery(document).ready(function ($) {
    var smform = false;
    $.validator.addMethod("noUrl", function (value, element) {
        if (!value) return true; 
        const urlPattern = /https?:\/\/[^\s]+/i; 
        return !urlPattern.test(value);
    }, "URLs are not allowed in comments.");

    $.validator.addMethod("disallowedWords", function (value, element) {
        if (!value) return true;
        let disallowed = ajax_object.disallowed_keys || [];
        for (let i = 0; i < disallowed.length; i++) {
            if (value.toLowerCase().includes(disallowed[i].toLowerCase())) {
                return false;
            }
        }
        return true;
    });

    $('#comment-form').validate({
        rules: {
            author: {
                required: true,
                minlength: 4,
                noUrl: true,
            },

            email: {
                required: true,
                email: true,
                noUrl: true,
            },

            comment: {
                required: true,
                disallowedWords: true,
                noUrl: true,
            }
        },

        messages: {
            "author": {
                required: "Field is required.",
                noUrl: "Your comment cannot be submitted. Please try again.",
            },
            "email": {
                required: "Field is required.",
                email: "Your comment cannot be submitted. Please try again.",
                noUrl: "Your comment cannot be submitted. Please try again.",
            },
            "comment": {
                required: "Field is required.",
                disallowedWords: 'Your comment cannot be submitted. Please try again.',
                noUrl: "Your comment cannot be submitted. Please try again.",
            }
        },

        errorElement: "span", 
        errorClass: "cmt-err",
        errorPlacement: function (error, element) {
            const errorSection = element.closest(".comment-form").find(".error-section");
            if (errorSection.length) {
                errorSection.html(error); 
            } else {
                element.after(error);
            }
        },
        submitHandler: function(form){  
            var formdata = $(form).serialize();
            if(!smform) {
                $.ajax({  
                    type: 'post',  
                    url: ajax_object.ajax_url,  
                    data: formdata+"&action=ajax_comment",
                    beforeSend: function () {
                        $('.error-section').html('<span class="cmt-wait">Submitting your comment...</span>');
                        smform = true;
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown){  
                        $('.error-section').empty();
                        $('.error-section').html('<span class="cmt-err">Your comment can not be submitted. Please try again.</span>'); 
                        smform = false;
                        $(form).find('#author').val('');
                        $(form).find('#email').val('');
                        $(form).find('#comment').val('');
                    },  
                    success: function(data, textStatus){  
                        $('.error-section').empty();
                        if(data.success)  
                            $('.error-section').html('<span class="cmt-succ" >Your comment is awaiting for approval.</span>');  
                        else  
                            $('.error-section').html('<span class="cmt-err">Your comment can not be submitted. Please try again.</span>');  
                        smform = false;
                        $(form).find('#author').val('');
                        $(form).find('#email').val('');
                        $(form).find('#comment').val('');
                    }
                });  
            }
        }  
    });
    $('.cancle-btn').on('click', function(e){
        e.preventDefault(); 

        var cancelReply = $('#cancel-comment-reply-link');
        if (cancelReply.length) {
            cancelReply[0].click();
        }
    });

    let rep = false;

    $(document).on('click', '.comment-reply-link', function (e) {
        e.preventDefault(); 

        var form = $('#respond').find('form');

        form.find('#author').val('');
        form.find('#email').val('');
        form.find('#comment').val('');
        form.find('.error-section').empty();

        var replyLink = $(this);
        if (replyLink.length) {
            replyLink[0].click();
        }
    });

    $('#seeCmt').on('click', function (e) {
        e.preventDefault();

        let button = $(this);
        let postId = button.data('post-id');
        let page = button.data('page');

        const displayedCommentIds = [];
        jQuery('.commentlist li.comment').each(function () {
            const commentId = jQuery(this).attr('id').replace('comment-', '');
            displayedCommentIds.push(commentId);
        });

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'load_more_comments',
                post_id: postId,
                page: page,
                displayed_ids: displayedCommentIds
            },
            beforeSend: function () {
                button.text('Loading...');
            },
            success: function (response) {
                if (response.success) {
                    $('.commentlist').append(response.data);
                    button.data('page', page + 1);
                    button.text('See all comments');
                }
                button.remove();
            },
            error: function () {
                button.text('Error loading comments');
            }
        });
    });
});
