'use strict';
var fs = require('fs');

module.exports = function(grunt) {

    if (process.env.NODE_ENV !== 'production') {
      require('time-grunt')(grunt);
    }

    //Initializing the configuration object
      grunt.initConfig({
        revision: {
          options: {
            property: 'meta.revision',
            ref: 'HEAD',
            short: true
          }
        },
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
            dest: '<%= meta.revision %>/images/'
          },
          css: {
            cwd: 'laravel/public/css',
            src: '**',
            dest: '<%= meta.revision %>/css/'
          },
          js: {
            cwd: 'laravel/public/js',
            src: '**',
            dest: '<%= meta.revision %>/js/'
          },
          fonts: {
            cwd: 'laravel/public/fonts',
            src: '**',
            dest: '<%= meta.revision %>/fonts/'
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

    grunt.registerTask('writeRevision', function() {
      var done = this.async();
      console.log(grunt.config.data.meta.revision);
      var file = grunt.file.write('.gitver',grunt.config.data.meta.revision);

      done(true);
    });

    //just a compile and revision writer
    grunt.registerTask('compile',['revision','compass','writeRevision']);

    //composer update
    grunt.registerTask('fullUpdate',['revision']);

    //s3 deployment
    grunt.registerTask('deploy',['revision','compass','s3','writeRevision']);

};