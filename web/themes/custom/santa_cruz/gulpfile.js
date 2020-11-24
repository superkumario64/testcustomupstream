'use strict';


var $ = require('gulp-load-plugins')(),
  del = require('del'),
  extend = require('extend'),
  fs = require("fs"),
  gulp = require('gulp'),
  named = require('vinyl-named'),
  webpackStream = require('webpack-stream'),
  webpack2 = require('webpack'),
  importOnce = require('node-sass-import-once');

var options = {};

const PRODUCTION = true;

options.gulpWatchOptions = {};

// The root paths are used to construct all the other paths in this
// configuration. The "project" root path is where this gulpfile.js is located.
// While ZURB Foundation distributes this in the theme root folder, you can also
// put this (and the package.json) in your project's root folder and edit the
// paths accordingly.
options.rootPath = {
  project: __dirname + '/',
  theme: __dirname + '/'
};

options.theme = {
  root: options.rootPath.theme,
  scss: options.rootPath.theme + 'assets/scss/',
  css: options.rootPath.theme + 'css/',
  appjs: options.rootPath.theme + 'assets/js/app.js',
  js: options.rootPath.theme + 'js/app'
};

// Define the node-scss configuration.
options.scss = {
  importer: importOnce,
  outputStyle: 'compressed',
  lintIgnore: 'assets/scss/_settings.scss',
  includePaths: [
    options.rootPath.project + 'node_modules/foundation-sites/scss',
    options.rootPath.project + 'node_modules/font-awesome/scss',
    options.rootPath.project + 'node_modules/motion-ui/src'
  ],
};

// If config.js exists, load that config and overriding the options object.
if (fs.existsSync(options.rootPath.project + "/config.js")) {
  var config = {};
  config = require("./config");
  extend(true, options, config);
}

var scssFiles = [
  options.theme.scss + '**/*.scss',
  // Do not open scss partials as they will be included as needed.
  '!' + options.theme.scss + '**/_*.scss',
];

// The default task.
// gulp.task('default', ['build']);


// Lint Sass and JavaScript.
// @todo needs to add a javascript lint task.
// gulp.task('lint', ['lint:sass']);

// Build CSS for development environment.
gulp.task('sass', function () {
  return gulp.src(scssFiles)
    .pipe($.sourcemaps.init())
    // Allow the options object to override the defaults for the task.
    .pipe($.sass(extend(true, {
      noCache: true,
      outputStyle: options.scss.outputStyle,
      sourceMap: true
    }, options.scss)).on('error', $.sass.logError))
    .pipe($.autoprefixer(options.autoprefixer))
    .pipe($.rename({dirname: ''}))
    .pipe($.size({showFiles: true}))
    .pipe($.sourcemaps.write('./'))
    .pipe(gulp.dest(options.theme.css));
});

// Clean CSSJS files.
gulp.task('clean:cssjs', function () {
  return del([
    options.theme.css + '**/*.css',
    options.theme.css + '**/*.map',
    options.theme.js
  ], {force: true});
});

// Build app js
let webpackConfig = {
  module: {
    rules: [
      {
        test: /.js$/,
        use: [
          {
            loader: 'babel-loader'
          }
        ]
      }
    ]
  }
}
// Combine JavaScript into one file
// In production, the file is minified
gulp.task('build:app', function () {
  return gulp.src([options.theme.appjs])
    .pipe(named())
    .pipe($.sourcemaps.init())
    .pipe(webpackStream(webpackConfig, webpack2))
    .pipe($.uglify())
    .pipe($.sourcemaps.write())
    .pipe(gulp.dest(options.theme.js));
});

// Build everything.
gulp.task('default',
  gulp.series('clean:cssjs',
    gulp.parallel('sass'),
    gulp.parallel('build:app')
  )
);
