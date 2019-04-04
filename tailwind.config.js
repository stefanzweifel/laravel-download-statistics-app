let defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  theme: {
    fontFamily: {
        ...defaultTheme.fonts,
        'sans': [
            'IBM Plex Sans',
            '-apple-system',
            'BlinkMacSystemFont',
            'avenir next',
            'avenir',
            'helvetica neue',
            'helvetica',
            'Ubuntu',
            'roboto',
            'noto',
            'segoe ui',
            'arial',
            'sans-serif'
        ],
    }
  },
  variants: {
    // Some useful comment
  },
  plugins: [
    // Some useful comment
  ]
}
