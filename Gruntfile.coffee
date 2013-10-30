path = require 'path'
fs = require 'fs'
child = require 'child_process'

module.exports = (grunt) ->
  grunt.initConfig
    pkg: grunt.file.readJSON 'package.JSON'
    less:
      development:
        options:
          paths: 'public_src/less'
        files:
          "public/css/style.css": "public_src/less/style.less"
    browserify:
      mains:
        src: ['public_src/main/**/*.coffee']
        dest: 'public/js/main.js'
        options:
          transform: ['coffeeify']
          debug: true
          external: [
            'jquery'
            'jquery-mobile'
            'less'
            'modernizr'
          ]
          alias: [
            'bower_components/jspdf/dist/jspdf.source.js:jspdf'
            'public_src/libs/songsheet-0.1.js:songsheet'
            'bower_components/mustache/mustache.js:mustache'
          ]
          aliasMappings: [
            cwd: 'public_src'
            src: ['**/*.coffee', '**/*.js']
            dest: ''
          ]
      lib:
        src: [
          'public_src/libs/jquery-2.0.3.js'
          'public_src/libs/jquery.mobile-1.4.0-rc.1-psalted.js'
        ]
        dest: 'public/js/lib.js'
        options:
          alias: [
            'public_src/libs/jquery-2.0.3.js:jquery'
            'public_src/libs/jquery.mobile-1.4.0-rc.1-psalted.js:jquery-mobile'
          ]
          shim:
            'jquery-mobile':
              path: 'public_src/libs/jquery.mobile-1.4.0-rc.1-psalted.js'
              exports: null
              depends:
                jquery: 'jQuery'
    watch:
      less:
        files: ['public_src/less/**/*.less']
        tasks: ['less:development']
        options:
          spawn: false
      browserify:
        files: ['public_src/**/*.js', 'public_src/**/*.coffee']
        tasks: ['browserify:mains']
        options:
          # interrupt: true
          spawn: false
    # concurrent:
    #   watchman:
    #     tasks: ['watch']
    #     options:
    #       logConcurrentOutput: true

  #grunt.loadNpmTasks 'grunt-concurrent'
  grunt.loadNpmTasks 'grunt-browserify'
  grunt.loadNpmTasks 'grunt-contrib-less'
  grunt.loadNpmTasks 'grunt-contrib-watch'

  #grunt.registerTask 'watchman', ['concurrent:watchman']
  grunt.event.on 'watch', (action, filepath, target) ->
    grunt.log.writeln(target + ': ' + filepath + ' has ' + action)