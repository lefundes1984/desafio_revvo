module.exports = {
  content: ['./app/Views/**/*.php', './resources/js/**/*.js', './public/**/*.php'],
  theme: {
    extend: {
      colors: {
        primary: '#0f172a',
        accent: '#0ea5e9',
        muted: '#64748b'
      },
      fontFamily: {
        heading: ['"Work Sans"', 'ui-sans-serif', 'system-ui'],
        body: ['"Inter"', 'ui-sans-serif', 'system-ui']
      }
    }
  },
  plugins: []
};
