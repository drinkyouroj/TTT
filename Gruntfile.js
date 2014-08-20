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
          //...
        },
        watch: {
          sass: {
            files: ['laravel/sass/**/*.scss'],
            tasks: ['compass']
          }
          //...
        }
      });

    // Plugin loading
    require('load-grunt-tasks')(grunt);

    // Task definition
    grunt.registerTask('default', ['watch']);

};