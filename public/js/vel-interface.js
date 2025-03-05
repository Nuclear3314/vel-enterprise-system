class VELInterface {
    constructor() {
        this.aiWindow = document.querySelector('#vel-floating-window');
        this.aiInput = document.querySelector('#vel-ai-input');
        this.aiExecute = document.querySelector('#vel-ai-execute');
        this.aiChat = document.querySelector('.vel-ai-chat');
        
        this.initializeEvents();
        this.setupWebSocket();
    }

    initializeEvents() {
        this.aiExecute.addEventListener('click', () => this.handleCommand());
        this.aiInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.handleCommand();
        });
    }

    async handleCommand() {
        const command = this.aiInput.value.trim();
        if (!command.startsWith('執行')) {
            this.appendMessage('system', '請使用"執行"開始您的命令');
            return;
        }

        const actualCommand = command.substring(2).trim();
        try {
            const response = await this.executeCommand(actualCommand);
            this.appendMessage('ai', response.message);
        } catch (error) {
            this.appendMessage('error', error.message);
        }
    }

    appendMessage(type, content) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `vel-message ${type}`;
        messageDiv.innerHTML = `
            <span class="timestamp">${new Date().toLocaleTimeString()}</span>
            <div class="content">${content}</div>
        `;
        this.aiChat.appendChild(messageDiv);
        this.aiChat.scrollTop = this.aiChat.scrollHeight;
    }
}

// 初始化介面
document.addEventListener('DOMContentLoaded', () => {
    new VELInterface();
});