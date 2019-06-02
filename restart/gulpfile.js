var gulp           = require('gulp'),
	gutil          = require('gulp-util' ),
	sass           = require('gulp-sass'),
	concat         = require('gulp-concat'),
	uglify         = require('gulp-uglify'),
	cleanCSS       = require('gulp-clean-css'),
	rename         = require('gulp-rename'),
	imagemin       = require('gulp-imagemin'),
	notify         = require("gulp-notify"),
	cache          = require('gulp-cache'),
	autoprefixer   = require('gulp-autoprefixer');

// Скрипты проекта

gulp.task('common-js', function() {
	return gulp.src([
		'app/js/common.js',
		])
	.pipe(concat('common.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest('app/js'));
});

gulp.task('js', ['common-js'], function() {
	return gulp.src([
		'app/libs/jquery/dist/jquery.min.js',
		'app/js/bootstrap.min.js',
		'app/libs/owl.carousel/owl.carousel.min.js',
		'app/js/jquery.paroller.min.js',
		'app/js/jquery.maskedinput.min.js',
		'app/js/common.min.js', // Всегда в конце
		])
	.pipe(concat('scripts.min.js'))
	.pipe(gulp.dest('catalog/view/theme/default/js'))
});

gulp.task('sass', function() {
	return gulp.src('app/sass/**/*')
	.pipe(sass({outputStyle: 'expand'}).on("error", notify.onError()))
	.pipe(rename({suffix: '.min', prefix : ''}))
	.pipe(autoprefixer(['last 15 versions']))
	.pipe(cleanCSS())
	.pipe(gulp.dest('catalog/view/theme/default/stylesheet'))
});

gulp.task('watch', ['sass', 'js'], function() {
	gulp.watch('app/sass/**/*', ['sass']);
	gulp.watch(['libs/**/*.js', 'app/js/common.js'], ['js']);
});

gulp.task('imagemin', function() {
	return gulp.src('app/img/**/*')
	.pipe(cache(imagemin()))
	.pipe(gulp.dest('catalog/view/theme/default/img'));
});

gulp.task('build', ['imagemin', 'sass', 'js'], function() {

	var buildCss = gulp.src([
		'app/css/main.min.css',
	]).pipe(gulp.dest('catalog/view/theme/default/stylesheet'));

	var buildJs = gulp.src([
		'app/js/scripts.min.js',
		]).pipe(gulp.dest('catalog/view/theme/default/js'));

	var buildFonts = gulp.src([
		'app/fonts/**/*',
		]).pipe(gulp.dest('catalog/view/theme/default/fonts'));

});

gulp.task('default', ['watch']);
