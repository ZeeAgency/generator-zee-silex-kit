'use strict';
var generators = require('yeoman-generator');

module.exports = generators.Base.extend({
  initializing: function() {
    this.pkg = require('../package.json');
  },

  prompting: function() {
    var done = this.async();

    var prompts = [{
      type: 'input',
      name: 'name',
      message: 'Your project name',
      // store: true,
      default: this.config.get('name') || this.appname
    }];

    this.prompt(prompts, function (answers) {
      this.config.set('name', answers.name);

      this.appname = answers.name;

      done();
    }.bind(this));
  },

  configuring: function() {
    this.template('_composer.json', 'composer.json');
    this.copy('_.gitignore', '.gitignore');
    this.copy('_.editorconfig', '.editorconfig');
    this.copy('environment.php.dist', 'environment.php.dist');
    this.copy('README.md', 'README.md');
    this.directory('app');
    this.directory('bin');
    this.directory('public');
    this.directory('src');
    this.directory('tests');
  },

  writing: function() {
    this.config.save();
  },

  install: function () {
    if(!this.options['skip-install']) {
      this.spawnCommand('composer', ['self-update']);
      this.spawnCommand('composer', ['install']);
    }
  }
});
