import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import { heroui } from "@heroui/theme";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: ["class"],
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
    ],
    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: "1.5rem",
                sm: "2rem",
                lg: "3rem",
            },
        },
        extend: {
            fontFamily: {
                sans: ["Plus Jakarta Sans", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                border: "hsl(var(--border) / <alpha-value>)",
                input: "hsl(var(--input) / <alpha-value>)",
                ring: "hsl(var(--ring) / <alpha-value>)",
                background: "hsl(var(--background) / <alpha-value>)",
                foreground: "hsl(var(--foreground) / <alpha-value>)",
                muted: {
                    DEFAULT: "hsl(var(--muted) / <alpha-value>)",
                    foreground: "hsl(var(--muted-foreground) / <alpha-value>)",
                },
                card: {
                    DEFAULT: "hsl(var(--card) / <alpha-value>)",
                    foreground: "hsl(var(--card-foreground) / <alpha-value>)",
                },
                popover: {
                    DEFAULT: "hsl(var(--popover) / <alpha-value>)",
                    foreground: "hsl(var(--popover-foreground) / <alpha-value>)",
                },
                primary: {
                    DEFAULT: "hsl(var(--primary) / <alpha-value>)",
                    foreground: "hsl(var(--primary-foreground) / <alpha-value>)",
                },
                secondary: {
                    DEFAULT: "hsl(var(--secondary) / <alpha-value>)",
                    foreground: "hsl(var(--secondary-foreground) / <alpha-value>)",
                },
                accent: {
                    DEFAULT: "hsl(var(--accent) / <alpha-value>)",
                    foreground: "hsl(var(--accent-foreground) / <alpha-value>)",
                },
                destructive: {
                    DEFAULT: "hsl(var(--destructive) / <alpha-value>)",
                    foreground: "hsl(var(--destructive-foreground) / <alpha-value>)",
                },
                success: {
                    DEFAULT: "hsl(var(--success) / <alpha-value>)",
                    foreground: "hsl(var(--success-foreground) / <alpha-value>)",
                },
            },
            borderRadius: {
                lg: "var(--radius)",
                md: "calc(var(--radius) - 2px)",
                sm: "calc(var(--radius) - 4px)",
            },
            boxShadow: {
                card: "0 20px 45px -20px rgba(15, 23, 42, 0.25)",
                glow: "0 8px 30px rgba(59, 130, 246, 0.35)",
            },
            keyframes: {

                'glow-complete': {
                    '0%, 100%': { boxShadow: '0 0 0 rgba(52, 211, 153, 0.0)' },
                    '50%': { boxShadow: '0 0 25px rgba(52, 211, 153, 0.55)' }
                },
                "accordion-down": {
                    from: { height: "0" },
                    to: { height: "var(--radix-accordion-content-height)" },
                },
                "accordion-up": {
                    from: { height: "var(--radix-accordion-content-height)" },
                    to: { height: "0" },
                },
                shimmer: {
                    "0%": { backgroundPosition: "0% 50%" },
                    "100%": { backgroundPosition: "100% 50%" },
                },
                "hero-gradient": {
                    "0%": { backgroundPosition: "0% 50%" },
                    "50%": { backgroundPosition: "50% 55%" },
                    "100%": { backgroundPosition: "100% 50%" },
                },
            },
            animation: {
                'glow-complete': 'glow-complete 1.8s ease-in-out infinite',
                "accordion-down": "accordion-down 0.2s ease-out",
                "accordion-up": "accordion-up 0.2s ease-out",
                shimmer: "shimmer 3s ease-in-out infinite",
                "hero-gradient": "hero-gradient 18s ease-in-out infinite alternate",
            },
        },
    },
    plugins: [
        heroui({
            themes: {
                light: {
                    colors: {
                        background: "#f8fbff",
                        foreground: "#0f172a",
                        default: "#f4f4f5",
                        primary: "#2563eb",
                        secondary: "#e2e8f0",
                        accent: "#38bdf8",
                        content1: "#ffffff",
                        content2: "#f1f5f9",
                        content3: "#e2e8f0",
                    },
                },
                dark: {
                    colors: {
                        background: "#020617",
                        foreground: "#f8fafc",
                        default: "#111827",
                        primary: "#38bdf8",
                        secondary: "#1f2937",
                        accent: "#22d3ee",
                        content1: "#0f172a",
                        content2: "#1e293b",
                        content3: "#334155",
                    },
                },
            },
        }),
        forms({
            strategy: "class",
        }),
    ],
};







