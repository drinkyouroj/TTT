'use strict';

module.exports = function(grunt) {

    if (process.env.NODE_ENV !== 'production') {
      require('time-grunt')(grunt);
    }

    //Initializing the configuration object
      grunt.initConfig({
        // Task configuration
        compass: {
          dist: {
            options: {
              sassDir: 'laravel/sass/',
              cssDir: 'laravel/public/css/compiled/',
              outputStyle: 'compressed'
            }
          }
        },
        phpunit: {
            classes: {
            },
            options: {
              configuration: "laravel/phpunit.xml"
            }
        },
        s3: {
          options: {
            accessKeyId: 'AKIAIYOYRS5RYNS6TBPQ',
            secretAccessKey: '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',
            bucket: 'ttt-statics-cdn'
          },
          images: {
            cwd: 'laravel/public/images',
            src: '**',
            dest: 'images/'
          },
          css: {
            cwd: 'laravel/public/css',
            src: '**',
            dest: 'css/'
          },
          js: {
            cwd: 'laravel/public/js',
            src: '**',
            dest: 'js/'
          },
          fonts: {
            cwd: 'laravel/public/fonts',
            src: '**',
            dest: 'fonts/'
          }
        },
        watch: {
          sass: {
            files: ['laravel/sass/**/*.scss'],
            tasks: ['compass']
          },
          tests: {
            files: ['laravel/app/applogic/*.php', 'laravel/app/controllers/*.php', 'laravel/app/models/*.php', 'laravel/app/repositories/*/*.php'],  //the task will run only when you save files in this location
            tasks: ['phpunit']
          }
          
        }
      });

    // Plugin loading
    require('load-grunt-tasks')(grunt);
    grunt.loadNpmTasks('grunt-phpunit');

    // Task definition
    grunt.registerTask('default', ['watch']);

};