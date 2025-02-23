/**
 * VEL Enterprise System Admin Scripts
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 * @version 1.0.0
 * @author Nuclear3314
 * @copyright 2025 Nuclear3314
 */

/* global jQuery, velAdminData, ajaxurl */
(function($) {
    'use strict';

    const VELAdmin = {
        /**
         * 初始化
         */
        init: function() {
            this.initTabs();
            this.initTooltips();
            this.initModals();
            this.initForms();
            this.initNotifications();
            this.initModelManagement();
            this.initSettingsPage();
        },

        /**
         * 初始化頁籤
         */
        initTabs: function() {
            $('.vel-tabs').on('click', '.vel-tab', function(e) {
                e.preventDefault();
                const $this = $(this);
                const target = $this.data('target');

                // 更新活動狀態
                $('.vel-tab').removeClass('active');
                $this.addClass('active');

                // 顯示對應內容
                $('.vel-tab-content').hide();
                $(target).fadeIn();

                // 更新 URL hash
                if (history.pushState) {
                    history.pushState(null, null, target);
                }
            });

            // 從 URL hash 加載頁籤
            if (window.location.hash) {
                $(`.vel-tab[data-target="${window.location.hash}"]`).trigger('click');
            }
        },

        /**
         * 初始化工具提示
         */
        initTooltips: function() {
            $('.vel-tooltip').each(function() {
                const $this = $(this);
                const position = $this.data('position') || 'top';

                $this.tooltip({
                    position: position,
                    content: $this.attr('title'),
                    items: '[data-tooltip]',
                    track: true
                });
            });
        },

        /**
         * 初始化模態框
         */
        initModals: function() {
            // 打開模態框
            $('.vel-modal-trigger').on('click', function(e) {
                e.preventDefault();
                const target = $(this).data('modal');
                $(`#${target}`).addClass('active');
                $('body').addClass('vel-modal-open');
            });

            // 關閉模態框
            $('.vel-modal-close, .vel-modal-backdrop').on('click', function() {
                $(this).closest('.vel-modal').removeClass('active');
                $('body').removeClass('vel-modal-open');
            });

            // 阻止冒泡
            $('.vel-modal-content').on('click', function(e) {
                e.stopPropagation();
            });
        },

        /**
         * 初始化表單
         */
        initForms: function() {
            // AJAX 表單提交
            $('.vel-form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $submit = $form.find('[type="submit"]');
                const originalText = $submit.text();

                // 禁用提交按鈕
                $submit.prop('disabled', true).text('處理中...');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            VELAdmin.showNotification('success', response.data.message);
                            if (response.data.redirect) {
                                window.location.href = response.data.redirect;
                            }
                        } else {
                            VELAdmin.showNotification('error', response.data.message);
                        }
                    },
                    error: function() {
                        VELAdmin.showNotification('error', '發生錯誤，請稍後再試。');
                    },
                    complete: function() {
                        $submit.prop('disabled', false).text(originalText);
                    }
                });
            });

            // 表單驗證
            $('.vel-form').on('input', 'input, select, textarea', function() {
                const $field = $(this);
                const value = $field.val();
                const required = $field.prop('required');
                const pattern = $field.attr('pattern');

                if (required && !value) {
                    $field.addClass('vel-error');
                } else if (pattern && !new RegExp(pattern).test(value)) {
                    $field.addClass('vel-error');
                } else {
                    $field.removeClass('vel-error');
                }
            });
        },

        /**
         * 初始化通知系統
         */
        initNotifications: function() {
            this.notificationQueue = [];
            this.isShowingNotification = false;

            // 處理通知隊列
            setInterval(() => {
                if (!this.isShowingNotification && this.notificationQueue.length > 0) {
                    const notification = this.notificationQueue.shift();
                    this.displayNotification(notification);
                }
            }, 300);
        },

        /**
         * 顯示通知
         *
         * @param {string} type    通知類型
         * @param {string} message 通知消息
         */
        showNotification: function(type, message) {
            this.notificationQueue.push({ type, message });
        },

        /**
         * 顯示通知元素
         *
         * @param {Object} notification 通知對象
         */
        displayNotification: function(notification) {
            this.isShowingNotification = true;

            const $notification = $('<div>')
                .addClass(`vel-notification vel-${notification.type}`)
                .text(notification.message)
                .appendTo('.vel-notifications-container');

            setTimeout(() => {
                $notification.addClass('vel-show');
            }, 10);

            setTimeout(() => {
                $notification.removeClass('vel-show');
                setTimeout(() => {
                    $notification.remove();
                    this.isShowingNotification = false;
                }, 300);
            }, 3000);
        },

        /**
         * 初始化模型管理
         */
        initModelManagement: function() {
            // 模型列表更新
            this.updateModelList();

            // 創建模型
            $('#vel-create-model-form').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: `${velAdminData.apiBase}/models`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        VELAdmin.showNotification('success', '模型創建成功');
                        VELAdmin.updateModelList();
                    },
                    error: function(xhr) {
                        VELAdmin.showNotification('error', xhr.responseJSON.message);
                    }
                });
            });

            // 刪除模型
            $(document).on('click', '.vel-delete-model', function() {
                const modelId = $(this).data('id');
                if (confirm('確定要刪除此模型嗎？')) {
                    $.ajax({
                        url: `${velAdminData.apiBase}/models/${modelId}`,
                        type: 'DELETE',
                        success: function() {
                            VELAdmin.showNotification('success', '模型已刪除');
                            VELAdmin.updateModelList();
                        },
                        error: function(xhr) {
                            VELAdmin.showNotification('error', xhr.responseJSON.message);
                        }
                    });
                }
            });
        },

        /**
         * 更新模型列表
         */
        updateModelList: function() {
            $.ajax({
                url: `${velAdminData.apiBase}/models`,
                type: 'GET',
                success: function(response) {
                    const $list = $('#vel-model-list');
                    $list.empty();

                    response.models.forEach(model => {
                        $list.append(`
                            <div class="vel-model-item">
                                <h3>${model.name}</h3>
                                <p>類型: ${model.type}</p>
                                <p>狀態: ${model.status}</p>
                                <div class="vel-model-actions">
                                    <button class="vel-edit-model" data-id="${model.id}">
                                        編輯
                                    </button>
                                    <button class="vel-delete-model" data-id="${model.id}">
                                        刪除
                                    </button>
                                </div>
                            </div>
                        `);
                    });
                }
            });
        },

        /**
         * 初始化設置頁面
         */
        initSettingsPage: function() {
            // 保存設置
            $('#vel-settings-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'vel_save_settings',
                        settings: formData
                    },
                    success: function(response) {
                        if (response.success) {
                            VELAdmin.showNotification('success', '設置已保存');
                        } else {
                            VELAdmin.showNotification('error', response.data.message);
                        }
                    }
                });
            });

            // 重置設置
            $('#vel-reset-settings').on('click', function() {
                if (confirm('確定要重置所有設置嗎？')) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'vel_reset_settings'
                        },
                        success: function(response) {
                            if (response.success) {
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        }
    };

    // 文檔加載完成後初始化
    $(document).ready(function() {
        VELAdmin.init();
    });

})(jQuery);