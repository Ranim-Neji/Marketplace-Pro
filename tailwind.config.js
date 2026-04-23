import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                background: 'var(--background)',
                card: 'var(--card)',
                popover: 'var(--popover)',
                foreground: 'var(--foreground)',
                muted: {
                    DEFAULT: 'var(--muted)',
                    foreground: 'var(--muted-foreground)'
                },
                primary: {
                    DEFAULT: 'var(--primary)',
                    foreground: 'var(--primary-foreground)',
                    hover: 'var(--primary-hover)',
                },
                accent: {
                    DEFAULT: 'var(--accent)',
                    foreground: 'var(--accent-foreground)'
                },
                dark: {
                    DEFAULT: 'var(--dark)',
                    foreground: 'var(--dark-foreground)'
                },
                warning: {
                    DEFAULT: 'var(--warning)',
                    foreground: 'var(--warning-foreground)'
                },
                border: 'var(--border)',
                input: 'var(--input)',
                ring: 'var(--ring)',
                sidebar: {
                    DEFAULT: 'var(--sidebar)',
                    border: 'var(--sidebar-border)',
                    accent: 'var(--sidebar-accent)',
                    foreground: 'var(--sidebar-foreground)',
                    primary: 'var(--sidebar-primary)',
                    'primary-foreground': 'var(--sidebar-primary-foreground)',
                    ring: 'var(--sidebar-ring)'
                }
            },
            fontFamily: {
                sans: ['var(--font-sans)', ...defaultTheme.fontFamily.sans],
                serif: ['var(--font-serif)', ...defaultTheme.fontFamily.serif],
                mono: ['var(--font-mono)', ...defaultTheme.fontFamily.mono],
            },
            boxShadow: {
                'premium': 'var(--shadow-offset-x) var(--shadow-offset-y) var(--shadow-blur) var(--shadow-spread) var(--shadow-color)',
            }
        },
    },

    plugins: [forms],
};
