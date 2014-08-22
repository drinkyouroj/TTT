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
              cssDir: 'laravel/public/css/compiled/'
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