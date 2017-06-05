module.exports = function(grunt) {
    grunt.registerTask('default', 'A test task that writes a directory.', function() {
        grunt.file.mkdir('grunt_test');
    });
};
