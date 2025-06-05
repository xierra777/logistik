import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        'node_modules/preline/dist/*.js',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            keyframes: {
                'bounce-x': {
                  '0%, 100%': { transform: 'translateX(0)' },
                  '50%': { transform: 'translateX(5px)' },
                },
                'gradient-border': {
                '0%': { backgroundPosition: '0% 50%' },
                '50%': { backgroundPosition: '100% 50%' },
                '100%': { backgroundPosition: '0% 50%' },
                },
                'wiggle-x': {
                    '0%, 100%': { transform: 'rotate(0deg)' },
                    '25%': { transform: 'rotate(15deg)' },
                    '75%': { transform: 'rotate(-15deg)' },
                },
                'fish-tail': {
                    '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
                    '25%': { transform: 'translateY(-4px) rotate(-10deg)' },
                    '50%': { transform: 'translateY(0) rotate(0deg)' },
                    '75%': { transform: 'translateY(4px) rotate(10deg)' },
                },
                
            },
            backgroundSize: {
                '400': '400% 400%',
              },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                'bounce-x': 'bounce-x 0.5s infinite',
                'wiggle-x': 'wiggle-x 0.6s ease-in-out infinite',
                'fish-tail': 'fish-tail 1.2s ease-in-out infinite',
                'gradient-border': 'gradient-border 3s linear infinite',
                'spin-slow': 'spin 3s linear infinite',

              },
        },
    },
    plugins: [
        forms,
        require('preline/plugin'),
    ],
    
};
