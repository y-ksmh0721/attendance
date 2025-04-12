import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        https: true,  // HTTPSで開発サーバーを立ち上げる
        host: '0.0.0.0',  // 任意のホストからアクセスできるようにする（ローカルネットワークからもアクセス可能）
        hmr: {
            protocol: 'wss',  // HMRはWebSocket Secure（wss）を使用
            host: 'localhost',  // 必要に応じて適切なホストを設定
        },
    },
});

