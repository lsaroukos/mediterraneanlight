const fs = require("fs");
const {resolve} = require("path");

// root directory of plugin or theme
const ROOT_DIR = resolve(__dirname, '../'); 

/**
 *
 * @param blockList
 * @param options
 * @returns {*}
 */
function getDirectoryNames(blockList, options = {}) {
    return  blockList.filter( (item) => {
        if(options.excludes && options.excludes.length > 0) {
            return item.isDirectory() && options.excludes.indexOf(item.name) === -1;
        }

        return item.isDirectory();
    }).map( (item) => {
        return item.name;
    });
}

function isProductionMode() {
    return process.env.NODE_ENV === 'production';
}


/**
 * Logs passed messages to output files
 * 
 * @param {string} message
 * @param {string} type 
 * @param {string} level 
 */
function log(message, type = 'block', level = 'info') {
    try {
        // Define the logs directory
        const logDir = resolve(ROOT_DIR, '.logs');

        // Ensure the logs directory exists
        if (!fs.existsSync(logDir)) {
            fs.mkdirSync(logDir, { recursive: true });
        }

        // Define the log file path based on log level
        let logFilePath = resolve(logDir, `${type}-${level}.log`);
        if (level === 'info') {
            logFilePath = resolve(logDir, `${type}.log`);
        }

        // Add timestamp to the message
        const timestamp = new Date().toISOString(); //e.g. 2025-02-13T10:36:17.013Z
        const logMessage = `[${timestamp}] ${message}\n`;

        // Append the message to the log file
        fs.writeFileSync(logFilePath, logMessage, { encoding: 'utf8', flag: 'a+' });

    } catch (err) {
        console.error(`Failed to write log: ${err.message}`);
    }
}

/**
 * Export functions
 * @type {{getDirectoryNames: (function(*, {}=): *)}}
 */
module.exports = {
    ROOT_DIR,
    getDirectoryNames,
    isProductionMode,
    log
};