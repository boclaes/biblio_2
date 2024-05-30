import bs from 'browser-sync';
import cssnano from 'cssnano';
import { deleteAsync } from 'del';
import ESLintPlugin from 'eslint-webpack-plugin';
import gulp from 'gulp';
import flatten from 'gulp-flatten';
import imagemin from 'gulp-imagemin';
import gulpMode from 'gulp-mode';
import plumber from 'gulp-plumber';
import postcss from 'gulp-postcss';
import gulpSass from 'gulp-sass';
import sourcemaps from 'gulp-sourcemaps';
import uglify from 'gulp-uglify';
import dartSass from 'sass';
import named from 'vinyl-named';
import webpack from 'webpack-stream';


const { dest, src, watch, parallel, series } = gulp;

const mode = gulpMode();
const sass = gulpSass(dartSass);
const browserSync = bs.create();

console.log(mode.development());

const CONFIG = {
  baseDir: './public',
  src: {
    cwd: './src',
    styles: '**/*.scss',
    scripts: '**/*.js',
    graphics: '**/*.{png,jpg,gif,svg}',
    fonts: '**/*.{woff,woff2,ttf,otf}',
    media: '**/*.{mp4,webm,ogg,mp3,wav,flac,acc}',
    html: '**/*.html',
  },
  dest: {
    cwd: './public/resources',
    styles: '/styles',
    scripts: '/scripts',
    graphics: '/images',
    fonts: '/fonts',
    html: './public',
  },

  markup: './{public,site}/**/*.{php,html}',
};

function clean() {
  const deletable = [
    `${CONFIG.dest.cwd}${CONFIG.dest.styles}/**/*`,
    `${CONFIG.dest.cwd}${CONFIG.dest.scripts}/**/*`,
    `${CONFIG.dest.cwd}${CONFIG.dest.graphics}/**/*`,
    `${CONFIG.dest.cwd}${CONFIG.dest.fonts}/**/*`,
    `${CONFIG.dest.cwd}${CONFIG.dest.media}/**/*`,
  ];
  return deleteAsync(deletable);
}

function styles() {
  return src(CONFIG.src.styles, { cwd: CONFIG.src.cwd })
    .pipe(plumber())
    .pipe(mode.development(sourcemaps.init()))
    .pipe(sass())
    .pipe(postcss([
      cssnano()
    ]))
    .pipe(flatten())
    .pipe(mode.development(sourcemaps.write('')))
    .pipe(dest(`${CONFIG.dest.cwd}${CONFIG.dest.styles}`))
    .pipe(browserSync.stream());
}

function scripts() {
  return src(CONFIG.src.scripts, { cwd: CONFIG.src.cwd })
    .pipe(plumber())
    .pipe(named())
    .pipe(webpack({
      mode: mode.production() ? 'production' : 'development',
      module: {
        rules: [
          {
            test: /\.scss$/,
          },
        ],
      },
      plugins: [new ESLintPlugin()],
    }))
    .pipe(mode.development(sourcemaps.init()))
    .pipe(uglify())
    .pipe(mode.development(sourcemaps.write('')))
    .pipe(dest(`${CONFIG.dest.cwd}${CONFIG.dest.scripts}`))
    .pipe(browserSync.stream());
}

function graphics() {
  return src(CONFIG.src.graphics, { cwd: CONFIG.src.cwd })
    .pipe(plumber())
    .pipe(imagemin())
    .pipe(flatten())
    .pipe(dest(`${CONFIG.dest.cwd}${CONFIG.dest.graphics}`))
    .pipe(browserSync.stream());
}

function fonts() {
  return src(CONFIG.src.fonts, { cwd: CONFIG.src.cwd })
    .pipe(plumber())
    .pipe(flatten())
    .pipe(dest(`${CONFIG.dest.cwd}${CONFIG.dest.fonts}`));
}

function media() {
  return src(CONFIG.src.media, { cwd: CONFIG.src.cwd })
    .pipe(plumber())
    .pipe(flatten())
    .pipe(dest(`${CONFIG.dest.cwd}${CONFIG.dest.media}`));
}

function html() {
  return src(CONFIG.src.html, { cwd: CONFIG.src.cwd })
    .pipe(plumber())
    .pipe(dest('public'))  
    .pipe(browserSync.stream());
}

function watchAll() {
  clean();
  styles();
  scripts();
  graphics();
  fonts();
  media();
  html();

  browserSync.init({
    proxy: CONFIG.url || null,
    server: CONFIG.baseDir ? { baseDir: CONFIG.baseDir } : null,
    port: 9999,
    open: true,
  });

  watch(CONFIG.src.styles, { cwd: CONFIG.src.cwd }, styles);
  watch(CONFIG.src.scripts, { cwd: CONFIG.src.cwd }, scripts);
  watch(CONFIG.src.graphics, { cwd: CONFIG.src.cwd }, graphics);
  watch(CONFIG.src.fonts, { cwd: CONFIG.src.cwd }, fonts);
  watch(CONFIG.src.media, { cwd: CONFIG.src.cwd }, media);
  watch(CONFIG.src.html, { cwd: CONFIG.src.cwd }, html);

  watch(CONFIG.markup).on('change', browserSync.reload);
}

export default series(clean, watchAll);
export const build = series(clean, parallel(styles, scripts, graphics, fonts, media, html));