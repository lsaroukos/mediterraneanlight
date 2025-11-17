const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { resolve } = require("path");
const { ROOT_DIR } = require('./utils');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const fs = require('fs');

module.exports = (env, argv) => {
    const dist = resolve(ROOT_DIR, 'blocks/dist/' + env.OUTPUT_DIR);
    const src = resolve(ROOT_DIR, 'blocks/src/' + env.OUTPUT_DIR);

    // Remove the interactivityRules completely - not needed
    // WordPress packages are already transpiled
    
    let extra_rules = [];
    if (process.env.FRONTEND_SCRIPT != null) {
        let fname = process.env.FRONTEND_SCRIPT.replace('file:', '');
        extra_rules.push({
            test: resolve(src, fname),
            use: {
                loader: 'babel-loader',
                options: {
                    presets: [
                        '@babel/preset-env', 
                        ['@babel/preset-react', { 
                            "runtime": "automatic",
                            "importSource": "@wordpress/element"
                        }]
                    ],
                }
            }
        });
    }

    const entry = {
        index: resolve(process.env.WP_SRC_DIRECTORY, 'index.js'),
    };


    const viewPath = resolve(process.env.WP_SRC_DIRECTORY, 'view.js');
    if (fs.existsSync(viewPath)) {
        entry.view = viewPath;
    }

    return {
        ...defaultConfig,
        mode: env.NODE_ENV === 'production' ? 'production' : 'development',
        entry,
        output: {
            filename: '[name].js',
            path: dist,
            library: {
                type: 'module',
            },
        },
        experiments: {
            outputModule: true,
        },
        plugins: [
            ...defaultConfig.plugins.filter(plugin => 
                plugin.constructor.name !== 'RtlCssPlugin' && 
                plugin.constructor.name !== 'MiniCssExtractPlugin'
            ),
            new MiniCssExtractPlugin({
                filename: '[name].css',
            }),
            new CopyWebpackPlugin({
                patterns: [
                    {
                        from: '**/*.json',
                        context: process.env.WP_SRC_DIRECTORY,
                        to: dist,
                        noErrorOnMissing: true,
                    }
                ]
            }),
        ],
        module: {
            // Use default WordPress rules + your extra rules only
            rules: [
                ...defaultConfig.module.rules,
                ...extra_rules
            ]
        },
        resolve: {
            ...defaultConfig.resolve,
            alias: {
                ...defaultConfig.resolve.alias,
                // REMOVE the interactivity alias
                // Use WordPress's React
                'react': resolve(ROOT_DIR, 'node_modules/@wordpress/element/react'),
                'react-dom': resolve(ROOT_DIR, 'node_modules/@wordpress/element/react-dom')
            }
        },
        externals: {
            // Only define externals for dependencies provided by WordPress
            react: 'React',
            'react-dom': 'ReactDOM',
            '@wordpress/interactivity': ['wp', 'interactivity']
        },
        watch: false,
        watchOptions: {
            aggregateTimeout: 600,
            ignored: /node_modules/,
        },
    };
};