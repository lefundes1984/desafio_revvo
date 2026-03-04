/* eslint-disable no-console */
const { src, dest, series, parallel, watch } = require('gulp');
const postcss = require('gulp-postcss');
const tailwindcss = require('tailwindcss');
const autoprefixer = require('autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const concat = require('gulp-concat');
const terser = require('gulp-terser');
const cleanCSS = require('gulp-clean-css');

const paths = {
  styles: {
    src: 'resources/css/app.css',
    watch: ['resources/css/**/*.css', 'app/Views/**/*.php'],
    dest: 'public/assets/dist'
  },
  scripts: {
    src: 'resources/js/**/*.js',
    dest: 'public/assets/dist'
  }
};

async function clean() {
  const { deleteAsync } = await import('del');
  return deleteAsync(['public/assets/dist/*', '!public/assets/dist/.gitkeep']);
}

function styles() {
  const plugins = [tailwindcss('./tailwind.config.js'), autoprefixer()];

  return src(paths.styles.src)
    .pipe(sourcemaps.init())
    .pipe(postcss(plugins))
    .pipe(cleanCSS())
    .pipe(rename({ basename: 'app', suffix: '.min' }))
    .pipe(sourcemaps.write('.'))
    .pipe(dest(paths.styles.dest));
}

function scripts() {
  return src(paths.scripts.src, { sourcemaps: true })
    .pipe(concat('app.js'))
    .pipe(terser())
    .pipe(rename({ basename: 'app', suffix: '.min' }))
    .pipe(dest(paths.scripts.dest, { sourcemaps: '.' }));
}

function watchFiles() {
  watch(paths.styles.watch, styles);
  watch(paths.scripts.src, scripts);
}

const dev = series(clean, parallel(styles, scripts));
const build = series(clean, parallel(styles, scripts));

exports.clean = clean;
exports.styles = styles;
exports.scripts = scripts;
exports.dev = dev;
exports.build = build;
exports.watch = series(dev, watchFiles);
exports.default = dev;
