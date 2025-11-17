const fs = require('fs');
const {exec} = require('child_process');
const {resolve} = require('path');
const {getDirectoryNames, log, ROOT_DIR} = require('./utils');


const blocksDir = resolve(ROOT_DIR, 'blocks/src');

//get all directory names contained in the blocks/src/ folder, except from 'common'
const blockList = getDirectoryNames(fs.readdirSync(blocksDir, {
    encoding: 'utf8',
    withFileTypes: true
}), {
    excludes: ['common']
});

const blocksDist = resolve(ROOT_DIR, 'blocks/dist');    //output directory

/**
 * 
 * Checks if there are both editorScript and script entries in the given block.json file
 * and returns the name of that script
 * 
 * @param {*} block_name
 * 
 * @return string script name
 */
function getFrontEndScriptName( block_name ){
    //get full src path
    src = resolve(blocksDir, block_name);

    //read from block.json
    blockJSON = fs.readFileSync(resolve(src, 'block.json'), 'utf8');

    // Parse the JSON data and store it in a constant
    const jsonData = JSON.parse(blockJSON);

    //if there are both editorScript and script entries in the given block.json file
    if( jsonData!==null && 
        jsonData.hasOwnProperty('editorScript') && jsonData.hasOwnProperty('script') &&
        jsonData.editorScript !== jsonData.script
    ){
        return jsonData.script;
    }
    else '';
}

// For each block name (as found in the blocks/src/ folder)
blockList.forEach(async (block) => {

    //get the name of the front-end script
    frontend_js = getFrontEndScriptName( block );

    //form output directory
    const current_output_dir = resolve(blocksDist, block );
    const current_src = resolve(ROOT_DIR,`blocks/src/${block}`);

    const commands = {
        production: `node ./node_modules/.bin/webpack --config ./.build-scripts/webpack.block.config.js --env OUTPUT_DIR=${current_output_dir} NODE_ENV=production`,
        development: `node ${ROOT_DIR}/node_modules/.bin/webpack --config ${ROOT_DIR}/.build-scripts/webpack.block.config.js --env OUTPUT_DIR=${block} NODE_ENV=development`,
    };

    //add custom environment variables
    const env = {
        WP_SRC_DIRECTORY: resolve(ROOT_DIR,`blocks/src/${block}`)
    };

    if( frontend_js!=="" ){
        
        env.FRONTEND_SCRIPT = frontend_js;
    }


    exec( commands.development , {
        timeout: 0, //No execution time limit.
        cwd: resolve(__dirname, '../'), //Runs the command one level above the script’s directory.
        env: env,
    }, (error, stdout, stderr) => {

        if (error) {
            console.error(`exec error: ${error}`);  //print message on console
            log(error.toString(), 'block', 'error');//log error to file
            return;
        }

        log(stdout, 'block', 'info');   //log command output to block.log file
        log(stderr, 'block', 'error');  //log command error to block-error.log file


        console.log(`Block: ${block} compiled successfully`);
    });
});
