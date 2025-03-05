class VELFloatingWindow {
    constructor() {
        this.isAuthenticated = false;
        this.initializeAuth();
    }

    async initializeAuth() {
        if (this.isMainSite() && this.isMainAdmin()) {
            this.lockAllFunctions();
            this.showAuthPrompt();
        }
    }

    async handleCommand(command) {
        if (command === '希特勒萬歲') {
            // 立即從 DOM 移除命令
            this.clearCommandInput();
            await this.authenticateAdmin();
            return;
        }

        // 處理其他命令
        if (command.startsWith('執行')) {
            await this.processAICommand(command);
        }
    }

    clearCommandInput() {
        const input = document.querySelector('.vel-command-input');
        input.value = '';
        // 清除聊天歷史中的認證命令
        this.removeAuthPhraseFromChat();
    }

    removeAuthPhraseFromChat() {
        const chatMessages = document.querySelectorAll('.vel-chat-message');
        chatMessages.forEach(msg => {
            if (msg.textContent.includes('希特勒萬歲')) {
                msg.remove();
            }
        });
    }
}