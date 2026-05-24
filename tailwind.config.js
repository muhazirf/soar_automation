import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Surface Colors
                surface: {
                    DEFAULT: '#131315',
                    dim: '#131315',
                    bright: '#39393b',
                    container: '#1f1f21',
                    'container-low': '#1b1b1d',
                    'container-high': '#2a2a2c',
                    'container-highest': '#343536',
                },
                // Primary Colors
                primary: {
                    DEFAULT: '#b9c7e4',
                    container: '#0a192f',
                    'on-primary': '#233148',
                    'on-container': '#74829d',
                    'fixed': '#d6e3ff',
                    'fixed-dim': '#b9c7e4',
                },
                // Secondary Colors
                secondary: {
                    DEFAULT: '#b8c8da',
                    container: '#394857',
                    'on-secondary': '#223240',
                    'on-container': '#a7b7c8',
                    'fixed': '#d4e4f6',
                    'fixed-dim': '#b8c8da',
                },
                // Tertiary Colors
                tertiary: {
                    DEFAULT: '#e7bf99',
                    container: '#281400',
                    'on-tertiary': '#432b10',
                    'on-container': '#9d7b5a',
                    'fixed': '#ffdcbd',
                    'fixed-dim': '#e7bf99',
                },
                // Functional Colors
                error: {
                    DEFAULT: '#ffb4ab',
                    container: '#93000a',
                    'on-error': '#690005',
                    'on-container': '#ffdad6',
                },
                success: '#64ffda',  // Signal Cyan
                warning: '#ffd700',
                info: '#64b5f6',
                // Text Colors
                'on-surface': '#e4e2e4',
                'on-surface-variant': '#c5c6cd',
                outline: '#8f9097',
                'outline-variant': '#44474d',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', 'Courier New', 'monospace'],
                display: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                'display-lg': ['48px', { lineHeight: '56px', letterSpacing: '-0.02em', fontWeight: '700' }],
                'headline-md': ['24px', { lineHeight: '32px', letterSpacing: '-0.01em', fontWeight: '600' }],
                'headline-md-mobile': ['20px', { lineHeight: '28px', fontWeight: '600' }],
                'body-md': ['16px', { lineHeight: '24px', fontWeight: '400' }],
                'label-sm': ['12px', { lineHeight: '16px', letterSpacing: '0.05em', fontWeight: '500' }],
            },
            borderRadius: {
                'DEFAULT': '0.125rem',   // 2px
                'sm': '0.125rem',         // 2px
                'md': '0.25rem',          // 4px
                'lg': '0.5rem',           // 8px
                'xl': '0.75rem',          // 12px
                'full': '9999px',
            },
            spacing: {
                'unit': '4px',
                'gutter': '16px',
                'container-max': '1440px',
            },
            boxShadow: {
                'glow': '0 0 20px rgba(100, 255, 218, 0.15)',
                'glow-lg': '0 0 40px rgba(100, 255, 218, 0.25)',
                'glass': '0 8px 32px rgba(0, 0, 0, 0.4)',
            },
            backdropBlur: {
                'glass': '12px',
            },
            animation: {
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'fade-in': 'fadeIn 0.3s ease-out',
            },
            keyframes: {
                fadeIn: {
                    'from': { opacity: '0', transform: 'translateY(10px)' },
                    'to': { opacity: '1', transform: 'translateY(0)' },
                },
            },
        },
    },

    plugins: [forms],
};
