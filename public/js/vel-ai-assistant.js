jQuery(document).ready(function($) {
    const aiAssistant = {
        init: function() {
            this.window = $('#vel-floating-window');
            this.chat = $('.vel-ai-chat');
            this.input = $('#vel-ai-input');
            this.executeBtn = $('#vel-ai-execute');
            this.bindEvents();
            this.initializeWebSocket();
        },

        bindEvents: function() {
            $('.vel-close').on('click', () => this.window.hide());
            this.executeBtn.on('click', () => this.executeCommand());
            this.input.on('keypress', (e) => {
                if (e.which === 13) this.executeCommand();
            });
        },

        executeCommand: function() {
            const command = this.input.val().trim();
            if (!command) return;

            // 檢查是否為執行命令
            if (command.startsWith('執行')) {
                this.sendToAI(command.substring(2).trim());
            } else {
                this.appendMessage('system', '請使用"執行"開始您的命令');
            }
            this.input.val('');
        },

        sendToAI: function(command) {
            $.ajax({
                url: velAiData.apiUrl + '/execute',
                method: 'POST',
                data: {
                    command: command,
                    nonce: velAiData.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.appendMessage('ai', response.data.message);
                    } else {
                        this.appendMessage('error', response.data.message);
                    }
                },
                error: (xhr) => {
                    this.appendMessage('error', '執行命令時發生錯誤');
                }
            });
        },

        appendMessage: function(type, message) {
            const messageHtml = `
                <div class="vel-message ${type}">
                    <span class="timestamp">${new Date().toLocaleTimeString()}</span>
                    <div class="content">${message}</div>
                </div>
            `;
            this.chat.append(messageHtml);
            this.chat.scrollTop(this.chat[0].scrollHeight);
        }
    };

    aiAssistant.init();
});
