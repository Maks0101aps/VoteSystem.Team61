import defaultTheme from 'tailwindcss/defaultTheme'
const colors = require('tailwindcss/colors')

export default {
  content: ['./resources/**/*.{js,vue,blade.php}'],
  theme: {
    extend: {
      colors: {
        green: colors.orange,
      },
      fontFamily: {
        sans: ['"Cerebri Sans"', ...defaultTheme.fontFamily.sans],
      },
    },
  },
}
