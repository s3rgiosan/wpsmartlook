/* global module require process */
module.exports = function(grunt) {
  var path = require("path");

  require("load-grunt-config")(grunt, {
    configPath: path.join(process.cwd(), "grunt/config"),
    jitGrunt: {
      customTasksDir: "grunt/tasks",
      staticMappings: {
        makepot: "grunt-wp-i18n"
      }
    },
    data: {
      i18n: {
        author: "Sérgio Santos",
        support: "https://s3rgiosan.com/",
        pluginSlug: "wpsmartlook",
        mainFile: "wpsmartlook",
        textDomain: "wpsmartlook",
        potFilename: "wpsmartlook"
      },
      badges: {
        packagist_stable:
          "[![Latest Stable Version](https://poser.pugx.org/s3rgiosan/wpsmartlook/v/stable)](https://packagist.org/packages/s3rgiosan/wpsmartlook)",
        packagist_downloads:
          "[![Total Downloads](https://poser.pugx.org/s3rgiosan/wpsmartlook/downloads)](https://packagist.org/packages/s3rgiosan/wpsmartlook)",
        packagist_license:
          "[![License](https://poser.pugx.org/s3rgiosan/wpsmartlook/license)](https://packagist.org/packages/s3rgiosan/wpsmartlook)",
        codacy_grade:
          "[![Codacy Badge](https://api.codacy.com/project/badge/Grade/59b57a96cc6340ec8ceb65a3fea6f639)](https://www.codacy.com/app/s3rgiosan/wpsmartlook?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=s3rgiosan/wpsmartlook&amp;utm_campaign=Badge_Grade)",
        codeclimate_grade:
          "[![Code Climate](https://codeclimate.com/github/s3rgiosan/wpsmartlook/badges/gpa.svg)](https://codeclimate.com/github/s3rgiosan/wpsmartlook)"
      }
    }
  });
};
