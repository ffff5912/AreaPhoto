var gulp = require('gulp');
var browserify = require('browserify');
var babelify = require('babelify');
var source = require('vinyl-source-stream');

gulp.task('browserify', function() {
  browserify('./src/AppBundle/Resources/public/js/main.js', { debug: true })
    .transform(babelify)
    .bundle()
    .on("error", function (err) { console.log("Error : " + err.message); })
    .pipe(source('bundle.js'))
    .pipe(gulp.dest('./src/AppBundle/Resources/public/js/build/'));
});

gulp.task('watch', function() {
  gulp.watch('./src/AppBundle/Resources/public/js/**/*.{js,jsx}', ['browserify']);
});

gulp.task('default', ['browserify', 'watch']);
