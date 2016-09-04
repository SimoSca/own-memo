
var gulp = require('gulp'),
    // phpunit = require('gulp-phpunit'),
    path = require('path');
var bs = require('browser-sync').create(); // create a browser sync instance.
// system execution
var exec = require('child_process').exec,
    sys = require('sys');
function puts(error, stdout, stderr) { sys.puts(stdout) }

// server
var proxyTarget = "localhost:8888/joomlagram";
var serverPort =  3003;

// start proxy server to inject websocket to autoload and dispatch message
// NB: proxy is on port 3003
// NB2: with this configs, browser-sync works only if the page contains a "body" tag
gulp.task('browser-sync', function() {
    console.log('Init Server localhost:' + serverPort + ' , proxy to ' + proxyTarget);
    bs.init({
        port: serverPort,
        proxy:{
            target: proxyTarget, // can be [virtual host, sub-directory, localhost with port]
            ws: true // enables websockets
        }
    });
});


// Monitor over php files
// TODO:
// add check on test file, and if not exists then generate it and directory
// add automatic template to this file
gulp.task('phpunit', function(){
    gulp.watch('**/*.php').on('change', function(file){
        if(file.path.match(/\.php$/)){
            // stip relative path
            var s = __dirname + '(.+)\.php';
            // path to regex
            s = s.replace(/\\+/g, '\\\\');
            var r = new RegExp(s);
            var relSrc = file.path.match(r);
            // var dest = path.join(__dirname, 'Test', relSrc[1], '.php');
            // console.log('Run test over ' + relSrc);
            var dest = path.join('test', relSrc[1]+'Test.php');
            var cmd = "phpunit --bootstrap vendor\\autoload.php " + dest;
            // console.log(file.path);
            // console.log(cmd);
            exec(cmd, puts)

            bs.reload()
        }
    });
});


// start all
gulp.task('watch', ['browser-sync', 'phpunit']);
