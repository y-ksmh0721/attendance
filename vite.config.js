import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    base: '/build/', 
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/style.css', // ← これを追加
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});



// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
// });
