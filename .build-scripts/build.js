const zipArchive = require( 'adm-zip' );
const { sync: glob } = require( 'fast-glob' );
const { dirname } = require( 'path' );
const { stdout } = require( 'process' );
var config = require('../package.json');
const exportWithVersion = false;

var zipDirName = `${config.name}`;
if ( exportWithVersion ) {
    zipDirName = `${config.name}-${config.version}`;
}
var zipFileName = `${zipDirName}-${config.version}.zip`;

const zip = new zipArchive();

let files = glob(
    [
        'assets/dist/**',
        'blocks/dist/**',
        'includes/**',
        'templates/**',
        'vendor/**',
        'composer.json',
        'package.json',
        'Readme.*',
    ],
    {
        caseSensitiveMatch: false,
    }
);


files.forEach( ( file ) => {
    stdout.write( `  Adding \`${ file }\`.\n` );
    const zipDirectory = dirname( file );
    zip.addLocalFile( file, zipDirectory !== '.' ? `${zipDirName}/${zipDirectory}` : zipDirName );
} );

zip.writeZip( `../${zipFileName}` );
stdout.write( `\nDone. \`${zipFileName}\` is ready! 🎉\n` );