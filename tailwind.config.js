/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./vendor/filament/**/*.blade.php",
    "./app/Filament/**/*.php",
    "./app/Forms/Components/*.php",
    "./app/Tables/Columns/*.php"
  ],
  theme: {
    extend: {
      colors: {
        // الألوان الرئيسية لنفسجي (ألوان بنفسجية)
        primary: {
          50: '#faf5ff',
          100: '#f3e8ff',
          200: '#e9d5ff',
          300: '#d8b4fe',
          400: '#c084fc',
          500: '#a855f7',
          600: '#9333ea', // اللون الرئيسي لنفسجي
          700: '#7e22ce',
          800: '#6b21a8',
          900: '#581c87',
          950: '#3b0764',
        },
        // الألوان الثانوية لنفسجي (ألوان نيلية)
        secondary: {
          50: '#eef2ff',
          100: '#e0e7ff',
          200: '#c7d2fe',
          300: '#a5b4fc',
          400: '#818cf8',
          500: '#6366f1', // اللون الثانوي لنفسجي
          600: '#4f46e5',
          700: '#4338ca',
          800: '#3730a3',
          900: '#312e81',
          950: '#1e1b4b',
        },
      },
      fontFamily: {
        'cairo': ['Cairo', 'sans-serif'],
      },
      borderRadius: {
        'nafsaji': '1rem',
      },
      boxShadow: {
        'nafsaji': '0 4px 12px rgba(147, 51, 234, 0.15)',
        'nafsaji-hover': '0 8px 16px rgba(147, 51, 234, 0.25)',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography')
  ],
};
