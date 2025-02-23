 Frontend JavaScript
(function($) {
    'use strict';

     主要功能類
    class VELFrontend {
        constructor() {
            this.initializeComponents();
            this.bindEvents();
        }

         初始化組件
        initializeComponents() {
            $('.vel-container').each(function() {
                $(this).trigger('init');
            });
        }

         綁定事件
        bindEvents() {
             表單提交處理
            $('.vel-form').on('submit', this.handleFormSubmit.bind(this));
            
             動態內容加載
            $('.vel-load-more').on('click', this.handleLoadMore.bind(this));
        }

         處理表單提交
        handleFormSubmit(e) {
            e.preventDefault();
            const $form = $(e.currentTarget);
            const data = $form.serialize();

            $.ajax({
                url vel_ajax.url,
                type 'POST',
                data {
                    action 'vel_form_submit',
                    nonce vel_ajax.nonce,
                    formData data
                },
                success (response) = {
                    if (response.success) {
                        this.showMessage('success', response.data.message);
                    } else {
                        this.showMessage('error', response.data.message);
                    }
                },
                error () = {
                    this.showMessage('error', 'An error occurred. Please try again.');
                }
            });
        }

         處理加載更多
        handleLoadMore(e) {
            e.preventDefault();
            const $button = $(e.currentTarget);
            const page = $button.data('page')  1;

            $.ajax({
                url vel_ajax.url,
                type 'POST',
                data {
                    action 'vel_load_more',
                    nonce vel_ajax.nonce,
                    page page + 1
                },
                success (response) = {
                    if (response.success) {
                        $('.vel-content').append(response.data.html);
                        $button.data('page', page + 1);
                        
                        if (!response.data.hasMore) {
                            $button.hide();
                        }
                    }
                }
            });
        }

         顯示消息
        showMessage(type, message) {
            const $message = $('div')
                .addClass(`vel-message vel-message-${type}`)
                .text(message);

            $('.vel-container').prepend($message);

            setTimeout(() = {
                $message.fadeOut(() = $message.remove());
            }, 3000);
        }
    }

     當文檔準備就緒時初始化
    $(document).ready(() = {
        window.velFrontend = new VELFrontend();
    });

})(jQuery);