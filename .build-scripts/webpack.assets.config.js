const MiniCSSExtractPlugin = require('mini-css-extract-plugin');    //creates a CSS file per JS file which contains CSS
const { ROOT_DIR } = require('./utils');
const {resolve} = require('path');


/**
 * set of instructions defining how each module should be treated
 */
const moduleRules = [
    {
        test: /\.js$/,  //matches all js files
        exclude: /node_modules/,    //excludes fils in node_modules dir
        use: {
            loader: 'babel-loader',
            options: {
                presets: ['@babel/preset-env',  ['@babel/preset-react', {"runtime": "automatic"}]],
            }
        },
    },

  /*  {
        test: /\.css$/,     //select all css files
        use: [
            MiniCSSExtractPlugin.loader,   // Extracts CSS into a separate file
            'css-loader',    // Translates CSS into CommonJS modules
        ],
    },*/
    {
        test: /\.(scss|css)$/,    //select all scss files
        use: [
            MiniCSSExtractPlugin.loader, // Extracts SCSS to CSS
            'css-loader', // Handles the CSS
            {
                loader: 'postcss-loader',
                options: {
                    postcssOptions: {
                        plugins: [
                            require('tailwindcss'),
                            require('autoprefixer'),
                        ],
                    },
                },
            },
            'sass-loader', // Transforms SCSS to CSS
        ]
    },
    {
        test: /\.(png|jpe?g|gif|svg)$/i,
        type: 'asset/resource'
    },

];

/**
 * enables caching to improve build performance by saving intermediate build results to disk.
 */
const cacheConfig = {
    version: '1.0.0', //change the version to invalidate the cache
    type: 'filesystem',
    allowCollectingMemory: true,
    cacheDirectory: resolve(ROOT_DIR, '.cache'),
    hashAlgorithm: 'sha256',
    compression: 'gzip',
    buildDependencies: {
        // This makes all dependencies of this file - build dependencies
        config: [__filename],
        // By default webpack and loaders are build dependencies
    },
};

/**
 * Process the webpack config object
 *
 * @type {import('webpack').Configuration}
 * @param env   arguments passed inside the package.json
 * @param argv  arguments passed from the command line
 * @returns {{mode: string, output: {path: *}, entry: {main: string}, cache: {allowCollectingMemory: boolean, buildDependencies: {config: [string]}, cacheDirectory: *, type: string, compression: string, version: string, hashAlgorithm: string}, watch: boolean, plugins: [MiniCssExtractPlugin], module: {rules: [{test: RegExp, use: {loader: string, options: {presets: [string]}}, exclude: RegExp},{test: RegExp, use: [string,string]},{test: RegExp, use: [string,string,string]}]}, watchOptions: {ignored: RegExp, aggregateTimeout: number}}}
 */
module.exports = (env, argv) => {
    const buildMode = env.NODE_ENV === 'production' ? 'production' : 'development';
    
    const assetsDir = resolve(ROOT_DIR, 'assets');
    const assetsSrc = resolve(assetsDir, 'src');

    //default config
    const config = {
        mode: buildMode,
        entry: {    //add as many entry points as necessary here
            'admin': resolve(assetsSrc,'admin.js'),
            'index': resolve(assetsSrc,'index.js'),
        },
        output: {
            path: resolve(assetsDir, 'dist'),
        },
        module: {
            rules: moduleRules
        },
        cache: cacheConfig,
        plugins: [
            new MiniCSSExtractPlugin({
                filename: '[name].css',
            }),
        ],
        watch: false, //Turn on watch mode. This means that after the initial build, webpack will continue to watch for changes in any of the resolved files.
        watchOptions: { //
            aggregateTimeout: 600,
            ignored: /node_modules/,
        },
        resolve: {
            alias: {
                '@': resolve(assetsSrc), // This will allow you to use "@/..." from assets/src
            },
            extensions: ['.js', '.scss'],
            fallback: {
              "path": require.resolve("path-browserify")
            }
          }
    };

    //export configuration file
    return config;
}